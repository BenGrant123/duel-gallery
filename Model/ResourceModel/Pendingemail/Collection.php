<?php
/**
 * Pendingemail File Doc Comment
 *
 * @category Collection
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Model\ResourceModel\Pendingemail;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Pendingemail Class Doc Comment
 *
 * @category Collection
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Collection extends AbstractCollection
{
    /**
     * Constructor function
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Duel\Gallery\Model\Pendingemail',
            'Duel\Gallery\Model\ResourceModel\Pendingemail'
        );
    }
}
