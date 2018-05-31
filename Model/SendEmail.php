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

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;

/**
 * Class LogsRepository
 * @package KiwiCommerce\EnhancedSMTP\Model
 */
class SendEmail
{
    /**
     * @var StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var TransportBuilder
     */
    public $transportBuilder;

    /**
     * @var StateInterface
     */
    public $inlineTranslation;

    /**
     * Test Email Template Name
     */
    const TEST_EMAIL_TEMPLATE = 'enhancedsmtp_test_email_template';

    /**
     * Sens Email constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation
    ) {
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
    }

    /**
     * Send Email
     *
     * @param string $toEmail
     * @param array $from
     * @return mixed
     */
    public function sendEmail($toEmail, $from)
    {
        $this->inlineTranslation->suspend();

        $transport = $this->transportBuilder->setTemplateIdentifier(self::TEST_EMAIL_TEMPLATE)
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId()
            ])
            ->setTemplateVars(['store' => $this->storeManager->getStore()])
            ->setFrom($from)
            ->addTo([$toEmail])
            ->getTransport();
        $transport->sendMessage();

        $this->inlineTranslation->resume();
        return $this;
    }
}
