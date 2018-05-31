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
namespace KiwiCommerce\EnhancedSMTP\Cron;

use Magento\Framework\Stdlib\DateTime\DateTime;
use KiwiCommerce\EnhancedSMTP\Helper\Config;
use KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs\CollectionFactory;
use KiwiCommerce\EnhancedSMTP\Logger\Logger;
use Magento\Framework\Api\SearchCriteriaBuilder;
use KiwiCommerce\EnhancedSMTP\Api\LogsRepositoryInterface;

/**
 * Class ClearLog
 * @package KiwiCommerce\EnhancedSMTP\Cron
 */
class ClearLog
{
    /**
     * Default date format
     * @var string
     */
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var DateTime
     */
    public $dateTime;

    /**
     * @var Config
     */
    public $helper;

    /**
     * @var CollectionFactory
     */
    public $collectionLogFactory;

    /**
     * @var Logger
     */
    public $logger;

    /**
     * ClearLog constructor.
     *
     * @param DateTime $dateTime
     * @param Config $config
     * @param CollectionFactory $collectionLogFactory
     * @param SearchCriteriaBuilder $searchCriteria
     * @param LogsRepositoryInterface $logsRepository
     * @param Logger $logger
     */
    public function __construct(
        DateTime $dateTime,
        Config $config,
        CollectionFactory $collectionLogFactory,
        SearchCriteriaBuilder $searchCriteria,
        LogsRepositoryInterface $logsRepository,
        Logger $logger
    ) {
        $this->dateTime = $dateTime;
        $this->helper = $config;
        $this->collectionLogFactory = $collectionLogFactory;
        $this->logger = $logger;
        $this->searchCriteria = $searchCriteria;
        $this->logsRepository = $logsRepository;
    }

    /**
     * Return cron cleanup date
     *
     * @return null|string
     */
    public function __getDate()
    {
        $timestamp = $this->dateTime->gmtTimestamp();
        $day = $this->helper->getConfig(Config::CLEAR_EMAIL_LOG_DURATION);
        if ($day) {
            $timestamp -= $day * 24 * 60 * 60;
            return $this->dateTime->gmtDate(self::DATE_FORMAT, $timestamp);
        }
        return null;
    }

    /**
     * Methods for clear email log
     *
     * @return bool
     */
    public function execute()
    {
        try {
            if (!$this->helper->isEnable()) {
                return false;
            }

            $this->logger->info("Enhanced SMTP Cron Clear Log Started");

            if ($date = $this->__getDate()) {
                $search = $this->searchCriteria->addFilter('created_at', $date, 'lteq')->create();
                $logs = $this->logsRepository->getList($search);

                if ($logs->getTotalCount()) {
                    foreach ($logs->getItems() as $log) {
                        //Remove email log
                        $log->delete();
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
        return true;
    }
}
