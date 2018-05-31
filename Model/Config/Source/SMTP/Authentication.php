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
namespace KiwiCommerce\EnhancedSMTP\Model\Config\Source\SMTP;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Authentication
 * @package KiwiCommerce\EnhancedSMTP\Model\Config\Source
 */
class Authentication implements ArrayInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => '',
                'label' => __('NONE')
            ],
            [
                'value' => 'Plain',
                'label' => __('PLAIN')
            ],
            [
                'value' => 'Login',
                'label' => __('Login')
            ],
            [
                'value' => 'Crammd5',
                'label' => __('CRAM-MD5')
            ],
        ];

        return $options;
    }
}
