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
namespace KiwiCommerce\EnhancedSMTP\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class TestEmail
 * @package KiwiCommerce\EnhancedSMTP\Block\Adminhtml\System\Config
 */
class SmtpProvider extends Field
{
    /**
     * @var string
     */
    public $buttonLabel = '';

    /**
     * Set template
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('KiwiCommerce_EnhancedSMTP::system/config/smtp-provider.phtml');
    }

    /**
     * Get Element Html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function _getElementHtml(AbstractElement $element)
    {
        $this->addData([
            'html_id' => $element->getHtmlId(),
            'smtp_provider' => $this->getListOfSmtpProvider(),
            'smtp_data_info' => json_encode($this->getListOfSmtpProvider())
        ]);


        return $this->_toHtml();
    }

    /**
     * Get list of all smtp
     *
     * @return array
     */
    private function getListOfSmtpProvider()
    {
        $options = [
            [
                'label' => __('- Choose a SMTP Provider -'),
                'host' => ''
            ],
            [
                'label' => __('Custom'),
                'host' => '',
                'port' => '',
                'protocol' => ''
            ],
            [
                'label' => __('Gmail, GSuite'),
                'host' => 'smtp.gmail.com',
                'port' => '465',
                'protocol' => 'ssl',
                'userguide' => 'https://support.google.com/a/answer/176600'
            ],
            [
                'label' => __('Mailgun'),
                'host' => 'smtp.mailgun.org',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://documentation.mailgun.com/en/latest/user_manual.html#sending-via-smtp'

            ],
            [
                'label' => __('Mandrill'),
                'host' => 'smtp.mandrillapp.com',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://mandrill.zendesk.com/hc/en-us/articles/205582197-Where-do-I-find-my-SMTP-credentials-'

            ],
            [
                'label' => __('Sendinblue'),
                'host' => 'smtp-relay.sendinblue.com',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://help.sendinblue.com/hc/en-us'
            ],
            [
                'label' => __('Sendgrid'),
                'host' => 'smtp.sendgrid.net',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://support.sendgrid.com/hc/en-us'
            ],
            [
                'label' => __('Elastic Email'),
                'host' => 'smtp.elasticemail.com',
                'port' => '2525',
                'protocol' => '',
                'userguide' => 'https://elasticemail.com/resources/settings/smtp-api/'
            ],
            [
                'label' => __('SparkPost'),
                'host' => 'smtp.sparkpostmail.com',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://www.sparkpost.com/docs/getting-started/getting-started-sparkpost/'
            ],
            [
                'label' => __('Mailjet'),
                'host' => 'in-v3.mailjet.com',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://www.mailjet.com/support/how-can-i-configure-my-smtp-parameters,2.htm'
            ],
            [
                'label' => __('Postmark'),
                'host' => 'smtp.postmarkapp.com',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://postmarkapp.com/developer/user-guide/sending-email/sending-with-smtp'
            ],
            [
                'label' => __('AOL Mail'),
                'host' => 'smtp.aol.com',
                'port' => '587',
                'protocol' => '',
                'userguide' => 'https://help.aol.com/articles/aol-mail-account-and-password'
            ],
            [
                'label' => __('Comcast'),
                'host' => 'smtp.comcast.net',
                'port' => '587',
                'protocol' => '',
                'userguide' => 'https://www.xfinity.com/support/articles/email-client-programs-with-xfinity-email'
            ],
            [
                'label' => __('GMX'),
                'host' => 'mail.gmx.net',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://support.gmx.com/pop-imap/pop3/index.html#indexlink_help_pop-imap'
            ],
            [
                'label' => __('Hotmail'),
                'host' => 'smtp-mail.outlook.com',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://support.office.com/en-us/article/POP-IMAP-and-SMTP-settings-for-Outlook-com-d088b986-291d-42b8-9564-9c414e2aa040'
            ],
            [
                'label' => __('Mail.com'),
                'host' => 'smtp.mail.com',
                'port' => '587',
                'protocol' => '',
                'userguide' => 'https://support.mail.com/premium/imap/imap.html'
            ],
            [
                'label' => __('O2 Mail'),
                'host' => 'smtp.o2.ie',
                'port' => '25',
                'protocol' => '',
                'userguide' => 'http://service.o2.co.uk/IQ/SRVS/CGI-BIN/WEBCGI.EXE?New,Kb=Companion,question=ref(User):str(Broadband),T=Broadband_Case,CASE=24839'
            ],
            [
                'label' => __('Office365'),
                'host' => 'smtp.office365.com',
                'port' => '587',
                'protocol' => '',
                'userguide' => 'https://support.office.com/en-us/article/POP-IMAP-and-SMTP-settings-for-Outlook-com-d088b986-291d-42b8-9564-9c414e2aa040'
            ],
            [
                'label' => __('Orange'),
                'host' => 'smtp.orange.net',
                'port' => '25',
                'protocol' => '',
                'userguide' => ''
            ],
            [
                'label' => __('Outlook'),
                'host' => 'smtp-mail.outlook.com',
                'port' => '587',
                'protocol' => 'tls',
                'userguide' => 'https://support.office.com/en-us/article/POP-IMAP-and-SMTP-settings-for-Outlook-com-d088b986-291d-42b8-9564-9c414e2aa040'
            ],
            [
                'label' => __('Yahoo Mail'),
                'host' => 'smtp.mail.yahoo.com',
                'port' => '465',
                'protocol' => 'ssl',
                'userguide' => 'https://help.yahoo.com/kb/SLN4724.html'
            ],
            [
                'label' => __('Yahoo Mail Plus'),
                'host' => 'plus.smtp.mail.yahoo.com',
                'port' => '465',
                'protocol' => 'ssl',
                'userguide' => 'https://au.help.yahoo.com/kb/SLN4724.html'
            ],
            [
                'label' => __('Yahoo AU/NZ'),
                'host' => 'smtp.mail.yahoo.com.au',
                'port' => '465',
                'protocol' => 'ssl',
                'userguide' => 'https://au.help.yahoo.com/kb/SLN4724.html'
            ],
            [
                'label' => __('AT&T'),
                'host' => 'smtp.att.yahoo.com',
                'port' => '465',
                'protocol' => 'ssl',
                'userguide' => 'https://www.att.com/esupport/article.html#!/u-verse-tv/KM1010523'
            ],
            [
                'label' => __('NTL @ntlworld.com'),
                'host' => 'smtp.ntlworld.com',
                'port' => '465',
                'protocol' => 'ssl',
                'userguide' => ''
            ],
            [
                'label' => __('BT Connect'),
                'host' => 'pop3.btconnect.com',
                'port' => '25',
                'protocol' => '',
                'userguide' => ''
            ],
            [
                'label' => __('Zoho Mail'),
                'host' => 'smtp.zoho.com',
                'port' => '465',
                'protocol' => 'ssl',
                'userguide' => 'https://www.zoho.com/mail/help/zoho-smtp.html'
            ],
            [
                'label' => __('Verizon'),
                'host' => 'outgoing.verizon.net',
                'port' => '465',
                'protocol' => 'ssl',
                'userguide' => 'https://www.verizon.com/support/consumer/email/settings/RK=2/RS=_dFiIjDkw9mkm81gv5I6vDDm9ww-'
            ],
            [
                'label' => __('BT Openworld'),
                'host' => 'mail.btopenworld.com',
                'port' => '25',
                'protocol' => '',
                'userguide' => ''
            ],
            [
                'label' => __('O2 Online Deutschland'),
                'host' => 'mail.o2online.de',
                'port' => '25',
                'protocol' => '',
                'userguide' => 'https://hilfe.o2online.de/docs/DOC-2924'
            ],
            [
                'label' => __('OVH'),
                'host' => 'ssl0.ovh.net',
                'port' => '465',
                'protocol' => 'ssl',
                'userguide' => 'https://www.ovh.com/world/g1286.ovh_email_configuration_on_outlook_2013#configure_outlook_2013_part_5_account_settings_-_popimap'
            ],
        ];

        return $options;
    }
}
