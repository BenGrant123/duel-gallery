<?php

namespace Duel\Gallery\Block\Adminhtml;

use Duel\Gallery\Model\WidthruleFactory;
use Duel\Gallery\Model\Config\Source\DuelRowsAndColumns;
use Magento\Backend\Model\Auth\Session;

class Widthrules extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\View\Helper\Js $jsHelper,
        Session $authSession,
        WidthruleFactory $widthruleFactory,
        DuelRowsAndColumns $rowsAndColumns
    ) {
        $this->_jsHelper = $jsHelper;
        parent::__construct($context, $authSession, $jsHelper);
        $this->widthruleFactory = $widthruleFactory;
        $this->rowsAndColumns = $rowsAndColumns;
    }
    
    public function render(
        \Magento\Framework\Data\Form\Element\AbstractElement $element
    ) {
        
        $html = $this->_getHeaderHtml($element);
        
        $widthRulesCollection = $this->widthruleFactory->create()->getCollection();
        $addRuleUrl = $this->getUrl('gallery/widthrule/add');
        $updateRuleUrl = $this->getUrl('gallery/widthrule/update');
        $removeRuleUrl = $this->getUrl('gallery/widthrule/remove');

        $html.=
        '<script>
            function removeRule (id) {
                console.log(id);
                document.getElementById("remove_rule").value = id;
                document.getElementById("config-edit-form").action = ' . json_encode($removeRuleUrl) . ';
                document.getElementById("config-edit-form").submit();
            }

            function updateRule (id) {
                console.log(id);
                document.getElementById("update_rule").value = id;
                document.getElementById("config-edit-form").action = ' . json_encode($updateRuleUrl) . ';
                document.getElementById("config-edit-form").submit();
            }
            </script>';

        $renderer = $this->getLayout()->createBlock(
            'Magento\Config\Block\System\Config\Form\Field'
        );
            
        foreach ($widthRulesCollection as $widthrule) {
            $html.= $this->getRuleFieldHtml($element, $widthrule, 'minimum_width', 'text', $renderer);
            $html.= $this->getRuleFieldHtml($element, $widthrule, 'rows', 'select', $renderer);
            $html.= $this->getRuleFieldHtml($element, $widthrule, 'columns', 'select', $renderer);
            $html.= $this->getUpdateButtonHtml($element, $widthrule->getId(), $renderer);
            $html.= $this->getRemoveButtonHtml($element, $widthrule->getId(), $renderer);
        }

        $html.= $element->addField('remove_rule', 'hidden', [ 'name'  => 'remove_rule', ])
        ->setRenderer($renderer)->toHtml();

        $html.= $element->addField('update_rule', 'hidden', [ 'name'  => 'update_rule', ])
        ->setRenderer($renderer)->toHtml();

        $html .= $this->_getFooterHtml($element);
        return $html;
    }

    protected function getRuleFieldHtml(
        $fieldset,
        $widthrule,
        $property,
        $type,
        $renderer
    ) {
        $optionModel = 'duel_emails_gallery_config/' . $property;
        $label = $property == 'minimum_width' ? 'Minimum iframe width (px)' : ucfirst($property);
        
        $field = $fieldset->addField($property . '_' . $widthrule->getId(), $type, [
                'name'          => $property. '_' . $widthrule->getId() . '[value]',
                'label'         => $label,
                'values'        => $type == 'select' ? $this->rowsAndColumns->toOptionArray() : '',
                'value'         => $widthrule->getData($property)
            ])->setRenderer($renderer);
        return $field->toHtml();
    }
    
    protected function getUpdateButtonHtml($fieldset, $id, $renderer)
    {

        $field = $fieldset->addField('update_rule_' . $id, 'button', [
                'value' => 'Update',
                'class' => 'form-button',
                'onclick' => 'updateRule(' . $id . ');'
            ])->setRenderer($renderer);
        return $field->toHtml();
    }

    protected function getRemoveButtonHtml($fieldset, $id, $renderer)
    {

        $field = $fieldset->addField('remove_rule' . $id, 'button', [
            'value' => 'Remove',
            'class' => 'form-button',
            'onclick' => 'removeRule(' . $id . ');'
        ])->setRenderer($renderer);
        return $field->toHtml();
    }
}
