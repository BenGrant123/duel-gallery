<?php
/**
 * SalesOrderShipmentSaveAfter File Doc Comment
 *
 * @category SalesOrderShipmentSaveAfter
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Mail\Template\TransportBuilder;
use Duel\Gallery\Model\PendingemailFactory;

/**
 * SalesOrderShipmentSaveAfter Class Doc Comment
 *
 * @category SalesOrderShipmentSaveAfter
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class SalesOrderShipmentSaveAfter implements ObserverInterface
{

    private $pendingEmailFactory;

    /**
     * Construct function to inject necessary arguments
     *
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager        Store Manager
     * @param \Magento\Sales\Model\Order                         $_order              Order
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig         Scope Config
     * @param PendingEmailFactory                                $pendingEmailFactory Pending Email Factory
     * @param array                                              $data                Data
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Sales\Model\Order $_order,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        PendingemailFactory $pendingEmailFactory,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_order = $_order;
        $this->_scopeConfig = $scopeConfig;
        $this->pendingEmailFactory = $pendingEmailFactory;
    }

    /**
     * Execute function
     *
     * @param Observer $observer Observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $emailsEnabled = $this->_scopeConfig
        ->getValue('settings/emails/duel_email_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        if ($emailsEnabled == 0) {
            return;
        }
        
        $shipment = $observer->getEvent()->getShipment();
        $shipmentData = $shipment->getData();

        $orderId = $shipment->getData()['order_id'];
       
        $_order = $this->_order->load($orderId);

        $_items = $_order->getAllItems();

        $itemsEmailEnabledCount = 0;
        foreach ($_items as $_item) {
            if ($_item->getProduct()->getData('duel_email_enabled')) {
                $itemsEmailEnabledCount++;
            }
        }

        if (count($itemsEmailEnabledCount) == 0) {
            return;
        }

        $pendingEmail = $this->pendingEmailFactory->create();
        $pendingEmail->setData('order_id', $orderId);
        $pendingEmail->save();
    }
}
