<?php
/*
 * Support Tab Setting
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSReferralSystemModule')) {

    class RSReferralSystemModule {

        public static function init() {

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'), 999);

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_referral_system_module', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_modules_rewardsystem_referral_system_module', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

            add_action('wp_ajax_nopriv_rs_refer_a_friend_ajax', array(__CLASS__, 'reward_system_process_ajax_request'));

            add_action('wp_ajax_rs_refer_a_friend_ajax', array(__CLASS__, 'reward_system_process_ajax_request'));

            add_action('woocommerce_admin_field_rs_user_role_dynamics_manual', array(__CLASS__, 'reward_system_add_manual_table_to_action'));

            add_action('admin_head', array(__CLASS__, 'rs_chosen_user_role'));

            add_action('woocommerce_admin_field_display_referral_reward_log', array(__CLASS__, 'rs_list_referral_rewards_log'));

            add_action('woocommerce_admin_field_rs_enable_disable_referral_system_module', array(__CLASS__, 'rs_function_to_enable_disable_referral_system_module'));

            add_action('woocommerce_admin_field_image_uploader', array(__CLASS__, 'rs_add_upload_your_facebook_share_image'));

            add_action('woocommerce_admin_field_rs_select_exclude_user_for_referral_link', array(__CLASS__, 'rs_exclude_user_as_hide_referal_link'));

            add_action('woocommerce_admin_field_rs_hide_bulk_update_for_referral_product_purchase_start', array(__CLASS__, 'rs_hide_bulk_update_for_referral_product_purchase_start'));

            add_action('woocommerce_admin_field_rs_hide_bulk_update_for_referral_product_purchase_end', array(__CLASS__, 'rs_hide_bulk_update_for_referral_product_purchase_end'));

            add_action('woocommerce_admin_field_referral_button', array(__CLASS__, 'rs_save_button_for_referral_update'));

            add_action('woocommerce_admin_field_rs_select_user_for_referral_link', array(__CLASS__, 'rs_include_user_as_hide_referal_link'));

            add_action('woocommerce_admin_field_rs_include_products_for_referral_product_purchase', array(__CLASS__, 'rs_include_products_for_referral_product_purchase'));

            add_action('woocommerce_admin_field_rs_exclude_products_for_referral_product_purchase', array(__CLASS__, 'rs_exclude_products_for_referral_product_purchase'));

            add_action('fp_action_to_reset_module_settings_rewardsystem_referral_system_module', array(__CLASS__, 'rs_function_to_referral_system_module'));
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_referral_system_module'] = __('Referral System Module', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;
            global $wp_roles;
            foreach ($wp_roles->roles as $values => $key) {
                $userroleslug[] = $values;
                $userrolename[] = $key['name'];
            }
            $newcombineduserrole = array_combine((array) $userroleslug, (array) $userrolename);
            $categorylist = fp_rs_get_product_category();
            return apply_filters('woocommerce_rewardsystem_referral_system_module', array(
                array(
                    'type' => 'rs_modulecheck_start',
                ),
                array(
                    'name' => __('Referral System Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_activate_referral_module'
                ),
                array(
                    'type' => 'rs_enable_disable_referral_system_module',
                ),
                array('type' => 'sectionend', 'id' => '_rs_activate_referral_module'),
                array(
                    'type' => 'rs_modulecheck_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referral Link Cookies Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_referral_cookies_settings'
                ),
                array(
                    'name' => __('Referral Link Cookies Expires in', 'rewardsystem'),
                    'id' => 'rs_referral_cookies_expiry',
                    'std' => '3',
                    'default' => '3',
                    'newids' => 'rs_referral_cookies_expiry',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Minutes', 'rewardsystem'),
                        '2' => __('Hours', 'rewardsystem'),
                        '3' => __('Days', 'rewardsystem'),
                    ),
                    'desc_tip' => false,
                ),
                array(
                    'name' => __('Referral Link Cookies Expiry in Minutes', 'rewardsystem'),
                    'desc' => __('Enter a Fixed Number greater than or equal to 0', 'rewardsystem'),
                    'id' => 'rs_referral_cookies_expiry_in_min',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_referral_cookies_expiry_in_min',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Referral Link Cookies Expiry in Hours', 'rewardsystem'),
                    'desc' => __('Enter a Fixed Number greater than or equal to 0', 'rewardsystem'),
                    'id' => 'rs_referral_cookies_expiry_in_hours',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_referral_cookies_expiry_in_hours',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Referral Link Cookies Expiry in Days', 'rewardsystem'),
                    'desc' => __('Enter a Fixed Number greater than or equal to 0', 'rewardsystem'),
                    'id' => 'rs_referral_cookies_expiry_in_days',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'text',
                    'newids' => 'rs_referral_cookies_expiry_in_days',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Delete Cookies After X Number of Purchase(s)', 'rewardsystem'),
                    'desc' => __('Enable this option to delete cookies after X number of purchase(s)', 'rewardsystem'),
                    'id' => 'rs_enable_delete_referral_cookie_after_first_purchase',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_delete_referral_cookie_after_first_purchase',
                ),
                array(
                    'name' => __('Number of Purchase(s)', 'rewardsystem'),
                    'desc' => __('Number of Purchase(s) in which cookie to be deleted', 'rewardsystem'),
                    'id' => 'rs_no_of_purchase',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_no_of_purchase',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_referral_cookies_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Linking Referrals for Life Time Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_life_time_referral',
                ),
                array(
                    'name' => __('Linking Referrals for Life Time', 'rewardsystem'),
                    'desc' => __('Enable this option to link referrals for life time', 'rewardsystem'),
                    'id' => 'rs_enable_referral_link_for_life_time',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_referral_link_for_life_time',
                ),
                array('type' => 'sectionend', 'id' => '_rs_life_time_referral'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referral Product Purchase Reward Points Global Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_global_referral_reward_points'
                ),
                array(
                    'name' => __('Referral Product Purchase Reward Points', 'rewardsystem'),
                    'id' => 'rs_enable_product_category_level_for_referral_product_purchase',
                    'class' => 'rs_enable_product_category_level_for_referral_product_purchase',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'radio',
                    'newids' => 'rs_enable_product_category_level_for_referral_product_purchase',
                    'options' => array(
                        'no' => __('Quick Setup (Global Level Settings will be enabled)', 'rewardsystem'),
                        'yes' => __('Advanced Setup (Global,Category and Product Level wil be enabled)', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                    'desc' => __('Quick Setup - Global Level will be enabled<br>Advanced Setup - Global,Category and Product Level wil be enabled', 'rewardsystem')
                ),
                array(
                    'name' => __('Referral Product Purchase Reward Points is applicable for', 'rewardsystem'),
                    'id' => 'rs_referral_product_purchase_global_level_applicable_for',
                    'std' => '1',
                    'class' => 'rs_referral_product_purchase_global_level_applicable_for',
                    'default' => '1',
                    'newids' => 'rs_referral_product_purchase_global_level_applicable_for',
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
                    'type' => 'rs_include_products_for_referral_product_purchase',
                ),
                array(
                    'type' => 'rs_exclude_products_for_referral_product_purchase',
                ),
                array(
                    'name' => __('Include Categories', 'rewardsystem'),
                    'id' => 'rs_include_particular_categories_for_referral_product_purchase',
                    'css' => 'min-width:350px;',
                    'std' => '',
                    'class' => 'rs_include_particular_categories_for_referral_product_purchase',
                    'default' => '',
                    'newids' => 'rs_include_particular_categories_for_referral_product_purchase',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Exclude Categories', 'rewardsystem'),
                    'id' => 'rs_exclude_particular_categories_for_referral_product_purchase',
                    'css' => 'min-width:350px;',
                    'std' => '',
                    'class' => 'rs_exclude_particular_categories_for_referral_product_purchase',
                    'default' => '',
                    'newids' => 'rs_exclude_particular_categories_for_referral_product_purchase',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Global Level Referral Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_enable_disable_sumo_referral_reward',
                    'std' => '2',
                    'default' => '2',
                    'desc_tip' => true,
                    'desc' => __('Global Settings will be considered when Product and Category Settings are Enabled and Values are Empty. '
                            . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order.', 'rewardsystem'),
                    'newids' => 'rs_global_enable_disable_sumo_referral_reward',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Referral Reward Type', 'rewardsystem'),
                    'desc' => __('Select Reward Type by Points/Percentage', 'rewardsystem'),
                    'id' => 'rs_global_referral_reward_type',
                    'class' => 'show_if_enable_in_referral',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_global_referral_reward_type',
                    'type' => 'select',
                    'desc_tip' => true,
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Referral Reward Points', 'rewardsystem'),
                    'id' => 'rs_global_referral_reward_point',
                    'class' => 'show_if_enable_in_referral',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_referral_reward_point',
                    'placeholder' => '',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Referral Reward Points in Percent %', 'rewardsystem'),
                    'id' => 'rs_global_referral_reward_percent',
                    'class' => 'show_if_enable_in_referral',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_referral_reward_percent',
                    'placeholder' => '',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Getting Referred Reward Type', 'rewardsystem'),
                    'desc' => __('Select Reward Type by Points/Percentage', 'rewardsystem'),
                    'id' => 'rs_global_referral_reward_type_refer',
                    'class' => 'show_if_enable_in_referral',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_global_referral_reward_type_refer',
                    'type' => 'select',
                    'desc_tip' => true,
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Reward Points for Getting Referred', 'rewardsystem'),
                    'id' => 'rs_global_referral_reward_point_get_refer',
                    'class' => 'show_if_enable_in_referral',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_referral_reward_point_get_refer',
                    'placeholder' => '',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Reward Points in Percent % For Getting Referred', 'rewardsystem'),
                    'id' => 'rs_global_referral_reward_percent_get_refer',
                    'class' => 'show_if_enable_in_referral',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_global_referral_reward_percent_get_refer',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_global_referral_reward_points'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_hide_bulk_update_for_referral_product_purchase_start',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referral Product Purchase Rewards Bulk Update Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_update_setting',
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
                    'desc_tip' => true,
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
                    'name' => __('Enable Referral Reward Points', 'rewardsystem'),
                    'id' => 'rs_local_enable_disable_referral_reward',
                    'std' => '2',
                    'default' => '2',
                    'desc_tip' => true,
                    'desc' => __('Enable will Turn On Referral Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. '
                            . 'Disable will Turn Off Referral Reward Points for Product Purchase and Category/Product Settings will be considered if it is available.', 'rewardsystem'),
                    'newids' => 'rs_local_enable_disable_referral_reward',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Referral Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_referral_reward_type',
                    'class' => 'show_if_enable_in_update_referral',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_local_referral_reward_type',
                    'type' => 'select',
                    'desc_tip' => true,
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Referral Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Referral Reward Points', 'rewardsystem'),
                    'id' => 'rs_local_referral_reward_point',
                    'class' => 'show_if_enable_in_update_referral',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_referral_reward_point',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Referral Reward Points in Percent %', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                    'id' => 'rs_local_referral_reward_percent',
                    'class' => 'show_if_enable_in_update_referral',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_referral_reward_percent',
                    'placeholder' => '',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Getting Referred Reward Type', 'rewardsystem'),
                    'id' => 'rs_local_referral_reward_type_get_refer',
                    'class' => 'show_if_enable_in_update_referral',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_local_referral_reward_type_get_refer',
                    'type' => 'select',
                    'desc_tip' => true,
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Referral Reward Points for Getting Referred', 'rewardsystem'),
                    'desc' => __('Please Enter Referral Reward Points for getting referred', 'rewardsystem'),
                    'id' => 'rs_local_referral_reward_point_for_getting_referred',
                    'class' => 'show_if_enable_in_update_referral',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_referral_reward_point_for_getting_referred',
                    'placeholder' => '',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Referral Reward Points in Percent % for Getting Referred', 'rewardsystem'),
                    'desc' => __('Please Enter Percentage value of Reward Points for getting referred', 'rewardsystem'),
                    'id' => 'rs_local_referral_reward_percent_for_getting_referred',
                    'class' => 'show_if_enable_in_update_referral',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_referral_reward_percent_for_getting_referred',
                    'placeholder' => '',
                    'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'type' => 'referral_button',
                ),
                array('type' => 'sectionend', 'id' => 'rs_update_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_hide_bulk_update_for_referral_product_purchase_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referral Sign up Reward Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_referral_action_setting',
                ),
                array(
                    'name' => __('Referral Account Sign up Reward Points is Awarded ', 'rewardsystem'),
                    'desc' => __('Select Referral Reward Account Sign up Points Reward type ', 'rewardsystem'),
                    'id' => 'rs_select_referral_points_award',
                    'type' => 'select',
                    'newids' => 'rs_select_referral_points_award',
                    'std' => '1',
                    'default' => '1',
                    'options' => array(
                        '1' => __('Instantly', 'rewardsystem'),
                        '2' => __('After Referral Places Minimum Number of Successful Order(s)', 'rewardsystem'),
                        '3' => __('After Referral Spents the Minimum Amount in Site', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Number of Successful Order(s)', 'rewardsystem'),
                    'desc' => __('Please Enter the Minimum Number Of Sucessful Orders', 'rewardsystem'),
                    'id' => 'rs_number_of_order_for_referral_points',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_number_of_order_for_referral_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Minimum Amount to be Spent by the User', 'rewardsystem'),
                    'desc' => __('Please Enter the Minimum Amount Spent by User', 'rewardsystem'),
                    'id' => 'rs_amount_of_order_for_referral_points',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_amount_of_order_for_referral_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Account Sign up Referral Reward Points after First Purchase', 'rewardsystem'),
                    'desc' => __('Enable this option to award referral reward points for account signup after first purchase', 'rewardsystem'),
                    'id' => 'rs_referral_reward_signup_after_first_purchase',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_referral_reward_signup_after_first_purchase',
                ),
                array(
                    'name' => __('Referral Reward Points for Account Sign up', 'rewardsystem'),
                    'desc' => __('Please Enter the Referral Reward Points that will be earned for Account Sign up', 'rewardsystem'),
                    'id' => 'rs_referral_reward_signup',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_referral_reward_signup',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Enable Reward Points for Getting Referred', 'rewardsystem'),
                    'desc' => __('Enable the Reward Points that will be earned for Getting Referred', 'rewardsystem'),
                    'id' => 'rs_referral_reward_signup_getting_refer',
                    'std' => '2',
                    'type' => 'select',
                    'newids' => 'rs_referral_reward_signup_getting_refer',
                    'std' => '2',
                    'default' => '2',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Enable Reward Points for Getting Referred after first purchase', 'rewardsystem'),
                    'desc' => __('Enable the Reward Points that will be earned for Getting Referred after first purchase', 'rewardsystem'),
                    'id' => 'rs_referral_reward_getting_refer_after_first_purchase',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_referral_reward_getting_refer_after_first_purchase',
                ),
                array(
                    'name' => __('Reward Points for Getting Referred', 'rewardsystem'),
                    'desc' => __('Please Enter the Reward Points that will be earned for Getting Referred', 'rewardsystem'),
                    'id' => 'rs_referral_reward_getting_refer',
                    'std' => '1000',
                    'default' => '1000',
                    'type' => 'text',
                    'newids' => 'rs_referral_reward_getting_refer',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => 'rs_referral_action_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Generate Referral Link Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_my_generate_referral_settings'
                ),
                array(
                    'name' => __('Generate Referral Link', 'rewardsystem'),
                    'id' => 'rs_show_hide_generate_referral',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_generate_referral',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Referral System of SUMO Reward Points is accessible by', 'rewardsystem'),
                    'id' => 'rs_select_type_of_user_for_referral',
                    'css' => 'min-width:100px;',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('All Users', 'rewardsystem'),
                        '2' => __('Include User(s)', 'rewardsystem'),
                        '3' => __('Exclude User(s)', 'rewardsystem'),
                        '4' => __('Include User Role(s)', 'rewardsystem'),
                        '5' => __('Exclude User Role(s)', 'rewardsystem'),
                    ),
                    'newids' => 'rs_select_type_of_user_for_referral',
                    'desc' => __('Referral System includes Referral Table,Refer A Friend Form and Generate Referral Link', 'rewardsystem'),
                    'desc_tip' => true
                ),
                array(
                    'type' => 'rs_select_user_for_referral_link',
                ),
                array(
                    'type' => 'rs_select_exclude_user_for_referral_link',
                ),
                array(
                    'name' => __('Select the User Role for Providing access to Referral System', 'rewardsystem'),
                    'id' => 'rs_select_users_role_for_show_referral_link',
                    'css' => 'min-width:343px;',
                    'std' => '',
                    'default' => '',
                    'placeholder' => 'Select for a User Role',
                    'type' => 'multiselect',
                    'options' => $newcombineduserrole,
                    'newids' => 'rs_select_users_role_for_show_referral_link',
                    'desc_tip' => false,
                ),
                array(
                    'name' => __('Select the User Role for Preventing access to Referral System', 'rewardsystem'),
                    'id' => 'rs_select_exclude_users_role_for_show_referral_link',
                    'css' => 'min-width:343px;',
                    'std' => '',
                    'default' => '',
                    'placeholder' => 'Select for a User Role',
                    'type' => 'multiselect',
                    'options' => $newcombineduserrole,
                    'newids' => 'rs_select_exclude_users_role_for_show_referral_link',
                    'desc_tip' => false,
                ),
                array(
                    'name' => __('Fallback Message for Referral Restriction', 'rewardsystem'),
                    'id' => 'rs_display_msg_when_access_is_prevented',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_display_msg_when_access_is_prevented',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Fallback Message for Referral Restriction', 'rewardsystem'),
                    'id' => 'rs_msg_for_restricted_user',
                    'std' => 'Referral System is currently restricted for your account',
                    'default' => 'Referral System is currently restricted for your account',
                    'type' => 'text',
                    'newids' => 'rs_msg_for_restricted_user',
                ),
                array(
                    'name' => __('Generate Referral Link Label', 'rewardsystem'),
                    'id' => 'rs_generate_link_label',
                    'std' => 'Generate Referral Link',
                    'default' => 'Generate Referral Link',
                    'type' => 'text',
                    'newids' => 'rs_generate_link_label',
                ),
                array(
                    'name' => __('S.No Label', 'rewardsystem'),
                    'id' => 'rs_generate_link_sno_label',
                    'std' => 'S.No',
                    'default' => 'S.No',
                    'type' => 'text',
                    'newids' => 'rs_generate_link_sno_label',
                ),
                array(
                    'name' => __('Date Label', 'rewardsystem'),
                    'id' => 'rs_generate_link_date_label',
                    'std' => 'Date',
                    'default' => 'Date',
                    'type' => 'text',
                    'newids' => 'rs_generate_link_date_label',
                ),
                array(
                    'name' => __('Referral Link Label', 'rewardsystem'),
                    'id' => 'rs_generate_link_referrallink_label',
                    'std' => 'Referral Link',
                    'default' => 'Referral Link',
                    'type' => 'text',
                    'newids' => 'rs_generate_link_referrallink_label',
                ),
                array(
                    'name' => __('Social Label', 'rewardsystem'),
                    'id' => 'rs_generate_link_social_label',
                    'std' => 'Social',
                    'default' => 'Social',
                    'type' => 'text',
                    'newids' => 'rs_generate_link_social_label',
                ),
                array(
                    'name' => __('Action Label', 'rewardsystem'),
                    'id' => 'rs_generate_link_action_label',
                    'std' => 'Action',
                    'default' => 'Action',
                    'type' => 'text',
                    'newids' => 'rs_generate_link_action_label',
                ),
                array(
                    'name' => __('Generate Referral Link Button Label', 'rewardsystem'),
                    'id' => 'rs_generate_link_button_label',
                    'std' => 'Generate Referral Link',
                    'default' => 'Generate Referral Link',
                    'type' => 'text',
                    'newids' => 'rs_generate_link_button_label',
                ),
                array(
                    'name' => __('Generate Referral Link based on Username/User ID', 'rewardsystem'),
                    'id' => 'rs_generate_referral_link_based_on_user',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_generate_referral_link_based_on_user',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Username', 'rewardsystem'),
                        '2' => __('User ID', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Type of Referral Link to be displayed', 'rewardsystem'),
                    'id' => 'rs_show_hide_generate_referral_link_type',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_generate_referral_link_type',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Default', 'rewardsystem'),
                        '2' => __('Static Url', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Prefill Generate Referral Link', 'rewardsystem'),
                    'id' => 'rs_prefill_generate_link',
                    'std' => site_url(),
                    'default' => site_url(),
                    'type' => 'text',
                    'newids' => 'rs_prefill_generate_link',
                ),
                array(
                    'name' => __('My Referral Link Label', 'rewardsystem'),
                    'id' => 'rs_my_referral_link_button_label',
                    'std' => 'My Referral Link',
                    'default' => 'My Referral Link',
                    'type' => 'text',
                    'newids' => 'rs_my_referral_link_button_label',
                ),
                array(
                    'name' => __('Static Referral Link', 'rewardsystem'),
                    'id' => 'rs_static_generate_link',
                    'std' => site_url(),
                    'default' => site_url(),
                    'type' => 'text',
                    'newids' => 'rs_static_generate_link',
                ),
                array(
                    'name' => __('Referral Link Table Position', 'rewardsystem'),
                    'id' => 'rs_display_generate_referral',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_display_generate_referral',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Before My Account ', 'rewardsystem'),
                        '2' => __('After My Account', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Extra Class Name for Generate Referral Link Button', 'rewardsystem'),
                    'desc' => __('Add Extra Class Name to the My Account Generate Referral Link Button, Don\'t Enter dot(.) before Class Name', 'rewardsystem'),
                    'id' => 'rs_extra_class_name_generate_referral_link',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_extra_class_name_generate_referral_link',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Facebook Share Button', 'rewardsystem'),
                    'id' => 'rs_account_show_hide_facebook_like_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_account_show_hide_facebook_like_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Title used for Facebook Share', 'rewardsystem'),
                    'desc' => __('Enter the title of website that shown in Facebook Share', 'rewardsystem'),
                    'type' => 'text',
                    'id' => 'rs_facebook_title',
                    'std' => get_bloginfo(),
                    'default' => get_bloginfo(),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Description used for Facebook Share', 'rewardsystem'),
                    'desc' => __('Enter the description of website that shown in Facebook Share', 'rewardsystem'),
                    'type' => 'text',
                    'id' => 'rs_facebook_description',
                    'std' => get_option('blogdescription'),
                    'default' => get_option('blogdescription'),
                    'desc_tip' => true,
                ),
                array(
                    'type' => 'image_uploader',
                ),
                array(
                    'name' => __('Twitter Tweet Button', 'rewardsystem'),
                    'id' => 'rs_account_show_hide_twitter_tweet_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_account_show_hide_twitter_tweet_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Google+1 Button', 'rewardsystem'),
                    'id' => 'rs_acount_show_hide_google_plus_button',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'newids' => 'rs_acount_show_hide_google_plus_button',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array('type' => 'sectionend', 'id' => '_rs_my_generate_referral_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referrer Earning Restriction Settings', 'rewardsystem'),
                    'type' => 'title',
                    'desc' => __('For eg: If A Refers B then A is the Referrer and B is the Referral', 'rewardsystem'),
                    'id' => '_rs_ban_referee_points_time',
                ),
                array(
                    'name' => __('Referrer should earn points only after the user(Buyer or Referral) is X days old', 'rewardsystem'),
                    'id' => '_rs_select_referral_points_referee_time',
                    'std' => '1',
                    'default' => '1',
                    'newids' => '_rs_select_referral_points_referee_time',
                    'type' => 'select',
                    'desc_tip' => false,
                    'options' => array(
                        '1' => __('Unlimited', 'rewardsystem'),
                        '2' => __('Limited', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Number of Day(s)', 'rewardsystem'),
                    'desc' => __('Enter Fixed Number greater than or equal to 0', 'rewardsystem'),
                    'id' => '_rs_select_referral_points_referee_time_content',
                    'newids' => '_rs_select_referral_points_referee_time_content',
                    'type' => 'text',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('If the Referred Person\'s account is deleted, the Referral Points', 'rewardsystem'),
                    'id' => '_rs_reward_referal_point_user_deleted',
                    'std' => '2',
                    'default' => '2',
                    'newids' => '_rs_reward_referal_point_user_deleted',
                    'type' => 'select',
                    'desc_tip' => false,
                    'options' => array(
                        '1' => __('Should be Revoked', 'rewardsystem'),
                        '2' => __('Shouldn\'t be Revoked', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Applies for Referral account created', 'rewardsystem'),
                    'id' => '_rs_time_validity_to_redeem',
                    'std' => '1',
                    'default' => '1',
                    'newids' => '_rs_time_validity_to_redeem',
                    'type' => 'select',
                    'desc_tip' => false,
                    'options' => array(
                        '1' => __('Any time', 'rewardsystem'),
                        '2' => __('Within specific number of days', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Number of Day(s)', 'rewardsystem'),
                    'desc' => __('Enter Fixed Number greater than or equal to 0', 'rewardsystem'),
                    'id' => '_rs_days_for_redeeming_points',
                    'newids' => '_rs_days_for_redeeming_points',
                    'type' => 'text',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_ban_referee_points_time'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referrer Label Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_referrer_label_settings'
                ),
                array(
                    'name' => __('To display the Message to Referral Person', 'rewardsystem'),
                    'id' => 'rs_show_hide_generate_referral_message',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_generate_referral_message',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Message to display the Referral Person', 'rewardsystem'),
                    'id' => 'rs_show_hide_generate_referral_message_text',
                    'std' => 'You are being referred by [rs_referred_user_name]',
                    'default' => 'You are being referred by [rs_referred_user_name]',
                    'type' => 'text',
                    'newids' => 'rs_show_hide_generate_referral_message_text',
                ),
                array('type' => 'sectionend', 'id' => '_rs_referrer_label_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('My Referral Table Label Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_my_referal_label_settings'
                ),
                array(
                    'name' => __('Referral Table ', 'rewardsystem'),
                    'id' => 'rs_show_hide_referal_table',
                    'std' => '2',
                    'default' => '2',
                    'default' => '2',
                    'newids' => 'rs_show_hide_referal_table',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Referral Table Label', 'rewardsystem'),
                    'desc' => __('Enter the Referral Table Label', 'rewardsystem'),
                    'id' => 'rs_referal_table_title',
                    'std' => 'Referral Table',
                    'default' => 'Referral Table',
                    'type' => 'text',
                    'newids' => 'rs_referal_table_title',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('S.No Label', 'rewardsystem'),
                    'desc' => __('Enter the Serial Number Label', 'rewardsystem'),
                    'id' => 'rs_my_referal_sno_label',
                    'std' => 'S.No',
                    'default' => 'S.No',
                    'type' => 'text',
                    'newids' => 'rs_my_referal_sno_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Referral Username Label', 'rewardsystem'),
                    'desc' => __('Enter the Referral Username Label', 'rewardsystem'),
                    'id' => 'rs_my_referal_userid_label',
                    'std' => 'Username',
                    'default' => 'Username',
                    'type' => 'text',
                    'newids' => 'rs_my_referal_userid_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Total Referral Points Label', 'rewardsystem'),
                    'desc' => __('Enter the Total Referral Points Label', 'rewardsystem'),
                    'id' => 'rs_my_total_referal_points_label',
                    'std' => 'Total Referral Points',
                    'default' => 'Total Referral Points',
                    'type' => 'text',
                    'newids' => 'rs_my_total_referal_points_label',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_my_referal_label_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Refer a Friend Form Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_referfriend_status'
                ),
                array(
                    'name' => __('Friend Name Label', 'rewardsystem'),
                    'desc' => __('Enter Friend Name Label which will be available in Frontend when you use shortcode', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_name_label',
                    'std' => 'Your Friend Name',
                    'default' => 'Your Friend Name',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_name_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Friend Name Field Placeholder', 'rewardsystem'),
                    'desc' => __('Enter Friend Name Field Placeholder which will be appear in frontend when you use shortcode', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_name_placeholder',
                    'std' => 'Enter your Friend Name',
                    'default' => 'Enter your Friend Name',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_name_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Friend Email Label', 'rewardsystem'),
                    'desc' => __('Enter Friend Email Label which will be available in Frontend when you use shortcode', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_email_label',
                    'std' => 'Your Friend Email',
                    'default' => 'Your Friend Email',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_email_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Friend Email Field Placeholder', 'rewardsystem'),
                    'desc' => __('Enter Friend Email Field Placeholder which will be appear in frontend when you use shortcode', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_email_placeholder',
                    'std' => 'Enter your Friend Email',
                    'default' => 'Enter your Friend Email',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_email_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Friend Email Subject Label', 'rewardsystem'),
                    'desc' => __('Enter Friend Subject which will be appear in Frontend when you use shortcode', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_subject_label',
                    'std' => 'Your Subject',
                    'default' => 'Your Subject',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_subject_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Friend Email Subject Field Placeholder', 'rewardsystem'),
                    'desc' => __('Enter Friend Email Subject Field Placeholder which will be appear in frontend when you use shortcode', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_email_subject_placeholder',
                    'std' => 'Enter your Subject',
                    'default' => 'Enter your Subject',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_email_subject_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Friend Email Message Label', 'rewardsystem'),
                    'desc' => __('Enter Friend Email Message which will be appear in frontend when you use shortcode', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_message_label',
                    'std' => 'Your Message',
                    'default' => 'Your Message',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_message_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Friend Email Message Field Placeholder', 'rewardsystem'),
                    'desc' => __('Enter Friend Email Message Field Placeholder which will be appear in frontend when you use shortcode', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_email_message_placeholder',
                    'std' => 'Enter your Message',
                    'default' => 'Enter your Message',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_email_message_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Prefilled Message for Refer a Friend', 'rewardsystem'),
                    'desc' => __('This Message will be displayed in the Message field along with the Referral link', 'rewardsystem'),
                    'id' => 'rs_friend_referral_link',
                    'std' => 'You can Customize your message here.[site_referral_url]',
                    'default' => 'You can Customize your message here.[site_referral_url]',
                    'type' => 'textarea',
                    'newids' => 'rs_friend_referral_link',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Show/Hide I agree to the Terms and Condition Field', 'rewardsystem'),
                    'id' => 'rs_show_hide_iagree_termsandcondition_field',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_iagree_termsandcondition_field',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Hide', 'rewardsystem'),
                        '2' => __('Show', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('I Agree Field Label', 'rewardsystem'),
                    'desc' => __('This Caption will be displayed for the I agree field in Refer a Friend Form', 'rewardsystem'),
                    'id' => 'rs_refer_friend_iagreecaption_link',
                    'std' => 'I agree to the {termsandconditions}',
                    'default' => 'I agree to the {termsandconditions}',
                    'type' => 'textarea',
                    'newids' => 'rs_refer_friend_iagreecaption_link',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Terms and Conditions Label', 'rewardsystem'),
                    'desc' => __('This Caption will be displayed for terms and condition', 'rewardsystem'),
                    'id' => 'rs_refer_friend_termscondition_caption',
                    'std' => 'Terms and Conditions',
                    'default' => 'Terms and Conditions',
                    'type' => 'textarea',
                    'newids' => 'rs_refer_friend_termscondition_caption',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Terms and Conditions URL', 'rewardsystem'),
                    'desc' => __('Enter the URL for Terms and Conditions', 'rewardsystem'),
                    'id' => 'rs_refer_friend_termscondition_url',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_refer_friend_termscondition_url',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_referfriend_status'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Error Message Settings for Refer a Friend Form', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_referfriend_error_settings'
                ),
                array(
                    'name' => __('Error Message to display when Friend Name Field is left empty', 'rewardsystem'),
                    'desc' => __('Enter your Error Message which will be appear in frontend if the Friend Name is Empty', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_name_error_message',
                    'std' => 'Please Enter your Friend Name',
                    'default' => 'Please Enter your Friend Name',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_name_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when Friend Email Field is left empty', 'rewardsystem'),
                    'desc' => __('Enter your Error Message which will be appear in frontend if the Friend Email is Empty', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_email_error_message',
                    'std' => 'Please Enter your Friend Email',
                    'default' => 'Please Enter your Friend Email',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_email_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when Email format is not valid', 'rewardsystem'),
                    'desc' => __('Enter your Error Message which will be appear in frontend if the Friend Email is not Valid', 'rewardsystem'),
                    'id' => 'rs_my_rewards_friend_email_is_not_valid',
                    'std' => 'Enter Email is not Valid',
                    'default' => 'Enter Email is not Valid',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_friend_email_is_not_valid',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when Email Subject is left empty', 'rewardsystem'),
                    'desc' => __('Enter your Error Message which will be appear in frontend if the Email Subject is Empty', 'rewardsystem'),
                    'id' => 'rs_my_rewards_email_subject_error_message',
                    'std' => 'Email Subject should not be left blank',
                    'default' => 'Email Subject should not be left blank',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_email_subject_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when Email Message is left empty', 'rewardsystem'),
                    'desc' => __('Enter your Error Message which will be appear in frontend if the Email Message is Empty', 'rewardsystem'),
                    'id' => 'rs_my_rewards_email_message_error_message',
                    'std' => 'Please Enter your Message',
                    'default' => 'Please Enter your Message',
                    'type' => 'text',
                    'newids' => 'rs_my_rewards_email_message_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when I agree checkbox is unchecked', 'rewardsystem'),
                    'desc' => __('Enter your Error Message which will be appear in frontend if i agree is unchecked', 'rewardsystem'),
                    'id' => 'rs_iagree_error_message',
                    'std' => 'Please Accept our Terms and Condition',
                    'default' => 'Please Accept our Terms and Condition',
                    'type' => 'text',
                    'newids' => 'rs_iagree_error_message',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_referfriend_error_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Manual Referral Link Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_manual_setting'
                ),
                array(
                    'type' => 'rs_user_role_dynamics_manual',
                ),
                array('type' => 'sectionend', 'id' => '_rs_manual_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referral Reward Table', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_referral_setting',
                ),
                array(
                    'type' => 'display_referral_reward_log',
                ),
                array('type' => 'sectionend', 'id' => 'rs_referral_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shortcodes used in Refer a Friend', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_shortcodes_in_refer_a_friend',
                ),
                array(
                    'type' => 'title',
                    'desc' => '<b>[site_referral_url]</b> - To display referrer url<br><br>'
                    . '<b>{termsandconditions}</b> - To display the link for terms and conditions',
                ),
                array('type' => 'sectionend', 'id' => 'rs_shortcodes_in_refer_a_friend'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {
            woocommerce_admin_fields(RSReferralSystemModule::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSReferralSystemModule::reward_system_admin_fields());
            if (isset($_POST['rs_select_exclude_users_list_for_show_referral_link'])) {
                update_option('rs_select_exclude_users_list_for_show_referral_link', $_POST['rs_select_exclude_users_list_for_show_referral_link']);
            } else {
                update_option('rs_select_exclude_users_list_for_show_referral_link', '');
            }
            if (isset($_POST['rs_select_include_users_for_show_referral_link'])) {
                update_option('rs_select_include_users_for_show_referral_link', $_POST['rs_select_include_users_for_show_referral_link']);
            } else {
                update_option('rs_select_include_users_for_show_referral_link', '');
            }
            if (isset($_POST['rs_fbshare_image_url_upload'])) {
                update_option('rs_fbshare_image_url_upload', $_POST['rs_fbshare_image_url_upload']);
            } else {
                update_option('rs_fbshare_image_url_upload', '');
            }
            if (isset($_POST['rewards_dynamic_rule_manual'])) {
                $rewards_dynamic_rulerule_manual = array_values($_POST['rewards_dynamic_rule_manual']);
                update_option('rewards_dynamic_rule_manual', $rewards_dynamic_rulerule_manual);
            }
            if (isset($_POST['rs_referral_module_checkbox'])) {
                update_option('rs_referral_activated', $_POST['rs_referral_module_checkbox']);
            } else {
                update_option('rs_referral_activated', 'no');
            }
            if (isset($_POST['rs_include_products_for_referral_product_purchase'])) {
                update_option('rs_include_products_for_referral_product_purchase', $_POST['rs_include_products_for_referral_product_purchase']);
            } else {
                update_option('rs_include_products_for_referral_product_purchase', '');
            }
            if (isset($_POST['rs_exclude_products_for_referral_product_purchase'])) {
                update_option('rs_exclude_products_for_referral_product_purchase', $_POST['rs_exclude_products_for_referral_product_purchase']);
            } else {
                update_option('rs_exclude_products_for_referral_product_purchase', '');
            }
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSReferralSystemModule::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        public static function rs_save_button_for_referral_update() {
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

        public static function rs_hide_bulk_update_for_referral_product_purchase_start() {
            ?>
            <div class="rs_hide_bulk_update_for_referral_product_purchase_start">
                <?php
            }

            public static function rs_hide_bulk_update_for_referral_product_purchase_end() {
                ?>
            </div>
            <?php
        }

        public static function rs_function_to_referral_system_module() {
            $settings = RSReferralSystemModule::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
            delete_option('rewards_dynamic_rule_manual');
        }

        public static function rs_function_to_enable_disable_referral_system_module() {
            $get_option_value = get_option('rs_referral_activated');
            $name_of_checkbox = 'rs_referral_module_checkbox';
            RSModulesTab::rs_common_function_to_get_checkbox($get_option_value, $name_of_checkbox);
        }

        public static function rs_exclude_user_as_hide_referal_link() {
            $field_id = "rs_select_exclude_users_list_for_show_referral_link";
            $field_label = "Select the Users for Preventing access to Referral System";
            $getuser = get_option('rs_select_exclude_users_list_for_show_referral_link');
            echo rs_function_to_add_field_for_user_select($field_id, $field_label, $getuser);
            ?>
            <style type="text/css">
                .chosen-container-single {
                    position:absolute;
                }
            </style>
            <?php
        }

        public static function rs_include_user_as_hide_referal_link() {
            $field_id = "rs_select_include_users_for_show_referral_link";
            $field_label = "Select the Users for Providing access to Referral System";
            $getuser = get_option('rs_select_include_users_for_show_referral_link');
            echo rs_function_to_add_field_for_user_select($field_id, $field_label, $getuser);
            ?>
            <style type="text/css">
                .chosen-container-single {
                    position:absolute;
                }

            </style>
            <?php
        }

        public static function reward_system_process_ajax_request() {
            global $woocommerce;
            if (isset($_POST)) {
                if (isset($_POST['friendname'])) {
                    $friendname = $_POST['friendname'];
                }
                if (isset($_POST['friendemail'])) {
                    $friendemail = $_POST['friendemail'];
                }
                if (isset($_POST['friendsubject'])) {
                    $friendsubject = $_POST['friendsubject'];
                }
                if (isset($_POST['friendmessage'])) {
                    
                }
                $name_n = explode(",", $friendname);
                $email_n = explode(",", $friendemail);
                foreach ($email_n as $key => $value) {
                    $friendmessage = __('Hi ', 'rewardsystem') . $name_n[$key] . '<br>';
                    $friendmessage .= $_POST['friendmessage'];
                    ob_start();
                    wc_get_template('emails/email-header.php', array('email_heading' => $friendsubject));
                    echo wpautop(stripslashes($friendmessage));
                    wc_get_template('emails/email-footer.php');
                    $woo_rs_msg = ob_get_clean();
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $headers .= "From: " . get_option('woocommerce_email_from_name') . " <" . get_option('woocommerce_email_from_address') . ">\r\n";
                    $headers .= "Reply-To: " . get_option('woocommerce_email_from_name') . " <" . get_option('woocommerce_email_from_address') . ">\r\n";
                    if (get_option('rs_select_mail_function') == '1') {
                        mail($value, $friendsubject, $woo_rs_msg, $headers);
                    } else {
                        if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                            wp_mail($value, $friendsubject, $woo_rs_msg, $headers);
                        } else {
                            $mailer = WC()->mailer();
                            $mailer->send($value, $friendsubject, $woo_rs_msg, $headers);
                        }
                    }
                    error_reporting(E_ALL);
                    ini_set('display_errors', '1');
                }
            }
            exit();
        }

        public static function reward_system_add_manual_table_to_action() {
            global $woocommerce;
            wp_nonce_field(plugin_basename(__FILE__), 'rsdynamicrulecreation_manual');
            global $woocommerce;
            ?>
            <style type="text/css">
                .rs_manual_linking_referral{
                    width:60%;
                }
                .rs_manual_linking_referer{
                    width:60%;
                }
                .chosen-container-single {
                    position:absolute;
                }
                .column-columnname-link{
                    width:10%;               
                }            

            </style>
            <?php
            echo rs_common_ajax_function_to_select_user('rs_manual_linking_referer');
            echo rs_common_ajax_function_to_select_user('rs_manual_linking_referral');
            ?>
            <table class="widefat fixed rsdynamicrulecreation_manual" cellspacing="0">
                <thead>
                    <tr>

                        <th class="manage-column column-columnname" scope="col"><?php _e('Referrer Username', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Buyer Username', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname-link" scope="col"><?php _e('Linking Type', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Linking', 'rewardsystem'); ?></th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="manage-column column-columnname num" scope="col"> <span class="add button-primary"><?php _e('Add Linking', 'rewardsystem'); ?></span></td>
                    </tr>
                    <tr>

                        <th class="manage-column column-columnname" scope="col"><?php _e('Referrer Username', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Buyer Username', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname-link" scope="col"><?php _e('Linking Type', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Add Linking', 'rewardsystem'); ?></th>

                    </tr>
                </tfoot>

                <tbody id="here">
                    <?php
                    $rewards_dynamic_rulerule_manual = get_option('rewards_dynamic_rule_manual');
                    $i = 0;
                    if (is_array($rewards_dynamic_rulerule_manual)) {
                        foreach ($rewards_dynamic_rulerule_manual as $rewards_dynamic_rule) {
                            if ($rewards_dynamic_rule['referer'] != '' && $rewards_dynamic_rule['refferal'] != '') {
                                ?>
                                <tr>
                                    <td class="column-columnname">
                                        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                            <select name="rewards_dynamic_rule_manual[<?php echo $i; ?>][referer]" class="short rs_manual_linking_referer">
                                                <?php
                                                $user = get_user_by('id', absint($rewards_dynamic_rule['referer']));
                                                echo '<option value="' . absint($user->ID) . '" ';
                                                selected(1, 1);
                                                echo '>' . esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')</option>';
                                                ?>
                                            </select>
                                            <?php
                                        } else {
                                            $user_id = absint($rewards_dynamic_rule['referer']);
                                            $user = get_user_by('id', $user_id);
                                            $user_string = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')';
                                            if ((float) $woocommerce->version >= (float) ('3.0.0')) {
                                                ?>
                                                <select multiple="multiple"  class="wc-customer-search" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][referer]" data-placeholder="<?php _e('Search Users', 'rewardsystem'); ?>" >
                                                    <option value="<?php echo $user_id; ?>" selected="selected"><?php echo esc_attr($user_string); ?><option>
                                                </select>
                                            <?php } else {
                                                ?>
                                                <input type="hidden" class="wc-customer-search" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][referer]" data-placeholder="<?php _e('Search for a customer&hellip;', 'rewardsystem'); ?>" data-selected="<?php echo esc_attr($user_string); ?>" value="<?php echo $user_id; ?>" data-allow_clear="true" />
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="column-columnname">
                                        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                            <select name="rewards_dynamic_rule_manual[<?php echo $i; ?>][refferal]" class="short rs_manual_linking_referral">
                                                <?php
                                                $user = get_user_by('id', absint($rewards_dynamic_rule['refferal']));
                                                echo '<option value="' . absint($user->ID) . '" ';
                                                selected(1, 1);
                                                echo '>' . esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')</option>';
                                                ?>
                                            </select>
                                        <?php } else { ?>
                                            <?php
                                            $user_id = absint($rewards_dynamic_rule['refferal']);
                                            $user = get_user_by('id', $user_id);
                                            $user_string = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')';
                                            if ((float) $woocommerce->version >= (float) ('3.0.0')) {
                                                ?>
                                                <select multiple="multiple"  class="wc-customer-search" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][refferal]" data-placeholder="<?php _e('Search Users', 'rewardsystem'); ?>" >
                                                    <option value="<?php echo $user_id; ?>" selected="selected"><?php echo esc_attr($user_string); ?><option>
                                                </select>
                                            <?php } else { ?>
                                                <input type="hidden" class="wc-customer-search" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][refferal]" data-placeholder="<?php _e('Search for a customer&hellip;', 'rewardsystem'); ?>" data-selected="<?php echo esc_attr($user_string); ?>" value="<?php echo $user_id; ?>" data-allow_clear="true" />
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="column-columnname-link">    <?php
                                        if (@$rewards_dynamic_rule['type'] != '') {
                                            ?>
                                            <span> <b>Automatic</b></span>
                                            <?php
                                        } else {
                                            ?>
                                            <span> <b>Manual</b></span>
                                            <?php
                                        }
                                        ?>
                                        <input type="hidden" value="<?php echo @$rewards_dynamic_rule['type']; ?>" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][type]"/>
                                    </td>
                                    <td class="column-columnname num">
                                        <span class="remove button-secondary"><?php _e('Remove Linking', 'rewardsystem'); ?></span>
                                    </td>
                                </tr>
                                <?php
                                $i = $i + 1;
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
            <script>
                jQuery(document).ready(function () {
                    var countrewards_dynamic_rule = <?php echo $i; ?>;
                    jQuery(".add").click(function () {
                        countrewards_dynamic_rule = countrewards_dynamic_rule + 1;
            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>

                            jQuery('#here').append('<tr><td><select name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][referer]" class="short rs_manual_linking_referer"><option value=""></option></select></td>\n\
                                                                                                                                \n\<td><select name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][refferal]" class="short rs_manual_linking_referral"><option value=""></option></select></td>\n\
                                                                                                                                \n\<td class="column-columnname-link" ><span><input type="hidden" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][type]"  value="" class="short "/><b>Manual</b></span></td>\n\
                                                                                                                            \n\
                                                                                                                            <td class="num"><span class="remove button-secondary">Remove Linking</span></td></tr><hr>');
                            jQuery(function () {
                                // Ajax Chosen Product Selectors
                                jQuery("select.rs_manual_linking_referer").ajaxChosen({
                                    method: 'GET',
                                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                    dataType: 'json',
                                    afterTypeDelay: 100,
                                    data: {
                                        action: 'woocommerce_json_search_customers',
                                        security: '<?php echo wp_create_nonce("search-customers"); ?>'
                                    }
                                }, function (data) {
                                    var terms = {};

                                    jQuery.each(data, function (i, val) {
                                        terms[i] = val;
                                    });
                                    return terms;
                                });
                            });
                            jQuery(function () {
                                // Ajax Chosen Product Selectors
                                jQuery("select.rs_manual_linking_referral").ajaxChosen({
                                    method: 'GET',
                                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                    dataType: 'json',
                                    afterTypeDelay: 100,
                                    data: {
                                        action: 'woocommerce_json_search_customers',
                                        security: '<?php echo wp_create_nonce("search-customers"); ?>'
                                    }
                                }, function (data) {
                                    var terms = {};

                                    jQuery.each(data, function (i, val) {
                                        terms[i] = val;
                                    });
                                    return terms;
                                });
                            });
                <?php
            } else {
                if ((float) $woocommerce->version >= (float) ('3.0.0')) {
                    ?>
                                jQuery('#here').append('<tr><td><select class="wc-customer-search" style="width:250px;" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][referer]" data-placeholder="<?php _e("Search for a customer&hellip;", "rewardsystem"); ?>" data-allow_clear="true"><option value=""></option></select></td>\n\
                                                                                                                                                            \n\<td><select class="wc-customer-search" style="width:250px;" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][refferal]" data-placeholder="<?php _e("Search for a customer&hellip;", "rewardsystem"); ?>" data-allow_clear="true"><option value=""></option></select></td>\n\
                                                                                                                                                          \n\<td class="column-columnname-link" ><span><input type="hidden" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][type]"  value="" class="short "/><b>Manual</b></span></td>\n\
                                                                                                                                                        \n\
                                                                                                                                                        <td class="num"><span class="remove button-secondary">Remove Linking</span></td></tr><hr>');
                                jQuery('body').trigger('wc-enhanced-select-init');
                <?php } else { ?>
                                jQuery('#here').append('<tr><td><input type="hidden" class="wc-customer-search" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][referer]" data-placeholder="<?php _e("Search for a customer&hellip;", "rewardsystem"); ?>" data-selected="" value="" data-allow_clear="true"/></td>\n\
                                                                                                                                                            \n\<td><input type="hidden" class="wc-customer-search" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][refferal]" data-placeholder="<?php _e("Search for a customer&hellip;", "rewardsystem"); ?>" data-selected="" value="" data-allow_clear="true"/></td>\n\
                                                                                                                                                          \n\<td class="column-columnname-link" ><span><input type="hidden" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][type]"  value="" class="short "/><b>Manual</b></span></td>\n\
                                                                                                                                                        \n\
                                                                                                                                                        <td class="num"><span class="remove button-secondary">Remove Linking</span></td></tr><hr>');
                                jQuery('body').trigger('wc-enhanced-select-init');
                    <?php
                }
            }
            ?>
                        return false;
                    });
                    jQuery(document).on('click', '.remove', function () {
                        jQuery(this).parent().parent().remove();
                    });
                });</script>

            <?php
        }

        public static function rs_chosen_user_role() {
            global $woocommerce;
            if (isset($_GET['page'])) {
                if (isset($_GET['tab']) && isset($_GET['section'])) {
                    if ($_GET['section'] == 'rewardsystem_referral_system_module') {
                        if ((float) $woocommerce->version > (float) ('2.2.0')) {
                            echo rs_common_select_function('#rs_select_users_role_for_show_referral_link');
                            echo rs_common_select_function('#rs_select_exclude_users_role_for_show_referral_link');
                            echo rs_common_select_function('#rs_include_particular_categories_for_referral_product_purchase');
                            echo rs_common_select_function('#rs_exclude_particular_categories_for_referral_product_purchase');
                        } else {
                            echo rs_common_chosen_function('#rs_select_users_role_for_show_referral_link');
                            echo rs_common_chosen_function('#rs_select_exclude_users_role_for_show_referral_link');
                            echo rs_common_chosen_function('#rs_include_particular_categories_for_referral_product_purchase');
                            echo rs_common_chosen_function('#rs_exclude_particular_categories_for_referral_product_purchase');
                        }
                    }
                }
            }
        }

        public static function rs_list_referral_rewards_log() {
            if ((!isset($_GET['view']))) {
                $newwp_list_table_for_users = new WP_List_Table_for_Referral_Table();
                $newwp_list_table_for_users->prepare_items();
                $newwp_list_table_for_users->search_box('Search Users', 'search_id');
                $newwp_list_table_for_users->display();
            } else {
                $newwp_list_table_for_users = new WP_List_Table_for_View_Referral_Table();
                $newwp_list_table_for_users->prepare_items();
                $newwp_list_table_for_users->search_box('Search', 'search_id');
                $newwp_list_table_for_users->display();
                ?>
                <a href="<?php echo remove_query_arg(array('view'), get_permalink()); ?>">Go Back</a>
                <?php
            }
        }

        public static function rs_add_upload_your_facebook_share_image() {
            ?>           
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_fbshare_image_url_upload"><?php _e('Image used for Facebook Share', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="text" id="rs_fbshare_image_url_upload" name="rs_fbshare_image_url_upload" value="<?php echo get_option('rs_fbshare_image_url_upload'); ?>"/>
                    <input type="submit" id="rs_fbimage_upload_button" class="rs_imgupload_button" name="rs_fbimage_upload_button" value="Upload Image"/>
                </td>
            </tr>            
            <?php
            rs_ajax_for_upload_your_gift_voucher('#rs_fbshare_image_url_upload');
        }

        public static function rs_include_products_for_referral_product_purchase() {
            $field_id = "rs_include_products_for_referral_product_purchase";
            $field_label = "Include Product(s)";
            $getproducts = get_option('rs_include_products_for_referral_product_purchase');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        public static function rs_exclude_products_for_referral_product_purchase() {
            $field_id = "rs_exclude_products_for_referral_product_purchase";
            $field_label = "Exclude Product(s)";
            $getproducts = get_option('rs_exclude_products_for_referral_product_purchase');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

    }

    RSReferralSystemModule::init();
}