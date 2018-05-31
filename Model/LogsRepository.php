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

use KiwiCommerce\EnhancedSMTP\Api\LogsRepositoryInterface;
use KiwiCommerce\EnhancedSMTP\Api\Data\LogInterfaceFactory;
use KiwiCommerce\EnhancedSMTP\Api\Data\LogSearchResultsInterfaceFactory;
use KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs\CollectionFactory as LogCollectionFactory;
use KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs as ResourceLogs;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class LogsRepository
 * @package KiwiCommerce\EnhancedSMTP\Model
 */
class LogsRepository implements LogsRepositoryInterface
{
    /**
     * @var LogInterfaceFactory
     */
    public $logInterfaceFactory;

    /**
     * @var LogCollectionFactory
     */
    public $logsCollectionFactory;

    /**
     * @var LogSearchResultsInterfaceFactory
     */
    public $searchResultsFactory;

    /**
     * @var ResourceLogs
     */
    public $resource;

    /**
     * LogsRepository constructor.
     *
     * @param LogInterfaceFactory $logInterfaceFactory
     * @param LogCollectionFactory $logsCollectionFactory
     * @param ResourceLogs $resource
     * @param LogSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        LogInterfaceFactory $logInterfaceFactory,
        LogCollectionFactory $logsCollectionFactory,
        ResourceLogs $resource,
        LogSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->logInterfaceFactory = $logInterfaceFactory;
        $this->logsCollectionFactory = $logsCollectionFactory;
        $this->resource = $resource;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Save data
     *
     * @param \KiwiCommerce\EnhancedSMTP\Api\Data\LogInterface $logs
     * @return Logs
     * @throws CouldNotSaveException
     */
    public function save(\KiwiCommerce\EnhancedSMTP\Api\Data\LogInterface $logs)
    {
        try {
            $this->resource->save($logs);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the email logs : %1',
                $exception->getMessage()
            ));
        }
        return $logs;
    }

    /**
     * Get Email Log Data by ID
     *
     * @param $entityId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($entityId)
    {
        $log =$this->logInterfaceFactory->create()->load($entityId);
        if (!$log->getId()) {
            throw new NoSuchEntityException(__('email log with id "%1" does not exist.', $entityId));
        }
        return $log;
    }

    /**
     * Get Email Log List
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->logsCollectionFactory->create();

        //Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders === null) {
            $sortOrders = [];
        }
        /** @var \Magento\Framework\Api\SortOrder $sortOrder */
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Email Log
     *
     * @param \KiwiCommerce\EnhancedSMTP\Api\Data\LogInterface $logs
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\KiwiCommerce\EnhancedSMTP\Api\Data\LogInterface $logs)
    {
        try {
            $this->resource->delete($logs);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Email Logs: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete Email Log by ID
     *
     * @param $entityId
     * @return bool|mixed
     */
    public function deleteById($entityId)
    {
        return $this->delete($this->getById($entityId));
    }
    
    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param ResourceModel\Logs\Collection $collection
     * @return void
     */
    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs\Collection $collection
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ?: 'eq';
            $conditions[] = [$condition => $filter->getValue()];
            $fields[] = $filter->getField();
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }
}
