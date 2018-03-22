<?php
/**
 * DuelPagePositions File Doc Comment
 *
 * @category DuelPagePositions
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Model\Product\Attribute\Source;

/**
 * DuelPagePositions Class Doc Comment
 *
 * @category DuelPagePositions
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class DuelPagePositions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
            '0' => 'Use default',
            '1' => 'Above product info',
            '2' => 'Below product info',
            '3' => 'Below product media',
            '4' => 'Below Add-To-Cart button'
        ];

        $ret = [];
        foreach ($arr as $key => $value) {
            $ret[] = ['value' => $key, 'label' => $value];
        }

        return $ret;
    }
}
