<?php

namespace Duel\Gallery\Controller\Adminhtml\Widthrule;

use Magento\Backend\App\Action\Context;
use Duel\Gallery\Model\WidthruleFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Remove extends \Magento\Backend\App\Action
{

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        WidthruleFactory $widthruleFactory
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->widthruleFactory = $widthruleFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $widthrule = $this->widthruleFactory->create();        
        $params = $this->getRequest()->getParams();
        $widthrule->load($params['remove_rule'])->delete();
      
        $this->messageManager->addSuccess('CSS Rule removed.');
        return $this->_redirect('adminhtml/system_config/edit/section/settings');
    }
}
