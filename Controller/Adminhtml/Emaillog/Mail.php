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
namespace KiwiCommerce\EnhancedSMTP\Controller\Adminhtml\Emaillog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use KiwiCommerce\EnhancedSMTP\Helper\Config;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use KiwiCommerce\EnhancedSMTP\Model\SendEmail;

/**
 * Class Mail
 * @package KiwiCommerce\EnhancedSMTP\Controller\Adminhtml\Emaillog
 */
class Mail extends Action
{
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $jsonHelper;

    /**
     * @var Config
     */
    public $config;

    /**
     * @var SendEmail
     */
    public $sendEmailModel;

    /**
     * Sender resolver
     *
     * @var \Magento\Framework\Mail\Template\SenderResolverInterface
     */
    public $senderResolver;

    /**
     * Mail constructor
     *
     * @param Context $context
     * @param Data $jsonHelper
     * @param Config $config
     * @param SendEmail $sendEmailModel
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        Config $config,
        SenderResolverInterface $senderResolver,
        SendEmail $sendEmailModel
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->config = $config;
        $this->senderResolver = $senderResolver;
        $this->sendEmailModel = $sendEmailModel;
        parent::__construct($context);
    }

    /**
     * Send Test Email
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $result = ['status' => false];
        $testEmail = $this->getRequest()->getParam('test_email');

        if ($testEmail) {
            // Get Sender Email and Name
            $fromEmail = $this->config->getConfig(Config::ENHANCED_SMTP_TEST_EMAIL_FROM);
            $from= $this->senderResolver->resolve($fromEmail);

            try {
                //Send Email
                $this->sendEmailModel->sendEmail($testEmail, $from);
                $result['status'] = true;
                $result['content'] = __('Email has been sent successfully. Please check your inbox.');
            } catch (\Exception $e) {
                $result['content'] = $e->getMessage();
            }
        } else {
            $result['content'] = __('Test Error');
        }
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($result)
        );
    }
}
