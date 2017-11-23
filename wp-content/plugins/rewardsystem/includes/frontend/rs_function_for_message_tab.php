<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('RSFunctionForMessage')) {

    class RSFunctionForMessage {

        public static function init() {
            if (get_option('rs_my_reward_table') == '1') {
                if (get_option('rs_reward_table_position') == '1') {
                    add_action('woocommerce_after_my_account', array(__CLASS__, 'view_list_table_in_myaccount'));
                } else {
                    add_action('woocommerce_before_my_account', array(__CLASS__, 'view_list_table_in_myaccount'));
                }
            }
             if (get_option('rs_my_reward_table_shortcode') == '1') {
                 add_shortcode('rs_my_rewards_log', array(__CLASS__, 'viewchangelog_shortcode'));
             }
        }
        
         public static function view_list_table_in_myaccount() {
             echo self::view_list_table_in_myaccount_buffer();
         }
         
         /* Handled RS view list table in my account table in buffer For site slowness issue */
        public static function view_list_table_in_myaccount_buffer() {
            ob_start () ;
            global $woocommerce;
            $userid = get_current_user_id();
            $banning_type = FPRewardSystem::check_banning_type($userid);
            if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                global $wpdb;
                $table_name = $wpdb->prefix . 'rspointexpiry';
                $table_name2 = $wpdb->prefix . 'rsrecordpoints';
                ?>
                <style type="text/css">
                <?php echo get_option('rs_myaccount_custom_css'); ?>
                </style>
                <?php
                echo "<h2  class=my_rewards_title>" . get_option('rs_my_rewards_title') . "</h2>";
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                $userid = get_current_user_id();
                $getusermeta = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$userid", ARRAY_A);
                $totaloldpoints = $getusermeta[0]['availablepoints'];
                $display_currency = get_option('rs_reward_currency_value');
                if ($display_currency == '1') {
                    $point_control = wc_format_decimal(get_option('rs_redeem_point'));
                    $point_control_price = RSMemberFunction::user_role_based_redeem_points(get_current_user_id());
                    $revised_amount = $totaloldpoints * $point_control_price;
                    $coupon_value_in_points = $revised_amount / $point_control;
                    $msg = '(' . get_woocommerce_formatted_price(round_off_type($coupon_value_in_points, $roundofftype)) . ')';
                } else {
                    $msg = '';
                }
                if ($totaloldpoints != '' && $totaloldpoints > 0) {
                    if (get_option('rs_reward_point_label_position') == '1') {
                        echo "<h4 class=my_reward_total> " . get_option('rs_my_rewards_total') . " " . round_off_type(number_format((float) $totaloldpoints, 2, '.', ''), $roundofftype) . $msg . "</h4><br>";
                    } else {
                        echo "<h4 class=my_reward_total> " . round_off_type(number_format((float) $totaloldpoints, 2, '.', ''), $roundofftype) . " " . $msg . get_option('rs_my_rewards_total') . "</h4><br>";
                    }
                } else {
                    if (get_option('rs_reward_point_label_position') == '1') {
                        echo "<h4 class=my_reward_total> " . get_option('rs_my_rewards_total') . " 0</h4><br>";
                    } else {
                        echo "<h4 class=my_reward_total> " . "0 " . get_option('rs_my_rewards_total') . " </h4><br>";
                    }
                }

                $outputtablefields = '<p> ';
                if (get_option('rs_show_hide_search_box_in_my_rewards_table') == '1') {
                    $outputtablefields .= __('Search:', 'rewardsystem') . '<input id="filters" type="text"/> ';
                }
                if (get_option('rs_show_hide_page_size_my_rewards') == '1') {
                    $outputtablefields .= __('Page Size:', 'rewardsystem') . '<select id="change-page-sizes"><option value="5">5</option><option value="10">10</option><option value="50">50</option>
                    <option value="100">100</option>
                </select>';
                }
                $outputtablefields .= '</p>';
                echo $outputtablefields;
                ?>

                <table class = "examples demo shop_table my_account_orders table-bordered" data-filter = "#filters" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next">
                    <thead>
                        <tr>
                            <?php if(get_option('rs_my_reward_points_s_no') == '1') { ?>
                                <th data-toggle="true" data-sort-initial = "true"><?php echo get_option('rs_my_rewards_sno_label'); ?></th>
                           <?php } ?>
                            <?php if (get_option('rs_my_reward_points_user_name_hide') == '1') { ?>
                                <th><?php echo get_option('rs_my_rewards_userid_label'); ?></th>
                            <?php } ?>
                            <th><?php echo get_option('rs_my_rewards_rewarder_label'); ?></th>

                            <th data-hide='phone' ><?php echo get_option('rs_my_rewards_points_earned_label'); ?></th>
                            <?php if (get_option('rs_my_reward_points_expire') == '1') { ?>
                                <th data-hide='phone'><?php echo get_option('rs_my_rewards_points_expired_label'); ?></th>
                            <?php } ?>
                            <th data-hide='phone,tablet'><?php echo get_option('rs_my_rewards_redeem_points_label'); ?></th>
                            <th data-hide="phone,tablet"><?php echo get_option('rs_my_rewards_total_points_label'); ?></th>
                            <th data-hide="phone,tablet"><?php echo get_option('rs_my_rewards_date_label'); ?></th></tr></thead>
                    <tbody>
                        <?php
                        $user_ID = get_current_user_id();
                        $fetcharray = $wpdb->get_results("SELECT * FROM $table_name2 WHERE userid = $user_ID AND showuserlog = false", ARRAY_A);
                        $fetcharray = $fetcharray + (array) get_user_meta($user_ID, '_my_points_log', true);
                        if (is_array($fetcharray)) {
                            if (get_option('rs_points_log_sorting') == '1') {
                                krsort($fetcharray, SORT_NUMERIC);
                            }
                        }
                        $i = 1;
                        if (is_array($fetcharray)) {
                            foreach ($fetcharray as $newarray) {
                                if (is_array($newarray)) {
                                    $orderid = $newarray['orderid'];
                                    if (isset($newarray['earnedpoints'])) {
                                        if (!empty($newarray['earnedpoints'])) {
                                            $pointsearned = round_off_type($newarray['earnedpoints']);
                                        } else {
                                            $pointsearned = 0;
                                        }

                                        if (!empty($newarray['redeempoints'])) {
                                            $redeemedpoints = round_off_type($newarray['redeempoints']);
                                        } else {
                                            $redeemedpoints = 0;
                                        }

                                        if (!empty($newarray['totalpoints'])) {
                                            $totalpoints = round_off_type($newarray['totalpoints']);
                                        } else {
                                            $totalpoints = 0;
                                        }
                                        $usernickname = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($newarray['userid'], 'nickname');
                                        if (!empty($newarray['checkpoints'])) {                                            
                                            $checkpoints = $newarray['checkpoints'];
                                            $productid = $newarray['productid'];
                                            $variationid = $newarray['variationid'];
                                            $userid = $newarray['userid'];
                                            $reasonindetail = $newarray['reasonindetail'];
                                            $redeempoints = $newarray['redeempoints'];
                                            $refuserid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($newarray['refuserid'], 'nickname');
                                            $masterlog = false;
                                            $earnpoints = $newarray['earnedpoints'];
                                            $nomineeid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($newarray['nomineeid'], 'nickname');
                                            $user_deleted = true;
                                            $order_status_changed = true;
                                            $csvmasterlog = false;
                                            $usernickname = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($newarray['userid'], 'nickname');
                                            $nominatedpoints = $newarray['nomineepoints'];
                                            $reason = RSPointExpiry::rs_function_to_display_log($csvmasterlog, $user_deleted, $order_status_changed, $earnpoints,  $checkpoints, $productid, $orderid, $variationid, $userid, $refuserid, $reasonindetail, $redeempoints, $masterlog, $nomineeid, $usernickname, $nominatedpoints);
                                            $rewarderforfrontend = $reason;
                                            $timeformat = get_option('time_format');
                                            $dateformat = get_option('date_format') . ' ' . $timeformat;
                                            $gmtdate = $newarray['expirydate'] + get_option('gmt_offset') * HOUR_IN_SECONDS;
                                            $pointsexpireddates = $newarray['expirydate'] != 999999999999 ? date_i18n($dateformat, $gmtdate) : '-';
                                        } else {
                                            $rewarderforfrontend = '';
                                        }
                                    } else {
                                        if (!empty($newarray['points_earned_order'])) {
                                            $pointsearned = round_off_type($newarray['points_earned_order']);
                                        } else {
                                            $pointsearned = 0;
                                        }

                                        if (!empty($newarray['before_order_points'])) {
                                            if (is_float($newarray['before_order_points'])) {
                                                $beforepoints = round_off_type($newarray['before_order_points']);
                                            } else {
                                                $beforepoints = number_format($newarray['before_order_points']);
                                            }
                                        } else {
                                            $beforepoints = 0;
                                        }

                                        if (!empty($newarray['points_redeemed'])) {
                                            $redeemedpoints = round_off_type($newarray['points_redeemed']);
                                        } else {
                                            $redeemedpoints = 0;
                                        }

                                        if (!empty($newarray['totalpoints'])) {
                                            $totalpoints = round_off_type($newarray['totalpoints']);
                                        } else {
                                            $totalpoints = 0;
                                        }
                                        $usernickname = get_user_meta($newarray['userid'], 'nickname', true);

                                        if (!empty($newarray['rewarder_for_frontend'])) {
                                            $rewarderforfrontend = $newarray['rewarder_for_frontend'];
                                        } else {
                                            $rewarderforfrontend = '';
                                        }
                                        if (get_option('rs_my_reward_points_expire') == '1') {
                                            $newarray['earneddate'] = $newarray['earneddate'];
                                        }
                                        if (get_option('rs_my_reward_points_expire') == '1') {
                                            $newarray['expirydate'] = '999999999999';
                                            $timeformat = get_option('time_format');
                                            $dateformat = get_option('date_format') . ' ' . $timeformat;

                                            $pointsexpireddates = $newarray['expirydate'] != 999999999999 ? date_i18n($dateformat, $newarray['expirydate']) : '-';
                                        }
                                    }

                                    if ($pointsexpireddates != '-') {
                                        if (get_option('rs_dispaly_time_format') == '1') {
                                            $pointsexpireddates = $newarray['expirydate'] != 999999999999 ? date("d-m-Y h:i:s A", (float) $newarray['expirydate']) : '-';
                                        } else {
                                            $stringto_time = strftime($pointsexpireddates);
                                            $pointsexpireddates = $stringto_time;
                                        }
                                    }
                                    if ((($pointsearned != 0) && ($redeemedpoints != 0)) || ((($pointsearned != 0) && ($redeemedpoints == 0)) || ($pointsearned == 0) && ($redeemedpoints != 0)) || ($rewarderforfrontend != '')) {
                                        if (get_option('rs_dispaly_time_format') == '1') {
                                            $dateformat = "d-m-Y h:i:s A";
                                            $gmtdate = $newarray['earneddate'] + get_option('gmt_offset') * HOUR_IN_SECONDS;
                                            $update_start_date = is_numeric($newarray['earneddate']) ? date_i18n($dateformat, $gmtdate) : $newarray['earneddate'];
                                            $update_start_date = strftime($update_start_date);
                                        } else {
                                            $timeformat = get_option('time_format');
                                            $dateformat = get_option('date_format') . ' ' . $timeformat;
                                            $gmtdate = $newarray['earneddate'] + get_option('gmt_offset') * HOUR_IN_SECONDS;
                                            $update_start_date = is_numeric($newarray['earneddate']) ? date_i18n($dateformat, $gmtdate) : $newarray['earneddate'];
                                            $update_start_date = strftime($update_start_date);
                                        }
                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $pointsearned = round_off_type($pointsearned);
                                        $redeemedpoints = round_off_type($redeemedpoints);
                                        if ($pointsearned != '0' || $redeemedpoints != '0' || $checkpoints == 'PPRPFNP' || $checkpoints == 'SPA') {
                                            ?>
                                            <tr>
                                                <?php if ( get_option ( 'rs_my_reward_points_s_no' ) == '1' ) { ?>
                                                    <td data-value="<?php echo $i ; ?>"><?php echo $i ; ?></td>
                                                <?php } ?>
                                                <?php if (get_option('rs_my_reward_points_user_name_hide') == '1') { ?>
                                                    <td><?php echo $usernickname; ?> </td>
                                                <?php } ?>
                                                <td><?php echo $rewarderforfrontend; ?></td>
                                                <td><?php echo $pointsearned; ?> </td>
                                                <?php if (get_option('rs_my_reward_points_expire') == '1') { ?>
                                                    <td><?php echo $pointsexpireddates; ?></td>
                                                <?php } ?>
                                                <td><?php echo $redeemedpoints; ?></td>
                                                <td><?php echo $totalpoints; ?> </td>
                                                <td><?php echo $update_start_date; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    $i++;
                                }
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="clear:both;">
                            <td colspan="7">
                                <div class="pagination pagination-centered"></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <?php
            }
            $contents     = ob_get_contents () ;
            ob_end_clean () ;
            return $contents;
        }

        public static function viewchangelog_shortcode($content) {
            if (is_user_logged_in()) {
                ob_start();
                echo self::view_list_table_in_myaccount();
                $content = ob_get_clean();
                return $content;
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                $msg = $message . ' <a href=' . $myaccountlink . '> ' . $login . '</a>';
                return '<br>' . $msg;
            }
        }

    }

    RSFunctionForMessage::init();
}