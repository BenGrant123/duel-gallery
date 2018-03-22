<?php
/**
 * Logviewer File Doc Comment
 *
 * @category Logviewer
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
 * Logviewer Class Doc Comment
 *
 * @category Logviewer
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Logviewer extends Template
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
        array $data,
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Magento\Framework\Filesystem\Driver\File $file
    ) {
        $this->_dir = $dir;
        $this->_file = $file;
        $this->_blockFactory = $blockFactory;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    public function getLogFilesSelect()
    {

      $logPath = $this->_dir->getPath('log');
      $logFiles = [];

      $logFiles = $this->_file->readDirectory($logPath);

      $flags = \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS;
      $iterator = new \FilesystemIterator($logPath, $flags);
      $logFiles = [];
      /** @var \FilesystemIterator $file */
      foreach ($iterator as $file) {
          $logFiles[] = $file;
      }
      sort($logFiles);
      
      if (empty($logFiles)) {
          return $this->__('No log files found');
      }
      

      $html = '<label for="rl-log-switcher">Please choose a file:</label><select id="rl-log-switcher" name="rl-log-switcher"><option value=""></option>';

      foreach ($logFiles as $l) {
          $htmlBody = $this->getContent($l->getFilename());
          $html .= '<option value="' . $l->getFilename() . '">' . $l->getFilename() . '</option>';
      }

      $html .= '</select>';

      return $html;
    }

    
}
