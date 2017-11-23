<?php
/*
 * Support Tab Setting
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSSendPointsModule')) {

    class RSSendPointsModule {

        public static function init() {
            
            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'), 999);

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_sendpoints_module', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_modules_rewardsystem_sendpoints_module', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

            add_action('woocommerce_admin_field_rs_select_user_for_send', array(__CLASS__, 'rs_select_user_to_send_point'));

            add_action('woocommerce_admin_field_rs_send_point_applications_edit_lists', array(__CLASS__, 'send_point_applications_list_table'));

            add_action('woocommerce_admin_field_rs_send_point_applications_list', array(__CLASS__, 'send_list_overall_applications'));
            
            add_action('woocommerce_admin_field_rs_enable_disable_send_points_module', array(__CLASS__, 'rs_function_to_enable_disable_send_points_module'));
            
            add_action('fp_action_to_reset_module_settings_rewardsystem_sendpoints_module', array(__CLASS__, 'rs_function_to_send_points_module'));
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_sendpoints_module'] = __('Send Point(s) Module', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;
            return apply_filters('woocommerce_rewardsystem_sendpoints_module', array(
                array(
                    'type' => 'rs_modulecheck_start',
                 ),
                array(
                    'name' => __('Send Point(s) Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_activate_send_points_module'
                ),
                array(
                    'type' => 'rs_enable_disable_send_points_module',
                ),
                array('type' => 'sectionend', 'id' => '_rs_activate_send_points_module'),
                array(
                    'type' => 'rs_modulecheck_end',
                ),
		array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Send Point(s) Form Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_send_point_setting'
                ),
                array(
                    'name' => __('Enable Send Point(s)', 'rewardsystem'),
                    'id' => 'rs_enable_msg_for_send_point',
                    'newids' => 'rs_enable_msg_for_send_point',
                    'std' => '2',
                    'default' => '2',
                    'class' => 'rs_enable_msg_for_send_point',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('User Selection type for Sending Points', 'rewardsystem'),
                    'id' => 'rs_select_send_points_user_type',
                    'newids' => 'rs_select_send_points_user_type',
                    'std' => '1',
                    'default' => '1',
                    'class' => 'rs_select_send_points_user_type',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('All Users', 'rewardsystem'),
                        '2' => __('Selected User(s)', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'type' => 'rs_select_user_for_send',
                ),                
                array(
                    'name' => __('Current Reward Points Label', 'rewardsystem'),
                    'id' => 'rs_total_send_points_request',
                    'std' => 'Current Reward Points',
                    'default' => 'Current Reward Points',
                    'type' => 'text',
                    'newids' => 'rs_total_send_points_request',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Points to Send Label', 'rewardsystem'),
                    'id' => 'rs_points_to_send_request',
                    'std' => 'Points to Send',
                    'default' => 'Points to Send',
                    'type' => 'text',
                    'newids' => 'rs_points_to_send_request',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Select the User to Send Label', 'rewardsystem'),
                    'id' => 'rs_select_user_label',                    
                    'std' => 'Select the user to send',
                    'default' => 'Select the user to send',
                    'type' => 'text',
                    'newids' => 'rs_select_user_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Send Point Form Submit Button Label', 'rewardsystem'),
                    'id' => 'rs_select_points_submit_label',                    
                    'std' => 'Submit',
                    'default' => 'Submit',
                    'type' => 'text',
                    'newids' => 'rs_select_points_submit_label',
                    'desc_tip' => true,
                ),                
                array(
                    'name' => __('Send Points Request Approval Type', 'rewardsystem'),
                    'id' => 'rs_request_approval_type',
                    'newids' => 'rs_request_approval_type',
                    'std' => '1',
                    'default' => '1',
                    'class' => 'rs_request_approval_type',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Manual Approval', 'rewardsystem'),
                        '2' => __('Auto Approval', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Message to display when Send Points Request is Submitted Successfully', 'rewardsystem'),
                    'id' => 'rs_message_send_point_request_submitted',                    
                    'std' => 'Send Point Request Submitted',
                    'default' => 'Send Point Request Submitted',
                    'type' => 'textarea',
                    'newids' => 'rs_message_send_point_request_submitted',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Message to display when Send Points Request is Submitted via Auto Approval', 'rewardsystem'),
                    'id' => 'rs_message_send_point_request_submitted_for_auto',                    
                    'std' => 'Points has been sent Successfully',
                    'default' => 'Points has been sent Successfully',
                    'type' => 'textarea',
                    'newids' => 'rs_message_send_point_request_submitted_for_auto',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Restriction on Sending Points', 'rewardsystem'),
                    'id' => 'rs_limit_for_send_point',
                    'newids' => 'rs_limit_for_send_point',
                    'std' => '2',
                    'default' => '2',
                    'class' => 'rs_limit_for_send_point',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Maximum Points which can be Sent', 'rewardsystem'),
                    'id' => 'rs_limit_send_points_request',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_limit_send_points_request',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when Send Points is greater than the Maximum Points', 'rewardsystem'),
                    'id' => 'rs_err_when_point_greater_than_limit',                    
                    'std' => 'Please Enter Points less than {limitpoints}',
                    'default' => 'Please Enter Points less than {limitpoints}',
                    'type' => 'text',
                    'newids' => 'rs_err_when_point_greater_than_limit',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_send_point_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),                
                array(
                    'name' => __('Send Point(s) Request List', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_request_for_send_point_setting'
                ),
                array(
                    'type' => 'rs_send_point_applications_list',
                ),
                array(
                    'type' => 'rs_send_point_applications_edit_lists',
                ),
                array('type' => 'sectionend', 'id' => '_rs_request_for_send_point_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Error Message(s) for Send Point(s) Form', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_error_msg_setting'
                ),
                array(
                    'name' => __('Error Message to be displayed when Points to Send field is left Empty', 'rewardsystem'),
                    'id' => 'rs_err_when_point_field_empty',                    
                    'std' => 'Please Enter the Points to Send',
                    'default' => 'Please Enter the Points to Send',
                    'type' => 'text',
                    'newids' => 'rs_err_when_point_field_empty',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when User doesn\'t have Points in their Account', 'rewardsystem'),
                    'id' => 'rs_msg_when_user_have_no_points',                    
                    'std' => 'You have no Points to Send',
                    'default' => 'You have no Points to Send',
                    'type' => 'text',
                    'newids' => 'rs_msg_when_user_have_no_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to be displayed when User entered Points more than the Available Points', 'rewardsystem'),
                    'id' => 'rs_error_msg_when_points_is_more',                    
                    'std' => 'Please Enter the Points less than your Current Points',
                    'default' => 'Please Enter the Points less than your Current Points',
                    'type' => 'text',
                    'newids' => 'rs_error_msg_when_points_is_more',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to be displayed when Select User field is left Empty', 'rewardsystem'),
                    'id' => 'rs_err_for_empty_user',                    
                    'std' => 'Please Select the User to Send Points',
                    'default' => 'Please Select the User to Send Points',
                    'type' => 'text',
                    'newids' => 'rs_err_for_empty_user',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to be displayed when entered Points is not a Number', 'rewardsystem'),
                    'id' => 'rs_err_when_point_is_not_number',                    
                    'std' => 'Please Enter only the Number',
                    'default' => 'Please Enter only the Number',
                    'type' => 'text',
                    'newids' => 'rs_err_when_point_is_not_number',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => 'rs_error_msg_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shortcode used in Send Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_shortcode_for_send_points'
                ),
                array(
                    'type' => 'title',
                    'desc' => '<b>{limitpoints}</b> - To display send points limitation'
                ),
                array('type' => 'sectionend', 'id' => 'rs_shortcode_for_send_points'),  
                array(
                    'type' => 'rs_wrapper_end',
                ),
                
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {

            woocommerce_admin_fields(RSSendPointsModule::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSSendPointsModule::reward_system_admin_fields());
            if (isset($_POST['rs_send_points_module_checkbox'])) {
                update_option('rs_send_points_activated', $_POST['rs_send_points_module_checkbox']);
            } else {
                update_option('rs_send_points_activated', 'no');
            }
            
            //send points users update
            if (isset($_POST['rs_select_users_list_for_send_point'])) {
                update_option('rs_select_users_list_for_send_point', $_POST['rs_select_users_list_for_send_point']);
            } else {
                update_option('rs_select_users_list_for_send_point', '');
            }
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSSendPointsModule::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }
        
        public static function rs_function_to_send_points_module() {
            $settings = RSSendPointsModule::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
        }
        
        public static function rs_function_to_enable_disable_send_points_module() {
            $get_option_value = get_option('rs_send_points_activated');
            $name_of_checkbox = 'rs_send_points_module_checkbox';
            RSModulesTab::rs_common_function_to_get_checkbox($get_option_value, $name_of_checkbox);
        }

        public static function rs_select_user_to_send_point() {
            $field_id = "rs_select_users_list_for_send_point";
            $field_label = "Select User(s)";
            $getuser = get_option('rs_select_users_list_for_send_point');
            echo rs_function_to_add_field_for_user_select($field_id, $field_label, $getuser);
            ?>
            <style type="text/css">
                .chosen-container-single {
                    position:absolute;
                }

            </style>
            <?php
        }

        public static function send_point_validation($item) {
            $messages = array();
            if (empty($messages))
                return true;
            return implode('<br />', $messages);
        }

        public static function send_point_applications_list_table($item) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sumo_reward_send_point_submitted_data';
            $message = '';
            $notice = '';
            $default = array(
                'id' => 0,
                'userid' => '',
                'pointstosend' => '',
                'sendercurrentpoints' => '',
                'selecteduser' => '',
                'status' => '',
            );

            if (isset($_REQUEST['nonce'])) {
                if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
                    $item = shortcode_atts($default, $_REQUEST);
                    $item_valid = self::send_point_validation($item);
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

                if (isset($_REQUEST['send_application_id'])) {
                    $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['send_application_id']), ARRAY_A);

                    if (!$item) {
                        $item = $default;
                        $notice = __('Item not found');
                    }
                }
            }
            ?>
            <?php
            if (isset($_REQUEST['send_application_id'])) {                
                $timeformat = get_option('time_format');
                $dateformat = get_option('date_format') . ' ' . $timeformat;
                $expired_date = date_i18n($dateformat);
                ?>
                <div class="wrap">
                    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                    <h3><?php _e('Edit Cashback Status', 'rewardsystem'); ?><a class="add-new-h2"
                                                                               href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=rewardsystem_callback&tab=send_applications'); ?>"><?php _e('Back to list') ?></a>
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
                                                <th scope="row"><?php _e('Points for Send', 'rewardsystem'); ?></th>
                                                <td>
                                                    <input type="text" name="pointstosend" id="setvendorname" value="<?php echo $item['pointstosend']; ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?php _e('Current User Point', 'rewardsystem'); ?></th>
                                                <td>
                                                    <textarea name="sendercurrentpoints" rows="3" cols="30"><?php echo $item['sendercurrentpoints']; ?></textarea>
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

                                        </tbody>
                                    </table>
                                    <input type="submit" value="<?php _e('Save Changes', 'rewardsystem') ?>" id="submit" class="button-primary" name="submit">
                                </div>
                            </div>
                        </div>                    
                    </form>

                </div>
            <?php
            }
        }

        public static function send_list_overall_applications() {
            global $wpdb;
            global $current_section;
            global $current_tab;
            $testListTable = new FPRewardSystemSendpointTabList();
            $testListTable->prepare_items();
            if (!isset($_REQUEST['send_application_id'])) {
                $array_list = array();
                $message = '';
                if ('send_application_delete' === $testListTable->current_action()) {
                    $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d'), count($_REQUEST['id'])) . '</p></div>';
                }
                echo $message;
                $testListTable->display();               
            }
        }

    }

    RSSendPointsModule::init();
}