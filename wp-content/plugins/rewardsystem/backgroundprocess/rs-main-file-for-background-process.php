<?php
if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('RS_Main_Function_for_Background_Process')) {

    /**
     * RS_Main_Function_for_Background_Process Class.
     */
    class RS_Main_Function_for_Background_Process {

        public static $update_simple_product;
        public static $update_variable_product;
        public static $update_category;
        public static $rs_progress_bar;

        public static function init() {
            if (self::fp_rs_upgrade_file_exists()) {
                if (!class_exists('WP_Async_Request'))
                    include_once(untrailingslashit(WP_PLUGIN_DIR) . '/woocommerce/includes/libraries/wp-async-request.php');

                if (!class_exists('WP_Background_Process'))
                    include_once(untrailingslashit(WP_PLUGIN_DIR) . '/woocommerce/includes/libraries/wp-background-process.php');

                if (!class_exists('RS_Update_for_Simple_Product'))
                    include_once('inc/rs-update-for-simple-product.php');

                if (!class_exists('RS_Update_for_Variable_Product'))
                    include_once('inc/rs-update-for-varaible-product.php');

                if (!class_exists('RS_Update_for_Category'))
                    include_once('inc/rs-update-for-category.php');

                if (!class_exists('FP_Updating_Process_for_RS'))
                    include_once('inc/class-fp-rs-updating-process.php');

                add_action('plugins_loaded', array(__CLASS__, 'fp_rs_background_process_redirect'));
                add_action('wp_ajax_rs_database_upgrade_process', array(__CLASS__, 'rs_initiate_to_background_process'));

                self::$update_simple_product = new RS_Update_for_Simple_Product();
                self::$update_variable_product = new RS_Update_for_Variable_Product();
                self::$update_category = new RS_Update_for_Category();
                self::$rs_progress_bar = new FP_Updating_Process_for_RS();
            }
            add_action('admin_head', array(__CLASS__, 'rs_display_notice_in_top'));
        }

        /**
         * Initializing the Welcome Page
         * 
         */
        public static function fp_rs_background_process_redirect() {
            if (!get_transient('fp_rs_background_process_transient')) {
                return;
            }
            delete_transient('fp_rs_background_process_transient');
            delete_option('rs_simple_product_background_updater_offset');
            FP_WooCommerce_Log::log('v18.0 Upgrade Started');
            self::rs_update_simple_product();
            $admin_url = admin_url('admin.php');
            $redirect_url = esc_url_raw(add_query_arg(array('page' => 'rewardsystem_callback', 'rs_background_process' => 'yes'), $admin_url));
            wp_safe_redirect($redirect_url);
        }

        /*
         * Display when required some updates for this plugin
         */

        public static function rs_display_notice_in_top() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'rspointexpiry';
            if ((get_option('rs_upgrade_success') != 'yes' ) && (get_option('rs_no_data_to_upgrade') != 'yes') && (!RSInstall::rs_check_table_exists($table_name) && (get_option('rs_new_update_user') != true))) {
                if (self::fp_rs_upgrade_file_exists()) {
                    $admin_url = admin_url('admin.php');
                    $link = "<a id='rs_display_notice' data-methd='cron' href='#'>Click here</a>";
                    $redirect_url = esc_url_raw(add_query_arg(array('page' => 'rewardsystem_callback', 'rs_background_process' => 'yes'), $admin_url));
                    ?>
                    <div id="rs_message" class="notice notice-warning"><p><strong><?php _e("SUMO Reward Points requires Database Upgrade, $link to proceed with the Upgrade", 'rewardsystem'); ?></strong></p></div>
                    <div id="rs_updating_message" class="updated notice-warning" style="display:none"><p><strong> <?php _e("SUMO Reward Points Data Update - Your database is being updated in the background.", 'rewardsystem'); ?></strong></p></div>
                    <script type="text/javascript">
                        jQuery(function () {
                            jQuery(document).on('click', '#rs_display_notice', function () {
                                var rsconfirm = confirm("It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?");
                                if (rsconfirm === true) {
                                    var data = {action: "rs_database_upgrade_process"};
                                    jQuery.ajax({
                                        type: "POST",
                                        url: ajaxurl,
                                        data: data,
                                    }).done(function (response) {
                                        window.location.href = '<?php echo $redirect_url ?>';
                                    });
                                }
                                return false;
                            });
                        });
                    </script>
                    <?php
                } else {
                    $support_link = '<a href="http://fantasticplugins.com/support">' . __('Support', 'rewardsystem') . '</a>';
                    ?><div id="message" class="notice notice-warning"><p><strong> <?php _e("Upgrade to v18.0 has failed. Please contact our $support_link", 'recoverabandoncart'); ?></strong></p></div><?php
                }
            }
        }

        /**
         * Update Simple Product Settings 
         */
        public static function rs_update_simple_product($offset = 0, $limit = 1000) {
            global $wpdb;
            $ids = $wpdb->get_results("SELECT DISTINCT ID FROM {$wpdb->posts} as p INNER JOIN {$wpdb->postmeta} as p1 ON p.ID=p1.post_id WHERE p.post_type = 'product' AND p1.meta_key = '_rewardsystemcheckboxvalue' AND p1.meta_value = 'yes' LIMIT $offset,$limit");
            if (is_array($ids) && !empty($ids)) {
                foreach ($ids as $id) {
                    self::$update_simple_product->push_to_queue($id->ID);
                }
            } else {
                self::$update_simple_product->push_to_queue('rs_data');
            }
            update_option('rs_simple_product_background_updater_offset', $limit + $offset);

            if ($offset == 0)
                FP_WooCommerce_Log::log('Simple Product Upgrade Started');

            self::$rs_progress_bar->fp_increase_progress(30);
            self::$update_simple_product->save()->dispatch();
        }

        /**
         * Update Variable Product Settings 
         */
        public static function rs_update_variable_product($offset = 0, $limit = 1000) {
            global $wpdb;
            $ids = $wpdb->get_results("SELECT DISTINCT ID FROM {$wpdb->posts} as p INNER JOIN {$wpdb->postmeta} as p1 ON p.ID=p1.post_id WHERE p.post_type = 'product' AND p1.meta_key = '_enable_reward_points' AND p1.meta_value = '1' LIMIT $offset,$limit");
            if (is_array($ids) && !empty($ids)) {
                foreach ($ids as $id) {
                    self::$update_variable_product->push_to_queue($id->ID);
                }
            } else {
                self::$update_variable_product->push_to_queue('rs_data');
            }
            update_option('rs_variable_product_background_updater_offset', $limit + $offset);

            if ($offset == 0)
                FP_WooCommerce_Log::log('Variable Product Upgrade Started');

            self::$rs_progress_bar->fp_increase_progress(60);
            self::$update_variable_product->save()->dispatch();
        }

        /**
         * Update Category Settings 
         */
        public static function rs_update_category($offset = 0, $limit = 1000) {
            global $wpdb;
            $ids = $wpdb->get_col("SELECT DISTINCT term_id FROM {$wpdb->termmeta} WHERE meta_key = 'enable_reward_system_category' AND meta_value = 'yes' LIMIT $offset,$limit");
            if (is_array($ids) && !empty($ids)) {
                foreach ($ids as $id) {
                    self::$update_category->push_to_queue($id);
                }
            } else {
                self::$update_category->push_to_queue('rs_data');
            }

            update_option('rs_category_background_updater_offset', $limit + $offset);

            if ($offset == 0)
                FP_WooCommerce_Log::log('Category Upgrade Started');

            self::$rs_progress_bar->fp_increase_progress(90);
            self::$update_category->save()->dispatch();
        }

        public static function rs_initiate_to_background_process() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'rspointexpiry';
            update_option('rs_product_purchase_activated', 'yes');
            update_option('rs_referral_activated', 'yes');
            update_option('rs_social_reward_activated', 'yes');
            update_option('rs_reward_action_activated', 'yes');
            update_option('rs_point_expiry_activated', 'yes');
            update_option('rs_redeeming_activated', 'yes');
            update_option('rs_point_price_activated', 'yes');
            update_option('rs_email_activated', 'yes');
            update_option('rs_gift_voucher_activated', 'yes');
            update_option('rs_sms_activated', 'yes');
            update_option('rs_cashback_activated', 'yes');
            update_option('rs_nominee_activated', 'yes');
            update_option('rs_point_url_activated', 'yes');
            update_option('rs_gateway_activated', 'yes');
            update_option('rs_send_points_activated', 'yes');
            update_option('rs_imp_exp_activated', 'yes');
            update_option('rs_report_activated', 'yes');
            update_option('rs_reset_activated', 'yes');
            update_option('rs_enable_product_category_level_for_product_purchase', 'yes');
            update_option('rs_enable_product_category_level_for_referral_product_purchase', 'yes');
            update_option('rs_enable_product_category_level_for_social_reward', 'yes');
            update_option('rs_enable_product_category_level_for_points_price', 'yes');
            if (get_option('rs_global_enable_disable_sumo_reward') == '1') {
                update_option('rs_global_enable_disable_sumo_referral_reward', '1');
            }
            $totalcount = self::fp_rs_overall_batch_count();
            if ($totalcount != 0) {
                self::$rs_progress_bar->fp_delete_option();
                self::$rs_progress_bar->fp_increase_progress(10);
                set_transient('fp_rs_background_process_transient', true, 30);
            }else{
                add_option('rs_no_data_to_upgrade','yes');
                set_transient('_welcome_screen_activation_redirect_reward_points', true, 30);
            }
        }

        public static function fp_rs_overall_batch_count() {
            global $wpdb;
            $simple_product_ids = $wpdb->get_col("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_rewardsystemcheckboxvalue' AND meta_value = 'yes'");
            $variable_product_ids = $wpdb->get_col("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_enable_reward_points' AND meta_value = '1'");
            $category_ids = $wpdb->get_col("SELECT term_id FROM " . $wpdb->prefix . "termmeta WHERE meta_key = 'enable_reward_system_category' AND meta_value = 'yes'");
            $total = count($simple_product_ids) + count($variable_product_ids) + count($category_ids);
            return $total;
        }

        /*
         * Check if Background Related Files exists
         */

        public static function fp_rs_upgrade_file_exists() {
            $async_file = file_exists(untrailingslashit(WP_PLUGIN_DIR) . '/woocommerce/includes/libraries/wp-async-request.php');
            $background_file = file_exists(untrailingslashit(WP_PLUGIN_DIR) . '/woocommerce/includes/libraries/wp-background-process.php');
            if ($async_file && $background_file)
                return true;

            return false;
        }

    }

    RS_Main_Function_for_Background_Process::init();
}