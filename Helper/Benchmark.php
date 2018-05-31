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
namespace KiwiCommerce\EnhancedSMTP\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Benchmark
 * @package KiwiCommerce\EnhancedSMTP\Helper
 */
class Benchmark extends AbstractHelper
{

    /**
     * Get Benchmark is enable or not
     */
    const BENCHMARK_ENABLE = 0;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Logger\Logger
     */
    public $logger;

    /**
     * @var int
     */
    public $startTime;

    /**
     * @var int
     */
    public $endTime;

    /**
     * Config constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \KiwiCommerce\EnhancedSMTP\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \KiwiCommerce\EnhancedSMTP\Logger\Logger $logger
    ) {
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * log info about start time in millisecond
     *
     * @param $method
     * @return void
     */
    public function start($method)
    {
        if (self::BENCHMARK_ENABLE) {
            $this->startTime = round(microtime(true) * 1000);
            $this->logger->info("method:". $method);
            $this->logger->info("start time:". $this->startTime);
        }
    }

    /**
     * log info about end time and time diiference in millisecond
     *
     * @param $method
     * @return void
     */
    public function end($method)
    {
        if (self::BENCHMARK_ENABLE && !empty($this->startTime)) {
            $this->endTime = round(microtime(true) * 1000);
            $diff = $this->endTime - $this->startTime;
            $this->logger->info("method:". $method);
            $this->logger->info("ends time:". $this->endTime);
            $this->logger->info("time difference in millisecond:". $diff);
        }
    }
}
