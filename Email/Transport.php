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
namespace KiwiCommerce\EnhancedSMTP\Email;

use KiwiCommerce\EnhancedSMTP\Helper\Config;
use KiwiCommerce\EnhancedSMTP\Helper\Benchmark;
use KiwiCommerce\EnhancedSMTP\Logger\Logger;
use KiwiCommerce\EnhancedSMTP\Model\Logs\Status;

/**
 * Class Transport
 * @package  KiwiCommerce\EnhancedSMTP\Email
 */
class Transport extends \Zend_Mail_Transport_Smtp
{
    /**
     * @var Config
     */
    public $config;

    /**
     * @var Logger
     */
    public $logger;

    /**
     * @var Status
     */
    public $status;

    /**
     * @var Benchmark
     */
    public $benchmark;

    /**
     * Transport constructor.
     *
     * @param Config $config
     * @param Logger $logger
     * @param Status $status
     * @param Benchmark $benchmark
     */
    public function __construct(
        Config $config,
        Logger $logger,
        Status $status,
        Benchmark $benchmark
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->status = $status;
        $this->benchmark = $benchmark;
    }

    /**
     * Set custom trasport for mail sending
     *
     * @param \Magento\Framework\Mail\TransportInterface $subject
     * @param callable $proceed
     */
    public function aroundSendMessage(\Magento\Framework\Mail\TransportInterface $subject, callable $proceed)
    {
        $this->benchmark->start(__METHOD__);
        $message = $this->getMessage($subject);
        if ($this->config->isEnable() && $message) {
            $this->sendSmtpMessage($message);
        } else {
            $proceed();
        }
        $this->benchmark->end(__METHOD__);
    }

    /**
     * Get message for email
     *
     * @param $transport
     * @return mixed|null
     */
    protected function getMessage($transport)
    {
        if ($this->config->versionCompare('2.2.0')) {
            return $transport->getMessage();
        }

        try {
            $reflectionClass = new \ReflectionClass($transport);
            $message = $reflectionClass->getProperty('_message');
            $message->setAccessible(true);

            return $message->getValue($transport);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            return null;
        }
    }

    /**
     * Send a mail using this transport
     *
     * @param $message
     * @throws \Exception
     * @return null
     */
    public function sendSmtpMessage($message)
    {
        $smtpHost = $this->config->getConfig(Config::ENHANCED_SMTP_HOST_NAME);

        $smtpConf = [
            'auth' => $this->config->getConfig(Config::ENHANCED_SMTP_AUTH),
            'ssl' => $this->config->getConfig(Config::ENHANCED_SMTP_PROTOCOL),
            'port' => $this->config->getConfig(Config::ENHANCED_SMTP_PORT),
            'username' => $this->config->getConfig(Config::ENHANCED_SMTP_USERNAME),
            'password' => $this->config->getConfig(Config::ENHANCED_SMTP_PASSWORD)
        ];
        parent::__construct($smtpHost, $smtpConf);

        try {
            if (!$this->config->getConfig(Config::ENHANCED_SMTP_DEVELOPER_MODE)) {
                parent::send($message);
            }

            //Set Staus Success
            $this->status->setStatusSuccess();

            //Reset Failed Count On Email Send Successfully
            $this->config->resetFailedCount();

            $this->logger->info("Email has been sent successfully.");

        } catch (\Exception $e) {
            //Set Staus Failed
            $this->status->setStatusFailed($e->getMessage());

            //Increment Failed Count Value On Email Sending Failed
            $this->config->incrementFailedCount();

            $this->logger->critical($e->getMessage());

            throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
        }
    }
}
