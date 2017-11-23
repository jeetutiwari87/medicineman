<?php
/*
 * Support Tab Setting
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSCashbackModule')) {

    class RSCashbackModule {

        public static function init() {

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'), 999);

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_cashback_module', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_modules_rewardsystem_cashback_module', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

            add_action('woocommerce_admin_field_rs_encash_applications_list', array(__CLASS__, 'encash_list_overall_applications'));

            add_action('woocommerce_admin_field_rs_encash_applications_edit_lists', array(__CLASS__, 'encash_applications_list_table'));

            add_action('woocommerce_admin_field_redeeming_conversion_for_cash_back', array(__CLASS__, 'reward_system_redeeming_points_conversion_for_cash_back'));

            add_action('woocommerce_admin_field_rs_enable_disable_cashback_module', array(__CLASS__, 'rs_function_to_enable_disable_cashback_module'));

            add_action('fp_action_to_reset_module_settings_rewardsystem_cashback_module', array(__CLASS__, 'rs_function_to_cashback_module'));
            
        }

        /*
         * Function to Define Name of the Tabss
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_cashback_module'] = __('Cashback Module', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;
            return apply_filters('woocommerce_rewardsystem_cashback_module', array(
                array(
                    'type' => 'rs_modulecheck_start',
                 ),
                array(
                    'name' => __('Cashback Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_activate_cashback_module'
                ),
                array(
                    'type' => 'rs_enable_disable_cashback_module',
                ),
                array('type' => 'sectionend', 'id' => '_rs_activate_cashback_module'),
                array(
                    'type' => 'rs_modulecheck_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Cashback Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_point_encashing_settings'
                ),
                array(
                    'name' => __('Enable Cashback for Reward Points', 'rewardsystem'),
                    'desc' => __('Enable this option to provide the feature to Cashback the Reward Points earned by the Users', 'rewardsystem'),
                    'id' => 'rs_enable_disable_encashing',
                    'std' => '2',
                    'default' => '2',
                    'type' => 'select',
                    'newids' => 'rs_enable_disable_encashing',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Minimum Points for Cashback of Reward Points', 'rewardsystem'),
                    'desc' => __('Enter the Minimum points that the user should have in order to Submit the Cashback Request', 'rewardsystem'),
                    'id' => 'rs_minimum_points_encashing_request',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_minimum_points_encashing_request',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Maximum Points for Cashback of Reward Points', 'rewardsystem'),
                    'desc' => __('Enter the Maximum points that the user should enter order to Submit the Cashback Request', 'rewardsystem'),
                    'id' => 'rs_maximum_points_encashing_request',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_maximum_points_encashing_request',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Points for Cashback Label', 'rewardsystem'),
                    'desc' => __('Please Enter Points the Label for Cashback', 'rewardsystem'),
                    'id' => 'rs_encashing_points_label',
                    'std' => 'Points for Cashback',
                    'default' => 'Points for Cashback',
                    'type' => 'text',
                    'newids' => 'rs_encashing_points_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Reason for Cashback Label', 'rewardsystem'),
                    'desc' => __('Please Enter label for Reason Cashback', 'rewardsystem'),
                    'id' => 'rs_encashing_reason_label',
                    'std' => 'Reason for Cashback',
                    'default' => 'Reason for Cashback',
                    'type' => 'text',
                    'newids' => 'rs_encashing_reason_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Payment Method Label', 'rewardsystem'),
                    'desc' => __('Please Enter Payment Method Label for Cashback', 'rewardsystem'),
                    'id' => 'rs_encashing_payment_method_label',
                    'std' => 'Payment Method',
                    'default' => 'Payment Method',
                    'type' => 'text',
                    'newids' => 'rs_encashing_payment_method_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Display Payment Method', 'rewardsystem'),
                    'id' => 'rs_select_payment_method',
                    'std' => '3',
                    'default' => '3',
                    'type' => 'select',
                    'newids' => 'rs_select_payment_method',
                    'options' => array(
                        '1' => __('PayPal', 'rewardsystem'),
                        '2' => __('Custom Payment', 'rewardsystem'),
                        '3' => __('Both', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('PayPal Email Address Label', 'rewardsystem'),
                    'desc' => __('Please Enter PayPal Email Address Label for Cashback', 'rewardsystem'),
                    'id' => 'rs_encashing_payment_paypal_label',
                    'std' => 'PayPal Email Address',
                    'default' => 'PayPal Email Address',
                    'type' => 'text',
                    'newids' => 'rs_encashing_payment_paypal_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Custom Payment Details Label', 'rewardsystem'),
                    'desc' => __('Please Enter Custom Payment Details Label for Cashback', 'rewardsystem'),
                    'id' => 'rs_encashing_payment_custom_label',
                    'std' => 'Custom Payment Details',
                    'default' => 'Custom Payment Details',
                    'type' => 'text',
                    'newids' => 'rs_encashing_payment_custom_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Cashback Form Submit Button Label', 'rewardsystem'),
                    'desc' => __('Please Enter Cashback Form Submit Button Label ', 'rewardsystem'),
                    'id' => 'rs_encashing_submit_button_label',
                    'std' => 'Submit',
                    'default' => 'Submit',
                    'type' => 'text',
                    'newids' => 'rs_encashing_submit_button_label',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_point_checkout_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Redeeming Points Conversion Settings for Cashback', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_redeem_point_conversion_for_cash_back'
                ),
                array(
                    'type' => 'redeeming_conversion_for_cash_back',
                ),
                array('type' => 'sectionend', 'id' => '_rs_redeem_point_conversion_cash_back'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Cashback Request List', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_request_for_cash_back_setting'
                ),
                array(
                    'type' => 'rs_encash_applications_list',
                ),
                array(
                    'type' => 'rs_encash_applications_edit_lists',
                ),
                array('type' => 'sectionend', 'id' => '_rs_request_for_cash_back_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('My Cashback Table Label Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_my_cashback_label_settings'
                ),
                array(
                    'name' => __('My Cashback Table', 'rewardsystem'),
                    'id' => 'rs_my_cashback_table',
                    'std' => '1',
                    'desc_tip' => true,
                    'default' => '1',
                    'newids' => 'rs_my_cashback_table',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('My Cashback Label', 'rewardsystem'),
                    'desc' => __('Enter the My Cashback Label', 'rewardsystem'),
                    'id' => 'rs_my_cashback_title',
                    'std' => 'My Cashback',
                    'default' => 'My Cashback',
                    'type' => 'text',
                    'newids' => 'rs_my_cashback_title',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('S.No Label', 'rewardsystem'),
                    'desc' => __('Enter the Serial Number Label', 'rewardsystem'),
                    'id' => 'rs_my_cashback_sno_label',
                    'std' => 'S.No',
                    'default' => 'S.No',
                    'type' => 'text',
                    'newids' => 'rs_my_cashback_sno_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Username Label', 'rewardsystem'),
                    'desc' => __('Enter the Username Label', 'rewardsystem'),
                    'id' => 'rs_my_cashback_userid_label',
                    'std' => 'Username',
                    'default' => 'Username',
                    'type' => 'text',
                    'newids' => 'rs_my_cashback_userid_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Requested for Cashback Label', 'rewardsystem'),
                    'desc' => __('Enter the Requested for Cashback Label', 'rewardsystem'),
                    'id' => 'rs_my_cashback_requested_label',
                    'std' => 'Requested for Cashback',
                    'default' => 'Requested for Cashback',
                    'type' => 'text',
                    'newids' => 'rs_my_cashback_requested_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Status Label', 'rewardsystem'),
                    'desc' => __('Enter the Status On Label', 'rewardsystem'),
                    'id' => 'rs_my_cashback_status_label',
                    'std' => 'Status',
                    'default' => 'Status',
                    'type' => 'text',
                    'newids' => 'rs_my_cashback_status_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Action Label', 'rewardsystem'),
                    'desc' => __('Enter the Action On Label', 'rewardsystem'),
                    'id' => 'rs_my_cashback_action_label',
                    'std' => 'Action',
                    'default' => 'Action',
                    'type' => 'rs_action_for_cash_back',
                    'newids' => 'rs_my_cashback_action_label',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_my_cashback_label_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_message_settings_encashing'
                ),
                array(
                    'name' => __('Message displayed for Guest', 'rewardsystem'),
                    'desc' => __('Please Enter Message displayed for Guest', 'rewardsystem'),
                    'id' => 'rs_message_for_guest_encashing',
                    'std' => 'Please [rssitelogin] to Cashback your Reward Points.',
                    'default' => 'Please [rssitelogin] to Cashback your Reward Points.',
                    'type' => 'textarea',
                    'newids' => 'rs_message_for_guest_encashing',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Login Link for Guest Label', 'rewardsystem'),
                    'desc' => __('Please Enter Login link for Guest Label', 'rewardsystem'),
                    'id' => 'rs_encashing_login_link_label',
                    'std' => 'Login',
                    'default' => 'Login',
                    'type' => 'text',
                    'newids' => 'rs_encashing_login_link_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Message displayed for Banned Users', 'rewardsystem'),
                    'desc' => __('Please Enter Message Displayed for Banned Users', 'rewardsystem'),
                    'id' => 'rs_message_for_banned_users_encashing',
                    'std' => 'You cannot Cashback Your points',
                    'default' => 'You cannot Cashback Your points',
                    'type' => 'textarea',
                    'newids' => 'rs_message_for_banned_users_encashing',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Message displayed when Users don\'t have Reward Points', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when Users dont have Reward Points', 'rewardsystem'),
                    'id' => 'rs_message_users_nopoints_encashing',
                    'std' => 'You Don\'t have points for Cashback',
                    'default' => 'You Don\'t have points for Cashback',
                    'type' => 'textarea',
                    'newids' => 'rs_message_users_nopoints_encashing',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Message displayed when Cashback Request is Submitted', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when Cashback Request is Submitted', 'rewardsystem'),
                    'id' => 'rs_message_encashing_request_submitted',
                    'std' => 'Cashback Request Submitted',
                    'default' => 'Cashback Request Submitted',
                    'type' => 'textarea',
                    'newids' => 'rs_message_encashing_request_submitted',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_message_settings_encashing'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('CSV Settings (Export CSV for Paypal Mass Payment)', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_csv_message_settings_encashing'
                ),
                array(
                    'name' => __('Custom Note for Paypal', 'rewardsystem'),
                    'desc' => __('A Custom Note for Paypal', 'rewardsystem'),
                    'id' => 'rs_encashing_paypal_custom_notes',
                    'std' => 'Thanks for your Business',
                    'default' => 'Thanks for your Business',
                    'type' => 'textarea',
                    'newids' => 'rs_encashing_paypal_custom_notes',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_csv_message_settings_encashing'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Error Message Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_error_settings_encashing'
                ),
                array(
                    'name' => __('Error Message displayed when Points for Cashback Field is left Empty', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when Points for Cashback Field is Empty', 'rewardsystem'),
                    'id' => 'rs_error_message_points_empty_encash',
                    'std' => 'Points for Cashback Field cannot be empty',
                    'default' => 'Points for Cashback Field cannot be empty',
                    'type' => 'text',
                    'newids' => 'rs_error_message_points_empty_encash',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message displayed when Points to Cashback Value is not a Number', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when Points To Cashback Field value is not a number', 'rewardsystem'),
                    'id' => 'rs_error_message_points_number_val_encash',
                    'std' => 'Please Enter only Numbers',
                    'default' => 'Please Enter only Numbers',
                    'type' => 'text',
                    'newids' => 'rs_error_message_points_number_val_encash',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message displayed when Points entered for Cashback is more than the Points Earned', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when Points entered for Cashback is more than the Points Earned', 'rewardsystem'),
                    'id' => 'rs_error_message_points_greater_than_earnpoints',
                    'std' => 'Points Entered for Cashback is more than the Earned Points',
                    'default' => 'Points Entered for Cashback is more than the Earned Points',
                    'type' => 'text',
                    'newids' => 'rs_error_message_points_greater_than_earnpoints',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message displayed when Current User Points is less than the Minimum Points for Cashback', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when Points entered for Cashback is more than the Maximum Points for Cashback', 'rewardsystem'),
                    'id' => 'rs_error_message_currentpoints_less_than_minimum_points',
                    'std' => 'You need a Minimum of [minimum_encash_points] points in order for Cashback',
                    'default' => 'You need a Minimum of [minimum_encash_points] points in order for Cashback',
                    'type' => 'textarea',
                    'newids' => 'rs_error_message_currentpoints_less_than_minimum_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message displayed when Points entered to Cashback is less than the Minimum Points and more than Maximum Points', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when Points entered to Cashback is less than the Minimum Points and more than Maximum Points', 'rewardsystem'),
                    'id' => 'rs_error_message_points_lesser_than_minimum_points',
                    'std' => 'Please Enter Between [minimum_encash_points] and [maximum_encash_points] ',
                    'default' => 'Please Enter Between [minimum_encash_points] and [maximum_encash_points]',
                    'type' => 'textarea',
                    'newids' => 'rs_error_message_points_lesser_than_minimum_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message displayed when Reason to Cashback Field is Empty', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when Reason To Cashback Field is Empty', 'rewardsystem'),
                    'id' => 'rs_error_message_reason_encash_empty',
                    'std' => 'Reason to Encash Field cannot be empty',
                    'default' => 'Reason to Encash Field cannot be empty',
                    'type' => 'text',
                    'newids' => 'rs_error_message_reason_encash_empty',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message displayed when PayPal Email Address is Empty', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when PayPal Email Address is Empty', 'rewardsystem'),
                    'id' => 'rs_error_message_paypal_email_empty',
                    'std' => 'Paypal Email Field cannot be empty',
                    'default' => 'Paypal Email Field cannot be empty',
                    'type' => 'text',
                    'newids' => 'rs_error_message_paypal_email_empty',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message displayed when PayPal Email Address Format is wrong', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when PayPal Email Address format is wrong', 'rewardsystem'),
                    'id' => 'rs_error_message_paypal_email_wrong',
                    'std' => 'Enter a Correct Email Address',
                    'default' => 'Enter a Correct Email Address',
                    'type' => 'text',
                    'newids' => 'rs_error_message_paypal_email_wrong',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message displayed when Custom Payment Details field is left Empty', 'rewardsystem'),
                    'desc' => __('Please Enter Message to be Displayed when Custom Payment Details field is Empty', 'rewardsystem'),
                    'id' => 'rs_error_custom_payment_field_empty',
                    'std' => 'Custom Payment Details Field cannot be empty',
                    'default' => 'Custom Payment Details Field cannot be empty',
                    'type' => 'text',
                    'newids' => 'rs_error_custom_payment_field_empty',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_error_settings_encashing'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shortcode used in Form for Cashback', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_shortcode_for_cashback'
                ),
                array(
                    'type' => 'title',
                    'desc' => '<b>[minimum_encash_points]</b> - To display minimum points required to get cashback<br><br>'
                    . '<b>[maximum_encash_points]</b> - To display maximum points required to get cashback<br><br>'
                    . '<b>[rssitelogin]</b> - To display login link for guests',
                ),
                array('type' => 'sectionend', 'id' => 'rs_shortcode_for_cashback'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {

            woocommerce_admin_fields(RSCashbackModule::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSCashbackModule::reward_system_admin_fields());
            if (isset($_POST['rs_redeem_point_for_cash_back'])) {
                update_option('rs_redeem_point_for_cash_back', $_POST['rs_redeem_point_for_cash_back']);
            }else{
                update_option('rs_redeem_point_for_cash_back', '');
            }
            if (isset($_POST['rs_redeem_point_value_for_cash_back'])) {
                update_option('rs_redeem_point_value_for_cash_back', $_POST['rs_redeem_point_value_for_cash_back']);
            }else{
                update_option('rs_redeem_point_value_for_cash_back', '');
            }
            if (isset($_POST['rs_cashback_module_checkbox'])) {
                update_option('rs_cashback_activated', $_POST['rs_cashback_module_checkbox']);
            } else {
                update_option('rs_cashback_activated', 'no');
            }
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSCashbackModule::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        public static function rs_function_to_cashback_module() {
            $settings = RSCashbackModule::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
            update_option('rs_redeem_point_for_cash_back', '1');
            update_option('rs_redeem_point_value_for_cash_back', '1');
        }

        public static function encash_list_overall_applications() {
            global $wpdb;
            global $current_section;
            global $current_tab;

            $testListTable = new FPRewardSystemEncashTabList();
            $testListTable->prepare_items();
            if (!isset($_REQUEST['encash_application_id'])) {
                $array_list = array();
                $message = '';
                if ('encash_application_delete' === $testListTable->current_action()) {
                    $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d'), count($_REQUEST['id'])) . '</p></div>';
                }
                echo $message;
                $testListTable->display();
            }
        }

        public static function encash_applications_list_table($item) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sumo_reward_encashing_submitted_data';
            $message = '';
            $notice = '';
            $default = array(
                'id' => 0,
                'userid' => '',
                'pointstoencash' => '',
                'encashercurrentpoints' => '',
                'reasonforencash' => '',
                'encashpaymentmethod' => '',
                'paypalemailid' => '',
                'otherpaymentdetails' => '',
                'status' => '',
            );

            if (isset($_REQUEST['nonce'])) {
                if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
                    $item = shortcode_atts($default, $_REQUEST);
                    $item_valid = self::encash_validation($item);
                    if ($item_valid === true) {
                        if ($item['id'] == 0) {
                            $result = $wpdb->insert($table_name, $item);
                            $item['id'] = $wpdb->insert_id;
                            if ($result) {
                                $message = __('Item was successfully saved');
                            } else {
                                $notice = __('There was an error while saving item');
                            }
                        } else {
                            $result = $wpdb->update($table_name, $item, array('id' => $item['id']));



                            if ($result) {
                                $message = __('Item was successfully updated');
                            } else {
                                $notice = __('There was an error while updating item');
                            }
                        }
                    } else {
                        // if $item_valid not true it contains error message(s)
                        $notice = $item_valid;
                    }
                }
            } else {
                // if this is not post back we load item to edit or give new one to create
                $item = $default;

                if (isset($_REQUEST['encash_application_id'])) {
                    $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['encash_application_id']), ARRAY_A);

                    if (!$item) {
                        $item = $default;
                        $notice = __('Item not found');
                    }
                }
            }
            ?>
            <?php
            if (isset($_REQUEST['encash_application_id'])) {
                ?>
                <style type="text/css">
                    p.sumo_reward_points {
                        display:none;
                    }
                    #mainforms {
                        display:none;
                    }
                </style>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        var currentvalue = jQuery('#encashpaymentmethod').val();
                        if (currentvalue === '1') {
                            jQuery('.paypalemailid').parent().parent().css('display', 'table-row');
                            jQuery('.otherpaymentdetails').parent().parent().css('display', 'none');
                        } else {
                            jQuery('.otherpaymentdetails').parent().parent().css('display', 'table-row');
                            jQuery('.paypalemailid').parent().parent().css('display', 'none');
                        }
                        jQuery('#encashpaymentmethod').change(function () {
                            var thisvalue = jQuery(this).val();
                            if (thisvalue === '1') {
                                jQuery('.paypalemailid').parent().parent().css('display', 'table-row');
                                jQuery('.otherpaymentdetails').parent().parent().css('display', 'none');
                            } else {
                                if (thisvalue === '2') {
                                    jQuery('.paypalemailid').parent().parent().css('display', 'none');
                                    jQuery('.otherpaymentdetails').parent().parent().css('display', 'table-row');
                                }
                            }
                        });
                    });
                </script>
                <?php
                $timeformat = get_option('time_format');
                $dateformat = get_option('date_format') . ' ' . $timeformat;
                $expired_date = date_i18n($dateformat);
                ?>
                <div class="wrap">
                    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                    <h3><?php _e('Edit Cashback Status', 'rewardsystem'); ?><a class="add-new-h2"
                                                                               href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=rewardsystem_callback&tab=encash_applications'); ?>"><?php _e('Back to list') ?></a>
                    </h3>
                    <?php if (!empty($notice)): ?>
                        <div id="notice" class="error"><p><?php echo $notice ?></p></div>
                    <?php endif; ?>
                    <?php if (!empty($message)): ?>
                        <div id="message" class="updated"><p><?php echo $message ?></p></div>
                    <?php endif; ?>
                    <form id="form" method="POST">
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>"/>
                        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>
                        <input type="hidden" name="userid" value="<?php echo $item['userid']; ?>"/>
                        <input type="hidden" value="<?php echo $item['setvendoradmins']; ?>" name="setvendoradmins"/>
                        <input type="hidden" value="<?php echo $item['setusernickname']; ?>" name="setusernickname"/>
                        <input type="hidden" value="<?php echo $expired_date; ?>" name="date"/>
                        <div class="metabox-holder" id="poststuff">
                            <div id="post-body">
                                <div id="post-body-content">
                                    <table class="form-table">
                                        <tbody>                                        
                                            <tr>
                                                <th scope="row"><?php _e('Points for Cashback', 'rewardsystem'); ?></th>
                                                <td>
                                                    <input type="text" name="pointstoencash" id="setvendorname" value="<?php echo $item['pointstoencash']; ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?php _e('Reason for Cashback', 'rewardsystem'); ?></th>
                                                <td>
                                                    <textarea name="reasonforencash" rows="3" cols="30"><?php echo $item['reasonforencash']; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?php _e('Application Status', 'rewardsystem'); ?></th>
                                                <td>
                                                    <?php
                                                    $selected_approved = $item['status'] == 'Paid' ? "selected=selected" : '';
                                                    $selected_rejected = $item['status'] == 'Due' ? "selected=selected" : '';
                                                    ?>
                                                    <select name = "status">                                                    
                                                        <option value = "Paid" <?php echo $selected_approved; ?>><?php _e('Paid', 'rewardsystem'); ?></option>
                                                        <option value = "Due" <?php echo $selected_rejected; ?>><?php _e('Due', 'rewardsystem'); ?></option>
                                                    </select>
                                                </td>
                                            </tr>                                                                                
                                            <tr>
                                                <th scope="row"><?php _e('Cashback Payment Option', 'rewardsystem'); ?></th>
                                                <td>                                             
                                                    <?php
                                                    $selectedpaymentoption = $item['encashpaymentmethod'] == 'encash_through_paypal_method' ? "selected=selected" : "";
                                                    $mainselectedpaymentoption = $item['encashpaymentmethod'] == 'encash_through_custom_payment' ? "selected=selected" : "";
                                                    ?>
                                                    <select id="encashpaymentmethod" name="encashpaymentmethod">
                                                        <option value="1" <?php echo $selectedpaymentoption; ?>><?php _e('Paypal Address', 'rewardsystem'); ?></option>
                                                        <option value="2" <?php echo $mainselectedpaymentoption; ?>><?php _e('Custom Payment', 'rewardsystem'); ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?php _e('User Paypal Email', 'rewardsystem'); ?></th>
                                                <td>
                                                    <input type="text" name="paypalemailid" class="paypalemailid" value="<?php echo $item['paypalemailid']; ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?php _e('User Custom Payment Details', 'rewardsystem'); ?></th>
                                                <td>
                                                    <textarea name='otherpaymentdetails' rows='3' cols='30' id='otherpaymentdetails' class='otherpaymentdetails'><?php echo $item['otherpaymentdetails']; ?></textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <input type="submit" value="<?php _e('Save Changes', 'rewardsystem') ?>" id="submit" class="button-primary" name="submit">
                                </div>
                            </div>
                        </div>                    
                    </form>

                </div>
            <?php } ?>

            <?php
        }

        public static function encash_validation($item) {
            $messages = array();
            if (empty($messages))
                return true;
            return implode('<br />', $messages);
        }

        public static function reward_system_redeeming_points_conversion_for_cash_back() {
            ?>
            <tr valign="top">
                <td class="forminp forminp-text">
                    <input type="number" step="any" min="0" value="<?php echo get_option('rs_redeem_point_for_cash_back'); ?>" style="max-width:50px;" id="rs_redeem_point_for_cash_back" name="rs_redeem_point_for_cash_back"> <?php _e('Redeeming Point(s)', 'rewardsystem'); ?>
                    &nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;
                    <?php echo get_woocommerce_currency_symbol(); ?> 	<input type="number" step="any" min="0" value="<?php echo get_option('rs_redeem_point_value_for_cash_back'); ?>" style="max-width:50px;" id="rs_redeem_point_value_for_cash_back" name="rs_redeem_point_value_for_cash_back"></td>
            </td>
            </tr>
            <?php
        }

        public static function rs_function_to_enable_disable_cashback_module() {
            $get_option_value = get_option('rs_cashback_activated');
            $name_of_checkbox = 'rs_cashback_module_checkbox';
            RSModulesTab::rs_common_function_to_get_checkbox($get_option_value, $name_of_checkbox);
        }
      

    }

    RSCashbackModule::init();
}