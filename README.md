# We're not maintaining this extension, if you need any support please contact us at hello@kiwicommerce.co.uk

# Magento 2 - Enhanced SMTP by [KiwiCommerce](https://kiwicommerce.co.uk/)
- Delivering messages is an essential and mandatory part of running an e-Commerce business. Magento sends hundreds and thousands of emails on a daily basis. Reliability of the email sending process should be as stable as possible. All emails must be delivered to recipients without delays.
- Magento-based store owners often struggle when it comes to sending transactional emails. The default email server of inherent hosting will be used to send unregistered emails from unauthorized senders. These emails that are sent by default Magento 2, will probably end up in the Spam box. As a result, these emails cannot approach your customers.
- This extension lets you use any third-party SMTP server for your store and configure all the necessary settings to avoid this problem. You can use any reliable SMTP server to give your emails higher chances to be delivered directly to your customers.
- This extension includes pre-configured settings for 25+ most popular SMTP providers.
- This extension provides you with a feature to track all sent emails by inserting email log.

## Installation
 1. Composer Installation
      - Navigate to your Magento root folder<br />
            `cd path_to_the_magento_root_directory`
      - Then run the following command<br />
          `composer require kiwicommerce/module-enhanced-smtp`
      - Make sure that composer finished the installation without errors

 2. Command Line Installation
      - Backup your web directory and database
      - Download Enhanced SMTP installation package from <a href="https://github.com/kiwicommerce/magento2-enhanced-smtp/releases/download/v1.0.2/kiwicommerce-enhanced-smtp-v102.zip">here</a>
      - Upload contents of the Enhanced SMTP Log installation package to your Magento root directory
      - Navigate to your Magento root folder<br />
          `cd path_to_the_magento_root_directory`<br />
      - Then run the following command<br />
          `php bin/magento module:enable KiwiCommerce_EnhancedSMTP`
   
- After install the extension, run the following command
```
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento cache:flush
```
- Log out from the backend and log in again.
          
Find More details on [KiwiCommerce](https://kiwicommerce.co.uk/extensions/magento2-enhanced-smtp/)

## Where will it appear in the Admin Panel
- Go to **System > Enhanced SMTP by KiwiCommerce > Email Logs**. Here you can See the list of sent mail logs.

![Email Log](https://kiwicommerce.co.uk/docs/img/enhanced_smtp/email_log.png)

- By clicking on **View** option in each mail log, you shall get to view your mail like a customer.

![Email Popup](https://kiwicommerce.co.uk/docs/img/enhanced_smtp/email_popup.png)


## Configuration
### SMTP Configuration
Go to **System > Enhanced SMTP by KiwiCommerce > Configuration**. Open SMTP Configuration section

![SMTP Configuration](https://kiwicommerce.co.uk/docs/img/enhanced_smtp/smtp-configuration-section.png)

### Advanced Configuration
This section is placed right under SMTP Configure Section.

![Advanced Configuration](https://kiwicommerce.co.uk/docs/img/enhanced_smtp/advanced-configuration-section.png)

## Need Additional Features?
Feel free to get in touch with us at https://kiwicommerce.co.uk/get-in-touch/

## Other KiwiCommerce Extensions
* [Magento 2 Cron Scheduler](https://kiwicommerce.co.uk/extensions/magento2-cron-scheduler/)
* [Magento 2 Login As Customer](https://kiwicommerce.co.uk/extensions/magento2-login-as-customer/)
* [Magento 2 Inventory Log](https://kiwicommerce.co.uk/extensions/magento2-inventory-log/)
* [Magento 2 Admin Activity](https://kiwicommerce.co.uk/extensions/magento2-admin-activity/)

## Contribution
Well unfortunately there is no formal way to contribute, we would encourage you to feel free and contribute by:
 
  - Creating bug reports, issues or feature requests on [Github](https://github.com/kiwicommerce/magento2-enhanced-smtp/issues)
  - Submitting pull requests for improvements.
    
We love answering questions or doubts simply ask us in issue section. We're looking forward to hearing from you!
 
  - Follow us [@KiwiCommerce](https://twitter.com/KiwiCommerce)
  - <a href="mailto:support@kiwicommerce.co.uk">Email Us</a>
  - Have a look at our [documentation](https://kiwicommerce.co.uk/docs/enhanced-smtp/)


