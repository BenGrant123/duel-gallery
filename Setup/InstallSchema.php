<?php
/**
 * InstallSchema
 *
 * @category InstallSchema
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
namespace Duel\Gallery\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * InstallSchema
 *
 * @category InstallSchema
 * @package  Duel_Gallery
 * @author   Duel <ben@duel.me>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://duel.tech
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Public Install function
     *
     * @param SchemaSetupInterface   $setup   Setup
     * @param ModuleContextInterface $context Context
     *
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if ($setup->getConnection()->isTableExists('duel_pending_email') == true) {
            $setup->getConnection()->dropTable($setup->getTable('duel_pending_email'));
        }
 
        $table = $setup->getConnection()->newTable(
            $setup->getTable('duel_pending_email')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity Id'
        )->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Order Id'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            255,
            ['nullable' => true, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Created At'
        )->addColumn(
            'duel_email_sent',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['nullable' => true, 'default' => 0],
            'Sent'
        )->setComment(
            'Duel Pending Emails'
        );
        $setup->getConnection()->createTable($table);
        $setup->getConnection()->addIndex(
            $setup->getTable('duel_pending_email'),
            $setup->getIdxName('pending', ['entity_id']),
            ['entity_id']
        );

        if ($setup->getConnection()->isTableExists('duel_widthrule') == true) {
            $setup->getConnection()->dropTable($setup->getTable('duel_widthrule'));
        }
 
        $table2 = $setup->getConnection()->newTable(
            $setup->getTable('duel_widthrule')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity Id'
        )->addColumn(
            'minimum_width',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => false],
            'Minimum width'
        )->addColumn(
            'columns',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => false],
            'Columns'
        )->addColumn(
            'rows',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Rows'
        )->setComment(
            'Duel Width Rules'
        );
        $setup->getConnection()->createTable($table2);
        $setup->getConnection()->addIndex(
            $setup->getTable('duel_widthrule'),
            $setup->getIdxName('widthrule', ['entity_id']),
            ['entity_id']
        );
 
        $setup->endSetup();
    }
}
