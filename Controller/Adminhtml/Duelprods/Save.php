<?php

namespace Duel\Gallery\Controller\Adminhtml\Duelprods;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Action;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
 
class Save extends \Magento\Backend\App\Action
{

    /**
     * @param Context $context
     * @param Builder $productBuilder
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        Action $productAction,
        Registry $registry,
        StoreManagerInterface $storeManager
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productAction = $productAction;
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }
    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $storeId = $this->storeManager->getStore()->getId();
        if (!$data) {
            $this->_redirect('gallery/duelprods/index');
            return;
        }
        $productIds = json_decode($data['bulk_edit_ids']);
        try {
            $values = [
                'duel_rows' => $data['duel_rows'],
                'duel_columns' => $data['duel_columns'],
                'duel_page_position' => $data['duel_page_position'],
                'duel_is_active' => $data['duel_is_active'],
                'duel_email_enabled' => $data['duel_email_enabled'],
                'duel_colour' => $data['duel_colour'],
                'duel_background_colour' => $data['duel_background_colour'],
                'duel_page_position_custom' => $data['duel_page_position_custom'],
            ];
            $values = array_diff($values, ["-1", "Don't update this attribute"]);
            $this->productAction->updateAttributes($productIds, $values, $storeId);
            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('gallery/duelprods/index');
    }
 
    /**
     * Check Category Map permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Duel_Gallery::add_action');
    }
}
