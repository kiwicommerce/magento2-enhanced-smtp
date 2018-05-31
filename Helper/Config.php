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
namespace KiwiCommerce\EnhancedSMTP\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Config
 * @package KiwiCommerce\EnhancedSMTP\Helper
 */
class Config extends AbstractHelper
{
    /**
     * Config Path
     */
    const CONFIG_MODULE_PATH = 'enhancedsmtp';

    /**
     * Enable/Disable extension
     */
    const ENHANCED_SMTP_ENABLED = 'general/enabled';

    /**
     * Cron interval in day to clear log history
     */
    const CLEAR_EMAIL_LOG_DURATION = 'general/log_clear';

    /**
     * Sending Failed Warning After these Times
     */
    const WARNING_MESSAGE_COUNT = 'general/warning_message';

    /**
     * Get Failed Count
     */
    const CUSTOM_FAILED_COUNT= 'custom/failed_count';

    /**
     * Allow email log for sales order module
     */
    const ALLOWED_ORDER_MODULE = 'allowedmodule/order';

    /**
     * Allow email log for customer module
     */
    const ALLOWED_CUSTOMER_MODULE = 'allowedmodule/customer';

    /**
     * Allow email log for newsletter module
     */
    const ALLOWED_NEWSLETTER_MODULE = 'allowedmodule/newsletter';

    /**
     * Allow email log for contact us module
     */
    const ALLOWED_CONTACTUS_MODULE = 'allowedmodule/contactus';

    /**
     * SMTP Provider
     */
    const ENHANCED_SMTP_PROVIDER = 'smtpconfig/smtp_provider';

    /**
     * SMTP Hostname
     */
    const ENHANCED_SMTP_HOST_NAME = 'smtpconfig/hostname';

    /**
     * SMTP Port
     */
    const ENHANCED_SMTP_PORT = 'smtpconfig/port';

    /**
     * SMTP Username
     */
    const ENHANCED_SMTP_USERNAME = 'smtpconfig/username';

    /**
     * SMTP Password
     */
    const ENHANCED_SMTP_PASSWORD = 'smtpconfig/password';

    /**
     * SMTP Protocol
     */
    const ENHANCED_SMTP_PROTOCOL = 'smtpconfig/protocol';

    /**
     * SMTP Protocol
     */
    const ENHANCED_SMTP_AUTH = 'smtpconfig/auth';

    /**
     * Enable/Disable Email log
     */
    const ENHANCED_SMTP_TEST_EMAIL_FROM = 'advanced/test_email_from';

    /**
     * Enable/Disable Email log
     */
    const ENHANCED_SMTP_LOG_EMAIL = 'advanced/log_email';

    /**
     * Developer Mode
     */
    const ENHANCED_SMTP_DEVELOPER_MODE = 'advanced/developermode';

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    public $configWriter;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    public $productMetadata;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    public $configCollectionFactory;

    /**
     * Config constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory $configCollectionFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory $configCollectionFactory
    ) {
        $this->configWriter = $configWriter;
        $this->productMetadata = $productMetadata;
        $this->configCollectionFactory = $configCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Check module is enabled
     *
     * @return bool
     */
    public function isEnable()
    {
        $status = $this->scopeConfig->getValue(
            self::CONFIG_MODULE_PATH . '/' . self::ENHANCED_SMTP_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        return ($status == '1')?true:false;
    }

    /**
     * To get core config values using path
     *
     * @param $path
     * @return mixed
     */
    public function getConfig($path)
    {
        $config = $this->scopeConfig->getValue(
            self::CONFIG_MODULE_PATH . '/' . $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $config;
    }

    /**
     * To skip cache value when get custom failed count
     *
     * @return int
     */
    public function getCustomFailedCount()
    {
        $configCollection = $this->configCollectionFactory->create();
        $configCollection->addFieldToFilter('scope', ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        $configCollection->addFieldToFilter('scope_id', 0);
        $configCollection->addFieldToFilter('path', self::CONFIG_MODULE_PATH . '/' . self::CUSTOM_FAILED_COUNT);

        $singleConfigCollection = $configCollection->getFirstItem();

        return $singleConfigCollection->getValue();
    }

    /**
     * Increment failed count after sending email failed
     *
     * @return bool
     */
    public function incrementFailedCount()
    {
        $count = $this->getCustomFailedCount();
        $count++;

        $this->configWriter->save(
            self::CONFIG_MODULE_PATH . '/' . self::CUSTOM_FAILED_COUNT,
            $count
        );

        return true;
    }

    /**
     * Reset failed count after sending successfull email
     *
     * @return bool
     */
    public function resetFailedCount()
    {
        $this->configWriter->save(
            self::CONFIG_MODULE_PATH . '/' . self::CUSTOM_FAILED_COUNT,
            0
        );
        return true;
    }

    /**
     * Compare magento version
     *
     * @param $ver
     * @return mixed
     */
    public function versionCompare($ver)
    {
        //will return the magento version
        $version = $this->productMetadata->getVersion();

        return version_compare($version, $ver, '>=');
    }
}
