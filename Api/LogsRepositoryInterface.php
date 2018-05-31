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
namespace KiwiCommerce\EnhancedSMTP\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface LogsRepositoryInterface
 * @package KiwiCommerce\EnhancedSMTP\Api
 */
interface LogsRepositoryInterface
{
    /**
     * @param Data\LogInterface $logs
     * @return mixed
     */
    public function save(
        \KiwiCommerce\EnhancedSMTP\Api\Data\LogInterface $logs
    );

    /**
     * @param $entityId
     * @return mixed
     */
    public function getById($entityId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * @param Data\LogInterface $logs
     * @return mixed
     */
    public function delete(
        \KiwiCommerce\EnhancedSMTP\Api\Data\LogInterface $logs
    );

    /**
     * @param $entityId
     * @return mixed
     */
    public function deleteById($entityId);
}
