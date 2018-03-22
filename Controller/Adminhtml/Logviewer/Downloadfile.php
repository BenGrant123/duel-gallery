<?php

namespace Duel\Gallery\Controller\Adminhtml\Logviewer;

use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;

class Downloadfile extends \Magento\Backend\App\Action
{

    public function __construct(
        Context $context,
        DirectoryList $dir,
        FileFactory $fileFactory
    ) {
        $this->_dir = $dir;
        $this->_fileFactory = $fileFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $fileName = $this->getRequest()->getParam('f');
        if (is_null($fileName)) {
            return;
        }

        $file = $this->_dir->getPath('log') . DIRECTORY_SEPARATOR . $fileName;

        return $this->_fileFactory->create($fileName, file_get_contents($file));
    }

}