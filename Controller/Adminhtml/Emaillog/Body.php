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

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use KiwiCommerce\EnhancedSMTP\Api\LogsRepositoryInterface;

/**
 * Class Body
 * @package KiwiCommerce\EnhancedSMTP\Controller\Adminhtml\Emaillog
 */
class Body extends Action
{
    /**
     * Index resultPageFactory.
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @var LogsRepositoryInterface
     */
    public $log;

    /**
     * Index constructor.
     *
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     * @param LogsRepositoryInterface $log
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LogsRepositoryInterface $log
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->log = $log;

        parent::__construct($context);
    }

    /**
     * Preview email template
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $emailTemplate = $this->log->getById($id)->getEmailTemplate();
        return $this->getResponse()->setBody($emailTemplate);
    }
}
