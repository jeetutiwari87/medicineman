<?php
/*
 * Social Rewards Tab Setting
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSSocialReward')) {

    class RSSocialReward {

        public static function init() {

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_socialrewards', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_modules_rewardsystem_socialrewards', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'));

            add_action('woocommerce_admin_field_selected_social_products', array(__CLASS__, 'rs_select_products_to_update_social'));

            add_action('woocommerce_admin_field_button_social', array(__CLASS__, 'rs_save_button_for_update_social'));

            add_action('wp_ajax_nopriv_previoussocialproductvalue', array(__CLASS__, 'get_ajax_request_for_previous_social_product'));

            add_action('wp_ajax_previoussocialproductvalue', array(__CLASS__, 'get_ajax_request_for_previous_social_product'));

            add_action('admin_head', array(__CLASS__, 'rs_chosen_for_category'));

            add_action('woocommerce_admin_field_rs_enable_disable_social_reward_module', array(__CLASS__, 'rs_function_to_enable_disable_social_reward_module'));

            add_action('wp_ajax_rssplitajaxoptimizationsocial', array(__CLASS__, 'process_chunk_ajax_request_in_social_rewardsystem'));

            add_action('woocommerce_admin_field_rs_hide_bulk_update_for_social_reward_start', array(__CLASS__, 'rs_hide_bulk_update_for_social_reward_start'));

            add_action('woocommerce_admin_field_rs_hide_bulk_update_for_social_reward_end', array(__CLASS__, 'rs_hide_bulk_update_for_social_reward_end'));

            add_action('woocommerce_admin_field_rs_include_products_for_social_reward', array(__CLASS__, 'rs_include_products_for_social_reward'));

            add_action('woocommerce_admin_field_rs_exclude_products_for_social_reward', array(__CLASS__, 'rs_exclude_products_for_social_reward'));

            add_action('fp_action_to_reset_module_settings_rewardsystem_product_purchase_module', array(__CLASS__, 'rs_function_to_reset_social_reward_tab'));
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_socialrewards'] = __('Social Reward Points Module', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;
            $categorylist = fp_rs_get_product_category();
            return apply_filters('woocommerce_rewardsystem_social_reward_settings', array(
                array(
                    'type' => 'rs_modulecheck_start',
                ),
                array(
                    'name' => __('Social Reward Points Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_activate_social_reward_module'
                ),
                array(
                    'type' => 'rs_enable_disable_social_reward_module',
                ),
                array('type' => 'sectionend', 'id' => '_rs_activate_social_reward_module'),
                array(
                    'type' => 'rs_modulecheck_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Social Reward Points Global Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_global_social_reward_points'
                ),
                array(
                    'name' => __('Social Reward Points', 'rewardsystem'),
                    'id' => 'rs_enable_product_category_level_for_social_reward',
                    'class' => 'rs_enable_product_category_level_for_social_reward',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'radio',
                    'newids' => 'rs_enable_product_category_level_for_social_reward',
                    'options' => array(
                        'no' => __('Quick Setup (Global Level Settings will be enabled)', 'rewardsystem'),
                        'yes' => __('Advanced Setup (Global,Category and Product Level wil be enabled)', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                    'desc' => __('Quick Setup - Global Level will be enabled<br>Advanced Setup - Global,Category and Product Level wil be enabled', 'rewardsystem')
                ),
                array(
                    'name' => __('Social Reward Points is applicable for', 'rewardsystem'),
                    'id' => 'rs_social_reward_global_level_applicable_for',
                    'std' => '1',
                    'class' => 'rs_social_reward_global_level_applicable_for',
                    'default' => '1',
                    'newids' => 'rs_social_reward_global_level_applicable_for',
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
                    'type' => 'rs_include_products_for_social_reward',
                ),
                array(
                    'type' => 'rs_exclude_products_for_social_reward',
                ),
                array(
                    'name' => __('Include Categories', 'rewardsystem'),
                    'id' => 'rs_include_particular_categories_for_social_reward',
                    'css' => 'min-width:350px;',
                    'std' => '',
                    'class' => 'rs_include_particular_categories_for_social_reward',
                    'default' => '',
                    'newids' => 'rs_include_particular_categories_for_social_reward',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Exclude Categories', 'rewardsystem'),
                    'id' => 'rs_exclude_particular_categories_for_social_reward',
                    'css' => 'min-width:350px;',
                    'std' => '',
                    'class' => 'rs_exclude_particular_categories_for_social_reward',
                    'default' => '',
                    'newids' => 'rs_exclude_particular_categories_for_social_reward',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Enable SUMO Reward Points for Social Promotion', 'rewardsystem'),
                    'desc' => __('This helps to Enable Social Reward Points in Global Level', 'rewardsystem'),
                    'id' => 'rs_global_social_enable_disable_reward',
                    'std' => '2',
                    'default' => '2',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_enable_disable_reward',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Facebook Like Reward Type', 'rewardsystem'),
                    'id' => 'rs_global_social_reward_type_facebook',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_reward_type_facebook',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Facebook Like Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for facebook', 'rewardsystem'),
                    'id' => 'rs_global_social_facebook_reward_points',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_facebook_reward_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Like Reward Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_social_facebook_reward_percent',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_facebook_reward_percent',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Share Reward Type', 'rewardsystem'),
                    'id' => 'rs_global_social_reward_type_facebook_share',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_reward_type_facebook_share',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Facebook Share Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for facebook share', 'rewardsystem'),
                    'id' => 'rs_global_social_facebook_share_reward_points',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_facebook_share_reward_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Share Reward Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_social_facebook_share_reward_percent',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_facebook_share_reward_percent',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Tweet Reward Type', 'rewardsystem'),
                    'id' => 'rs_global_social_reward_type_twitter',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_reward_type_twitter',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Twitter Tweet Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Twitter', 'rewardsystem'),
                    'id' => 'rs_global_social_twitter_reward_points',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_twitter_reward_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Tweet Reward Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_social_twitter_reward_percent',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_twitter_reward_percent',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Follow Reward Type', 'rewardsystem'),
                    'id' => 'rs_global_social_reward_type_twitter_follow',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_reward_type_twitter_follow',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Twitter Follow Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Twitter Follow ', 'rewardsystem'),
                    'id' => 'rs_global_social_twitter_follow_reward_points',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_twitter_follow_reward_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Follow Reward Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_social_twitter_follow_reward_percent',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_twitter_follow_reward_percent',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Google+1 Reward Type', 'rewardsystem'),
                    'desc' => __('Select Social Reward Type for Google by Points/Percentage', 'rewardsystem'),
                    'id' => 'rs_global_social_reward_type_google',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_reward_type_google',
                    'class' => 'show_if_social_tab_enable',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Google+1 Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Google', 'rewardsystem'),
                    'id' => 'rs_global_social_google_reward_points',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_google_reward_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Google+1 Reward Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_social_google_reward_percent',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_google_reward_percent',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('VK.com Like Reward Type', 'rewardsystem'),
                    'desc' => __('Select Social Reward Type for VK by Points/Percentage', 'rewardsystem'),
                    'id' => 'rs_global_social_reward_type_vk',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_reward_type_vk',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('VK.com Like Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for VK', 'rewardsystem'),
                    'id' => 'rs_global_social_vk_reward_points',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_vk_reward_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('VK.com Like Reward Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_social_vk_reward_percent',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_vk_reward_percent',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Instagram Follow Reward Type', 'rewardsystem'),
                    'desc' => __('Select Social Reward Type for Instagram Follow by Points/Percentage', 'rewardsystem'),
                    'id' => 'rs_global_social_reward_type_instagram',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_reward_type_instagram',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Instagram Follow Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Instagram', 'rewardsystem'),
                    'id' => 'rs_global_social_instagram_reward_points',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_instagram_reward_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Instagram Follow Reward Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_social_instagram_reward_percent',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_instagram_reward_percent',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('OK.ru Share Reward Type', 'rewardsystem'),
                    'id' => 'rs_global_social_reward_type_ok_follow',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_reward_type_ok_follow',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('OK.ru Share Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for OK.ru share', 'rewardsystem'),
                    'id' => 'rs_global_social_ok_follow_reward_points',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_ok_follow_reward_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('OK.ru Share Reward Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_social_ok_follow_reward_percent',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_ok_follow_reward_percent',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_global_social_reward_points'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                 array(
                    'type' => 'rs_wrapper_start',
                ),
                 array(
                    'name' => __('Social Reward Points Setting For Post/Page', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_global_social_reward_points_post'
                ),
                array(
                    'name' => __('OK.ru Share Reward Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_social_ok_follow_reward_percent',
                    'class' => 'show_if_social_tab_enable',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_ok_follow_reward_percent',
                    'desc_tip' => true,
                ),
                 array(
                    'name' => __('Enable SUMO Reward Points for Social Promotion in Post/Page', 'rewardsystem'),
                    'desc' => __('This helps to Enable Social Reward Points in Global Level', 'rewardsystem'),
                    'id' => 'rs_global_social_enable_disable_reward_post',
                    'std' => '2',
                    'default' => '2',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_enable_disable_reward_post',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                
                array(
                    'name' => __('Facebook Like Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for facebook', 'rewardsystem'),
                    'id' => 'rs_global_social_facebook_reward_points_post',
                    'class' => 'show_if_social_tab_enable_post',
                    'std' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_facebook_reward_points_post',
                    'desc_tip' => true,
                ),
               
              
                array(
                    'name' => __('Facebook Share Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for facebook share', 'rewardsystem'),
                    'id' => 'rs_global_social_facebook_share_reward_points_post',
                    'class' => 'show_if_social_tab_enable_post',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_facebook_share_reward_points_post',
                    'desc_tip' => true,
                ),
               
              
                array(
                    'name' => __('Twitter Tweet Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Twitter', 'rewardsystem'),
                    'id' => 'rs_global_social_twitter_reward_points_post',
                    'class' => 'show_if_social_tab_enable_post',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_twitter_reward_points_post',
                    'desc_tip' => true,
                ),
               
              
                array(
                    'name' => __('Twitter Follow Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Twitter Follow ', 'rewardsystem'),
                    'id' => 'rs_global_social_twitter_follow_reward_points_post',
                    'class' => 'show_if_social_tab_enable_post',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_twitter_follow_reward_points_post',
                    'desc_tip' => true,
                ),
              
               
                array(
                    'name' => __('Google+1 Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Google', 'rewardsystem'),
                    'id' => 'rs_global_social_google_reward_points_post',
                    'class' => 'show_if_social_tab_enable_post',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_google_reward_points_post',
                    'desc_tip' => true,
                ),
               
                array(
                    'name' => __('VK.com Like Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for VK', 'rewardsystem'),
                    'id' => 'rs_global_social_vk_reward_points_post',
                    'class' => 'show_if_social_tab_enable_post',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_vk_reward_points_post',
                    'desc_tip' => true,
                ),
               
                
                array(
                    'name' => __('Instagram Follow Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Instagram', 'rewardsystem'),
                    'id' => 'rs_global_social_instagram_reward_points_post',
                    'class' => 'show_if_social_tab_enable_post',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_instagram_reward_points_post',
                    'desc_tip' => true,
                ),
              
              
                array(
                    'name' => __('OK.ru Share Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for OK.ru share', 'rewardsystem'),
                    'id' => 'rs_global_social_ok_follow_reward_points_post',
                    'class' => 'show_if_social_tab_enable_post',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_social_ok_follow_reward_points_post',
                    'desc_tip' => true,
                ),
                 array('type' => 'sectionend', 'id' => '_rs_global_social_reward_points_post'),
                  array(
                    'type' => 'rs_wrapper_end',
                ),
               
             
                array(
                    'type' => 'rs_hide_bulk_update_for_social_reward_start',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Social Reward Bulk Update Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_update_social_settings'
                ),
                array(
                    'name' => __('Product/Category Selection', 'rewardsystem'),
                    'id' => 'rs_which_social_product_selection',
                    'std' => '1',
                    'class' => 'rs_which_social_product_selection',
                    'default' => '1',
                    'newids' => 'rs_which_social_product_selection',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('All Products', 'rewardsystem'),
                        '2' => __('Selected Products', 'rewardsystem'),
                        '3' => __('All Categories', 'rewardsystem'),
                        '4' => __('Selected Categories', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Selected Particular Products', 'rewardsystem'),
                    'type' => 'selected_social_products',
                    'id' => 'rs_select_particular_social_products',
                    'class' => 'rs_select_particular_social_categories',
                    'newids' => 'rs_select_particular_social_products',
                ),
                array(
                    'name' => __('Select Particular Categories', 'rewardsystem'),
                    'id' => 'rs_select_particular_social_categories',
                    'css' => 'min-width:350px;',
                    'std' => '1',
                    'class' => 'rs_select_particular_social_categories',
                    'default' => '1',
                    'newids' => 'rs_select_particular_social_categories',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Enable SUMO Reward Points for Social Promotion', 'rewardsystem'),
                    'id' => 'rs_local_enable_disable_social_reward',
                    'std' => '2',
                    'default' => '2',
                    'desc_tip' => true,
                    'desc' => __('Enable will Turn On Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. '
                            . 'Disable will Turn Off Reward Points for Product Purchase and Category/Product Settings will be considered if it is available.', 'rewardsystem'),
                    'newids' => 'rs_local_enable_disable_social_reward',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Facebook Like Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_reward_type_for_facebook',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_local_reward_type_for_facebook',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Facebook Like Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Facebook', 'rewardsystem'),
                    'id' => 'rs_local_reward_points_facebook',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_points_facebook',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Like Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_local_reward_percent_facebook',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_percent_facebook',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Share Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_reward_type_for_facebook_share',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_local_reward_type_for_facebook_share',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Facebook Share Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Facebook Share', 'rewardsystem'),
                    'id' => 'rs_local_reward_points_facebook_share',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_points_facebook_share',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Share Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_local_reward_percent_facebook_share',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_percent_facebook_share',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Tweet Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_reward_type_for_twitter',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_local_reward_type_for_twitter',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Twitter Tweet Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Twitter', 'rewardsystem'),
                    'id' => 'rs_local_reward_points_twitter',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_points_twitter',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Tweet Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points for Twitter', 'rewardsystem'),
                    'id' => 'rs_local_reward_percent_twitter',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_percent_twitter',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Follow Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_reward_type_for_twitter_follow',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_local_reward_type_for_twitter_follow',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Twitter Follow Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Twitter', 'rewardsystem'),
                    'id' => 'rs_local_reward_points_twitter_follow',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_points_twitter_follow',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Follow Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points for Twitter', 'rewardsystem'),
                    'id' => 'rs_local_reward_percent_twitter_follow',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_percent_twitter_follow',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Google+1 Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_reward_type_for_google',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_local_reward_type_for_google',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Google+1 Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Google+', 'rewardsystem'),
                    'id' => 'rs_local_reward_points_google',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_points_google',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Google+1 Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points for Google+', 'rewardsystem'),
                    'id' => 'rs_local_reward_percent_google',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_percent_google',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('VK.com Like Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_reward_type_for_vk',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_local_reward_type_for_vk',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('VK.com Like Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for VK', 'rewardsystem'),
                    'id' => 'rs_local_reward_points_vk',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_points_vk',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('VK.com Like Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points for VK', 'rewardsystem'),
                    'id' => 'rs_local_reward_percent_vk',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_percent_vk',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Instagram Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_reward_type_for_instagram',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_local_reward_type_for_instagram',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Instagram Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Instagram', 'rewardsystem'),
                    'id' => 'rs_local_reward_points_instagram',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_points_instagram',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Instagram Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points for Instagram', 'rewardsystem'),
                    'id' => 'rs_local_reward_percent_instagram',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_percent_instagram',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('OK.ru Share Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_reward_type_for_ok_follow',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_local_reward_type_for_ok_follow',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('OK.ru Share Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Reward Points for Ok.ru', 'rewardsystem'),
                    'id' => 'rs_local_reward_points_ok_follow',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_points_ok_follow',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('OK.ru Share Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points for OK.ru', 'rewardsystem'),
                    'id' => 'rs_local_reward_percent_ok_follow',
                    'class' => 'show_if_social_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_reward_percent_ok_follow',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Test Button', 'rewardsystem'),
                    'desc' => __('This is for testing button', 'rewardsystem'),
                    'id' => 'rs_sumo_reward_button',
                    'std' => '',
                    'type' => 'button_social',
                    'desc_tip' => true,
                    'newids' => 'rs_sumo_reward_button',
                ),
                array('type' => 'sectionend', 'id' => '_rs_update_redeem_settings'),
                array('type' => 'sectionend', 'id' => '_rs_update_social_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_hide_bulk_update_for_social_reward_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Facebook Like & Share Button Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_fblike_setting'
                ),
                array(
                    'name' => __('Language Selection for Facebook Like & Share', 'rewardsystem'),
                    'id' => 'rs_language_selection_for_button',
                    'class' => 'rs_language_selection_for_button',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'options' => array(
                        '1' => __('English(US)', 'rewardsystem'),
                        '2' => __('Default Site Language', 'rewardsystem'),
                    ),
                    'newids' => 'rs_language_selection_for_button',
                ),
                array(
                    'name' => __('Facebook Application ID', 'rewardsystem'),
                    'desc' => __('Please Enter Application ID of your Facebook', 'rewardsystem'),
                    'id' => 'rs_facebook_application_id',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_facebook_application_id',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Like Button', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_facebook_like_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_show_hide_facebook_like_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Facebook URL Selection', 'rewardsystem'),
                    'id' => 'rs_global_social_facebook_url',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_facebook_url',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Default', 'rewardsystem'),
                        '2' => __('Custom', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Facebook Custom URL', 'rewardsystem'),
                    'desc' => __('Enter the Custom URL that you wish to enable for Facebook', 'rewardsystem'),
                    'type' => 'text',
                    'id' => 'rs_global_social_facebook_url_custom',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Social ToolTip for Facebook Like', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_social_tooltip_for_facebook',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => 'Show',
                        '2' => 'Hide'
                    ),
                    'newids' => 'rs_global_show_hide_social_tooltip_for_facebook',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('ToolTip Facebook Like Message', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Message for Facebook Like', 'rewardsystem'),
                    'id' => 'rs_social_message_for_facebook',
                    'std' => 'Facebook Like will fetch you [facebook_like_reward_points] Reward Points',
                    'default' => 'Facebook Like will fetch you [facebook_like_reward_points] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_social_message_for_facebook',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Like Success Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon successful Facebook like', 'rewardsystem'),
                    'id' => 'rs_succcess_message_for_facebook_like',
                    'std' => 'Thanks for liking the Product.  [facebook_like_reward_points] Reward Points has been added to your Account.',
                    'default' => 'Thanks for liking the Product.  [facebook_like_reward_points] Reward Points has been added to your Account.',
                    'type' => 'textarea',
                    'newids' => 'rs_succcess_message_for_facebook_like',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Unlike Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon when Facebook unlike', 'rewardsystem'),
                    'id' => 'rs_unsucccess_message_for_facebook_unlike',
                    'std' => 'You have already Unliked this product on Facebook.You cannot earn points again',
                    'default' => 'You have already Unliked this product on Facebook.You cannot earn points again',
                    'type' => 'textarea',
                    'newids' => 'rs_unsucccess_message_for_facebook_unlike',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Share Button', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_facebook_share_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_show_hide_facebook_share_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Social ToolTip for Facebook Share', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_social_tooltip_for_facebook_share',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => 'Show',
                        '2' => 'Hide'
                    ),
                    'newids' => 'rs_global_show_hide_social_tooltip_for_facebook_share',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('ToolTip Facebook Share Message', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Message for Facebook Share', 'rewardsystem'),
                    'id' => 'rs_social_message_for_facebook_share',
                    'std' => 'Facebook Share will fetch you [facebook_share_reward_points] Reward Points',
                    'default' => 'Facebook Share will fetch you [facebook_share_reward_points] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_social_message_for_facebook_share',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Share Success Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon successful Facebook Share', 'rewardsystem'),
                    'id' => 'rs_succcess_message_for_facebook_share',
                    'std' => 'Thanks for Sharing the Product.[facebook_share_reward_points] Reward Points has been added to your Account.',
                    'default' => 'Thanks for Sharing the Product.[facebook_share_reward_points] Reward Points has been added to your Account.',
                    'type' => 'textarea',
                    'newids' => 'rs_succcess_message_for_facebook_share',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Share UnSuccess Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon Unsuccessful Facebook Share', 'rewardsystem'),
                    'id' => 'rs_unsucccess_message_for_facebook_share',
                    'std' => 'You already shared this post.You cannot earn points again',
                    'default' => 'You already shared this post.You cannot earn points again',
                    'type' => 'textarea',
                    'newids' => 'rs_unsucccess_message_for_facebook_share',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Share Button Label', 'rewardsystem'),
                    'type' => 'text',
                    'id' => 'rs_fbshare_button_label',
                    'class' => 'rs_fbshare_button_label',
                    'newids' => 'rs_fbshare_button_label',
                    'std' => 'Share',
                    'default' => 'Share',
                    'desc_tip' => true,
                    'desc' => __('Enter the Name of Facebook Share that will be displayed in button', 'rewardsystem'),
                ),
                array('type' => 'sectionend', 'id' => '_rs_fblike_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Twitter Tweet & Follow Button Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_twitter_setting'
                ),
                array(
                    'name' => __('Twitter Tweet Button', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_twitter_tweet_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_show_hide_twitter_tweet_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Twitter URL Selection', 'rewardsystem'),
                    'id' => 'rs_global_social_twitter_url',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_twitter_url',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Default', 'rewardsystem'),
                        '2' => __('Custom', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Twitter Custom URL', 'rewardsystem'),
                    'desc' => __('Enter the Custom URL that you wish to enable for Twitter', 'rewardsystem'),
                    'type' => 'text',
                    'id' => 'rs_global_social_twitter_url_custom',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Social ToolTip for Twitter Tweet', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_social_tooltip_for_twitter',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => 'Show',
                        '2' => 'Hide'
                    ),
                    'newids' => 'rs_global_show_hide_social_tooltip_for_twitter',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('ToolTip Twitter Tweet Message', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Message for Twitter', 'rewardsystem'),
                    'id' => 'rs_social_message_for_twitter',
                    'std' => 'Twitter Tweet will fetch you [twitter_tweet_reward_points] Reward Points',
                    'default' => 'Twitter Tweet will fetch you [twitter_tweet_reward_points] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_social_message_for_twitter',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Tweet Success Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon successful Twitter Tweet', 'rewardsystem'),
                    'id' => 'rs_succcess_message_for_twitter_share',
                    'std' => 'Thanks for the tweet . [twitter_tweet_reward_points] Reward Points has been added to your Account',
                    'default' => 'Thanks for the tweet . [twitter_tweet_reward_points] Reward Points has been added to your Account',
                    'type' => 'textarea',
                    'newids' => 'rs_succcess_message_for_twitter_share',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Tweet UnSuccess Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed when tweet deleted in Twitter', 'rewardsystem'),
                    'id' => 'rs_unsucccess_message_for_twitter_unshare',
                    'std' => 'You have already Unshared this product on Twitter.You cannot earn points again',
                    'default' => 'You have already Unshared this product on Twitter.You cannot earn points again',
                    'type' => 'textarea',
                    'newids' => 'rs_unsucccess_message_for_twitter_unshare',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Follow Button', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_twitter_follow_tweet_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_show_hide_twitter_follow_tweet_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Twitter Profile Name', 'rewardsystem'),
                    'desc' => __('Enter the Profile  Name For Twitter Follow ', 'rewardsystem'),
                    'type' => 'text',
                    'id' => 'rs_global_social_twitter_profile_name',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Social ToolTip for Twitter Follow', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_social_tooltip_for_twitter_follow',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => 'Show',
                        '2' => 'Hide'
                    ),
                    'newids' => 'rs_global_show_hide_social_tooltip_for_twitter_follow',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('ToolTip Twitter Follow Message', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Message for Twitter Follow', 'rewardsystem'),
                    'id' => 'rs_social_message_for_twitter_follow',
                    'std' => 'Twitter Follow will fetch you [twitter_follow_reward_points] Reward Points',
                    'default' => 'Twitter Follow will fetch you [twitter_follow_reward_points] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_social_message_for_twitter_follow',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Follow Success Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon successful Twitter Follow', 'rewardsystem'),
                    'id' => 'rs_succcess_message_for_twitter_follow',
                    'std' => 'Thanks for the Follow . [twitter_follow_reward_points] Reward Points has been added to your Account',
                    'default' => 'Thanks for the Follow . [twitter_follow_reward_points] Reward Points has been added to your Account',
                    'type' => 'textarea',
                    'newids' => 'rs_succcess_message_for_twitter_follow',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Twitter Follow UnSuccess Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed when Follow deleted in Twitter', 'rewardsystem'),
                    'id' => 'rs_unsucccess_message_for_twitter_unfollow',
                    'std' => 'You have already follow this Profile on Twitter.You cannot earn points again',
                    'default' => 'You have already follow this Profile on Twitter.You cannot earn points again',
                    'type' => 'textarea',
                    'newids' => 'rs_unsucccess_message_for_twitter_unfollow',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_twitter_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Instagram Button Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_instagram_setting'
                ),
                array(
                    'name' => __('Instagram Button', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_instagram_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_show_hide_instagram_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Instagram Profile Name', 'rewardsystem'),
                    'desc' => __('Please Enter Profile Name of your Instagram', 'rewardsystem'),
                    'id' => 'rs_instagram_profile_name',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_instagram_profile_name',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Social ToolTip for Instagram', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_social_tooltip_for_instagram',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => 'Show',
                        '2' => 'Hide'
                    ),
                    'newids' => 'rs_global_show_hide_social_tooltip_for_instagram',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('ToolTip Instagram Message', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Message for Instagram', 'rewardsystem'),
                    'id' => 'rs_social_message_for_instagram',
                    'std' => 'Instagram Follow will fetch you [instagram_reward_points] Reward Points',
                    'default' => 'Instagram Follow will fetch you [instagram_reward_points] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_social_message_for_instagram',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Instagram Success Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon successful Instagram follow', 'rewardsystem'),
                    'id' => 'rs_succcess_message_for_instagram',
                    'std' => 'Thanks for the follow on Instagram. [instagram_reward_points] Reward Points has been added to your Account',
                    'default' => 'Thanks for the follow on Instagram. [instagram_reward_points] Reward Points has been added to your Account',
                    'type' => 'textarea',
                    'newids' => 'rs_succcess_message_for_instagram',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Instagram Unfollow Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed Unfollow Instagram', 'rewardsystem'),
                    'id' => 'rs_unsucccess_message_for_instagram',
                    'std' => 'You have already Follow this Profile on Instagram.You cannot earn points again',
                    'default' => 'You have already Follow this Profile on Instagram.You cannot earn points again',
                    'type' => 'textarea',
                    'newids' => 'rs_unsucccess_message_for_instagram',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_instagram_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('VK.Com Button Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_vk_setting'
                ),
                array(
                    'name' => __('VK.com Like Button', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_vk_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_show_hide_vk_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('VK Application ID', 'rewardsystem'),
                    'desc' => __('Please Enter Application ID of your VK', 'rewardsystem'),
                    'id' => 'rs_vk_application_id',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_vk_application_id',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Social ToolTip for VK.com', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_social_tooltip_for_vk',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => 'Show',
                        '2' => 'Hide'
                    ),
                    'newids' => 'rs_global_show_hide_social_tooltip_for_vk',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('ToolTip VK.com Like Message ', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Message for VK.com Like', 'rewardsystem'),
                    'id' => 'rs_social_message_for_vk',
                    'std' => 'VK Share will fetch you [vk_reward_points] Reward Points',
                    'default' => 'VK Share will fetch you [vk_reward_points] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_social_message_for_vk',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('VK Like Success Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon successful VK Like', 'rewardsystem'),
                    'id' => 'rs_succcess_message_for_vk',
                    'std' => 'Thanks for the like on VK. [vk_reward_points] Reward Points has been added to your Account',
                    'default' => 'Thanks for the like on VK. [vk_reward_points] Reward Points has been added to your Account',
                    'type' => 'textarea',
                    'newids' => 'rs_succcess_message_for_vk',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('VK Unlike Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed Unlike VK', 'rewardsystem'),
                    'id' => 'rs_unsucccess_message_for_vk',
                    'std' => 'You have already Unlike this product on VK.You cannot earn points again',
                    'default' => 'You have already Unlike this product on VK.You cannot earn points again',
                    'type' => 'textarea',
                    'newids' => 'rs_unsucccess_message_for_vk',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_vk_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Google+1 Button Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_gplus_setting'
                ),
                array(
                    'name' => __('Google+1 Button', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_google_plus_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_show_hide_google_plus_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Google+1 URL Selection', 'rewardsystem'),
                    'id' => 'rs_global_social_google_url',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_google_url',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Default', 'rewardsystem'),
                        '2' => __('Custom', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Google+1 Custom URL', 'rewardsystem'),
                    'desc' => __('Enter the Custom URL that you wish to enable for Google', 'rewardsystem'),
                    'type' => 'text',
                    'id' => 'rs_global_social_google_url_custom',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Social ToolTip for Google+1', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_social_tooltip_for_google',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => 'Show',
                        '2' => 'Hide'
                    ),
                    'newids' => 'rs_global_show_hide_social_tooltip_for_google',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('ToolTip Google+1 Message ', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Message for Google+ Share', 'rewardsystem'),
                    'id' => 'rs_social_message_for_google_plus',
                    'std' => 'Google+1 Share will fetch you [google_share_reward_points] Reward Points',
                    'default' => 'Google+1 Share will fetch you [google_share_reward_points] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_social_message_for_google_plus',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Google+1 Share Success Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon successful Google+ Share', 'rewardsystem'),
                    'id' => 'rs_succcess_message_for_google_share',
                    'std' => 'Thanks for Sharing the Product on Google+ . [google_share_reward_points] Reward Points has been added to your Account',
                    'default' => 'Thanks for Sharing the Product on Google+ . [google_share_reward_points] Reward Points has been added to your Account',
                    'type' => 'textarea',
                    'newids' => 'rs_succcess_message_for_google_share',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Google+1 UnShare Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon when unshare Google+', 'rewardsystem'),
                    'id' => 'rs_unsucccess_message_for_google_unshare',
                    'std' => 'You have already Unshared this product on Google +.You cannot earn points again',
                    'default' => 'You have already Unshared this product on Google +.You cannot earn points again',
                    'type' => 'textarea',
                    'newids' => 'rs_unsucccess_message_for_google_unshare',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_gplus_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('OK.ru Button Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_ok_setting'
                ),
                array(
                    'name' => __('OK.ru Button', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_ok_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_show_hide_ok_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('OK.ru URL Selection', 'rewardsystem'),
                    'id' => 'rs_global_social_ok_url',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_global_social_ok_url',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Default', 'rewardsystem'),
                        '2' => __('Custom', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('OK.ru Custom URL', 'rewardsystem'),
                    'desc' => __('Enter the Custom URL that you wish to enable for OK.ru', 'rewardsystem'),
                    'type' => 'text',
                    'id' => 'rs_global_social_ok_url_custom',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Social ToolTip for OK.ru Share', 'rewardsystem'),
                    'id' => 'rs_global_show_hide_social_tooltip_for_ok_follow',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => 'Show',
                        '2' => 'Hide'
                    ),
                    'newids' => 'rs_global_show_hide_social_tooltip_for_ok_follow',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('ToolTip OK.ru Share Message', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Message for OK.ru Share', 'rewardsystem'),
                    'id' => 'rs_social_message_for_ok_follow',
                    'std' => 'OK.ru Share will fetch you [ok_share_reward_points] Reward Points',
                    'default' => 'OK.ru Share will fetch you [ok_share_reward_points] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_social_message_for_ok_follow',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('OK.ru Share Success Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed upon successful OK.ru Share', 'rewardsystem'),
                    'id' => 'rs_succcess_message_for_ok_follow',
                    'std' => 'Thanks for the Share. [ok_share_reward_points] Reward Points has been added to your Account',
                    'default' => 'Thanks for the Share. [ok_share_reward_points] Reward Points has been added to your Account',
                    'type' => 'textarea',
                    'newids' => 'rs_succcess_message_for_ok_follow',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('OK.ru Share UnSuccess Message', 'rewardsystem'),
                    'desc' => __('Enter the Message that will be displayed when Shared in OK.ru', 'rewardsystem'),
                    'id' => 'rs_unsucccess_message_for_ok_unfollow',
                    'std' => 'You have already Shared this Profile on OK.ru.You cannot earn points again',
                    'default' => 'You have already Shared this Profile on OK.ru.You cannot earn points again',
                    'type' => 'textarea',
                    'newids' => 'rs_unsucccess_message_for_ok_unfollow',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_ok_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Social Button Position Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_point_socialrewards_position_settings'
                ),
                array(
                    'name' => __('Social Buttons display Position in Single Product Page', 'rewardsystem'),
                    'id' => 'rs_global_position_sumo_social_buttons',
                    'std' => '5',
                    'default' => '5',
                    'desc' => __('Some theme do not support all the positions. If the position not support then it might result in a JQuery Conflict.', 'rewardsystem'),
                    'newids' => 'rs_global_position_sumo_social_buttons',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Before Single Product', 'rewardsystem'),
                        '2' => __('Before Single Product Summary', 'rewardsystem'),
                        '3' => __('Single Product Summary', 'rewardsystem'),
                        '4' => __('After Single Product', 'rewardsystem'),
                        '5' => __('After Single Product Summary', 'rewardsystem'),
                        '6' => __('After Product Meta End', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Social Buttons Position display type', 'rewardsystem'),
                    'id' => 'rs_social_button_position_troubleshoot',
                    'newids' => 'rs_social_button_position_troubleshoot',
                    'std' => 'inline',
                    'default' => 'inline',
                    'type' => 'select',
                    'options' => array(
                        'inline' => __('Inline', 'rewardsystem'),
                        'inline-block' => __('Inline Block', 'rewardsystem'),
                        'inline-flex' => __('Inline Flex', 'rewardsystem'),
                        'inline-table' => __('Inline Table', 'rewardsystem'),
                        'table' => __('Table', 'rewardsystem'),
                        'block' => __('Block', 'rewardsystem'),
                        'flex' => __('Flex', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_point_socialrewards_position_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('ToolTip Color Customization', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_global_social_color_customization'
                ),
                array(
                    'name' => __('ToolTip Background Color', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Background Color', 'rewardsystem'),
                    'id' => 'rs_social_tooltip_bg_color',
                    'std' => '000',
                    'default' => '000',
                    'type' => 'text',
                    'class' => 'color',
                    'newids' => 'rs_social_tooltip_bg_color',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('ToolTip Text Color', 'rewardsystem'),
                    'desc' => __('Enter ToolTip Text Color', 'rewardsystem'),
                    'id' => 'rs_social_tooltip_text_color',
                    'std' => 'fff',
                    'default' => 'fff',
                    'type' => 'text',
                    'class' => 'color',
                    'newids' => 'rs_social_tooltip_text_color',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_global_social_color_customization'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shortcodes used in Social Reward', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_shortcodes_in_social_reward',
                ),
                array(
                    'type' => 'title',
                    'desc' => '<b>[facebook_like_reward_points]</b> - To display earning points for facebook like<br><br>'
                    . '<b>[facebook_share_reward_points]</b> - To display earning points for facebook share<br><br>'
                    . '<b>[twitter_tweet_reward_points]</b> - To display earning points for twitter tweet<br><br>'
                    . '<b>[twitter_follow_reward_points]</b> - To display earning points for twitter follow<br><br>'
                    . '<b>[google_share_reward_points]</b> - To display earning points for google share<br><br>'
                    . '<b>[vk_reward_points]</b> - To display earning points for vk like<br><br>'
                    . '<b>[instagram_reward_points]</b> - To display earning points for instagram follow<br><br>'
                    . '<b>[ok_share_reward_points]</b> - To display earning points for ok share',
                ),
                array('type' => 'sectionend', 'id' => 'rs_shortcodes_in_social_reward'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {
            woocommerce_admin_fields(RSSocialReward::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSSocialReward::reward_system_admin_fields());
            if (isset($_POST['rs_select_particular_social_products'])) {
                update_option('rs_select_particular_social_products', $_POST['rs_select_particular_social_products']);
            } else {
                update_option('rs_select_particular_social_products', '');
            }
            if (isset($_POST['rs_social_reward_module_checkbox'])) {
                update_option('rs_social_reward_activated', $_POST['rs_social_reward_module_checkbox']);
            } else {
                update_option('rs_social_reward_activated', 'no');
            }
            if (isset($_POST['rs_include_products_for_social_reward'])) {
                update_option('rs_include_products_for_social_reward', $_POST['rs_include_products_for_social_reward']);
            } else {
                update_option('rs_include_products_for_social_reward', '');
            }
            if (isset($_POST['rs_exclude_products_for_social_reward'])) {
                update_option('rs_exclude_products_for_social_reward', $_POST['rs_exclude_products_for_social_reward']);
            } else {
                update_option('rs_exclude_products_for_social_reward', '');
            }
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSSocialReward::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        public static function rs_function_to_reset_social_reward_tab() {
            $settings = RSSocialReward::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
        }

        public static function rs_function_to_enable_disable_social_reward_module() {
            $get_option_value = get_option('rs_social_reward_activated');
            $name_of_checkbox = 'rs_social_reward_module_checkbox';
            RSModulesTab::rs_common_function_to_get_checkbox($get_option_value, $name_of_checkbox);
        }

        public static function rs_select_products_to_update_social($value) {
            $field_id = "rs_select_particular_social_products";
            $field_label = "Select Particular Products";
            $getproducts = get_option('rs_select_particular_social_products');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        public static function rs_hide_bulk_update_for_social_reward_start() {
            ?>
            <div class="rs_hide_bulk_update_for_social_reward_start">
                <?php
            }

            public static function rs_hide_bulk_update_for_social_reward_end() {
                ?>
            </div>
            <?php
        }

        public static function rs_save_button_for_update_social() {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">                    
                </th>
                <td class="forminp forminp-select">
                    <input type="submit" class="rs_sumo_reward_button_social button-primary rs_save_update" value="Save and Update"/>
                    <img class="gif_rs_sumo_reward_button_social" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/admin/images/update.gif" style="width:32px;height:32px;position:absolute"/>          
                    <div class='rs_sumo_rewards_social' style='margin-bottom:10px; margin-top:10px; color:green;'></div>
                </td>
            </tr>
            <?php
        }

        public function get_ajax_request_for_previous_social_product($post_id) {
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
                                if ($_POST['enabledisablereward'] == '1') {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystemcheckboxvalue', 'yes');
                                } else {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystemcheckboxvalue', 'no');
                                }
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_facebook', $_POST['rewardtypefacebook']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_facebook', $_POST['facebookrewardpoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_facebook', $_POST['facebookrewardpercent']);

                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_facebook_share', $_POST['rewardtypefacebook_share']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_facebook_share', $_POST['facebookrewardpoints_share']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_facebook_share', $_POST['facebookrewardpercent_share']);



                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_twitter', $_POST['rewardtypetwitter']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_twitter', $_POST['twitterrewardpoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_twitter', $_POST['twitterrewardpercent']);


                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_twitter_follow', $_POST['rewardtypetwitter_follow']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_twitter_follow', $_POST['twitterrewardpoints_follow']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_twitter_follow', $_POST['twitterrewardpercent_follow']);


                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_ok_follow', $_POST['rewardtypeok_follow']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_ok_follow', $_POST['okrewardpoints_follow']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_ok_follow', $_POST['okrewardpercent_follow']);


                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_google', $_POST['rewardtypegoogle']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_google', $_POST['googlerewardpoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_google', $_POST['googlerewardpercent']);


                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_vk', $_POST['rewardtypevk']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_vk', $_POST['vkrewardpoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_vk', $_POST['vkrewardpercent']);


                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_instagram', $_POST['rewardtypeinstagram']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_instagram', $_POST['instagramrewardpoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_instagram', $_POST['instagramrewardpercent']);
                            }
                        }
                        echo json_encode("success");
                    } elseif ($_POST['whichproduct'] == '3') {
                        $allcategories = get_terms('product_cat');

                        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                        $products = get_posts($args);
                        foreach ($products as $product) {



                            $term = get_the_terms($product, 'product_cat');
                            if (is_array($term)) {

                                if (is_array($allcategories)) {
                                    foreach ($allcategories as $mycategory) {
                                        if ($_POST['enabledisablereward'] == '1') {
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_social_reward_system_category', 'yes');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'yes');
                                        } else {
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_social_reward_system_category', 'no');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'no');
                                        }
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_facebook', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_facebook', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_facebook', '');

                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_twitter', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_twitter', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_twitter', '');

                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_twitter_follow', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_twitter_follow', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_twitter_follow', '');


                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_google', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_google', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_google', '');


                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_vk', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_vk', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_vk', '');

                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_instagram', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_instagram', '');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_instagram', '');


                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_enable_rs_rule', $_POST['rewardtypefacebook']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_rs_category_points', $_POST['facebookrewardpoints']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_rs_category_percent', $_POST['facebookrewardpercent']);


                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_share_enable_rs_rule', $_POST['rewardtypefacebook_share']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_share_rs_category_points', $_POST['facebookrewardpoints_share']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_share_rs_category_percent', $_POST['facebookrewardpercent_share']);


                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_twitter_enable_rs_rule', $_POST['rewardtypetwitter']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_twitter_rs_category_points', $_POST['twitterrewardpoints']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_twitter_rs_category_percent', $_POST['twitterrewardpercent']);

                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_google_enable_rs_rule', $_POST['rewardtypegoogle']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_google_rs_category_points', $_POST['googlerewardpoints']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_google_rs_category_percent', $_POST['googlerewardpercent']);


                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_vk_enable_rs_rule', $_POST['rewardtypevk']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_vk_rs_category_points', $_POST['vkrewardpoints']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_vk_rs_category_percent', $_POST['vkrewardpercent']);
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

                            if (is_array($mycategorylist)) {
                                foreach ($mycategorylist as $eachlist) {
                                    $term = get_the_terms($product, 'product_cat');
                                    if (is_array($term)) {
                                        foreach ($term as $termidlist) {
                                            if ($eachlist == $termidlist->term_id) {
                                                if ($_POST['enabledisablereward'] == '1') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_social_reward_system_category', 'yes');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'yes');
                                                } else {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_social_reward_system_category', 'no');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'no');
                                                }

                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_facebook', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_facebook', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_facebook', '');

                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_twitter', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_twitter', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_twitter', '');

                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_twitter_follow', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_twitter_follow', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_twitter_follow', '');


                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_google', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_google', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_google', '');


                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_vk', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_vk', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_vk', '');

                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_instagram', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_instagram', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_instagram', '');



                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_enable_rs_rule', $_POST['rewardtypefacebook']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_rs_category_points', $_POST['facebookrewardpoints']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_rs_category_percent', $_POST['facebookrewardpercent']);


                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_share_enable_rs_rule', $_POST['rewardtypefacebook_share']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_share_rs_category_points', $_POST['facebookrewardpoints_share']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_share_rs_category_percent', $_POST['facebookrewardpercent_share']);


                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_twitter_enable_rs_rule', $_POST['rewardtypetwitter']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_twitter_rs_category_points', $_POST['twitterrewardpoints']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_twitter_rs_category_percent', $_POST['twitterrewardpercent']);

                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_google_enable_rs_rule', $_POST['rewardtypegoogle']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_google_rs_category_points', $_POST['googlerewardpoints']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_google_rs_category_percent', $_POST['googlerewardpercent']);


                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_vk_enable_rs_rule', $_POST['rewardtypevk']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_vk_rs_category_points', $_POST['vkrewardpoints']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_vk_rs_category_percent', $_POST['vkrewardpercent']);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        echo json_encode("success");
                    }
                }
                exit();
            }
        }

        public static function rs_chosen_for_category() {
            global $woocommerce;
            if (isset($_GET['page'])) {
                if (isset($_GET['tab']) && isset($_GET['section'])) {
                    if ($_GET['section'] == 'rewardsystem_socialrewards') {
                        if ((float) $woocommerce->version > (float) ('2.2.0')) {
                            echo rs_common_select_function('#rs_select_particular_social_categories');
                            echo rs_common_select_function('#rs_include_particular_categories_for_social_reward');
                            echo rs_common_select_function('#rs_exclude_particular_categories_for_social_reward');
                        } else {
                            echo rs_common_chosen_function('#rs_select_particular_social_categories');
                            echo rs_common_chosen_function('#rs_include_particular_categories_for_social_reward');
                            echo rs_common_chosen_function('#rs_exclude_particular_categories_for_social_reward');
                        }
                    }
                }
            }
        }

        public static function process_chunk_ajax_request_in_social_rewardsystem() {
            if (isset($_POST['ids'])) {
                $products = $_POST['ids'];
                foreach ($products as $product) {


                    if ($_POST['enabledisablereward'] == '1') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'yes');
                    } else {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'no');
                    }
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_facebook', $_POST['rewardtypefacebook']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_facebook', $_POST['facebookrewardpoints']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_facebook', $_POST['facebookrewardpercent']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_facebook_share', $_POST['rewardtypefacebook_share']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_facebook_share', $_POST['facebookrewardpoints_share']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_facebook_share', $_POST['facebookrewardpercent_share']);

                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_twitter', $_POST['rewardtypetwitter']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_twitter', $_POST['twitterrewardpoints']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_twitter', $_POST['twitterrewardpercent']);

                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_twitter_follow', $_POST['rewardtypetwitter_follow']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_twitter_follow', $_POST['twitterrewardpoints_follow']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_twitter_follow', $_POST['twitterrewardpercent_follow']);

                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_ok_follow', $_POST['rewardtypeok_follow']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_ok_follow', $_POST['okrewardpoints_follow']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_ok_follow', $_POST['okrewardpercent_follow']);


                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_google', $_POST['rewardtypegoogle']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_google', $_POST['googlerewardpoints']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_google', $_POST['googlerewardpercent']);


                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_vk', $_POST['rewardtypevk']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_vk', $_POST['vkrewardpoints']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_vk', $_POST['vkrewardpercent']);


                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_instagram', $_POST['rewardtypeinstagram']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_instagram', $_POST['instagramrewardpoints']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_instagram', $_POST['instagramrewardpercent']);
                }
            }
            exit();
        }

        public static function rs_include_products_for_social_reward() {
            $field_id = "rs_include_products_for_social_reward";
            $field_label = "Include Product(s)";
            $getproducts = get_option('rs_include_products_for_social_reward');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        public static function rs_exclude_products_for_social_reward() {
            $field_id = "rs_exclude_products_for_social_reward";
            $field_label = "Exclude Product(s)";
            $getproducts = get_option('rs_exclude_products_for_social_reward');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

    }

    RSSocialReward::init();
}