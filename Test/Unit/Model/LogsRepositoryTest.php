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
namespace KiwiCommerce\EnhancedSMTP\Test\Unit\Model;

use KiwiCommerce\EnhancedSMTP\Model\LogsRepository;
use Magento\Framework\Api\SortOrder;

class LogsRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    public $logId = '123';

    /**
     * @var LogsRepository
     */
    public $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs
     */
    public $logsResource;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\KiwiCommerce\EnhancedSMTP\Api\Data\LogSearchResultsInterfaceFactory
     */
    public $logsSearchResult;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|(\KiwiCommerce\EnhancedSMTP\Model\Logs
     */
    public $logs;

    /**
     * @var \KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs\Collection
     */
    public $collection;

    /**
     * Initialize Log Repository
     */
    public function setUp()
    {
        $this->logsResource = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs::class)
            ->disableOriginalConstructor()
            ->getMock();

        $logInterfaceFactory = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Api\Data\LogInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $logCollectionFactory = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs\CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $searchResultsFactory = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Api\Data\LogSearchResultsInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->logs = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Model\Logs::class)->disableOriginalConstructor()->getMock();
        $this->logsSearchResult = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Api\Data\LogSearchResultsInterface::class)
            ->getMock();

        $this->collection = $this->getMockBuilder(\KiwiCommerce\EnhancedSMTP\Model\ResourceModel\Logs\Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSize', 'setCurPage', 'setPageSize', 'load', 'addOrder'])
            ->getMock();

        $logInterfaceFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->logs);

        $searchResultsFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->logsSearchResult);

        $logCollectionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->collection);

        $this->repository = new LogsRepository(
            $logInterfaceFactory,
            $logCollectionFactory,
            $this->logsResource,
            $searchResultsFactory
        );
    }

    /**
     * @test
     */
    public function testSave()
    {
        $this->logsResource->expects($this->once())
            ->method('save')
            ->with($this->logs)
            ->willReturnSelf();
        $this->assertEquals($this->logs, $this->repository->save($this->logs));
    }

    /**
     * @test
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testGetByIdException()
    {
        $this->logs->expects($this->once())
            ->method('getId')
            ->willReturn(false);
        $this->logs->expects($this->once())
            ->method('load')
            ->with($this->logId)
            ->willReturnSelf();
        $this->repository->getById($this->logId);
    }

    /**
     * @test
     */
    public function testDeleteById()
    {
        $this->logs->expects($this->once())
            ->method('getId')
            ->willReturn(true);
        $this->logs->expects($this->once())
            ->method('load')
            ->with($this->logId)
            ->willReturnSelf();
        $this->logsResource->expects($this->once())
            ->method('delete')
            ->with($this->logs)
            ->willReturnSelf();

        $this->assertTrue($this->repository->deleteById($this->logId));
    }

    /**
     * @test
     *
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     */
    public function testSaveException()
    {
        $this->logsResource->expects($this->once())
            ->method('save')
            ->with($this->logs)
            ->willThrowException(new \Exception());
        $this->repository->save($this->logs);
    }

    /**
     * @test
     *
     * @expectedException \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function testDeleteException()
    {
        $this->logsResource->expects($this->once())
            ->method('delete')
            ->with($this->logs)
            ->willThrowException(new \Exception());
        $this->repository->delete($this->logs);
    }
}
