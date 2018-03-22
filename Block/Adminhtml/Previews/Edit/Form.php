<?php

namespace Duel\Gallery\Block\Adminhtml\Previews\Edit;
 
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Prepare form.
     *
     * @return $this
     */
    public function _prepareForm()
    {
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form',
                            'enctype' => 'multipart/form-data',
                            'action' => $this->getData('action'),
                            'method' => 'post'
                        ]
            ]
        );
 
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Send a preview of the Duel follow-up email:'), 'class' => 'fieldset-wide']
        );
        
        
        $colorField1 = $fieldset->addField(
            'preview_email',
            'text',
            [
                'name' => 'preview_email',
                'label' => __('Email Address'),
                'id' => 'preview_email',
                'title' => __('Email Address'),
                'class' => 'status',
                'value' => $this->_scopeConfig
                    ->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
    
            ]
        );
        
        $fieldset->addField(
            'preview_type',
            'select',
            [
                'name' => 'preview_type',
                'label' => __('Preview Type'),
                'id' => 'preview_type',
                'title' => __('Preview Type'),
                'class' => 'status',
                'values' => ['0' => 'Send preview with mock orders', '1' => 'Simulate cron job (use actual order information)'],
                'note' => 'Simulating the cron job will use orders from the "duel_pending_email" table, but send the Duel follow-up test email to the address inputted above.'
            ]
        );
        
        $form->setUseContainer(true);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
}