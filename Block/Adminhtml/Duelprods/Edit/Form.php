<?php

namespace Duel\Gallery\Block\Adminhtml\Duelprods\Edit;
 
use Duel\Gallery\Model\Config\Source\DuelRowsAndColumns;
use Duel\Gallery\Model\Config\Source\DuelPagePositions;
use Duel\Gallery\Block\Color;
use Magento\Framework\Registry;
 
/**
 * Adminhtml Mass edit products.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\FormFactory $formFactory,
        DuelRowsAndColumns $rowsAndColumns,
        DuelPagePositions $pagePositions,
        Color $colorField,
        Registry $registry,
        array $data = []
    ) {
        $this->rowsAndColumns = $rowsAndColumns;
        $this->pagePositions = $pagePositions;
        $this->colorField = $colorField;
        $this->registry = $registry;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Prepare form.
     *
     * @return $this
     */
    public function _prepareForm()
    {
        $bulkItems = $this->registry->registry('bulkItems');
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
            ['legend' => __('Bulk edit products'), 'class' => 'fieldset-wide']
        );
        
        $fieldset->addField(
            'bulk_edit_ids',
            'hidden',
            [
                  'name'  => 'bulk_edit_ids',
                  'required' => false,
            ]
        );
        $colorField1 = $fieldset->addField(
            'duel_colour',
            'text',
            [
                'name' => 'duel_colour',
                'label' => __('Colour'),
                'id' => 'duel_colour',
                'title' => __('Colour'),
                'class' => 'status',
    
            ]
        );
        $colorField2 = $fieldset->addField(
            'duel_background_colour',
            'text',
            [
                'name' => 'duel_background_colour',
                'label' => __('Background Colour'),
                'id' => 'duel_background_colour',
                'title' => __('Background Colour'),
                'class' => 'status',
            ]
        );
        $colorJs1 = '<script type="text/javascript">
            require(["jquery","jquery/colorpicker/js/colorpicker"], function ($) {
                $(document).ready(function () {
                    var $el = $("#duel_colour");
                    $el.ColorPicker({
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                        }
                    });
                });
            });
            </script>';
        $colorJs2 = '<script type="text/javascript">
            require(["jquery","jquery/colorpicker/js/colorpicker"], function ($) {
                $(document).ready(function () {
                    var $el = $("#duel_background_colour");
                    $el.ColorPicker({
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                        }
                    });
                });
            });
            </script>';
        $colorField1->setAfterElementHtml($colorJs1);
        $colorField2->setAfterElementHtml($colorJs2);
        $fieldset->addField(
            'duel_rows',
            'select',
            [
                'name' => 'duel_rows',
                'label' => __('Rows'),
                'id' => 'duel_rows',
                'title' => __('Rows'),
                'class' => 'status',
                'values' => ['-1' => 'Don\'t update this attribute'] + $this->rowsAndColumns->toOptionArray()
            ]
        );
        $fieldset->addField(
            'duel_columns',
            'select',
            [
                'name' => 'duel_columns',
                'label' => __('Columns'),
                'id' => 'duel_columns',
                'title' => __('Columns'),
                'class' => 'status',
                'values' => ['-1' => 'Don\'t update this attribute'] + $this->rowsAndColumns->toOptionArray()
            ]
        );

        $fieldset->addField(
            'duel_page_position',
            'select',
            [
                'name' => 'duel_page_position',
                'label' => __('Position gallery on product page'),
                'id' => 'duel_columns',
                'title' => __('Columns'),
                'class' => 'status',
                'values' => ['-1' => 'Don\'t update this attribute'] + $this->pagePositions->toOptionArray()
            ]
        );
        $colorField2 = $fieldset->addField(
            'duel_page_position_custom',
            'text',
            [
                'name' => 'duel_page_position_custom',
                'label' => __('Position gallery by custom CSS selector'),
                'id' => 'duel_background_colour',
                'title' => __('Background Colour'),
                'class' => 'status',
            ]
        );
        $boolArray = [
            '-1' => 'Don\'t update this attribute',
            '0' => 'Disabled',
            '1' => 'Enabled',

        ];
        $fieldset->addField(
            'duel_is_active',
            'select',
            [
                'name' => 'duel_is_active',
                'label' => __('Show gallery on product page'),
                'id' => 'duel_is_active',
                'title' => __('Show gallery on product page'),
                'class' => 'status',
                'values' => $boolArray
            ]
        );
        $fieldset->addField(
            'duel_email_enabled',
            'select',
            [
                'name' => 'duel_email_enabled',
                'label' => __('Enable Duel post-purchase email'),
                'id' => 'duel_email_enabled',
                'title' => __('Enable Duel post-purchase email'),
                'class' => 'status',
                'values' => $boolArray
            ]
        );
        $startValues = [
            'duel_rows' => '-1',
            'duel_columns' => '-1',
            'duel_page_position' => '-1',
            'duel_is_active' => '-1',
            'duel_email_enabled' => '-1',
            'duel_colour' => 'Don\'t update this attribute',
            'duel_background_colour' => 'Don\'t update this attribute',
            'duel_page_position_custom' => 'Don\'t update this attribute',
            'bulk_edit_ids' => json_encode($bulkItems),
        ];
        $form->setValues($startValues);
        $form->setUseContainer(true);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
}
