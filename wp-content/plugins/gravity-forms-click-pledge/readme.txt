=== Gravity Forms Click & Pledge ===
Contributors: Click & Pledge
Plugin Name: Gravity Forms Click & Pledge
Plugin URI: http://clickandpledge.com/
Author URI: http://clickandpledge.com/
Tags: gravityforms, gravity forms, gravity, cnp, clickandpledge, payment gateway, payment module, ecommerce, online payments, Click & Pledge, Click&Pledge, Click, Pledge, Salesforce, Payment, Kamran
Requires at least: 3.7.1
Tested up to: 4.9
Stable tag: 3.700.004
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a credit card payment gateway for Click & Pledge to the Gravity Forms plugin

== Description ==
Gravity Forms Click & Pledge adds a credit card payment gateway for to the [Gravity Forms](http://www.gravityforms.com/) plugin.
* build online donation forms
* build online booking forms
* build simple Buy Now forms
* accept recurring payments

> NB: this plugin extends [Gravity Forms](http://www.gravityforms.com/); you still need to install and activate Gravity Forms!

Thanks for sponsoring new features on Gravity Forms Click & Pledge!

= Requirements: =
* you need to install the [Gravity Forms](http://www.gravityforms.com/) plugin
* you need an SSL certificate for your hosting account
* you need an account with Click & Pledge

== Installation ==

1. Install and activate the [Gravity Forms](http://www.gravityforms.com/) plugin
2. Upload the Gravity Forms CnP plugin to your /wp-content/plugins/ directory.
3. Activate the Gravity Forms CnP plugin through the 'Plugins' menu in WordPress.
4. Edit the CnP payment gateway settings to set your Click & Pledge Account ID, API Account GUID and options

Gravity Forms will now display the Credit Card and Recurring fields under Pricing Fields when you edit a form.

= Building a Form with Credit Card Payments =

* add one or more product fields or a total field, or a recurring field, so that there is something to be charged by credit card
* add an email field and an address field if you want to see them on your Click & Pledge transaction; the first email field and first address field on the form will be sent to Click & Pledge
* add a credit card field; if you have a multi-page form, this must be the on the last page so that all other form validations occur first
* add a confirmation message to the form indicating that payment was successful; the form will not complete if payment was not successful, and will display an error message in the credit card field

== Screenshots ==
1. Settings page
2. Form Editor
3. Form display in front end
4. Credit Card Error display
5. Click & Pledge feed
6. Recurring Field
7. Credit Card Fields
8. eCheck Fields
== Changelog ==
= 3.700.004 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.700.003 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.700.002 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.700.001 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.700.000 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.600.009 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.600.008 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.600.007 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.600.006 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.600.005 =
* https://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/31711-release-notes

= 3.600.004 =
* An error was identified when only one recurring option is made available on the form, e.g. Monthly. The update resolves this issue.

= 3.600.003 =
* Addition of SKU Suffix for one-time and recurring transaction, matching the Connect design.

= 3.600.001 =
* Resolved the issue with fields editing.

= 3.600.000 =
* Resolved the javascript issue.

= 3.500.000 =
* Transaction mode per form may be set to "Test" or "Production". If not set per form, the default value will be used as set in the "Settings"
* Receipt - Connect Campaign URL Alias, per each form - {Connect_Campaign_URL_Alias}

= 3.400.000 =
* Fixed  Send Receipt to Patron issue.

= 3.200.000 =
* New Features: Addition of transaction response parameters to the Thank You page.

= 3.100.000 =
* Bug fixes & UI enhancements Compatibility with GF v 2.0.0

= 3.000.000 =
* Recurring options:  Flexibility in setting recurring in various combinations. 
* Campaign:  Transaction and item campaign may be added
* Compatibile with Gravity Form 1.9.19 and Wordpress 4.5V

= 2.100.016 =
* Fixed Drag and Drop issue with Click & Pledge fields

= 2.100.015 =
* Resolving double posting of items to salesforce opportunity when OTHER field is used.

= 2.100.014 =
* Coupons Add-on Fixed Quantity issue.
* Fixed Recurring issue.
* Fixed Conditional Logic payment methods error handling

= 2.100.013 =
* Coupons Add-on Support added ( Ref:http://forums.clickandpledge.com/node/18271 )

= 2.100.012 =
* Fixed white screen issue ( Ref:http://forums.clickandpledge.com/node/18203 )

= 2.100.011 =
* Fixed white screen issue ( Ref:http://forums.clickandpledge.com/node/18203 )

= 2.100.010 =
* Added new features to add custom payment type
* Allow Zero amount for processing

= 2.100.009 =
* Added new features to handle Conditional Logic Products

= 2.100.008 =
* Added new features to handle Radiobutton, Multi Check box

= 2.100.007 =
* Fixed issue when there is no Gravity Forms Installed

= 2.100.005 =
* Fixed Plugin Broken Issue (Ref:http://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms/17591-2-100-004-broken)

= 2.100.004.000.20150511 =
* Fixed SOAP Client issue (Ref:https://forums.clickandpledge.com/showthread.php?t=2545&p=9533#post9533)
* Simple load XML issue

= 2.100.003.000.20150506 =
* Fixed Ip address issue

= 2.100.002.000.20150505 =
* Fixed SKU issue (don’t allow & sign in the SKU)
* Fixed First Name issue (Ref:https://forums.clickandpledge.com/showthread.php?t=2542)

= 2.100.001.000.20150420 =
* Fixed shipping method issue 

= 2.100.000.000.20150324 = 
* Handled to avoid replace of 'js.php' file. (Ref:https://github.com/ClickandPledge/WordPress-GravityForms/issues/1)
* Fixed Minor issues
* Added custom labels for Recurring field (Ref:https://forums.clickandpledge.com/showthread.php?t=2297)
* Compatible with Gravity Forms 1.9.4.9
* Updated March 24, 2015

= 2.0.8 =
* Fixed Permissions issue.
* Updated Dec 03, 2014

= 2.0.7 = 
* Fixed SKU issue.
* Updated Nov 17, 2014

Version 2.0.6    
* Fixed special characters issue.
* Fixed recurring field issue (Not adding to the form).
* Updated Oct 07, 2014

= 2.0.5 = 
* Fixed warning message when form submit. (Ref:https://forums.clickandpledge.com/showthread.php?t=2070&p=7732)
* Update the 'js.php' for not to add more than one 'recurring' or 'echeck' fields to the same form.
* Updated Sep 25, 2014

= 2.0.4 = 
* Fixed issue while adding a form to C&P gateway.
* Updated July 21, 2014

= 2.0.3 =   
* Fixed Duplicate custom field issue.
* Updated July 10, 2014

= 2.0.2 = 
* Fixed UI issues in recurring field.
* Updated June 25, 2014

= 2.0.1 =
* Fixed UI issues in recurring field.

= 2.0.0 = 
* Added eCheck feature.

= 1.2.1 = 
* Fixed issue for special characters handling in XML.
* Added Form ID while assigning forms to C&P Payment Gateway form  

= 1.2 =
* Fixed issue while saving payment options in admin.  

= 1.1 = 
* Fixed validation issue on using multi payment gateways.    
* Added support for multiple payment gateways to be configured using the same form and executed based on condition.

= 1.0.beta   = 
* Added ability to specify mode (Production or Test) on settings page

== Frequently Asked Questions ==
= Click & Pledge Overview =
* <a href="http://help.clickandpledge.com/customer/portal/articles/2089939" target="_blank"> <strong>Recorded Webinar</strong></a>

= Other helpful resources: =
* <a href="http://forums.clickandpledge.com/forum/platform-product-forums/3rd-party-integrations/gravity-forms" target="_blank">Gravity Forms Click & Pledge Forum</a>
* <a href="http://manual.clickandpledge.com/Gravity-Forms.html" target="_blank">Integration Manual Gravity Forms Click & Pledge</a>