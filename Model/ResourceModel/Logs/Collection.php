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
namespace KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Initialize Model and Resource Model
     */
    protected function _construct()
    {
        $this->_init(
            'KiwiCommerce\EnhancedSMTP\Model\Logs',
            'KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs'
        );
    }
}
