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
namespace KiwiCommerce\EnhancedSMTP\Test\Unit\Model;

use KiwiCommerce\EnhancedSMTP\Model\SendEmail;

class SendEmailTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    public $toEmail = 'test1@example.com';

    /**
     * @var string
     */
    public $fromEmail = "sender@example.com";

    /**
     * @var string
     */
    public $fromEmailName = "Sender";

    /**
     * @var string
     */
    public $templateId = 'enhancedsmtp_test_email_template';

    /**
     * @var int
     */
    public $storeId = 0;

    /**
     * @var SendEmail
     */
    public $sendEmail;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    public $transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    public $inlineTranslation;

    public function setUp()
    {
        $this->storeManager = $this->getMockBuilder(\Magento\Store\Model\StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transportBuilder = $this->getMockBuilder(\Magento\Framework\Mail\Template\TransportBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->inlineTranslation = $this->getMockBuilder(\Magento\Framework\Translate\Inline\StateInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->store = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sendEmail = new SendEmail(
            $this->storeManager,
            $this->transportBuilder,
            $this->inlineTranslation
        );
    }

    public function testSendEmail()
    {
        $this->storeManager->expects($this->exactly(2))
            ->method('getStore')
            ->with($this->storeId)
            ->willReturn($this->store);

        $templateOptions = ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->storeId];
        $templateVars = [
            'store' => $this->store
        ];

        $to = [$this->toEmail];
        $from = ['email' => $this->fromEmail, 'name' => $this->fromEmailName];

        $transport = $this->getMockBuilder(\Magento\Framework\Mail\TransportInterface::class)
            ->getMock();

        $this->transportBuilder->expects($this->once())
            ->method('setTemplateIdentifier')
            ->with($this->templateId)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateOptions')
            ->with($templateOptions)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateVars')
            ->with($templateVars)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setFrom')
            ->with($from)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('addTo')
            ->with($to)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('getTransport')
            ->willReturn($transport);

        $transport->expects($this->once())
            ->method('sendMessage');

        $this->assertEquals($this->sendEmail, $this->sendEmail->sendEmail($this->toEmail, $from));
    }
}
