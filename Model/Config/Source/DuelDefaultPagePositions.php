<?php
/**
 * DuelDefaultPagePositions File Doc Comment
 *
 * @category DuelDefaultPagePositions
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * DuelDefaultPagePositions Class Doc Comment
 *
 * @category DuelDefaultPagePositions
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class DuelDefaultPagePositions implements ArrayInterface
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
            '1' => 'Below Add-To-Cart button',
            '2' => 'Above product info',
            '3' => 'Below product info',
            '4' => 'Below product media',
        ];
        return $choose;
    }
}
