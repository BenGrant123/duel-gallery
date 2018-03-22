<?php
/**
 * DuelRowsAndColumns File Doc Comment
 *
 * @category DuelRowsAndColumns
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Model\Product\Attribute\Source;

/**
 * DuelRowsAndColumns Class Doc Comment
 *
 * @category DuelRowsAndColumns
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class DuelRowsAndColumns extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    private $pageLayoutBuilder;

    /**
     * Constructor function
     *
     * @param BuilderInterface $pageLayoutBuilder Page Layout Builder
     *
     * @return void
     */
    public function __construct(\Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder)
    {
        $this->pageLayoutBuilder = $pageLayoutBuilder;
    }

    /**
     * Return an array with all the relevant options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $arr = [
            '0'=>'Use default',
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
            '11' => '11',
            '12' => '12'
        ];

        $ret = [];
        foreach ($arr as $key => $value) {
            $ret[] = ['value' => $key, 'label' => $value];
        }

        return $ret;
    }
}
