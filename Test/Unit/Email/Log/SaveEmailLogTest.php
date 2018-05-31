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
namespace KiwiCommerce\EnhancedSMTP\Test\Unit\Email\Log;

use KiwiCommerce\EnhancedSMTP\Email\Log\SaveEmailLog;
use Magento\Framework\App\TemplateTypesInterface;
use Magento\Framework\Mail\MessageInterface;

class SaveEmailLogTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var int
     */
    public $templateType = TemplateTypesInterface::TYPE_HTML;

    /**
     * @var string
     */
    public $bodyText = 'Sample text';

    /**
     * @var string
     */
    public $templateNamespace = "";

    /**
     * @var string
     */
    public $messageType = MessageInterface::TYPE_HTML;

    /**
     * @var array
     */
    public $vars = ['reason' => 'Reason', 'customer' => 'Customer'];

    /**
     * @var array
     */
    public $options = ['area' => 'frontend', 'store' => 1];
    /**
     * @var array
     */
    public $types = [
        TemplateTypesInterface::TYPE_TEXT => MessageInterface::TYPE_TEXT,
        TemplateTypesInterface::TYPE_HTML => MessageInterface::TYPE_HTML
    ];

    /**
     * @var string
     */
    public $templateIdentifier = "identifier";

    /**
     * @var string
     */
    public $senderEmail = "sender@example.com";

    /**
     * @var string
     */
    public $senderName = 'sender';

    /**
     * @var string
     */
    public $templateId = 'test_template_id';

    /**
     * @var string
     */
    public $moduleName = 'module_name';

    /**
     * @var string
     */
    public $emailTemplate = 'test_email_template';

    /**
     * @var string
     */
    public $emailSubject = 'test_email_subject';

    /**
     * @var int
     */
    public $storeId = 0;

    /**
     * @var string
     */
    public $statusValue = \KiwiCommerce\EnhancedSMTP\Model\Logs\Status::STATUS_PENDING;

    /**
     * @var string
     */
    public $recipientName = "test recipient name";

    /**
     * @var string
     */
    public $recipientEmail = "recipient@example.com";

    /**
     * Template Factory
     *
     * @var FactoryInterface
     */
    protected $templateFactory;

    /**
     * Object Manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Message
     *
     * @var \Magento\Framework\Mail\Message
     */
    protected $message;

    /**
     * Sender resolver
     *
     * @var \Magento\Framework\Mail\Template\SenderResolverInterface
     */
    protected $senderResolver;

    /**
     * @var \Magento\Framework\Mail\TransportInterfaceFactory
     */
    protected $mailTransportFactory;


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
     * @var \KiwiCommerce\EnhancedSMTP\Model\Logs\Status
     */
    public $status;

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
     * @var \KiwiCommerce\EnhancedSMTP\Model\Logs
     */
    public $logs;

    /**
     * @var SaveEmailLog
     */
    public $saveEmailLog;

    public function setUp()
    {
        $this->templateFactory = $this->createMock(\Magento\Framework\Mail\Template\FactoryInterface::class);
        $this->message = $this->createMock(\Magento\Framework\Mail\Message::class);
        $this->senderResolver = $this->createMock(\Magento\Framework\Mail\Template\SenderResolverInterface::class);
        $this->objectManager = $this->createMock(\Magento\Framework\ObjectManagerInterface::class);

        $this->mailTransportFactory = $this->getMockBuilder(\Magento\Framework\Mail\TransportInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->logsFactory = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Model\LogsFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->storeManager = $this->getMockBuilder(\Magento\Store\Model\StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Helper\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requests = $this->getMockBuilder(\Magento\Framework\App\Request\Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->status = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Model\Logs\Status::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->logger = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Logger\Logger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->logs = $this->createPartialMock(\KiwiCommerce\EnhancedSMTP\Model\Logs::class, [
            'create',
            'setSenderEmail',
            'setSenderName',
            'setTemplateId',
            'setModuleName',
            'setEmailTemplate',
            'setEmailSubject',
            'setStoreId',
            'setStatus',
            'setRecipientName',
            'setRecipientEmail',
            'save'
        ]);

        $this->saveEmailLog = new SaveEmailLog(
            $this->templateFactory,
            $this->message,
            $this->senderResolver,
            $this->objectManager,
            $this->mailTransportFactory,
            $this->logsFactory,
            $this->storeManager,
            $this->requests,
            $this->config,
            $this->status,
            $this->logger
        );
    }

    public function testPrepareMessage()
    {
        $this->config->expects($this->once())
            ->method('isEnable')
            ->willReturn(true);

        $this->config->expects($this->once())
            ->method('getConfig')
            ->with(\KiwiCommerce\EnhancedSMTP\Helper\Config::ENHANCED_SMTP_LOG_EMAIL)
            ->willReturn(true);

        $this->saveEmailLog->setTemplateModel($this->templateNamespace);
        $this->saveEmailLog->setTemplateIdentifier($this->templateIdentifier);
        $this->saveEmailLog->setTemplateVars($this->vars);
        $this->saveEmailLog->setTemplateOptions($this->options);

        $template = $this->createMock(\Magento\Framework\Mail\TemplateInterface::class);
        $template->expects($this->once())->method('setVars')->with($this->equalTo($this->vars))->willReturnSelf();
        $template->expects($this->once())->method('setOptions')->with($this->equalTo($this->options))->willReturnSelf();
        $template->expects($this->once())->method('getSubject')->willReturn('Email Subject');
        $template->expects($this->once())->method('getType')->willReturn($this->templateType);
        $template->expects($this->once())->method('processTemplate')->willReturn($this->bodyText);

        $this->templateFactory->expects($this->once())
            ->method('get')
            ->with($this->equalTo('identifier'), $this->equalTo($this->templateNamespace))
            ->willReturn($template);

        $this->message->expects($this->any())
            ->method('setSubject')
            ->with($this->equalTo('Email Subject'))
            ->willReturnSelf();
        $this->message->expects($this->any())
            ->method('setMessageType')
            ->with($this->equalTo($this->messageType))
            ->willReturnSelf();
        $this->message->expects($this->any())
            ->method('setBody')
            ->with($this->equalTo($this->bodyText))
            ->willReturnSelf();

        $this->message->expects($this->any())
            ->method('getHeaders')
            ->willReturn(['From' => [$this->senderName.'<'.$this->senderEmail]]);

        $transport = $this->createMock(\Magento\Framework\Mail\TransportInterface::class);

        $this->mailTransportFactory->expects($this->any())
            ->method('create')
            ->with($this->equalTo(['message' => $this->message]))
            ->willReturn($transport);

        $this->objectManager->expects($this->any())
            ->method('create')
            ->with($this->equalTo(\Magento\Framework\Mail\Message::class))
            ->willReturn($transport);

        $this->message->expects($this->any())
            ->method('getFrom')
            ->willReturn($this->senderEmail);

        $this->requests->expects($this->any())
            ->method('getModuleName')
            ->willReturn($this->moduleName);

        $this->message->expects($this->any())
            ->method('getBody')
            ->willReturnSelf();

        $this->logsFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->logs);

        $this->logs->expects($this->any())
            ->method('setSenderEmail')
            ->with($this->senderEmail)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('setSenderName')
            ->with($this->senderName)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('setTemplateId')
            ->with($this->templateIdentifier)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('setModuleName')
            ->with($this->moduleName)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('setEmailTemplate')
            ->with($this->emailTemplate)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('setEmailSubject')
            ->with($this->emailSubject)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('setStoreId')
            ->with($this->storeId)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('setStatus')
            ->with($this->statusValue)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('setRecipientName')
            ->with($this->recipientName)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('setRecipientEmail')
            ->with($this->recipientEmail)
            ->willReturnSelf();

        $this->logs->expects($this->any())
            ->method('save')
            ->with($this->logs)
            ->willReturnSelf();

        $this->saveEmailLog->prepareMessage();
    }
}
