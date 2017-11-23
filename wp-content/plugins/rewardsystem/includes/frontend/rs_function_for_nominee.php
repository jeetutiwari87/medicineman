<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSFunctionForNominee')) {

    class RSFunctionForNominee {

        public static function init() {

            if (get_option('rs_nominee_activated') == 'yes' && get_option('rs_show_hide_nominee_field_in_checkout') == '1') {

                add_action('woocommerce_after_order_notes', array(__CLASS__, 'ajax_for_saving_nominee_in_checkout'));

                add_action('woocommerce_after_order_notes', array(__CLASS__, 'display_nominee_field_in_checkout'));
            }

            add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'save_selected_nominee_in_checkout'), 10, 2);

            if (get_option('rs_nominee_activated') == 'yes' && get_option('rs_show_hide_nominee_field') == '1') {

                add_action('woocommerce_after_my_account', array(__CLASS__, 'display_nominee_field_in_my_account'));

                add_shortcode('rs_nominee_table', array(__CLASS__, 'display_nominee_field_in_my_account'));
            }
            add_shortcode('rs_nominee_table', array(__CLASS__, 'display_nominee_field_in_my_account'));

            add_action('wp_ajax_nopriv_rs_save_nominee', array(__CLASS__, 'save_selected_nominee'));

            add_action('wp_ajax_rs_save_nominee', array(__CLASS__, 'save_selected_nominee'));
        }

        public static function display_nominee_field_in_checkout() {
            global $woocommerce;
            global $wp_roles;
            ?>
            <style type="text/css">
                .chosen-container-single {
                    position:absolute;
                }

            </style>        
            <?php
            $getnomineetype = get_option('rs_select_type_of_user_for_nominee_checkout');
            if ($getnomineetype == '1') {
                $getusers = get_option('rs_select_users_list_for_nominee_in_checkout');
                echo "<h2>" . get_option('rs_my_nominee_title_in_checkout') . "</h2>";
                if ($getusers != '') {
                    ?>
                    <table class="form-table">
                        <tr valign="top">
                            <td style="width:150px;">
                                <label for="rs_select_nominee_in_checkout" style="font-size:16px;font-weight: bold;"><?php _e('Select Nominee for Product Purchase', 'rewardsystem'); ?></label>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td style="width:300px;">
                                <select name="rs_select_nominee_in_checkout" style="width:300px;" id="rs_select_nominee_in_checkout" class="short rs_select_nominee_in_checkout">
                                    <option value=""><?php _e('Choose Nominee', 'rewardsystem'); ?></option>
                                    <?php
                                    $getusers = get_option('rs_select_users_list_for_nominee_in_checkout');
                                    $currentuserid = get_current_user_id();
                                    $usermeta = get_user_meta($currentuserid, 'rs_selected_nominee_in_checkout', true);
                                    if ($getusers != '') {
                                        if (!is_array($getusers)) {
                                            $userids = array_filter(array_map('absint', (array) explode(',', $getusers)));
                                            foreach ($userids as $userid) {
                                                $user = get_user_by('id', $userid);
                                                ?>
                                                <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>>
                                                    <?php if (get_option('rs_select_type_of_user_for_nominee_name_checkout') == '1') { ?>
                                                        <?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                                        <?php
                                                } else {
                                                    echo esc_html($user->display_name);
                                                }
                                                ?>
                                                <?php
                                            }
                                        } else {
                                            $userids = $getusers;
                                            foreach ($userids as $userid) {
                                                $user = get_user_by('id', $userid);
                                                ?>
                                                <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>>
                                                    <?php if (get_option('rs_select_type_of_user_for_nominee_name_checkout') == '1') { ?>
                                                        <?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                                        <?php
                                                } else {
                                                    echo esc_html($user->display_name);
                                                }
                                                ?>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </td> 

                        </tr>
                    </table>
                    <?php
                } else {
                    _e('You have no Nominee', 'rewardsystem');
                }
            } else {
                $getuserrole = get_option('rs_select_users_role_for_nominee_checkout');
                echo "<h2>" . get_option('rs_my_nominee_title_in_checkout') . "</h2>";
                if ($getuserrole != '') {
                    ?>
                    <table class="form-table">
                        <tr valign="top">
                            <td style="width:150px;">
                                <label for="rs_select_nominee_in_checkout" style="font-size:20px;font-weight:bold;"><?php _e('Select Nominee', 'rewardsystem'); ?></label>
                            </td>
                            <td style="width:300px;">
                                <select name="rs_select_nominee_in_checkout" style="width:300px;" id="rs_select_nominee_in_checkout" class="short rs_select_nominee_in_checkout">
                                    <option value=""><?php _e('Choose Nominee', 'rewardsystem'); ?></option>
                                    <?php
                                    $getusers = get_option('rs_select_users_role_for_nominee_checkout');
                                    $currentuserid = get_current_user_id();
                                    $usermeta = get_user_meta($currentuserid, 'rs_selected_nominee_in_checkout', true);
                                    if ($getusers != '') {
                                        if (is_array($getusers)) {
                                            foreach ($getusers as $userrole) {
                                                $args['role'] = $userrole;
                                                $users = get_users($args);
                                                foreach ($users as $user) {
                                                    $userid = $user->ID;
                                                    ?>
                                                    <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>>
                                                        <?php if (get_option('rs_select_type_of_user_for_nominee_name_checkout') == '1') { ?>
                                                            <?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option> ?></option>
                                                            <?php
                                                    } else {
                                                        echo esc_html($user->display_name);
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </td>

                        </tr>
                    </table>
                    <?php
                } else {
                    _e('You have no Nominee', 'rewardsystem');
                }
            }
        }

        public static function ajax_for_saving_nominee_in_checkout() {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#rs_select_nominee_in_checkout').change(function () {
                        var value = jQuery('#rs_select_nominee_in_checkout').val();
                        var Value = {
                            action: "rs_save_nominee_in_checkout",
                            selectedvalue: value,
                        };
                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", Value, function (response) {
                            console.log('Success');
                        });
                        return false;
                    });
                    return false;
                });
            </script>
            <?php
        }

        public static function save_selected_nominee_in_checkout($order_id, $user_id) {
            if (get_option('rs_nominee_activated') == 'yes') {
                $getpostvalue = isset($_POST['rs_select_nominee_in_checkout']) ? $_POST['rs_select_nominee_in_checkout'] : '';
                update_post_meta($order_id, 'rs_selected_nominee_in_checkout', $getpostvalue);
            }else{
                update_post_meta($order_id, 'rs_selected_nominee_in_checkout', '');
            }
        }
        
         public static function display_nominee_field_in_my_account() {
             echo self::display_nominee_field_in_my_account_buffer();
         }

        public static function display_nominee_field_in_my_account_buffer() {
            if (is_user_logged_in()) {
                ob_start () ;
                global $woocommerce;
                global $wp_roles;
                ?>
                <style type="text/css">
                    .chosen-container-single {
                        position:absolute;
                    }
                </style>
                <?php
                $getnomineetype = get_option('rs_select_type_of_user_for_nominee');
                if ($getnomineetype == '1') {
                    $getusers = get_option('rs_select_users_list_for_nominee');
                    echo "<h2>" . get_option('rs_my_nominee_title') . "</h2>";
                    if ($getusers != '') {
                        ?>
                        <table class="form-table">
                            <tr valign="top">
                                <td style="width:150px;">
                                    <label for="rs_select_nominee" style="font-size:20px;font-weight: bold;"><?php _e('Select Nominee', 'rewardsystem'); ?></label>
                                </td>
                                <td style="width:300px;">
                                    <select name="rs_select_nominee" style="width:300px;" id="rs_select_nominee" class="short rs_select_nominee">
                                        <option value=""><?php _e('Choose Nominee', 'rewardsystem'); ?></option>
                                        <?php
                                        $getusers = get_option('rs_select_users_list_for_nominee');
                                        $currentuserid = get_current_user_id();
                                        $usermeta = get_user_meta($currentuserid, 'rs_selected_nominee', true);
                                        if ($getusers != '') {
                                            if (!is_array($getusers)) {
                                                $userids = array_filter(array_map('absint', (array) explode(',', $getusers)));
                                                foreach ($userids as $userid) {
                                                    $user = get_user_by('id', $userid);
                                                    ?>
                                                    <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>>
                                                        <?php if (get_option('rs_select_type_of_user_for_nominee_name') == '1') { ?>
                                                            <?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                                        <?php
                                                    } else {
                                                        echo esc_html($user->display_name);
                                                    }
                                                }
                                            } else {
                                                $userids = $getusers;
                                                foreach ($userids as $userid) {
                                                    $user = get_user_by('id', $userid);
                                                    ?>
                                                    <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>>
                                                        <?php if (get_option('rs_select_type_of_user_for_nominee_name') == '1') { ?>
                                                            <?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                                        <?php
                                                    } else {
                                                        echo esc_html($user->display_name);
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="width:150px;">
                                    <input type="button" value="Add" class="rs_add_nominee"/>
                                </td>
                            </tr>
                        </table>
                        <?php
                        $contents       = ob_get_contents () ;
                        ob_end_clean () ;
                    } else {
                        $contents = __('You have no Nominee', 'rewardsystem');
                    }
                } else {
                    $getuserrole = get_option('rs_select_users_role_for_nominee');
                    echo "<h2>" . get_option('rs_my_nominee_title') . "</h2>";
                    if ($getuserrole != '') {
                        ?>
                        <table class="form-table">
                            <tr valign="top">
                                <td style="width:150px;">
                                    <label for="rs_select_nominee" style="font-size:20px;font-weight:bold;"><?php _e('Select Nominee', 'rewardsystem'); ?></label>
                                </td>
                                <td style="width:300px;">
                                    <select name="rs_select_nominee" style="width:300px;" id="rs_select_nominee" class="short rs_select_nominee">
                                        <option value=""><?php _e('Choose Nominee', 'rewardsystem'); ?></option>
                                        <?php
                                        $getusers = get_option('rs_select_users_role_for_nominee');
                                        $currentuserid = get_current_user_id();
                                        $usermeta = get_user_meta($currentuserid, 'rs_selected_nominee', true);
                                        if ($getusers != '') {
                                            if (is_array($getusers)) {
                                                foreach ($getusers as $userrole) {
                                                    $args['role'] = $userrole;
                                                    $users = get_users($args);
                                                    foreach ($users as $user) {
                                                        $userid = $user->ID;
                                                        ?>
                                                        <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>>
                                                            <?php if (get_option('rs_select_type_of_user_for_nominee_name') == '1') { ?>
                                                                <?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                                            <?php
                                                        } else {
                                                            echo esc_html($user->display_name);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="width:150px;">
                                    <input type="button" value="Add" class="rs_add_nominee"/>
                                </td>
                            </tr>
                        </table>
                        <?php
                        $contents       = ob_get_contents () ;
                        ob_end_clean () ;
                    } else {
                       $contents = __('You have no Nominee', 'rewardsystem');
                    }
                }
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $linkforlogin = add_query_arg('redirect_to', get_permalink(), $myaccountlink);
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                $contents = '<br>' . $message . ' <a href=' . $linkforlogin . '> ' . $login . '</a>';
            }
            return $contents;
        }

        public static function ajax_for_saving_nominee() {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('.rs_add_nominee').click(function () {
                        var value = jQuery('#rs_select_nominee').val();
                        var Value = {
                            action: "rs_save_nominee",
                            selectedvalue: value,
                        };
                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", Value, function (response) {
                            alert("Nominee Saved");
                            console.log('Success');
                        });
                        return false;
                    });
                    return false;
                });
            </script>
            <?php
            if(get_option('rs_nominee_activated') == 'no'){
                $currentuserid = get_current_user_id();
                update_user_meta($currentuserid, 'rs_selected_nominee', '');
                update_user_meta($currentuserid, 'rs_enable_nominee', 'no');
            }
        }

        public static function save_selected_nominee() {
            $getpostvalue = $_POST['selectedvalue'];
            $currentuserid = get_current_user_id();
            update_user_meta($currentuserid, 'rs_selected_nominee', $getpostvalue);
            update_user_meta($currentuserid, 'rs_enable_nominee', 'yes');
        }

        /*
         * Function for choosen in Select user role as Nominee
         */

        public static function rs_chosen_for_nominee_in_my_account_tab() {
            if (is_account_page()) {
                global $woocommerce;
                if ((float) $woocommerce->version > (float) ('2.2.0')) {
                    echo rs_common_select_function('.rs_select_nominee');
                } else {
                    echo rs_common_chosen_function('.rs_select_nominee');
                }
            }
        }


    }

    RSFunctionForNominee::init();
}