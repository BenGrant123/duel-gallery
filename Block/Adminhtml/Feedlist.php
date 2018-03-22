<?php
/**
 * Feedlist File Doc Comment
 *
 * @category Feedlist
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Block\Adminhtml;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;

/**
 * Feedlist Class Doc Comment
 *
 * @category Feedlist
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Feedlist extends Template
{
    /**
     * The product
     *
     * @var Product
     */
    private $product;

    /**
     * Construct function to inject necessary arguments
     *
     * @param BlockFactory $blockFactory Block Factory
     * @param Context      $context      Context
     * @param Registry     $registry     Registry
     * @param array        $data         Data
     */
    public function __construct(
        \Magento\Cms\Model\BlockFactory $blockFactory,
        Template\Context $context,
        Registry $registry,
        array $data
    ) {
        $this->_blockFactory = $blockFactory;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }
    
    public function getFeedUrl()
    {
        $feedHash = $this->_scopeConfig
        ->getValue('settings/defaults/duel_feed_hash', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $storeUrl = $this->_scopeConfig
        ->getValue('web/secure/base_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $feedUrl = $storeUrl . 'feeds/duel/' . $feedHash;
        return $feedUrl;
    }
}
