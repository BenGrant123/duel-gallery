<?php

namespace Duel\Gallery\Controller\Adminhtml\Previews;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Duel\Gallery\Model\PendingemailFactory;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order;
use Magento\Catalog\Model\ProductRepository;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
 
class Send extends \Magento\Backend\App\Action
{

    /**
     * @param Context $context
     * @param Builder $productBuilder
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $productCollectionFactory,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        PendingemailFactory $pendingEmailFactory,
        Configurable $catalogProductTypeConfigurable,
        Order $order,
        ProductRepository $productRepository,
        OrderFactory $orderFactory,
        DateTime $date
    ) {
        $this->filter = $filter;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->pendingEmailFactory = $pendingEmailFactory;
        $this->configurable = $catalogProductTypeConfigurable;
        $this->order = $order;
        $this->productRepository = $productRepository;
        $this->orderFactory = $orderFactory;
        $this->date = $date;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $config = [];
        $config['logFile'] = fopen('./var/log/duel_preview.log', 'a');

        $dateNow = $this->date->gmtDate();

        fwrite($config['logFile'], $dateNow . " Duel email preview." . "\r\n");
    	
        $data = $this->getRequest()->getPostValue();
        
        $storeId = $this->storeManager->getStore()->getId();
        if (!$data) {
            $this->_redirect('gallery/previews/index');
            return;
        }
        
        $emailsEnabled = $this->scopeConfig
        ->getValue('settings/emails/duel_email_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $delay = $this->scopeConfig
        ->getValue('settings/emails/duel_email_delay', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        $brandId = $this->scopeConfig
        ->getValue('settings/emails/duel_brand_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if (!$emailsEnabled) {
            $this->messageManager->addError(__('Emails are currently disabled in "Stores > Configuration > Duel > Settings".'));
        }

        if (!$brandId) {
            $this->messageManager->addError(__('This store does not have a brand ID for the Duel app.'));
        }
        
        $config['baseMediaUrl'] = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $config['placeholder'] = $this->scopeConfig->getValue('catalog/placeholder/thumbnail_placeholder');
        $config['templateId'] = $this->scopeConfig
        ->getValue('settings/emails/duel_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $config['storeEmail'] = $this->scopeConfig
        ->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $config['storeName']  = $this->storeManager->getStore()->getName();
        $config['brandId'] = $brandId;

        if ($data['preview_type'] == 0) {
            fwrite($config['logFile'], $dateNow . " Attempt to simulate Duel cron job with mock order data." . "\r\n");
            $products = $this->productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToSort('entity_id', 'DESC')->setPageSize(3);
            $orderProducts = $this->prepareProducts($products, $config);
            if (!empty($orderProducts)) {
                $this->sendDuelEmail('100', $orderProducts, $data['preview_email'], $config);
            }
            return $this->_redirect('gallery/previews/index');
        }


        $pendingEmail = $this->pendingEmailFactory->create();
        $collection = $pendingEmail->getCollection()->setOrder('entity_id', 'DESC')->setPageSize(5);
        

        if (!sizeof($collection)) {
            fwrite($config['logFile'], $dateNow . " No pending follow-up emails were found. Attempt to simulate Duel cron job using 5 most recent customer orders." . "\r\n");
            $ordersCollection = $this->orderFactory->create()->getCollection()->addAttributeToSort('entity_id', 'DESC')->setPageSize(5);
            fwrite($config['logFile'], $dateNow . " " . sizeof($ordersCollection) . " customer orders were found. Attempt to simulate Duel cron job." . "\r\n");
            foreach ($ordersCollection as $value) {
                fwrite($config['logFile'], $dateNow . " Sending email for order with ID: " . $value->getId() . "\r\n");
                $order = $this->order->load($value->getId());
                $items = $order->getAllItems();
                $orderItems = $this->prepareItems($items, $config);
                if (!empty($orderItems)) {
                    $this->sendDuelEmail($value->getId(), $orderItems, $data['preview_email'], $config);
                }
                
            }
        } else {
            fwrite($config['logFile'], $dateNow . " " . sizeof($collection) . " pending follow-up emails were found. Attempt to simulate Duel cron job." . "\r\n");
            foreach ($collection as $value) {
                fwrite($config['logFile'], $dateNow . " Sending email for order with ID: " . $value['order_id'] . "\r\n");
                $order = $this->order->load($value['order_id']);
                $items = $order->getAllItems();
                $orderItems = $this->prepareItems($items, $config);
                if (!empty($orderItems)) {
                    $this->sendDuelEmail($value['order_id'], $orderItems, $data['preview_email'], $config);
                }
                
            }
        }
       
        $this->_redirect('gallery/previews/index');
    }
 
    private function prepareProducts($products, $config) {
        $storeUrl = $this->storeManager->getStore()->getBaseUrl();
        $mockItems = [];
        foreach ($products as $product) {

            $parentByChild = $this->configurable->getParentIdsByChild($product->getId());
            if ($parentByChild) {
                $parentId = $parentByChild[0];
                $sku = $this->productRepository->getById($parentId)->getData('sku');
            } else {
                $sku = $product->getSku();
            }

            $productThumbnail = $product->getThumbnail();
            if (!$productThumbnail Or $productThumbnail == 'no_selection') {
                $thumbnail = ($config['placeholder'] == null) ? "" : $config['baseMediaUrl'] . 'catalog/product/placeholder/' . $config['placeholder'];
            } else {
                $thumbnail = $config['baseMediaUrl'] . 'catalog/product' . $productThumbnail;
            }

            $item = [
                'product_id' => $product->getId(),
                'sku' => $sku,
                'thumbnail' => $thumbnail,
                'name' => $product->getName(),
            ];

            array_push($mockItems, $item);
        }

        return $mockItems;
    }

    private function prepareItems($items, $config)
    {
        $storeUrl = $this->storeManager->getStore()->getBaseUrl();
        $orderItems = [];
        foreach ($items as $item) {
            if ($item->getProduct()) {
                $productId = $item->getProduct()->getData('entity_id');
                $parentByChild = $this->configurable->getParentIdsByChild($productId);
                if ($parentByChild) {
                    $parentId = $parentByChild[0];
                    $sku = $this->productRepository->getById($parentId)->getData('sku');
                } else {
                    $sku = $item->getSku();
                }
                   
                $productThumbnail = $item->getProduct()->getThumbnail();
                if (!$productThumbnail Or $productThumbnail == 'no_selection') {
                    $thumbnail = ($config['placeholder'] == null) ? "" : $config['baseMediaUrl'] . 'catalog/product/placeholder/' . $config['placeholder'];
                } else {
                    $thumbnail = $config['baseMediaUrl'] . 'catalog/product' . $productThumbnail;
                }

                $item = [
                    'product_id' => $productId,
                    'sku' => $sku,
                    'item_id' => $productId,
                    'price' => $item->getPrice(),
                    'thumbnail' => $thumbnail,
                    'name' => $item->getName(),
                ];
                array_push($orderItems, $item);
                
            }
        }
        return $orderItems;
    }

    /**
     * Send the post-purchase email for this order
     *
     * @param string $orderId       Order Id
     * @param array  $orderItems    Order Items
     * @param string $customerEmail Customer Email
     * @param array  $config        Config
     *
     * @return void
     */
    private function sendDuelEmail($orderId, $orderItems, $toEmail, $config)
    {
        $emailData = [];

        $shipmentData = [];
        $shipmentData['order_id'] = $orderId;
        $shipmentData['orderItems'] = $orderItems;
        $shipmentData['brandId'] = $config['brandId'];

        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($shipmentData);

        $transport = $this->transportBuilder
            ->setTemplateIdentifier($config['templateId'])
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID
            ])
            ->setTemplateVars(['data' => $postObject])
            ->setFrom(['email' => $config['storeEmail'], 'name' => $config['storeName']])
            ->addTo([$toEmail])
            ->getTransport();
        try {
            $transport->sendMessage();
            $this->messageManager->addSuccess(__('Test email sent.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
    }

    

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Duel_Gallery::add_action');
    }
}
