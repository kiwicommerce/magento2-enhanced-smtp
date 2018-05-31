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
namespace KiwiCommerce\EnhancedSMTP\Api\Data;

/**
 * Interface LogSearchResultsInterface
 * @package KiwiCommerce\EnhancedSMTP\Api\Data
 */
interface LogSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get email log list.
     *
     * @api
     * @return \KiwiCommerce\EnhancedSMTP\Model\Logs[]
     */
    public function getItems();

    /**
     * Set email log list.
     *
     * @api
     * @param \KiwiCommerce\EnhancedSMTP\Model\Logs[] $items
     * @return $this
     */
    public function setItems(array $items);
}
