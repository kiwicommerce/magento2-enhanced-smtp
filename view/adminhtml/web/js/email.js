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
require([
    'jquery',
    'mage/template',
    'text!KiwiCommerce_EnhancedSMTP/templates/email-popup.html',
    'mage/translate',
    'Magento_Ui/js/modal/modal',
    'domReady!'
], function (jquery, mageTemplate, emailPreviewTemplate, $t) {
    var form_key = FORM_KEY;

    jquery(document).on('click', window.viewselector, function (e) {
        var id = jquery(this).data('id');
        var url = jquery(this).data('url');

        jquery.ajax({
            url: url,
            data: {form_key: form_key, isAjax: true},
            showLoader: true,
            complete: function (xhr) {

                var row = JSON.parse(xhr.responseText);
                if (row.error !== null) {
                    var recipient, sender;
                    if (row.recipient_name) {
                        recipient = row.recipient_name+' ('+row.recipient_email+')';
                    } else {
                        recipient = row.recipient_email ? row.recipient_email : '';
                    }

                    if (row.sender_name) {
                        sender = row.sender_name+' ('+row.sender_email+')';
                    } else {
                        sender = row.sender_email ? row.sender_email : '';
                    }

                    var modalHtml = mageTemplate(
                        emailPreviewTemplate, {
                            sender: sender,
                            recipient: recipient,
                            template: row.template_id,
                            date: row.created_at,
                            subject: row.email_subject,
                            body_url: row.body_url
                        }
                    );

                    var previewPopup = jquery('<div/>').html(modalHtml);
                    previewPopup.modal({
                        title: $t('Email Details'),
                        innerScroll: true,
                        modalClass: '_image-box',
                        buttons: []
                    }).trigger('openModal');

                    jquery('div.loader').trigger('processStart');
                }
            }
        });
    });
});