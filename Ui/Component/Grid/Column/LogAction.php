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

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class LogAction
 * @package KiwiCommerce\EnhancedSMTP\Ui\Component\Grid\Column
 */
class LogAction extends Column
{
    /**
     * @var string
     */
    const URL_PATH_EDIT = '#';

    /**
     * @var string
     */
    const ELEMENT_SELECTOR = 'enhancedsmtp-view-email';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    public $url;

    /**
     * @var array
     */
    public $allowedAttributes = [
        'href',
        'title',
        'id',
        'class',
        'style',
        'data-url',
        'data-id'
    ];

    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    public $escaper;

    /**
     * LogAction constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $url
     * @param \Magento\Framework\Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $url,
        \Magento\Framework\Escaper $escaper,
        array $components = [],
        array $data = []
    ) {
    
        $this->url = $url;
        $this->escaper = $escaper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Escape HTML entities
     *
     * @param string|array $data
     * @param array|null $allowedTags
     * @return string
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        return $this->escaper->escapeHtml($data, $allowedTags);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        return '<a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a></li>';
    }

    /**
     * Get object data by key with calling getter method
     *
     * @param string $key
     * @param mixed $args
     * @return mixed
     */
    public function getDataUsingMethod($key, $args = null)
    {
        $method = 'get' . str_replace(' ', '', ucwords(str_replace(['_','-'], ' ', $key)));
        return $this->{$method}($args);
    }

    /**
     * Prepare link attributes as serialized and formatted string
     *
     * @return string
     */
    public function getLinkAttributes()
    {
        $attributes = [];
        foreach ($this->allowedAttributes as $attribute) {
            $value = $this->getDataUsingMethod($attribute);
            if ($value !== null) {
                $attributes[$attribute] = $this->escapeHtml($value);
            }
        }

        if (!empty($attributes)) {
            return $this->serialize($attributes);
        }

        return '';
    }

    /**
     * Serialize attributes
     *
     * @param   array $attributes
     * @param   string $valueSeparator
     * @param   string $fieldSeparator
     * @param   string $quote
     * @return  string
     */
    public function serialize($attributes = [], $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $data = [];
        foreach ($attributes as $key => $value) {
            $data[] = $key . $valueSeparator . $quote . $value . $quote;
        }

        return implode($fieldSeparator, $data);
    }
    
    /**
     * Initialize parameter for link
     *
     * @param $item
     * @return void
     */
    public function __initLinkParams($item)
    {
        $this->setHref(self::URL_PATH_EDIT);
        $this->setTitle(__('View Email'));
        $this->setLabel(__('View'));
        $this->setDataUrl($this->getAdminViewUrl($item['entity_id']));
        $this->setDataId($item['entity_id']);
        $this->setClass(self::ELEMENT_SELECTOR);
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
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $this->__initLinkParams($item);
                    $item[$this->getData('name')] = $this->_toHtml();
                }
            }
        }
        return $dataSource;
    }

    /**
     * Get View Url
     *
     * @return string
     */
    public function getAdminViewUrl($id)
    {
        return $this->url->getUrl('enhancedsmtp/emaillog/email', ['id' => $id]);
    }
}
