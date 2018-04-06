<?php
/**
 * Index File Doc Comment
 *
 * @category Index
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Controller\Duelfeed;

use \Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

/**
 * Index Class Doc Comment
 *
 * @category Index
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Construct function to inject necessary arguments
     *
     * @param Context     $context           Context
     * @param PageFactory $resultPageFactory Result Page Factory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->_storeManager = $storeManager;
        $this->_stockItemRepository = $stockItemRepository;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * Execute function
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $storeUrl = $this->_storeManager->getStore()->getBaseUrl();
        $currency = $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
        $result = $this->resultJsonFactory->create();

        $collection = $this->_collectionFactory->create();
        $collection->addFieldToFilter('duel_feed_enabled', true)
        ->addAttributeToSelect(['entity_id','name','sku','description','product_url','thumbnail','price']);
        $collectionData = $collection->getData();
        
        $productsArray = [];

        if (!empty($collectionData)) {
            foreach ($collectionData as $item) {
                $inStock = $this->_stockItemRepository->get($item['entity_id'])->getIsInStock();
                $product = $this->productRepository->getById($item['entity_id']);
                
                $row = [
                    'sku' => $product->getSku(),
                    'name' => $product->getName(),
                    'description' => strip_tags($product->getShortDescription()),
                    'url' => $product->getProductUrl(),
                    'srcImg' => $storeUrl . 'pub/media/catalog/product' . $product->getData('thumbnail'),
                    'price' => number_format($product->getPrice(), 2),
                    'currency' => $currency,
                    
                ];
                if (!$inStock) {
                    $row['noStock'] = true;
                }
                $productsArray[] = $row;
            }
        }

        $productsObj = (object)[];

        $productsObj->items = $productsArray;
        $json = json_encode($productsObj);
        $checksum = (string) md5($json);
        $etag = 'W/' . '"' . $checksum . '"';
        $reqEtag = (string) $this->getRequest()->getHeader('If-None-Match');
        
        $response = $this->getResponse();
        if ($etag == $reqEtag) {
            $response->clearHeaders()
            ->setHeader('ETag', $etag)
            ->setStatusCode(\Magento\Framework\App\Response\Http::STATUS_CODE_304)
            ->send();
        } else {
            $response
            ->clearHeaders()
            ->setHeader('ETag', $etag)
            ->setHeader('x-Req-ETag', $reqEtag)
            ->setHeader('Content-Type', 'application/json')
            ->setContent($json);
        }
    }
}
