<?php
/**
 * InstallData
 *
 * @category InstallData
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Setup;
 
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Action;
use Magento\Framework\App\Config\Storage\WriterInterface;

/**
 * InstallData
 *
 * @category InstallData
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
 
    /**
     * Construct function to inject necessary arguments
     *
     * @param EavSetupFactory   $eavSetupFactory   EAV Setup Factory
     * @param CollectionFactory $collectionFactory Collection Factory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CollectionFactory $collectionFactory,
        Action $productAction,
        WriterInterface $configWriter
    ) {
    
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->collectionFactory = $collectionFactory;
        $this->productAction = $productAction;
        $this->configWriter = $configWriter;
    }
 
    /**
     * Public Install function
     *
     * @param ModuleDataSetupInterface $setup   Setup
     * @param ModuleContextInterface   $context Context
     *
     * @return void
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $duelFeedHash = bin2hex(openssl_random_pseudo_bytes(10));
        $this->configWriter->save('settings/defaults/duel_feed_hash', $duelFeedHash);

        $installer = $setup;
        $installer->startSetup();

        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->removeAttribute(4, 'duel_colour');
        $eavSetup->removeAttribute(4, 'duel_background_colour');
        $eavSetup->removeAttribute(4, 'duel_rows');
        $eavSetup->removeAttribute(4, 'duel_columns');
        $eavSetup->removeAttribute(4, 'duel_page_position');
        $eavSetup->removeAttribute(4, 'duel_page_position_custom');
        $eavSetup->removeAttribute(4, 'duel_is_active');
        $eavSetup->removeAttribute(4, 'duel_email_enabled');
        $eavSetup->removeAttribute(4, 'duel_feed_enabled');

        $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_colour',
                [
                'type' => 'varchar',
                'input' => 'text',
                'label' => 'Gallery Colour',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                ]
            )
            ->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_background_colour',
                [
                'type' => 'varchar',
                'input' => 'text',
                'label' => 'Gallery Background Colour',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                ]
            )
            ->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_rows',
                [
                'type' => 'varchar',
                'input' => 'select',
                'label' => 'Rows',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                'source' => 'Duel\Gallery\Model\Product\Attribute\Source\DuelRowsAndColumns'
                ]
            )
            ->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_columns',
                [
                'type' => 'varchar',
                'input' => 'select',
                'label' => 'Columns',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                'source' => 'Duel\Gallery\Model\Product\Attribute\Source\DuelRowsAndColumns'
                ]
            )
            ->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_page_position',
                [
                'type' => 'varchar',
                'input' => 'select',
                'label' => 'Position gallery on page',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                'source' => 'Duel\Gallery\Model\Product\Attribute\Source\DuelPagePositions'
                ]
            )
            ->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_page_position_custom',
                [
                'type' => 'varchar',
                'input' => 'text',
                'label' => 'Position gallery by CSS selector',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                ]
            )
            ->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_is_active',
                [
                'type' => 'int',
                'input' => 'boolean',
                'label' => 'Show gallery on product page',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                'default' => false,
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                ]
            )
            ->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_email_enabled',
                [
                'type' => 'int',
                'input' => 'boolean',
                'label' => 'Enable Duel post-purchase email',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                'default' => false,
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                ]
            )
            ->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_feed_enabled',
                [
                'type' => 'int',
                'input' => 'boolean',
                'label' => 'Add product to Duel JSON feed',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                'default' => true,
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                ]
            )
            ->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'duel_logging',
                [
                'type' => 'int',
                'input' => 'boolean',
                'label' => 'Enable Duel logging',
                'required' => false,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Duel',
                'default' => false,
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                ]
            );

        $installer->endSetup();

        $eavAttributeTable = $setup->getTable('eav_attribute');

        $updateables = [
            "duel_rows",
            "duel_columns",
            "duel_page_position",
            "duel_is_active",
            "duel_email_enabled",
            "duel_feed_enabled"
        ];
        $eavs = $setup->getConnection()
        ->fetchAll('SELECT attribute_id FROM eav_attribute WHERE attribute_code IN ("duel_rows", "duel_columns", "duel_page_position", "duel_is_active", "duel_email_enabled", "duel_feed_enabled") ORDER BY attribute_id ASC');
        $entityIds = $setup->getConnection()->fetchAll('SELECT entity_id FROM catalog_product_entity');

        $rowsCode = $eavs[0]['attribute_id'];
        $columnsCode = $eavs[1]['attribute_id'];
        $positionCode = $eavs[2]['attribute_id'];
        $galleryActiveCode = $eavs[3]['attribute_id'];
        $emailEnabledCode = $eavs[4]['attribute_id'];
        $feedEnabledCode = $eavs[5]['attribute_id'];

        foreach ($entityIds as $product) {
            $entityId = $product['entity_id'];
            $rowsDefault = [
                'entity_id' => $entityId,
                'store_id' => 0,
                'attribute_id' => $rowsCode,
                'value' => 0
            ];
            $columnsDefault = [
                'entity_id' => $entityId,
                'store_id' => 0,
                'attribute_id' => $columnsCode,
                'value' => 0
            ];
            $positionDefault = [
                'entity_id' => $entityId,
                'store_id' => 0,
                'attribute_id' => $positionCode,
                'value' => 0
            ];
            $setup->getConnection()->insert('catalog_product_entity_varchar', $rowsDefault);
            $setup->getConnection()->insert('catalog_product_entity_varchar', $columnsDefault);
            $setup->getConnection()->insert('catalog_product_entity_varchar', $positionDefault);
            $galleryActiveDefault = [
                'entity_id' => $entityId,
                'store_id' => 0,
                'attribute_id' => $galleryActiveCode,
                'value' => 1
            ];
            $emailEnabledDefault = [
                'entity_id' => $entityId,
                'store_id' => 0,
                'attribute_id' => $emailEnabledCode,
                'value' => 0
            ];
            $feedEnabledDefault = [
                'entity_id' => $entityId,
                'store_id' => 0,
                'attribute_id' => $feedEnabledCode,
                'value' => 0
            ];
            $setup->getConnection()->insert('catalog_product_entity_int', $galleryActiveDefault);
            $setup->getConnection()->insert('catalog_product_entity_int', $emailEnabledDefault);
            $setup->getConnection()->insert('catalog_product_entity_int', $feedEnabledDefault);
        }
    }
}
