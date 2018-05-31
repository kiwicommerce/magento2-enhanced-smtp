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
use KiwiCommerce\EnhancedSMTP\Api\LogsRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Email
 * @package KiwiCommerce\EnhancedSMTP\Controller\Adminhtml\Emaillog
 */
class Email extends Action
{
    /**
     * @var LogsRepositoryInterface
     */
    public $logs;

    /**
     * @var JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var TimezoneInterface
     */
    public $timezone;

    /**
     * Email constructor.
     *
     * @param Context $context
     * @param LogsRepositoryInterface $logsRepository
     * @param JsonFactory $resultJsonFactory
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        Context $context,
        LogsRepositoryInterface $logsRepository,
        JsonFactory $resultJsonFactory,
        TimezoneInterface $timezone
    ) {
        $this->logs = $logsRepository;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->timezone = $timezone;

        return parent::__construct($context);
    }

    /**
     * Retrieve email log details
     *
     * @return mixed
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $id = $this->getRequest()->getParam('id');
        $log = $this->logs->getById($id);

        if ($log->getId()) {
            $data = $log->getData();

            //Unset Email Template
            unset($data['email_template']);

            //Set Body URL
            $data['body_url'] = $this->getUrl('*/*/body', ['id' => $id]);

            //Convert Datetime Into Timezone
            $date = $this->timezone->date(new \DateTime($data['created_at']));

            $data['created_at'] = $date->format('M d,Y H:i:s A');

            return $result->setData($data);
        } else {
            return $result->setData(['error' => __('Not Found!')]);
        }
    }
}
