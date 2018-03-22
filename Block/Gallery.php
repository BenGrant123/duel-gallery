<?php
/**
 * Gallery File Doc Comment
 *
 * @category Gallery
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Block;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Duel\Gallery\Model\WidthruleFactory;

/**
 * Gallery Class Doc Comment
 *
 * @category Gallery
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Gallery extends Template
{
    /**
     * The product
     *
     * @var Product
     */
    private $product;

    /**
     * Construct function to inject necessary arguments
     *
     * @param WidthruleFactory $widthrruleFactory Width Rule Factory
     * @param Context      $context      Context
     * @param Registry     $registry     Registry
     * @param array        $data         Data
     */
    public function __construct(
        WidthruleFactory $widthruleFactory,
        Template\Context $context,
        Registry $registry,
        array $data
    ) {
        $this->widthruleFactory = $widthruleFactory;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }
    
    /**
     * Gets the product for this product page if available
     *
     * @return Product
     */
    private function getProduct()
    {
        if ($this->product === null) {
            $this->product = $this->registry->registry('product');

            if (!$this->product->getId()) {
                throw new LocalizedException(__('Failed to initialize product'));
            }
        }

        return $this->product;
    }

    /**
     * Gets the gallery info for this product and returns it to the calling template file
     *
     * @return $gallery
     */
    public function getGallery()
    {
        $brandId =  $this->_scopeConfig->getValue('settings/emails/duel_brand_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sku =  $this->getProduct()->getData('sku');
        
        $duelDefaults = [];
        $result = [];

        $showGalleries = $this->_scopeConfig
        ->getValue('settings/defaults/show_galleries', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $showGallery = $this->getProduct()->getData('duel_is_active');
        

        if ($showGalleries == false || $showGallery == false || !$sku || !$brandId) {
            $result['active'] = false;
            return json_encode($result);
        } else {
            $result['active'] = true;
        }

        $duelDefaults['colour'] = $this->_scopeConfig
        ->getValue('settings/defaults/duel_colour', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $duelDefaults['background_colour'] = $this->_scopeConfig
        ->getValue('settings/defaults/duel_background_colour', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        $result['default_page_position'] = $this->_scopeConfig
        ->getValue('settings/defaults/duel_page_position', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $result['default_page_position_custom'] = $this->_scopeConfig
        ->getValue('settings/defaults/duel_page_selector', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $defaultRows = $this->_scopeConfig
        ->getValue('settings/defaults/duel_rows', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $defaultColumns = $this->_scopeConfig
        ->getValue('settings/defaults/duel_columns', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $defaultRules = [
            'columns' => $defaultColumns ? $defaultColumns : '3',
            'rows' => $defaultRows ? $defaultRows : '12',
        ];

        $productRows = $this->getProduct()->getData('duel_rows');
        $productColumns = $this->getProduct()->getData('duel_columns');

        if ($productColumns) {
            $defaultRules['columns'] = $productColumns;
          }
        if ($productRows) {
            $defaultRules['rows'] = $productRows;
        }

        $cssRules = $this->getWidthRules();

        $result['layout_rules'] = [];

        if ($cssRules And !$productColumns And !$productRows) {
            $result['layout_rules'] = $cssRules;
        }

        array_push($result['layout_rules'], $defaultRules);
        
        $result['product'] = $brandId . '/' . $sku;
        $result['colour'] = $this->getProduct()->getData('duel_colour');
        $result['background_colour'] = $this->getProduct()->getData('duel_background_colour');
        $result['page_position'] = $this->getProduct()->getData('duel_page_position');
        $result['page_position_custom'] = $this->getProduct()->getData('duel_page_position_custom');
        
        if ($result['page_position_custom'] == 'N/A') {
            $result['page_position_custom'] = null;
        }

        if (empty($result['colour']) or $result['colour'] == 'Use default') {
            $result['colour'] = empty($duelDefaults['colour']) ? "#000000" : $duelDefaults['colour'];
        }
        if (empty($result['background_colour']) or $result['background_colour'] == 'Use default') {
            $result['background_colour'] = empty($duelDefaults['background_colour'])
            ? "#ffffff"
            : $duelDefaults['background_colour'];
        }
        
        $gallery = json_encode($result);

        return $gallery;
    }

    protected function getWidthRules() {
        $widthRules = $this->widthruleFactory->create()->getCollection()->setOrder('minimum_width', 'DESC');;
        $layoutRules = [];
        if ($widthRules) {
            foreach ($widthRules as $widthrule) {
                $row =[];
                $row['mediaQuery'] = '(min-width: ' . $widthrule->getData('minimum_width') . 'px)';
                $row['columns'] = $widthrule->getData('columns') ? $widthrule->getData('columns') : '3';
                $row['rows'] = $widthrule->getData('rows') ? $widthrule->getData('rows') : '12';
                array_push($layoutRules, $row);
            }
        }
        return $layoutRules;
    }
}
