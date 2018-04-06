<?php

namespace Duel\Gallery\Controller\Adminhtml\Logviewer;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Filesystem\DirectoryList;

class Tail extends \Magento\Backend\App\Action
{

    public function __construct(
        Context $context,
        DirectoryList $dir
    ) {
        $this->_dir = $dir;
        parent::__construct($context);
    }

    public function execute()
    {
        $r = $this->getRequest();
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if (!$r->getParam('file')) {
            return $response->setData([
                'status'  => "ok",
                'iframeHtml' => '<pre id="log-pre">Please select a file</pre>'
            ]);
        }

        $f = $this->_dir->getPath('log') . DIRECTORY_SEPARATOR . $r->getParam('file');

        $numberOfLines = 200;
        $handle = fopen($f, "r");
        $lineCounter = $numberOfLines;
        $pos = - 2;
        $beginning = false;
        $text = [];

        while ($lineCounter > 0) {
            $t = " ";
            while ($t != "\n") {
                if (fseek($handle, $pos, SEEK_END) == - 1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos --;
            }
            $lineCounter --;
            if ($beginning) {
                rewind($handle);
            }
            
            $text[$numberOfLines - $lineCounter - 1] = fgets($handle);

            if ($beginning) {
                break;
            }
        }
        fclose($handle);

        $dlFile = '<a href="' . $this->getUrl(
            'gallery/logviewer/downloadfile',
            ['f' => $r->getParam('file')]
        ) . '">' . 'Download file' . '</a>';

        return $response->setData([
            'status'  => "ok",
            'iframeHtml' => '<pre id="log-pre">' . $dlFile . "\r\n\n" . strip_tags(implode('', $text)). '</pre>'
        ]);
    }
}
