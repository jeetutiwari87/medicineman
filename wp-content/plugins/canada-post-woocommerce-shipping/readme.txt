=== WooCommerce Canada Post Shipping ===
Contributors: xadapter, niwf, mujeebur
Tags: Canada Post, Canada Post Shipping, Shipping rates, Label printing, Manifest / Invoice printing, Shipping, WooCommerce
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Ultimate Canada Post WooCommerce Shipping Plugin. Dynamic Shipping Rates, Shipment Creation, Label and Invoice/Manifest Printing

== Description ==

= Introduction =

Canada Post WooCommerce Shipping plugin helps WooCommerce shops to streamline Canada Post shipping integration. This plugin helps you to get shipping rates from Canada Post APIs based on product weight, post code and other relevant details. Based on the postal codes and other parameters, all available shipping services along with the rates are listed for the customers to choose from.

To make the life of shop owners easier, Canada Post WooCommerce Shipping plugin automates the backend activities. With the click of a button on Wordpress dashboard, you can create new shipping and print the shipping label. You can also fetch manifest/invoice and print them. The plugin also enables sending of shipping status notifications to the customer email.

= Integrates WooCommerce to Canada Post =

Once this plugin is installed and configured with necessary information (please visit installation section for more info), your WordPress/WooCommerce Shop will be ready to ship using Canada Post. This plugin will add Canada Post shipping method as one of the shipping methods in WooCommerce.

= Calculate shipping rates dynamically =

While checking out, a customer is presented with the available shipping services and the rates based on his/her postal code, product weight and dimensions. Customer can choose the best method that matches his requirements and proceed to payment.

= Create Shipment and Print Shipping labels from WordPress admin =

Once an order is received, you can create the shipment with Canada Post from the order page on the admin side. Once shipment is created you can transmit the shipment to Canada Post. 

Note: Don't worry if you get an error saying that Fetch Manifest Failed. You can retry it by clicking 'Fetch Manifest'

You can now view the order and use the button   Print Label  to print the label which can be attached to the shipping boxes. Without this feature the owner has to log in to the Canada Post website each time he/she want to print the label.

Shipment will be split into multiple shipment if the order falls above the max weight configured in the settings. In this case, multiple  Print Label  buttons will appear on the order page.

= Fetch and print Manifest/Invoice from WordPress Admin =

Canada Post WooCommerce Shipping plugin adds a  Print Invoice  button in the order page in the WordPress admin. This will print the manifest/invoice which is your hard-copy proof of payment required for all shipments for pickup or drop-off to Canada Post.

Without this feature the owner has to log in to the Canada Post website each time he/she want to print the manifest/invoice.

= Enables automatic notifications =

Customers will get automatic email notifications about the status of their shipment.

<blockquote>

= Premium version Features =

<ul>

<li>Option to set printing paper size as 8.5*11 or 4*6 for label printing. 4*6 can be set for label printing with Zebra thermal printer, Dymo label printer etc..</li>

<li>Option to enter weight and dimension manually for Label printing, Useful when weight and dimensions of products are not correct.</li>

<li>Auto tracking added to customer order complete email and account view.</li>

<li>Enable/disable, edit the names of services and add costs/handling charges to services

Here you can rename, and re-order, Canada Post shipping rates and add price adjustments as a percentage or by dollar amount. These adjustments can be either positive or negative, should you want to apply discounts to shipping.</li>

<li>Pack items individually or using the built in box packer

You can choose from three different packing methods with Canada Post. This will affect the rates as well as label printing.</li>

<li>Per-Item: Each item in your cart (non virtual) will be sent to the Canada Post API. Quotes for all items will be combined for the final cost.</li>

<li>Weight Based: The cart will be split into 30kg packages, and each package sent to the API. No dimensions will be sent, only the weight.</li>

<li>Pack into boxes: Items will be packed into pre-defined boxes and sent to the API. We recommend this option. See box-packing below for more information on this.</li>

<li>Timely compatibility updates and bug fixes.</li>

<li>Premium support!</li>

</ul>


For complete list of features and details, please visit <a rel="nofollow" href="https://www.xadapter.com/product/woocommerce-canada-post-shipping-plugin-with-print-label/">WooCommerce Canada Post Shipping Plugin with Print Label</a>



</blockquote>



= About XAdapter.com =



[XAdapter.com](https://www.xadapter.com/) creates quality WordPress/WooCommerce plug-ins that are easy to use and customize. We are proud to have thousands of customers actively using our plugins across the globe.



== Installation ==



1. Upload the plugin folder to the /wp-content/plugins/ directory.

2. Activate the plugin through the Plugins menu in WordPress.

3. Thats it! you can now configure the plugin.



== Frequently Asked Questions ==



= I can't see facility to define package sizes. Isn't this required? =



In most of the cases it is not required. If your actual order weight is less than Volumetric weight then you will get accurate results. If you are shipping item which is huge in size and less in weight then it could be a problem.

Please [contact us](https://www.xadapter.com/contact/) if this is the  case. 



= Currently during checkout all shipping services from Canada Post are displayed. Can I disable few of them? =



Not in the current version. Please [contact us](https://www.xadapter.com/contact/) in case you need this feature 



== Screenshots ==



1. Plugin Configuration Screen

2. Checkout Screen



== Changelog ==

= 1.0 =

 * Dynamic Shipping Rates

 * Shipment Creation

 * Label Printing 

 * Invoice/Manifest Printing

= 1.1 =

 * Plugin id and settings made its same as the premium version for seamless upgrade.

= 1.1.1 =

 * Provided a settings link in plugin page.

= 1.1.2 =

 * Minor bug fix function doesn't exist.

= 1.2.0 =

 * Stability improvements.

 * Label feature removed from Basic due to maintainablility reasons. 

 * Introduced options to choose services.

= 1.2.1 =

 * Provided Production Mode Option.

= 1.2.2 =

 * UI Changes
 
= 1.2.3 =

 * Wordpress 4.6.0 compatibility

 * Fixed conflict with Premium version

= 1.2.4 =

 * Links updated.

= 1.2.5 =

 * Fixed compatibility issue with WC 3.0

= 1.2.6 =

 * Fixed weight is not getting properly on variable products.

= 1.2.7 =

 * Minor Content Change.

= 1.2.8 =
 
 * Tested up to WP-4.8
= 1.2.9 =

 * Minor Content Change.

= 1.3.0 =

 * Marketing Content Update.
= 1.3.1 =

 * PHP7 Compatibility tested OK.

== Upgrade Notice ==
= 1.3.1 =

 * PHP7 Compatibility tested OK.

= 1.3.0 =

 * Marketing Content Update.

= 1.2.9 =

 * Minor Content Change.

= 1.2.8 =

 * Tested up to WP-4.8

= 1.2.7 =

 * Minor Content Change.

= 1.2.6 =

 * Fixed weight is not getting properly on variable products.
 
= 1.2.5 =

 * Fixed compatibility issue with WC 3.0

= 1.2.4 =

 * Link updated.

= 1.2.3 =

 * Wordpress 4.6.0 compatibility
 
 * Fixed conflict with Premium version

= 1.2.2 =

 * UI Changes

= 1.2.1 =

 * Provided Production Mode Option.

= 1.2.0 =

 * Stability improvements.

 * Label feature removed from Basic due to maintainability reasons. 

 * Introduced options to choose services.