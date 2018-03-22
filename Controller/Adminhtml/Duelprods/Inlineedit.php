<?php
/**
 * InlineEdit File Doc Comment
 *
 * @category InlineEdit
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Controller\Adminhtml\Duelprods;

use Psr\Logger\LoggerInterface;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Action;
use Magento\Framework\Model\ResourceModel\Iterator;
use Magento\Catalog\Model\ProductRepository;

/**
 * InlineEdit Class Doc Comment
 *
 * @category InlineEdit
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Inlineedit extends \Magento\Backend\App\Action
{
    /**
     * Construct function to inject necessary arguments
     *
     * @param Context           $context           Context
     * @param Filter            $filter            Filter
     * @param CollectionFactory $collectionFactory Collection Factory
     * @param array             $data              Data
     */
    public function __construct(
        Context $context,
        Filter $filter,
        ProductRepository $productRepository,
        Iterator $iterator,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->_iterator = $iterator;
        $this->_productRepository = $productRepository;
        parent::__construct($context);
    }
 
    /**
     * Execute function
     *
     * @return void
     */
    public function execute()
    {
        $collection = $this->_collectionFactory->create();
        $collection->addAttributeToSelect(['duel_gallery_id']);
        $data = $this->getRequest()->getPostValue();
        
        $walkItems = $data['items'];
        if (!$walkItems) {
            $this->_redirect('gallery/duelprods/index');
            return;
        }

        foreach ($walkItems as $key => $value) {
            try {
                $currentEdit = $value;
                if (isset($currentEdit['duel_gallery_id']) and $currentEdit['duel_gallery_id'] == '') {
                    $currentEdit['duel_gallery_id'] = 'No gallery selected';
                }
                if (isset($currentEdit['duel_colour']) and $currentEdit['duel_colour'] == '') {
                    $currentEdit['duel_colour'] = 'Use default';
                }
                if (isset($currentEdit['duel_background_colour'])
                and $currentEdit['duel_background_colour'] == '') {
                    $currentEdit['duel_background_colour'] = 'Use default';
                }
                if (isset($currentEdit['duel_page_position_custom'])
                and $currentEdit['duel_page_position_custom'] == '') {
                    $currentEdit['duel_page_position_custom'] = 'N/A';
                }
                $product = $this->_productRepository->getById($key);
                $product->setData($currentEdit);
                $this->_productRepository->save($product);
            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
            }
        }
        
        $saveResponse = ["error" => null];
        print_r(json_encode($saveResponse));
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Duel_Gallery::add_action');
    }
}
