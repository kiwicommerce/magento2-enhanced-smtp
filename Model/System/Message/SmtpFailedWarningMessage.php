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
namespace KiwiCommerce\EnhancedSMTP\Model\System\Message;

use Magento\Framework\Notification\MessageInterface;
use KiwiCommerce\EnhancedSMTP\Helper\Config;
use Magento\Framework\UrlInterface;

/**
 * Class SmtpFailedWarningMessage
 * @package KiwiCommerce\EnhancedSMTP\Model\System\Message
 */
class SmtpFailedWarningMessage implements MessageInterface
{
    /**
     * Message identity
     */
    const MESSAGE_IDENTITY = 'smtp_sending_failed_warning_message';

    /**
     * @var Config
     */
    public $config;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * SmtpFailedWarningMessage constructor.
     *
     * @param Config $config
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Config $config,
        UrlInterface $urlBuilder
    ) {
        $this->config = $config;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve unique system message identity
     *
     * @return string
     */
    public function getIdentity()
    {
        return self::MESSAGE_IDENTITY;
    }

    /**
     * Check whether the system message should be shown
     *
     * @return bool
     */
    public function isDisplayed()
    {
        $warningMessageCount = $this->config->getConfig(Config::WARNING_MESSAGE_COUNT);
        $failedCount = $this->config->getCustomFailedCount();

        return ($failedCount >= $warningMessageCount)? true : false;
    }

    /**
     * Retrieve system message text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getText()
    {
        $messageDetails = '';
        $messageDetails .= __(
            'Email sending failed %1 times. Please check your ',
            $this->config->getCustomFailedCount()
        );
        $messageDetails .= __(
            '<a href="%1">SMTP Configuration</a>',
            $this->urlBuilder->getUrl('adminhtml/system_config/edit/section/enhancedsmtp')
        );

        return $messageDetails;
    }

    /**
     * Retrieve system message severity
     * Possible default system message types:
     * - MessageInterface::SEVERITY_CRITICAL
     * - MessageInterface::SEVERITY_MAJOR
     * - MessageInterface::SEVERITY_MINOR
     * - MessageInterface::SEVERITY_NOTICE
     *
     * @return int
     */
    public function getSeverity()
    {
        return MessageInterface::SEVERITY_MAJOR;
    }
}
