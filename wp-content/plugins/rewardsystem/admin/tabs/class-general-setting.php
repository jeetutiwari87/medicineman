<?php
/*
 * General Tab Setting
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSGeneralTabSetting')) {

    class RSGeneralTabSetting {

        public static function init() {

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings

            add_action('woocommerce_rs_settings_tabs_rewardsystem_general', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab

            add_action('woocommerce_update_options_rewardsystem_general', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system            

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'), 999);

            add_action('admin_head', array(__CLASS__, 'rs_chosen_for_general_tab'));

            add_action('woocommerce_admin_field_rs_select_user_to_restrict_ban', array(__CLASS__, 'rs_select_user_to_ban'));

            add_action('woocommerce_admin_field_rs_user_purchase_history', array(__CLASS__, 'rs_function_to_add_rule_based_on_user_purchase_history'));

            add_action('woocommerce_admin_field_rs_user_role_dynamics', array(__CLASS__, 'reward_system_add_table_to_action'));

            add_action('admin_head', array(__CLASS__, 'get_woocommerce_upload_field'));

            add_action('woocommerce_admin_field_earning_conversion', array(__CLASS__, 'reward_system_earning_points_conversion'));

            add_action('woocommerce_admin_field_redeeming_conversion', array(__CLASS__, 'reward_system_redeeming_points_conversion'));

            add_action('woocommerce_admin_field_rs_refresh_button', array(__CLASS__, 'refresh_button_for_expired'));

            add_action('admin_head', array(__CLASS__, 'rs_send_ajax_to_refresh_expired_points'));

            add_action('wp_ajax_nopriv_rsrefreshexpiredpoints', array(__CLASS__, 'rs_process_ajax_to_get_all_user_id'));

            add_action('wp_ajax_rsrefreshexpiredpoints', array(__CLASS__, 'rs_process_ajax_to_get_all_user_id'));

            add_action('wp_ajax_rssplitrefreshexpiredpoints', array(__CLASS__, 'process_ajax_to_refresh_user_points'));

            add_action('fp_action_to_reset_settings_rewardsystem_general', array(__CLASS__, 'rs_function_to_reset_general_tab'));

            add_filter("woocommerce_rewardsystem_general_settings", array(__CLASS__, 'reward_system_add_settings_to_action'));

            add_action('admin_head', array(__CLASS__, 'rs_select_status'));

            if (class_exists('SUMOMemberships')) {
                add_filter('woocommerce_rewardsystem_general_settings', array(__CLASS__, 'add_field_for_membership_plan'));
            }

            if (class_exists('SUMOSubscriptions')) {
                add_filter('woocommerce_rewardsystem_general_settings', array(__CLASS__, 'add_custom_field_to_general_tab'));
            }

            if (class_exists('SUMORewardcoupons')) {
                add_filter('woocommerce_rewardsystem_general_settings', array(__CLASS__, 'setting_for_sumo_coupons'));
            }
        }

        /*
         * @param $settingstab RSGeneralTabSetting 
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_general'] = __('General', 'rewardsystem');
            return $setting_tabs;
        }

        public static function add_field_for_membership_plan($settings) {
            $updated_settings = array();
            $membership_level = sumo_get_membership_levels();
            foreach ($settings as $section) {
                if (isset($section['id']) && '_rs_membership_plan_reward_points' == $section['id'] &&
                        isset($section['type']) && 'sectionend' == $section['type']) {
                      $updated_settings[] = array(
                        'name' => __('Don\'t allow Earn Points when the user hasn\'t purchased any membership plan through SUMO Memberships', 'rewardsystem'),
                        'desc' => __('Don\'t allow Earn Points when the user hasn\'t purchased any membership plan through SUMO Memberships', 'rewardsystem'),
                        'id' => 'rs_enable_restrict_reward_points',
                        'type' => 'checkbox',
                        'newids' => 'rs_enable_restrict_reward_points',
                    );
                    $updated_settings[] = array(
                        'name' => __('Membership Plan based Earning Level', 'rewardsystem'),
                        'desc' => __('Enable this option to modify earning points based on membership plan', 'rewardsystem'),
                        'id' => 'rs_enable_membership_plan_based_reward_points',
                        'std' => 'yes',
                        'default' => 'yes',
                        'type' => 'checkbox',
                        'newids' => 'rs_enable_membership_plan_based_reward_points',
                    );
                    foreach ($membership_level as $key => $value) {
                        $updated_settings[] = array(
                            'name' => __('Reward Points Earning Percentage for ' . $value, 'rewardsystem'),
                            'desc' => __('Please Enter Percentage of Reward Points for ' . $value, 'rewardsystem'),
                            'class' => 'rewardpoints_membership_plan',
                            'id' => 'rs_reward_membership_plan_' . $key,
                            'std' => '100',
                            'default' => '100',
                            'type' => 'text',
                            'newids' => 'rs_reward_membership_plan_' . $key,
                            'desc_tip' => true,
                        );
                    }
                }
                $updated_settings[] = $section;
            }
            return $updated_settings;
        }

        public static function add_custom_field_to_general_tab($settings) {
            $updated_settings = array();
            foreach ($settings as $section) {
                if (isset($section['id']) && '_rs_subscription_settings' == $section['id'] &&
                        isset($section['type']) && 'sectionend' == $section['type']) {
                    $updated_settings[] = array(
                        'name' => __('Don\'t Award Points for Renewal Orders of SUMO Subscriptions', 'rewardsystem'),
                        'desc' => __('If You Enable this option, Reward Points for Renewal orders will not be awarded.', 'rewardsystem'),
                        'id' => 'rs_award_point_for_renewal_order',
                        'std' => 'no',
                        'type' => 'checkbox',
                        'newids' => 'rs_award_point_for_renewal_order',
                    );
                    $updated_settings[] = array(
                        'name' => __('Don\'t Award Referral Product Purchase Points for Renewal Orders of SUMO Subscriptions', 'rewardsystem'),
                        'desc' => __('If You Enable this option, Referral Product Purchase Points for Renewal orders will not be awarded.', 'rewardsystem'),
                        'id' => 'rs_award_referral_point_for_renewal_order',
                        'std' => 'no',
                        'type' => 'checkbox',
                        'newids' => 'rs_award_referral_point_for_renewal_order',
                    );
                }
                $updated_settings[] = $section;
            }

            return $updated_settings;
        }

        public static function setting_for_sumo_coupons($settings) {
            $updated_settings = array();
            foreach ($settings as $section) {
                if (isset($section['id']) && '_rs_coupon_settings' == $section['id'] &&
                        isset($section['type']) && 'sectionend' == $section['type']) {
                    $updated_settings[] = array(
                        'name' => __('Don\'t allow Earn Points when SUMO Coupon is applied', 'rewardsystem'),
                        'desc' => __(' Don\'t allow Earn Points when SUMO Coupon is applied', 'rewardsystem'),
                        'id' => '_rs_not_allow_earn_points_if_sumo_coupon',
                        'css' => 'min-width:550px;',
                        'type' => 'checkbox',
                        'std' => 'no',
                        'default' => 'no',
                        'newids' => '_rs_not_allow_earn_points_if_sumo_coupon',
                    );
                    $updated_settings[] = array(
                        'name' => __('Don\'t allow Redeem when SUMO Coupon is applied', 'rewardsystem'),
                        'desc' => __('Don\'t allow Redeem when SUMO Coupon is applied', 'rewardsystem'),
                        'id' => 'rs_dont_allow_redeem_if_sumo_coupon',
                        'css' => 'min-width:550px;',
                        'type' => 'checkbox',
                        'std' => 'no',
                        'default' => 'no',
                        'newids' => 'rs_dont_allow_redeem_if_sumo_coupon',
                    );
                }
                $updated_settings[] = $section;
            }
            return $updated_settings;
        }

        public static function reward_system_admin_fields() {
            global $woocommerce;
            $list_of_user_roles = fp_rs_get_user_roles();
            $newcombinedarray = fp_rs_get_all_order_status();
            return apply_filters('woocommerce_rewardsystem_general_settings', array(
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('General Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_general_setting',
                ),
                array(
                    'type' => 'rs_refresh_button',
                ),
                array(
                    'name' => __('Plugin Menu Display Name', 'rewardsystem'),
                    'desc' => __('This name will be used to identify SUMO Reward Settings in Wordpress Dashboard', 'rewardsystem'),
                    'id' => 'rs_brand_name',
                    'class' => 'rs_brand_name',
                    'std' => 'SUMO Reward Points',
                    'default' => 'SUMO Reward Points',
                    'desc_tip' => true,
                    'newids' => 'rs_brand_name',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Round Off Type', 'rewardsystem'),
                    'id' => 'rs_round_off_type',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('2 Decimal Places', 'rewardsystem'),
                        '2' => __('Whole Number', 'rewardsystem'),
                    ),
                    'newids' => 'rs_round_off_type',
                    'desc' => __('Reward Points Earned/Redeemed will be displayed based on the Round Off Type Format', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Roundup/Rounddown', 'rewardsystem'),
                    'id' => 'rs_round_up_down',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Floor', 'rewardsystem'),
                        '2' => __('Ceil', 'rewardsystem'),
                    ),
                    'newids' => 'rs_round_up_down',
                ),
                array(
                    'name' => __('Date and Time Format Type', 'rewardsystem'),
                    'id' => 'rs_dispaly_time_format',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_dispaly_time_format',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Default', 'rewardsystem'),
                        '2' => __('WordPress Format', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                    'desc' => __('If Default is selected as Date and Time Format Type, then the date and time should be displayed as d-m-Y h:i:s A. If WordPress Format is selected, then the date and time format in WordPress settings is consider as date and time format', 'rewardsystem'),
                ),
                array('type' => 'sectionend', 'id' => 'rs_general_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Earning Points Conversion Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_point_conversion',
                    'desc' => __('This Conversion settings controls how much points can be earned if Reward Type is set as "By Percentage of Product Price"', 'rewardsystem')
                ),
                array(
                    'type' => 'earning_conversion',
                ),
                array('type' => 'sectionend', 'id' => '_rs_point_conversion'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Redeeming Points Conversion Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_redeem_point_conversion',
                    'desc' => __('This conversion settings controls how much discount can be obtained by redeeming the available Reward Points', 'rewardsystem')
                ),
                array(
                    'type' => 'redeeming_conversion',
                ),
                array('type' => 'sectionend', 'id' => '_rs_redeem_point_conversion'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_subscription_compatible_start',
                ),
                array(
                    'name' => __('SUMO Subscriptions Compatability Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_subscription_settings',
                ),
                array('type' => 'sectionend', 'id' => '_rs_subscription_settings'),
                array(
                    'type' => 'rs_subscription_compatible_end',
                ),
                array(
                    'type' => 'rs_coupon_compatible_start',
                ),
                array(
                    'name' => __('SUMO Coupons Compatability Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_coupon_settings'
                ),
                array('type' => 'sectionend', 'id' => '_rs_coupon_settings'),
                array(
                    'type' => 'rs_coupon_compatible_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Order Status Settings for Earning', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_product_purchase_status_settings',
                ),
                array(
                    'name' => __('Reward Points will be awarded when Order Status reaches', 'rewardsystem'),
                    'desc' => __('Here you can set Reward Points should awarded on which Status of Order', 'rewardsystem'),
                    'id' => 'rs_order_status_control',
                    'std' => array('completed'),
                    'default' => array('completed'),
                    'type' => 'multiselect',
                    'options' => $newcombinedarray,
                    'newids' => 'rs_order_status_control',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => 'rs_product_purchase_status_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Earning Threshold Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_restriction_setting',
                ),
                array(
                    'name' => __('Maximum Threshold for Accumulating Reward Points', 'rewardsystem'),
                    'desc' => __('Enable this option to provide restriction on Accumulating Reward Points without using it', 'rewardsystem'),
                    'id' => 'rs_enable_disable_max_earning_points_for_user',
                    'type' => 'checkbox',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_enable_disable_max_earning_points_for_user',
                ),
                array(
                    'name' => __('Maximum Threshold value in Points', 'rewardsystem'),
                    'desc' => __('Enter a Fixed or Decimal Number greater than 0', 'rewardsystem'),
                    'id' => 'rs_max_earning_points_for_user',
                    'std' => '',
                    'default' => '',
                    'desc_tip' => true,
                    'newids' => 'rs_max_earning_points_for_user',
                    'type' => 'text',
                ),
                array('type' => 'sectionend', 'id' => 'rs_restriction_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Earning Member Level Priority Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_member_level_setting',
                    'desc' => __('This option controls which earning percentage should apply for the user if more than one  earning percentage is applicable for that user', 'rewardsystem')
                ),
                array(
                    'name' => __('Priority Level Selection', 'rewardsystem'),
                    'desc' => __('If more than one type(level) is enabled then use the highest/lowest percentage', 'rewardsystem'),
                    'id' => 'rs_choose_priority_level_selection',
                    'class' => 'rs_choose_priority_level_selection',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'newids' => 'rs_choose_priority_level_selection',
                    'options' => array(
                        '1' => __('Use the level that gives highest percentage', 'rewardsystem'),
                        '2' => __('Use the level that gives lowest percentage', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => 'rs_member_level_setting', 'class' => 'rs_member_level_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Earning Percentage based on User Role', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_user_role_reward_points',
                ),
                array(
                    'name' => __('User Role based Earning Level', 'rewardsystem'),
                    'desc' => __('Enable this option to modify reward points earning percentage based on user role', 'rewardsystem'),
                    'id' => 'rs_enable_user_role_based_reward_points',
                    'std' => 'yes',
                    'default' => 'yes',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_user_role_based_reward_points',
                ),
                array('type' => 'sectionend', 'id' => '_rs_user_role_reward_points'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Earning Percentage based on Earned Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_member_level_earning_points',
                ),
                array(
                    'name' => __('Earned Points based on Earning Level', 'rewardsystem'),
                    'desc' => __('Enable this option to modify earning points based on earned points', 'rewardsystem'),
                    'id' => 'rs_enable_earned_level_based_reward_points',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_earned_level_based_reward_points',
                ),
                array(
                    'name' => __('Earned Points is decided', 'rewardsystem'),
                    'id' => 'rs_select_earn_points_based_on',
                    'std' => '1',
                    'type' => 'select',
                    'newids' => 'rs_select_earn_points_based_on',
                    'options' => array(
                        '1' => __('Based on Total Earned Points', 'rewardsystem'),
                        '2' => __('Based on Current Points', 'rewardsystem')),
                ),
                array(
                    'type' => 'rs_user_role_dynamics',
                ),
                array('type' => 'sectionend', 'id' => '_rs_member_level_earning_points'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Earning Percentage based on Purchase History', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_member_level_earning_points_purchase_history',
                ),
                array(
                    'name' => __('Purchase History based on Earning Level', 'rewardsystem'),
                    'desc' => __('Enable this option to modify earning points based on Purchase History', 'rewardsystem'),
                    'id' => 'rs_enable_user_purchase_history_based_reward_points',
                    'css' => 'min-width:150px;',
                    'std' => 'yes',
                    'default' => 'yes',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_user_purchase_history_based_reward_points',
                ),
                array(
                    'type' => 'rs_user_purchase_history',
                ),
                array('type' => 'sectionend', 'id' => '_rs_member_level_earning_points_purchase_history'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_membership_compatible_start',
                ),
                array(
                    'name' => __('Reward Points Earning Percentage based on Membership Plan', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_membership_plan_reward_points',
                ),
                array('type' => 'sectionend', 'id' => '_rs_membership_plan_reward_points'),
                array(
                    'type' => 'rs_membership_compatible_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Member Level Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_member_level_message_settings',
                ),
                array(
                    'name' => __('Message displayed for Free Products when product is added to cart', 'rewardsystem'),
                    'id' => 'rs_free_product_message_info',
                    'std' => 'You have got this product for reaching [current_level_points] Reward Points',
                    'default' => 'You have got this product for reaching [current_level_points] Reward Points',
                    'type' => 'textarea',
                    'newids' => 'rs_free_product_message_info',
                ),
                array(
                    'name' => __('Free Product Label in Cart', 'rewardsystem'),
                    'id' => 'rs_free_product_msg_caption',
                    'std' => 'Free Product',
                    'default' => 'Free Product',
                    'type' => 'textarea',
                    'newids' => 'rs_free_product_msg_caption',
                ),
                array(
                    'name' => __('Display Free Product Message in Cart and Order Details Page', 'rewardsystem'),
                    'id' => 'rs_remove_msg_from_cart_order',
                    'std' => 'yes',
                    'default' => 'yes',
                    'type' => 'checkbox',
                    'newids' => 'rs_remove_msg_from_cart_order',
                ),
                array(
                    'name' => __('Message for Balance Points to reach next Member Level shortcode', 'rewardsystem'),
                    'id' => 'rs_point_to_reach_next_level',
                    'std' => '[balancepoint] more Points to reach [next_level_name] Earning Level ',
                    'default' => '[balancepoint] more Points to reach [next_level_name] Earning Level',
                    'type' => 'textarea',
                    'newids' => 'rs_point_to_reach_next_level',
                ),
                array('type' => 'sectionend', 'id' => '_rs_member_level_message_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Restriction Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_ban_users',
                ),
                array(
                    'name' => __('Earning Points', 'rewardsystem'),
                    'desc' => __('Restrict Users from Earning Points', 'rewardsystem'),
                    'id' => 'rs_enable_banning_users_earning_points',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_banning_users_earning_points',
                ),
                array(
                    'name' => __('Redeeming Points', 'rewardsystem'),
                    'desc' => __('Restrict Users from Redeeming Points', 'rewardsystem'),
                    'id' => 'rs_enable_banning_users_redeeming_points',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_banning_users_redeeming_points',
                ),
                array(
                    'type' => 'rs_select_user_to_restrict_ban',
                ),
                array(
                    'name' => __('Select the User Role(s)', 'rewardsystem'),
                    'id' => 'rs_banning_user_role',
                    'css' => 'min-width:343px;',
                    'std' => '',
                    'default' => '',
                    'placeholder' => 'Search for a User Role',
                    'type' => 'multiselect',
                    'options' => $list_of_user_roles,
                    'newids' => 'rs_banning_user_role',
                    'desc_tip' => false,
                ),
                array('type' => 'sectionend', 'id' => '_rs_ban_users'),
                array('type' => 'sectionend', 'id' => '_rs_reward_point_general_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shortcodes used in Product Purchase Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_shortcode_in_member_level',
                ),
                array(
                    'type' => 'title',
                    'desc' => '<b>[current_level_points]</b> - To display current level points<br><br>'
                    . '<b>[balancepoint]</b> - Displays the reward points needed to reach next earning level<br><br>'
                    . '<b>[paymentgatewaytitle]</b> - To display payment gateway title<br><br>'
                    . '<b>[next_level_name]</b> - To display next earning level name',
                ),
                array('type' => 'sectionend', 'id' => 'rs_shortcode_in_member_level'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
            ));
        }

        /*
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */

        public static function reward_system_register_admin_settings() {
            woocommerce_admin_fields(RSGeneralTabSetting::reward_system_admin_fields());
        }

        /*
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */

        public static function reward_system_update_settings() {
            woocommerce_update_options(RSGeneralTabSetting::reward_system_admin_fields());
            if (isset($_POST['rs_banned_users_list'])) {
                update_option('rs_banned_users_list', $_POST['rs_banned_users_list']);
            } else {
                update_option('rs_banned_users_list', '');
            }
            if (isset($_POST['rs_earn_point']) && $_POST['rs_earn_point'] != ('' || 0)) {
                update_option('rs_earn_point', $_POST['rs_earn_point']);
            } else {
                update_option('rs_earn_point', '1');
            }
            if (isset($_POST['rs_earn_point_value']) && $_POST['rs_earn_point_value'] != ('' || 0)) {
                update_option('rs_earn_point_value', $_POST['rs_earn_point_value']);
            } else {
                update_option('rs_earn_point_value', '1');
            }
            if (isset($_POST['rs_redeem_point']) && $_POST['rs_redeem_point'] != ('' || 0)) {
                update_option('rs_redeem_point', $_POST['rs_redeem_point']);
            } else {
                update_option('rs_redeem_point', '1');
            }
            if (isset($_POST['rs_redeem_point_value']) && $_POST['rs_redeem_point_value'] != ('' || 0)) {
                update_option('rs_redeem_point_value', $_POST['rs_redeem_point_value']);
            } else {
                update_option('rs_redeem_point_value', '1');
            }
            if (isset($_POST['rs_redeem_point_value'])) {
                update_option('rewards_dynamic_rule', $_POST['rewards_dynamic_rule']);
            } else {
                update_option('rewards_dynamic_rule', '');
            }
            if (isset($_POST['rewards_dynamic_rule_purchase_history'])) {
                update_option('rewards_dynamic_rule_purchase_history', $_POST['rewards_dynamic_rule_purchase_history']);
            } else {
                update_option('rewards_dynamic_rule_purchase_history', '');
            }
        }

        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSGeneralTabSetting::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        /*
         * Function for choosen in Select user role for banning
         */

        public static function rs_chosen_for_general_tab() {
            global $woocommerce;
            if (isset($_GET['page'])) {
                if ($_GET['page'] == 'rewardsystem_callback') {
                    if ((float) $woocommerce->version > (float) ('2.2.0')) {
                        echo rs_common_select_function('#rs_banning_user_role');
                        echo rs_common_select_function('#rs_order_status_control');
                    } else {
                        echo rs_common_chosen_function('#rs_banning_user_role');
                        echo rs_common_chosen_function('#rs_order_status_control');
                    }
                }
            }
        }

        /*
         * Function to Select user for banning
         */

        public static function rs_select_user_to_ban() {
            ?>
            <style type="text/css">
                .chosen-container-single {
                    position:absolute;
                }
            </style>
            <?php
            $field_id = "rs_banned_users_list";
            $field_label = "Select the User(s)";
            $getuser = get_option('rs_banned_users_list');
            echo rs_function_to_add_field_for_user_select($field_id, $field_label, $getuser);
        }

        public static function get_woocommerce_upload_field() {
            if (isset($_REQUEST['rs_image_url_upload'])) {
                update_option('rs_image_url_upload', $_POST['rs_image_url_upload']);
            }
        }

        public static function reward_system_earning_points_conversion() {
            ?>
            <tr valign="top">
                <td class="forminp forminp-text">
                    <input type="number" step="any" min="0" value="<?php echo get_option('rs_earn_point'); ?>" style="max-width:50px;" id="rs_earn_point" name="rs_earn_point"> <?php _e('Earning Point(s)', 'rewardsystem'); ?>
                    &nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;
                    <?php echo get_woocommerce_currency_symbol(); ?> <input type="number" step="any" min="0" value="<?php echo get_option('rs_earn_point_value'); ?>" style="max-width:50px;" id="rs_earn_point_value" name="rs_earn_point_value">
                </td>
            </tr>

            <?php
        }

        public static function reward_system_redeeming_points_conversion() {
            ?>
            <tr valign="top">
                <td class="forminp forminp-text">
                    <input type="number" step="any" min="0" value="<?php echo get_option('rs_redeem_point'); ?>" style="max-width:50px;" id="rs_redeem_point" name="rs_redeem_point"> <?php _e('Redeeming Point(s)', 'rewardsystem'); ?>
                    &nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;
                    <?php echo get_woocommerce_currency_symbol(); ?> 	<input type="number" step="any" min="0" value="<?php echo get_option('rs_redeem_point_value'); ?>" style="max-width:50px;" id="rs_redeem_point_value" name="rs_redeem_point_value"></td>
            </td>
            </tr>
            <?php
        }

        public static function refresh_button_for_expired() {
            ?>
            <tr valign="top">
                <th>
                    <label for="rs_refresh_button" style="font-size:14px;"><?php _e('Update Expired Points for All Users', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <input type="button" class="rs_refresh_button" value="<?php _e('Update Expired Points', 'rewardsystem'); ?>"  id="rs_refresh_button" name="rs_refresh_button"/>
                    <img class="gif_rs_refresh_button" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/admin/images/update.gif" style="width:32px;height:32px;position:absolute"/>
                </td>
            </tr>
            <?php
        }

        public static function rs_select_status() {
            global $woocommerce;
            if (isset($_GET['tab'])) {
                if ($_GET['tab'] == 'rewardsystem_general') {
                    if (get_option('rs_enable_earned_level_based_reward_points') == 'yes') {
                        ?>
                        <style>
                            .rs_section_wrapper .rs_sample{
                                border:1px solid #ccc;
                            }
                        </style>

                    <?php } else { ?>
                        <style>
                            .rs_section_wrapper .rs_sample{
                                border:none;
                            }
                        </style>

                        <?php
                    }
                }
            }
        }

        public static function rs_send_ajax_to_refresh_expired_points() {
            if (isset($_GET['page'])) {
                if ($_GET['page'] == 'rewardsystem_callback') {
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery('.gif_rs_refresh_button').css('display', 'none');
                            jQuery('.rs_refresh_button').click(function () {
                                jQuery('.gif_rs_refresh_button').css('display', 'inline-block');
                                jQuery(this).attr('data-clicked', '1');
                                var dataclicked = jQuery(this).attr('data-clicked');
                                var dataparam = ({
                                    action: 'rsrefreshexpiredpoints',
                                    proceedanyway: dataclicked,
                                });
                                function getDataforDate(id) {
                                    return jQuery.ajax({
                                        type: 'POST',
                                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                        data: ({
                                            action: 'rssplitrefreshexpiredpoints',
                                            ids: id,
                                            proceedanyway: dataclicked,
                                        }),
                                        success: function (response) {
                                            console.log(response);
                                        },
                                        dataType: 'json',
                                        async: false
                                    });
                                }
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                        function (response) {
                                            console.log(response);
                                            if (response != 'success') {
                                                var j = 1;
                                                var i, j, temparray, chunk = 10;
                                                for (i = 0, j = response.length; i < j; i += chunk) {
                                                    temparray = response.slice(i, i + chunk);
                                                    getDataforDate(temparray);
                                                }
                                                location.reload();
                                                console.log('Ajax Done Successfully');

                                            }
                                        }, 'json');
                                return false;
                            });
                        });
                    </script>
                    <?php
                }
            }
        }

        public static function rs_process_ajax_to_get_all_user_id() {
            if (isset($_POST['proceedanyway'])) {
                if ($_POST['proceedanyway'] == '1') {
                    $args = array(
                        'fields' => 'ID',
                    );
                    $get_users = get_users($args);

                    echo json_encode($get_users);
                }
            }
            exit();
        }

        public static function process_ajax_to_refresh_user_points() {
            if (isset($_POST['ids'])) {
                $userids = $_POST['ids'];
                foreach ($userids as $userid) {
                    RSPointExpiry::check_if_expiry_on_admin($userid);
                }
            }
            exit();
        }

        public static function rs_function_to_reset_general_tab() {
            $settings = RSGeneralTabSetting::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
        }

        /*
         * Function to add table for Earning Level in Member Level Tab
         */

        public static function reward_system_add_table_to_action() {
            global $woocommerce;
            wp_nonce_field(plugin_basename(__FILE__), 'rsdynamicrulecreation');
            ?>
            <style type="text/css">
                .rs_add_free_product_user_levels{
                    width:100%;
                }
                .chosen-container-active{
                    position: absolute;
                }
            </style>            
            <table class="widefat fixed rs_sample" cellspacing="0">
                <thead>
                    <tr class="rsdynamicrulecreation">
                        <th class="manage-column column-columnname" scope="col"><?php _e('Level Name', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points Earning Percentage', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Free Product(s)', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Level', 'rewardsystem'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="rsdynamicrulecreation">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="manage-column column-columnname num" scope="col"> <span class="add button-primary"><?php _e('Add New Level', 'rewardsystem'); ?></span></td>
                    </tr>
                    <tr class="rsdynamicrulecreation">
                        <th class="manage-column column-columnname" scope="col"><?php _e('Level Name', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points Earning Percentage', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Free Product(s)', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Level', 'rewardsystem'); ?></th>

                    </tr>
                </tfoot>
                <tbody id="here">
                    <?php
                    $rewards_dynamic_rulerule = get_option('rewards_dynamic_rule');
                    if (!empty($rewards_dynamic_rulerule)) {
                        if (is_array($rewards_dynamic_rulerule)) {
                            foreach ($rewards_dynamic_rulerule as $i => $rewards_dynamic_rule) {
                                ?>
                                <tr class="rsdynamicrulecreation">
                                    <td class="column-columnname">
                                        <input type="text" name="rewards_dynamic_rule[<?php echo $i; ?>][name]" class="short" value="<?php echo $rewards_dynamic_rule['name']; ?>"/>
                                    </td>
                                    <td class="column-columnname">
                                        <input type="number" step="any" min="0" name="rewards_dynamic_rule[<?php echo $i; ?>][rewardpoints]" id="rewards_dynamic_rewardpoints<?php echo $i; ?>" class="short" value="<?php echo $rewards_dynamic_rule['rewardpoints']; ?>"/>
                                    </td>
                                    <td class="column-columnname">
                                        <input type ="number" name="rewards_dynamic_rule[<?php echo $i; ?>][percentage]" id="rewards_dynamic_rule_percentage<?php echo $i; ?>" class="short test" value="<?php echo $rewards_dynamic_rule['percentage']; ?>"/>
                                    </td>
                                    <td class="column-columnname">
                                        <?php
                                        if ((float) $woocommerce->version > (float) ('2.2.0')) {
                                            if ($woocommerce->version >= (float) ('3.0.0')) {
                                                ?>                                                    
                                                <select class="wc-product-search" multiple="multiple" style="width: 100%;" id="rewards_dynamic_rule[<?php echo $i; ?>]['product_list'][]" name="rewards_dynamic_rule[<?php echo $i; ?>][product_list][]" data-placeholder="<?php _e('Search for a product', 'woocommerce'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true">
                                                    <?php
                                                    $json_ids = array();
                                                    if (isset($rewards_dynamic_rule['product_list']) && $rewards_dynamic_rule['product_list'] != "") {
                                                        $list_of_produts = $rewards_dynamic_rule['product_list'];
                                                        if (is_array($list_of_produts) && !empty($list_of_produts)) {
                                                            $product_ids = $list_of_produts;
                                                        } else {
                                                            $product_ids = array_filter(array_map('absint', (array) explode(',', $list_of_produts)));
                                                        }
                                                        foreach ($product_ids as $product_id) {
                                                            $product = rs_get_product_object($product_id);
                                                            if (is_object($product)) {
                                                                $json_ids = wp_kses_post($product->get_formatted_name());
                                                                ?> <option value="<?php echo $product_id; ?>" selected="selected"><?php echo esc_html($json_ids); ?></option><?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                            } else {
                                                ?>
                                                <input type="hidden" class="wc-product-search" style="width: 100%;" id="rewards_dynamic_rule[<?php echo $i; ?>][product_list][]" name="rewards_dynamic_rule[<?php echo $i; ?>][product_list][]" data-placeholder="<?php _e('Search for a product', 'woocommerce'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
                                $json_ids = array();
                                if ($rewards_dynamic_rule['product_list'] != "") {
                                    $list_of_produts = $rewards_dynamic_rule['product_list'];
                                    if (is_array($list_of_produts) && !empty($list_of_produts)) {
                                        $product_ids = $list_of_produts;
                                    } else {
                                        $product_ids = array_filter(array_map('absint', (array) explode(',', $list_of_produts)));
                                    }
                                    foreach ($product_ids as $product_id) {
                                        $product = rs_get_product_object($product_id);
                                        if (is_object($product)) {
                                            $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                                        }
                                    } echo esc_attr(json_encode($json_ids));
                                }
                                                ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" /><?php
                                                   }
                                               } else {
                                                   echo rs_common_ajax_function_to_select_products('rs_add_free_product_user_levels');
                                                   ?>
                                            <!-- For Old Version -->
                                            <select multiple name="rewards_dynamic_rule[<?php echo $i; ?>][product_list][]" class="rs_add_free_product_user_levels">
                                                <?php
                                                if ($rewards_dynamic_rule['product_list'] != "") {
                                                    $list_of_produts = $rewards_dynamic_rule['product_list'];
                                                    if (is_array($list_of_produts) && !empty($list_of_produts)) {
                                                        $product_ids = $list_of_produts;
                                                    } else {
                                                        $product_ids = array_filter(array_map('absint', (array) explode(',', $list_of_produts)));
                                                    }
                                                    foreach ($product_ids as $rs_free_id) {
                                                        echo '<option value="' . $rs_free_id . '" ';
                                                        selected(1, 1);
                                                        echo '>' . ' #' . $rs_free_id . ' &ndash; ' . get_the_title($rs_free_id);
                                                        ?>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value=""></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td class="column-columnname num">
                                        <span class="remove button-secondary"><?php _e('Remove Level', 'rewardsystem'); ?></span>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery(".add").on('click', function () {
                        var countrewards_dynamic_rule = Math.round(new Date().getTime() + (Math.random() * 100));
            <?php
            if ((float) $woocommerce->version > (float) ('2.2.0')) {
                if ($woocommerce->version >= (float) ('3.0.0')) {
                    ?>
                                jQuery('#here').append('<tr><td><input type="text" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][name]" class="short" value=""/></td>\n\
                                                                                                                                                                                                                            \n\<td><input type="number" step="any" min="0" id="rewards_dynamic_ruleamount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][rewardpoints]" class="short" value=""/></td>\n\
                                                                                                                                                                                                                            \n\\n\
                                                                                                                                                                                                                            <td><input type ="number" id="rewards_dynamic_rule_claimcount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][percentage]" class="short test"  value=""/></td>\n\\n\
                                                                                                                                                                                                                            \n\<td>\n\
                                                                                                                                                                                                                            \n\
                                                                                                                                                                                                                            <select style="width:100%;" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][product_list][]" class="wc-product-search" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="true"></select></td>n\
                                                                                                                                                                                                                            <td class="num"><span class="remove button-secondary">Remove Level</span></td></tr><hr>');
                                jQuery('body').trigger('wc-enhanced-select-init');
                <?php } else {
                    ?>
                                jQuery('#here').append('<tr><td><input type="text" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][name]" class="short" value=""/></td>\n\
                                                                                                                                                                                                                            \n\<td><input type="number" step="any" min="0" id="rewards_dynamic_ruleamount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][rewardpoints]" class="short" value=""/></td>\n\
                                                                                                                                                                                                                            \n\\n\
                                                                                                                                                                                                                            <td><input type ="number" id="rewards_dynamic_rule_claimcount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][percentage]" class="short test"  value=""/></td>\n\\n\
                                                                                                                                                                                                                            \n\<td>\n\
                                                                                                                                                                                                                            \n\
                                                                                                                                                                                                                            <input type=hidden style="width:100%;" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][product_list][]" class="wc-product-search" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="true"/></td>n\
                                                                                                                                                                                                                            <td class="num"><span class="remove button-secondary">Remove Level</span></td></tr><hr>');
                                jQuery('body').trigger('wc-enhanced-select-init');
                <?php } ?>
            <?php } else { ?>
                            jQuery('#here').append('<tr><td><input type="text" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][name]" class="short" value=""/></td>\n\
                                                                                                                                                                                                                        \n\<td><input type="number" step="any" min="0" id="rewards_dynamic_ruleamount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][rewardpoints]" class="short" value=""/></td>\n\
                                                                                                                                                                                                                        \n\\n\
                                                                                                                                                                                                                        <td><input type ="number" id="rewards_dynamic_rule_claimcount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][percentage]" class="short test"  value=""/></td>\n\\n\
                                                                                                                                                                                                                        \n\\n\
                                                                                                                                                                                                                        \n\<td><select multiple name="rewards_dynamic_rule[' + countrewards_dynamic_rule + '][product_list][]" class="rs_add_free_product_user_levels"><option value=""></option></select></td>n\
                                                                                                                                                                                                                        <td class="num"><span class="remove button-secondary">Remove Level</span></td></tr><hr>');

            <?php } if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery(function () {
                                jQuery("select.rs_add_free_product_user_levels").ajaxChosen({
                                    method: 'GET',
                                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                    dataType: 'json',
                                    afterTypeDelay: 100,
                                    data: {
                                        action: 'woocommerce_json_search_products_and_variations',
                                        security: '<?php echo wp_create_nonce("search-products"); ?>'
                                    }
                                }, function (data) {
                                    var terms = {};

                                    jQuery.each(data, function (i, val) {
                                        terms[i] = val;
                                    });
                                    return terms;
                                });
                            });
            <?php } ?>
                        return false;
                    });
                    jQuery(document).on('click', '.remove', function () {
                        jQuery(this).parent().parent().remove();
                    });
                    jQuery('#rs_enable_user_role_based_reward_points').addClass('rs_enable_user_role_based_reward_points');
                    jQuery('#rs_enable_earned_level_based_reward_points').addClass('rs_enable_user_role_based_reward_points');
                });
            </script>
            <?php
        }

        public static function rs_function_to_add_rule_based_on_user_purchase_history() {
            global $woocommerce;
            wp_nonce_field(plugin_basename(__FILE__), 'rsdynamicrulecreationsforuserpurchasehistory');
            ?>
            <table class="widefat fixed rsdynamicrulecreationsforuserpurchasehistory" cellspacing="0">
                <thead>
                    <tr>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Level Name', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Type', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Value', 'rewardsystem'); ?></th>      
                        <th class="manage-column column-columnname" scope="col"><?php _e('Percentage', 'rewardsystem'); ?></th>   
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Level', 'rewardsystem'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="manage-column column-columnname num" scope="col"> <span class="rs_add_new_level button-primary"><?php _e('Add New Level', 'rewardsystem'); ?></span></td>
                    </tr>
                    <tr>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Level Name', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Type', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Value', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Percentage', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Level', 'rewardsystem'); ?></th>

                    </tr>
                </tfoot>
                <tbody id="rs_table_data_for_user_purchase_history">
                    <?php
                    $rewards_dynamic_rulerule = get_option('rewards_dynamic_rule_purchase_history');
                    if (!empty($rewards_dynamic_rulerule)) {
                        if (is_array($rewards_dynamic_rulerule)) {
                            foreach ($rewards_dynamic_rulerule as $i => $rewards_dynamic_rule) {
                                ?>
                                <tr>
                                    <td class="column-columnname">
                                        <p class="form-field">
                                            <input type="text" name="rewards_dynamic_rule_purchase_history[<?php echo $i; ?>][name]" class="short" value="<?php echo $rewards_dynamic_rule['name']; ?>"/>
                                        </p>
                                    </td>
                                    <td class="column-columnname">
                                        <p class="form-field">
                                            <select style="width:225px !important;" name="rewards_dynamic_rule_purchase_history[<?php echo $i; ?>][type]" id="rewards_dynamic_rule_purchase_history<?php echo $i; ?>" class="short"  />
                            <option value="1" <?php selected('1', $rewards_dynamic_rule['type']); ?>><?php _e('Number of Successful Order(s)', 'rewardsystem'); ?></option>
                            <option value="2" <?php selected('2', $rewards_dynamic_rule['type']); ?>><?php _e('Total Amount Spent in Site', 'rewardsystem'); ?></option>

                        </select> 
                        </p>
                        </td>
                        <td class="column-columnname">
                            <p class="form-field">
                                <input type ="number" name="rewards_dynamic_rule_purchase_history[<?php echo $i; ?>][value]" id="rewards_dynamic_rule_purchase_historyvalue<?php echo $i; ?>" class="short test" value="<?php echo $rewards_dynamic_rule['value']; ?>"/>
                            </p>
                        </td>
                        <td class="column-columnname">
                            <p class="form-field">
                                <input type ="number" name="rewards_dynamic_rule_purchase_history[<?php echo $i; ?>][percentage]" id="rewards_dynamic_rule_purchase_historypercentage<?php echo $i; ?>" class="short test" value="<?php echo $rewards_dynamic_rule['percentage']; ?>"/>
                            </p>
                        </td>

                        <td class="column-columnname num">
                            <span class="remove button-secondary"><?php _e('Remove Level', 'rewardsystem'); ?></span>
                        </td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
            </tbody>
            </table>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery(".rs_add_new_level").on('click', function () {
                        var countrewards_dynamic_rule = Math.round(new Date().getTime() + (Math.random() * 100));
            <?php ?>
                        jQuery('#rs_table_data_for_user_purchase_history').append('<tr><td><p class="form-field"><input type="text" name="rewards_dynamic_rule_purchase_history[' + countrewards_dynamic_rule + '][name]" class="short" value=""/></p></td>\n\
            <td><p class="form-field"><select style="width:225px !important;" id="rewards_dynamic_rule_purchase_history' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_purchase_history[' + countrewards_dynamic_rule + '][type]" class="short">\n\
            <option value="1"><?php _e('Number of Successful Order(s)', 'rewardsystem'); ?></option>\n\
            <option value="2"><?php _e('Total Amount Spent in Site', 'rewardsystem'); ?></select></p></td>\n\
            <td><p class="form-field"><input type ="number" id="rewards_dynamic_rule_purchase_history' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_purchase_history[' + countrewards_dynamic_rule + '][value]" class="short test"  value=""/></p></td>\n\
             <td><p class="form-field"><input type ="number" id="rewards_dynamic_rule_purchase_history' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_purchase_history[' + countrewards_dynamic_rule + '][percentage]" class="short"  value=""/></p></td>\n\
            <td class="num"><span class="remove button-secondary"><?php _e('Remove Rule', 'rewardsystem'); ?></span></td></tr><hr>');
                        return false;
                    });
                    jQuery(document).on('click', '.remove', function () {
                        jQuery(this).parent().parent().remove();
                    });                    
                });
            </script>
            <?php
        }

        /*
         * Function to add settings for Member Level in Member Level Tab
         */

        public static function reward_system_add_settings_to_action($settings) {
            global $wp_roles;
            $updated_settings = array();
            $mainvariable = array();
            global $woocommerce;
            foreach ($settings as $section) {
                if (isset($section['id']) && '_rs_user_role_reward_points' == $section['id'] &&
                        isset($section['type']) && 'sectionend' == $section['type']) {
                    foreach ($wp_roles->role_names as $value => $key) {
                        $updated_settings[] = array(
                            'name' => __('Reward Points Earning Percentage for ' . $key . ' User Role', 'rewardsystem'),
                            'desc' => __('Earning Percentage of Reward Points for ' . $key . 'user role', 'rewardsystem'),
                            'class' => 'rewardpoints_userrole',
                            'id' => 'rs_reward_user_role_' . $value,
                            'std' => '100',
                            'type' => 'text',
                            'newids' => 'rs_reward_user_role_' . $value,
                            'desc_tip' => true,
                        );
                    }

                    $updated_settings[] = array(
                        'type' => 'sectionend', 'id' => '_rs_user_role_reward_points',
                    );
                }

                $updated_settings[] = $section;
            }

            return $updated_settings;
        }

    }

    RSGeneralTabSetting::init();
}