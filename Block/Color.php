<?php
/**
 * Color File Doc Comment
 *
 * @category Color
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Block;

/**
 * Color Class Doc Comment
 *
 * @category Color
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Color extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * Provides jquery color picker for form field
     *
     * @param \Magento\Backend\Block\Template\Context $context Context
     * @param array                                   $data    data
     */

    /**
     * Gets the element HTML and adds color picker to it
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Element
     *
     * @return $html
     */
    protected function _getElementHtml(
        \Magento\Framework\Data\Form\Element\AbstractElement $element
    ) {
        
        $html = $element->getElementHtml();
        $value = $element->getData('value');

        $html .= '<script type="text/javascript">
            require(["jquery","jquery/colorpicker/js/colorpicker"], function ($) {
                $(document).ready(function () {
                    var $el = $("#' . $element->getHtmlId() . '");
                    $el.css("backgroundColor", "'. $value .'");

                    // Attach the color picker
                    $el.ColorPicker({
                        color: "'. $value .'",
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                        }
                    });
                });
            });
            </script>';
        return $html;
    }
}
