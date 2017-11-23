<?php
/*
 * Support Tab Setting
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSModulesTab')) {

    class RSModulesTab {

        public static function init() {

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_modules', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_modules', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

            add_action('woocommerce_admin_field_rs_modules_for_sumo', array(__CLASS__, 'reward_system_module_html'));

            add_action('admin_head', array(__CLASS__, 'rs_activate_modules_for_sumo'));

            add_action('wp_ajax_rs_ajax_to_activate', array(__CLASS__, 'rs_callback_to_activate'));

            add_action('admin_head', array(__CLASS__, 'check_trigger_button_rewardsystem'));

            add_action('add_meta_boxes', array(__CLASS__, 'add_meta_box_for_earned'));
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_modules'] = __('Modules', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;
            return apply_filters('woocommerce_rewardsystem_modules_tab', array(
                array(
                    'type' => 'rs_modules_for_sumo'
                ),
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {

            woocommerce_admin_fields(RSModulesTab::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSModulesTab::reward_system_admin_fields());
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSModulesTab::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        public static function rs_common_function_to_get_hyperlink_and_box_class_name($get_option) {
            $new_array = array();
            if ($get_option == 'yes') {
                $active_class_name = 'active_rs_box';
                $active_hyperlink_class_name = 'rs_active_hyperlink';
            } else {
                $active_class_name = 'rs-box';
                $active_hyperlink_class_name = 'rs_hyperlink';
            }
            $new_array = array('box_class_name' => $active_class_name, 'hyperlink_class_name' => $active_hyperlink_class_name);
            return $new_array;
        }

        public static function reward_system_module_html() {
            if (isset($_GET['section'])) {
                do_action('woocommerce_rs_settings_tabs_' . $_GET['section']);
            } else {
                ?>
                <style type="text/css">
                    p.sumo_reward_points{
                        display:none;
                    }
                </style>
                <div class="rs_Grid_wrapper"> 
                    <h1 class="rs_module_title"> SUMO Reward points <span class="rs_module">- Modules</span> </h1>
                    <div class="rs_Grid_wrapper_inner">
                        <?php
                        //Product Purchase Module
                        $get_prod_purchase = get_option('rs_product_purchase_activated');
                        $class_name_for_product_purchase = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_prod_purchase);
                        self::rs_common_html_function_for_module($label_id = 'product_purchase_label', $div_id = 'product_purchase_module', $class_name_for_product_purchase['box_class_name'], $class_name_for_product_purchase['hyperlink_class_name'], $module_name = 'Product Purchase', $checkbox_id = 'rs_product_purchase_module_checkbox', $settings_id = 'product_purchase_settings', $tab_name = 'rewardsystem_product_purchase_module', $get_prod_purchase);

                        //Referral System Module
                        $get_referral = get_option('rs_referral_activated');
                        $class_name_for_referral = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_referral);
                        self::rs_common_html_function_for_module($label_id = 'referral_system_label', $div_id = 'referral_module', $class_name_for_referral['box_class_name'], $class_name_for_referral['hyperlink_class_name'], $module_name = 'Referral System', $checkbox_id = 'rs_referral_module_checkbox', $settings_id = 'referral_settings', $tab_name = 'rewardsystem_referral_system_module', $get_referral);

                        //Social Reward Module
                        $get_social_reward = get_option('rs_social_reward_activated');
                        $class_name_for_social_reward = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_social_reward);
                        self::rs_common_html_function_for_module($label_id = 'social_reward_label', $div_id = 'social_reward_module', $class_name_for_social_reward['box_class_name'], $class_name_for_social_reward['hyperlink_class_name'], $module_name = 'Social Reward Points', $checkbox_id = 'rs_social_reward_module_checkbox', $settings_id = 'social_reward_settings', $tab_name = 'rewardsystem_socialrewards', $get_social_reward);

                        //Reward Points for Actions Module
                        $get_reward_action_module = get_option('rs_reward_action_activated');
                        $class_name_for_action = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_reward_action_module);
                        self::rs_common_html_function_for_module($label_id = 'reward_action_label', $div_id = 'reward_action_module', $class_name_for_action['box_class_name'], $class_name_for_action['hyperlink_class_name'], $module_name = 'Action Reward Points', $checkbox_id = 'rs_reward_action_module_checkbox', $settings_id = 'reward_action_settings', $tab_name = 'rewardsystem_reward_points_for_action', $get_reward_action_module);

                        //Points Expiry Module
                        $get_point_expiry_module = get_option('rs_point_expiry_activated');
                        $class_name_for_pointexp = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_point_expiry_module);
                        self::rs_common_html_function_for_module($label_id = 'point_expiry_label', $div_id = 'point_expiry_module', $class_name_for_pointexp['box_class_name'], $class_name_for_pointexp['hyperlink_class_name'], $module_name = 'Points Expiry', $checkbox_id = 'rs_point_expiry_module_checkbox', $settings_id = 'point_expiry_settings', $tab_name = 'rewardsystem_point_expiry_module', $get_point_expiry_module);

                        //Redeeming Points Module
                        $get_redeeming_module = get_option('rs_redeeming_activated');
                        $class_name_for_redeeming = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_redeeming_module);
                        self::rs_common_html_function_for_module($label_id = 'redeeming_label', $div_id = 'redeeming_module', $class_name_for_redeeming['box_class_name'], $class_name_for_redeeming['hyperlink_class_name'], $module_name = 'Redeeming Points', $checkbox_id = 'rs_redeeming_module_checkbox', $settings_id = 'redeeming_settings', $tab_name = 'rewardsystem_redeeming_module', $get_redeeming_module);

                        //Points Price Module
                        $get_point_price_module = get_option('rs_point_price_activated');
                        $class_name_for_pointprice = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_point_price_module);
                        self::rs_common_html_function_for_module($label_id = 'point_price_label', $div_id = 'point_price_module', $class_name_for_pointprice['box_class_name'], $class_name_for_pointprice['hyperlink_class_name'], $module_name = 'Points Price', $checkbox_id = 'rs_point_price_module_checkbox', $settings_id = 'point_price_settings', $tab_name = 'rewardsystem_point_price_module', $get_point_price_module);

                        //Email Module
                        $get_email_module = get_option('rs_email_activated');
                        $class_name_for_email = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_email_module);
                        self::rs_common_html_function_for_module($label_id = 'email_label', $div_id = 'email_module', $class_name_for_email['box_class_name'], $class_name_for_email['hyperlink_class_name'], $module_name = 'Email', $checkbox_id = 'rs_email_module_checkbox', $settings_id = 'email_settings', $tab_name = 'rewardsystem_email_module', $get_email_module);

                        //Gift Voucher Module
                        $get_gift_voucher = get_option('rs_gift_voucher_activated');
                        $class_name_for_gift_voucher = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_gift_voucher);
                        self::rs_common_html_function_for_module($label_id = 'gift_voucher_label', $div_id = 'gift_voucher_module', $class_name_for_gift_voucher['box_class_name'], $class_name_for_gift_voucher['hyperlink_class_name'], $module_name = 'Gift Voucher', $checkbox_id = 'rs_gift_voucher_module_checkbox', $settings_id = 'gift_vocuher_settings', $tab_name = 'rewardsystem_offline_online_rewards', $get_gift_voucher);

                        //SMS Module
                        $get_sms_module = get_option('rs_sms_activated');
                        $class_name_for_sms = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_sms_module);
                        self::rs_common_html_function_for_module($label_id = 'sms_label', $div_id = 'sms_module', $class_name_for_sms['box_class_name'], $class_name_for_sms['hyperlink_class_name'], $module_name = 'SMS', $checkbox_id = 'rs_sms_module_checkbox', $settings_id = 'sms_settings', $tab_name = 'rewardsystem_sms', $get_sms_module);

                        //Cashback Module
                        $get_cashback_module = get_option('rs_cashback_activated');
                        $class_name_for_cashback = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_cashback_module);
                        self::rs_common_html_function_for_module($label_id = 'cashback_label', $div_id = 'cashback_module', $class_name_for_cashback['box_class_name'], $class_name_for_cashback['hyperlink_class_name'], $module_name = 'Cash Back', $checkbox_id = 'rs_cashback_module_checkbox', $settings_id = 'cashback_settings', $tab_name = 'rewardsystem_cashback_module', $get_cashback_module);

                        //Nominee Module
                        $get_nominee_module = get_option('rs_nominee_activated');
                        $class_name_for_nominee = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_nominee_module);
                        self::rs_common_html_function_for_module($label_id = 'nominee_label', $div_id = 'nominee_module', $class_name_for_nominee['box_class_name'], $class_name_for_nominee['hyperlink_class_name'], $module_name = 'Nominee', $checkbox_id = 'rs_nominee_module_checkbox', $settings_id = 'nominee_settings', $tab_name = 'rewardsystem_nominee', $get_nominee_module);

                        //Point URL Module
                        $get_point_url_module = get_option('rs_point_url_activated');
                        $class_name_for_pointurl = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_point_url_module);
                        self::rs_common_html_function_for_module($label_id = 'point_url_label', $div_id = 'point_url_module', $class_name_for_pointurl['box_class_name'], $class_name_for_pointurl['hyperlink_class_name'], $module_name = 'Point URL', $checkbox_id = 'rs_point_url_module_checkbox', $settings_id = 'point_url_settings', $tab_name = 'rs_points_url', $get_point_url_module);

                        //Reward Point Gateway Module
                        $get_gateway_module = get_option('rs_gateway_activated');
                        $class_name_for_gateway = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_gateway_module);
                        self::rs_common_html_function_for_module($label_id = 'gateway_label', $div_id = 'gateway_module', $class_name_for_gateway['box_class_name'], $class_name_for_gateway['hyperlink_class_name'], $module_name = 'Reward Points Payment Gateway', $checkbox_id = 'rs_gateway_module_checkbox', $settings_id = 'reward_gateway_settings', $tab_name = 'rewardsystem_rewardpoints_gateway_module', $get_gateway_module);

                        //Send Points Module
                        $get_send_points_module = get_option('rs_send_points_activated');
                        $class_name_for_sendpoints = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_send_points_module);
                        self::rs_common_html_function_for_module($label_id = 'send_points_label', $div_id = 'send_points_module', $class_name_for_sendpoints['box_class_name'], $class_name_for_sendpoints['hyperlink_class_name'], $module_name = 'Send Points', $checkbox_id = 'rs_send_points_module_checkbox', $settings_id = 'send_points_settings', $tab_name = 'rewardsystem_sendpoints_module', $get_send_points_module);

                        //Import/Export Points Module
                        $get_imp_exp_module = get_option('rs_imp_exp_activated');
                        $class_name_for_imp_exp = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_imp_exp_module);
                        self::rs_common_html_function_for_module($label_id = 'imp_exp_label', $div_id = 'imp_exp_module', $class_name_for_imp_exp['box_class_name'], $class_name_for_imp_exp['hyperlink_class_name'], $module_name = 'Import/Export Points', $checkbox_id = 'rs_imp_exp_module_checkbox', $settings_id = 'imp_exp_settings', $tab_name = 'rewardsystem_import_export', $get_imp_exp_module);

                        //Reports Module
                        $get_report_module = get_option('rs_report_activated');
                        $class_name_for_report = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_report_module);
                        self::rs_common_html_function_for_module($label_id = 'reports_label', $div_id = 'report_module', $class_name_for_report['box_class_name'], $class_name_for_report['hyperlink_class_name'], $module_name = 'Reports', $checkbox_id = 'rs_report_module_checkbox', $settings_id = 'report_settings', $tab_name = 'rewardsystem_reports_in_csv', $get_report_module);

                        //Reset Module
                        $get_reset_module = get_option('rs_reset_activated');
                        $class_name_for_reset = self::rs_common_function_to_get_hyperlink_and_box_class_name($get_reset_module);
                        self::rs_common_html_function_for_module($label_id = 'reset_label', $div_id = 'reset_module', $class_name_for_reset['box_class_name'], $class_name_for_reset['hyperlink_class_name'], $module_name = 'Reset', $checkbox_id = 'rs_reset_module_checkbox', $settings_id = 'reset_settings', $tab_name = 'rewardsystem_reset', $get_reset_module);
                        ?>
                    </div>
                </div>
                <?php
            }
        }

        public static function rs_common_html_function_for_module($label_id, $div_id, $active_class_name, $active_hyperlink_class_name, $module_name, $checkbox_id, $settings_id, $tab_name, $get_option_value) {
            ?>             
            <div class="rs_grid">
                <div id='<?php echo $div_id; ?>' class="<?php echo $active_class_name; ?>">                    
                    <div class="<?php echo $active_hyperlink_class_name; ?>">
                        <h1><?php echo $module_name; ?></h1>
                    </div>                    
                    <div class='bottom_sec'>
                        <label class="rs_switch_round" id="<?php echo $label_id; ?>">
                            <input type="checkbox" id="<?php echo $checkbox_id; ?>" <?php if ($get_option_value == 'yes') { ?> checked="checked" <?php } ?>>
                            <div class="rs_slider_round"></div>
                        </label>
                        <?php if ($get_option_value == 'yes') { ?>                                    
                            <a id='<?php echo $settings_id; ?>' style="display:block;" href="<?php echo admin_url('admin.php?page=rewardsystem_callback&tab=rewardsystem_modules&section=' . $tab_name); ?>" >Settings</a>
                        <?php } ?>
                    </div>
                </div>
            </div>                 
            <?php
        }

        public static function rs_common_ajax_function_to_activate_modules($label_id, $id, $div_id, $class_to_add, $class_to_remove, $settings_id, $meta_name, $tab_name) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#<?php echo $id; ?>').click(function () {
                        var checkboxvalue = jQuery(this).is(':checked') ? 'yes' : 'no';
                        var dataparam = ({
                            action: 'rs_ajax_to_activate',
                            checkboxvalue: checkboxvalue,
                            metaname: '<?php echo $meta_name; ?>'
                        });
                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                function (response) {
                                    if (response === 'yes') {
                                        jQuery('#<?php echo $tab_name; ?>').css('display', 'inline-block');
                                        jQuery('#<?php echo $div_id; ?>').removeClass('<?php echo $class_to_remove; ?>').addClass('<?php echo $class_to_add; ?>');
                                        jQuery('#<?php echo $label_id; ?>').after("<a id='<?php echo $settings_id; ?>' style='display:block;' href='<?php echo admin_url('admin.php?page=rewardsystem_callback&tab=rewardsystem_modules&section=' . $tab_name); ?>' >Settings</a>");
                                        jQuery('#<?php echo $settings_id; ?>').css('display', 'block');
                                    } else {
                                        jQuery('#<?php echo $tab_name; ?>').css('display', 'none');
                                        jQuery('#<?php echo $div_id; ?>').removeClass('<?php echo $class_to_add; ?>').addClass('<?php echo $class_to_remove; ?>');
                                        jQuery('#<?php echo $settings_id; ?>').css('display', 'none');
            <?php
            if ($tab_name == ('rewardsystem_reset' || 'rewardsystem_import_export' || 'rewardsystem_reports_in_csv')) {
                if (isset($_GET['section'])) {
                    ?>
                    window.location.href = "<?php echo admin_url('admin.php?page=rewardsystem_callback&tab=rewardsystem_modules'); ?>";
                    <?php
                }
            }
            ?>
                                    }
                                }, 'json');
                    });
                });
            </script>
            <?php
        }

        public static function rs_activate_modules_for_sumo() {
            $class_to_add = 'active_rs_box';
            $class_to_remove = 'rs-box';
            //Referral Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'referral_system_label', $checkbox_id = 'rs_referral_module_checkbox', $div_id = 'referral_module', $class_to_add, $class_to_remove, $settings_id = 'referral_settings', $meta_name = 'rs_referral_activated', $tab_name = 'rewardsystem_referral_system_module');
            //Product Purchase Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'product_purchase_label', $checkbox_id = 'rs_product_purchase_module_checkbox', $div_id = 'product_purchase_module', $class_to_add, $class_to_remove, $settings_id = 'product_purchase_settings', $meta_name = 'rs_product_purchase_activated', $tab_name = 'rewardsystem_product_purchase_module');
            //Social Reward Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'social_reward_label', $checkbox_id = 'rs_social_reward_module_checkbox', $div_id = 'social_reward_module', $class_to_add, $class_to_remove, $settings_id = 'social_reward_settings', $meta_name = 'rs_social_reward_activated', $tab_name = 'rewardsystem_socialrewards');
            //Gift Voucher Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'gift_voucher_label', $checkbox_id = 'rs_gift_voucher_module_checkbox', $div_id = 'gift_voucher_module', $class_to_add, $class_to_remove, $settings_id = 'gift_vocuher_settings', $meta_name = 'rs_gift_voucher_activated', $tab_name = 'rewardsystem_offline_online_rewards');
            //SMS Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'sms_label', $checkbox_id = 'rs_sms_module_checkbox', $div_id = 'sms_module', $class_to_add, $class_to_remove, $settings_id = 'sms_settings', $meta_name = 'rs_sms_activated', $tab_name = 'rewardsystem_sms');
            //Cashback Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'cashback_label', $checkbox_id = 'rs_cashback_module_checkbox', $div_id = 'cashback_module', $class_to_add, $class_to_remove, $settings_id = 'cashback_settings', $meta_name = 'rs_cashback_activated', $tab_name = 'rewardsystem_cashback_module');
            //Nominee Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'nominee_label', $checkbox_id = 'rs_nominee_module_checkbox', $div_id = 'nominee_module', $class_to_add, $class_to_remove, $settings_id = 'nominee_settings', $meta_name = 'rs_nominee_activated', $tab_name = 'rewardsystem_nominee');
            //Point URL Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'point_url_label', $checkbox_id = 'rs_point_url_module_checkbox', $div_id = 'point_url_module', $class_to_add, $class_to_remove, $settings_id = 'point_url_settings', $meta_name = 'rs_point_url_activated', $tab_name = 'rs_points_url');
            //Reward Point Gateway Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'gateway_label', $checkbox_id = 'rs_gateway_module_checkbox', $div_id = 'gateway_module', $class_to_add, $class_to_remove, $settings_id = 'reward_gateway_settings', $meta_name = 'rs_gateway_activated', $tab_name = 'rewardsystem_rewardpoints_gateway_module');
            //Send Points Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'send_points_label', $checkbox_id = 'rs_send_points_module_checkbox', $div_id = 'send_points_module', $class_to_add, $class_to_remove, $settings_id = 'send_points_settings', $meta_name = 'rs_send_points_activated', $tab_name = 'rewardsystem_sendpoints_module');
            //Import/Export Points Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'imp_exp_label', $checkbox_id = 'rs_imp_exp_module_checkbox', $div_id = 'imp_exp_module', $class_to_add, $class_to_remove, $settings_id = 'imp_exp_settings', $meta_name = 'rs_imp_exp_activated', $tab_name = 'rewardsystem_import_export');
            //Redeeming Points Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'redeeming_label', $checkbox_id = 'rs_redeeming_module_checkbox', $div_id = 'redeeming_module', $class_to_add, $class_to_remove, $settings_id = 'redeeming_settings', $meta_name = 'rs_redeeming_activated', $tab_name = 'rewardsystem_redeeming_module');
            //Reward Points for Actions Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'reward_action_label', $checkbox_id = 'rs_reward_action_module_checkbox', $div_id = 'reward_action_module', $class_to_add, $class_to_remove, $settings_id = 'reward_action_settings', $meta_name = 'rs_reward_action_activated', $tab_name = 'rewardsystem_reward_points_for_action');
            //Points Expiry Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'point_expiry_label', $checkbox_id = 'rs_point_expiry_module_checkbox', $div_id = 'point_expiry_module', $class_to_add, $class_to_remove, $settings_id = 'point_expiry_settings', $meta_name = 'rs_point_expiry_activated', $tab_name = 'rewardsystem_point_expiry_module');
            //Points Price Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'point_price_label', $checkbox_id = 'rs_point_price_module_checkbox', $div_id = 'point_price_module', $class_to_add, $class_to_remove, $settings_id = 'point_price_settings', $meta_name = 'rs_point_price_activated', $tab_name = 'rewardsystem_point_price_module');
            //Reports Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'reports_label', $checkbox_id = 'rs_report_module_checkbox', $div_id = 'report_module', $class_to_add, $class_to_remove, $settings_id = 'report_settings', $meta_name = 'rs_report_activated', $tab_name = 'rewardsystem_reports_in_csv');
            //Reset Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'reset_label', $checkbox_id = 'rs_reset_module_checkbox', $div_id = 'reset_module', $class_to_add, $class_to_remove, $settings_id = 'reset_settings', $meta_name = 'rs_reset_activated', $tab_name = 'rewardsystem_reset');
            //Email Module
            self::rs_common_ajax_function_to_activate_modules($label_id = 'email_label', $checkbox_id = 'rs_email_module_checkbox', $div_id = 'email_module', $class_to_add, $class_to_remove, $settings_id = 'email_settings', $meta_name = 'rs_email_activated', $tab_name = 'rewardsystem_email_module');
        }

        public static function rs_callback_to_activate() {
            if (isset($_POST['checkboxvalue'])) {
                $meta_name = $_POST['metaname'];
                update_option("$meta_name", $_POST['checkboxvalue']);
                echo json_encode($_POST['checkboxvalue']);
            }
            exit();
        }

        public static function rs_common_function_to_get_checkbox($get_option_value, $name_of_checkbox) {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label><?php _e('Enable/Disable', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-text">
                    <label class="rs_switch_round">
                        <input name="<?php echo $name_of_checkbox; ?>" value="yes" type="checkbox" id="<?php echo $name_of_checkbox; ?>" <?php if ($get_option_value == 'yes') { ?> checked="checked" <?php } ?>>
                        <div class="rs_slider_round"></div>
                    </label>
                </td>
            </tr>
            <?php
        }

        public static function check_trigger_button_rewardsystem() {
            ?>
            <script type='text/javascript'>
                jQuery(document).ready(function () {
                    jQuery('.rs_sumo_point_price_button').click(function () {
                        jQuery('.gif_rs_sumo_point_price_button').css('display', 'inline-block');
                        var whichproduct = jQuery('#rs_which_point_precing_product_selection').val();
                        var enabledisablepoints = jQuery('#rs_local_enable_disable_point_price').val();
                        var pointpricetype = jQuery('#rs_local_point_price_type').val();
                        var selectparticularproducts = jQuery('#rs_select_particular_products_for_point_price').val();
                        var pricepoints = jQuery('#rs_local_price_points').val();
                        var selectedcategories = jQuery('#rs_select_particular_categories_for_point_price').val();
                        var pointpricingtype = jQuery('#rs_local_point_pricing_type').val();
                        jQuery(this).attr('data-clicked', '1');
                        var dataclicked = jQuery(this).attr('data-clicked');
                        var dataparam = ({
                            action: 'previousproductpointpricevalue',
                            proceedanyway: dataclicked,
                            whichproduct: whichproduct,
                            enabledisablepoints: enabledisablepoints,
                            pointpricetype: pointpricetype,
                            selectedproducts: selectparticularproducts,
                            pricepoints: pricepoints,
                            selectedcategories: selectedcategories,
                            pointpricingtype: pointpricingtype,
                        });

                        function getDatapointprice(id) {
                            return jQuery.ajax({
                                type: 'POST',
                                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                data: ({action: 'rssplitajaxoptimizationforpointprice',
                                    ids: id,
                                    enabledisablepoints: enabledisablepoints,
                                    selectedproducts: selectparticularproducts,
                                    pointpricetype: pointpricetype,
                                    pricepoints: pricepoints,
                                    pointpricetype: pointpricetype,
                                            selectedcategories: selectedcategories,
                                    pointpricingtype: pointpricingtype,
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
                                    if (response !== 'success') {
                                        var j = 1;
                                        var i, j, temparray, chunk = 10;
                                        for (i = 0, j = response.length; i < j; i += chunk) {
                                            temparray = response.slice(i, i + chunk);
                                            getDatapointprice(temparray);
                                        }
                                        jQuery.when(getDatapointprice()).done(function (a1) {
                                            console.log('Ajax Done Successfully');
                                            jQuery('.submit .button-primary').trigger('click');
                                        });
                                    } else {
                                        var newresponse = response.replace(/\s/g, '');
                                        if (newresponse === 'success') {
                                            jQuery('.submit .button-primary').trigger('click');
                                        }
                                    }
                                }, 'json');
                        return false;

                    });



                    jQuery('.rs_sumo_reward_button').click(function () {                        
                        jQuery('.gif_rs_sumo_reward_button').css('display', 'inline-block');
                        var whichproduct = jQuery('#rs_which_product_selection').val();
                        var enabledisablereward = jQuery('#rs_local_enable_disable_reward').val();
                        var selectparticularproducts = jQuery('#rs_select_particular_products').val();
                        var selectedcategories = jQuery('#rs_select_particular_categories').val();
                        var rewardtype = jQuery('#rs_local_reward_type').val();
                        var rewardpoints = jQuery('#rs_local_reward_points').val();
                        var rewardpercent = jQuery('#rs_local_reward_percent').val();
                        var enabledisablereferralreward = jQuery('#rs_local_enable_disable_referral_reward').val();
                        var referralrewardtype = jQuery('#rs_local_referral_reward_type').val();
                        var referralrewardpoint = jQuery('#rs_local_referral_reward_point').val();
                        var referralrewardpercent = jQuery('#rs_local_referral_reward_percent').val();
                        var referralrewardtyperefer = jQuery('#rs_local_referral_reward_type_get_refer').val();
                        var referralpointforgettingrefer = jQuery('#rs_local_referral_reward_point_for_getting_referred').val();
                        var referralrewardpercentgettingrefer = jQuery('#rs_local_referral_reward_percent_for_getting_referred').val();

                        jQuery(this).attr('data-clicked', '1');
                        var dataclicked = jQuery(this).attr('data-clicked');
                        var dataparam = ({
                            action: 'previousproductvalue',
                            proceedanyway: dataclicked,
                            whichproduct: whichproduct,
                            enabledisablereward: enabledisablereward,
                            selectedproducts: selectparticularproducts,
                            selectedcategories: selectedcategories,
                            rewardtype: rewardtype,
                            rewardpoints: rewardpoints,
                            rewardpercent: rewardpercent,
                            enabledisablereferralreward:enabledisablereferralreward,
                            referralrewardtype: referralrewardtype,
                            referralrewardpoint: referralrewardpoint,
                            referralrewardpercent: referralrewardpercent,
                            referralrewardtyperefer: referralrewardtyperefer,
                            referralpointforgettingrefer: referralpointforgettingrefer,
                            referralrewardpercentgettingrefer: referralrewardpercentgettingrefer,
                        });
                        function getData(id) {
                            return jQuery.ajax({
                                type: 'POST',
                                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                data: ({action: 'rssplitajaxoptimization',
                                    ids: id,
                                    enabledisablereward: enabledisablereward,
                                    selectedproducts: selectparticularproducts,
                                    selectedcategories: selectedcategories,
                                    rewardtype: rewardtype,
                                    rewardpoints: rewardpoints,
                                    rewardpercent: rewardpercent,
                                    enabledisablereferralreward:enabledisablereferralreward,
                                    referralrewardtype: referralrewardtype,
                                    referralrewardpoint: referralrewardpoint,
                                    referralrewardpercent: referralrewardpercent,
                                    referralrewardtyperefer: referralrewardtyperefer,
                                    referralpointforgettingrefer: referralpointforgettingrefer,
                                    referralrewardpercentgettingrefer: referralrewardpercentgettingrefer,
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
                                    if (response !== 'success') {
                                        var j = 1;
                                        var i, j, temparray, chunk = 10;
                                        for (i = 0, j = response.length; i < j; i += chunk) {
                                            temparray = response.slice(i, i + chunk);
                                            getData(temparray);
                                        }
                                        jQuery.when(getData()).done(function (a1) {
                                            console.log('Ajax Done Successfully');
                                            jQuery('.submit .button-primary').trigger('click');
                                        });
                                    } else {
                                        var newresponse = response.replace(/\s/g, '');
                                        if (newresponse === 'success') {
                                            jQuery('.submit .button-primary').trigger('click');
                                        }
                                    }
                                }, 'json');
                        return false;
                    });
                    jQuery('.rs_sumo_reward_button_social').click(function () {
                        jQuery('.gif_rs_sumo_reward_button_social').css('display', 'inline-block');
                        var whichproduct = jQuery('#rs_which_social_product_selection').val();
                        var enabledisablereward = jQuery('#rs_local_enable_disable_social_reward').val();
                        var selectparticularproducts = jQuery('#rs_select_particular_social_products').val();
                        var selectedcategories = jQuery('#rs_select_particular_social_categories').val();
                        var rewardtypefacebook = jQuery('#rs_local_reward_type_for_facebook').val();
                        var facebookrewardpoints = jQuery('#rs_local_reward_points_facebook').val();
                        var facebookrewardpercent = jQuery('#rs_local_reward_percent_facebook').val();
                        var rewardtypefacebook_share = jQuery('#rs_local_reward_type_for_facebook_share').val();
                        var facebookrewardpoints_share = jQuery('#rs_local_reward_points_facebook_share').val();
                        var facebookrewardpercent_share = jQuery('#rs_local_reward_percent_facebook_share').val();
                        var rewardtypetwitter = jQuery('#rs_local_reward_type_for_twitter').val();
                        var twitterrewardpoints = jQuery('#rs_local_reward_points_twitter').val();
                        var twitterrewardpercent = jQuery('#rs_local_reward_percent_twitter').val();
                        var rewardtypegoogle = jQuery('#rs_local_reward_type_for_google').val();
                        var googlerewardpoints = jQuery('#rs_local_reward_points_google').val();
                        var googlerewardpercent = jQuery('#rs_local_reward_percent_google').val();
                        var rewardtypevk = jQuery('#rs_local_reward_type_for_vk').val();
                        var vkrewardpoints = jQuery('#rs_local_reward_points_vk').val();
                        var vkrewardpercent = jQuery('#rs_local_reward_percent_vk').val();
                        var rewardtypetwitter_follow = jQuery('#rs_local_reward_type_for_twitter_follow').val();
                        var twitterrewardpoints_follow = jQuery('#rs_local_reward_points_twitter_follow').val();
                        var twitterrewardpercent_follow = jQuery('#rs_local_reward_percent_twitter_follow').val();
                        var rewardtypeinstagram = jQuery('#rs_local_reward_type_for_instagram').val();
                        var instagramrewardpoints = jQuery('#rs_local_reward_points_instagram').val();
                        var instagramrewardpercent = jQuery('#rs_local_reward_percent_instagram').val();
                        var rewardtypeok_follow = jQuery('#rs_local_reward_type_for_ok_follow').val();
                        var okrewardpoints_follow = jQuery('#rs_local_reward_points_ok_follow').val();
                        var okrewardpercent_follow = jQuery('#rs_local_reward_percent_ok_follow').val();
                        jQuery(this).attr('data-clicked', '1');
                        var dataclicked = jQuery(this).attr('data-clicked');
                        var dataparam = ({
                            action: 'previoussocialproductvalue',
                            proceedanyway: dataclicked,
                            whichproduct: whichproduct,
                            enabledisablereward: enabledisablereward,
                            selectedproducts: selectparticularproducts,
                            selectedcategories: selectedcategories,
                            rewardtypefacebook: rewardtypefacebook,
                            facebookrewardpoints: facebookrewardpoints,
                            facebookrewardpercent: facebookrewardpercent,
                            rewardtypefacebook_share: rewardtypefacebook_share,
                            facebookrewardpoints_share: facebookrewardpoints_share,
                            facebookrewardpercent_share: facebookrewardpercent_share,
                            rewardtypetwitter: rewardtypetwitter,
                            twitterrewardpoints: twitterrewardpoints,
                            twitterrewardpercent: twitterrewardpercent,
                            rewardtypegoogle: rewardtypegoogle,
                            googlerewardpoints: googlerewardpoints,
                            googlerewardpercent: googlerewardpercent,
                            rewardtypevk: rewardtypevk,
                            vkrewardpoints: vkrewardpoints,
                            vkrewardpercent: vkrewardpercent,
                            rewardtypetwitter_follow: rewardtypetwitter_follow,
                            twitterrewardpoints_follow: twitterrewardpoints_follow,
                            twitterrewardpercent_follow: twitterrewardpercent_follow,
                            rewardtypeinstagram: rewardtypeinstagram,
                            instagramrewardpoints: instagramrewardpoints,
                            instagramrewardpercent: instagramrewardpercent,
                            rewardtypeok_follow: rewardtypeok_follow,
                            okrewardpoints_follow: okrewardpoints_follow,
                            okrewardpercent_follow: okrewardpercent_follow,
                        });
                        function getDataSocial(id) {
                            return jQuery.ajax({
                                type: 'POST',
                                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                data: ({action: 'rssplitajaxoptimizationsocial', ids: id, enabledisablereward: enabledisablereward,
                                    selectedproducts: selectparticularproducts,
                                    selectedcategories: selectedcategories,
                                    rewardtypefacebook: rewardtypefacebook,
                                    facebookrewardpoints: facebookrewardpoints,
                                    facebookrewardpercent: facebookrewardpercent,
                                    rewardtypefacebook_share: rewardtypefacebook_share,
                                    facebookrewardpoints_share: facebookrewardpoints_share,
                                    facebookrewardpercent_share: facebookrewardpercent_share,
                                    rewardtypetwitter: rewardtypetwitter,
                                    twitterrewardpoints: twitterrewardpoints,
                                    twitterrewardpercent: twitterrewardpercent,
                                    rewardtypegoogle: rewardtypegoogle,
                                    googlerewardpoints: googlerewardpoints,
                                    googlerewardpercent: googlerewardpercent,
                                    rewardtypevk: rewardtypevk,
                                    vkrewardpoints: vkrewardpoints,
                                    vkrewardpercent: vkrewardpercent,
                                    rewardtypetwitter_follow: rewardtypetwitter_follow,
                                    twitterrewardpoints_follow: twitterrewardpoints_follow,
                                    twitterrewardpercent_follow: twitterrewardpercent_follow,
                                    rewardtypeinstagram: rewardtypeinstagram,
                                    instagramrewardpoints: instagramrewardpoints,
                                    instagramrewardpercent: instagramrewardpercent,
                                    rewardtypeok_follow: rewardtypeok_follow,
                                    okrewardpoints_follow: okrewardpoints_follow,
                                    okrewardpercent_follow: okrewardpercent_follow,
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
                                    if (response !== 'success') {
                                        var j = 1;
                                        var i, j, temparray, chunk = 10;
                                        for (i = 0, j = response.length; i < j; i += chunk) {
                                            temparray = response.slice(i, i + chunk);
                                            getDataSocial(temparray);
                                        }
                                        jQuery.when(getDataSocial()).done(function (a1) {
                                            console.log('Ajax Done Successfully');
                                            jQuery('.submit .button-primary').trigger('click');
                                        });
                                    } else {
                                        var newresponse = response.replace(/\s/g, '');
                                        if (newresponse === 'success') {
                                            jQuery('.submit .button-primary').trigger('click');
                                        }
                                    }
                                }, 'json');
                        return false;
                    });
                    jQuery('.rs_sumo_undo_reward').click(function () {
                        jQuery(this).attr('data-clicked', '0');
                        var dataclicked = jQuery(this).attr('data-clicked');
                        var dataparam = ({
                            action: 'previousproductvalue',
                            proceedanyway: dataclicked,
                        });
                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                function (response) {
                                    var newresponse = response.replace(/\s/g, '');
                                    if (newresponse === 'success') {
                                        jQuery('.rs_sumo_rewards').fadeIn();
                                        jQuery('.rs_sumo_rewards').html('Successfully Disabled from Existing Products');
                                        jQuery('.rs_sumo_rewards').fadeOut(5000);
                                    }
                                });
                        return false;
                    });
                });
            </script>
            <?php
        }

        public static function add_meta_box_for_earned() {
            add_meta_box('order_earned_points', 'Earned Point and Redeem Points For Current Order', array('RSModulesTab', 'add_meta_box_to_earned_and_redeem_points'), 'shop_order', 'normal', 'low');
        }

        public static function add_meta_box_to_earned_and_redeem_points($order) {
            if (isset($_GET['post'])) {
                $order = $_GET['post'];
                $earned_redeemed_message = array();
                $replacemsgforearnedpoints = '';
                $replacemsgforredeempoints = '';
                $earned_redeemed_message   = self::get_earned_redeemed_points_message ( $order ) ;
                if ( is_array ( $earned_redeemed_message ) ) {
                    foreach ( $earned_redeemed_message as $replacemsgforearnedpoints => $replacemsgforredeempoints ) {
                        $replacemsgforearnedpoints = $replacemsgforearnedpoints ;
                        $replacemsgforredeempoints = $replacemsgforredeempoints ;
                    }
                    if (get_option('rs_enable_msg_for_earned_points') == 'yes') {
                        if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
                            ?>
                            <table width="100%" style=" border-radius: 10px; border-style: solid; border-color: #dfdfdf;">
                                <tr><td style="text-align:center; background-color:#F1F1F1"><h3>Earned Points</h3></td><td style="text-align:center;background-color:#F1F1F1"><h3>Redeem Points</h3></td></tr>
                                <tr><td style="text-align:center"><?php echo $replacemsgforearnedpoints; ?></td><td style="text-align:center"><?php echo $replacemsgforredeempoints; ?></td></tr>
                            </table>

                            <?php
                        } else {
                            ?>
                            <table width="100%" style=" border-radius: 10px; border-style: solid; border-color: #dfdfdf;">
                                <tr><td style="text-align:center; background-color:#F1F1F1"><h3>Earned Points</h3></td></tr>
                                <tr><td style="text-align:center"><?php echo $replacemsgforearnedpoints; ?></td></tr>
                            </table>

                            <?php
                        }
                    } else {
                        if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
                            ?>
                            <table width="100%" style=" border-radius: 10px; border-style: solid; border-color: #dfdfdf;">
                                <tr><td style="text-align:center;background-color:#F1F1F1"><h3>Redeem Points</h3></td></tr>
                                <tr><td style="text-align:center"><?php echo $replacemsgforredeempoints; ?></td></tr>
                            </table>

                            <?php
                        }
                    }
                 }
            }
         }

         public static function get_earned_redeemed_points_message ($order) {
                global $wpdb;
                $overall_earned_totals = array();
                $overall_redeem_totals = array();
                $revised_earned_totals = array();
                $revised_redeem_totals = array();
                $orderstatuslistforredeem = array();
                $totalearnedvalue = "";
                $totalredeemvalue = '';
                $table_name = $wpdb->prefix . 'rsrecordpoints';
                $orderid = $order;
                $order_obj = wc_get_order($orderid);
                $ord_obj = rs_get_order_obj($order_obj);
                $user_id = $ord_obj['order_userid'];
                if ($user_id != '' && $user_id != '0') {
                    $orderstatus = $ord_obj['order_status'];
                    $order_status = str_replace('wc-', '', $orderstatus);
                    $getoverallearnpoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE orderid = $orderid and userid=$user_id and checkpoints != 'RVPFRP'and  checkpoints != 'RVPFRPG'", ARRAY_A);
                    foreach ($getoverallearnpoints as $getoverallearnpointss) {
                        $overall_earned_totals[] = $getoverallearnpointss['earnedpoints'];
                    }
                    $getoverallredeempoints = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE orderid = $orderid and userid=$user_id and checkpoints != 'RVPFPPRP'", ARRAY_A);
                    foreach ($getoverallredeempoints as $getoverallredeempointss) {
                        $overall_redeem_totals[] = $getoverallredeempointss['redeempoints'];
                    }
                    $getrevisedearnedpoint = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE checkpoints = 'RVPFPPRP' and userid=$user_id and orderid = $orderid", ARRAY_A);
                    foreach ($getrevisedearnedpoint as $getrevisedearnedpoints) {
                        $revised_earned_totals[] = $getrevisedearnedpoints['redeempoints'];
                    }
                    $getrevisedredeempoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE orderid = $orderid and userid=$user_id and checkpoints != 'PPRP' and checkpoints != 'PPRRPG' and checkpoints != 'RRP' and checkpoints != 'RPG' and checkpoints != 'RPBSRP'", ARRAY_A);
                    foreach ($getrevisedredeempoints as $getrevisedredeempointss) {
                        $revised_redeem_totals[] = $getrevisedredeempointss['earnedpoints'];
                    }
                    $orderstatuslistforredeem = get_option('rs_order_status_control_redeem');
                    if (in_array($order_status, $orderstatuslistforredeem)) {
                        RSPointExpiry::update_redeem_point_for_user($orderid);
                    }
                    if (get_option('rs_enable_msg_for_earned_points') == 'yes') {
                        if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
                            $totalearnedvalue = array_sum($overall_earned_totals) - array_sum($revised_earned_totals);
                            $totalredeemvalue = array_sum($overall_redeem_totals) - array_sum($revised_redeem_totals);

                            $msgforearnedpoints = get_option('rs_msg_for_earned_points');
                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                            $replacemsgforearnedpoints = str_replace('[earnedpoints]', $totalearnedvalue != "" ? round_off_type($totalearnedvalue) : "0", $msgforearnedpoints);

                            $msgforredeempoints = get_option('rs_msg_for_redeem_points');
                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                            $replacemsgforredeempoints = str_replace('[redeempoints]', $totalredeemvalue != "" ? round_off_type($totalredeemvalue) : "0", $msgforredeempoints);
                        } else {
                            $totalearnedvalue = array_sum($overall_earned_totals) - array_sum($revised_earned_totals);

                            $msgforearnedpoints = get_option('rs_msg_for_earned_points');
                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                            $replacemsgforearnedpoints = str_replace('[earnedpoints]', $totalearnedvalue != "" ? round_off_type($totalearnedvalue) : "0", $msgforearnedpoints);

                            $msgforredeempoints = get_option('rs_msg_for_redeem_points');
                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                            $replacemsgforredeempoints = str_replace('[redeempoints]', $totalredeemvalue != "" ? round_off_type($totalredeemvalue) : "0", $msgforredeempoints);
                        }
                    } else {
                        $msgforearnedpoints = get_option('rs_msg_for_earned_points');
                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                        $replacemsgforearnedpoints = str_replace('[earnedpoints]', $totalearnedvalue != "" ? round_off_type($totalearnedvalue) : "0", $msgforearnedpoints);

                        if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
                            $totalredeemvalue = array_sum($overall_redeem_totals) - array_sum($revised_redeem_totals);

                            $msgforredeempoints = get_option('rs_msg_for_redeem_points');
                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                            $replacemsgforredeempoints = str_replace('[redeempoints]', $totalredeemvalue != "" ? round_off_type($totalredeemvalue) : "0", $msgforredeempoints);
                        } else {
                            $msgforredeempoints = get_option('rs_msg_for_redeem_points');
                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                            $replacemsgforredeempoints = str_replace('[redeempoints]', $totalredeemvalue != "" ? round_off_type($totalredeemvalue) : "0", $msgforredeempoints);
                        }
                    }
                    $earned_redeemed_message[$replacemsgforearnedpoints] = $replacemsgforredeempoints ;
                   
                }
                 return $earned_redeemed_message;
            }
    }

    RSModulesTab::init();
}