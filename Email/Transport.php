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
            if ($message instanceof \Zend_mail) {
                $this->sendSmtpMessage($message);
            } elseif ($message instanceof \Magento\Framework\Mail\Message) {
                $this->sendSmtpMailMessage($message);
            } elseif ($message instanceof \Magento\Framework\Mail\EmailMessage) {
                $this->sendSmtpMailMessage($message);
            } else {
                $proceed();
            }
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
     * Send a mail using this transport for magento 2.1 or magento 2.2 version
     *
     * @param $message
     * @throws \Magento\Framework\Exception\MailException
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

            //Set Status Success
            $this->status->setStatusSuccess();

            //Reset Failed Count On Email Send Successfully
            $this->config->resetFailedCount();

            $this->logger->info("Email has been sent successfully.");
        } catch (\Exception $e) {
            //Set Status Failed
            $this->status->setStatusFailed($e->getMessage());

            //Increment Failed Count Value On Email Sending Failed
            $this->config->incrementFailedCount();

            $this->logger->critical($e->getMessage());

            throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
        }
    }

    /**
     * Send a mail using this transport for magento 2.2.8 or greater version
     *
     * @param $message
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendSmtpMailMessage($message)
    {

        $message = \Zend\Mail\Message::fromString($message->getRawMessage());
        
        $message->getHeaders()->get('to')->setEncoding('utf-8');
        $message->getHeaders()->get('reply-to')->setEncoding('utf-8');
        $message->getHeaders()->get('from')->setEncoding('utf-8');

        $options   = new \Zend\Mail\Transport\SmtpOptions([
            'host' => $this->config->getConfig(Config::ENHANCED_SMTP_HOST_NAME),
            'port' => $this->config->getConfig(Config::ENHANCED_SMTP_PORT)
        ]);

        $connectionConfig = [];

        $auth = strtolower($this->config->getConfig(Config::ENHANCED_SMTP_AUTH));
        if (isset($auth) && $auth !== "") {
            $options->setConnectionClass($auth);

            $connectionConfig = [
                'username' => $this->config->getConfig(Config::ENHANCED_SMTP_USERNAME),
                'password' => $this->config->getConfig(Config::ENHANCED_SMTP_PASSWORD)
            ];
        }

        $ssl = $this->config->getConfig(Config::ENHANCED_SMTP_PROTOCOL);
        if (isset($ssl) && $ssl !== "") {
            $connectionConfig['ssl'] = $ssl;
        }

        if (!empty($connectionConfig)) {
            $options->setConnectionConfig($connectionConfig);
        }

        try {
            if (!$this->config->getConfig(Config::ENHANCED_SMTP_DEVELOPER_MODE)) {
                $transport = new \Zend\Mail\Transport\Smtp();
                $transport->setOptions($options);
                $transport->send($message);
            }

            //Set Status Success
            $this->status->setStatusSuccess();

            //Reset Failed Count On Email Send Successfully
            $this->config->resetFailedCount();

            $this->logger->info("Email has been sent successfully.");
        } catch (\Exception $e) {
            //Set Status Failed
            $this->status->setStatusFailed($e->getMessage());

            //Increment Failed Count Value On Email Sending Failed
            $this->config->incrementFailedCount();

            $this->logger->critical($e->getMessage());

            throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
        }
    }
}
