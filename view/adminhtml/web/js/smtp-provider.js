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
    "mage/translate",
    "jquery/ui"
], function (jQuery, $t) {
    "use strict";

    function main(config) {

        var jsonSmtpProviderInfo = config.jsonSmtpProviderInfo,
            selectId = "#"+config.selectId;
        jQuery(document).ready(function () {
            jQuery("#row_enhancedsmtp_smtpconfig_smtp_provider .value .note").append('<p id="smtp_userguide"></p>');
        });

        jQuery(selectId).on('change', function () {
            var selectOptionValue = jQuery(this).val();
            if (selectOptionValue) {
                var providerData = jsonSmtpProviderInfo[selectOptionValue];
                if (providerData) {
                    var userguideHtml = '';

                    if (providerData.userguide) {
                        userguideHtml = "<b>"+$t("User Guide")+": </b>" +
                            "<a href='"+providerData.userguide+"' target='_blank'>"+providerData.userguide+"</a>";

                        jQuery('#smtp_userguide').html(userguideHtml);
                    } else {
                        jQuery('#smtp_userguide').html(userguideHtml);
                    }

                    jQuery('#enhancedsmtp_smtpconfig_hostname').val(providerData.host);
                    jQuery('#enhancedsmtp_smtpconfig_port').val(providerData.port);
                    jQuery('#enhancedsmtp_smtpconfig_protocol').val(providerData.protocol);
                    jQuery('#enhancedsmtp_smtpconfig_auth').val('Login');
                }
            }
        });
    }
    return main;
});
