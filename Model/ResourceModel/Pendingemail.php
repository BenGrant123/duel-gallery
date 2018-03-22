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
namespace Duel\Gallery\Model\ResourceModel;

/**
 * PendingEmail
 *
 * @category PendingEmail
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Pendingemail extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context Context
     */
    
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('duel_pending_email', 'entity_id');
    }
}
