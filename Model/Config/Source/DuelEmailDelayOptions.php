<?php
/**
 * DuelEmailDelayOptions File Doc Comment
 *
 * @category DuelEmailDelayOptions
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * DuelEmailDelayOptions Class Doc Comment
 *
 * @category DuelEmailDelayOptions
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class DuelEmailDelayOptions implements ArrayInterface
{

    /**
     * Option getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $arr = $this->toArray();
        $ret = [];
        foreach ($arr as $key => $value) {
            $ret[] = [
            'value' => $key,
            'label' => $value
            ];
        }
        return $ret;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $choose = [
        '0'=> '5 days (default)',
        '1' => '4',
        '2' => '3',
        '3' => '2',
        '4' => '1',
        '5' => '0 days (send immediately)',
        ];
        return $choose;
    }
}
