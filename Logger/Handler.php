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
namespace KiwiCommerce\EnhancedSMTP\Logger;

/**
 * Class Handler
 * @package KiwiCommerce\EnhancedSMTP\Logger
 */
class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = \KiwiCommerce\EnhancedSMTP\Logger\Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/enhanced_smtp.log';
}
