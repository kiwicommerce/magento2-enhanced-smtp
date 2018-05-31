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

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\App\Config\Storage\Writer;
use KiwiCommerce\EnhancedSMTP\Helper\Config;

/**
 * Class Uninstall
 * @package KiwiCommerce\EnhancedSMTP\Setup
 */
class Uninstall implements UninstallInterface
{
    /**
     * @var Writer
     */
    public $scope;

    /**
     * Uninstall constructor.
     *
     * @param Writer $scopeWriter
     */
    public function __construct(Writer $scopeWriter)
    {
        $this->scope = $scopeWriter;
    }
    
    /**
     * Module uninstall code
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $tableName = $installer->getTable('kiwicommerce_email_log');

        // Execute SQL to drop the table
        $installer->getConnection()->dropTable($tableName);

        // End Setup
        $installer->endSetup();

        //Remove system configuration data when uninstall a module
        $scope = $this->scope;

        $configModulePath = Config::CONFIG_MODULE_PATH . '/';

        $scope->delete($configModulePath . Config::ENHANCED_SMTP_ENABLED);
        $scope->delete($configModulePath . Config::CLEAR_EMAIL_LOG_DURATION);
        $scope->delete($configModulePath . Config::ALLOWED_ORDER_MODULE);
        $scope->delete($configModulePath . Config::ALLOWED_CUSTOMER_MODULE);
        $scope->delete($configModulePath . Config::ALLOWED_NEWSLETTER_MODULE);
        $scope->delete($configModulePath . Config::ALLOWED_CONTACTUS_MODULE);
        $scope->delete($configModulePath . Config::ENHANCED_SMTP_PROVIDER);
        $scope->delete($configModulePath . Config::ENHANCED_SMTP_HOST_NAME);
        $scope->delete($configModulePath . Config::ENHANCED_SMTP_PORT);
        $scope->delete($configModulePath . Config::ENHANCED_SMTP_USERNAME);
        $scope->delete($configModulePath . Config::ENHANCED_SMTP_PASSWORD);
        $scope->delete($configModulePath . Config::ENHANCED_SMTP_PROTOCOL);
        $scope->delete($configModulePath . Config::ENHANCED_SMTP_AUTH);
        $scope->delete($configModulePath . Config::ENHANCED_SMTP_LOG_EMAIL);
        $scope->delete($configModulePath . Config::ENHANCED_SMTP_DEVELOPER_MODE);
    }
}
