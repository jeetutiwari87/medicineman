<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('RSFunctionForMyAccount')) {

    class RSFunctionForMyAccount {

        public static function init() {

            add_shortcode('rs_user_total_redeemed_points', array(__CLASS__, 'add_shortcode_to_display_total_redeem_points'));

            add_shortcode('rs_user_total_earned_points', array(__CLASS__, 'add_shortcode_to_display_total_earned_points'));

            add_shortcode('rs_user_total_expired_points', array(__CLASS__, 'add_shortcode_to_display_total_expired_points'));

            add_shortcode('rs_referred_user_name', array(__CLASS__, 'addshort_code_username'));

            add_shortcode('rs_user_total_points_in_value', array(__CLASS__, 'add_shortcode_to_display_user_total_points_in_value'));

            add_shortcode('rs_rank_based_total_earned_points', array(__CLASS__, 'view_total_user_points'));

            add_shortcode('rs_rank_based_current_reward_points', array(__CLASS__, 'view_total_current_user_points'));
        }

        public static function view_total_current_user_points() {
            if (is_user_logged_in()) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'rspointexpiry';
                $table_name2 = $wpdb->prefix . 'rsrecordpoints';
                $outputtablefields = '<p> ';
                $outputtablefields .= __('Page Size:', 'rewardsystem') . '<select id="change-page-sizesss"><option value="5">5</option><option value="10">10</option><option value="50">50</option>
                    <option value="100">100</option>
                </select>';
                $outputtablefields .= '</p>';
                echo $outputtablefields;
                ?>
                <table class = "totaluser_current demo shop_table my_account_orders table-bordered" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next">
                    <thead><tr><th ><?php echo get_option('rs_my_rewards_sno_label'); ?></th>                   
                            <th data-sortable="false" ><?php echo get_option('rs_my_rewards_userid_label'); ?></th>                  
                            <th data-type="numeric" data-sort-initial="true"><?php echo get_option('rs_my_rewards_points_earned_label'); ?></th>

                    <tbody>
                        <?php
                        $getusermeta = $wpdb->get_results("SELECT userid ,(earnedpoints-usedpoints) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) ORDER BY availablepoints DESC", ARRAY_A);
                        $i = 1;
                        foreach ($getusermeta as $user) {
                            $author_obj = get_user_by('id', $user['userid']);
                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                            $points = round_off_type($user['availablepoints'])
                            ?>
                            <tr>
                                <td data-value="<?php echo $i; ?>"><?php echo $i; ?></td>                                     
                                <td><?php echo is_object($author_obj) ? $author_obj->user_login : 'Guest'; ?> </td>                                     
                                <td><?php echo $points; ?></td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="clear:both;">
                            <td colspan="3">
                                <div class="pagination pagination-centered"></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <?php ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('.totaluser_current').footable();
                        jQuery('#change-page-sizesss').change(function (e) {
                            e.preventDefault();
                            var pageSize = jQuery(this).val();
                            jQuery('.footable').data('page-size', pageSize);
                            jQuery('.footable').trigger('footable_initialized');
                        });

                    });</script>
                <?php
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $linkforlogin = add_query_arg('redirect_to', get_permalink(), $myaccountlink);
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                echo '<br>' . $message . ' <a href=' . $linkforlogin . '> ' . $login . '</a>';
            }
        }

        public static function view_total_user_points() {
            if (is_user_logged_in()) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'rspointexpiry';
                $table_name2 = $wpdb->prefix . 'rsrecordpoints';
                $outputtablefieldss = '<p> ';
                $outputtablefieldss .= __('Page Size:', 'rewardsystem') . '<select id="change-page-sizess"><option value="5">5</option><option value="10">10</option><option value="50">50</option>
                    <option value="100">100</option>
                </select>';

                $outputtablefieldss .= '</p>';
                echo $outputtablefieldss;
                ?>

                <table class = "totaluser demo shop_table my_account_orders table-bordered" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next">
                    <thead><tr><th ><?php echo get_option('rs_my_rewards_sno_label'); ?></th>                   
                            <th data-sortable="false"><?php echo get_option('rs_my_rewards_userid_label'); ?></th>                  
                            <th data-type="numeric" data-sort-initial="true"><?php echo get_option('rs_my_rewards_points_earned_label'); ?></th>

                    <tbody>
                        <?php
                        $getusermeta = $wpdb->get_results("SELECT userid ,earnedpoints  FROM $table_name WHERE earnedpoints NOT IN(0) and expiredpoints IN(0) ORDER BY earnedpoints DESC ", ARRAY_A);
                        $i = 1;
                        foreach ($getusermeta as $user) {
                            $author_obj = get_user_by('id', $user['userid']);
                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                            $points = round_off_type($user['earnedpoints'])
                            ?>
                            <tr>
                                <td data-value="<?php echo $i; ?>"><?php echo $i; ?></td>                                     
                                <td><?php echo is_object($author_obj) ? $author_obj->user_login : 'Guest'; ?> </td>                                     
                                <td><?php echo $points; ?></td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="clear:both;">
                            <td colspan="3">
                                <div class="pagination pagination-centered"></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <?php ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('.totaluser').footable();
                        jQuery('#change-page-sizess').change(function (e) {
                            e.preventDefault();
                            var pageSize = jQuery(this).val();
                            jQuery('.footable').data('page-size', pageSize);
                            jQuery('.footable').trigger('footable_initialized');
                        });

                    });</script>
                <?php
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $linkforlogin = add_query_arg('redirect_to', get_permalink(), $myaccountlink);
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                echo '<br>' . $message . ' <a href=' . $linkforlogin . '> ' . $login . '</a>';
            }
        }

        public static function add_shortcode_to_display_user_total_points_in_value() {
            if (is_user_logged_in()) {
                $getcurrentuserid = get_current_user_id();
                echo '<b>' . display_total_currency_value($getcurrentuserid) . '</b>';
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $linkforlogin = add_query_arg('redirect_to', get_permalink(), $myaccountlink);
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                echo '<br>' . $message . ' <a href=' . $linkforlogin . '> ' . $login . '</a>';
            }
        }

        public static function add_script_to_my_account() {            
            if (!is_product() && !is_checkout() && !is_shop()) {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {                        
                        <?php 
                        if (get_option('rs_facebook_application_id') != '') {
                if (get_option('rs_account_show_hide_facebook_like_button') == '1') {
                        ?>
                        window.fbAsyncInit = function () {
                            FB.init({
                                appId: "<?php echo get_option('rs_facebook_application_id'); ?>",
                                xfbml: true,
                                version: 'v2.6'
                            });
                        };
                        console.log('loaded script . . . . . ');
                        (function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id))
                                return;
                            js = d.createElement(s);
                            js.id = id;
                <?php if ((get_option('rs_language_selection_for_button') == 1)) { ?>
                                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
                    <?php
                } else {
                    if (get_option('WPLANG') == '') {
                        ?>
                                    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
                    <?php } else { ?>
                                    js.src = "//connect.facebook.net/<?php echo get_option('WPLANG'); ?>/sdk.js#xfbml=1&version=v2.0";
                    <?php } ?>
                <?php } ?>
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                        <?php }                        
                    }
                    ?>                

                        function postToFeed(url, image, description, title) {
                            var obj = {
                                method: 'feed',
                                name: title,
                                link: url,
                                picture: image,
                                description: description
                            };
                            function callback(response) {
                                if (response != null) {
                                    alert('sucessfully posted');
                                } else {
                                    alert('cancel');
                                }
                            }
                            FB.ui(obj, callback);
                        }
                         jQuery('.share_wrapper').click(function (evt) {
                                    evt.preventDefault();
                                    var a = document.getElementById('share_wrapper')
                                    var url = a.getAttribute('href');
                                    var image = a.getAttribute('data-image');
                                    var title = a.getAttribute('data-title');
                                    var description = a.getAttribute('data-description');
                                    postToFeed(url, image, description, title);
                                    return false;
                                });                                
                        jQuery('.examples').footable().bind('footable_filtering', function (e) {                            
                            var selected = jQuery('.filter-status').find(':selected').text();
                            if (selected && selected.length > 0) {
                                e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                                e.clear = !e.filter;
                            }
                        });
                        jQuery('.referrallog').footable().bind('footable_filtering', function (e) {
                            var selected = jQuery('.filter-status').find(':selected').text();
                            if (selected && selected.length > 0) {
                                e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                                e.clear = !e.filter;
                            }
                        });                        
                        jQuery('.referral_link').footable().bind({
                            'footable_row_expanded': function (e) {
                                jQuery('.referralclick').click(function () {
                                    var getarraykey = jQuery(this).attr('data-array');
                                    console.log(jQuery(this).parent().parent().hide());
                                    var dataparam = ({
                                        action: 'unset_referral',
                                        unsetarray: getarraykey,
                                        userid: '<?php echo get_current_user_id(); ?>'
                                    });
                                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                            function (response) {
                                                var newresponse = response.replace(/\s/g, '');
                                                if (newresponse === "success") {
                                                    location.reload();
                                                }
                                            });
                                    return false;
                                });
                                jQuery('.share_wrapper').click(function (evt) {
                                    evt.preventDefault();
                                    var a = document.getElementById('share_wrapper')
                                    var url = a.getAttribute('href');
                                    var image = a.getAttribute('data-image');
                                    var title = a.getAttribute('data-title');
                                    var description = a.getAttribute('data-description');
                                    postToFeed(url, image, description, title);
                                    return false;
                                });
                            },
                        });
                        jQuery('#change-page-sizes').change(function (e) {
                            e.preventDefault();
                            var pageSize = jQuery(this).val();
                            jQuery('.footable').data('page-size', pageSize);
                            jQuery('.footable').trigger('footable_initialized');
                        });
                    });
                </script>
                <?php
            }
        }

        /* Shortcode For Total Redeem Points */

        public static function add_shortcode_to_display_total_redeem_points() {
            if (is_user_logged_in()) {
                global $wpdb;
                $table_name = $wpdb->prefix . "rspointexpiry";
                $getcurrentuserid = get_current_user_id();
                $current_user_points_log = $wpdb->get_results("SELECT SUM(usedpoints) as availablepoints FROM $table_name WHERE usedpoints NOT IN(0) and userid=$getcurrentuserid", ARRAY_A);
                $total_points_redemed = '0';
                foreach ($current_user_points_log as $separate_points) {
                    $totalredeempoints = self::function_to_get_total_redeemed_points_for_user();
                    $total_points_redemed = $separate_points['availablepoints'] + $totalredeempoints;
                }
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                return round_off_type($total_points_redemed);
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                $msg = $message . ' <a href=' . $myaccountlink . '> ' . $login . '</a>';
                return '<br>' . $msg;
            }
        }

        /* Shortcode for total Redeemed Points for User */

        public static function function_to_get_total_redeemed_points_for_user() {
            if (is_user_logged_in()) {
                $current_user_points_log = get_user_meta(get_current_user_id(), '_my_points_log', true);
                $total_points_redemed = '0';
                if ($current_user_points_log != '') {
                    foreach ($current_user_points_log as $separate_points) {
                        if (isset($separate_points['points_redeemed'])) {
                            if ($separate_points['points_redeemed'] != "") {
                                $total_points_redemed += $separate_points['points_redeemed'];
                            } else {
                                $total_points_redemed = "0";
                            }
                        }
                    }
                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';

                    return round_off_type($total_points_redemed);
                }
            }
        }

        /* Shortcode For Total Earned Points */

        public static function add_shortcode_to_display_total_earned_points() {
            if (is_user_logged_in()) {
                $totaloldearnedpoints = '';
                global $wpdb;
                $table_name = $wpdb->prefix . "rspointexpiry";
                $getcurrentuserid = get_current_user_id();
                $current_user_points_log = $wpdb->get_results("SELECT SUM(earnedpoints) as availablepoints FROM $table_name WHERE earnedpoints NOT IN(0) and userid=$getcurrentuserid", ARRAY_A);
                $total_points_earned = '0';
                foreach ($current_user_points_log as $separate_points) {
                    $deletedearnedpoints = get_user_meta($getcurrentuserid, 'rs_earned_points_before_delete', true);
                    $total_earned_points = get_user_meta($getcurrentuserid, 'rs_user_total_earned_points', true);
                    $oldearnedpoints = get_user_meta($getcurrentuserid, '_my_reward_points', true);
                    if ($total_earned_points > $oldearnedpoints) {
                        $totaloldearnedpoints = $total_earned_points - $oldearnedpoints;
                    }
                    $total_points_earned = $separate_points['availablepoints'] + $deletedearnedpoints + $totaloldearnedpoints;
                }
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                return round_off_type($total_points_earned);
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $linkforlogin = add_query_arg('redirect_to', get_permalink(), $myaccountlink);
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                echo '<br>' . $message . ' <a href=' . $linkforlogin . '> ' . $login . '</a>';
            }
        }

        /* Shortcode For Total Expired Points */

        public static function add_shortcode_to_display_total_expired_points() {
            if (is_user_logged_in()) {
                global $wpdb;
                $table_name = $wpdb->prefix . "rspointexpiry";
                $getcurrentuserid = get_current_user_id();
                $current_user_points_log = $wpdb->get_results("SELECT SUM(expiredpoints) as availablepoints FROM $table_name WHERE expiredpoints NOT IN(0) and userid=$getcurrentuserid", ARRAY_A);
                $total_points_expired = '0';
                foreach ($current_user_points_log as $separate_points) {
                    $deletedexpiredpoints = get_user_meta($getcurrentuserid, 'rs_expired_points_before_delete', true);
                    $total_points_expired = $separate_points['availablepoints'] + $deletedexpiredpoints;
                }
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';

                return round_off_type($total_points_expired);
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $linkforlogin = add_query_arg('redirect_to', get_permalink(), $myaccountlink);
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                echo '<br>' . $message . ' <a href=' . $linkforlogin . '> ' . $login . '</a>';
            }
        }

        public static function addshort_code_username() {
            if (get_option('rs_generate_referral_link_based_on_user') == '1') {
                $user = get_user_by('login', $_GET['ref']);
                $currentuserid = $user->user_nicename;
                return $currentuserid;
            } else {
                $user_info = get_userdata($_GET['ref']);
                $currentuserid = is_object($user_info) ? $user_info->user_login : 'Guest';
                return $currentuserid;
            }
        }

    }

    RSFunctionForMyAccount::init();
}