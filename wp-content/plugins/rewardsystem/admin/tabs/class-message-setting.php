<?php
/*
 * Message Tab Setting
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSMessage')) {

    class RSMessage {

        public static function init() {

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_message', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_message', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'));

            add_action('fp_action_to_reset_settings_rewardsystem_message', array(__CLASS__, 'rs_function_to_reset_message_tab'));

            add_action('admin_head', array(__CLASS__, 'add_script_to_dashboard'));

            add_action('woocommerce_admin_field_uploader', array(__CLASS__, 'rs_add_upload_your_gift_voucher'));
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_message'] = __('Messages', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;
            return apply_filters('woocommerce_rewardsystem_message_settings', array(
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shop and Category Page Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_shop_page_msg',
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for Simple Products - Logged in Users', 'rewardsystem'),
                    'desc_tip' => true,
                    'id' => 'rs_show_hide_message_for_simple_in_shop',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_simple_in_shop',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for Simple Products - Guests', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_simple_in_shop_guest',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_simple_in_shop_guest',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for Simple Products', 'rewardsystem'),
                    'id' => 'rs_message_in_shop_page_for_simple',
                    'std' => 'Earn [rewardpoints] Reward Points',
                    'default' => 'Earn [rewardpoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_message_in_shop_page_for_simple',
                ),
                array(
                    'name' => __('Position to display the Earn Points Message for Simple Products', 'rewardsystem'),
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_message_position_for_simple_products_in_shop_page',
                    'options' => array(
                        '1' => __('Before Product Price', 'rewardsystem'),
                        '2' => __('After Product Price', 'rewardsystem'),
                    ),
                ),
                 array(
                    'name' => __('Show/Hide Earn Point(s) Message for Simple Products - Logged in Users (Buying Reward Points)', 'rewardsystem'),
                    'desc_tip' => true,
                    'id' => 'rs_show_hide_buy_points_message_for_simple_in_shop',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_points_message_for_simple_in_shop',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                 array(
                    'name' => __('Show/Hide Earn Point(s) Message for Simple Products - Guests (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_show_hide_buy_pont_message_for_simple_in_shop_guest',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_pont_message_for_simple_in_shop_guest',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for Simple Products (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_buy_point_message_in_shop_page_for_simple',
                    'std' => 'Earn [buypoints] Reward Points',
                    'default' => 'Earn [buypoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_buy_point_message_in_shop_page_for_simple',
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for Variable Products', 'rewardsystem'),
                    'id' => 'rs_enable_display_earn_message_for_variation',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_display_earn_message_for_variation',
                    'desc' => __('Enable this checkbox to display the points to earn for first created variation on shop page', 'rewardsystem'),
                ),
                
                
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for Variable Products - Logged in Users (Buying Reward Points)', 'rewardsystem'),
                    'desc_tip' => true,
                    'id' => 'rs_show_hide_buy_points_message_for_variable_in_shop',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_points_message_for_variable_in_shop',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                 array(
                    'name' => __('Show/Hide Earn Point(s) Message for Variable Products - Guests (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_show_hide_buy_pont_message_for_variable_in_shop_guest',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_pont_message_for_variable_in_shop_guest',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for Variable Products (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_buy_point_message_in_shop_page_for_variable',
                    'std' => 'Earn [buypoints] Reward Points',
                    'default' => 'Earn [buypoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_buy_point_message_in_shop_page_for_variable',
                ),
                
                array(
                    'type' => 'uploader',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => '_rs_shop_page_msg'
                ),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Single Product Page Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_single__product_page_msg',
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message as Notice for Simple Products - Logged in Users', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_single_product',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_single_product',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message as Notice for Simple Products - Guests', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_single_product_guest',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_single_product_guest',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Notice Message for Simple Products', 'rewardsystem'),
                    'id' => 'rs_message_for_single_product_point_rule',
                    'std' => 'Purchase this Product and Earn [rewardpoints] Reward Points ([equalamount])',
                    'default' => 'Purchase this Product and Earn [rewardpoints] Reward Points ([equalamount])',
                    'type' => 'textarea',
                    'newids' => 'rs_message_for_single_product_point_rule',
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for Simple Products', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_shop_archive_single',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_shop_archive_single',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for Simple Products', 'rewardsystem'),
                    'id' => 'rs_message_in_single_product_page',
                    'std' => 'Earn [rewardpoints] Reward Points',
                    'default' => 'Earn [rewardpoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_message_in_single_product_page',
                ),
                
              
                 array(
                    'name' => __('Show/Hide Earn Point(s) Message for Simple Products - Logged in Users (Buying Reward Points)', 'rewardsystem'),
                    'desc_tip' => true,
                    'id' => 'rs_show_hide_buy_points_message_for_simple_in_product',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_points_message_for_simple_in_product',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                 array(
                    'name' => __('Show/Hide Earn Point(s) Message for Simple Products - Guests (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_show_hide_buy_pont_message_for_simple_in_product_guest',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_pont_message_for_simple_in_product_guest',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for Simple Products', 'rewardsystem'),
                    'id' => 'rs_buy_point_message_in_product_page_for_simple',
                    'std' => 'Earn [rewardpoints] Reward Points',
                    'default' => 'Earn [rewardpoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_buy_point_message_in_product_page_for_simple',
                ),
                
                
                array(
                    'name' => __('Show/Hide Earn Point(s) Message in Variation Level for Variable Products', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_variable_in_single_product_page',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_variable_in_single_product_page',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for Variations of Variable Product', 'rewardsystem'),
                    'id' => 'rs_message_for_single_product_variation',
                    'std' => 'Earn [variationrewardpoints] Reward Points',
                    'default' => 'Earn [variationrewardpoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_message_for_single_product_variation',
                ),
                array(
                    'name' => __('Position to display the Earn Points Message for Simple Products', 'rewardsystem'),
                    'id' => 'rs_message_position_in_single_product_page_for_simple_products',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_message_position_in_single_product_page_for_simple_products',
                    'options' => array(
                        '1' => __('Before Product Price', 'rewardsystem'),
                        '2' => __('After Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Position to display the Earn Points Message for Variable Products', 'rewardsystem'),
                    'id' => 'rs_message_position_in_single_product_page_for_variable_products',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_message_position_in_single_product_page_for_variable_products',
                    'options' => array(
                        '1' => __('Before Product Price', 'rewardsystem'),
                        '2' => __('After Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message as Notice for Variable Products - Logged in Users', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_variable_product',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_variable_product',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Notice Message for Variable Products', 'rewardsystem'),
                    'id' => 'rs_message_for_variation_products',
                    'std' => 'Purchase this Product and Earn [variationrewardpoints] Reward Points ([variationpointsvalue])',
                    'default' => 'Purchase this Product and Earn [variationrewardpoints] Reward Points ([variationpointsvalue])',
                    'type' => 'textarea',
                    'newids' => 'rs_message_for_variation_products',
                ),
                
                
                
                
                array(
                    'name' => __('Show/Hide Earn Point(s) Message as Notice for Variable Products - Logged in Users (Buying Reward Points)', 'rewardsystem'),
                    'desc_tip' => true,
                    'id' => 'rs_show_hide_buy_points_message_for_variable_in_product',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_points_message_for_variable_in_product',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                 array(
                    'name' => __('Show/Hide Earn Point(s) Message as Notice for Variable Products - Guests (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_show_hide_buy_pont_message_for_variable_in_product_guest',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_pont_message_for_variable_in_product_guest',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for Variations of Variable Product (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_buy_point_message_in_product_page_for_variable',
                    'std' => 'Earn [buypoints] Reward Points',
                    'default' => 'Earn [buypoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_buy_point_message_in_product_page_for_variable',
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for Variable Products', 'rewardsystem'),
                    'id' => 'rs_enable_display_earn_message_for_variation_single_product',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_display_earn_message_for_variation_single_product',
                ),
                array('type' => 'sectionend', 'id' => '_rs_single__product_page_msg'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Cart Page Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_cart_page_msg',
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for Guests', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_guest',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_guest',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for Guests', 'rewardsystem'),
                    'id' => 'rs_message_for_guest_in_cart',
                    'std' => 'Earn Reward Points for Product Purchase, Product Review and Sign up, etc [loginlink]',
                    'default' => 'Earn Reward Points for Product Purchase, Product Review and Sign up, etc [loginlink]',
                    'type' => 'textarea',
                    'newids' => 'rs_message_for_guest_in_cart',
                ),
                array(
                    'name' => __('Position to display the points messages', 'rewardsystem'),
                    'id' => 'rs_message_before_after_cart_table',                    
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_message_before_after_cart_table',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Before', 'rewardsystem'),
                        '2' => __('After', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for each Product', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_each_products',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_each_products',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for each Product', 'rewardsystem'),
                    'id' => 'rs_message_product_in_cart',
                    'std' => 'Purchase [titleofproduct] and Earn <strong>[rspoint]</strong> Reward Points ([carteachvalue])',
                    'default' => 'Purchase [titleofproduct] and Earn <strong>[rspoint]</strong> Reward Points ([carteachvalue])',
                    'type' => 'textarea',
                    'newids' => 'rs_message_product_in_cart',
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for each Product (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_show_hide_buy_point_message_for_each_products',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_point_message_for_each_products',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for each Product (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_buy_point_message_product_in_cart',
                    'std' => 'Purchase [titleofproduct] and Earn <strong>[buypoint]</strong> Reward Points',
                    'default' => 'Purchase [titleofproduct] and Earn <strong>[buypoint]</strong> Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_buy_point_message_product_in_cart',
                ),
                array(
                    'name' => __('Show/Hide Total Points that can be Earned', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_total_points',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_total_points',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Message for Total Points that can be Earned', 'rewardsystem'),
                    'id' => 'rs_message_total_price_in_cart',
                    'std' => 'Complete the Purchase and Earn <strong>[totalrewards]</strong> Reward Points ([totalrewardsvalue])',
                    'default' => 'Complete the Purchase and Earn <strong>[totalrewards]</strong> Reward Points ([totalrewardsvalue])',
                    'type' => 'textarea',
                    'newids' => 'rs_message_total_price_in_cart',
                ),
                array(
                    'name' => __('Show/Hide Available Reward Points', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_my_rewards',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_my_rewards',
                    'type' => 'select',
                    'desc' => __('This option is used to show/hide the current available points on cart page', 'rewardsystem'),
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Available Reward Points Message', 'rewardsystem'),
                    'id' => 'rs_message_user_points_in_cart',
                    'std' => 'My Reward Points [userpoints] ([userpoints_value])',
                    'default' => 'My Reward Points [userpoints] ([userpoints_value])',
                    'type' => 'textarea',
                    'newids' => 'rs_message_user_points_in_cart',
                ),
                array(
                    'name' => __('Show/Hide Redeemed Points Message', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_redeem_points',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_redeem_points',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Redeemed Points Message', 'rewardsystem'),
                    'id' => 'rs_message_user_points_redeemed_in_cart',
                    'std' => '[redeempoints] Reward Points Redeemed. Balance [redeemeduserpoints] Reward Points',
                    'default' => '[redeempoints] Reward Points Redeemed. Balance [redeemeduserpoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_message_user_points_redeemed_in_cart',
                ),
                array('type' => 'sectionend', 'id' => '_rs_cart_page_msg'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Checkout Page Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_checkout_page_msg',
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for Guests', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_guest_checkout_page',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_guest_checkout_page',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for Guests', 'rewardsystem'),
                    'id' => 'rs_message_for_guest_in_checkout',
                    'std' => 'Earn Reward Points for Product Purchase, Product Review and Sign up, etc [loginlink]',
                    'default' => 'Earn Reward Points for Product Purchase, Product Review and Sign up, etc [loginlink]',
                    'type' => 'textarea',
                    'newids' => 'rs_message_for_guest_in_checkout',
                ),
                array(
                    'name' => __('Show/Hide Earn Point(s) Message for each Product', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_each_products_checkout_page',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_each_products_checkout_page',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for each Product', 'rewardsystem'),
                    'id' => 'rs_message_product_in_checkout',
                    'std' => 'Purchase [titleofproduct] and Earn <strong>[rspoint]</strong> Reward Points ([carteachvalue])',
                    'default' => 'Purchase [titleofproduct] and Earn <strong>[rspoint]</strong> Reward Points ([carteachvalue])',
                    'type' => 'textarea',
                    'newids' => 'rs_message_product_in_checkout',
                ),
                 array(
                    'name' => __('Show/Hide Earn Point(s) Message for each Product (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_show_hide_buy_point_message_for_each_products_checkout_page',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_buy_point_message_for_each_products_checkout_page',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Earn Point(s) Message for each Product (Buying Reward Points)', 'rewardsystem'),
                    'id' => 'rs_buy_point_message_product_in_checkout',
                    'std' => 'Purchase [titleofproduct] and Earn <strong>[buypoints]</strong> Reward Points',
                    'default' => 'Purchase [titleofproduct] and Earn <strong>[buypoints]</strong> Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_buy_point_message_product_in_checkout',
                ),
                array(
                    'name' => __('Show/Hide Total Points that can be Earned', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_total_points_checkout_page',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_total_points_checkout_page',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Message for Total Points that can be Earned', 'rewardsystem'),
                    'id' => 'rs_message_total_price_in_checkout',
                    'std' => 'Complete the Purchase and Earn <strong>[totalrewards]</strong> Reward Points ([totalrewardsvalue])',
                    'default' => 'Complete the Purchase and Earn <strong>[totalrewards]</strong> Reward Points ([totalrewardsvalue])',
                    'type' => 'textarea',
                    'newids' => 'rs_message_total_price_in_checkout',
                ),
                array(
                    'name' => __('Show/Hide Available Reward Points', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_my_rewards_checkout_page',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_my_rewards_checkout_page',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Available Reward Points Message', 'rewardsystem'),
                    'id' => 'rs_message_user_points_in_checkout',
                    'std' => 'My Reward Points [userpoints] ([userpoints_value])',
                    'default' => 'My Reward Points [userpoints] ([userpoints_value])',
                    'type' => 'textarea',
                    'newids' => 'rs_message_user_points_in_checkout',
                ),
                array(
                    'name' => __('Show/Hide Redeemed Points Message', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_redeem_points_checkout_page',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_redeem_points_checkout_page',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Redeemed Points Message', 'rewardsystem'),
                    'id' => 'rs_message_user_points_redeemed_in_checkout',
                    'std' => '[redeempoints] Reward Points Redeemed. Balance [redeemeduserpoints] Reward Points',
                    'default' => '[redeempoints] Reward Points Redeemed. Balance [redeemeduserpoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_message_user_points_redeemed_in_checkout',
                ),
                array(
                    'name' => __('Show/Hide Payment Gateway Reward Points Message', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_payment_gateway_reward_points',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_payment_gateway_reward_points',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Payment Gateway Reward Points Message', 'rewardsystem'),
                    'id' => 'rs_message_payment_gateway_reward_points',
                    'std' => 'Use this [paymentgatewaytitle] and Earn [paymentgatewaypoints] Reward Points',
                    'default' => 'Use this [paymentgatewaytitle] and Earn [paymentgatewaypoints] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_message_payment_gateway_reward_points',
                ),
                array('type' => 'sectionend', 'id' => '_rs_checkout_page_msg'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Cart and Checkout Page Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_cart_checkout_page_msg',
                ),
                array(
                    'name' => __('Show/Hide Reward Points Redeeming Success Message', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_for_redeem',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_for_redeem',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Reward Points Redeeming Success Message', 'rewardsystem'),
                    'id' => 'rs_success_coupon_message',
                    'std' => 'Reward Points Successfully Added',
                    'default' => 'Reward Points Successfully Added',
                    'type' => 'text',
                    'newids' => 'rs_success_coupon_message',
                ),
                array(
                    'name' => __('Redeemed Points Removal Message', 'rewardsystem'),
                    'id' => 'rs_remove_redeem_points_message',
                    'std' => 'Reward Points has been removed.',
                    'default' => 'Reward Points has been removed.',
                    'type' => 'text',
                    'newids' => 'rs_remove_redeem_points_message',
                ),
                array(
                    'name' => __('Error Message for Maximum Redeeming Threshold Value', 'rewardsystem'),
                    'desc' => __('Message which will be displayed when the user redeem points more than the Threshold Limit', 'rewardsystem'),
                    'id' => 'rs_errmsg_for_max_discount_type',
                    'std' => 'Maximum Discount has been Limited to [percentage] %',
                    'default' => 'Maximum Discount has been Limited to [percentage] %',
                    'type' => 'textarea',
                    'newids' => 'rs_errmsg_for_max_discount_type',
                    'class' => 'rs_errmsg_for_max_discount_type',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Product Purchase Reward Points Earning Prevented Error Message due to Redeeming', 'rewardsystem'),
                    'id' => 'rs_errmsg_for_redeeming_in_order',
                    'std' => 'Since,You Redeemed Your Reward Points in this Order, You Cannot Earn Reward Points For this Order',
                    'default' => 'Since,You Redeemed Your Reward Points in this Order, You Cannot Earn Reward Points For this Order',
                    'type' => 'textarea',
                    'newids' => 'rs_errmsg_for_redeeming_in_order',
                    'class' => 'rs_errmsg_for_redeeming_in_order',
                ),
                array(
                    'name' => __('Product Purchase Reward Points Earning Prevented Error Message due to Coupon usage', 'rewardsystem'),
                    'id' => 'rs_errmsg_for_coupon_in_order',
                    'std' => 'Since You have used Coupon in this Order, You Cannot Earn Reward Points For this Order',
                    'default' => 'Since You have used Coupon in this Order, You Cannot Earn Reward Points For this Order',
                    'type' => 'textarea',
                    'newids' => 'rs_errmsg_for_coupon_in_order',
                    'class' => 'rs_errmsg_for_coupon_in_order',
                ),
                array(
                    'name' => __('Show/Hide Points/Coupon Redeeming Restriction Message for Point Priced Products', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_errmsg_for_point_price_coupon',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_errmsg_for_point_price_coupon',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points/Coupon Redeeming Restriction Message for Point Priced Products', 'rewardsystem'),
                    'id' => 'rs_errmsg_for_redeem_in_point_price_prt',
                    'std' => 'Points not Redeem for Point Price Product',
                    'default' => 'Points not Redeem for Point Price Product',
                    'type' => 'textarea',
                    'newids' => 'rs_errmsg_for_redeem_in_point_price_prt',
                    'class' => 'rs_errmsg_for_redeem_in_point_price_prt',
                ),
                array(
                    'name' => __('Show/Hide Points Calculation Caution Message', 'rewardsystem'),
                    'id' => 'rs_show_hide_message_notice_for_redeeming',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_message_notice_for_redeeming',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points Calculation Caution Message', 'rewardsystem'),
                    'id' => 'rs_msg_for_redeem_when_tax_enabled',
                    'std' => 'Actual Points which can be Redeemed may differ based on Tax Configuration',
                    'default' => 'Actual Points which can be Redeemed may differ based on Tax Configuration',
                    'type' => 'textarea',
                    'newids' => 'rs_msg_for_redeem_when_tax_enabled',
                    'class' => 'rs_msg_for_redeem_when_tax_enabled',
                ),
                array('type' => 'sectionend', 'id' => '_rs_cart_checkout_page_msg'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('My Reward Table Customization Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_my_reward_label_settings'
                ),
                array(
                    'name' => __('My Rewards Table in My Account', 'rewardsystem'),
                    'id' => 'rs_my_reward_table',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_my_reward_table',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('My Rewards Table in Shortcode', 'rewardsystem'),
                    'id' => 'rs_my_reward_table_shortcode',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_my_reward_table_shortcode',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points Log should be displaed in', 'rewardsystem'),
                    'id' => 'rs_points_log_sorting',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_points_log_sorting',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Ascending Order', 'rewardsystem'),
                        '2' => __('Descending Order', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Search Box in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_show_hide_search_box_in_my_rewards_table',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_search_box_in_my_rewards_table',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('S.No Column', 'rewardsystem'),
                    'id' => 'rs_my_reward_points_s_no',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_my_reward_points_s_no',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points Expiry Column', 'rewardsystem'),
                    'id' => 'rs_my_reward_points_expire',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_my_reward_points_expire',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Username Column', 'rewardsystem'),
                    'id' => 'rs_my_reward_points_user_name_hide',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_my_reward_points_user_name_hide',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Page Size in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_show_hide_page_size_my_rewards',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_page_size_my_rewards',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Reward Table Position', 'rewardsystem'),
                    'id' => 'rs_reward_table_position',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_reward_table_position',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('After My Account', 'rewardsystem'),
                        '2' => __('Before My Account', 'rewardsystem'),
                    ),
                    'desc' => __('This option controls the Reward Table Display Position in My Account Page', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Display Reward Points Label Position', 'rewardsystem'),
                    'id' => 'rs_reward_point_label_position',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_reward_point_label_position',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Before Points', 'rewardsystem'),
                        '2' => __('After Points', 'rewardsystem'),
                    ),
                    'desc' => __('This option controls the Reward Points Label Display Position', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Total Points Label', 'rewardsystem'),
                    'desc' => __('Label used for displaying the Current Points in My Account Page', 'rewardsystem'),
                    'id' => 'rs_my_rewards_total',
                    'std' => 'Total Points: ',
                    'default' => 'Total Points:',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_total',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Display Currency Value of Total Points', 'rewardsystem'),
                    'id' => 'rs_reward_currency_value',
                    'std' => '2',
                    'default' => '2',
                    'newids' => 'rs_reward_currency_value',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                    'desc' => __('This option controls whether the Currency Value of the Earned Points has to be displayed next to Earned Points', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('My Rewards Label', 'rewardsystem'),
                    'desc' => __('My Rewards Label Secion', 'rewardsystem'),
                    'id' => 'rs_my_rewards_title',
                    'std' => 'My Rewards',
                    'default' => 'My Rewards',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_title',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('S.No Label', 'rewardsystem'),
                    'desc' => __('Label used for displaying the S.No Column Name in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_my_rewards_sno_label',
                    'std' => 'S.No',
                    'default' => 'S.No',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_sno_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Username Label', 'rewardsystem'),
                    'desc' => __('Label used for displaying the Username Column Name in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_my_rewards_userid_label',
                    'std' => 'Username',
                    'default' => 'Username',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_userid_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Reward for Label', 'rewardsystem'),
                    'desc' => __('Label used for displaying the Reward for Column Name in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_my_rewards_rewarder_label',
                    'std' => 'Reward for',
                    'default' => 'Reward for',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_rewarder_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Earned Points Label', 'rewardsystem'),
                    'desc' => __('Label used for displaying the Earned Points Column Name in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_my_rewards_points_earned_label',
                    'std' => 'Earned Points',
                    'default' => 'Earned Points',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_points_earned_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Redeemed Points Label', 'rewardsystem'),
                    'desc' => __('Label used for displaying the Redeemed Points Column Name in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_my_rewards_redeem_points_label',
                    'std' => 'Redeemed Points',
                    'default' => 'Redeemed Points',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_redeem_points_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Total Points Label', 'rewardsystem'),
                    'desc' => __('Label used for displaying the Total Points Column Name in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_my_rewards_total_points_label',
                    'std' => 'Total Points',
                    'default' => 'Total Points',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_total_points_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Earned Date Label', 'rewardsystem'),
                    'desc' => __('Label used for displaying the Earned Date Column Name in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_my_rewards_date_label',
                    'std' => 'Earned Date',
                    'default' => 'Earned Date',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_date_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Points Expires On', 'rewardsystem'),
                    'desc' => __('Label used for displaying the Points Expires On Column Name in My Rewards Table', 'rewardsystem'),
                    'id' => 'rs_my_rewards_points_expired_label',
                    'std' => 'Points Expires On',
                    'default' => 'Points Expires On',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_points_expired_label',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_my_reward_label_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Guest Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_message_guest',
                ),
                array(
                    'name' => __('Message Displayed for Guests', 'rewardsystem'),
                    'id' => 'rs_message_shortcode_guest_display',
                    'std' => 'Please Login to View the Contents of this Page',
                    'default' => 'Please Login to View the Contents of this Page',
                    'type' => 'text',
                    'newids' => 'rs_message_shortcode_guest_display',
                    'class' => 'rs_message_shortcode_guest_display',
                ),
                array(
                    'name' => __('Login Name Label', 'rewardsystem'),
                    'id' => 'rs_message_shortcode_login_name',
                    'std' => 'Login',
                    'default' => 'Login',
                    'type' => 'text',
                    'newids' => 'rs_message_shortcode_login_name',
                    'class' => 'rs_message_shortcode_login_name',
                    'desc' => __('This label will be used as Hyperlink text', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('[my_userpoints_value] Shortcode Label', 'rewardsystem'),
                    'id' => 'rs_label_shortcode',
                    'std' => 'My Points',
                    'default' => 'My Points',
                    'type' => 'text',
                    'newids' => 'rs_label_shortcode',
                    'class' => 'rs_label_shortcode',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_message_guest'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Unsubscription Link Text Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_unsub_link',
                ),
                array(
                    'name' => __('Unsubscribe Link Message', 'rewardsystem'),
                    'desc' => __('This message will be displayed in emails sent through SUMO Reward Points', 'rewardsystem'),
                    'id' => 'rs_unsubscribe_link_for_email',
                    'std' => 'If you want to unsubscribe from SUMO Reward Points Emails,click here...{rssitelinkwithid}',
                    'default' => 'If you want to unsubscribe from SUMO Reward Points Emails,click here...{rssitelinkwithid}',
                    'type' => 'textarea',
                    'newids' => 'rs_unsubscribe_link_for_email',
                    'class' => 'rs_unsubscribe_link_for_email',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_unsub_link'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Cart Error Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_cart_error_msg',
                ),
                array(
                    'name' => __('Error Message displayed when Normal Product is added to cart - Point Price Product is already in cart', 'rewardsystem'),
                    'id' => 'rs_errmsg_for_normal_product_with_point_price',
                    'std' => 'Cannot add normal product with point pricing product',
                    'default' => 'Cannot add normal product with point pricing product',
                    'type' => 'textarea',
                    'newids' => 'rs_errmsg_for_normal_product_with_point_price',
                    'class' => 'rs_errmsg_for_normal_product_with_point_price',
                ),
                array(
                    'name' => __('Error Message displayed when Point Price Product is added to cart - Normal Product is already in cart', 'rewardsystem'),
                    'id' => 'rs_errmsg_for_point_price_product_with_normal',
                    'std' => 'Cannot Purchase Point Pricing Product with Normal product',
                    'default' => 'Cannot Purchase Point Pricing Product with Normal product',
                    'type' => 'textarea',
                    'newids' => 'rs_errmsg_for_point_price_product_with_normal',
                    'class' => 'rs_errmsg_for_point_price_product_with_normal',
                ),
                array(
                    'name' => __('Error Message displayed when Point Priced Product added twice to cart', 'rewardsystem'),
                    'id' => 'rs_errmsg_for_point_price_product_with_same',
                    'std' => 'You cannot add same product to cart',
                    'default' => 'You cannot add same product to cart',
                    'type' => 'textarea',
                    'newids' => 'rs_errmsg_for_point_price_product_with_same',
                    'class' => 'rs_errmsg_for_point_price_product_with_same',
                ),
                array('type' => 'sectionend', 'id' => '_rs_cart_error_msg'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shortcodes used in Messages', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_shortcode_in_messages',
                ),
                array(
                    'type' => 'title',
                    'desc' => '<b>Single Product Page - Simple Product</b><br><br>
                        <b>[rewardpoints]</b> - To display the current points earned<br><br>
                        <b>[equalamount]</b> - To display currency value equivalent of earn points<br><br>
                        <b>Single Product Page - Variable Product</b><br><br>
                        <b>[variationrewardpoints]</b> - To display points that can be earned<br><br>
                        <b>[variationpointsvalue]</b> - To display currency value equivalent of points that can be earned<br><br>
                        <b>Cart/Checkout Page</b><br><br>
                        <b>[loginlink]</b> - To display login link for guests<br><br>
                        <b>[rspoint]</b> - To display earning points for each product<br><br>
                        <b>[carteachvalue]</b> - To display currency value equivalent of earning points for each product<br><br>
                        <b>[totalrewards]</b> - To display total earning points<br><br>
                        <b>[totalrewardsvalue]</b> - To display currency value equivalent of total earning points<br><br>
                        <b>[userpoints]</b> - To display total available points<br><br>
                        <b>[userpoints_value]</b> - To display currency value equivalent of total available points<br><br>
                        <b>[my_userpoints_value]</b> - To display currency value equivalent of total available points with label<br><br>
                        <b>[redeempoints]</b> - To display points redeemed<br><br>
                        <b>[redeemeduserpoints]</b> - To display available points after redeeming<br><br>
                        <b>{rssitelinkwithid}</b> - To display unsubscribe link from emails<br><br>
                        <b>[paymentgatewaytitle]</b> - To display payment gateway title in Checkout<br><br>
                        <b>[paymentgatewaypoints]</b> - To display sumo reward points payment gateway points in Checkout<br><br>
                        <b>[percentage]</b> - To display maximum threshold value to redeem
                        <b>[rs_referred_user_name]</b> - To display referrer name',
                ),
                array('type' => 'sectionend', 'id' => 'rs_shortcode_in_messages'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {

            woocommerce_admin_fields(RSMessage::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSMessage::reward_system_admin_fields());
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSMessage::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        public static function rs_function_to_reset_message_tab() {
            $settings = RSMessage::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
        }

        public static function add_script_to_dashboard() {
            ?>
            <script type='text/javascript'>
                jQuery(document).ready(function () {
                    jQuery('#changepagesize').change(function (e) {
                        e.preventDefault();
                        var pageSize = jQuery(this).val();
                        jQuery('.footable').data('page-size', pageSize);
                        jQuery('.footable').trigger('footable_initialized');
                    });

                    jQuery('#changepagesizes').change(function (e) {
                        e.preventDefault();
                        var pageSize = jQuery(this).val();
                        jQuery('.footable').data('page-size', pageSize);
                        jQuery('.footable').trigger('footable_initialized');
                    });

                    jQuery('#changepagesizer').change(function (e) {
                        e.preventDefault();
                        var pageSize = jQuery(this).val();
                        jQuery('.footable').data('page-size', pageSize);
                        jQuery('.footable').trigger('footable_initialized');
                    });
                    jQuery('#changepagesizertemplates').change(function (e) {
                        e.preventDefault();
                        var pageSize = jQuery(this).val();
                        jQuery('.footable').data('page-size', pageSize);
                        jQuery('.footable').trigger('footable_initialized');
                    });
                });</script>
            <?php
        }

        /*
         * Function For Upload Your own Gift
         */

        public static function rs_add_upload_your_gift_voucher() {
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th class="titledesc" scope="row">
                        <label for="rs_image_url_upload"><?php _e('Upload your own Gift Icon', 'rewardsystem'); ?></label>
                    </th>
                    <td class="forminp forminp-select">
                        <input type="text" id="rs_image_url_upload" name="rs_image_url_upload" value="<?php echo get_option('rs_image_url_upload'); ?>"/>
                        <input type="submit" id="rs_image_upload_button" class="rs_refresh_button" name="rs_image_upload_button" value="Upload Image"/>
                    </td>
                </tr>
            </table>
            <?php
            rs_ajax_for_upload_your_gift_voucher('#rs_image_url_upload');
        }
    }

    RSMessage::init();
}