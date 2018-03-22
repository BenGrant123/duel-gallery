<?php
/**
 * Widthrule
 *
 * @category Widthrule
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Model\ResourceModel;

/**
 * Widthrule
 *
 * @category Widthrule
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Widthrule extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('duel_widthrule', 'entity_id');
    }
}
