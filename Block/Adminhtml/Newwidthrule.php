<?php

namespace Duel\Gallery\Block\Adminhtml;

use Duel\Gallery\Model\WidthruleFactory;
use Duel\Gallery\Model\Config\Source\DuelRowsAndColumns;

class Newwidthrule extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        WidthruleFactory $widthruleFactory,
        DuelRowsAndColumns $rowsAndColumns,
        array $data = []
    ) {
        $this->_jsHelper = $jsHelper;
        $this->_authSession = $authSession;
        parent::__construct($context, $authSession, $jsHelper);
        $this->widthruleFactory = $widthruleFactory;
        $this->rowsAndColumns = $rowsAndColumns;
    }
    
    public function render(
        \Magento\Framework\Data\Form\Element\AbstractElement $element
    ) {
        
        $html = $this->_getHeaderHtml($element);
        $addRuleUrl = $this->getUrl('gallery/widthrule/add');

        $html .= '<script>
            function getAddRuleUrl () {
                document.getElementById("config-edit-form").action = ' . json_encode($addRuleUrl) . ';
                document.getElementById("config-edit-form").submit();
            }
        </script>';
            
        $html.= $this->getRuleFieldHtml($element, 'minimum_width');
        $html.= $this->getRuleFieldHtml($element, 'rows', 'select');
        $html.= $this->getRuleFieldHtml($element, 'columns', 'select');
        $html .= $this->getAddButtonHtml($element);
        $html .= $this->_getFooterHtml($element);
        return $html;
    }

    protected function getRuleFieldHtml($fieldset, $property, $type = 'text')
    {
        $optionModel = 'duel_emails_gallery_config/' . $property;
        $label = $property == 'minimum_width' ? 'Minimum iframe width (px)' : ucfirst($property);
        $renderer = $this->getLayout()->createBlock(
            'Magento\Config\Block\System\Config\Form\Field'
        );

        $field = $fieldset->addField('add_widthrule_' . $property, $type,
            [
                'name'          => 'addrule_'.$property.'[value]',
                'label'         => $label,
                'values'        => $type == 'select' ? $this->rowsAndColumns->toOptionArray() : '',
            ])->setRenderer($renderer);
        return $field->toHtml();
    }
    
    protected function getAddButtonHtml ($fieldset) {
        $renderer = $this->getLayout()->createBlock(
            'Magento\Config\Block\System\Config\Form\Field'
        );

        $field = $fieldset->addField('add_rule', 'button', array(
            'value' => 'Add CSS width rule',
            'name'  => 'add_rule',
            'class' => 'form-button',
            'onclick' => 'getAddRuleUrl();',
            'renderer' => ''
        ))->setRenderer($renderer);
        return $field->toHtml();
    }

}
