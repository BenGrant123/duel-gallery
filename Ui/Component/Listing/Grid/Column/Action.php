<?php
/**
 * Action File
 *
 * @category Action
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Ui\Component\Listing\Grid\Column;
 
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Action Class
 *
 * @category Action
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class Action extends Column
{
    const ROW_EDIT_URL = 'gallery/duelprods/inlineedit';

    private $urlBuilder;
 
    private $editUrl;
 
    /**
     * Constructor function
     *
     * @param ContextInterface   $context            Context
     * @param UiComponentFactory $uiComponentFactory Ui Component Factory
     * @param UrlInterface       $urlBuilder         Url Interface
     * @param array              $components         Components
     * @param array              $data               Data
     * @param string             $editUrl            The edit url path
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::ROW_EDIT_URL
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
 
    /**
     * Prepare Data Source.
     *
     * @param array $dataSource Data Source
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $title = $this->getData('title');
                if (isset($item['entity_id'])) {
                    $item[$title]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            $this->editUrl,
                            ['id' => $item['entity_id']]
                        ),
                        'label' => __('Edit'),
                    ];
                }
            }
        }
 
        return $dataSource;
    }
}
