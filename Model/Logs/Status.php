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
namespace KiwiCommerce\EnhancedSMTP\Model\Logs;

use KiwiCommerce\EnhancedSMTP\Api\Data\LogInterfaceFactory;
use Magento\Framework\Registry;

/**
 * Class Status
 * @package KiwiCommerce\EnhancedSMTP\Model\Logs
 */
class Status
{
    /**
     * @var string
     */
    const STATUS_SUCCESS = 'Success';

    /**
     * @var string
     */
    const STATUS_PENDING = 'Pending';

    /**
     * @var string
     */
    const STATUS_FAILD = 'Failed';

    /**
     * @var LogInterfaceFactory
     */
    public $logInterfaceFactory;

    /**
     * @var Registry
     */
    public $registry;

    /**
     * Status constructor.
     *
     * @param LogInterfaceFactory $logInterfaceFactory
     * @param Registry $registry
     */
    public function __construct(
        LogInterfaceFactory $logInterfaceFactory,
        Registry $registry
    ) {
        $this->logInterfaceFactory = $logInterfaceFactory;
        $this->registry = $registry;
    }

    /**
     * Set Log Id through Registry
     *
     * @param $logId
     * @return void
     */
    public function setLogId($logIdArray)
    {
        $this->unsetLogId();
        return $this->registry->register("smtp_email_log_id", $logIdArray);
    }

    /**
     * Get Log Id through Registry
     *
     * @return array
     */
    public function getLogId()
    {
        return $this->registry->registry("smtp_email_log_id");
    }

    /**
     * Unset Log Id through Registry
     *
     * @return void
     */
    public function unsetLogId()
    {
        return $this->registry->unregister("smtp_email_log_id");
    }

    /**
     * Check Log Id is valid or not
     *
     * @return int|bool
     */
    public function isLogIdValid($logId)
    {
        if (!empty($logId) && $logId > 0) {
            return $logId;
        }
        return false;
    }

    /**
     * To Set Status Success In Email Log
     *
     * @return bool
     */
    public function setStatusSuccess()
    {
        $logIdArray = $this->getLogId();
        $this->unsetLogId();
        if (is_array($logIdArray)) {
            foreach ($logIdArray as $logId) {
                $logIdValid = $this->isLogIdValid($logId);

                if ($logIdValid) {
                    $emailLog = $this->logInterfaceFactory->create()->load($logIdValid);
                    $emailLog->setStatus(self::STATUS_SUCCESS);
                    $emailLog->save();
                    $emailLog->unsetData();
                }
            }
        }
        return true;
    }

    /**
     * To Set Status Failed In Email Log
     *
     * @param $errorMessage
     * @return bool
     */
    public function setStatusFailed($errorMessage)
    {
        $logIdArray = $this->getLogId();
        $this->unsetLogId();
        if (is_array($logIdArray)) {
            foreach ($logIdArray as $logId) {
                $logIdValid = $this->isLogIdValid($logId);

                if ($logIdValid) {
                    $emailLog = $this->logInterfaceFactory->create()->load($logIdValid);
                    $emailLog->setStatus(self::STATUS_FAILD);
                    $emailLog->setRemarks($errorMessage);
                    $emailLog->save();
                    $emailLog->unsetData();
                }
            }
        }
        return true;
    }
}
