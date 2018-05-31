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
namespace KiwiCommerce\EnhancedSMTP\Ui\Component\Grid\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use KiwiCommerce\EnhancedSMTP\Model\Logs\Status as EmailStatus;

/**
 * Class Status
 * @package KiwiCommerce\EnhancedSMTP\Ui\Component\Grid\Column
 */
class Status extends Column
{
    /**
     * Status constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
    
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if ($item[$this->getData('name')] == EmailStatus::STATUS_SUCCESS) {
                    $item[$this->getData('name')] = '<span class="grid-severity-notice"><span>'.$item[$this->getData('name')]. '</span></span>';
                } elseif ($item[$this->getData('name')] == EmailStatus::STATUS_FAILD) {
                    $item[$this->getData('name')] = '<span class="grid-severity-critical" title="'. $item['remarks'] .'"><span>'.$item[$this->getData('name')]. '</span></span>';
                } else {
                    $item[$this->getData('name')] = '<span class="grid-severity-minor"><span>'.$item[$this->getData('name')]. '</span></span>';
                }
            }
        }
        return $dataSource;
    }
}
