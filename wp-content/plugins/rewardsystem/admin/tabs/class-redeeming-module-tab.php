<?php

/*
 * Support Tab Setting
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSRedeemingModule')) {

    class RSRedeemingModule {

        public static function init() {
            
            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'), 999);

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_redeeming_module', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_modules_rewardsystem_redeeming_module', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

            add_action('admin_head', array(__CLASS__, 'validate_maximum_minimum'), 10);

            add_action('admin_head', array(__CLASS__, 'rs_redeeming_selected_products_categories'));

            add_action('admin_head', array(__CLASS__, 'rs_purchase_product_using_point'));

            add_action('woocommerce_admin_field_exclude_product_selection', array(__CLASS__, 'rs_select_product_to_exclude'));

            add_action('woocommerce_admin_field_include_product_selection', array(__CLASS__, 'rs_select_product_to_include'));

            add_action('woocommerce_admin_field_rs_product_for_purchase', array(__CLASS__, 'rs_purchase_selected_product_using_points'));

            add_action('woocommerce_admin_field_rs_hide_gateway', array(__CLASS__, 'rs_selected_product_hide_gateway'));
            
            add_action('woocommerce_admin_field_rs_enable_disable_redeeming_module', array(__CLASS__, 'rs_function_to_enable_disable_redeeming_module'));
            
            if (class_exists('SUMODiscounts')) {
                add_filter('woocommerce_rewardsystem_redeeming_module', array(__CLASS__, 'setting_for_hide_redeem_field_when_sumo_discount_is_active'));
            }
            
            add_action('fp_action_to_reset_module_settings_rewardsystem_redeeming_module', array(__CLASS__, 'rs_function_to_redeeming_module'));

            if (class_exists('SUMOMemberships')) {
                add_filter('woocommerce_rewardsystem_redeeming_module', array(__CLASS__, 'add_field_for_membership'));
            }

            add_action('woocommerce_admin_field_rs_user_role_dynamics_for_redeem', array(__CLASS__, 'rs_function_to_add_rule_for_redeeming_percentage'));

            add_filter("woocommerce_rewardsystem_redeeming_module", array(__CLASS__, 'reward_system_add_settings_to_action'));

            add_action('fp_action_to_reset_settings_rewardsystem_member_level', array(__CLASS__, 'rs_function_to_reset_memberlevel_tab'));

            add_action('woocommerce_admin_field_rs_user_purchase_history_redeem', array(__CLASS__, 'rs_function_to_add_rule_for_redeeming_percentage_purchase_history'));
        }

        public static function rs_function_to_add_rule_for_redeeming_percentage_purchase_history() {
            global $woocommerce;
            wp_nonce_field(plugin_basename(__FILE__), 'rsdynamicrulecreationsforuserpurchasehistory_redeeming');
            ?>
            <table class="widefat fixed" cellspacing="0">
                <thead>
                    <tr class="rsdynamicrulecreationsforuserpurchasehistory_redeeming">
                        <th class="manage-column column-columnname" scope="col"><?php _e('Level Name', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Type', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Value', 'rewardsystem'); ?></th>      
                        <th class="manage-column column-columnname" scope="col"><?php _e('Percentage', 'rewardsystem'); ?></th>   
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Level', 'rewardsystem'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="rsdynamicrulecreationsforuserpurchasehistory_redeeming">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="manage-column column-columnname num" scope="col"> <span class="add_product button-primary"><?php _e('Add New Level', 'rewardsystem'); ?></span></td>
                    </tr>
                    <tr class="rsdynamicrulecreationsforuserpurchasehistory_redeeming">
                        <th class="manage-column column-columnname" scope="col"><?php _e('Level Name', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Type', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Value', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Percentage', 'rewardsystem'); ?></th>

                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Level', 'rewardsystem'); ?></th>

                    </tr>
                </tfoot>
                <tbody id="here_product">
                    <?php
                    $rewards_dynamic_rulerule = get_option('rewards_dynamic_rule_purchase_history_redeem');
                    if (!empty($rewards_dynamic_rulerule)) {
                        if (is_array($rewards_dynamic_rulerule)) {
                            foreach ($rewards_dynamic_rulerule as $i => $rewards_dynamic_rule) {
                                ?>
                                <tr class="rsdynamicrulecreationsforuserpurchasehistory_redeeming">
                                    <td class="column-columnname">
                                        <p class="form-field">
                                            <input type="text" name="rewards_dynamic_rule_purchase_history_redeem[<?php echo $i; ?>][name]" class="short" value="<?php echo $rewards_dynamic_rule['name']; ?>"/>
                                        </p>
                                    </td>
                                    <td class="column-columnname">
                                        <p class="form-field">
                                            <select style="width:225px !important;" name="rewards_dynamic_rule_purchase_history_redeem[<?php echo $i; ?>][type]" id="rewards_dynamic_rule_purchase_history_redeem<?php echo $i; ?>" class="short"  />
                            <option value="1" <?php selected('1', $rewards_dynamic_rule['type']); ?>><?php _e('Number of Successful Order(s)', 'rewardsystem'); ?></option>
                            <option value="2" <?php selected('2', $rewards_dynamic_rule['type']); ?>><?php _e('Total Amount Spent in Site', 'rewardsystem'); ?></option>

                        </select> 
                        </p>
                        </td>
                        <td class="column-columnname">
                            <p class="form-field">
                                <input type ="number" name="rewards_dynamic_rule_purchase_history_redeem[<?php echo $i; ?>][value]" id="rewards_dynamic_rule_purchase_historyrewards_dynamic_rule_purchase_history_redeemvalue<?php echo $i; ?>" class="short test" value="<?php echo $rewards_dynamic_rule['value']; ?>"/>
                            </p>
                        </td>
                        <td class="column-columnname">
                            <p class="form-field">
                                <input type ="number" name="rewards_dynamic_rule_purchase_history_redeem[<?php echo $i; ?>][percentage]" id="rewards_dynamic_rule_purchase_history_redeempercentage<?php echo $i; ?>" class="short test" value="<?php echo $rewards_dynamic_rule['percentage']; ?>"/>
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
                    jQuery(".add_product").on('click', function () {
                        var countrewards_dynamic_rule = Math.round(new Date().getTime() + (Math.random() * 100));
            <?php ?>
                        jQuery('#here_product').append('<tr class="rsdynamicrulecreationsforuserpurchasehistory_redeeming"><td><p class="form-field"><input type="text" name="rewards_dynamic_rule_purchase_history_redeem[' + countrewards_dynamic_rule + '][name]" class="short" value=""/></p></td>\n\
            <td><p class="form-field"><select style="width:225px !important;" id="rewards_dynamic_rule_purchase_history_redeem' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_purchase_history_redeem[' + countrewards_dynamic_rule + '][type]" class="short">\n\
            <option value="1"><?php _e('Number of Successful Order(s)','rewardsystem');?></option>\n\
            <option value="2"><?php _e('Total Amount Spent in Site','rewardsystem');?></select></p></td>\n\
            <td><p class="form-field"><input type ="number" id="rewards_dynamic_rule_purchase_history_redeem' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_purchase_history_redeem[' + countrewards_dynamic_rule + '][value]" class="short test"  value=""/></p></td>\n\
             <td><p class="form-field"><input type ="number" id="rewards_dynamic_rule_purchase_history_redeem' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_purchase_history_redeem[' + countrewards_dynamic_rule + '][percentage]" class="short"  value=""/></p></td>\n\
            <td class="num"><span class="remove button-secondary"><?php _e('Remove Rule','rewardsystem');?></span></td></tr><hr>');

                        return false;
                    });
                    jQuery(document).on('click', '.remove', function () {
                        jQuery(this).parent().parent().remove();
                    });
                    jQuery('#rs_enable_user_role_based_reward_points_for_redeem').addClass('rs_enable_user_role_based_reward_points_for_redeem');
                    jQuery('#rs_enable_earned_level_based_reward_points_for_redeem').addClass('rs_enable_earned_level_based_reward_points_for_redeem');
                });
            </script>
            <?php
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function add_field_for_membership($settings) {
            $updated_settings = array();
            $membership_level = sumo_get_membership_levels();
            foreach ($settings as $section) {
                $updated_settings[] = $section;
                if (isset($section['id']) && '_rs_user_role_reward_points_for_redeem' == $section['id'] &&
                        isset($section['type']) && 'sectionend' == $section['type']) {
                    $updated_settings[] = array(
                        'name' => __('Reward Points Redeem Percentage based on Membership Plan', 'rewardsystem'),
                        'type' => 'title',
                        'id' => '_rs_membership_plan_for_redeem',
                    );
                    $updated_settings[] = array(
                        'name' => __('Don\'t allow Redeeming when the user hasn\'t purchased any membership plan through SUMO Memberships', 'rewardsystem'),
                        'desc' => __('Don\'t allow Redeeming when the user hasn\'t purchased any membership plan through SUMO Memberships', 'rewardsystem'),
                        'id' => 'rs_restrict_redeem_when_no_membership_plan',
                        'css' => 'min-width:150px;',                       
                        'type' => 'checkbox',
                        'newids' => 'rs_restrict_redeem_when_no_membership_plan',
                    );
                    $updated_settings[] = array(
                        'name' => __('Membership Plan based Redeem Level', 'rewardsystem'),
                        'desc' => __('Enable this option to modify Redeem points based on membership plan', 'rewardsystem'),
                        'id' => 'rs_enable_membership_plan_based_redeem',
                        'css' => 'min-width:150px;',
                        'std' => 'yes',
                        'default' => 'yes',
                        'type' => 'checkbox',
                        'newids' => 'rs_enable_membership_plan_based_redeem',
                    );
                    foreach ($membership_level as $key => $value) {
                        $updated_settings[] = array(
                            'name' => __('Reward Points Redeem Percentage for ' . $value, 'rewardsystem'),
                            'desc' => __('Please Enter Percentage of Redeem for ' . $value, 'rewardsystem'),
                            'class' => 'rewardpoints_membership_plan_for_redeem',
                            'id' => 'rs_reward_membership_plan_for_redeem' . $key,
                            'css' => 'min-width:150px;',
                            'std' => '100',
                            'type' => 'text',
                            'newids' => 'rs_reward_membership_plan_for_redeem' . $key,
                            'desc_tip' => true,
                        );
                    }
                    $updated_settings[] = array(
                        'type' => 'sectionend',
                        'id' => '_rs_membership_plan_for_redeem'
                    );
                }
            }
            return $updated_settings;
        }

        public static function rs_function_to_add_rule_for_redeeming_percentage() {
            global $woocommerce;
            wp_nonce_field(plugin_basename(__FILE__), 'rsdynamicrulecreation_for_redeem');
            ?>
            <table class="widefat fixed" cellspacing="0">
                <thead>
                    <tr class="rsdynamicrulecreation_for_redeem">
                        <th class="manage-column column-columnname" scope="col"><?php _e('Level Name', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Redeem Points Percentage', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Level', 'rewardsystem'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="rsdynamicrulecreation_for_redeem">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="manage-column column-columnname num" scope="col"> <span class="add_redeem button-primary"><?php _e('Add New Level', 'rewardsystem'); ?></span></td>
                    </tr>
                    <tr class="rsdynamicrulecreation_for_redeem">
                        <th class="manage-column column-columnname" scope="col"><?php _e('Level Name', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname" scope="col"><?php _e('Redeem Points Percentage', 'rewardsystem'); ?></th>
                        <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Level', 'rewardsystem'); ?></th>

                    </tr>
                </tfoot>
                <tbody id="here_redeem">
                    <?php
                    $rewards_dynamic_rulerule = get_option('rewards_dynamic_rule_for_redeem');
                    if (!empty($rewards_dynamic_rulerule)) {
                        if (is_array($rewards_dynamic_rulerule)) {
                            foreach ($rewards_dynamic_rulerule as $i => $rewards_dynamic_rule) {
                                ?>
                                <tr class="rsdynamicrulecreation_for_redeem">
                                    <td class="column-columnname">
                                        <p class="form-field">
                                            <input type="text" name="rewards_dynamic_rule_for_redeem[<?php echo $i; ?>][name]" class="short" value="<?php echo $rewards_dynamic_rule['name']; ?>"/>
                                        </p>
                                    </td>
                                    <td class="column-columnname">
                                        <p class="form-field">
                                            <input type="number" step="any" min="0" name="rewards_dynamic_rule_for_redeem[<?php echo $i; ?>][rewardpoints]" id="rewards_dynamic_rewardpoints<?php echo $i; ?>" class="short" value="<?php echo $rewards_dynamic_rule['rewardpoints']; ?>"/>
                                        </p>
                                    </td>
                                    <td class="column-columnname">
                                        <p class="form-field">
                                            <input type ="number" name="rewards_dynamic_rule_for_redeem[<?php echo $i; ?>][percentage]" id="rewards_dynamic_rule_percentage<?php echo $i; ?>" class="short test" value="<?php echo $rewards_dynamic_rule['percentage']; ?>"/>
                                        </p>
                                    </td>

                                    <td class="column-columnname num">
                                        <span class="remove_redeem button-secondary"><?php _e('Remove Level', 'rewardsystem'); ?></span>
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
                    jQuery(".add_redeem").on('click', function () {
                        var countrewards_dynamic_rule = Math.round(new Date().getTime() + (Math.random() * 100));
            <?php
            if ((float) $woocommerce->version > (float) ('2.2.0')) {
                if ($woocommerce->version >= (float) ('3.0.0')) {
                    ?>
                                jQuery('#here_redeem').append('<tr class="rsdynamicrulecreation_for_redeem"><td><p class="form-field"><input type="text" name="rewards_dynamic_rule_for_redeem[' + countrewards_dynamic_rule + '][name]" class="short" value=""/></p></td>\n\
                            \n\<td><p class="form-field"><input type="number" step="any" min="0" id="rewards_dynamic_ruleamount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_for_redeem[' + countrewards_dynamic_rule + '][rewardpoints]" class="short" value=""/></p></td>\n\
                            \n\\n<td><p class="form-field"><input type ="number" id="rewards_dynamic_rule_claimcount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_for_redeem[' + countrewards_dynamic_rule + '][percentage]" class="short test"  value=""/></p></td>\n\\n\
                            \n\ <td class="num"><span class="remove_redeem button-secondary">Remove Rule</span></td></tr><hr>');
                                jQuery('body').trigger('wc-enhanced-select-init');
                <?php } else {
                    ?>
                                jQuery('#here_redeem').append('<tr><td><p class="form-field"><input type="text" name="rewards_dynamic_rule_for_redeem[' + countrewards_dynamic_rule + '][name]" class="short" value=""/></p></td>\n\
                                                                                                                                                                                                                           \n\<td><p class="form-field"><input type="number" step="any" min="0" id="rewards_dynamic_ruleamount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_for_redeem[' + countrewards_dynamic_rule + '][rewardpoints]" class="short" value=""/></p></td>\n\
                                                                                                                                                                                                                 \n\\n\
                                                                                                                                                                                                                <td><p class="form-field"><input type ="number" id="rewards_dynamic_rule_claimcount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_for_redeem[' + countrewards_dynamic_rule + '][percentage]" class="short test"  value=""/></p></td>\n\\n\
                                                                                                                                                                                                                \n\ <td class="num"><span class="remove_redeem button-secondary">Remove Rule</span></td></tr><hr>');
                                jQuery('body').trigger('wc-enhanced-select-init');
                <?php } ?>
            <?php } else { ?>
                            jQuery('#here_redeem').append('<tr><td><p class="form-field"><input type="text" name="rewards_dynamic_rule_for_redeem[' + countrewards_dynamic_rule + '][name]" class="short" value=""/></p></td>\n\
                                                                                                                                                            \n\<td><p class="form-field"><input type="number" step="any" min="0" id="rewards_dynamic_ruleamount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_for_redeem[' + countrewards_dynamic_rule + '][rewardpoints]" class="short" value=""/></p></td>\n\
                                                                                                                                                            \n\\n\
                                                                                                                                                            <td><p class="form-field"><input type ="number" id="rewards_dynamic_rule_claimcount' + countrewards_dynamic_rule + '" name="rewards_dynamic_rule_for_redeem[' + countrewards_dynamic_rule + '][percentage]" class="short test"  value=""/></p></td>\n\\n\
                                                                                                                                                            \n\  <td class="num"><span class="remove_redeem button-secondary">Remove Rule</span></td></tr><hr>');

            <?php } ?>
                        return false;
                    });
                    jQuery(document).on('click', '.remove_redeem', function () {
                        jQuery(this).parent().parent().remove();
                    });
                    jQuery('#rs_enable_user_role_based_reward_points_for_redeem').addClass('rs_enable_user_role_based_reward_points_for_redeem');
                    jQuery('#rs_enable_earned_level_based_reward_points_for_redeem').addClass('rs_enable_earned_level_based_reward_points_for_redeem');
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
                if (isset($section['id']) && '_rs_user_role_reward_points_for_redeem' == $section['id'] &&
                        isset($section['type']) && 'sectionend' == $section['type']) {
                    foreach ($wp_roles->role_names as $value => $key) {
                        $updated_settings[] = array(
                            'name' => __('Reward Points Redeeming Percentage for ' . $key . ' User Role', 'rewardsystem'),
                            'desc' => __('Please Enter Percentage of Redeeming Reward Points for ' . $key, 'rewardsystem'),
                            'class' => 'rewardpoints_userrole_for_redeem',
                            'id' => 'rs_reward_user_role_for_redeem_' . $value,
                            'css' => 'min-width:150px;',
                            'std' => '100',
                            'default' => '100',
                            'type' => 'text',
                            'newids' => 'rs_reward_user_role_for_redeem_' . $value,
                            'desc_tip' => true,
                        );
                    }
                    $updated_settings[] = array(
                        'type' => 'sectionend', 'id' => '_rs_user_role_reward_points_for_redeem',
                    );
                }

                $updated_settings[] = $section;
            }

            return $updated_settings;
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_redeeming_module'] = __('Redeeming Module', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;
            //Section and option details
             if ( class_exists( 'WooCommerce_PDF_Invoices' ) ) {
                 $section_title = 'Message Settings in Edit Order Page and Invoices';
             } else {
                 $section_title = 'Message Settings in Edit Order Page';
             }
            $newcombinedarray = fp_rs_get_all_order_status();
            $categorylist = fp_rs_get_product_category();
            return apply_filters('woocommerce_rewardsystem_redeeming_module', array(
                 array(
                    'type' => 'rs_modulecheck_start',
                 ),
                 array(
                    'name' => __('Redeeming Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_activate_redeeming_module'
                ),
                array(
                    'type' => 'rs_enable_disable_redeeming_module',
                ),
                array(
                    'name' => __('Apply Redeeming Before Tax', 'rewardsystem'),
                    'desc' => 'Works with WooCommerce Versions 2.2 or older',
                    'id' => 'rs_apply_redeem_before_tax',                    
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_apply_redeem_before_tax',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                    'desc_tip' => false,
                ),
                array(
                    'name' => __('Free Shipping when Reward Points is Redeemed', 'rewardsystem'),
                    'id' => 'rs_apply_shipping_tax',                    
                    'std' => '2',
                    'default' => '2',
                    'type' => 'select',
                    'newids' => 'rs_apply_shipping_tax',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_activate_redeeming_module'),
                array(
                    'type' => 'rs_modulecheck_end',
                ),		
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Redeeming Order Status Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_redeeming_status_setting',
                ),
                array(
                    'name' => __('Redeemed Points will be deducted when the Order Status reaches', 'rewardsystem'),
                    'desc' => __('This option controls when the points redeemed in order should be deducted from user\'s account', 'rewardsystem'),
                    'id' => 'rs_order_status_control_redeem',                    
                    'std' => array('completed', 'pending', 'processing', 'on-hold'),
                    'default' => array('completed', 'pending', 'processing', 'on-hold'),
                    'type' => 'multiselect',
                    'options' => $newcombinedarray,
                    'newids' => 'rs_order_status_control_redeem',
                    'desc_tip' => true,
                ),                
                array('type' => 'sectionend', 'id' => 'rs_redeeming_status_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Member Level Settings for Redeeming', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_member_level_setting_for_redeem',
                ),
                array(
                    'name' => __('Priority Level Selection', 'rewardsystem'),
                    'desc' => __('If more than one type(level) is enabled then use the highest/lowest percentage', 'rewardsystem'),
                    'id' => 'rs_choose_priority_level_selection_for_redeem',
                    'class' => 'rs_choose_priority_level_selection_for_redeem',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'radio',
                    'newids' => 'rs_choose_priority_level_selection_for_redeem',
                    'options' => array(
                        '1' => __('Use the level that gives highest percentage', 'rewardsystem'),
                        '2' => __('Use the level that gives lowest percentage', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => 'rs_member_level_setting_for_redeem', 'class' => 'rs_member_level_setting_for_redeem'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Redeeming Percentage based on User Role', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_user_role_reward_points_for_redeem',
                ),
                array(
                    'name' => __('User Role based Redeeming Level', 'rewardsystem'),
                    'desc' => __('Enable this option to modify Redeeming points based on user role', 'rewardsystem'),
                    'id' => 'rs_enable_user_role_based_reward_points_for_redeem',
                    'css' => 'min-width:150px;',
                    'std' => 'yes',
                    'default' => 'yes',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_user_role_based_reward_points_for_redeem',
                ),
                array('type' => 'sectionend', 'id' => '_rs_user_role_reward_points_for_redeem'),
                array(
                    'type' => 'rs_wrapper_end',
                ),                
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Redeeming Percentage based on Earned Points', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_member_level_earning_points_for_redeem',
                ),
                array(
                    'name' => __('Points to Redeem based on Earned Points', 'rewardsystem'),
                    'desc' => __('Enable this option to modify Redeeming Points percentage based on earned points', 'rewardsystem'),
                    'id' => 'rs_enable_redeem_level_based_reward_points',
                    'css' => 'min-width:150px;',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_earned_level_based_reward_points_for_redeem',
                ),
                array(
                    'name' => __('Earned Points is decided', 'rewardsystem'),
                    'id' => 'rs_select_redeem_points_based_on',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'type' => 'select',
                    'newids' => 'rs_select_redeem_points_based_on',
                    'options' => array(
                        '1' => __('Based on Total Earned Points', 'rewardsystem'),
                        '2' => __('Based on Current Points', 'rewardsystem')),
                ),
                array(
                    'type' => 'rs_user_role_dynamics_for_redeem',
                ),
                array('type' => 'sectionend', 'id' => '_rs_member_level_earning_points_for_redeem'),                
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Redeeming Percentage based on Purchase History', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_member_level_redeem_points_purchase_history',
                ),
                array(
                    'name' => __('Purchase History based on Redeeming Level', 'rewardsystem'),
                    'desc' => __('Enable this option to modify Redeeming points based on Purchase history', 'rewardsystem'),
                    'id' => 'rs_enable_user_purchase_history_based_reward_points_redeem',
                    'css' => 'min-width:150px;',
                    'std' => 'yes',
                    'default' => 'yes',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_user_purchase_history_based_reward_points_redeem',
                ),
                array(
                    'type' => 'rs_user_purchase_history_redeem',
                ),
                array('type' => 'sectionend', 'id' => '_rs_member_level_earning_points_purchase_history'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Redeeming Settings for Cart Page', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_redeem_settings'
                ),
                array(
                    'name' => __('Enable Automatic Points Redeeming in Cart Page', 'rewardsystem'),
                    'desc' => __('When enabled, available reward points will be automatically applied on cart to get a discount', 'rewardsystem'),
                    'id' => 'rs_enable_disable_auto_redeem_points',
                    'type' => 'checkbox',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_enable_disable_auto_redeem_points',
                ),
                array(
                    'name' => __('Enable Automatic Points Redeeming in Checkout Page', 'rewardsystem'),
                    'desc' => __('When enabled, available reward points will be automatically applied on checkout to get a discount when the page is redirected to checkout directly from shop page', 'rewardsystem'),
                    'id' => 'rs_enable_disable_auto_redeem_checkout',
                    'type' => 'checkbox',
                    'std' => 'no',
                    'default' => 'no',
                    'newids' => 'rs_enable_disable_auto_redeem_checkout',
                ),
                array(
                    'name' => __('Manual Redeeming Field Type', 'rewardsystem'),
                    'id' => 'rs_redeem_field_type_option',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_redeem_field_type_option',
                    'options' => array(
                        '1' => __('Default', 'rewardsystem'),
                        '2' => __('Button', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Percentage of Cart Total to be Redeemed', 'rewardsystem'),
                    'desc' => __('Enter the Percentage of the cart total that has to be Redeemed', 'rewardsystem'),
                    'id' => 'rs_percentage_cart_total_redeem',                    
                    'std' => '100 ',
                    'default' => '100',
                    'type' => 'text',
                    'newids' => 'rs_percentage_cart_total_redeem',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Redeeming Button Notice', 'rewardsystem'),
                    'id' => 'rs_redeeming_button_option_message',                    
                    'std' => '[cartredeempoints] points worth of [currencysymbol] [pointsvalue] will be Redeemed',
                    'default' => '[cartredeempoints] points worth of [currencysymbol] [pointsvalue] will be Redeemed',
                    'type' => 'textarea',
                    'newids' => 'rs_redeeming_button_option_message',
                ),                                
                array('type' => 'sectionend', 'id' => '_rs_redeem_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Redeeming Settings for Checkout Page', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_restriction_in_checkout'
                ),                
                array(
                    'name' => __('Show/hide Redeeming Field in Checkout Page', 'rewardsystem'),
                    'id' => 'rs_show_hide_redeem_field_checkout',
                    'std' => '2',
                    'default' => '2',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_redeem_field_checkout',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Manual Redeeming Field Type', 'rewardsystem'),
                    'id' => 'rs_redeem_field_type_option_checkout',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_redeem_field_type_option_checkout',
                    'options' => array(
                        '1' => __('Default', 'rewardsystem'),
                        '2' => __('Button', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Percentage of Cart Total to be Redeemed', 'rewardsystem'),
                    'desc' => __('Enter the Percentage of the cart total that has to be Redeemed', 'rewardsystem'),
                    'id' => 'rs_percentage_cart_total_redeem_checkout',                    
                    'std' => '100 ',
                    'default' => '100',
                    'type' => 'text',
                    'newids' => 'rs_percentage_cart_total_redeem_checkout',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Show/Hide WooCommerce Coupon Field', 'rewardsystem'),
                    'id' => 'rs_show_hide_coupon_field_checkout',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_coupon_field_checkout',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),                
                array(
                    'name' => __('Redeeming Field label', 'rewardsystem'),
                    'desc' => __('This Text will be displayed as redeeming field label in checkout page', 'rewardsystem'),
                    'id' => 'rs_reedming_field_label_checkout',                    
                    'std' => 'Have Reward Points ?',
                    'default' => 'Have Reward Points ?',
                    'type' => 'text',
                    'newids' => 'rs_reedming_field_label_checkout',
                    'class' => 'rs_reedming_field_label_checkout',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Redeeming Field Link label', 'rewardsystem'),
                    'desc' => __('This Text will be displayed as redeeming field link label in checkout page', 'rewardsystem'),
                    'id' => 'rs_reedming_field_link_label_checkout',                    
                    'std' => 'Redeem it',
                    'default' => 'Redeem it',
                    'type' => 'text',
                    'newids' => 'rs_reedming_field_link_label_checkout',
                    'class' => 'rs_reedming_field_link_label_checkout',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Redeeming Link Call to Action', 'rewardsystem'),
                    'desc' => __('Show/Hide Redeem It Link Call To Action in WooCommerce', 'rewardsystem'),
                    'id' => 'rs_show_hide_redeem_it_field_checkout',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_redeem_it_field_checkout',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Redeeming Button Message ', 'rewardsystem'),
                    'desc' => __('Enter the Message for the Redeeming Button', 'rewardsystem'),
                    'id' => 'rs_redeeming_button_option_message_checkout',                    
                    'std' => '[cartredeempoints] points worth of [currencysymbol] [pointsvalue] will be Redeemed',
                    'default' => '[cartredeempoints] points worth of [currencysymbol] [pointsvalue] will be Redeemed',
                    'type' => 'textarea',
                    'newids' => 'rs_redeeming_button_option_message_checkout',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_restriction_in_checkout'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Redeeming Settings for Cart and Checkout Page', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_restriction_in_cart_and_checkout'
                ),
                array(
                    'name' => __('Redeeming Field Label', 'rewardsystem'),
                    'id' => 'rs_show_hide_redeem_caption',                    
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_redeem_caption',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Redeeming Field Label', 'rewardsystem'),
                    'desc' => __('Enter the Label which will be displayed in Redeem Field', 'rewardsystem'),
                    'id' => 'rs_redeem_field_caption',                    
                    'std' => 'Redeem your Reward Points:',
                    'default' => 'Redeem your Reward Points:',
                    'type' => 'text',
                    'newids' => 'rs_redeem_field_caption',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Show/Hide Redeeming Field Placeholder', 'rewardsystem'),
                    'id' => 'rs_show_hide_redeem_placeholder',                    
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_redeem_placeholder',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Placeholder', 'rewardsystem'),
                    'desc' => __('Enter the Placeholder which will be displayed in Redeem Field', 'rewardsystem'),
                    'id' => 'rs_redeem_field_placeholder',                    
                    'std' => 'Reward Points to Enter',
                    'default' => 'Reward Points to Enter',
                    'type' => 'text',
                    'newids' => 'rs_redeem_field_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Redeeming Field Submit Button Caption', 'rewardsystem'),
                    'desc' => __('Enter the Label which will be displayed in Submit Button', 'rewardsystem'),
                    'id' => 'rs_redeem_field_submit_button_caption',                    
                    'std' => 'Apply Reward Points',
                    'default' => 'Apply Reward Points',
                    'type' => 'text',
                    'newids' => 'rs_redeem_field_submit_button_caption',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Coupon Label Settings', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed in Cart Subtotal', 'rewardsystem'),
                    'id' => 'rs_coupon_label_message',                    
                    'std' => 'Redeemed Points Value',
                    'default' => 'Redeemed Points Value',
                    'type' => 'text',
                    'newids' => 'rs_coupon_label_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Extra Class Name for Redeeming Field Submit Button', 'rewardsystem'),
                    'desc' => __('Add Extra Class Name to the Cart Apply Reward Points Button, Don\'t Enter dot(.) before Class Name', 'rewardsystem'),
                    'id' => 'rs_extra_class_name_apply_reward_points',                    
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_extra_class_name_apply_reward_points',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_restriction_in_cart_and_checkout'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Redeeming Restriction','rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_cart_remaining_setting'
                ),                
                array(
                    'name' => __('Redeeming/WooCommerce Coupon Field display', 'rewardsystem'),
                    'id' => 'rs_show_hide_redeem_field',
                    'css' => '',
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'newids' => 'rs_show_hide_redeem_field',
                    'options' => array(
                        '1' => __('Display Both', 'rewardsystem'),
                        '2' => __('Hide coupon', 'rewardsystem'),
                        '3' => __('Hide Redeem', 'rewardsystem'),
                        '4' => __('Hide Both', 'rewardsystem'),
                        '5' => __('Hide one when use', 'rewardsystem')
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Redeemed Points is applied on', 'rewardsystem'),
                    'id' => 'rs_apply_redeem_basedon_cart_or_product_total',
                    'newids' => 'rs_apply_redeem_basedon_cart_or_product_total',
                    'class' => 'rs_apply_redeem_basedon_cart_or_product_total',                    
                    'std' => '1',
                    'default' => '1',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Cart Subtotal', 'rewardsystem'),
                        '2' => __('Product Total', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Enable Redeeming for Selected Products', 'rewardsystem'),
                    'id' => 'rs_enable_redeem_for_selected_products',                    
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_redeem_for_selected_products',
                ),
                array(
                    'type' => 'include_product_selection',
                ),
                array(
                    'name' => __('Products excluded from Redeeming', 'rewardsystem'),
                    'id' => 'rs_exclude_products_for_redeeming',
                    'class' => 'rs_exclude_products_for_redeeming',
                    'std' => '',
                    'default' => '',
                    'type' => 'checkbox',
                    'newids' => 'rs_exclude_products_for_redeeming',
                ),
                array(
                    'type' => 'exclude_product_selection',
                ),
                array(
                    'name' => __('Enable Redeeming for Selected Category', 'rewardsystem'),
                    'id' => 'rs_enable_redeem_for_selected_category',
                    'class' => 'rs_enable_redeem_for_selected_category',
                    'std' => '',
                    'default' => '',
                    'type' => 'checkbox',
                    'newids' => 'rs_enable_redeem_for_selected_category',
                ),
                array(
                    'name' => __('Categories allowed for Redeeming', 'rewardsystem'),
                    'desc' => __('Select Category to enable redeeming', 'rewardsystem'),
                    'id' => 'rs_select_category_to_enable_redeeming',
                    'class' => 'rs_select_category_to_enable_redeeming',
                    'css' => 'min-width:350px',
                    'std' => '',
                    'default' => '',
                    'type' => 'multiselect',
                    'newids' => 'rs_select_category_to_enable_redeeming',
                    'options' => $categorylist,
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Exclude Category for Redeeming', 'rewardsystem'),
                    'id' => 'rs_exclude_category_for_redeeming',
                    'std' => '',
                    'default' => '',
                    'type' => 'checkbox',
                    'newids' => 'rs_exclude_category_for_redeeming',
                ),
                array(
                    'name' => __('Categories excluded from Redeeming', 'rewardsystem'),
                    'desc' => __('Select Category to enable redeeming', 'rewardsystem'),
                    'id' => 'rs_exclude_category_to_enable_redeeming',
                    'class' => 'rs_exclude_category_to_enable_redeeming',
                    'css' => 'min-width:350px',
                    'std' => '',
                    'default' => '',
                    'type' => 'multiselect',
                    'newids' => 'rs_exclude_category_to_enable_redeeming',
                    'options' => $categorylist,
                    'desc_tip' => true,
                ),                                
                array(
                    'name' => __('Maximum Redeeming Threshold Percentage for Auto Redeeming', 'rewardsystem'),
                    'desc' => __('Enter the Percentage of the cart total that has to be Auto Redeemed', 'rewardsystem'),
                    'id' => 'rs_percentage_cart_total_auto_redeem',                    
                    'std' => '100 ',
                    'default' => '100',
                    'type' => 'text',
                    'newids' => 'rs_percentage_cart_total_auto_redeem',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Maximum Redeeming Threshold Value (Discount) Type', 'rewardsystem'),
                    'id' => 'rs_max_redeem_discount',                    
                    'std' => '',
                    'default' => '',
                    'newids' => 'rs_max_redeem_discount',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed Value', 'rewardsystem'),
                        '2' => __('By Percentage of Cart Total', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Maximum Redeeming Threshold Value (Discount) for Order in ' . get_woocommerce_currency_symbol(), 'rewardsystem'),
                    'desc' => __('Enter a Fixed or Decimal Number greater than 0', 'rewardsystem'),
                    'id' => 'rs_fixed_max_redeem_discount',                    
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_fixed_max_redeem_discount',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Maximum Redeeming Threshold Value (Discount) for Order in Percentage %', 'rewardsystem'),
                    'desc' => __('Enter a Fixed or Decimal Number greater than 0', 'rewardsystem'),
                    'id' => 'rs_percent_max_redeem_discount',                    
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_percent_max_redeem_discount',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Minimum Points required for Redeeming for the First Time', 'rewardsystem'),
                    'desc' => __('Enter Minimum Points to be Earned for Redeeming First Time in Cart/Checkout', 'rewardsystem'),
                    'id' => 'rs_first_time_minimum_user_points',                    
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_first_time_minimum_user_points',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Show/Hide First Time Redeeming Minimum Points Required Warning Message', 'rewardsystem'),
                    'id' => 'rs_show_hide_first_redeem_error_message',                    
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_first_redeem_error_message',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Error Message', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when the user doesn\'t have enough points for first time redeeming', 'rewardsystem'),
                    'id' => 'rs_min_points_first_redeem_error_message',                    
                    'std' => 'You need Minimum of [firstredeempoints] Points when redeeming for the First time',
                    'default' => 'You need Minimum of [firstredeempoints] Points when redeeming for the First time',
                    'type' => 'textarea',
                    'newids' => 'rs_min_points_first_redeem_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Minimum Points required for Redeeming after First Redeeming', 'rewardsystem'),
                    'id' => 'rs_minimum_user_points_to_redeem',                    
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_minimum_user_points_to_redeem',
                ),
                array(
                    'name' => __('Show/Hide Minimum Points required for Redeeming after First Redeeming', 'rewardsystem'),
                    'id' => 'rs_show_hide_after_first_redeem_error_message',                    
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_after_first_redeem_error_message',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Error Message', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when the Current User doesn\'t have minimum points for Redeeming ', 'rewardsystem'),
                    'id' => 'rs_min_points_after_first_error',                    
                    'std' => 'You need minimum of [points_after_first_redeem] Points for Redeeming',
                    'default' => 'You need minimum of [points_after_first_redeem] Points for Redeeming',
                    'type' => 'textarea',
                    'newids' => 'rs_min_points_after_first_error',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Minimum Points to be entered for Redeeming', 'rewardsystem'),
                    'id' => 'rs_minimum_redeeming_points',                    
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_minimum_redeeming_points',
                ),
                array(
                    'name' => __('Maximum Points above which points cannot be Redeemed', 'rewardsystem'),
                    'id' => 'rs_maximum_redeeming_points',                    
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_maximum_redeeming_points',
                ),
                array(
                    'name' => __('Minimum Cart Total to Redeem Point(s)', 'rewardsystem'),
                    'id' => 'rs_minimum_cart_total_points',                    
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_minimum_cart_total_points',
                ),
                array(
                    'name' => __('Show/Hide Minimum Cart Total to Redeem Point(s)', 'rewardsystem'),
                    'id' => 'rs_show_hide_minimum_cart_total_error_message',                    
                    'std' => '1',
                    'default' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_minimum_cart_total_error_message',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),                
                array(
                    'name' => __('Error Message', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when current Cart total is less than minimum Cart Total for Redeeming', 'rewardsystem'),
                    'id' => 'rs_min_cart_total_redeem_error',                    
                    'std' => 'You need minimum cart Total of [currencysymbol][carttotal] in order to Redeem',
                    'default' => 'You need minimum cart Total of [currencysymbol][carttotal] in order to Redeem',
                    'type' => 'textarea',
                    'newids' => 'rs_min_cart_total_redeem_error',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Maximum Cart Total to Redeem Point(s)', 'rewardsystem'),
                    'id' => 'rs_maximum_cart_total_points',                    
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_maximum_cart_total_points',
                ),
                array(
                    'name' => __('Show/Hide Maximum Cart Total to Redeem Point(s)', 'rewardsystem'),
                    'id' => 'rs_show_hide_maximum_cart_total_error_message',                    
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_maximum_cart_total_error_message',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),                
                array(
                    'name' => __('Error Message', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when current Cart total is less than Maximum Cart Total for Redeeming', 'rewardsystem'),
                    'id' => 'rs_max_cart_total_redeem_error',                    
                    'std' => 'You Cannot Redeem Points Because you Reach the Maximum Cart total [currencysymbol][carttotal]',
                    'default' => 'You Cannot Redeem Points Because you Reach the Maximum Cart total [currencysymbol][carttotal]',
                    'type' => 'textarea',
                    'newids' => 'rs_max_cart_total_redeem_error',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Prevent Coupon Usage when points are redeemed', 'rewardsystem'),
                    'id' => 'rs_coupon_applied_individual',
                    'class' => 'rs_coupon_applied_individual',
                    'newids' => 'rs_coupon_applied_individual',                    
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'checkbox',
                    'desc' => __('Enable this option to prevent coupon usage when points are redeemed', 'rewardsystem'),
                ),
                array(
                    'name' => __('Error Message', 'rewardsystem'),
                    'desc' => __('Text for Error Message for redeeming Coupon When applied with other coupon', 'rewardsystem'),
                    'id' => 'rs_coupon_applied_individual_error_msg',
                    'class' => 'rs_coupon_applied_individual_error_msg',
                    'newids' => 'rs_coupon_applied_individual_error_msg',
                    'css' => 'min-width:400px;',
                    'std' => 'Coupon cannot be applied when points are redeemed',
                    'default' => 'Coupon cannot be applied when points are redeemed',
                    'type' => 'textarea',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_cart_remaining_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __("$section_title", 'rewardsystem'),
                    'type' => 'title',                    
                    'id' => '_rs_err_msg_setting_in_edit_order'
                ),
                array(
                    'name' => __('Display Redeemed Points', 'rewardsystem'),
                    'desc' => __('Enable Message for Redeem Points', 'rewardsystem'),
                    'id' => 'rs_enable_msg_for_redeem_points',
                    'newids' => 'rs_enable_msg_for_redeem_points',
                    'class' => 'rs_enable_msg_for_redeem_points',
                    'type' => 'checkbox',
                ),
                array(
                    'name' => __('Message to Redeemed Points', 'rewardsystem'),
                    'desc' => __('Message to Redeemed Points', 'rewardsystem'),
                    'id' => 'rs_msg_for_redeem_points',
                    'newids' => 'rs_msg_for_redeem_points',
                    'class' => 'rs_msg_for_redeem_points',                    
                    'std' => 'Points Redeemed in this Order [redeempoints]',
                    'default' => 'Points Redeemed in this Order [redeempoints]',
                    'type' => 'textarea',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_err_msg_setting_in_edit_order'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Error Message Settings for Redeeming Field', 'rewardsystem'),
                    'type' => 'title',                    
                    'id' => '_rs_err_msg_setting'
                ),
                array(
                    'name' => __('Error Message to display when User enters less than Minimum Points[Default Type]', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when Entered Points is less than Minimum Redeeming Points which is set in this Page', 'rewardsystem'),
                    'id' => 'rs_minimum_redeem_point_error_message',                    
                    'std' => 'Please Enter Points more than [rsminimumpoints]',
                    'default' => 'Please Enter Points more than [rsminimumpoints]',
                    'type' => 'text',
                    'newids' => 'rs_minimum_redeem_point_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when User enters more than Maximum Points[Default Type]', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when Entered Points is more than Maximum Redeeming Points which is set in this Page', 'rewardsystem'),
                    'id' => 'rs_maximum_redeem_point_error_message',                    
                    'std' => 'Please Enter Points less than [rsmaximumpoints]',
                    'default' => 'Please Enter Points less than [rsmaximumpoints]',
                    'type' => 'text',
                    'newids' => 'rs_maximum_redeem_point_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when User enters less than the Minimum Points  or more than Maximum Points[Default Type]', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when Maximum and Minimum Redeeming Points are Equal which is set in this Page', 'rewardsystem'),
                    'id' => 'rs_minimum_and_maximum_redeem_point_error_message',                    
                    'std' => 'Please Enter [rsequalpoints] Points',
                    'default' => 'Please Enter [rsequalpoints] Points',
                    'type' => 'text',
                    'newids' => 'rs_minimum_and_maximum_redeem_point_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when User enters less than Minimum Points[Button Type]', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when Entered Points is less than Minimum Redeeming Points which is set in this Page', 'rewardsystem'),
                    'id' => 'rs_minimum_redeem_point_error_message_for_button_type',                    
                    'std' => 'You cannot redeem because the current points to be redeemed is less than [rsminimumpoints] Points',
                    'default' => 'You cannot redeem because the current points to be redeemed is less than [rsminimumpoints] Points',
                    'type' => 'text',
                    'newids' => 'rs_minimum_redeem_point_error_message_for_button_type',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when User enters more than Maximum Points[Button Type]', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when Entered Points is more than Maximum Redeeming Points which is set in this Page', 'rewardsystem'),
                    'id' => 'rs_maximum_redeem_point_error_message_for_button_type',                    
                    'std' => 'You cannot redeem because the current points to be redeemed is more than [rsmaximumpoints] points',
                    'default' => 'You cannot redeem because the current points to be redeemed is more than [rsmaximumpoints] points',
                    'type' => 'text',
                    'newids' => 'rs_maximum_redeem_point_error_message_for_button_type',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Error Message to display when User enters less than the Minimum Points  or more than Maximum Points[Button Type]', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when Maximum and Minimum Redeeming Points are Equal which is set in this Page', 'rewardsystem'),
                    'id' => 'rs_minimum_and_maximum_redeem_point_error_message_for_buttontype',                    
                    'std' => 'You cannot redeem because the points to be redeemed is not equal to [rsequalpoints] Points ',
                    'default' => 'You cannot redeem because the points to be redeemed is not equal to [rsequalpoints] Points',
                    'type' => 'text',
                    'newids' => 'rs_minimum_and_maximum_redeem_point_error_message_for_buttontype',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Redeeming Field Empty Error Message', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when Redeem Field has Empty Value', 'rewardsystem'),
                    'id' => 'rs_redeem_empty_error_message',                    
                    'std' => 'No Reward Points Entered',
                    'default' => 'No Reward Points Entered',
                    'type' => 'text',
                    'newids' => 'rs_redeem_empty_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Unwanted Characters in Redeeming Field Error Message', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when redeeming field value contain characters', 'rewardsystem'),
                    'id' => 'rs_redeem_character_error_message',                    
                    'std' => 'Please Enter Only Numbers',
                    'default' => 'Please Enter Only Numbers',
                    'type' => 'text',
                    'newids' => 'rs_redeem_character_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Insufficient Points for Redeeming Error Message', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when Entered Reward Points is more than Earned Reward Points', 'rewardsystem'),
                    'id' => 'rs_redeem_max_error_message',                    
                    'std' => 'Reward Points you entered is more than Your Earned Reward Points ',
                    'default' => 'Reward Points you entered is more than Your Earned Reward Points ',
                    'type' => 'text',
                    'newids' => 'rs_redeem_max_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Current User Points is Empty Error Message', 'rewardsystem'),
                    'id' => 'rs_show_hide_points_empty_error_message',                    
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'rs_show_hide_points_empty_error_message',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'rewardsystem'),
                        '2' => __('Hide', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Error Message', 'rewardsystem'),
                    'desc' => __('Enter the Message which will be displayed when the Current User Points is Empty', 'rewardsystem'),
                    'id' => 'rs_current_points_empty_error_message',                    
                    'std' => 'You don\'t have Points for Redeeming',
                    'default' => 'You don\'t have Points for Redeeming',
                    'type' => 'text',
                    'newids' => 'rs_current_points_empty_error_message',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_rs_err_msg_setting'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shortcodes used in Redeeming Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_shortcodes_in_checkout',
                ),
                array(
                    'type' => 'title',
                    'desc' => '<b>[cartredeempoints]</b> - To display points can redeem based on cart total amount<br><br>'
                    . '<b>[currencysymbol]</b> - To display currency symbol<br><br>'
                    . '<b>[pointsvalue]</b> - To display currency value equivalent of redeeming points<br><br>'
                    . '<b>[productname]</b> - To display product name<br><br>'                    
                    . '<b>[firstredeempoints] </b> - To display points required for first time redeeming<br><br>'
                    . '<b>[points_after_first_redeem]</b> - To display points required after first redeeming<br><br>'
                    . '<b>[rsminimumpoints]</b> - To display minimum points required to redeem<br><br>'
                    . '<b>[rsmaximumpoints]</b> - To display maximum points required to redeem<br><br>'
                    . '<b>[rsequalpoints]</b> - To display exact points to redeem<br><br>'
                    . '<b>[carttotal]</b> - To display cart total value<br><br>',                
                ),
                array('type' => 'sectionend', 'id' => 'rs_shortcodes_in_checkout'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {
            woocommerce_admin_fields(RSRedeemingModule::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSRedeemingModule::reward_system_admin_fields());            
            if (isset($_POST['rs_select_products_to_enable_redeeming'])) {
                update_option('rs_select_products_to_enable_redeeming', $_POST['rs_select_products_to_enable_redeeming']);
            }else{
                update_option('rs_select_products_to_enable_redeeming', '');
            }
            if (isset($_POST['rs_exclude_products_to_enable_redeeming'])) {
                update_option('rs_exclude_products_to_enable_redeeming', $_POST['rs_exclude_products_to_enable_redeeming']);
            }else{
                update_option('rs_exclude_products_to_enable_redeeming', '');
            }
            if (isset($_POST['rs_select_product_for_hide_gateway'])) {
                update_option('rs_select_product_for_hide_gateway', $_POST['rs_select_product_for_hide_gateway']);
            }else{
                update_option('rs_select_product_for_hide_gateway', '');
            }
            if (isset($_POST['rs_select_product_for_purchase_using_points'])) {
                update_option('rs_select_product_for_purchase_using_points', $_POST['rs_select_product_for_purchase_using_points']);
            }else{
                update_option('rs_select_product_for_purchase_using_points', '');
            }
            if (isset($_POST['rs_redeeming_module_checkbox'])) {
                update_option('rs_redeeming_activated', $_POST['rs_redeeming_module_checkbox']);
            } else {
                update_option('rs_redeeming_activated', 'no');
            }
            if (isset($_POST['rewards_dynamic_rule_for_redeem'])) {
                update_option('rewards_dynamic_rule_for_redeem', $_POST['rewards_dynamic_rule_for_redeem']);
            } else {
                update_option('rewards_dynamic_rule_for_redeem', '');
            }
            if (isset($_POST['rewards_dynamic_rule_purchase_history_redeem'])) {
                update_option('rewards_dynamic_rule_purchase_history_redeem', $_POST['rewards_dynamic_rule_purchase_history_redeem']);
            } else {
                update_option('rewards_dynamic_rule_purchase_history_redeem', '');
            }
            
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSRedeemingModule::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }
        
        public static function rs_function_to_redeeming_module() {
            $settings = RSRedeemingModule::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);            
            update_option('rs_redeem_point', '1');
            update_option('rs_redeem_point_value', '1');            
        }
        
        public static function rs_function_to_enable_disable_redeeming_module() {
            $get_option_value = get_option('rs_redeeming_activated');
            $name_of_checkbox = 'rs_redeeming_module_checkbox';
            RSModulesTab::rs_common_function_to_get_checkbox($get_option_value, $name_of_checkbox);
        }
        
        public static function setting_for_hide_redeem_field_when_sumo_discount_is_active($settings) {
            $updated_settings = array();
            foreach ($settings as $section) {
                if (isset($section['id']) && ('_rs_cart_remaining_setting' === $section['id']) &&
                        isset($section['type']) && ('sectionend' === $section['type'])) {
                    $updated_settings[] = array(
                        'name' => __('Show Redeeming Field', 'rewardsystem'),
                        'id' => 'rs_show_redeeming_field',
                        'std' => '1',
                        'default' => '1',
                        'type' => 'select',
                        'newids' => 'rs_show_redeeming_field',
                        'options' => array(
                            '1' => __('Always', 'rewardsystem'),
                            '2' => __('When Price is not altered through SUMO Discounts Plugin', 'rewardsystem'),
                        ),
                        'desc_tip' => true,
                    );
                }
                $updated_settings[] = $section;
            }
            return $updated_settings;
        }

        public static function validate_maximum_minimum() {
            if (isset($_GET['tab']) && isset($_GET['section'])) {
                if ($_GET['section'] == 'rewardsystem_redeeming_module') {
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery('#rs_maximum_cart_total_points').keyup(function () {
                                var maximum_cart_total_redeem = jQuery('#rs_maximum_cart_total_points').val();
                                if (maximum_cart_total_redeem != '') {
                                    jQuery('#rs_maximum_cart_total_points').val(maximum_cart_total_redeem);

                                }
                            });
                            jQuery('#rs_minimum_cart_total_points').keyup(function () {
                                var mimimum_cart_total_redeem = jQuery('#rs_minimum_cart_total_points').val();
                                if (mimimum_cart_total_redeem != '') {
                                    jQuery('#rs_minimum_cart_total_points').val(mimimum_cart_total_redeem);
                                }
                            });


                            jQuery('#rs_maximum_cart_total_for_earning').keyup(function () {
                                var maximum_cart_total_earn = jQuery('#rs_maximum_cart_total_for_earning').val();
                                if (maximum_cart_total_earn != '') {
                                    jQuery('#rs_maximum_cart_total_for_earning').val(maximum_cart_total_earn);

                                }
                            });
                            jQuery('#rs_minimum_cart_total_for_earning').keyup(function () {
                                var mimimum_cart_total_earn = jQuery('#rs_minimum_cart_total_for_earning').val();
                                if (mimimum_cart_total_earn != '') {
                                    jQuery('#rs_minimum_cart_total_for_earning').val(mimimum_cart_total_earn);
                                }
                            });

                            jQuery('.button-primary').click(function (e) {
                                if (jQuery('#rs_maximum_cart_total_points').val() != '' && jQuery('#rs_minimum_cart_total_points').val() != '') {
                                    var maximum_cart_total_redeem = Number(jQuery('#rs_maximum_cart_total_points').val());
                                    var mimimum_cart_total_redeem = Number(jQuery('#rs_minimum_cart_total_points').val());
                                    if (maximum_cart_total_redeem < mimimum_cart_total_redeem) {
                                        e.preventDefault();
                                        jQuery('#rs_maximum_cart_total_points').focus();
                                        jQuery("#rs_maximum_cart_total_points").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter cart total greater than mimimum cart total for redeem points</div>");

                                    }
                                }
                                if (jQuery('#rs_maximum_cart_total_for_earning').val() != '' && jQuery('#rs_minimum_cart_total_for_earning').val() != '') {
                                    var maximum_cart_total_redeem = Number(jQuery('#rs_maximum_cart_total_for_earning').val());
                                    var mimimum_cart_total_redeem = Number(jQuery('#rs_minimum_cart_total_for_earning').val());
                                    if (maximum_cart_total_redeem < mimimum_cart_total_redeem) {
                                        e.preventDefault();
                                        jQuery('#rs_maximum_cart_total_for_earning').focus();
                                        jQuery("#rs_maximum_cart_total_for_earning").after("<div class='validation1' style='color:red;margin-bottom: 20px;'>Please enter cart total greater than mimimum cart total for earn points</div>");

                                    }
                                }
                                jQuery('#rs_maximum_cart_total_points').keyup(function () {
                                    jQuery(".validation").hide();

                                });
                                jQuery('#rs_maximum_cart_total_for_earning').keyup(function () {
                                    jQuery(".validation1").hide();
                                });

                            });
                        });
                    </script>
                    <?php
                }
            }
        }

        public static function rs_redeeming_selected_products_categories() {
            global $woocommerce;
            if (isset($_GET['tab']) && isset($_GET['section'])) {
                if ($_GET['section'] == 'rewardsystem_redeeming_module') {
                    echo rs_common_ajax_function_to_select_products('rs_ajax_chosen_select_products_redeem');
                    if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                        echo rs_common_chosen_function('#rs_select_category_to_enable_redeeming');
                        echo rs_common_chosen_function('#rs_exclude_category_to_enable_redeeming');
                        echo rs_common_chosen_function('#rs_select_category_for_purchase_using_points');
                        echo rs_common_chosen_function('#rs_order_status_control_redeem');
                    } else {
                        echo rs_common_select_function('#rs_select_category_to_enable_redeeming');
                        echo rs_common_select_function('#rs_exclude_category_to_enable_redeeming');
                        echo rs_common_select_function('#rs_select_category_for_purchase_using_points');
                        echo rs_common_select_function('#rs_order_status_control_redeem');
                    }
                }
            }
        }

        /*
         * Function to select products to exclude
         */

        public static function rs_select_product_to_exclude() {
            $field_id = "rs_exclude_products_to_enable_redeeming";
            $field_label = "Products excluded from Redeeming";
            $getproducts = get_option('rs_exclude_products_to_enable_redeeming');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        /*
         * Function to select products to include
         */

        public static function rs_select_product_to_include() {
            $field_id = "rs_select_products_to_enable_redeeming";
            $field_label = "Products allowed for Redeeming";
            $getproducts = get_option('rs_select_products_to_enable_redeeming');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        /*
         * Function to select the products which are going to be buy using Reward Points
         */

        public static function rs_purchase_selected_product_using_points() {
            $field_id = "rs_select_product_for_purchase_using_points";
            $field_label = "Select Product(s)";
            $getproducts = get_option('rs_select_product_for_purchase_using_points');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        public static function rs_selected_product_hide_gateway() {
            $field_id = "rs_select_product_for_hide_gateway";
            $field_label = "Select Product(s)";
            $getproducts = get_option('rs_select_product_for_hide_gateway');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        public static function rs_purchase_product_using_point() {
            global $woocommerce;
            if (isset($_GET['tab']) && isset($_GET['section'])) {
                if ($_GET['section'] == 'rewardsystem_redeeming_module' || $_GET['section'] == 'rewardsystem_rewardpoints_gateway_module') {
                    echo rs_common_ajax_function_to_select_products('rs_select_product_for_purchase_using_points');
                    echo rs_common_ajax_function_to_select_products('rs_select_product_for_hide_gateway');
                    if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                        echo rs_common_chosen_function('#rs_select_category_for_purchase_using_points');
                        echo rs_common_chosen_function('#rs_select_category_to_hide_gateway');
                    } else {
                        echo rs_common_select_function('#rs_select_category_for_purchase_using_points');
                        echo rs_common_select_function('#rs_select_category_to_hide_gateway');
                    }
                }
            }
        }

    }

    RSRedeemingModule::init();
}