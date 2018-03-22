<?php

namespace Duel\Gallery\Controller\Adminhtml\Feedlist;

use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Action;

class Massadd extends \Magento\Catalog\Controller\Adminhtml\Product
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Builder $productBuilder
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Builder $productBuilder,
        Filter $filter,
        CollectionFactory $collectionFactory,
        Action $productAction
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productAction = $productAction;
        parent::__construct($context, $productBuilder);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $productIds = $collection->getAllIds();
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        $addedToFeed = 0;
        $this->productAction->updateAttributes($productIds, ['duel_feed_enabled' => true], $storeId);
        
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been added to the Feed.', count($productIds))
        );

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('gallery/feedlist/index');
    }
}
