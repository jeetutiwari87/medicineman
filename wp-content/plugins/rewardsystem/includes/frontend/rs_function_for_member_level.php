<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSMemberFunction')) {

    class RSMemberFunction {

        public static function init() {

            //For older version of Woocommerce (i.e) Version < 2.3.0;
            add_action('woocommerce_before_cart_item_quantity_zero', array(__CLASS__, 'fp_remove_cart_item_key'), 10, 1);

            //For newer version of Woocommerce (i.e) Version > 2.3.0;
            add_action('woocommerce_cart_item_removed', array(__CLASS__, 'fp_remove_cart_item_key'), 1, 1);

            add_shortcode('rs_my_current_earning_level_name', array(__CLASS__, 'add_shortcode_for_current_level_name'));

            add_shortcode('rs_next_earning_level_points', array(__CLASS__, 'add_shortcode_for_next_earning_level_points'));
            add_shortcode('rs_my_current_redeem_level_name', array(__CLASS__, 'add_shortcode_for_current_level_name_redeem'));

            add_shortcode('rs_next_redeem_level_points', array(__CLASS__, 'add_shortcode_for_next_earning_level_points_redeem'));
        }

        public static function user_role_based_redeem_points($getuserid) {
            //Set Bool Value for User ID
            $userrole = get_option('rs_enable_user_role_based_reward_points_for_redeem');
            $earnuserrole = get_option('rs_enable_redeem_level_based_reward_points');
            $purchasehistory = get_option('rs_enable_user_purchase_history_based_reward_points_redeem');
            $membershipplan = class_exists('SUMOMemberships') ? get_option('rs_enable_membership_plan_based_reward_points') : 'no';
            $userpoints = '';
            if (class_exists('SUMOMemberships')) {
                $valuewithmembership = self::rs_function_to_get_membership_level_redeem($getuserid, $userpoints, $userrole, $earnuserrole, $membershipplan, $purchasehistory);
                return $valuewithmembership;
            } else {
                $userpoints = '';
                $valuewithoutmembership = self::rs_function_to_get_userrole_and_redeem_level($getuserid, $userpoints, $userrole, $earnuserrole, $purchasehistory);                
                return $valuewithoutmembership;
            }
        }

        public static function rs_function_to_get_userrole_and_redeem_level($getuserid, $userpoints, $userrole, $earnuserrole) {
            $pointvalue = wc_format_decimal(get_option('rs_redeem_point_value'));
            $purchasehistory = get_option('rs_enable_user_purchase_history_based_reward_points_redeem');
            //UserRole Level Enabled
            if (($userrole == 'yes') && ($earnuserrole != 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) : '100';
                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
                    }
                    $update = $pointvalue * $getcurrentrolepercentage;
                    $percentvalue = $update / 100;
                    return $percentvalue;
                }
            }

            //Earning Level Enabled
            if (($earnuserrole == 'yes') && ($userrole != 'yes')) {
                if ($getuserid != '') {
                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule_for_redeem'), 'rewardpoints');
                    if (get_option('rs_select_redeem_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }
                    $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';
                    if ($purchasehistory == 'yes') {
                        $getpercentage = self::comparison_product_purchase_history_redeem($getuserid, $getpercentage);
                    }
                    $update = $pointvalue * $getpercentage;
                    $percentvalue = $update / 100;
                    return $percentvalue;
                }
            }

            //UserRole and Earning Level Enabled              
            if (($userrole == 'yes') && ($earnuserrole == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) : '100';

                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule_for_redeem'), 'rewardpoints');
                    if (get_option('rs_select_redeem_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }

                    if ($arrayvalue != NULL) {
                        $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    } else {
                        $getpercentage = '1';
                    }
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';
                    if (get_option('rs_choose_priority_level_selection_for_redeem') == '1') {
                        if ($getcurrentrolepercentage >= $getpercentage) {
                            $getcurrentrolepercentage = $getcurrentrolepercentage;
                        } else {
                            $getcurrentrolepercentage = $getpercentage;
                        }
                    } else {
                        if (get_option('rs_choose_priority_level_selection_for_redeem') == '2') {
                            if ($getcurrentrolepercentage <= $getpercentage) {
                                $getcurrentrolepercentage = $getcurrentrolepercentage;
                            } else {
                                $getcurrentrolepercentage = $getpercentage;
                            }
                        }
                    }
                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
                    }
                    $update = $pointvalue * $getcurrentrolepercentage;
                    $percentvalue = $update / 100;
                    return $percentvalue;
                }
            }

            //UserRole And Earning Level Disabled
            if (($userrole != 'yes') && ($earnuserrole != 'yes')) {
                if ($getuserid != '') {
                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid,0) == 0 ? 100 : self::comparison_product_purchase_history_redeem($getuserid,0);                        
                    }else{
                        $getcurrentrolepercentage = 100;
                    }
                    $update = $pointvalue * $getcurrentrolepercentage;
                    $percentvalue = $update / 100;
                    return $percentvalue;
                }
            }
        }

        public static function comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage) {
            $purcasehistory = self::product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
            if (get_option('rs_choose_priority_level_selection_for_redeem') == '1') {
                if ($getcurrentrolepercentage >= $purcasehistory) {
                    $getcurrentrolepercentage = $getcurrentrolepercentage;
                } else {
                    $getcurrentrolepercentage = $purcasehistory;
                }
            } else {
                if (get_option('rs_choose_priority_level_selection_for_redeem') == '2') {
                    if ($getcurrentrolepercentage <= $purcasehistory) {
                        $getcurrentrolepercentage = $getcurrentrolepercentage;
                    } else {
                        $getcurrentrolepercentage = $purcasehistory;
                    }
                }
            }
            return $getcurrentrolepercentage;
        }

        public static function product_purchase_history_redeem($userdid, $getcurrentrolepercentage) {
            global $wpdb;
            $total = array();
            $order_ids = $wpdb->get_results("SELECT posts.ID
			FROM $wpdb->posts as posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE   meta.meta_key       = '_customer_user'
			AND     posts.post_type     IN ('" . implode("','", wc_get_order_types('order-count')) . "')
			AND     posts.post_status   IN ('" . implode("','", array_keys(wc_get_order_statuses())) . "')
			AND     meta_value          = $userdid
		", ARRAY_A);
            $rewards_dynamic_rulerule = get_option('rewards_dynamic_rule_purchase_history_redeem');
            $order_count = count($order_ids);
            foreach ($order_ids as $values) {
                $total[] = get_post_meta($values['ID'], '_order_total', true);
            }
            if(is_array($total)) {
                $order_total = array_sum($total);
            }            
            if (!empty($rewards_dynamic_rulerule)) {
                if (is_array($rewards_dynamic_rulerule)) {
                    foreach ($rewards_dynamic_rulerule as $i => $rewards_dynamic_rule) {
                        $type = $rewards_dynamic_rule['type'];
                        if ($type == '1') {
                            $value = $rewards_dynamic_rule['value'];
                            if ($order_count <= $value) {
                                $percentage = $rewards_dynamic_rule['percentage'];
                                return $percentage;
                            }
                        }
                        if ($type == '2') {
                            $get_order_amount = $rewards_dynamic_rule['value'];
                            if ($order_total <= $get_order_amount) {
                                $percentage = $rewards_dynamic_rule['percentage'];
                                return $percentage;
                            }
                        }
                    }
                }
            }

            return $getcurrentrolepercentage;
        }

        public static function rs_function_to_get_membership_level_redeem($getuserid, $userpoints, $userrole, $earnuserrole, $membershipplan) {
            $purchasehistory = get_option('rs_enable_user_purchase_history_based_reward_points_redeem');
            //User Role Enabled
            if (($userrole == 'yes') && ($earnuserrole != 'yes') && ($membershipplan != 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) : '100';
                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
                    }
                    $update = $pointvalue / $getcurrentrolepercentage;
                    return $update;
                } else {
                    $update = $pointvalue / 100;

                    return $update;
                }
            }

            //Earning Level Enabled
            if (($earnuserrole == 'yes') && ($userrole != 'yes') && ($membershipplan != 'yes')) {
                if ($getuserid != '') {
                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule_for_redeem'), 'rewardpoints');
                    if (get_option('rs_select_redeem_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }
                    $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';
                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
                    }
                    $update = $pointvalue / $getcurrentrolepercentage;
                    return $update;
                } else {
                    $update = $pointvalue / 100;
                    return $update;
                }
            }


            //Membership Level Enabled
            if (($earnuserrole != 'yes') && ($userrole != 'yes') && ($membershipplan == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $post_id = self::function_to_get_post_id($getuserid);
                    $get_plan_id = get_post_meta($post_id, 'sumomemberships_saved_plans', true);
                    if (is_array($get_plan_id)) {
                        foreach ($get_plan_id as $key => $value) {
                            if (isset($value['choose_plan']) && $value['choose_plan'] != '') {
                                $plan_id = $value['choose_plan'];
                                $getcurrentplanvalue[] = get_option('rs_reward_membership_plan_for_redeem' . $plan_id) != '' ? get_option('rs_reward_membership_plan_for_redeem' . $plan_id) : '100';
                            }
                        }
                    } else {
                        $getcurrentplanvalue[] = 100;
                    }
                    if (get_option('rs_choose_priority_level_selection_for_redeem') == '1') {
                        $getcurrentrolepercentage = max($getcurrentplanvalue);
                    } else {
                        $getcurrentrolepercentage = min($getcurrentplanvalue);
                    }

                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
                    }
                    $update = $pointvalue / $getcurrentrolepercentage;
                    return $update;
                } else {
                    $update = $pointvalue / 100;
                    return $update;
                }
            }

            //All Level Enabled
            if (($userrole == 'yes') && ($earnuserrole == 'yes') && ($membershipplan == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) : '100';
                    $post_id = self::function_to_get_post_id($getuserid);
                    $get_plan_id = get_post_meta($post_id, 'sumomemberships_saved_plans', true);
                    if (is_array($get_plan_id)) {
                        foreach ($get_plan_id as $key => $value) {
                            if (isset($value['choose_plan']) && $value['choose_plan'] != '') {
                                $plan_id = $value['choose_plan'];
                                $getcurrentplanvalues[] = get_option('rs_reward_membership_plan_for_redeem' . $plan_id) != '' ? get_option('rs_reward_membership_plan_for_redeem' . $plan_id) : '100';
                            }
                        }
                    } else {
                        $getcurrentplanvalues[] = 100;
                    }
                    if (get_option('rs_choose_priority_level_selection_for_redeem') == '1') {
                        $getcurrentplanpercentage = max($getcurrentplanvalues);
                    } else {
                        $getcurrentplanpercentage = min($getcurrentplanvalues);
                    }
                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule_for_redeem'), 'rewardpoints');
                    $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);

                    if ($arrayvalue != NULL) {
                        $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    } else {
                        $getpercentage = '1';
                    }
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';

                    if (get_option('rs_choose_priority_level_selection_for_redeem') == '1') {
                        if ($getcurrentrolepercentage >= $getpercentage) {
                            if ($getcurrentrolepercentage >= $getcurrentplanpercentage) {
                                $getcurrentrolepercentage = $getcurrentrolepercentage;
                            } else {
                                $getcurrentrolepercentage = $getcurrentplanpercentage;
                            }
                        } else {
                            if ($getpercentage >= $getcurrentplanpercentage) {
                                $getcurrentrolepercentage = $getpercentage;
                            } else {
                                $getcurrentrolepercentage = $getcurrentplanpercentage;
                            }
                        }
                    } else {
                        if (get_option('rs_choose_priority_level_selection_for_redeem') == '2') {
                            if ($getcurrentrolepercentage <= $getpercentage) {
                                if ($getcurrentrolepercentage <= $getcurrentplanpercentage) {
                                    $getcurrentrolepercentage = $getcurrentrolepercentage;
                                } else {
                                    $getcurrentrolepercentage = $getcurrentplanpercentage;
                                }
                            } else {
                                if ($getpercentage <= $getcurrentplanpercentage) {
                                    $getcurrentrolepercentage = $getpercentage;
                                } else {
                                    $getcurrentrolepercentage = $getcurrentplanpercentage;
                                }
                            }
                        }
                    }

                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
                    }
                    $update = $pointvalue / $getcurrentrolepercentage;
                    return $update;
                } else {
                    $update = $pointvalue / 100;
                    return $update;
                }
            }

            //Membership Level Disabled
            if (($userrole == 'yes') && ($earnuserrole == 'yes') && ($membershipplan != 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) : '100';

                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule_for_redeem'), 'rewardpoints');
                    if (get_option('rs_select_redeem_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }

                    if ($arrayvalue != NULL) {
                        $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    } else {
                        $getpercentage = '1';
                    }
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';

                    if (get_option('rs_choose_priority_level_selection_for_redeem') == '1') {
                        if ($getcurrentrolepercentage >= $getpercentage) {
                            $getcurrentrolepercentage = $getcurrentrolepercentage;
                        } else {
                            $getcurrentrolepercentage = $getpercentage;
                        }
                    } else {
                        if (get_option('rs_choose_priority_level_selection_for_redeem') == '2') {
                            if ($getcurrentrolepercentage <= $getpercentage) {
                                $getcurrentrolepercentage = $getcurrentrolepercentage;
                            } else {
                                $getcurrentrolepercentage = $getpercentage;
                            }
                        }
                    }


                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
                    }
                    $update = $pointvalue / $getcurrentrolepercentage;
                    return $update;
                } else {
                    $update = $pointvalue / 100;
                    return $update;
                }
            }

            //Membership and User Role Level Enabled
            if (($userrole == 'yes') && ($earnuserrole != 'yes') && ($membershipplan == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_for_redeem_' . $currentuserrole) : '100';
                    $post_id = self::function_to_get_post_id($getuserid);
                    $get_plan_id = get_post_meta($post_id, 'sumomemberships_saved_plans', true);
                    if (is_array($get_plan_id)) {
                        foreach ($get_plan_id as $key => $value) {
                            if (isset($value['choose_plan']) && $value['choose_plan'] != '') {
                                $plan_id = $value['choose_plan'];
                                $getcurrentplanvalues[] = get_option('rs_reward_membership_plan_for_redeem' . $plan_id) != '' ? get_option('rs_reward_membership_plan_for_redeem' . $plan_id) : '100';
                            }
                        }
                    } else {
                        $getcurrentplanvalues[] = 100;
                    }
                    if (get_option('rs_choose_priority_level_selection_for_redeem') == '1') {
                        $getcurrentplanpercentage = max($getcurrentplanvalues);
                    } else {
                        $getcurrentplanpercentage = min($getcurrentplanvalues);
                    }

                    if (get_option('rs_choose_priority_level_selection_for_redeem') == '1') {
                        if ($getcurrentrolepercentage >= $getcurrentplanpercentage) {
                            $getcurrentrolepercentage = $getcurrentrolepercentage;
                        } else {
                            $getcurrentrolepercentage = $getcurrentplanpercentage;
                        }
                    } else {
                        if ($getcurrentrolepercentage <= $getcurrentplanpercentage) {
                            $getcurrentrolepercentage = $getcurrentrolepercentage;
                        } else {
                            $getcurrentrolepercentage = $getcurrentplanpercentage;
                        }
                    }

                    $update = $pointvalue / $getcurrentrolepercentage;
                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
                        $update = $pointvalue / $getcurrentrolepercentage;
                    }
                    return $update;
                } else {
                    $update = $pointvalue / 100;
                    return $update;
                }
            }

            //Membership and Earning Level Enabled
            if (($userrole != 'yes') && ($earnuserrole == 'yes') && ($membershipplan == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $post_id = self::function_to_get_post_id($getuserid);
                    $get_plan_id = get_post_meta($post_id, 'sumomemberships_saved_plans', true);
                    if (is_array($get_plan_id)) {
                        foreach ($get_plan_id as $key => $value) {
                            if (isset($value['choose_plan']) && $value['choose_plan'] != '') {
                                $plan_id = $value['choose_plan'];
                                $getcurrentplanvalues[] = get_option('rs_reward_membership_plan_for_redeem' . $plan_id) != '' ? get_option('rs_reward_membership_plan_for_redeem' . $plan_id) : '100';
                            }
                        }
                    } else {
                        $getcurrentplanvalues[] = 100;
                    }
                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        $getcurrentplanpercentage = max($getcurrentplanvalues);
                    } else {
                        $getcurrentplanpercentage = min($getcurrentplanvalues);
                    }

                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule_for_redeem'), 'rewardpoints');
                    if (get_option('rs_select_redeem_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }
                    if ($arrayvalue != NULL) {
                        $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    } else {
                        $getpercentage = '1';
                    }
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';

                    if (get_option('rs_choose_priority_level_selection_for_redeem') == '1') {
                        if ($getpercentage >= $getcurrentplanpercentage) {
                            $getcurrentrolepercentage = $getpercentage;
                        } else {
                            $getcurrentrolepercentage = $getcurrentplanpercentage;
                        }
                    } else {
                        if ($getpercentage <= $getcurrentplanpercentage) {
                            $getcurrentrolepercentage = $getpercentage;
                        } else {
                            $getcurrentrolepercentage = $getcurrentplanpercentage;
                        }
                    }

                    $update = $pointvalue / $getcurrentrolepercentage;
                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history_redeem($getuserid, $getcurrentrolepercentage);
                        $update = $pointvalue / $getcurrentrolepercentage;
                    }
                    return $update;
                } else {
                    $update = $pointvalue / 100;
                    return $update;
                }
            }


            //All Level Disabled
            if (($userrole != 'yes') && ($earnuserrole != 'yes') && ($membershipplan != 'yes')) {
                if ($getuserid != '') {
                    $update = $pointvalue / 100;
                    if ($purchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::product_purchase_history_redeem($getuserid,0) == 0 ? 100 : self::product_purchase_history_redeem($getuserid,0);
                        $update = $pointvalue / $getcurrentrolepercentage;
                    }
                    return $update;
                } else {
                    $update = $pointvalue / 100;
                    return $update;
                }
            }
        }

        public static function add_shortcode_for_current_level_name() {
            if (is_user_logged_in()) {
                $userid = get_current_user_id();
                $options = 'mail';
                $message = self::current_level_name($userid, $options);
                return $message;
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                $msg = $message . ' <a href=' . $myaccountlink . '> ' . $login . '</a>';
                return '<br>' . $msg;
            }
        }

        public static function add_shortcode_for_next_earning_level_points_redeem() {
            if (is_user_logged_in()) {
                $userid = get_current_user_id();
                $options = '';
                $value = self::next_redeem_level_points($userid, $options);
                return $value;
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                $msg = $message . ' <a href=' . $myaccountlink . '> ' . $login . '</a>';
                return '<br>' . $msg;
            }
        }

        public static function next_redeem_level_points($userid, $option) {
            if (is_user_logged_in()) {
                if (get_option('rs_enable_redeem_level_based_reward_points') == 'yes') {
                    $next_level_points = "";
                    global $woocommerce;
                    if (get_option('rs_select_redeem_points_based_on') == '1') {
                        $total_earned_points = RSPointExpiry::get_sum_of_earned_points($userid);
                    } else {
                        $total_earned_points = RSPointExpiry::get_sum_of_total_earned_points($userid);
                    }
                    $current_level_id = FPRewardSystem_Free_Product::fp_get_free_product_level_id($total_earned_points);
                    if ($option == 'email') {
                        $current_level_id = self::fp_get_free_product_level_id($total_earned_points);
                    }
                    $member_level_list = get_option('rewards_dynamic_rule_for_redeem');
                    $current_level_name = isset($member_level_list[$current_level_id]['name']) ? $member_level_list[$current_level_id]['name'] : "";
                    if (isset($member_level_list[$current_level_id]['rewardpoints'])) {
                        if ($member_level_list[$current_level_id]['rewardpoints'] > $total_earned_points) {
                            $next_level_points = $member_level_list[$current_level_id]['rewardpoints'] - round($total_earned_points);
                            $each_member_level = RSMemberFunction::multi_dimensional_sort(get_option('rewards_dynamic_rule_for_redeem'), 'rewardpoints');
                            if ($each_member_level != "") {
                                foreach ($each_member_level as $key => $value) {
                                    $current_user_total_earned_points = $total_earned_points;
                                    $current_level_earning_points_limit = $value["rewardpoints"];
                                    if ($current_level_earning_points_limit >= $current_user_total_earned_points) {
                                        $levelname[] = $value["name"];
                                        $points[] = $value["rewardpoints"];
                                    }
                                }

                                if (count($levelname) > 1) {
                                    $message = get_option('rs_point_to_reach_next_level');
                                    $message_replace = str_replace('[balancepoint]', $next_level_points, $message);
                                    $message_update = str_replace('[next_level_name]', $levelname[1], $message_replace);
                                    return $message_update;
                                }
                            }
                        }
                    }
                }
            }
        }

        public static function function_to_get_post_id($member_id) {
            $args = array(
                'post_type' => 'sumomembers',
                'meta_query' => array(
                    array(
                        'key' => 'sumomemberships_userid', 'value' => array($member_id),
                        'compare' => 'IN')
            ));
            $get_posts = get_posts($args);

            $id = isset($get_posts[0]->ID) ? $get_posts[0]->ID : 0;

            return $id;
        }

        public static function user_role_based_reward_points($getuserid, $userpoints) {
            //Set Bool Value for User ID
            $userrole = get_option('rs_enable_user_role_based_reward_points');
            $earnuserrole = get_option('rs_enable_earned_level_based_reward_points');
            $membershipplan = class_exists('SUMOMemberships') ? get_option('rs_enable_membership_plan_based_reward_points') : 'no';

            if (class_exists('SUMOMemberships')) {
                $valuewithmembership = self::rs_function_to_get_membership_level($getuserid, $userpoints, $userrole, $earnuserrole, $membershipplan);
                return $valuewithmembership;
            } else {
                $valuewithoutmembership = self::rs_function_to_get_userrole_and_earning_level($getuserid, $userpoints, $userrole, $earnuserrole);
                return $valuewithoutmembership;
            }
        }

        public static function rs_function_to_get_userrole_and_earning_level($getuserid, $userpoints, $userrole, $earnuserrole) {
            $userpurchasehistory = get_option('rs_enable_user_purchase_history_based_reward_points');
            //UserRole Level Enabled
            if (($userrole == 'yes') && ($earnuserrole != 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_' . $currentuserrole) : '100';
                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, $getcurrentrolepercentage);
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }

            //Earning Level Enabled
            if (($earnuserrole == 'yes') && ($userrole != 'yes')) {
                if ($getuserid != '') {
                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule'), 'rewardpoints');
                    if (get_option('rs_select_earn_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }
                    $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';
                    if ($userpurchasehistory == 'yes') {
                        $getpercentage = self::comparison_product_purchase_history($getuserid, $getpercentage);
                    }
                    $calculation = $userpoints * $getpercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }

            //UserRole and Earning Level Enabled              
            if (($userrole == 'yes') && ($earnuserrole == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_' . $currentuserrole) : '100';

                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule'), 'rewardpoints');
                    if (get_option('rs_select_earn_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }

                    if ($arrayvalue != NULL) {
                        $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    } else {
                        $getpercentage = '1';
                    }
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';
                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        if ($getcurrentrolepercentage >= $getpercentage) {
                            $getcurrentrolepercentage = $getcurrentrolepercentage;
                        } else {
                            $getcurrentrolepercentage = $getpercentage;
                        }
                    } else {
                        if (get_option('rs_choose_priority_level_selection') == '2') {
                            if ($getcurrentrolepercentage <= $getpercentage) {
                                $getcurrentrolepercentage = $getcurrentrolepercentage;
                            } else {
                                $getcurrentrolepercentage = $getpercentage;
                            }
                        }
                    }
                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, $getcurrentrolepercentage);
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }

            //UserRole And Earning Level Disabled
            if (($userrole != 'yes') && ($earnuserrole != 'yes')) {
                if ($getuserid != '') {                    
                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, 0) == 0 ? 100 : self::comparison_product_purchase_history($getuserid, 0);                        
                    }else{
                        $getcurrentrolepercentage = 100;
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }
        }

        public static function comparison_product_purchase_history($getuserid, $getcurrentrolepercentage) {
            $purcasehistory = self::product_purchase_history($getuserid, $getcurrentrolepercentage);            
            if (get_option('rs_choose_priority_level_selection') == '1') {
                if ($getcurrentrolepercentage >= $purcasehistory) {
                    $getcurrentrolepercentage = $getcurrentrolepercentage;
                } else {
                    $getcurrentrolepercentage = $purcasehistory;
                }
            } else {
                if (get_option('rs_choose_priority_level_selection') == '2') {
                    if ($getcurrentrolepercentage <= $purcasehistory) {
                        $getcurrentrolepercentage = $getcurrentrolepercentage;
                    } else {
                        $getcurrentrolepercentage = $purcasehistory;
                    }
                }
            }
            return $getcurrentrolepercentage;
        }

        public static function product_purchase_history($userdid, $getcurrentrolepercentage) {
            global $wpdb;
            $total = array();
            $order_ids = $wpdb->get_results("SELECT posts.ID
			FROM $wpdb->posts as posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE   meta.meta_key       = '_customer_user'
			AND     posts.post_type     IN ('" . implode("','", wc_get_order_types('order-count')) . "')
			AND     posts.post_status   IN ('" . implode("','", array_keys(wc_get_order_statuses())) . "')
			AND     meta_value          = $userdid
		", ARRAY_A);
            $rewards_dynamic_rulerule = get_option('rewards_dynamic_rule_purchase_history');
            $order_count = count($order_ids);
            foreach ($order_ids as $values) {
                $total[] = get_post_meta($values['ID'], '_order_total', true);
            }
            if(is_array($total)) {
                $order_total = array_sum($total);
            }            
            if (!empty($rewards_dynamic_rulerule)) {
                if (is_array($rewards_dynamic_rulerule)) {
                    foreach ($rewards_dynamic_rulerule as $i => $rewards_dynamic_rule) {
                        $type = $rewards_dynamic_rule['type'];                        
                        if ($type == '1') {
                            $value = $rewards_dynamic_rule['value'];
                            if ($order_count <= $value) {
                                $percentage = $rewards_dynamic_rule['percentage'];
                                return $percentage;
                            }else{
                                
                            }
                        }
                        if ($type == '2') {
                            $get_order_amount = $rewards_dynamic_rule['value'];                            
                            if ($order_total <= $get_order_amount) {
                                $percentage = $rewards_dynamic_rule['percentage'];                                
                                return $percentage;
                            }
                        }
                    }
                }
            }            
            return $getcurrentrolepercentage;
        }

        public static function rs_function_to_get_membership_level($getuserid, $userpoints, $userrole, $earnuserrole, $membershipplan) {
            $userpurchasehistory = get_option('rs_enable_user_purchase_history_based_reward_points');
            //User Role Enabled
            if (($userrole == 'yes') && ($earnuserrole != 'yes') && ($membershipplan != 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_' . $currentuserrole) : '100';
                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, $getcurrentrolepercentage);
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }

            //Earning Level Enabled
            if (($earnuserrole == 'yes') && ($userrole != 'yes') && ($membershipplan != 'yes')) {
                if ($getuserid != '') {
                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule'), 'rewardpoints');
                    if (get_option('rs_select_earn_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }
                    $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';
                    if ($userpurchasehistory == 'yes') {
                        $getpercentage = self::comparison_product_purchase_history($getuserid, $getpercentage);
                    }
                    $calculation = $userpoints * $getpercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }


            //Membership Level Enabled
            if (($earnuserrole != 'yes') && ($userrole != 'yes') && ($membershipplan == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $post_id = self::function_to_get_post_id($getuserid);
                    $get_plan_id = get_post_meta($post_id, 'sumomemberships_saved_plans', true);
                    if (is_array($get_plan_id)) {
                        foreach ($get_plan_id as $key => $value) {
                            if (isset($value['choose_plan']) && $value['choose_plan'] != '') {
                                $plan_id = $value['choose_plan'];
                                $getcurrentplanvalue[] = get_option('rs_reward_membership_plan_' . $plan_id) != '' ? get_option('rs_reward_membership_plan_' . $plan_id) : '100';
                            }
                        }
                    } else {
                        $getcurrentplanvalue[] = 100;
                    }
                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        $getcurrentrolepercentage = max($getcurrentplanvalue);
                    } else {
                        $getcurrentrolepercentage = min($getcurrentplanvalue);
                    }
                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, $getcurrentrolepercentage);
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }

            //All Level Enabled
            if (($userrole == 'yes') && ($earnuserrole == 'yes') && ($membershipplan == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_' . $currentuserrole) : '100';
                    $post_id = self::function_to_get_post_id($getuserid);
                    $get_plan_id = get_post_meta($post_id, 'sumomemberships_saved_plans', true);
                    if (is_array($get_plan_id)) {
                        foreach ($get_plan_id as $key => $value) {
                            if (isset($value['choose_plan']) && $value['choose_plan'] != '') {
                                $plan_id = $value['choose_plan'];
                                $getcurrentplanvalues[] = get_option('rs_reward_membership_plan_' . $plan_id) != '' ? get_option('rs_reward_membership_plan_' . $plan_id) : '100';
                            }
                        }
                    } else {
                        $getcurrentplanvalues[] = 100;
                    }
                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        $getcurrentplanpercentage = max($getcurrentplanvalues);
                    } else {
                        $getcurrentplanpercentage = min($getcurrentplanvalues);
                    }
                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule'), 'rewardpoints');
                    $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);

                    if ($arrayvalue != NULL) {
                        $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    } else {
                        $getpercentage = '1';
                    }
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';

                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        if ($getcurrentrolepercentage >= $getpercentage) {
                            if ($getcurrentrolepercentage >= $getcurrentplanpercentage) {
                                $getcurrentrolepercentage = $getcurrentrolepercentage;
                            } else {
                                $getcurrentrolepercentage = $getcurrentplanpercentage;
                            }
                        } else {
                            if ($getpercentage >= $getcurrentplanpercentage) {
                                $getcurrentrolepercentage = $getpercentage;
                            } else {
                                $getcurrentrolepercentage = $getcurrentplanpercentage;
                            }
                        }
                    } else {
                        if (get_option('rs_choose_priority_level_selection') == '2') {
                            if ($getcurrentrolepercentage <= $getpercentage) {
                                if ($getcurrentrolepercentage <= $getcurrentplanpercentage) {
                                    $getcurrentrolepercentage = $getcurrentrolepercentage;
                                } else {
                                    $getcurrentrolepercentage = $getcurrentplanpercentage;
                                }
                            } else {
                                if ($getpercentage <= $getcurrentplanpercentage) {
                                    $getcurrentrolepercentage = $getpercentage;
                                } else {
                                    $getcurrentrolepercentage = $getcurrentplanpercentage;
                                }
                            }
                        }
                    }

                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, $getcurrentrolepercentage);
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }

            //Membership Level Disabled
            if (($userrole == 'yes') && ($earnuserrole == 'yes') && ($membershipplan != 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_' . $currentuserrole) : '100';

                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule'), 'rewardpoints');
                    if (get_option('rs_select_earn_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }

                    if ($arrayvalue != NULL) {
                        $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    } else {
                        $getpercentage = '1';
                    }
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';

                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        if ($getcurrentrolepercentage >= $getpercentage) {
                            $getcurrentrolepercentage = $getcurrentrolepercentage;
                        } else {
                            $getcurrentrolepercentage = $getpercentage;
                        }
                    } else {
                        if (get_option('rs_choose_priority_level_selection') == '2') {
                            if ($getcurrentrolepercentage <= $getpercentage) {
                                $getcurrentrolepercentage = $getcurrentrolepercentage;
                            } else {
                                $getcurrentrolepercentage = $getpercentage;
                            }
                        }
                    }

                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, $getcurrentrolepercentage);
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }

            //Membership and User Role Level Enabled
            if (($userrole == 'yes') && ($earnuserrole != 'yes') && ($membershipplan == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $getcurrentrolepercentage = get_option('rs_reward_user_role_' . $currentuserrole) != '' ? get_option('rs_reward_user_role_' . $currentuserrole) : '100';
                    $post_id = self::function_to_get_post_id($getuserid);
                    $get_plan_id = get_post_meta($post_id, 'sumomemberships_saved_plans', true);
                    if (is_array($get_plan_id)) {
                        foreach ($get_plan_id as $key => $value) {
                            if (isset($value['choose_plan']) && $value['choose_plan'] != '') {
                                $plan_id = $value['choose_plan'];
                                $getcurrentplanvalues[] = get_option('rs_reward_membership_plan_' . $plan_id) != '' ? get_option('rs_reward_membership_plan_' . $plan_id) : '100';
                            }
                        }
                    } else {
                        $getcurrentplanvalues[] = 100;
                    }
                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        $getcurrentplanpercentage = max($getcurrentplanvalues);
                    } else {
                        $getcurrentplanpercentage = min($getcurrentplanvalues);
                    }

                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        if ($getcurrentrolepercentage >= $getcurrentplanpercentage) {
                            $getcurrentrolepercentage = $getcurrentrolepercentage;
                        } else {
                            $getcurrentrolepercentage = $getcurrentplanpercentage;
                        }
                    } else {
                        if ($getcurrentrolepercentage <= $getcurrentplanpercentage) {
                            $getcurrentrolepercentage = $getcurrentrolepercentage;
                        } else {
                            $getcurrentrolepercentage = $getcurrentplanpercentage;
                        }
                    }

                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, $getcurrentrolepercentage);
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }

            //Membership and Earning Level Enabled
            if (($userrole != 'yes') && ($earnuserrole == 'yes') && ($membershipplan == 'yes')) {
                if ($getuserid != '') {
                    $user = new WP_User($getuserid);
                    $user_roles = $user->roles;
                    $currentuserrole = $user_roles[0];
                    $post_id = self::function_to_get_post_id($getuserid);
                    $get_plan_id = get_post_meta($post_id, 'sumomemberships_saved_plans', true);
                    if (is_array($get_plan_id)) {
                        foreach ($get_plan_id as $key => $value) {
                            if (isset($value['choose_plan']) && $value['choose_plan'] != '') {
                                $plan_id = $value['choose_plan'];
                                $getcurrentplanvalues[] = get_option('rs_reward_membership_plan_' . $plan_id) != '' ? get_option('rs_reward_membership_plan_' . $plan_id) : '100';
                            }
                        }
                    } else {
                        $getcurrentplanvalues[] = 100;
                    }
                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        $getcurrentplanpercentage = max($getcurrentplanvalues);
                    } else {
                        $getcurrentplanpercentage = min($getcurrentplanvalues);
                    }

                    $arrayvalue = self::multi_dimensional_sort(get_option('rewards_dynamic_rule'), 'rewardpoints');
                    if (get_option('rs_select_earn_points_based_on') == '1') {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_earned_points($getuserid);
                    } else {
                        $rs_total_earned_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                    }
                    if ($arrayvalue != NULL) {
                        $getpercentage = self::rs_get_percentage_in_dynamic_rule($arrayvalue, 'rewardpoints', $rs_total_earned_points_user);
                    } else {
                        $getpercentage = '1';
                    }
                    $getpercentage = $getpercentage != false ? $getpercentage : '100';

                    if (get_option('rs_choose_priority_level_selection') == '1') {
                        if ($getpercentage >= $getcurrentplanpercentage) {
                            $getcurrentrolepercentage = $getpercentage;
                        } else {
                            $getcurrentrolepercentage = $getcurrentplanpercentage;
                        }
                    } else {
                        if ($getpercentage <= $getcurrentplanpercentage) {
                            $getcurrentrolepercentage = $getpercentage;
                        } else {
                            $getcurrentrolepercentage = $getcurrentplanpercentage;
                        }
                    }

                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, $getcurrentrolepercentage);
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }


            //All Level Disabled
            if (($userrole != 'yes') && ($earnuserrole != 'yes') && ($membershipplan != 'yes')) {
                if ($getuserid != '') {                    
                    $currentpoints = $userpoints;
                    if ($userpurchasehistory == 'yes') {
                        $getcurrentrolepercentage = self::comparison_product_purchase_history($getuserid, 0) == 0 ? 100 : self::comparison_product_purchase_history($getuserid, 0);
                    }else{
                        $getcurrentrolepercentage = 100;
                    }
                    $calculation = $currentpoints * $getcurrentrolepercentage;
                    $calculation = $calculation / 100;
                    return $calculation;
                } else {
                    $currentpoints = $userpoints;
                    $calculation = $currentpoints * 100;
                    $calculation = $calculation / 100;
                    return $calculation;
                }
            }
        }

        public static function multi_dimensional_sort($arr, $index) {
            $b = array();
            $c = array();
            if (is_array($arr)) {
                foreach ($arr as $key => $value) {
                    $b[$key] = $value[$index];
                }
                asort($b);
                foreach ($b as $key => $value) {
                    $c[$key] = $arr[$key];
                }
                return $c;
            }
        }

        public static function rs_get_percentage_in_dynamic_rule($products, $field, $value) {
            if (is_array($products)) {
                foreach ($products as $key => $product) {
                    if ($product[$field] >= $value)
                        return $product['percentage'];
                }
            }else {
                return '100';
            }
            return false;
        }

        public static function delete_saved_product_key_callback() {
            global $wpdb;
            if (isset($_POST['key_to_remove']) && $_POST['current_user_id']) {
                $selected_key_to_delete = $_POST['key_to_remove'];
                $user_id_to_remove = $_POST['current_user_id'];
                $after_unset = self::unset_saved_keys($selected_key_to_delete, array_filter(array_unique(get_user_meta($user_id_to_remove, 'listsetofids', true))));
                update_user_meta($user_id_to_remove, 'listsetofids', array_unique($after_unset));


                echo "1";
            }
            exit();
        }

        public static function unset_saved_keys($del_val, $messages) {
            if (($key = array_search($del_val, $messages)) !== false) {
                unset($messages[$key]);
            }
            return $messages;
        }

        public static function fp_remove_cart_item_key($cart_item_key) {
            $olddataifany = (array) get_user_meta(get_current_user_id(), 'listsetofids', true);
            $arraymergedata = array_unique(array_filter(array_merge($olddataifany, (array) $cart_item_key)));
            update_user_meta(get_current_user_id(), 'listsetofids', $arraymergedata);
        }

        public static function current_level_name_redeem($userid, $options) {
            if (is_user_logged_in()) {
                if (get_option('rs_enable_redeem_level_based_reward_points') == 'yes') {
                    global $woocommerce;
                    if (get_option('rs_select_earn_points_based_on') == '1') {
                        $total_earned_points = RSPointExpiry::get_sum_of_earned_points($userid);
                    } else {
                        $total_earned_points = RSPointExpiry::get_sum_of_total_earned_points($userid);
                    }
                    $current_level_id = FPRewardSystem_Free_Product::fp_get_free_product_level_id($total_earned_points);
                    if ($options == 'email') {
                        $current_level_id = self::fp_get_free_product_level_id($total_earned_points);
                    }
                    $member_level_list = get_option('rewards_dynamic_rule_for_redeem');
                    $current_level_name = isset($member_level_list[$current_level_id]['name']) ? $member_level_list[$current_level_id]['name'] : "";
                    return $current_level_name;
                }
            }
        }

        public static function add_shortcode_for_current_level_name_redeem() {
            if (is_user_logged_in()) {
                $userid = get_current_user_id();
                $options = 'mail';
                $message = self::current_level_name_redeem($userid, $options);
                return $message;
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                $msg = $message . ' <a href=' . $myaccountlink . '> ' . $login . '</a>';
                return '<br>' . $msg;
            }
        }

        public static function current_level_name($userid, $options) {
            if (is_user_logged_in()) {
                if (get_option('rs_enable_earned_level_based_reward_points') == 'yes') {
                    global $woocommerce;
                    if (get_option('rs_select_earn_points_based_on') == '1') {
                        $total_earned_points = RSPointExpiry::get_sum_of_earned_points($userid);
                    } else {
                        $total_earned_points = RSPointExpiry::get_sum_of_total_earned_points($userid);
                    }
                    $current_level_id = FPRewardSystem_Free_Product::fp_get_free_product_level_id($total_earned_points);
                    if ($options == 'email') {
                        $current_level_id = self::fp_get_free_product_level_id($total_earned_points);
                    }
                    $member_level_list = get_option('rewards_dynamic_rule');
                    $current_level_name = isset($member_level_list[$current_level_id]['name']) ? $member_level_list[$current_level_id]['name'] : "";
                    return $current_level_name;
                }
            }
        }

        public static function add_shortcode_for_next_earning_level_points() {
            if (is_user_logged_in()) {
                $userid = get_current_user_id();
                $options = '';
                $value = self::next_earning_level_points($userid, $options);
                return $value;
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                $msg = $message . ' <a href=' . $myaccountlink . '> ' . $login . '</a>';
                return '<br>' . $msg;
            }
        }

        public static function next_earning_level_points($userid, $option) {
            if (is_user_logged_in()) {
                if (get_option('rs_enable_earned_level_based_reward_points') == 'yes') {
                    $next_level_points = "";
                    global $woocommerce;
                    if (get_option('rs_select_earn_points_based_on') == '1') {
                        $total_earned_points = RSPointExpiry::get_sum_of_earned_points($userid);
                    } else {
                        $total_earned_points = RSPointExpiry::get_sum_of_total_earned_points($userid);
                    }
                    $current_level_id = FPRewardSystem_Free_Product::fp_get_free_product_level_id($total_earned_points);
                    if ($option == 'email') {
                        $current_level_id = self::fp_get_free_product_level_id($total_earned_points);
                    }
                    $member_level_list = get_option('rewards_dynamic_rule');
                    $current_level_name = isset($member_level_list[$current_level_id]['name']) ? $member_level_list[$current_level_id]['name'] : "";
                    if (isset($member_level_list[$current_level_id]['rewardpoints'])) {
                        if ($member_level_list[$current_level_id]['rewardpoints'] > $total_earned_points) {
                            $next_level_points = $member_level_list[$current_level_id]['rewardpoints'] - round($total_earned_points);
                            $each_member_level = RSMemberFunction::multi_dimensional_sort(get_option('rewards_dynamic_rule'), 'rewardpoints');
                            if ($each_member_level != "") {
                                foreach ($each_member_level as $key => $value) {
                                    $current_user_total_earned_points = $total_earned_points;
                                    $current_level_earning_points_limit = $value["rewardpoints"];
                                    if ($current_level_earning_points_limit >= $current_user_total_earned_points) {
                                        $levelname[] = $value["name"];
                                        $points[] = $value["rewardpoints"];
                                    }
                                }

                                if (count($levelname) > 1) {
                                    $message = get_option('rs_point_to_reach_next_level');
                                    $message_replace = str_replace('[balancepoint]', $next_level_points, $message);
                                    $message_update = str_replace('[next_level_name]', $levelname[1], $message_replace);
                                    return $message_update;
                                }
                            }
                        }
                    }
                }
            }
        }

        public static function fp_get_free_product_level_id($total_earned_points) {

            if (is_user_logged_in()) {
                global $woocommerce;
                $each_member_level = RSMemberFunction::multi_dimensional_sort(get_option('rewards_dynamic_rule'), 'rewardpoints');
                if ($each_member_level != "") {
                    foreach ($each_member_level as $key => $value) {
                        $current_user_total_earned_points = $total_earned_points;
                        $current_level_earning_points_limit = $value["rewardpoints"];
                        if ($current_level_earning_points_limit >= $current_user_total_earned_points) {
                            return $key;
                        }
                    }
                }
            }
        }

    }

    RSMemberFunction::init();
}