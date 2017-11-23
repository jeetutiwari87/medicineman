<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('RSFunctionForReferralSystem')) {

    class RSFunctionForReferralSystem {

        public static function init() {
            add_action('woocommerce_before_my_account', array(__CLASS__, 'view_list_referal_table'));

            add_shortcode('rs_view_referral_table', array(__CLASS__, 'viewreferraltable_shortcode'));
        }

        public static function view_list_referal_table() {
            echo self::view_list_referal_table_buffer () ;
        }

        /* Handled view list referal table in buffer For site slowness issue */

        public static function view_list_referal_table_buffer() {
            ob_start () ;
            $get_user_type          = get_option ( 'rs_select_type_of_user_for_referral' ) ;
            $check_user_restriction = rs_function_to_check_the_restriction_for_referral ( $get_user_type ) ;
            if ( get_option ( 'rs_referral_activated' ) == 'yes' && get_option ( 'rs_show_hide_referal_table' ) != '2' && $check_user_restriction ) {
                echo "<h2  class=my_rewards_title>" . get_option ( 'rs_referal_table_title' ) . "</h2>" ;
                $roundofftype = get_option ( 'rs_round_off_type' ) == '1' ? '2' : '0' ;
                $userid       = get_current_user_id () ;
                ?>
                <table class = "referrallog demo shop_table my_account_referal table-bordered"  data-page-size="5" data-page-previous-text = "prev" >
                    <thead><th ><?php echo get_option ( 'rs_my_referal_sno_label' ) ; ?> </th >                                     
                    <th ><?php echo get_option ( 'rs_my_referal_userid_label' ) ; ?></th>             
                    <th ><?php echo get_option ( 'rs_my_total_referal_points_label' ) ; ?></th>
                    <tbody>
                        <?php
                        $user_ID      = get_current_user_id () ;
                        $fetcharray   = RS_Referral_Log::get_corresponding_users_log ( $userid ) ;
                        if ( is_array ( $fetcharray ) ) {
                            if ( get_option ( 'rs_points_log_sorting' ) == '1' ) {
                                krsort ( $fetcharray , SORT_NUMERIC ) ;
                            }
                        }
                        $i = 1 ;
                        if ( is_array ( $fetcharray ) ) {
                            foreach ( $fetcharray as $newarray => $values ) {
                                $getuserbyid = get_user_by ( 'id' , $newarray ) ;
                                if ( is_object ( $getuserbyid ) ) {
                                    ?>
                                    <tr>
                                        <td data-value="<?php echo $i ; ?>"><?php echo $i ; ?></td>
                                        <td><?php echo is_object ( $getuserbyid ) ? $getuserbyid->user_login : 'Guest' ; ?></td>
                                        <td><?php echo $values ; ?></td>

                                    </tr>
                                    <?php
                                }
                                $i ++ ;
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
            $contents = ob_get_contents () ;
            ob_end_clean () ;
            return $contents ;
        }

        public static function viewreferraltable_shortcode($content) {
            if (is_user_logged_in()) {
                $get_user_type = get_option('rs_select_type_of_user_for_referral');
                $check_user_restriction = rs_function_to_check_the_restriction_for_referral($get_user_type);
                if ($check_user_restriction) {
                    ob_start();
                    echo self::view_list_referal_table();
                    $content = ob_get_clean();
                    return $content;
                } else {
                    $message = get_option('rs_msg_for_restricted_user');
                    if (get_option('rs_display_msg_when_access_is_prevented') === '1') {
                        echo '<br>' . $message;
                    }
                }
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $linkforlogin = add_query_arg('redirect_to', get_permalink(), $myaccountlink);
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                echo '<br>' . $message . ' <a href=' . $linkforlogin . '> ' . $login . '</a>';
            }
        }

    }

    RSFunctionForReferralSystem::init();
}