<?php
/**
 * KiwiCommerce
 *
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customise this module for your needs.
 * Please contact us https://kiwicommerce.co.uk/contacts.
 *
 * @category   KiwiCommerce
 * @package    KiwiCommerce_EnhancedSMTP
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @license    https://kiwicommerce.co.uk/magento2-extension-license/
 */
namespace KiwiCommerce\EnhancedSMTP\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package KiwiCommerce\EnhancedSMTP\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'kiwicommerce_email_log'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('kiwicommerce_email_log')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Log Id'
        )->addColumn(
            'sender_name',
            Table::TYPE_TEXT,
            50,
            [],
            'Sender\'s name'
        )->addColumn(
            'sender_email',
            Table::TYPE_TEXT,
            128,
            [],
            'Sender\'s email address'
        )->addColumn(
            'recipient_name',
            Table::TYPE_TEXT,
            50,
            [],
            'Recipient\'s name'
        )->addColumn(
            'recipient_email',
            Table::TYPE_TEXT,
            128,
            [],
            'Recipient\'s email address'
        )->addColumn(
            'template_id',
            Table::TYPE_TEXT,
            100,
            [],
            'Template Name'
        )->addColumn(
            'email_subject',
            Table::TYPE_TEXT,
            100,
            [],
            'Template Name'
        )->addColumn(
            'email_template',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true],
            'Full body-content of sent email'
        )->addColumn(
            'module_name',
            Table::TYPE_TEXT,
            32,
            [],
            'Module name for email template'
        )->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            5,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Store Id'
        )->addColumn(
            'status',
            Table::TYPE_TEXT,
            15,
            [],
            'Status of sent email'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Datetime when email was sent'
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => true, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Datetime when email status changed'
        )->addColumn(
            'remarks',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true],
            'Reason for reject/failed sent email'
        )->addIndex(
            $installer->getIdxName('kiwicommerce_email_log', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName(
                $installer->getTable('kiwicommerce_email_log'),
                'store_id',
                $installer->getTable('store'),
                'store_id'
            ),
            'store_id',
            $installer->getTable('store'),
            'store_id'
        )->setComment(
            'Email log management'
        );
        $installer->getConnection()->createTable($table);

        // End Setup
        $installer->endSetup();
    }
}
