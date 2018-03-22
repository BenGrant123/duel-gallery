<?php

namespace Duel\Gallery\Controller\Adminhtml\Widthrule;

use Magento\Backend\App\Action\Context;
use Duel\Gallery\Model\WidthruleFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Add extends \Magento\Backend\App\Action
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
        $currentRules = $widthrule->getCollection();

        if ($currentRules->getSize() > 3) {
            $this->messageManager->addError('Max. 4 rules - Please delete at least one existing rule before adding a new one.');
            return $this->_redirect('adminhtml/system_config/edit/section/settings');
        }

        $params = $this->getRequest()->getParams();
          
        if ($params['addrule_minimum_width']['value'] < 100) {
            $this->messageManager->addError('Rule not added; minimum screen width for a rule to take effect is 100px.');
            return $this->_redirect('adminhtml/system_config/edit/section/settings');
        }
        
        $entry = [
        'minimum_width' => $params['addrule_minimum_width']['value'],
        'rows' => $params['addrule_rows']['value'],
        'columns' => $params['addrule_columns']['value']
        ];
    
        try {
            $widthrule->setData($entry)->save();
        } catch (\Exception $e) {
            // Mage::log($e->getMessage(), null, "system.log");
        }
    
        $this->messageManager->addSuccess('CSS Rule added.');
        return $this->_redirect('adminhtml/system_config/edit/section/settings');
    }
}
