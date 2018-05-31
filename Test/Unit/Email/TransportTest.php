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
namespace KiwiCommerce\EnhancedSMTP\Test\Unit\Email;

use KiwiCommerce\EnhancedSMTP\Email\Transport;
use KiwiCommerce\EnhancedSMTP\Helper\Config;

class TransportTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    public $smtpHost = "host";

    /**
     * @var array
     */
    public $smtpConf = [
        'auth' => 'Login',
        'ssl' => 'tls',
        'port' => 2525,
        'username' => 'test_username',
        'password' => 'test_password'
    ];

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Email\Transport
     */
    public $transport;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Helper\Config
     */
    public $config;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Logger\Logger
     */
    public $logger;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Model\Logs\Status
     */
    public $status;

    /**
     * @var \Magento\Framework\Mail\TransportInterface
     */
    public $transportInterface;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Cms\Model\Page
     */
    public $logs;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Cms\Model\Page
     */
    public $zendTransportMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Cms\Model\Page
     */
    public $zendMailMock;

    public function setUp()
    {
        $this->config = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Helper\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->logger = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Logger\Logger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->status = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Model\Logs\Status::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transportInterface = $this->createMock(\Magento\Framework\Mail\TransportInterface::class);
        $this->zendMailMock =  $this->createMock(\Zend_Mail::class);

        $this->transport = new Transport(
            $this->config,
            $this->logger,
            $this->status
        );
    }

    public function testAroundSendMessageIsEnableFalse()
    {
        $this->config->expects($this->once())
            ->method('isEnable')
            ->willReturn(false);

        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $callbackMock->expects($this->once())->method('__invoke');
        $this->assertNull($this->transport->aroundSendMessage($this->transportInterface, $callbackMock));
    }
}
