<?php
namespace Duel\Gallery\Controller;
 
class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;
 
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->actionFactory = $actionFactory;
        $this->_scopeConfig = $scopeConfig;
    }
 
    /**
     * Validate and Match
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $duelFeedHash = $this->_scopeConfig
        ->getValue('settings/defaults/duel_feed_hash');
        $identifier = trim($request->getPathInfo(), '/');

        if (strpos($identifier, $duelFeedHash) === false) {
            return;
        } else {
            $request->setModuleName('gallery')->setControllerName('duelfeed')->setActionName('index');
        }

        return $this->actionFactory->create(
            'Magento\Framework\App\Action\Forward',
            ['request' => $request]
        );
    }
}
