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
namespace KiwiCommerce\EnhancedSMTP\Email\Log;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\TemplateTypesInterface;
use Magento\Framework\Mail\MessageInterface;
use KiwiCommerce\EnhancedSMTP\Helper\Config;
use KiwiCommerce\EnhancedSMTP\Model\SendEmail;

/**
 * Class SaveEmailLog
 * @package KiwiCommerce\EnhancedSMTP\Email\Log
 */
class SaveEmailLog extends TransportBuilder
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Model\LogsFactory
     */
    public $logsFactory;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Helper\Config
     */
    public $config;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Logger\Logger
     */
    public $logger;

    /**
     * To get request parameter
     * @var \Magento\Framework\App\Request\Http
     */
    public $requests;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Model\Logs\Status
     */
    public $status;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Helper\Benchmark
     */
    public $benchmark;

    /**
     * @var \Magento\Framework\Mail\MessageInterfaceFactory
     */
    protected $messageFactory;

    /**
     * @var $mailMessage
     */
    protected $mailMessage;

    /**
     * SaveEmailLog constructor.
     * @param \Magento\Framework\Mail\Template\FactoryInterface $templateFactory
     * @param MessageInterface $message
     * @param \Magento\Framework\Mail\Template\SenderResolverInterface $senderResolver
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Mail\TransportInterfaceFactory $mailTransportFactory
     * @param \Magento\Framework\Mail\MessageInterfaceFactory|null $messageFactory
     * @param \KiwiCommerce\EnhancedSMTP\Model\LogsFactory $logsFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Request\Http $request
     * @param Config $config
     * @param \KiwiCommerce\EnhancedSMTP\Model\Logs\Status $status
     * @param \KiwiCommerce\EnhancedSMTP\Logger\Logger $logger
     * @param \KiwiCommerce\EnhancedSMTP\Helper\Benchmark $benchmark
     */
    public function __construct(
        \Magento\Framework\Mail\Template\FactoryInterface $templateFactory,
        \Magento\Framework\Mail\MessageInterface $message,
        \Magento\Framework\Mail\Template\SenderResolverInterface $senderResolver,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Mail\TransportInterfaceFactory $mailTransportFactory,
        \Magento\Framework\Mail\MessageInterfaceFactory $messageFactory = null,
        \KiwiCommerce\EnhancedSMTP\Model\LogsFactory $logsFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Request\Http $request,
        \KiwiCommerce\EnhancedSMTP\Helper\Config $config,
        \KiwiCommerce\EnhancedSMTP\Model\Logs\Status $status,
        \KiwiCommerce\EnhancedSMTP\Logger\Logger $logger,
        \KiwiCommerce\EnhancedSMTP\Helper\Benchmark $benchmark
    ) {

        $this->logsFactory = $logsFactory;
        $this->storeManager = $storeManager;
        $this->config = $config;
        $this->requests = $request;
        $this->status = $status;
        $this->logger = $logger;
        $this->benchmark = $benchmark;

        if (!$config->versionCompare('2.2.8')) {
            parent::__construct($templateFactory, $message, $senderResolver, $objectManager, $mailTransportFactory);
        } else {
            parent::__construct($templateFactory, $message, $senderResolver, $objectManager, $mailTransportFactory, $messageFactory);
        }
    }

    /**
     * Trim name
     *
     * @param $name
     * @return string
     */
    public function filterName($name)
    {
        return trim(strstr(
            $name,
            "<",
            true
        ));
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * Create Email Log object and set basic values.
     *
     * @return mixed
     */
    public function initEmailLog()
    {
        $module = $this->requests->getModuleName();
        $emailLog = $this->logsFactory->create();

        if ($this->config->versionCompare('2.2.8')) {

            $from = $this->mailMessage->getFrom();

            $senderEmail = '';
            $senderName = '';
            if (count($from)) {
                $from->rewind();
                $senderEmail = $from->current()->getEmail();
                $senderName = $from->current()->getName();
            }

            //Set sender details
            $emailLog->setSenderEmail($senderEmail);
            $emailLog->setSenderName($senderName);

            //Decode Email message for Magento 2.3.3 and higher version
            if ($this->config->versionCompare('2.3.3')) {
                $content = quoted_printable_decode($this->mailMessage->getBodyText());
            } else {
                $content = $this->mailMessage->getBodyText();
            }

            $emailLog->setEmailTemplate($content);

        } else {
            $header = $this->message->getHeaders();
            //Set sender details
            $emailLog->setSenderEmail($this->message->getFrom());

            $emailLog->setSenderName(
                $this->filterName(
                    isset($header['From'])?current($header['From']):''
                )
            );
            $emailLog->setEmailTemplate($this->message->getBody()->getRawContent());
        }

        //Set template detail
        $emailLog->setTemplateId($this->templateIdentifier);
        $emailLog->setModuleName($module);
        $emailLog->setEmailSubject($this->message->getSubject());
        $emailLog->setStoreId($this->getStoreId());
        $emailLog->setStatus(\KiwiCommerce\EnhancedSMTP\Model\Logs\Status::STATUS_PENDING);

        return $emailLog;
    }

    /**
     * Set recipient details
     *
     * @param $key
     * @return string
     */
    public function getRecipientName($key)
    {
        $header = $this->message->getHeaders();
        return $this->filterName(
            isset($header['To'][$key])?$header['To'][$key]:''
        );
    }

    /**
     * Save email log data
     *
     * @param $emailLog
     * @param $recipient
     * @param $name
     * @return int
     */
    public function sendLog($emailLog, $recipient, $name)
    {
        $emailLog->setRecipientName($name);
        $emailLog->setRecipientEmail($recipient);
        $emailLog->save();

        //Get Log Id After Email Log Save
        $logId = $emailLog->getId();

        //Unset Email Log Data
        $emailLog->unsetData();

        return $logId;
    }

    /**
     * Check if module is allowed for email log
     * @return bool
     */
    public function isAllowed()
    {
        $allow = true;
        $module = $this->requests->getModuleName();
        switch ($module) {
            case 'customer':
                $customer = $this->config->getConfig(Config::ALLOWED_CUSTOMER_MODULE);
                $allow = ($customer == 0)?false:$allow;
                break;
            case 'newsletter':
                $newsletter = $this->config->getConfig(Config::ALLOWED_NEWSLETTER_MODULE);
                $allow = ($newsletter == 0)?false:$allow;
                break;
            case 'contact':
                $contact = $this->config->getConfig(Config::ALLOWED_CONTACTUS_MODULE);
                $allow = ($contact == 0)?false:$allow;
                break;
            case 'sales':
                $order = $this->config->getConfig(Config::ALLOWED_ORDER_MODULE);
                $allow = ($order == 0)?false:$allow;
                break;
        }

        return $allow;
    }

    /**
     *  Get recipients
     *
     * @return array
     */
    public function getRecipients()
    {
        $recipients = [];

        foreach ($this->mailMessage->getTo() as $address) {
            $recipients[] = $address->getEmail();
        }

        foreach ($this->mailMessage->getCc() as $address) {
            $recipients[] = $address->getEmail();
        }
        foreach ($this->mailMessage->getBcc() as $address) {
            $recipients[] = $address->getEmail();
        }

        $recipients = array_unique($recipients);
        return $recipients;
    }

    /**
     *  Get recipients name by email
     *
     * @param $email
     * @return string
     */
    public function getRecipientsNameByEmail($email)
    {
        $recipients = [];

        foreach ($this->mailMessage->getTo() as $address) {
            $recipients[$address->getEmail()] = $address->getName();
        }

        foreach ($this->mailMessage->getCc() as $address) {
            $recipients[$address->getEmail()] = $address->getName();
        }
        foreach ($this->mailMessage->getBcc() as $address) {
            $recipients[$address->getEmail()] = $address->getName();
        }

        $recipientName = '';

        if (!empty($recipients[$email])) {
            $recipientName = $recipients[$email];
        }

        return $recipientName;
    }

    /**
     * Log emails
     *
     * @return $this|bool
     */
    public function prepareMessage()
    {
        $this->benchmark->start(__METHOD__);

        if (!$this->config->isEnable()
            || !$this->config->getConfig(Config::ENHANCED_SMTP_LOG_EMAIL)
        ) {
            return parent::prepareMessage();
        }

        try {

            if (!$this->isAllowed()) {
                return parent::prepareMessage();
            }

            $result = parent::prepareMessage();

            if ($this->config->versionCompare('2.2.8')) {
                $this->mailMessage = \Zend\Mail\Message::fromString($this->message->getRawMessage());

                $recipients = $this->getRecipients();

            } else {
                $recipients = $this->message->getRecipients();
            }

            $logIdArray = [];

            foreach ($recipients as $key => $recipient) {
                $emailLog = $this->initEmailLog();

                if ($this->config->versionCompare('2.2.8')) {
                    $name = $this->getRecipientsNameByEmail($recipient);
                } else {
                    $name = $this->getRecipientName($key);
                }

                $logId = $this->sendLog($emailLog, $recipient, $name);
                $logIdArray[] = $logId;
            }

            //Set array of log id in registry
            $this->status->setLogId($logIdArray);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        $this->benchmark->end(__METHOD__);

        return isset($result) ? $result : parent::prepareMessage();
    }
}
