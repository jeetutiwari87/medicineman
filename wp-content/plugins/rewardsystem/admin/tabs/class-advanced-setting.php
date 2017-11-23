<?php
/*
 * Advanced Tab
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSAdvancedSetting')) {

    class RSAdvancedSetting {

        public static function init() {

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_advanced', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_advanced', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings')); // call the init function to update the default settings on page load

            add_action('fp_action_to_reset_settings_rewardsystem_advanced', array(__CLASS__, 'rs_function_to_reset_advanced_tab'));

            add_action('woocommerce_admin_field_rs_add_old_version_points', array(__CLASS__, 'add_old_points_for_all_user'));

            add_action('woocommerce_admin_field_previous_order_button_range', array(__CLASS__, 'rs_add_date_picker'));

            add_action('woocommerce_admin_field_previous_order_button', array(__CLASS__, 'rs_apply_points_for_previous_order_button'));

            add_action('admin_head', array(__CLASS__, 'rs_send_ajax_points_to_previous_orders'));

            add_action('wp_ajax_nopriv_previousorderpoints', array(__CLASS__, 'rs_process_ajax_points_to_previous_order'));

            add_action('wp_ajax_previousorderpoints', array(__CLASS__, 'rs_process_ajax_points_to_previous_order'));

            add_action('wp_ajax_rssplitajaxoptimizationforpreviousorder', array(__CLASS__, 'process_chunk_ajax_request_for_previous_orders'));
                                   
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_advanced'] = __('Advanced', 'rewardsystem');
            return $setting_tabs;
        }

        public static function reward_system_admin_fields() {
            return apply_filters('woocommerce_rewardsystem_advanced_settings', array(
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Advanced Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_advanced_setting',
                ),
                array(
                    'name' => __('Show/Hide Reset Button in Tabs', 'rewardsystem'),
                    'id' => 'rs_show_hide_reset_all',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_reset_all',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Admin Color Scheme', 'rewardsystem'),
                    'id' => 'rs_color_scheme',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_color_scheme',
                    'options' => array(
                        '1' => __('Dark', 'rewardsystem'),
                        '2' => __('Light', 'rewardsystem'),
                    ),
                ),
                array('type' => 'sectionend', 'id' => 'rs_advanced_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Purchase Reward Points for Previous Orders', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_apply_reward_points',
                ),
                array(
                    'name' => __('Award Product Purchase Reward Points for', 'rewardsystem'),
                    'id' => 'rs_sumo_select_order_range',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => 'Any Old Orders',
                        '2' => 'Orders Placed Between Specific Date Range'
                    ),
                    'newids' => 'rs_sumo_select_order_range',
                    'desc_tip' => true,
                ),
                array(
                    'type' => 'previous_order_button_range',
                ),
                array(
                    'type' => 'previous_order_button',
                ),
                array('type' => 'sectionend', 'id' => '_rs_apply_reward_points'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Custom CSS Settings', 'rewardsystem'),
                    'type' => 'title',
                    'desc' => 'Try !important if styles doesn\'t apply ',
                    'id' => '_rs_general_custom_css_settings',
                ),
                array(
                    'name' => __('Custom CSS', 'rewardsystem'),
                    'id' => 'rs_general_custom_css',
                    'std' => '',
                    'default' => '',
                    'type' => 'textarea',
                    'newids' => 'rs_general_custom_css',
                ),
                array(
                    'name' => __('Custom CSS for Shop Page', 'rewardsystem'),
                    'id' => 'rs_shop_page_custom_css',
                    'std' => '',
                    'default' => '',
                    'type' => 'textarea',
                    'newids' => 'rs_shop_page_custom_css',
                ),
                array(
                    'name' => __('Custom CSS for Single Product Page', 'rewardsystem'),
                    'id' => 'rs_single_product_page_custom_css',
                    'std' => '',
                    'default' => '',
                    'type' => 'textarea',
                    'newids' => 'rs_single_product_page_custom_css',
                ),
                array(
                    'name' => __('Custom CSS for Cart Page', 'rewardsystem'),
                    'id' => 'rs_cart_page_custom_css',
                    'std' => '#rs_apply_coupon_code_field { } #mainsubmi { } .fp_apply_reward{ }',
                    'default' => '#rs_apply_coupon_code_field { } #mainsubmi { } .fp_apply_reward{ }',
                    'type' => 'textarea',
                    'newids' => 'rs_cart_page_custom_css',
                ),
                array(
                    'name' => __('Custom CSS for Checkout Page', 'rewardsystem'),
                    'id' => 'rs_checkout_page_custom_css',
                    'std' => '#rs_apply_coupon_code_field { } #mainsubmi { } .fp_apply_reward{ }',
                    'default' => '#rs_apply_coupon_code_field { } #mainsubmi { } .fp_apply_reward{ }',
                    'type' => 'textarea',
                    'newids' => 'rs_checkout_page_custom_css',
                ),
                array(
                    'name' => __('Custom CSS for My Account Page', 'rewardsystem'),
                    'id' => 'rs_myaccount_custom_css',
                    'std' => '#generate_referral_field { }  '
                    . '#rs_redeem_voucher_code { }  '
                    . '#ref_generate_now { } '
                    . ' #rs_submit_redeem_voucher { }',
                    'type' => 'textarea',
                    'newids' => 'rs_myaccount_custom_css',
                ),
                array(
                    'name' => __('Custom CSS for Social Button', 'rewardsystem'),
                    'id' => 'rs_social_custom_css',
                    'std' => '.rs_social_sharing_buttons{};'
                    . '.rs_social_sharing_success_message',
                    'default' => '.rs_social_sharing_buttons{};'
                    . '.rs_social_sharing_success_message',
                    'newids' => 'rs_social_custom_css',
                    'type' => 'textarea',
                ),
                array(
                    'name' => __('Custom CSS for Refer a Friend Form', 'rewardsystem'),
                    'id' => 'rs_refer_a_friend_custom_css',
                    'std' => '#rs_refer_a_friend_form { } #rs_friend_name { } #rs_friend_email { } #rs_friend_subject { } #rs_your_message { } #rs_refer_submit { }',
                    'default' => '#rs_refer_a_friend_form { } #rs_friend_name { } #rs_friend_email { } #rs_friend_subject { } #rs_your_message { } #rs_refer_submit { }',
                    'type' => 'textarea',
                    'newids' => 'rs_refer_a_friend_custom_css',
                ),                
                array(
                    'name' => __('Inbuilt Design for Cash Back Form', 'rewardsystem'),
                    'id' => 'rs_encash_form_inbuilt_design',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'options' => array('1' => 'Inbuilt Design'),
                    'newids' => 'rs_encash_form_inbuilt_design',
                ),
                array(
                    'name' => __('Inbuilt CSS (Non Editable) for Cash Back Form', 'rewardsystem'),
                    'id' => 'rs_encash_form_default_css',
                    'std' => '#encashing_form{}
.rs_encash_points_value{}
.error{color:#ED0514;}
.rs_encash_points_reason{}
.rs_encash_payment_method{}
.rs_encash_paypal_address{}
.rs_encash_custom_payment_option_value{}
.rs_encash_submit{}
#rs_encash_submit_button{}
.success_info{}
#encash_form_success_info{}',
                    'default' => '#encashing_form{}
.rs_encash_points_value{}
.error{color:#ED0514;}
.rs_encash_points_reason{}
.rs_encash_payment_method{}
.rs_encash_paypal_address{}
.rs_encash_custom_payment_option_value{}
.rs_encash_submit{}
#rs_encash_submit_button{}
.success_info{}
#encash_form_success_info{}',
                    'type' => 'textarea',
                    'custom_attributes' => array(
                        'readonly' => 'readonly'
                    ),
                    'newids' => 'rs_encash_form_default_css',
                ),
                array(
                    'name' => __('Custom Design for Cash Back Form', 'rewardsystem'),
                    'id' => 'rs_encash_form_inbuilt_design',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'options' => array('2' => 'Custom Design'),
                    'newids' => 'rs_encash_form_inbuilt_design',
                ),
                array(
                    'name' => __('Custom CSS for Cash Back Form', 'rewardsystem'),
                    'id' => 'rs_encash_form_custom_css',
                    'std' => '',
                    'default' => '',
                    'type' => 'textarea',
                    'newids' => 'rs_encash_form_custom_css',
                ),                
                array('type' => 'sectionend', 'id' => '_rs_reward_point_general_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Troubleshoot Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_point_troubleshoot_cart_page'
                ),
                array(
                    'name' => __('Troubleshoot Before Cart Hook', 'rewardsystem'),
                    'desc' => __('Here you can select the different hooks in Cart Page', 'rewardsystem'),
                    'id' => 'rs_reward_point_troubleshoot_before_cart',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'options' => array('1' => 'woocommerce_before_cart', '2' => 'woocommerce_before_cart_table'),
                    'newids' => 'rs_reward_point_troubleshoot_before_cart',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Redeeming Field display Position in Cart Page', 'rewardsystem'),
                    'id' => 'rs_reward_point_troubleshoot_after_cart',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'options' => array('1' => 'woocommerce_after_cart_table', '2' => 'woocommerce_cart_coupon'),
                    'newids' => 'rs_reward_point_troubleshoot_after_cart',
                ),
                array(
                    'name' => __('Enqueue Tipsy jQuery Library in SUMO Reward Points', 'rewardsystem'),
                    'desc' => __('Here you can select to change the load tipsy option if some jQuery conflict occurs', 'rewardsystem'),
                    'id' => 'rs_reward_point_enable_tipsy_social_rewards',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'options' => array('1' => 'Enable ', '2' => 'Disable'),
                    'newids' => 'rs_reward_point_enable_tipsy_social_rewards',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Enqueue jQuery UI Library in SUMO Reward Points', 'rewardsystem'),
                    'desc' => __('Here you can select whether to enqueue the jQuery UI library available within SUMO Reward Points', 'rewardsystem'),
                    'id' => 'rs_reward_point_enable_jquery',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'options' => array('1' => 'Enqueue ', '2' => 'Do not Enqueue'),
                    'newids' => 'rs_reward_point_enable_jquery',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Load SUMO Reward Points Script/Styles in', 'rewardsystem'),
                    'desc' => __('For Footer of the Site Option is experimental why because if your theme doesn\'t contain wp_footer hook then it won\'t work', 'rewardsystem'),
                    'id' => 'rs_load_script_styles',
                    'newids' => 'rs_load_script_styles',
                    'type' => 'select',
                    'desc_tip' => false,
                    'options' => array(
                        'wp_head' => 'Header of the Site',
                        'wp_footer' => 'Footer of the Site (Experimental)'
                    ),
                    'std' => 'wp_head',
                    'default' => 'wp_head',
                ),
                array(
                    'name' => __('Memory Exhaust Issues', 'rewardsystem'),
                    'desc' => __('Enable or Disable Memory Exhaust Troubleshoot', 'rewardsystem'),
                    'id' => 'rs_load_memory_unit',
                    'newids' => 'rs_load_memory_unit',
                    'type' => 'select',
                    'desc_tip' => false,
                    'options' => array(
                        '1' => 'Enable',
                        '2' => 'Disable'
                    ),
                    'std' => '2',
                    'default' => '2',
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_point_troubleshoot_cart_page'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Experimental Features', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_point_table'
                ),
                array(
                    'type' => 'rs_add_old_version_points',
                ),
                array(
                    'name' => __('SUMO Reward Points Payment Gateway for Manual Order', 'rewardsystem'),
                    'desc' => __('Enable or Disable SUMO Reward Points Payment Gateway for Manual Order', 'rewardsystem'),
                    'id' => 'rs_gateway_for_manual_order',
                    'newids' => 'rs_gateway_for_manual_order',
                    'std' => '2',
                    'default' => '2',
                    'type' => 'select',
                    'desc_tip' => false,
                    'options' => array(
                        '1' => 'Enable',
                        '2' => 'Disable'
                    ),
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_point_table'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                    )
            );
        }

        public static function reward_system_register_admin_settings() {

            woocommerce_admin_fields(RSAdvancedSetting::reward_system_admin_fields());
        }

        public static function reward_system_update_settings() {
            woocommerce_update_options(RSAdvancedSetting::reward_system_admin_fields());
        }

        public static function reward_system_default_settings() {
            foreach (RSAdvancedSetting::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        public static function rs_function_to_reset_advanced_tab() {
            $settings = RSAdvancedSetting::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
        }

        public static function add_old_points_for_all_user() {
            ?>
            <tr valign="top">
                <th>
                    <label for="rs_add_old_points_label" style="font-size:14px;font-weight:600;"><?php _e('Add the Old Available Points to User(s)', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <input type="button" value="<?php _e('Add Old Points', 'rewardsystem'); ?>"  id="rs_add_old_points" class="rs_oldpoints_button" name="rs_add_old_points" /><b><span style="font-size: 18px;">(Experimental)</span></b>
                    <img class="gif_rs_sumo_reward_button" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/admin/images/update.gif" style="width:32px;height:32px;position:absolute"/>                         
                </td>
            </tr>
            <?php
        }

        public static function rs_add_date_picker() {
            ?>
            <script type="text/javascript">
                jQuery(function () {
                    jQuery("#rs_from_date").datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        dateFormat: 'yy-mm-dd',
                        numberOfMonths: 1,
                        onClose: function (selectedDate) {
                            jQuery("#to").datepicker("option", "minDate", selectedDate);
                        }
                    });
                    jQuery('#rs_from_date').datepicker('setDate', '-1');
                    jQuery("#rs_to_date").datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        dateFormat: 'yy-mm-dd',
                        numberOfMonths: 1,
                        onClose: function (selectedDate) {
                            jQuery("#from").datepicker("option", "maxDate", selectedDate);
                        }

                    });
                    jQuery("#rs_to_date").datepicker('setDate', new Date());
                });
            </script>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_sumo_rewards_for_selecting_particular_date"><?php _e('Select from Specific Date', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    From <input type="text" id="rs_from_date" value=""/> To <input type="text" id="rs_to_date" value=""/>
                </td>
            </tr>
            <?php
        }

        public static function rs_process_ajax_points_to_previous_order() {
            if (isset($_POST['proceedanyway'])) {
                if ($_POST['proceedanyway'] == '1') {
                    $orderstatuslist = get_option('rs_order_status_control');
                    $new_order = array('wc-completed');
                    foreach ($orderstatuslist as $each_order) {
                        $new_order[] = 'wc-' . $each_order;
                    }
                    $args = array('post_type' => 'shop_order', 'numberposts' => '-1', 'meta_query' => array(array('key' => 'reward_points_awarded', 'compare' => 'NOT EXISTS')), 'post_status' => $new_order, 'fields' => 'ids', 'cache_results' => false);
                    $order_id = get_posts($args);
                    echo json_encode($order_id);
                }
            }
            exit();
        }

        public static function rs_apply_points_for_previous_order_button() {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_sumo_rewards_for_previous_order_label"><?php _e('Apply Product Purchase Reward Points to Previous Orders', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="submit" class="rs_sumo_rewards_for_previous_order button-primary rs_button" value="Apply Points for Previous Orders"/>
                    <img class="gif_rs_sumo_reward_button_for_previous_order" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/admin/images/update.gif" style="width:32px;height:32px;position:absolute"/>
                    <div class="rs_sumo_rewards_previous_order" style="margin-bottom:10px;margin-top:10px; color:green;"></div>
                </td>
            </tr>
            <?php
        }

        public static function rs_send_ajax_points_to_previous_orders() {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('.gif_rs_sumo_reward_button_for_previous_order').css('display', 'none');
                    jQuery('.rs_sumo_rewards_for_previous_order').click(function () {
                        jQuery('.gif_rs_sumo_reward_button_for_previous_order').css('display', 'inline-block');
                        jQuery(this).attr('data-clicked', '1');
                        var dataclicked = jQuery(this).attr('data-clicked');
                        var fromdate = jQuery('#rs_from_date').val();
                        var todate = jQuery('#rs_to_date').val();
                        if (jQuery('#rs_sumo_select_order_range').val() === '1') {
                            var dataparam = ({
                                action: 'previousorderpoints',
                                proceedanyway: dataclicked,
                            });
                            function getData(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: 'rssplitajaxoptimizationforpreviousorder',
                                        ids: id,
                                        proceedanyway: dataclicked,
                                        //fromdate: fromdate,
                                        //todate: todate,
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
                                                getData(temparray);
                                            }
                                            jQuery.when(getData()).done(function (a1) {
                                                console.log('Ajax Done Successfully');
                                                location.reload();
                                                jQuery('.rs_sumo_rewards_previous_order').fadeIn();
                                                if (response != '') {
                                                    jQuery('.rs_sumo_rewards_previous_order').html('Points Successfully Added to Previous Order');
                                                } else {
                                                    jQuery('.rs_sumo_rewards_previous_order').html('There is no order to give points');
                                                }
                                                jQuery('.rs_sumo_rewards_previous_order').fadeOut(5000);
                                            });
                                        }
                                    }, 'json');
                        } else {
                            var dataparam = ({
                                action: 'previousorderpoints',
                                proceedanyway: dataclicked,
                                fromdate: fromdate,
                                todate: todate,
                            });
                            function getDataforDate(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: 'rssplitajaxoptimizationforpreviousorder',
                                        ids: id,
                                        proceedanyway: dataclicked,
                                        //fromdate: fromdate,
                                        //todate: todate,
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
                                        // alert(response);
                                        if (response != 'success') {
                                            var j = 1;
                                            var i, j, temparray, chunk = 10;
                                            for (i = 0, j = response.length; i < j; i += chunk) {
                                                temparray = response.slice(i, i + chunk);
                                                getDataforDate(temparray);
                                            }
                                            jQuery.when(getDataforDate()).done(function (a1) {
                                                console.log('Ajax Done Successfully');
                                                location.reload();
                                                jQuery('.rs_sumo_rewards_previous_order').fadeIn();
                                                if (response != '') {
                                                    jQuery('.rs_sumo_rewards_previous_order').html('Points Successfully Added to Previous Order');
                                                } else {
                                                    jQuery('.rs_sumo_rewards_previous_order').html('There is no order to give points');
                                                }
                                                jQuery('.rs_sumo_rewards_previous_order').fadeOut(5000);
                                            });
                                        }
                                    }, 'json');
                        }
                        return false;
                    });
                });</script>
            <?php
        }

        public static function process_chunk_ajax_request_for_previous_orders() {
            if (isset($_POST['ids'])) {
                $products = $_POST['ids'];
                foreach ($products as $product) {
                    $order = new WC_Order($product);
                    $modified_date = get_the_time('Y-m-d', $product);
                    if (isset($_POST['fromdate']) && ($_POST['todate'])) {
                        if (($_POST['fromdate'] <= $modified_date) && $modified_date <= $_POST['todate']) {
                            $points_awarded_for_this_order = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product, 'reward_points_awarded');
                            if ($points_awarded_for_this_order != 'yes') {
                                $new_obj = new RewardPointsOrder($product, $apply_previous_order_points = 'yes');
                                $new_obj->update_earning_points_for_user();
                                $order_user_id = rs_get_order_obj($order);
                                $order_user_id = $order_user_id['order_userid'];
                                update_user_meta($order_user_id, 'rsfirsttime_redeemed', 1);
                                add_post_meta($product, 'reward_points_awarded', 'yes');
                            }
                        }
                    } else {
                        $points_awarded_for_this_order = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product, 'reward_points_awarded');
                        if ($points_awarded_for_this_order != 'yes') {
                            $new_obj = new RewardPointsOrder($product, $apply_previous_order_points = 'yes');
                            $new_obj->update_earning_points_for_user();
                            $order_user_id = rs_get_order_obj($order);
                            $order_user_id = $order_user_id['order_userid'];
                            update_user_meta($order_user_id, 'rsfirsttime_redeemed', 1);
                            add_post_meta($product, 'reward_points_awarded', 'yes');
                        }
                    }
                }
            }
            exit();
        }

    }

    RSAdvancedSetting::init();
}