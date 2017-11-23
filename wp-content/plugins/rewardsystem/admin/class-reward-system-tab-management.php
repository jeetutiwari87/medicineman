<?php
/*
 * Reward System Tab Management
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('RSTabManagement')) {

    class RSTabManagement {

        public static function init() {
            add_action('admin_menu', array(__CLASS__, 'add_submenu_woocommerce'));
            if (isset($_GET['page']) && $_GET['page'] == 'rewardsystem_callback') {
                add_filter('set-screen-option', array(__CLASS__, 'rs_set_screen_option_value'), 10, 3);
            }
            add_filter('plugin_action_links_' . REWARDSYSTEM_PLUGIN_BASENAME, array(__CLASS__, 'rs_plugin_action'));
            add_filter('plugin_row_meta', array(__CLASS__, 'rs_plugin_row_meta'), 10, 2);
            add_action('woocommerce_sections_rewardsystem_modules', array(__CLASS__, 'rs_function_to_get_subtab'));
        }

        public static function add_submenu_woocommerce() {
            global $my_admin_page;
            $name = get_option('rs_brand_name');
            if ($name == '') {
                $name = 'SUMO Reward Points';
            }
            $my_admin_page = add_submenu_page('woocommerce', $name, $name, 'manage_woocommerce', 'rewardsystem_callback', array('RSTabManagement', 'rewardsystem_tab_management'));
            add_action('load-' . $my_admin_page, array('RSTabManagement', 'rs_function_to_display_screen_option'));
        }

        public static function rewardsystem_tab_management() {
            global $woocommerce, $woocommerce_settings, $current_section, $current_tab;
            do_action('woocommerce_rs_settings_start');
            $current_tab = ( empty($_GET['tab']) ) ? 'rewardsystem_general' : sanitize_text_field(urldecode($_GET['tab']));
            $current_section = ( empty($_REQUEST['section']) ) ? '' : sanitize_text_field(urldecode($_REQUEST['section']));
            if (!empty($_POST['save'])) {
                if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-settings'))
                    die(__('Action failed. Please refresh the page and retry.', 'rewardsystem'));

                if (!$current_section) {
                    switch ($current_tab) {
                        default :
                            if (isset($woocommerce_settings[$current_tab]))
                                woocommerce_update_options($woocommerce_settings[$current_tab]);
// Trigger action for tab
                            do_action('woocommerce_update_options_' . $current_tab);
                            break;
                    }
                    do_action('woocommerce_update_options');
                } else {
// Save section onlys
                    do_action('woocommerce_update_options_' . $current_tab . '_' . $current_section);
                }

// Clear any unwanted data
                delete_transient('woocommerce_cache_excluded_uris');
// Redirect back to the settings page
                $redirect = add_query_arg(array('saved' => 'true'));

                if (isset($_POST['subtab'])) {
                    wp_safe_redirect($redirect);
                    exit;
                }
            }
            if (isset($_GET['rs_background_process']) && $_GET['rs_background_process'] == 'yes') {
                $obj = new FP_Updating_Process_for_RS();
                $obj->fp_display_progress_bar();
                exit();
            }
// Get any returned messages
            if (!empty($_POST['reset'])) {
                do_action('fp_action_to_reset_module_settings_' . $current_section);
                do_action('fp_action_to_reset_settings_' . $current_tab);
            }
            $error = ( empty($_GET['wc_error']) ) ? '' : urldecode(stripslashes($_GET['wc_error']));
            $message = ( empty($_GET['wc_message']) ) ? '' : urldecode(stripslashes($_GET['wc_message']));

            if ($error || $message) {

                if ($error) {
                    echo '<div id="message" class="error fade"><p><strong>' . esc_html($error) . '</strong></p></div>';
                } else {
                    echo '<div id="message" class="updated fade"><p><strong>' . esc_html($message) . '</strong></p></div>';
                }
            } elseif (!empty($_GET['saved'])) {

                echo '<div id="message" class="updated fade"><p><strong>' . __('Your settings have been saved.', 'rewardsystem') . '</strong></p></div>';
            }
            ?>
            <div class="wrap woocommerce rs_main_wrapper">
                <form method="post" id="mainform" action="" enctype="multipart/form-data" class="rs_main">
                    <div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
                    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper rs_tab_design">
                        <div class="welcome_header" >
                            <div class="welcome_title" >
                                <h1> <strong>SUMO Reward Points</strong></h1>
                            </div>
                            <div class="branding_logo" >
                                <a href="http://fantasticplugins.com/" target="_blank" ><img src="<?php echo REWARDSYSTEM_PLUGIN_DIR_URL; ?>/admin/images/Fantastic-Plugins-final-Logo.png" alt="" /></a>
                            </div>
                        </div>
                        <ul>
                        <?php
                        $tabs = '';
                        $tabs = apply_filters('woocommerce_rs_settings_tabs_array', $tabs);
                        foreach ($tabs as $name => $label) {
                            $tabtoshow = array('rewardsystem_general', 'rewardsystem_user_reward_points', 'rewardsystem_add_remove_points', 'rewardsystem_message', 'rewardsystem_masterlog', 'rewardsystem_localization', 'rewardsystem_modules', 'rewardsystem_shortcode', 'rewardsystem_advanced', 'rewardsystem_support', 'rewardsystem_custom_css');
                            if (in_array($name, $tabtoshow)) {
                                echo '<li><a href="' . admin_url('admin.php?page=rewardsystem_callback&tab=' . $name) . '" class="nav-tab ';
                                if ($current_tab == $name)
                                    echo 'nav-tab-active';
                                echo '">' . $label . '</a></li>';
                            }
                        }
                        do_action('woocommerce_rs_settings_tabs');
                        ?></ul><?php
                        do_action('woocommerce_sections_' . $current_tab);
                        ?>
                    </h2>
                    <?php
                    switch ($current_tab) :
                        default :
                            do_action('woocommerce_rs_settings_tabs_' . $current_tab);
                            break;
                    endswitch;
                    ?>
                    <p class="submit sumo_reward_points">
                        <?php if (!isset($GLOBALS['hide_save_button'])) : ?>
                            <input name="save" class="button-primary rs_save_btn" type="submit" value="<?php _e('Save changes', 'rewardsystem'); ?>" />
                        <?php endif; ?>
                        <input type="hidden" name="subtab" id="last_tab" />
                        <?php wp_nonce_field('woocommerce-settings', '_wpnonce', true, true); ?>
                    </p>
                </form>
                <?php
                if (get_option('rs_show_hide_reset_all') == '1') {
                    ?>
                    <form method="post" id="mainforms" action="" enctype="multipart/form-data" style="float: left; margin-top: -59px; margin-left: 170px;">
                        <input id="resettab" name="reset" class="button-secondary rs_reset" type="submit" value="<?php _e('Reset', 'rewardsystem'); ?>"/>
                        <?php wp_nonce_field('woocommerce-reset_settings', '_wpnonce', true, true); ?>             
                    </form>
                    <?php
                }
                ?>
            </div> 
            <?php
        }

        public static function rs_function_to_get_subtab() {
            global $current_section;
            $sections = self::rs_function_to_get_list_of_modules($value = 1);
            echo '<ul class="subsubsub rs_sub_tab_design">';
            $array_keys = array_keys($sections);
            foreach ($sections as $id => $label) {
                $subtabs = self::rs_function_to_get_list_of_modules($value = 2);
                if ($subtabs[$id] === 'yes') {
                    echo '<li class="rs_sub_tab_li" id=' . $id . ' style="display:inline-block"><a href="' . admin_url('admin.php?page=rewardsystem_callback&tab=rewardsystem_modules&section=' . sanitize_title($id)) . '" class="' . ( $current_section === $id ? 'current' : '' ) . '">' . $label . ( end($array_keys) == $id ? '' : ' |' ) . '</a></li>';
                } else {
                    echo '<li class="rs_sub_tab_li" id=' . $id . ' style="display:none"><a href="' . admin_url('admin.php?page=rewardsystem_callback&tab=rewardsystem_modules&section=' . sanitize_title($id)) . '" class="' . ( $current_section === $id ? 'current' : '' ) . '">' . $label . ( end($array_keys) == $id ? '' : ' |' ) . '</a></li>';
                }
            }
            echo '</ul><br class="clear" />';
        }

        public static function rs_function_to_get_list_of_modules($value) {
            $get_option_values = array(
                'rewardsystem_product_purchase_module' => $value == 1 ? 'Product Purchase' : get_option('rs_product_purchase_activated'),
                'rewardsystem_referral_system_module' => $value == 1 ? 'Referral System' : get_option('rs_referral_activated'),
                'rewardsystem_socialrewards' => $value == 1 ? 'Social Reward Points' : get_option('rs_social_reward_activated'),
                'rewardsystem_reward_points_for_action' => $value == 1 ? 'Action Reward Points' : get_option('rs_reward_action_activated'),
                'rewardsystem_point_expiry_module' => $value == 1 ? 'Points Expiry' : get_option('rs_point_expiry_activated'),
                'rewardsystem_redeeming_module' => $value == 1 ? 'Redeeming Points' : get_option('rs_redeeming_activated'),
                'rewardsystem_point_price_module' => $value == 1 ? 'Points Price' : get_option('rs_point_price_activated'),
                'rewardsystem_email_module' => $value == 1 ? 'Email' : get_option('rs_email_activated'),
                'rewardsystem_offline_online_rewards' => $value == 1 ? 'Gift Voucher' : get_option('rs_gift_voucher_activated'),
                'rewardsystem_sms' => $value == 1 ? 'SMS' : get_option('rs_sms_activated'),
                'rewardsystem_cashback_module' => $value == 1 ? 'Cash Back' : get_option('rs_cashback_activated'),
                'rewardsystem_nominee' => $value == 1 ? 'Nominee' : get_option('rs_nominee_activated'),
                'rs_points_url' => $value == 1 ? 'Point URL' : get_option('rs_point_url_activated'),
                'rewardsystem_rewardpoints_gateway_module' => $value == 1 ? 'Reward Points Payment Gateway' : get_option('rs_gateway_activated'),
                'rewardsystem_sendpoints_module' => $value == 1 ? 'Send Points' : get_option('rs_send_points_activated'),
                'rewardsystem_import_export' => $value == 1 ? 'Import/Export Points' : get_option('rs_imp_exp_activated'),
                'rewardsystem_reports_in_csv' => $value == 1 ? 'Reports' : get_option('rs_report_activated'),
                'rewardsystem_reset' => $value == 1 ? 'Reset' : get_option('rs_reset_activated'),
            );
            return $get_option_values;
        }

        public static function rs_function_to_display_screen_option() {
            if (isset($_GET['tab'])) {
                $array = array(
                    'rewardsystem_offline_online_rewards' => $_GET['tab'] == 'rewardsystem_offline_online_rewards',
                    'rewardsystem_masterlog' => $_GET['tab'] == 'rewardsystem_masterlog',
                    'rewardsystem_nominee' => $_GET['tab'] == 'rewardsystem_nominee',
                    'rewardsystem_referral_system_module' => $_GET['tab'] == 'rewardsystem_referral_system_module',
                    'rewardsystem_user_reward_points' => $_GET['tab'] == 'rewardsystem_user_reward_points',
                    'rs_points_url' => $_GET['tab'] == 'rs_points_url',
                    'rewardsystem_sendpoints_module' => $_GET['tab'] == 'rewardsystem_sendpoints_module',
                    'rewardsystem_modules' => $_GET['tab'] == 'rewardsystem_modules',
                );
                if (is_array($array) && !empty($array)) {
                    foreach ($array as $option_name => $tab_name) {
                        if ($tab_name) {
                            $screen = get_current_screen();
                            $args = array(
                                'label' => __('Number Of Items Per Page', 'rewardsystem'),
                                'default' => 10,
                                'option' => $option_name
                            );
                            add_screen_option('per_page', $args);
                        }
                    }
                }
            }
        }

        public static function rs_set_screen_option_value($status, $option, $value) {
            if ('rewardsystem_offline_online_rewards' == $option)
                return $value;

            if ('rewardsystem_masterlog' == $option)
                return $value;

            if ('rewardsystem_nominee' == $option)
                return $value;

            if ('rewardsystem_referral_system_module' == $option)
                return $value;

            if ('rewardsystem_user_reward_points' == $option)
                return $value;

            if ('rs_points_url' == $option)
                return $value;

            if ('rewardsystem_sendpoints_module' == $option)
                return $value;

            if ('rewardsystem_modules' == $option)
                return $value;
        }

        public static function rs_get_value_for_no_of_item_perpage($user, $screen) {
            $screen_option = $screen->get_option('per_page', 'option');
            $per_page = get_user_meta($user, $screen_option, true);
            if (empty($per_page) || $per_page < 1) {
                $per_page = $screen->get_option('per_page', 'default');
            }
            return $per_page;
        }
         //common function to check field ids
         public static function rs_function_stop_mail_when_reset ( $field_id , $setting_array ) {
            if ( $setting_array[ 'newids' ] == "$field_id" && $setting_array[ 'default' ] != get_option ( "$field_id" ) ) {
                return true;
            }
            return false;
        }
        public static function rs_function_to_reset_setting($settings , $module_flag = '' ) {
            $x=0;
            foreach ($settings as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                       //check only for email module
                    if($module_flag=='rsemailmodule') {
                        if ( self::rs_function_stop_mail_when_reset ( 'rs_mail_cron_type' , $setting) ) {
                            $x ++ ;
                        }
                        if ( self::rs_function_stop_mail_when_reset ( 'rs_mail_cron_time' , $setting ) ) {
                            $x ++ ;
                        }
                    }
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
                 //reseting a cron values when tab Reset.
                if($module_flag=='rsemailmodule' && $x > 0) {
                        RSInstall::create_cron_job () ;
                }
            }
        }

        /**
         * Show action links on the plugin screen.
         *
         * @param	mixed $links Plugin Action links
         * @return	array
         */
        public static function rs_plugin_action($links) {
            $action_links = array(
                'rsaboutpage' => '<a href="' . admin_url('admin.php?page=rewardsystem_callback') . '" aria-label="' . esc_attr__('Settings', 'rewardsystem') . '">' . esc_attr__('Settings', 'rewardsystem') . '</a>',
            );
            return array_merge($action_links, $links);
        }

        /**
         * Show row meta on the plugin screen.
         *
         * @param	mixed $links Plugin Row Meta
         * @param	mixed $file  Plugin Base file
         * @return	array
         */
        public static function rs_plugin_row_meta($links, $file) {
            if (REWARDSYSTEM_PLUGIN_BASENAME == $file) {
                $redirect_url = add_query_arg(array('page' => 'sumo-reward-points-welcome-page'), admin_url('admin.php'));
                $row_meta = array(
                    'rs_about' => '<a href="' . $redirect_url . '" aria-label="' . esc_attr__('About', 'rewardsystem') . '">' . esc_html__('About', 'rewardsystem') . '</a>',
                    'rs_support' => '<a href="http://fantasticplugins.com/support/" aria-label="' . esc_attr__('Support', 'rewardsystem') . '">' . esc_html__('Support', 'rewardsystem') . '</a>',
                );

                return array_merge($links, $row_meta);
            }
            return (array) $links;
        }

    }

    RSTabManagement::init();
}