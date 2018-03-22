<?php

namespace Duel\Gallery\Controller\Adminhtml\Widthrule;

use Magento\Backend\App\Action\Context;
use Duel\Gallery\Model\WidthruleFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Update extends \Magento\Backend\App\Action
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

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $widthrule = $this->widthruleFactory->create();
        $params = $this->getRequest()->getParams();
        $ruleId = $params['update_rule'];

        if ($params['minimum_width_' . $ruleId]['value'] < 100) {
            $this->messageManager->addError('Rule not added; minimum screen width for a rule to take effect is 100px.');
            return $this->_redirect('adminhtml/system_config/edit/section/settings');
        }

        $entry = [
            'minimum_width' => $params['minimum_width_' . $ruleId]['value'],
            'rows' => $params['rows_' . $ruleId]['value'],
            'columns' => $params['columns_' . $ruleId]['value'],
            ];
        
        try {
            $widthrule->load($params['update_rule'])->addData($entry)->save();
            $this->messageManager->addSuccess('CSS Rule updated.');
        } catch (\Exception $e) {
            $this->messageManager->addError('CSS Rule could not be saved.');
        }
            
        return $this->_redirect('adminhtml/system_config/edit/section/settings');
    }
}
