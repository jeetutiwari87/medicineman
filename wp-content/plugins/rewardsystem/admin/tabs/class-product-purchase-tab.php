<?php
/*
 * Support Tab Setting
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSProductPurchaseModule')) {

    class RSProductPurchaseModule {

        public static function init() {

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'), 999);

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_product_purchase_module', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_modules_rewardsystem_product_purchase_module', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system

            add_action('woocommerce_admin_field_selected_products', array(__CLASS__, 'rs_select_products_to_update'));

            add_action('woocommerce_admin_field_rs_enable_disable_product_purchase_module', array(__CLASS__, 'rs_function_to_enable_disable_product_purchase_module'));

            add_action('woocommerce_admin_field_rs_wrapper_start', array(__CLASS__, 'rs_wrapper_section_start'));

            add_action('woocommerce_admin_field_rs_wrapper_end', array(__CLASS__, 'rs_wrapper_section_end'));

            add_action('woocommerce_admin_field_rs_modulecheck_start', array(__CLASS__, 'rs_wrapper_modulecheck_start'));

            add_action('woocommerce_admin_field_rs_modulecheck_end', array(__CLASS__, 'rs_wrapper_modulecheck_close'));

            add_action('woocommerce_admin_field_rs_membership_compatible_start', array(__CLASS__, 'rs_wrapper_membership_compatible_start'));

            add_action('woocommerce_admin_field_rs_membership_compatible_end', array(__CLASS__, 'rs_wrapper_membership_compatible_close'));

            add_action('woocommerce_admin_field_rs_subscription_compatible_start', array(__CLASS__, 'rs_wrapper_subscription_compatible_start'));

            add_action('woocommerce_admin_field_rs_subscription_compatible_end', array(__CLASS__, 'rs_wrapper_subscription_compatible_close'));

            add_action('woocommerce_admin_field_rs_coupon_compatible_start', array(__CLASS__, 'rs_wrapper_coupon_compatible_start'));

            add_action('woocommerce_admin_field_rs_coupon_compatible_end', array(__CLASS__, 'rs_wrapper_coupon_compatible_close'));
            
            add_action('woocommerce_admin_field_rs_hide_bulk_update_for_product_purchase_start', array(__CLASS__, 'rs_hide_bulk_update_for_product_purchase_start'));
            
            add_action('woocommerce_admin_field_rs_hide_bulk_update_for_product_purchase_end', array(__CLASS__, 'rs_hide_bulk_update_for_product_purchase_end'));

            add_action('woocommerce_admin_field_button', array(__CLASS__, 'rs_save_button_for_update'));

            add_action('wp_ajax_nopriv_previousproductvalue', array(__CLASS__, 'get_ajax_request_for_previous_product'));

            add_action('wp_ajax_previousproductvalue', array(__CLASS__, 'get_ajax_request_for_previous_product'));

            add_action('wp_ajax_rssplitajaxoptimization', array(__CLASS__, 'process_chunk_ajax_request_in_rewardsystem'));

            add_action('woocommerce_admin_field_rs_include_products_for_product_purchase', array(__CLASS__, 'rs_include_products_for_product_purchase'));

            add_action('woocommerce_admin_field_rs_exclude_products_for_product_purchase', array(__CLASS__, 'rs_exclude_products_for_product_purchase'));

            add_action('fp_action_to_reset_module_settings_rewardsystem_product_purchase_module', array(__CLASS__, 'rs_function_to_product_purchase_module'));
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_product_purchase_module'] = __('Product Purchase Module', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            //Section and option details
             if ( class_exists( 'WooCommerce_PDF_Invoices' ) ) {
                 $section_title = 'Message Settings in Edit Order Page and Invoices';
                 $option_title =  'Display Points from Order on Order Details Page and Invoices';
             } else {
                 $section_title = 'Message Settings in Edit Order Page';
                 $option_title =  'Display Points from Order on Order Details';
             }
                
            $categorylist = fp_rs_get_product_category();
            return apply_filters('woocommerce_rewardsystem_product_purchase_module', array(
                array(
                    'type' => 'rs_modulecheck_start',
                ),
                array(
                    'name' => __('Product Purchase Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_activate_product_purchase_module',
                    'desc' => __('By Enabling this Module you can award Reward Points for Product Purchase', 'rewardsystem'),
                ),
                array(
                    'type' => 'rs_enable_disable_product_purchase_module',
                ),
                array('type' => 'sectionend', 'id' => '_rs_activate_product_purchase_module'),
                array(
                    'type' => 'rs_modulecheck_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Purchase Reward Points Global Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_product_purchase_module',
                ),
                array(
                    'name' => __('Product Purchase Reward Points', 'rewardsystem'),
                    'id' => 'rs_enable_product_category_level_for_product_purchase',
                    'class' => 'rs_enable_product_category_level_for_product_purchase',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'radio',
                    'newids' => 'rs_enable_product_category_level_for_product_purchase',
                    'options' => array(
                        'no' => __('Quick Setup (Global Level Settings will be enabled)', 'rewardsystem'),
                        'yes' => __('Advanced Setup (Global,Category and Product Level wil be enabled)', 'rewardsystem'),
                    ),
                    'desc_tip'=>true,
                    'desc'=>__('Quick Setup - Global Level will be enabled<br>Advanced Setup - Global,Category and Product Level wil be enabled','rewardsystem')
                ),
                array(
                    'name' => __('Product Purchase Reward Points is applicable for', 'rewardsystem'),
                    'id' => 'rs_product_purchase_global_level_applicable_for',
                    'std' => '1',
                    'class' => 'rs_product_purchase_global_level_applicable_for',
                    'default' => '1',
                    'newids' => 'rs_product_purchase_global_level_applicable_for',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('All Product(s)', 'rewardsystem'),
                        '2' => __('Include Product(s)', 'rewardsystem'),
                        '3' => __('Exclude Product(s)', 'rewardsystem'),
                        '4' => __('All Categories', 'rewardsystem'),
                        '5' => __('Include Categories', 'rewardsystem'),
                        '6' => __('Exclude Categories', 'rewardsystem'),
                    ),
                ),
                array(
                    'type' => 'rs_include_products_for_product_purchase',
                ),
                array(
                    'type' => 'rs_exclude_products_for_product_purchase',
                ),
                array(
                    'name' => __('Include Categories', 'rewardsystem'),
                    'id' => 'rs_include_particular_categories_for_product_purchase',
                    'css' => 'min-width:350px;',
                    'std' => '',
                    'class' => 'rs_include_particular_categories_for_product_purchase',
                    'default' => '',
                    'newids' => 'rs_include_particular_categories_for_product_purchase',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Exclude Categories', 'rewardsystem'),
                    'id' => 'rs_exclude_particular_categories_for_product_purchase',
                    'css' => 'min-width:350px;',
                    'std' => '',
                    'class' => 'rs_exclude_particular_categories_for_product_purchase',
                    'default' => '',
                    'newids' => 'rs_exclude_particular_categories_for_product_purchase',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Global Level Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_enable_disable_sumo_reward',
                    'std' => '2',
                    'default' => '2',
                    'placeholder' => '',
                    'desc_tip' => true,
                    'desc' => __('Global Settings will be considered when Product and Category Settings are Enabled and Values are Empty. '
                            . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order.', 'rewardsystem'),
                    'newids' => 'rs_global_enable_disable_sumo_reward',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Reward Type', 'rewardsystem'),
                    'id' => 'rs_global_reward_type',
                    'class' => 'show_if_enable_in_general',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_global_reward_type',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_reward_points',
                    'class' => 'show_if_enable_in_general',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_reward_points',
                    'placeholder' => '',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Reward Points in Percent %', 'rewardsystem'),
                    'id' => 'rs_global_reward_percent',
                    'class' => 'show_if_enable_in_general',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_reward_percent',
                    'placeholder' => '',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => 'rs_product_purchase_module'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_hide_bulk_update_for_product_purchase_start',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Purchase Reward Points Bulk Update Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_update_setting',
                    'desc' => __('This Settings can be used to Configure Reward Points to Multiple Products/Categories at once', 'rewardsystem')
                ),
                array(
                    'name' => __('Product/Category Selection', 'rewardsystem'),
                    'id' => 'rs_which_product_selection',
                    'std' => '1',
                    'class' => 'rs_which_product_selection',
                    'default' => '1',
                    'newids' => 'rs_which_product_selection',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('All Products', 'rewardsystem'),
                        '2' => __('Selected Products', 'rewardsystem'),
                        '3' => __('All Categories', 'rewardsystem'),
                        '4' => __('Selected Categories', 'rewardsystem'),
                    ),
                    'desc' => __('Select the Products/Categories for which the bulk update has to be processed', 'rewardsystem'),
                    'desc_tip' => true
                ),
                array(
                    'name' => __('Selected Particular Products', 'rewardsystem'),
                    'type' => 'selected_products',
                    'id' => 'rs_select_particular_products',
                    'class' => 'rs_select_particular_products',
                    'newids' => 'rs_select_particular_products',
                ),
                array(
                    'name' => __('Select Particular Categories', 'rewardsystem'),
                    'id' => 'rs_select_particular_categories',
                    'css' => 'min-width:350px;',
                    'std' => '1',
                    'class' => 'rs_select_particular_categories',
                    'default' => '1',
                    'newids' => 'rs_select_particular_categories',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Enable SUMO Reward Points', 'rewardsystem'),
                    'id' => 'rs_local_enable_disable_reward',
                    'std' => '2',
                    'default' => '2',
                    'desc_tip' => true,
                    'desc' => __('Enable will Turn On Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. '
                            . 'Disable will Turn Off Reward Points for Product Purchase and Category/Product Settings will be considered if it is available.', 'rewardsystem'),
                    'newids' => 'rs_local_enable_disable_reward',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_reward_type',
                    'class' => 'show_if_enable_in_reward',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_local_reward_type',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points', 'rewardsystem'),
                    'id' => 'rs_local_reward_points',
                    'class' => 'show_if_enable_in_reward',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_points',
                ),
                array(
                    'name' => __('Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_local_reward_percent',
                    'class' => 'show_if_enable_in_reward',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_percent',
                ),
                array(
                    'type' => 'button',
                ),
                array('type' => 'sectionend', 'id' => 'rs_update_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_hide_bulk_update_for_product_purchase_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Guest Registration Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_checkout_force_login',
                ),
                array(
                    'name' => __('Force Guest to Create Account before placing the order which contain Points associated Product', 'rewardsystem'),
                    'id' => 'rs_enable_acc_creation_for_guest_checkout_page',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_enable_acc_creation_for_guest_checkout_page',
                    'type' => 'checkbox',
                ),
                array('type' => 'sectionend', 'id' => '_rs_checkout_force_login'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Purchase Reward Points Restrictions', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_restriction_in_cart_settings',
                ),
                array(
                    'name' => __('Sale Priced Products', 'rewardsystem'),
                    'desc' => __('Enable this option to prevent earning of points on products that have "sale price"', 'rewardsystem'),
                    'id' => 'rs_pointx_not_award_when_sale_price',
                    'type' => 'checkbox',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_pointx_not_award_when_sale_price',
                ),
                array(
                    'name' => __('Calculate Reward Points after Discounts(WooCommerce Coupons / Points Redeeming)', 'rewardsystem'),
                    'desc' => __('Enabling this option will calculate reward points for the price after excluding the coupon/ points redeeming discounts', 'rewardsystem'),
                    'id' => 'rs_enable_disable_reward_point_based_coupon_amount',
                    'type' => 'checkbox',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_enable_disable_reward_point_based_coupon_amount',
                ),
                array(
                    'name' => __('Restrict Product Purchase Reward Points when Reward Points is Redeemed', 'rewardsystem'),
                    'desc' => __('Enabling this option will restrict product purchase reward points when reward points is redeemed for the order', 'rewardsystem'),
                    'id' => 'rs_enable_redeem_for_order',
                    'type' => 'checkbox',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_enable_redeem_for_order',
                ),
                array(
                    'name' => __('Restrict Product Purchase Reward Points when WooCommerce Coupon is applied', 'rewardsystem'),
                    'desc' => __('Enabling this option will restrict product purchase reward points when woocommerce coupon is applied on order', 'rewardsystem'),
                    'id' => 'rs_disable_point_if_coupon',
                    'type' => 'checkbox',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_disable_point_if_coupon',
                ),
                array(
                    'name' => __('Restrict Product Purchase Reward Points when SUMO Reward Points Payment Gateway is used', 'rewardsystem'),
                    'desc' => __('Enabling this option will restrict product purchase reward points when SUMO Reward  Points Payment gateway is used on order', 'rewardsystem'),
                    'id' => 'rs_disable_point_if_reward_points_gateway',
                    'type' => 'checkbox',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_disable_point_if_reward_points_gateway',
                ),
                array(
                    'name' => __('Message to display when using SUMO Reward Points Payment Gateway to restrict earn points', 'rewardsystem'),
                    'id' => 'rs_restriction_msg_for_reward_gatweway',
                    'type' => 'textarea',
                    'std' => 'You cannot earn points if you use [paymentgatewaytitle] Gateway',
                    'default' => 'You cannot earn points if you use [paymentgatewaytitle] Gateway',
                    'newids' => 'rs_restriction_msg_for_reward_gatweway',
                ),
                array(
                    'name' => __('Minimum Cart Total for Earn Point(s)', 'rewardsystem'),
                    'id' => 'rs_minimum_cart_total_for_earning',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_minimum_cart_total_for_earning',
                    'desc' => __('Minimum Cart total needed in order to earn product purchase Reward Points', 'rewardsystem'),
                    'desc_tip' => true
                ),
                array(
                    'name' => __('Show/Hide Minimum Cart Total Error Message', 'rewardsystem'),
                    'id' => 'rs_show_hide_minimum_cart_total_earn_error_message',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_minimum_cart_total_earn_error_message',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Error Message', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when the user doesn\'t have enough Cart Total for Earning', 'rewardsystem'),
                    'id' => 'rs_min_cart_total_for_earning_error_message',
                    'std' => 'You need Minimum of [carttotal] carttotal to Earn Points',
                    'default' => 'You need Minimum of [carttotal] carttotal to Earn Points',
                    'type' => 'textarea',
                    'newids' => 'rs_min_cart_total_for_earning_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Maximum Cart Total for Earn Point(s)', 'rewardsystem'),
                    'id' => 'rs_maximum_cart_total_for_earning',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_maximum_cart_total_for_earning',
                    'desc' => __('Maximum Cart total needed in order to earn product purchase Reward Points', 'rewardsystem'),
                    'desc_tip' => true
                ),
                array(
                    'name' => __('Show/Hide Maximum Cart Total Error Message', 'rewardsystem'),
                    'id' => 'rs_show_hide_maximum_cart_total_earn_error_message',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_maximum_cart_total_earn_error_message',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Error Message', 'rewardsystem'),
                    'desc' => __('Error Message Displayed when the user\'s cart total is more than the maximum cart total for earning reward Points', 'rewardsystem'),
                    'id' => 'rs_max_cart_total_for_earning_error_message',
                    'std' => 'You Cannot Earn Points Because you Reach the Maximum Cart total [carttotal] for earn Points',
                    'default' => 'You Cannot Earn Points Because you Reach the Maximum Cart total [carttotal] for earn Points',
                    'type' => 'textarea',
                    'newids' => 'rs_max_cart_total_for_earning_error_message',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_restriction_in_cart_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Message Settings in Cart, Checkout and Thank You Page', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_order_setting',
                ),
                array(
                    'name' => __('Show/Hide Points that can be Earned Message display in Cart Totals Table', 'rewardsystem'),
                    'id' => 'rs_show_hide_total_points_cart_field',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_total_points_cart_field',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points Earned in Order Label in Cart Total Table', 'rewardsystem'),
                    'id' => 'rs_total_earned_point_caption',
                    'std' => 'Points that can be earned',
                    'default' => 'Points that can be earned',
                    'type' => 'text',
                    'newids' => 'rs_total_earned_point_caption',
                ),
                array(
                    'name' => __('Show/Hide equivalent points in value on Cart Page', 'rewardsystem'),
                    'id' => 'rs_show_hide_equivalent_price_for_points_cart',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_equivalent_price_for_points_cart',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Show/Hide Points label in Cart Page', 'rewardsystem'),
                    'id' => 'rs_show_hide_custom_msg_for_points_cart',
                    'std' => '2',
                    'default' => '2',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_custom_msg_for_points_cart',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points label in Cart Page', 'rewardsystem'),
                    'id' => 'rs_custom_message_for_points_cart',
                    'std' => 'Points',
                    'default' => 'Points',
                    'type' => 'text',
                    'newids' => 'rs_custom_message_for_points_cart',
                ),
                array(
                    'name' => __('Points that can be Earned Message display in Checkout Total Table', 'rewardsystem'),
                    'id' => 'rs_show_hide_total_points_checkout_field',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_total_points_checkout_field',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points Earned in Order Caption in Checkout', 'rewardsystem'),
                    'id' => 'rs_total_earned_point_caption_checkout',
                    'std' => 'Points that can be earned',
                    'default' => 'Points that can be earned',
                    'type' => 'text',
                    'newids' => 'rs_total_earned_point_caption_checkout',
                ),
                 array(
                    'name' => __('Show/Hide equivalent points in value on Checkout Page', 'rewardsystem'),
                    'id' => 'rs_show_hide_equivalent_price_for_points',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_equivalent_price_for_points',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Show/Hide Points label in Checkout', 'rewardsystem'),
                    'id' => 'rs_show_hide_custom_msg_for_points_checkout',
                    'std' => '2',
                    'default' => '2',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_custom_msg_for_points_checkout',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points label in Checkout Page', 'rewardsystem'),
                    'id' => 'rs_custom_message_for_points_checkout',
                    'std' => 'Points',
                    'default' => 'Points',
                    'type' => 'text',
                    'newids' => 'rs_custom_message_for_points_checkout',
                ),
                array(
                    'name' => __('Show/Hide Points that can be Earned Message display in Thank You Page', 'rewardsystem'),
                    'id' => 'rs_show_hide_total_points_order_field',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_total_points_order_field',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points Earned in Order Thank You Page Label', 'rewardsystem'),
                    'id' => 'rs_total_earned_point_caption_thank_you',
                    'std' => 'Points that can be earned',
                    'default' => 'Points that can be earned',
                    'type' => 'text',
                    'newids' => 'rs_total_earned_point_caption_thank_you',
                ),
                 array(
                    'name' => __('Show/Hide equivalent points in value on Order Thank You Page', 'rewardsystem'),
                    'id' => 'rs_show_hide_equivalent_price_for_points_thankyou',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_equivalent_price_for_points_thankyou',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Show/Hide Points label in Thankyou Page', 'rewardsystem'),
                    'id' => 'rs_show_hide_custom_msg_for_points_thankyou',
                    'std' => '2',
                    'default' => '2',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_custom_msg_for_points_thankyou',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points label in Thankyou Page', 'rewardsystem'),
                    'id' => 'rs_custom_message_for_points_thankyou',
                    'std' => 'Points',
                    'default' => 'Points',
                    'type' => 'text',
                    'newids' => 'rs_custom_message_for_points_thankyou',
                ),
                array('type' => 'sectionend', 'id' => '_rs_order_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __("$section_title", 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_order_setting',
                ),
               
                array(
                    'name' => __("$option_title", 'rewardsystem'),
                    'id' => 'rs_enable_msg_for_earned_points',
                    'newids' => 'rs_enable_msg_for_earned_points',
                    'class' => 'rs_enable_msg_for_earned_points',
                    'type' => 'checkbox',
                ),
                array(
                    'name' => __('Message to display Earned Points', 'rewardsystem'),
                    'id' => 'rs_msg_for_earned_points',
                    'newids' => 'rs_msg_for_earned_points',
                    'class' => 'rs_msg_for_earned_points',
                    'std' => 'Points Earned in this Order [earnedpoints]',
                    'default' => 'Points Earned in this Order [earnedpoints]',
                    'type' => 'textarea',
                ),
                array('type' => 'sectionend', 'id' => '_rs_order_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {
            woocommerce_admin_fields(RSProductPurchaseModule::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSProductPurchaseModule::reward_system_admin_fields());
            if (isset($_POST['rs_product_purchase_module_checkbox'])) {
                update_option('rs_product_purchase_activated', $_POST['rs_product_purchase_module_checkbox']);
            } else {
                update_option('rs_product_purchase_activated', 'no');
            }
            
            if (isset($_POST['rs_include_products_for_product_purchase'])) {
                update_option('rs_include_products_for_product_purchase', $_POST['rs_include_products_for_product_purchase']);
            }else{
                update_option('rs_include_products_for_product_purchase', '');
            }
            if (isset($_POST['rs_exclude_products_for_product_purchase'])) {
                update_option('rs_exclude_products_for_product_purchase', $_POST['rs_exclude_products_for_product_purchase']);
            }else{
                update_option('rs_exclude_products_for_product_purchase', '');
            }
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSProductPurchaseModule::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        public static function rs_function_to_product_purchase_module() {
            $settings = RSProductPurchaseModule::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
            update_option('rs_earn_point', '1');
            update_option('rs_earn_point_value', '1');
            delete_option('rewards_dynamic_rule');
        }

        public static function rs_function_to_enable_disable_product_purchase_module() {
            $get_option_value = get_option('rs_product_purchase_activated');
            $name_of_checkbox = 'rs_product_purchase_module_checkbox';
            RSModulesTab::rs_common_function_to_get_checkbox($get_option_value, $name_of_checkbox);
        }

        /*
         * Function to add wrapper div start and close
         * rs_hide_bulk_update_for_product_purchase_end
         */

        public static function rs_hide_bulk_update_for_product_purchase_start() {
            ?>
            <div class="rs_hide_bulk_update_for_product_purchase_start">
                <?php
            }

            public static function rs_hide_bulk_update_for_product_purchase_end() {
                ?>
            </div>
            <?php
        }
        
        public static function rs_wrapper_section_start() {
            ?>
            <div class="rs_section_wrapper">
                <?php
            }

            public static function rs_wrapper_section_end() {
                ?>
            </div>
            <?php
        }

        public static function rs_wrapper_modulecheck_start() {
            ?>
            <div class="rs_modulecheck_wrapper">
                <?php
            }

            public static function rs_wrapper_modulecheck_close() {
                ?>
            </div>
            <?php
        }

        public static function rs_wrapper_membership_compatible_start() {
            ?>
            <div class="rs_membership_compatible_wrapper">
                <?php
            }

            public static function rs_wrapper_membership_compatible_close() {
                ?>
            </div>
            <?php
            if (!class_exists('SUMOMemberships')) {
                ?>
                <style type="text/css">
                    .rs_membership_compatible_wrapper{
                        display:none;
                    }
                </style>
                <?php
            }
        }

        public static function rs_wrapper_subscription_compatible_start() {
            ?>
            <div class="rs_subscription_compatible_wrapper">
                <?php
            }

            public static function rs_wrapper_subscription_compatible_close() {
                ?>
            </div>
            <?php
            if (!class_exists('SUMOSubscriptions')) {
                ?>
                <style type="text/css">
                    .rs_subscription_compatible_wrapper{
                        display:none;
                    }
                </style>
                <?php
            }
        }

        public static function rs_wrapper_coupon_compatible_start() {
            ?>
            <div class="rs_coupon_compatible_wrapper">
                <?php
            }

            public static function rs_wrapper_coupon_compatible_close() {
                ?>
            </div>
            <?php
            if (!class_exists('SUMORewardcoupons')) {
                ?>
                <style type="text/css">
                    .rs_coupon_compatible_wrapper{
                        display:none;
                    }
                </style>
                <?php
            }
        }

        public static function rs_save_button_for_update() {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">                    
                </th>
                <td class="forminp forminp-select">
                    <input type="submit" class="rs_sumo_reward_button button-primary" value="Save and Update"/>
                    <img class="gif_rs_sumo_reward_button" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/admin/images/update.gif" style="width:32px;height:32px;position:absolute"/>         
                    <div class='rs_sumo_rewards' style='margin-bottom:10px; margin-top:10px; color:green;'></div>
                </td>
            </tr>
            <?php
        }

        public static function get_ajax_request_for_previous_product() {
            global $woocommerce;
            global $post;
            if (isset($_POST['proceedanyway'])) {
                if ($_POST['proceedanyway'] == '1') {
                    if ($_POST['whichproduct'] == '1') {
                        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                        $products = get_posts($args);
                        echo json_encode($products);
                    } elseif ($_POST['whichproduct'] == '2') {
                        if (!is_array($_POST['selectedproducts'])) {
                            $_POST['selectedproducts'] = explode(',', $_POST['selectedproducts']);
                        }
                        if (is_array($_POST['selectedproducts'])) {

                            foreach ($_POST['selectedproducts']as $particularpost) {
                                $checkprod = rs_get_product_object($particularpost);
                                if (is_object($checkprod) && ($checkprod->is_type('simple') || ($checkprod->is_type('subscription')) || $checkprod->is_type('booking'))) {
                                    if ($_POST['enabledisablereward'] == '1') {
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystemcheckboxvalue', 'yes');
                                    } else {
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystemcheckboxvalue', 'no');
                                    }

                                    if ($_POST['enabledisablereferralreward'] == '1') {
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystemreferralcheckboxvalue', 'yes');
                                    } else {
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystemreferralcheckboxvalue', 'no');
                                    }
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_options', $_POST['rewardtype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystempoints', $_POST['rewardpoints']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystempercent', $_POST['rewardpercent']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referral_rewardsystem_options', $_POST['referralrewardtype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referralrewardsystempoints', $_POST['referralrewardpoint']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referralrewardsystempercent', $_POST['referralrewardpercent']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referral_rewardsystem_options_getrefer', $_POST['referralrewardtyperefer']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referralrewardsystempoints_for_getting_referred', $_POST['referralpointforgettingrefer']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referralrewardsystempercent_for_getting_referred', $_POST['referralrewardpercentgettingrefer']);
                                } else {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_enable_reward_points', $_POST['enabledisablereward']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_select_reward_rule', $_POST['rewardtype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_reward_points', $_POST['rewardpoints']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_reward_percent', $_POST['rewardpercent']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_enable_referral_reward_points', $_POST['enabledisablereferralreward']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_select_referral_reward_rule', $_POST['referralrewardtype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referral_reward_points', $_POST['referralrewardpoint']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referral_reward_percent', $_POST['referralrewardpercent']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_select_referral_reward_rule_getrefer', $_POST['referralrewardtyperefer']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referral_reward_points_getting_refer', $_POST['referralpointforgettingrefer']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referral_reward_percent_getting_refer', $_POST['referralrewardpercentgettingrefer']);
                                }
                            }
                        }
                        echo json_encode("success");
                    } elseif ($_POST['whichproduct'] == '3') {
                        $allcategories = get_terms('product_cat');
                        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                        $products = get_posts($args);                        
                        foreach ($products as $product) {
                            $checkproducts = rs_get_product_object($product);
                            if ((float) $woocommerce->version >= (float) '3.0') {
                                $id = $checkproducts->get_id();
                            } else {
                                $id = $checkproducts->id;
                            }
                            if (is_object($checkproducts) && ($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking'))) {
                                $term = get_the_terms($product, 'product_cat');
                                if (is_array($term)) {
                                    foreach ($allcategories as $mycategory) {
                                        if ($_POST['enabledisablereward'] == '1') {
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'yes');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'yes');
                                        } else {
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'no');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'no');
                                        }
                                        if ($_POST['enabledisablereferralreward'] == '1') {
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_referral_reward_system_category', 'yes');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemreferralcheckboxvalue', 'yes');
                                        } else {
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'no');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemreferralcheckboxvalue', 'no');
                                        }
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_options', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempoints', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempercent', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referral_rewardsystem_options', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempoints', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempercent', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempoints_for_getting_referred', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempercent_for_getting_referred', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referral_rewardsystem_options_getrefer', '');


                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_rs_rule', $_POST['rewardtype']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_points', $_POST['rewardpoints']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_percent', $_POST['rewardpercent']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_enable_rs_rule_refer', $_POST['referralrewardtyperefer']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_points_get_refered', $_POST['referralpointforgettingrefer']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_percent_get_refer', $_POST['referralrewardpercentgettingrefer']);



                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_enable_rs_rule', $_POST['referralrewardtype']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_points', $_POST['referralrewardpoint']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_percent', $_POST['referralrewardpercent']);
                                    }
                                }
                            } elseif (is_object($checkproducts) && (rs_check_variable_product_type($checkproducts) || ($checkproducts->is_type('variable-subscription')))) {
                                if (is_array($checkproducts->get_available_variations())) {
                                    foreach ($checkproducts->get_available_variations() as $getvariation) {
                                        $term = get_the_terms($id, 'product_cat');
                                        if (is_array($term)) {
                                            foreach ($allcategories as $mycategory) {
                                                if ($_POST['enabledisablereward'] == '1') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'yes');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                                } else {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'no');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                                }

                                                if ($_POST['enabledisablereferralreward'] == '1') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_referral_reward_system_category', 'yes');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_referral_reward_points', $_POST['enabledisablereferralreward']);
                                                } else {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'no');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_referral_reward_points', $_POST['enabledisablereferralreward']);
                                                }

                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_reward_rule', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_points', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_percent', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_referral_reward_rule', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_points', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_percent', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_points_getting_refer', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_percent_getting_refer', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_referral_reward_rule_getrefer', '');


                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_rs_rule', $_POST['rewardtype']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_points', $_POST['rewardpoints']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_percent', $_POST['rewardpercent']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_enable_rs_rule_refer', $_POST['referralrewardtyperefer']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_points_get_refered', $_POST['referralpointforgettingrefer']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_percent_get_refer', $_POST['referralrewardpercentgettingrefer']);



                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_enable_rs_rule', $_POST['referralrewardtype']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_points', $_POST['referralrewardpoint']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_percent', $_POST['referralrewardpercent']);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        echo json_encode("success");
                    } else {
                        $mycategorylist = $_POST['selectedcategories'];
                        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                        $products = get_posts($args);
                        foreach ($products as $product) {
                            $checkproducts = rs_get_product_object($product);
                            if ((float) $woocommerce->version >= (float) '3.0') {
                                $id = $checkproducts->get_id();
                            } else {
                                $id = $checkproducts->id;
                            }
                            if (is_object($checkproducts) && ($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking'))) {
                                if (is_array($mycategorylist)) {
                                    foreach ($mycategorylist as $eachlist) {
                                        $term = get_the_terms($product, 'product_cat');
                                        if (is_array($term)) {
                                            foreach ($term as $termidlist) {
                                                if ($eachlist == $termidlist->term_id) {
                                                    if ($_POST['enabledisablereward'] == '1') {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_reward_system_category', 'yes');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'yes');
                                                    } else {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_reward_system_category', 'no');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'no');
                                                    }

                                                    if ($_POST['enabledisablereferralreward'] == '1') {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_referral_reward_system_category', 'yes');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemreferralcheckboxvalue', 'yes');
                                                    } else {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_referral_reward_system_category', 'no');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemreferralcheckboxvalue', 'no');
                                                    }

                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_options', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempoints', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempercent', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referral_rewardsystem_options', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempoints', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempercent', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referral_rewardsystem_options_getrefer', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempoints_for_getting_referred', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempercent_for_getting_referred', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_enable_rs_rule_refer', $_POST['referralrewardtyperefer']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_points_get_refered', $_POST['referralpointforgettingrefer']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_percent_get_refer', $_POST['referralrewardpercentgettingrefer']);



                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_rs_rule', $_POST['rewardtype']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_points', $_POST['rewardpoints']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_percent', $_POST['rewardpercent']);


                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_enable_rs_rule', $_POST['referralrewardtype']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_points', $_POST['referralrewardpoint']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_percent', $_POST['referralrewardpercent']);
                                                }
                                            }
                                        }
                                    }
                                }
                            } elseif (is_object($checkproducts) && (rs_check_variable_product_type($checkproducts) || ($checkproducts->is_type('variable-subscription')))) {
                                $mycategorylist = $_POST['selectedcategories'];
                                if (is_array($checkproducts->get_available_variations())) {
                                    foreach ($checkproducts->get_available_variations() as $getvariation) {
                                        if (is_array($mycategorylist)) {
                                            foreach ($mycategorylist as $eachlist) {
                                                $term = get_the_terms($id, 'product_cat');
                                                if (is_array($term)) {
                                                    foreach ($term as $termidlist) {
                                                        if ($eachlist == $termidlist->term_id) {
                                                            if ($_POST['enabledisablereward'] == '1') {
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_reward_system_category', 'yes');
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                                            } else {
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_reward_system_category', 'no');
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                                            }

                                                            if ($_POST['enabledisablereferralreward'] == '1') {
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_referral_reward_system_category', 'yes');
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_referral_reward_points', $_POST['enabledisablereferralreward']);
                                                            } else {
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_referral_reward_system_category', 'no');
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_referral_reward_points', $_POST['enabledisablereferralreward']);
                                                            }

                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_reward_rule', '');
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_points', '');
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_percent', '');
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_referral_reward_rule', '');
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_points', '');
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_percent', '');

                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_points_getting_refer', '');
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_percent_getting_refer', '');
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_referral_reward_rule_getrefer', '');

                                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_rs_rule', $_POST['rewardtype']);
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_points', $_POST['rewardpoints']);
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_percent', $_POST['rewardpercent']);


                                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_enable_rs_rule', $_POST['referralrewardtype']);
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_points', $_POST['referralrewardpoint']);
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_percent', $_POST['referralrewardpercent']);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        echo json_encode("success");
                    }
                }
                if ($_POST['proceedanyway'] == '0') {
                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    foreach ($products as $product) {
                        $checkproducts = rs_get_product_object($product);
                        if (is_object($checkproducts) && ($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking'))) {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'no');
                        } elseif (is_object($checkproducts) && (rs_check_variable_product_type($checkproducts) || ($checkproducts->is_type('variable-subscription')))) {
                            if (is_array($checkproducts->get_available_variations())) {
                                foreach ($checkproducts->get_available_variations() as $getvariation) {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', '2');
                                }
                            }
                        }
                    }
                    echo json_encode("success");
                }
                exit();
            }
        }

        public static function process_chunk_ajax_request_in_rewardsystem() {
            if (isset($_POST['ids'])) {
                $products = $_POST['ids'];
                foreach ($products as $product) {
                    $checkproduct = rs_get_product_object($product);
                    if (is_object($checkproduct) && ($checkproduct->is_type('simple') || ($checkproduct->is_type('subscription')) || $checkproduct->is_type('booking'))) {
                        if ($_POST['enabledisablereward'] == '1') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'yes');
                        } else {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'no');
                        }
                        if ($_POST['enabledisablereferralreward'] == '1') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemreferralcheckboxvalue', 'yes');
                        } else {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemreferralcheckboxvalue', 'no');
                        }
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_options', $_POST['rewardtype']);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempoints', $_POST['rewardpoints']);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempercent', $_POST['rewardpercent']);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referral_rewardsystem_options', $_POST['referralrewardtype']);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempoints', $_POST['referralrewardpoint']);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempercent', $_POST['referralrewardpercent']);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referral_rewardsystem_options_getrefer', $_POST['referralrewardtyperefer']);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempoints_for_getting_referred', $_POST['referralpointforgettingrefer']);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempercent_for_getting_referred', $_POST['referralrewardpercentgettingrefer']);
                    } else {
                        if (is_object($checkproduct) && (rs_check_variable_product_type($checkproduct) || ($checkproduct->is_type('variable-subscription')))) {
                            if (is_array($checkproduct->get_available_variations())) {
                                foreach ($checkproduct->get_available_variations() as $getvariation) {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_reward_rule', $_POST['rewardtype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_points', $_POST['rewardpoints']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_percent', $_POST['rewardpercent']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_referral_reward_points', $_POST['enabledisablereferralreward']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_referral_reward_rule', $_POST['referralrewardtype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_points', $_POST['referralrewardpoint']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_percent', $_POST['referralrewardpercent']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_referral_reward_rule_getrefer', $_POST['referralrewardtyperefer']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_points_getting_refer', $_POST['referralpointforgettingrefer']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_percent_getting_refer', $_POST['referralrewardpercentgettingrefer']);
                                }
                            }
                        }
                    }
                }
            }

            exit();
        }

        public static function rs_select_products_to_update() {
            $field_id = "rs_select_particular_products";
            $field_label = "Select Particular Products";
            $getproducts = get_option('rs_select_particular_products');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        public static function rs_include_products_for_product_purchase() {
            $field_id = "rs_include_products_for_product_purchase";
            $field_label = "Include Product(s)";
            $getproducts = get_option('rs_include_products_for_product_purchase');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        public static function rs_exclude_products_for_product_purchase() {
            $field_id = "rs_exclude_products_for_product_purchase";
            $field_label = "Exclude Product(s)";
            $getproducts = get_option('rs_exclude_products_for_product_purchase');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

    }

    RSProductPurchaseModule::init();
}