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
define([
    "jquery",
    "Magento_Ui/js/modal/alert",
    "mage/translate",
    "jquery/ui"
], function (jQuery, alert, $t) {
    "use strict";

    function main(config) {

        var ajaxUrl = config.ajaxUrl;

        jQuery('#enhancedsmtp_advanced_test_email').click(function (e) {
            e.preventDefault();
            var testEmail = jQuery('#kiwicommerce_enhancedsmtp_test_email').val();

            if (testEmail) {
                jQuery.ajax({
                    url: ajaxUrl,
                    data: {
                        test_email: testEmail,
                    },
                    dataType: 'json',
                    showLoader: true,
                    success: function (result) {
                        alert({
                            title: result.status ? $t('Success') : $t('Error'),
                            content: result.content
                        });
                    }
                });
            } else {
                alert({
                    title: $t('Error'),
                    content: $t('Enter Email Address')
                });
            }
        });
    }
    return main;
});
