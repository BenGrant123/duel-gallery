<?php
/**
 * Pendingemail
 *
 * @category Pendingemail
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Model;

/**
 * Pendingemail
 *
 * @category Pendingemail
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Pendingemail extends \Magento\Framework\Model\AbstractModel
{
 
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Duel\Gallery\Model\ResourceModel\Pendingemail');
    }
}
