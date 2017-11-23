<?php
/*
 * Reward Points for Action Tab Settings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSRewardPointsForAction')) {

    class RSRewardPointsForAction {

        public static function init() {
            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_reward_points_for_action', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_modules_rewardsystem_reward_points_for_action', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system

            if (class_exists('bbPress')) {
                add_filter('woocommerce_rewardsystem_reward_points_for_action_settings', array(__CLASS__, 'add_field_for_create_topic'));
            }

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'));

            add_action('admin_head', array(__CLASS__, 'rs_validation_for_input_field_in_reward_points_tab'));

            add_filter('woocommerce_rewardsystem_reward_points_for_action_settings', array(__CLASS__, 'reward_system_add_settings_to_action'));

            add_action('publish_post', array(__CLASS__, 'on_post_publish'), 10, 2);

            add_action('woocommerce_admin_field_rs_coupon_usage_points_dynamics', array(__CLASS__, 'reward_add_coupon_usage_points_to_action'));

            add_action('fp_action_to_reset_module_settings_rewardsystem_reward_points_for_action', array(__CLASS__, 'rs_function_to_reset_action_tab'));

            add_action('woocommerce_admin_field_rs_enable_disable_reward_action_module', array(__CLASS__, 'rs_function_to_enable_disable_reward_action_module'));
        }

        public static function add_field_for_create_topic($settings) {
            $updated_settings = array();
            foreach ($settings as $section) {
                $updated_settings[] = $section;
                if (isset($section['id']) && 'rs_page_comment_reward_points_setting' == $section['id'] &&
                        isset($section['type']) && 'sectionend' == $section['type']) {
                    $updated_settings[] = array(
                        'name' => __('bbPress Reward Points', 'rewardsystem'),
                        'type' => 'title',
                        'id' => '_rs_reward_point_for_topic'
                    );
                    $updated_settings[] = array(
                        'name' => __('Topic Creation Reward Points', 'rewardsystem'),
                        'id' => 'rs_enable_reward_points_for_create_topic',
                        'std' => 'no',
                        'default' => 'no',
                        'type' => 'checkbox',
                        'newids' => 'rs_enable_reward_points_for_create_topic',
                    );

                    $updated_settings[] = array(
                        'name' => __('Topic Creation Reward Points', 'rewardsystem'),
                        'id' => 'rs_reward_points_for_creatic_topic',
                        'std' => '',
                        'default' => '',
                        'type' => 'text',
                        'newids' => 'rs_reward_points_for_creatic_topic',
                    );
                    $updated_settings[] = array(
                        'name' => __('Topic Reply Reward Points', 'rewardsystem'),
                        'id' => 'rs_enable_reward_points_for_reply_topic',
                        'std' => 'no',
                        'default' => 'no',
                        'type' => 'checkbox',
                        'newids' => 'rs_enable_reward_points_for_reply_topic',
                    );

                    $updated_settings[] = array(
                        'name' => __('Topic Reply Reward Points', 'rewardsystem'),
                        'id' => 'rs_reward_points_for_reply_topic',
                        'std' => '',
                        'default' => '',
                        'type' => 'text',
                        'newids' => 'rs_reward_points_for_reply_topic',
                    );

                    $updated_settings[] = array(
                        'type' => 'sectionend',
                        'id' => '_rs_reward_point_for_topic'
                    );
                }
            }
            return $updated_settings;
        }

        /*
         * Function to Define Name of the tab.
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_reward_points_for_action'] = __('Action Reward Points Module', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function for label Settings in Reward Points For Action.
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;
            return apply_filters('woocommerce_rewardsystem_reward_points_for_action_settings', array(
                array(
                    'type' => 'rs_modulecheck_start',
                ),
                array(
                    'name' => __('Action Reward Points Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_activate_reward_action_module'
                ),
                array(
                    'type' => 'rs_enable_disable_reward_action_module',
                ),
                array('type' => 'sectionend', 'id' => '_rs_activate_reward_action_module'),
                array(
                    'type' => 'rs_modulecheck_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Signup Reward Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_signup_reward_points_setting',
                ),
                array(
                    'name' => __('Account Signup Reward Points is Awarded for', 'rewardsystem'),
                    'desc' => __('This option controls whether account signup reward points should be awarded for any registered user/users registered through referral links', 'rewardsystem'),
                    'id' => 'rs_select_account_signup_points_award',
                    'type' => 'select',
                    'newids' => 'rs_select_account_signup_points_award',
                    'std' => '1',
                    'default' => '1',
                    'options' => array(
                        '1' => __('All Users', 'rewardsystem'),
                        '2' => __('Referred Users', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Award Account Signup Reward Points only after First Purchase', 'rewardsystem'),
                    'desc' => __('Enabling this option will award account signup reward points only after first purchase', 'rewardsystem'),
                    'id' => 'rs_reward_signup_after_first_purchase',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_reward_signup_after_first_purchase',
                ),
                array(
                    'name' => __('Prevent Product Purchase Reward Points for First Purchase', 'rewardsystem'),
                    'desc' => __('Enable this option to prevent product purchase reward points for first purchase', 'rewardsystem'),
                    'id' => 'rs_signup_points_with_purchase_points',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_signup_points_with_purchase_points',
                ),
                array(
                    'name' => __('Account Signup Reward Points', 'rewardsystem'),
                    'id' => 'rs_reward_signup',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_reward_signup',
                ),
                array('type' => 'sectionend', 'id' => 'rs_signup_reward_points_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Review Reward Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_product_review_reward_points_setting',
                ),
                array(
                    'name' => __('Status on which Product Review Reward Points should be awarded', 'rewardsystem'),
                    'id' => 'rs_review_reward_status',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'options' => array('1' => 'Approve', '2' => 'Unapprove'),
                    'newids' => 'rs_review_reward_status',
                ),
                array(
                    'name' => __('Product Review Reward Points', 'rewardsystem'),
                    'id' => 'rs_reward_product_review',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_reward_product_review',
                ),
                array(
                    'name' => __('Restrict Product Review Reward Points to One Review per Product per User', 'rewardsystem'),
                    'desc' => __('Enabling this option will restrict product review reward points will be awarded only for one product per user', 'rewardsystem'),
                    'id' => 'rs_restrict_reward_product_review',
                    'type' => 'checkbox',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_restrict_reward_product_review',
                ),
                array(
                    'name' => __('Product Review Reward Points should be awarded only for Purchased User', 'rewardsystem'),
                    'desc' => __('Enabling this option will award product review reward points only for reviews made by purchased user', 'rewardsystem'),
                    'id' => 'rs_reward_for_comment_product_review',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_reward_for_comment_product_review',
                ),
                array('type' => 'sectionend', 'id' => 'rs_product_review_reward_points_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Blog Post Reward Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_blog_post_reward_points_setting',
                ),
                array(
                    'name' => __('Blog Post Creation Reward Points', 'rewardsystem'),
                    'desc' => __('By enabling this option you can award reward points for blog post creation', 'rewardsystem'),
                    'id' => 'rs_reward_for_Creating_Post',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_reward_for_Creating_Post',
                ),
                array(
                    'name' => __('Blog Post Creation Reward Points', 'rewardsystem'),
                    'id' => 'rs_reward_post',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_reward_post',
                ),
                array(
                    'name' => __('Blog Post Comment Reward Points', 'rewardsystem'),
                    'desc' => __('By Enabling this option you can award reward points for blog post comment', 'rewardsystem'),
                    'id' => 'rs_reward_for_comment_Post',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_reward_for_comment_Post',
                ),
                array(
                    'name' => __('Blog Post Comment Reward Points', 'rewardsystem'),
                    'id' => 'rs_reward_post_review',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_reward_post_review',
                ),
                array('type' => 'sectionend', 'id' => 'rs_blog_post_reward_points_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Creation Reward Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_product_creation_reward_points_setting',
                ),
                array(
                    'name' => __('Product Creation Reward Points', 'rewardsystem'),
                    'desc' => __('By Enabling this option, you can award reward points for creating products', 'rewardsystem'),
                    'id' => 'rs_reward_for_enable_product_create',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_reward_for_enable_product_create',
                ),
                array(
                    'name' => __('Product Creation Reward Points', 'rewardsystem'),
                    'id' => 'rs_reward_Product_create',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_reward_Product_create',
                ),
                array('type' => 'sectionend', 'id' => 'rs_product_creation_reward_points_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Page Comment Reward Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_page_comment_reward_points_setting',
                ),
                array(
                    'name' => __('Page Comment Reward Points', 'rewardsystem'),
                    'desc' => __('By Enabling this option, you can award reward points for commenting on pages', 'rewardsystem'),
                    'id' => 'rs_reward_for_comment_Page',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_reward_for_comment_Page',
                ),
                array(
                    'name' => __('Page Comment Reward Points', 'rewardsystem'),
                    'id' => 'rs_reward_page_review',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_reward_page_review',
                ),
                array('type' => 'sectionend', 'id' => 'rs_page_comment_reward_points_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Daily Login Reward Points Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_point_action'
                ),
                array(
                    'name' => __('Daily Login Reward Points', 'rewardsystem'),
                    'desc' => __('By Enabling this option, you can award reward points for daily login', 'rewardsystem'),
                    'id' => 'rs_enable_reward_points_for_login',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_reward_points_for_login',
                ),
                array(
                    'name' => __('Daily Login Reward Points', 'rewardsystem'),
                    'id' => 'rs_reward_points_for_login',
                    'std' => '10',
                    'default' => '10',
                    'type' => 'text',
                    'newids' => 'rs_reward_points_for_login',
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_point_action'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Payment Gateway Reward Points Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_point_for_payment_gateway',
                    'desc' => __('You can reward points for using specific payment gateway in order', 'rewardsystem')
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_point_for_payment_gateway'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('WooCommerce Coupon Usage Reward Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_point_coupon_message_settings'
                ),
                array(
                    'name' => __('When different Points is associated with the same Coupon Code then', 'rewardsystem'),
                    'desc' => __('This option controls what points should be awarded to user when different points are associated with the same coupon code', 'rewardsystem'),
                    'id' => 'rs_choose_priority_level_selection_coupon_points',
                    'class' => 'rs_choose_priority_level_selection_coupon_points',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'newids' => 'rs_choose_priority_level_selection_coupon_points',
                    'options' => array(
                        '1' => __('Rule with the highest number of points will be awarded', 'rewardsystem'),
                        '2' => __('Rule with the lowest number of points will be awarded', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Message displayed on cart page when Coupon Reward Points is Earned', 'rewardsystem'),
                    'id' => 'rs_coupon_applied_reward_success',
                    'std' => 'You have received [coupon_rewardpoints] Points for using the coupon [coupon_name]',
                    'type' => 'textarea',
                    'newids' => 'rs_coupon_applied_reward_success',
                    'default' => 'You have received [coupon_rewardpoints] Points for using the coupon [coupon_name]',
                ),
                array(
                    'type' => 'rs_coupon_usage_points_dynamics',
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_point_coupon_message_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shortcode used in Coupon Reward Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_shortcode_for_coupon'
                ),
                array(
                    'type' => 'title',
                    'desc' => '<b>[coupon_rewardpoints]</b> - To display points earned for using coupon code<br><br>'
                    . '<b>[coupon_name]</b> - To display coupon name<br><br>',
                ),
                array('type' => 'sectionend', 'id' => 'rs_shortcode_for_coupon'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {

            woocommerce_admin_fields(RSRewardPointsForAction::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSRewardPointsForAction::reward_system_admin_fields());
            $rewards_dynamic_rulerule_couponpoints = array_values($_POST['rewards_dynamic_rule_coupon_usage']);
            update_option('rewards_dynamic_rule_couponpoints', $rewards_dynamic_rulerule_couponpoints);
            if (isset($_POST['rs_reward_action_module_checkbox'])) {
                update_option('rs_reward_action_activated', $_POST['rs_reward_action_module_checkbox']);
            } else {
                update_option('rs_reward_action_activated', 'no');
            }
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSRewardPointsForAction::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        public static function rs_function_to_enable_disable_reward_action_module() {
            $get_option_value = get_option('rs_reward_action_activated');
            $name_of_checkbox = 'rs_reward_action_module_checkbox';
            RSModulesTab::rs_common_function_to_get_checkbox($get_option_value, $name_of_checkbox);
        }

        public static function reward_system_add_settings_to_action($settings) {
            $updated_settings = array();
            $mainvariable = array();
            global $woocommerce;
            foreach ($settings as $section) {
                if (isset($section['id']) && '_rs_reward_point_for_payment_gateway' == $section['id'] &&
                        isset($section['type']) && 'sectionend' == $section['type']) {
                    if (function_exists('WC')) {
                        foreach (WC()->payment_gateways->payment_gateways() as $gateway) {
                            $updated_settings[] = array(
                                'name' => __($gateway->title . ' Reward Type', 'rewardsystem'),
                                'desc' => __('Please Select Reward Type for ' . $gateway->title, 'rewardsystem'),
                                'id' => 'rs_reward_type_for_payment_gateways_' . $gateway->id,
                                'std' => '',
                                'default' => '',
                                'type' => 'select',
                                'newids' => 'rs_reward_type_for_payment_gateways_' . $gateway->id,
                                'desc_tip' => true,
                                'options' => array(
                                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                                    '2' => __('By Percentage of Cart Total', 'rewardsystem'),
                                ),
                            );
                            $updated_settings[] = array(
                                'name' => __($gateway->title . ' Reward Points', 'rewardsystem'),
                                'desc' => __('Please Enter Reward Points for ' . $gateway->title, 'rewardsystem'),
                                'id' => 'rs_reward_payment_gateways_' . $gateway->id,
                                'std' => '',
                                'default' => '',
                                'type' => 'text',
                                'newids' => 'rs_reward_payment_gateways_' . $gateway->id,
                                'desc_tip' => true,
                            );
                            $updated_settings[] = array(
                                'name' => __($gateway->title . ' Reward Points in Percent %', 'rewardsystem'),
                                'desc' => __('Please Enter Reward Points for ' . $gateway->title . ' in Percent %', 'rewardsystem'),
                                'id' => 'rs_reward_points_for_payment_gateways_in_percent_' . $gateway->id,
                                'std' => '',
                                'default' => '',
                                'type' => 'text',
                                'newids' => 'rs_reward_points_for_payment_gateways_in_percent_' . $gateway->id,
                                'desc_tip' => true,
                            );
                        }
                    } else {
                        if (class_exists('WC_Payment_Gateways')) {
                            $paymentgateway = new WC_Payment_Gateways();
                            foreach ($paymentgateway->payment_gateways()as $gateway) {
                                $updated_settings[] = array(
                                    'name' => __($gateway->title . ' Reward Type', 'rewardsystem'),
                                    'desc' => __('Please Select Reward Type for ' . $gateway->title, 'rewardsystem'),
                                    'id' => 'rs_reward_type_for_payment_gateways_' . $gateway->id,
                                    'std' => '',
                                    'default' => '',
                                    'type' => 'select',
                                    'newids' => 'rs_reward_type_for_payment_gateways_' . $gateway->id,
                                    'desc_tip' => true,
                                    'options' => array(
                                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                                        '2' => __('By Percentage of Cart Total', 'rewardsystem'),
                                    ),
                                );
                                $updated_settings[] = array(
                                    'name' => __($gateway->title . ' Reward Points', 'rewardsystem'),
                                    'desc' => __('Please Enter Reward Points for ' . $gateway->title, 'rewardsystem'),
                                    'id' => 'rs_reward_payment_gateways_' . $gateway->id,
                                    'std' => '',
                                    'default' => '',
                                    'type' => 'text',
                                    'newids' => 'rs_reward_payment_gateways_' . $gateway->id,
                                    'desc_tip' => true,
                                );
                                $updated_settings[] = array(
                                    'name' => __($gateway->title . ' Reward Points in Percent %', 'rewardsystem'),
                                    'desc' => __('Please Enter Reward Points for ' . $gateway->title . ' in Percent %', 'rewardsystem'),
                                    'id' => 'rs_reward_points_for_payment_gateways_in_percent_' . $gateway->id,
                                    'std' => '',
                                    'default' => '',
                                    'type' => 'text',
                                    'newids' => 'rs_reward_points_for_payment_gateways_in_percent_' . $gateway->id,
                                    'desc_tip' => true,
                                );
                            }
                        }
                    }
                    $updated_settings[] = array(
                        'type' => 'sectionend', 'id' => '_rs_reward_system_payment_gateway',
                    );
                }
                $newsettings = array('type' => 'sectionend', 'id' => '_rs_reward_system_pg_end');
                $updated_settings[] = $section;
            }
            return $updated_settings;
        }

        public static function rs_validation_for_input_field_in_reward_points_tab() {
            global $woocommerce;
            if (function_exists('WC')) {
                foreach (WC()->payment_gateways->payment_gateways() as $gateway) {
                    self::rs_script_for_validation($gateway);
                }
            } else {
                if (class_exists('WC_Payment_Gateways')) {
                    $paymentgateway = new WC_Payment_Gateways();
                    foreach ($paymentgateway->payment_gateways()as $gateway) {
                        self::rs_script_for_validation($gateway);
                    }
                }
            }
        }

        public static function rs_script_for_validation($gateway) {
            ?>
            <script type="text/javascript">
                jQuery(function () {
                    jQuery('body').on('blur', '#rs_reward_payment_gateways_<?php echo $gateway->id; ?>', function () {
                        jQuery('.wc_error_tip').fadeOut('100', function () {
                            jQuery(this).remove();
                        });
                        return this;
                    });
                    jQuery('body').on('keyup change', '#rs_reward_payment_gateways_<?php echo $gateway->id; ?>', function () {
                        var value = jQuery(this).val();
                        console.log(woocommerce_admin.i18n_mon_decimal_error);
                        var regex = new RegExp("[^\+0-9\%.\\" + woocommerce_admin.mon_decimal_point + "]+", "gi");
                        var newvalue = value.replace(regex, '');
                        if (value !== newvalue) {
                            jQuery(this).val(newvalue);
                            if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                                var offset = jQuery(this).position();
                                jQuery(this).after('<div class="wc_error_tip">' + woocommerce_admin.i18n_mon_decimal_error + " Negative Values are not allowed" + '</div>');
                                jQuery('.wc_error_tip')
                                        .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                        .css('top', offset.top + jQuery(this).height())
                                        .fadeIn('100');
                            }
                        }
                        return this;
                    });
                    jQuery("body").click(function () {
                        jQuery('.wc_error_tip').fadeOut('100', function () {
                            jQuery(this).remove();
                        });

                    });
                    if (jQuery('#rs_reward_type_for_payment_gateways_<?php echo $gateway->id; ?>').val() == '1') {
                        jQuery('#rs_reward_payment_gateways_<?php echo $gateway->id; ?>').closest('tr').show();
                        jQuery('#rs_reward_points_for_payment_gateways_in_percent_<?php echo $gateway->id; ?>').closest('tr').hide();
                    } else {
                        jQuery('#rs_reward_payment_gateways_<?php echo $gateway->id; ?>').closest('tr').hide();
                        jQuery('#rs_reward_points_for_payment_gateways_in_percent_<?php echo $gateway->id; ?>').closest('tr').show();
                    }

                    jQuery('#rs_reward_type_for_payment_gateways_<?php echo $gateway->id; ?>').change(function () {
                        if (jQuery('#rs_reward_type_for_payment_gateways_<?php echo $gateway->id; ?>').val() == '1') {
                            jQuery('#rs_reward_payment_gateways_<?php echo $gateway->id; ?>').closest('tr').show();
                            jQuery('#rs_reward_points_for_payment_gateways_in_percent_<?php echo $gateway->id; ?>').closest('tr').hide();
                        } else {
                            jQuery('#rs_reward_payment_gateways_<?php echo $gateway->id; ?>').closest('tr').hide();
                            jQuery('#rs_reward_points_for_payment_gateways_in_percent_<?php echo $gateway->id; ?>').closest('tr').show();
                        }
                    });
                });
            </script>
            <?php
        }

        public static function on_post_publish($ID, $post) {
            // A function to perform actions when a post is published.
            $user_ID = get_current_user_id();
            $post_id = $ID;
            //$title = $post_id->post_title;
            $earned_points = get_option('rs_reward_post');
            $date = rs_function_to_get_expiry_date_in_unixtimestamp();
            $enableoptforpost = get_option('rs_reward_for_Creating_Post');
            $meta_value = get_post_meta($post_id, 'rewardpointsforblogpost', true);
            if ($enableoptforpost == 'yes' && get_option('rs_reward_action_activated') == 'yes') {
                $retrived_value = get_option('fp_rs_list_blog_posts');
                if (!in_array($ID, $retrived_value)) {
                    if ($earned_points != "") {
                        if ($meta_value != "yes") {
                            RSPointExpiry::insert_earning_points($user_ID, $earned_points, '', $date, 'RPFP', '', '', '', '');
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                            RSPointExpiry::record_the_points($user_ID, $earned_points, '', $date, 'RPFP', '', '', '', $post_id, '', '', '', $totalpoints, '', '');
                            update_post_meta($post_id, 'rewardpointsforblogpost', 'yes');
                        }
                    }
                    $previous_value = get_option('fp_rs_list_blog_posts');
                    if ($previous_value != "") {
                        $current_id = $ID;
                        $combined_id = array_merge($previous_value, $current_id);
                        update_option('fp_rs_list_blog_posts', $ID);
                    } else {
                        update_option('fp_rs_list_blog_posts', $ID);
                    }
                }
            }
            $current_id[] = $ID;
            update_option('fp_rs_list_blog_posts', $current_id);
        }

        public static function rs_function_to_reset_action_tab() {
            $settings = RSRewardPointsForAction::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
        }

        public static function reward_add_coupon_usage_points_to_action() {
            wp_nonce_field(plugin_basename(__FILE__), 'rsdynamicrulecreation_coupon_usage');
            global $woocommerce;
            ?>
            <style type="text/css">
                .coupon_code_points_selected{
                    width: 60%!important;
                }
                .coupon_code_points{
                    width: 60%!important;
                }
                .chosen-container-multi {
                    position:absolute!important;
                }
            </style>
            <table class="widefat fixed rsdynamicrulecreation_coupon_usage" cellspacing="0">
                <thead>
                    <tr>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Coupon Codes', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Rule', 'rewardsystem'); ?></th>
                    </tr>
                </thead>
                <tbody id="here">
                    <?php
                    $rewards_dynamic_rulerule_coupon_points = get_option('rewards_dynamic_rule_couponpoints');
                    $i = 0;
                    if (is_array($rewards_dynamic_rulerule_coupon_points)) {
                        foreach ($rewards_dynamic_rulerule_coupon_points as $rewards_dynamic_rule) {
                            ?>
                            <tr>
                                <td class="column-columnname">
                                    <select multiple="multiple" name="rewards_dynamic_rule_coupon_usage[<?php echo $i; ?>][coupon_codes][]" class="short coupon_code_points_selected">
                                        <?php
                                        if (isset($rewards_dynamic_rule["coupon_codes"]) && $rewards_dynamic_rule["coupon_codes"] != "") {
                                            $coupons_list = $rewards_dynamic_rule["coupon_codes"];
                                            foreach ($coupons_list as $separate_coupons) {
                                                ?>
                                                <option value="<?php echo $separate_coupons; ?>" selected><?php echo $separate_coupons; ?></option>
                                                <?php
                                            }
                                            foreach (get_posts('post_type=shop_coupon') as $value) {
                                                $coupon_title = $value->post_title;
                                                $coupon_object = new WC_Coupon($coupon_title);
                                                $couponcodeuserid = get_userdata($value->post_author);
                                                $couponcodeuserlogin = $couponcodeuserid->user_login;
                                                $usernickname = 'sumo_' . strtolower("$couponcodeuserlogin");
                                                if ($usernickname != $value->post_title) {
                                                    if (!in_array($coupon_title, $coupons_list)) {
                                                        ?>
                                                        <option value="<?php echo $coupon_title; ?>"><?php echo $coupon_title; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="column-columnname">
                                    <input type="text" name="rewards_dynamic_rule_coupon_usage[<?php echo $i; ?>][reward_points]" value="<?php echo $rewards_dynamic_rule["reward_points"]; ?>" />
                                </td>
                                <td class="column-columnname num">
                                    <span class="remove button-secondary"><?php _e('Remove Rule', 'rewardsystem'); ?></span>
                                </td>
                            </tr>
                            <?php
                            $i = $i + 1;
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="manage-column column-columnname num" scope="col"> <span class="add button-primary"><?php _e('Add Rule', 'rewardsystem'); ?></span></td>
                    </tr>
                    <tr>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Coupon Codes', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Add Rule', 'rewardsystem'); ?></th>

                    </tr>
                </tfoot>
            </table>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var countrewards_dynamic_rule = <?php echo $i; ?>;
                    jQuery(".add").click(function () {
                        countrewards_dynamic_rule = countrewards_dynamic_rule + 1;
                        jQuery('#here').append('<tr><td><select multiple="multiple" id = "coupon_code_points' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_coupon_usage[' + countrewards_dynamic_rule + '][coupon_codes][]" class="short coupon_points coupon_code_points"><?php
            foreach (get_posts('post_type=shop_coupon') as $value) {
                $coupon_title = $value->post_title;
                $coupon_object = new WC_Coupon($coupon_title);
                $couponcodeuserid = get_userdata($value->post_author);
                $couponcodeuserlogin = $couponcodeuserid->user_login;
                $usernickname = 'sumo_' . strtolower("$couponcodeuserlogin");
                if ($usernickname != $value->post_title) {
                    ?><option value="<?php echo $coupon_title; ?>"><?php echo $coupon_title; ?><?php
                }
            }
            ?></option></select></td>\n\
            \n\<td><input type = "text" name="rewards_dynamic_rule_coupon_usage[' + countrewards_dynamic_rule + '][reward_points]" class="short " /></td>\n\
            \n\<td class="num"><span class="remove button-secondary">Remove Rule</span></td></tr><hr>');
            <?php if ((float) $woocommerce->version > (float) ('2.2.0')) { ?>
                            jQuery('#coupon_code_points' + countrewards_dynamic_rule).select2();
            <?php } else { ?>
                            jQuery('#coupon_code_points' + countrewards_dynamic_rule).chosen();
            <?php } ?>
                    });

                    jQuery(document).on('click', '.remove', function () {
                        jQuery(this).parent().parent().remove();
                    });
            <?php if ((float) $woocommerce->version > (float) ('2.2.0')) { ?>
                        jQuery('.coupon_code_points_selected').select2();
            <?php } else { ?>
                        jQuery('.coupon_code_points_selected').chosen();
            <?php } ?>
                });
            </script>
            <?php
        }

    }

    RSRewardPointsForAction::init();
}