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
namespace KiwiCommerce\EnhancedSMTP\Model;

use KiwiCommerce\EnhancedSMTP\Api\Data\LogInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Logs
 * @package KiwiCommerce\EnhancedSMTP\Model
 */
class Logs extends AbstractModel implements LogInterface
{
    /**
     * Initialize resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs');
    }
}
