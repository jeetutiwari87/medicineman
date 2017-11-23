<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSFunctionForReferAFriend')) {

    class RSFunctionForReferAFriend {

        public static function init() {
            if (get_option('rs_referral_activated') == 'yes') {

                add_action('wp_head', array(__CLASS__, 'check_cokkies_for_referal'));

                add_action('wp_ajax_nopriv_unset_referral', array(__CLASS__, 'unset_array_referral_key'));

                add_action('wp_ajax_unset_referral', array(__CLASS__, 'unset_array_referral_key'));

                add_action('wp_ajax_nopriv_ajaxify_referral', array(__CLASS__, 'ajaxify_referral_key'));

                add_action('wp_ajax_ajaxify_referral', array(__CLASS__, 'ajaxify_referral_key'));

                add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'checkout_cookies_referral_meta'), 10, 2);

                if (get_option('rs_display_generate_referral') == '2') {
                    if (get_option('rs_show_hide_generate_referral') == '1') {

                        if (get_option('rs_show_hide_generate_referral_link_type') == '1') {

                            add_action('woocommerce_after_my_account', array(__CLASS__, 'generate_referral_key'));

                            add_action('woocommerce_after_my_account', array(__CLASS__, 'list_table_array'));
                        } else {
                            add_action('woocommerce_after_my_account', array(__CLASS__, 'function_to_display_static_url'));
                        }
                    }
                }

                add_shortcode('rs_refer_a_friend', array(__CLASS__, 'reward_system_refer_a_friend_shortcode'));

                if (get_option('rs_display_generate_referral') == '1') {

                    if (get_option('rs_show_hide_generate_referral') == '1') {

                        if (get_option('rs_show_hide_generate_referral_link_type') == '1') {

                            add_action('woocommerce_before_my_account', array(__CLASS__, 'function_for_referal_link'));
                        } else {
                            add_action('woocommerce_before_my_account', array(__CLASS__, 'static_referral_function'));
                        }
                    }
                }

                if (get_option('rs_show_hide_generate_referral_message') == '1') {

                    add_action('wp', array(__CLASS__, 'rs_show_referrer_name_in_home_page'));
                    //add_filter('template_include', array(__CLASS__, 'rs_show_referrer_name_in_home_page'), 10, 1);
                }

                add_shortcode('rs_generate_referral', array(__CLASS__, 'rs_fp_rewardsystem'));

                add_shortcode('rs_generate_static_referral', array(__CLASS__, 'shortcode_for_static_referral_link'));
            }
        }

        public static function reward_system_refer_a_friend_shortcode() {
            wp_enqueue_script('referfriend', false, array(), '', true);
            ob_start();
            ?>
            <style type="text/css">
                <?php echo get_option('rs_refer_a_friend_custom_css'); ?>;
            </style>
            <?php
            if (is_user_logged_in()) {
                $get_user_type = get_option('rs_select_type_of_user_for_referral');
                $check_user_restriction = rs_function_to_check_the_restriction_for_referral($get_user_type);
                if ($check_user_restriction) {
                    ?>
                    <form id="rs_refer_a_friend_form" method="post">
                        <table class="shop_table my_account_referrals">
                            <tr>
                                <td><h3><?php echo addslashes(get_option('rs_my_rewards_friend_name_label')); ?></h3></td>
                                <td><input type="text" name="rs_friend_name" placeholder ="<?php echo addslashes(get_option('rs_my_rewards_friend_name_placeholder')); ?>" id="rs_friend_name" value=""/>
                                    <br>
                                    <div class="rs_notification"></div>
                                </td>
                            </tr>
                            <tr>
                                <td><h3><?php echo addslashes(get_option('rs_my_rewards_friend_email_label')); ?></h3></td>
                                <td><input type="text" name="rs_friend_email" placeholder="<?php echo addslashes(get_option('rs_my_rewards_friend_email_placeholder')); ?>" id="rs_friend_email" value=""/>
                                    <br>
                                    <div class="rs_notification"></div>
                                </td>
                            </tr>
                            <tr>
                                <td><h3><?php echo addslashes(get_option('rs_my_rewards_friend_subject_label')); ?></h3></td>
                                <td><input type="text" name="rs_friend_subject" id="rs_friend_subject" placeholder ="<?php echo addslashes(get_option('rs_my_rewards_friend_email_subject_placeholder')); ?>" value=""/>
                                    <br>
                                    <div class="rs_notification"></div>
                                </td>
                            </tr>
                            <tr>
                                <td><h3><?php echo addslashes(get_option('rs_my_rewards_friend_message_label')); ?></h3></td>
                                <?php
                                $currentuserid = get_current_user_id();
                                $user_info = get_userdata($currentuserid);
                                if (get_option('rs_generate_referral_link_based_on_user') == '1') {
                                    $referralperson = is_object($user_info) ? $user_info->user_login : 'Guest';
                                } else {
                                    $referralperson = $currentuserid;
                                }
                                $friend_free_product_to_find = "[site_referral_url]";
                                $friend_free_product_to_replace = esc_url_raw(add_query_arg('ref', $referralperson, get_option('rs_prefill_generate_link')));
                                $friend_free_product_replaced = str_replace($friend_free_product_to_find, $friend_free_product_to_replace, addslashes(htmlentities(get_option('rs_friend_referral_link'))));
                                $referurl = $friend_free_product_replaced;
                                ?>
                                <td><textarea rows="5" cols="35" id="rs_your_message" placeholder ="<?php echo addslashes(get_option('rs_my_rewards_friend_email_message_placeholder')); ?>" name="rs_your_message"><?php echo $referurl; ?></textarea>
                                    <br>
                                    <div class="rs_notification"></div>
                                </td>
                            </tr>


                            <?php
                            $show = get_option('rs_show_hide_iagree_termsandcondition_field');
                            if ($show == '2') {
                                ?>    
                                <tr>
                                    <td colspan="2">

                                        <input type="checkbox" name="rs_terms"  id="rs_terms" /> 
                                        <?php
                                        $initialmessage = addslashes(get_option('rs_refer_friend_iagreecaption_link'));
                                        $stringtofind = "{termsandconditions}";
                                        $hyperlinkforterms = get_option('rs_refer_friend_termscondition_url');
                                        $stringtoreplace = "<a href='$hyperlinkforterms'>" . addslashes(get_option('rs_refer_friend_termscondition_caption')) . "</a>";
                                        $replacedcontent = str_replace($stringtofind, $stringtoreplace, $initialmessage);
                                        echo $replacedcontent;
                                        ?>  

                                        <div class ="iagreeerror" style="display:none;"><?php echo addslashes(get_option('rs_iagree_error_message')); ?></div>

                                    </td>
                                <br>
                                <div class="rs_notification"></div> 
                                </tr>
                            <?php } ?>    
                        </table>    
                        <input type="submit" class="button-primary" name="submit" id="rs_refer_submit" value="<?php _e('Send Mail', 'rewardsystem'); ?>"/>
                        <div class="rs_notification_final"></div>
                    </form>
                    <?php
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
            $maincontent = ob_get_clean();
            return $maincontent;
        }

        /* Handled RS referal link in buffer For site slowness issue */
        public static function function_for_referal_link_buffer() {
            ob_start () ;
            $get_user_type = get_option('rs_select_type_of_user_for_referral');
            $check_user_restriction = rs_function_to_check_the_restriction_for_referral($get_user_type);
            if ($check_user_restriction) {
                self::generate_referral_key();
                self::list_table_array();
            }
            $contents  = ob_get_contents () ;
            ob_end_clean () ;
            return $contents;
        }
        
         public static function function_for_referal_link() {
             echo self::function_for_referal_link_buffer();
         }
         
          public static function static_referral_function() {
              echo self::static_referral_function_buffer();
          }
        /* Handled static url function in buffer For site slowness issue */ 
        public static function static_referral_function_buffer() {
            ob_start () ;
            $get_user_type = get_option('rs_select_type_of_user_for_referral');
            $check_user_restriction = rs_function_to_check_the_restriction_for_referral($get_user_type);
            if ($check_user_restriction) {
                 self::function_to_display_static_url();
            }
            $contents = ob_get_contents () ;
            ob_end_clean () ;
            return $contents;
        }

        
         /* Function to display the input field and button for Generate Referral Link */
        public static function generate_referral_key() {
            if (is_user_logged_in()) {
                ?>
                <div class="referral_field1" style="margin-top:10px;">
                    <input type="text" size="50" name="generate_referral_field" id="generate_referral_field" required="required" value="<?php echo get_option('rs_prefill_generate_link'); ?>"><input type="submit" style="margin-left:10px;" class="button <?php echo get_option('rs_extra_class_name_generate_referral_link'); ?>" name="refgeneratenow" id="refgeneratenow" value="<?php echo get_option('rs_generate_link_button_label'); ?>"/>
                </div>                
                <?php
            } else {
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                echo 'Please Login to View the Content of  this Page <a href=' . $myaccountlink . '> Login </a>';
            }
        }

        public static function rs_script_to_generate_referral_link() {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#refgeneratenow').click(function () {
                        var referral_generate = jQuery('#generate_referral_field').val();
                        if (referral_generate === '') {
                            jQuery('#generate_referral_field').css('outline', 'red solid');
                            return false;
                        } else {
                            jQuery('#generate_referral_field').css('outline', '');
                            var urlstring = jQuery('#generate_referral_field').val();
                            var dataparam = ({
                                action: 'ajaxify_referral',
                                url: urlstring,
                                userid: '<?php echo get_current_user_id(); ?>',
                            });
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                    function (response) {
                                        jQuery(".my_account_referral_link").load(window.location + " .my_account_referral_link");
                                        jQuery(document).ajaxComplete(function () {
                                            try {
                                                twttr.widgets.load();
                                                FB.XFBML.parse();
                                                gapi.plusone.go();
                                            } catch (ex) {
                                                
                                            }
                                            jQuery('.referralclick').click(function () {
                                                var getarraykey = jQuery(this).attr('data-array');
                                                jQuery(this).parent().parent().hide();
                                                var dataparam = ({
                                                    action: 'unset_referral',
                                                    unsetarray: getarraykey,
                                                    userid: '<?php echo get_current_user_id(); ?>',
                                                });
                                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                                        function (response) {
                                                            var newresponse = response.replace(/\s/g, '');
                                                            if (newresponse === "success") {

                                                            }
                                                        });
                                                return false;
                                            });
                                        });
                                        if (response === "success") {
                                            location.reload();
                                        }
                                    });
                            return false;
                        }
                    });

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

                                    }
                                });
                        return false;
                    });
                });
            </script>
            <?php
        }

         /* Handled list table in buffer For site slowness issue */
        public static function list_table_array() {
            if (is_user_logged_in()) {
                ?>
                <style type="text/css">
                    .referralclick {
                        border: 2px solid #a1a1a1;
                        padding: 3px 9px;
                        background: #dddddd;
                        width: 5px;
                        border-radius: 25px;
                    }
                    .referralclick:hover {
                        cursor: pointer;
                        background:red;
                        color:#fff;
                        border: 2px solid #fff;
                    }
                </style>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('.referrals .footable-toggle').click(function () {
                            gapi.plusone.go();
                            jQuery('.rs_social_buttons .fb-share-button span').css("width", "60px");
                            jQuery('.rs_social_buttons .fb-share-button span iframe').css({"width": "59px", "height": "29px", "visibility": "visible"});
                            jQuery('.rs_social_buttons iframe.twitter-share-button').css({"width": "59px", "height": "29px", "visibility": "visible"});
                        });
                        jQuery('.referrals').click(function () {
                            gapi.plusone.go();
                            jQuery('.rs_social_buttons .fb-share-button span').css("width", "60px");
                            jQuery('.rs_social_buttons .fb-share-button span iframe').css({"width": "59px", "height": "29px", "visibility": "visible"});
                            jQuery('.rs_social_buttons iframe.twitter-share-button').css({"width": "59px", "height": "29px", "visibility": "visible"});
                        });
                    });
                </script>
                <div id="fb-root"></div>
                <script>(function (d, s, id) {
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
                    }(document, 'script', 'facebook-jssdk'));</script>
                <script>!function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = p + '://platform.twitter.com/widgets.js';
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, 'script', 'twitter-wjs');</script>

                <!-- Place this tag where you want the share button to render. -->


                <!-- Place this tag after the last share tag. -->
                <script>
                    window.___gcfg = {
                        lang: '<?php echo get_option('WPLANG') == '' ? 'en_US' : get_option('WPLANG'); ?>',
                        parsetags: 'onload'
                    }
                </script>                
                <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
                    {
                        parsetags: 'explicit'
                    }
                </script>
                <h3><?php echo get_option('rs_generate_link_label'); ?></h3>
                <table class="referral_link shop_table my_account_referral_link" id="my_account_referral_link">
                    <thead>
                        <tr>
                            <th class="referral-number"><span class="nobr"><?php echo get_option('rs_generate_link_sno_label'); ?></span></th>
                            <th class="referral-date"><span class="nobr"><?php echo get_option('rs_generate_link_date_label'); ?></span></th>
                            <th class="referral-link"><span class="nobr"><?php echo get_option('rs_generate_link_referrallink_label'); ?></span></th>
                            <th data-hide='phone,tablet' class="referral-social"><span class="nobr"><?php echo get_option('rs_generate_link_social_label'); ?></span></th>
                            <th data-hide='phone,tablet' class="referral-actions"><span class="nobr"><?php echo get_option('rs_generate_link_action_label'); ?></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentuserid = get_current_user_id();
                        if (is_array(get_option('arrayref' . $currentuserid))) {
                            $i = 1;
                            $j = 0;
                            foreach (get_option('arrayref' . $currentuserid) as $array => $key) {
                                $mainkey = explode(',', $key);
                                ?>
                                <tr class="referrals" data-url="<?php echo $mainkey[0]; ?>">
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $mainkey[1]; ?></td>
                                    <td><?php echo $mainkey[0]; ?></td>
                                    <td>
                                        <div class="rs_social_buttons">      
                                            <?php if (get_option('rs_account_show_hide_facebook_like_button') == '1') { ?>
                                                <div class="share_wrapper" id="share_wrapper" href="<?php echo $mainkey[0]; ?>" data-image="<?php echo get_option('rs_fbshare_image_url_upload') ?>" data-title="<?php echo get_option('rs_facebook_title') ?>" data-description="<?php echo get_option('rs_facebook_description') ?>">
                                                    <img class='fb_share_img' src="<?php echo REWARDSYSTEM_PLUGIN_DIR_URL; ?>/admin/images/icon1.png"> <span class="label"><?php echo get_option('rs_fbshare_button_label'); ?> </span>
                                                </div> 
                                            <?php } ?>
                                            <?php if (get_option('rs_account_show_hide_twitter_tweet_button') == '1') { ?>
                                                <a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-url="<?php echo $mainkey[0]; ?>">Tweet</a>
                                            <?php } ?><br>

                                            <?php if (get_option('rs_acount_show_hide_google_plus_button') == '1') { ?>
                                                <div class="g-plusone" data-action="share" data-annotation="none" data-href="<?php echo $mainkey[0]; ?>"><g:plusone></g:plusone></div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td><span data-array="<?php echo $array; ?>" class="referralclick">x</span></td>
                                </tr>
                            <style>
                                .share_wrapper{
                                    margin-top: -12px;
                                    background-color:#3b5998;
                                    /*padding:2px;*/
                                    color:#fff;
                                    cursor:pointer;
                                    font-size:12px;
                                    font-weight:bold;
                                    border: 1px solid transparent;
                                    border-radius: 2px ;
                                    width:auto;
                                    height:23px;
                                }
                                .fb_share_img{
                                    margin-top: -3px;
                                    margin-left: 3px;
                                    margin-right: 3px;
                                }
                            </style>
                            <?php
                            $i++;
                            $j++;
                        }
                    }
                    ?>
                </tbody>
                </table>
                <?php
            }
        }
        
         /* Handled static url display in buffer For site slowness issue */          
        public static function function_to_display_static_url() {
            if (is_user_logged_in()) {
                $currentuserid = get_current_user_id();
                $objectcurrentuser = get_userdata($currentuserid);
                if (get_option('rs_generate_referral_link_based_on_user') == '1') {
                    $referralperson = is_object($objectcurrentuser) ? $objectcurrentuser->user_login : 'Guest';
                } else {
                    $referralperson = $currentuserid;
                }
                if (get_option('rs_show_hide_generate_referral_link_type') == '2') {
                    $refurl = add_query_arg('ref', $referralperson, get_option('rs_static_generate_link'));
                    ?>              
                    <script>!function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                            if (!d.getElementById(id)) {
                                js = d.createElement(s);
                                js.id = id;
                                js.src = p + '://platform.twitter.com/widgets.js';
                                fjs.parentNode.insertBefore(js, fjs);
                            }
                        }(document, 'script', 'twitter-wjs');</script>

                    <!-- Place this tag where you want the share button to render. -->


                    <!-- Place this tag after the last share tag. -->
                    <script>
                        window.___gcfg = {
                            lang: '<?php echo get_option('WPLANG') == '' ? 'en_US' : get_option('WPLANG'); ?>',
                            parsetags: 'onload'
                        }
                    </script>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            gapi.plusone.go();
                        });
                    </script>
                    <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
                        {
                            parsetags: 'explicit'
                        }
                    </script>
                    <h3><?php echo get_option('rs_my_referral_link_button_label'); ?></h3>
                    <table class="shop_table my_account_referral_link_static" id="my_account_referral_link_static">
                        <thead>
                            <tr>
                                <th class="referral-number_static"><span class="nobr"><?php echo get_option('rs_generate_link_sno_label'); ?></span></th>                        
                                <th class="referral-link_static"><span class="nobr"><?php echo get_option('rs_generate_link_referrallink_label'); ?></span></th>
                                <th  class="referral-social_static"><span class="nobr"><?php echo get_option('rs_generate_link_social_label'); ?></span></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $j = 0;
                            ?>
                            <tr class="referrals_static">
                                <td><?php echo 1; ?></td>

                                <td><?php
                                    echo $refurl;
                                    ?></td>
                                <td>
                                    <?php if (get_option('rs_account_show_hide_facebook_like_button') == '1') { ?>
                                        <div class="share_wrapper" id="share_wrapper" href="<?php echo $refurl; ?>" data-image="<?php echo get_option('rs_fbshare_image_url_upload') ?>" data-title="<?php echo get_option('rs_facebook_title') ?>" data-description="<?php echo get_option('rs_facebook_description') ?>">
                                            <img class='fb_share_img' src="<?php echo REWARDSYSTEM_PLUGIN_DIR_URL; ?>/admin/images/icon1.png"> <span class="label"><?php echo get_option('rs_fbshare_button_label'); ?> </span>
                                        </div>
                                    <?php } ?>
                                    <?php if (get_option('rs_account_show_hide_twitter_tweet_button') == '1') { ?>
                                        <a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-url="<?php echo $refurl; ?>">Tweet</a>
                                    <?php } ?><br>
                                    <?php if (get_option('rs_acount_show_hide_google_plus_button') == '1') { ?>
                                        <div class="g-plusone" data-action="share" data-annotation="none" data-href="<?php echo $refurl; ?>"><g:plusone></g:plusone></div>
                                    <?php } ?>
                                </td>
                        <style>
                            .share_wrapper{
                                margin-top: -12px;
                                background-color:#3b5998;
                                /*padding:2px;*/
                                color:#fff;
                                cursor:pointer;
                                font-size:12px;
                                font-weight:bold;
                                border: 1px solid transparent;
                                border-radius: 2px ;
                                width:auto;
                                height:23px;
                            }
                            .fb_share_img{
                                margin-top: -3px;
                                margin-left: 3px;
                                margin-right: 3px;
                            }
                        </style>
                        <?php if (get_option('rs_account_show_hide_facebook_like_button') == '1') { ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function () {
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
                                });
                            </script>
                        <?php } ?>
                    </tr>                    
                    </tbody>
                    </table>
                    <?php
                }
            }
        }

        public static function unset_array_referral_key() {
            $currentuserid = $_POST['userid'];
            if (isset($_POST['unsetarray'])) {
                $listarray = get_option('arrayref' . $currentuserid);
                unset($listarray[$_POST['unsetarray']]);
                update_option('arrayref' . $currentuserid, $listarray);
                echo "success";
            }
            exit();
        }

        public static function ajaxify_referral_key() {
            $currentuserid = $_POST['userid'];
            $objectcurrentuser = get_userdata($currentuserid);
            if (get_option('rs_generate_referral_link_based_on_user') == '1') {
                $referralperson = is_object($objectcurrentuser) ? $objectcurrentuser->user_login : 'Guest';
            } else {
                $referralperson = $currentuserid;
            }

            if (isset($_POST['url'])) {
                $refurl = add_query_arg('ref', $referralperson, $_POST['url']);
                $previousref = get_option('arrayref' . $currentuserid);
                $dateformat = get_option('date_format');
                $arrayref[] = $refurl . ',' . date_i18n($dateformat);
                if (is_array($previousref)) {
                    $arrayref = array_unique(array_merge($previousref, $arrayref), SORT_REGULAR);
                }
                update_option('arrayref' . $currentuserid, $arrayref);
                echo "success";
            }
            exit();
        }

        public static function check_cokkies_for_referal() {
            $get_user_type = get_option('rs_select_type_of_user_for_referral');
            $check_user_restriction = rs_function_to_check_the_restriction_for_referral($get_user_type);
            if ($check_user_restriction) {
                self::count_statistics_referral();
            }
        }

        public static function count_statistics_referral() {
            if (isset($_GET['ref']) && !is_user_logged_in()) {
                if (get_option('rs_referral_cookies_expiry') == '1') {
                    $min = get_option('rs_referral_cookies_expiry_in_min') == '' ? '1' : get_option('rs_referral_cookies_expiry_in_min');
                    setcookie('rsreferredusername', $_GET['ref'], time() + 60 * $min, '/');
                } elseif (get_option('rs_referral_cookies_expiry') == '2') {
                    $hour = get_option('rs_referral_cookies_expiry_in_hours') == '' ? '1' : get_option('rs_referral_cookies_expiry_in_hours');
                    $hours = 60 * $hour;
                    setcookie('rsreferredusername', $_GET['ref'], time() + 60 * $hours, '/');
                } else {
                    $day = get_option('rs_referral_cookies_expiry_in_days') == '' ? '1' : get_option('rs_referral_cookies_expiry_in_days');
                    $days = 24 * $day;
                    setcookie('rsreferredusername', $_GET['ref'], time() + 60 * 60 * $days, '/');
                }
                $user = get_user_by('login', $_GET['ref']);
                if ($user != false) {
                    $currentuserid = $user->ID;
                } else {
                    $currentuserid = $_GET['ref'];
                }
                if (isset($_COOKIE['rsreferredusername'])) {
                    $mycookies = $_COOKIE['rsreferredusername'];
                } else {
                    $mycookies = '';
                }
                if ($mycookies == '') {
                    $previouscount = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($currentuserid, 'rsreferredusernameclickthrough');
                    $updatedcount = (float)$previouscount + 1;
                    update_user_meta($currentuserid, 'rsreferredusernameclickthrough', $updatedcount);
                }
            }
            if (isset($_COOKIE['rsreferredusername'])) {
                $mycookies = $_COOKIE['rsreferredusername'];
                RSPointExpiry::delete_cookie_after_some_purchase($mycookies);
            }
        }

        public static function checkout_cookies_referral_meta($order_id, $order_posted) {
            if (isset($_COOKIE['rsreferredusername'])) {
                if (get_option('rs_generate_referral_link_based_on_user') == '1') {
                    $user = get_user_by('login', $_COOKIE['rsreferredusername']);
                    $myid = $user->ID;
                } else {
                    $refuser = get_userdata($_COOKIE['rsreferredusername']);
                    $myid = $refuser->ID;
                }

                if (get_current_user_id() != $myid) {
                    $getcurrentuserid = get_current_user_id();
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($order_id, '_referrer_name', $_COOKIE['rsreferredusername']);
                    $referral_data = array(
                        'referred_user_name' => $_COOKIE['rsreferredusername'],
                        'award_referral_points_for_renewal' => get_option('rs_award_referral_point_for_renewal_order'),
                    );
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($order_id, 'rs_referral_data_for_renewal_order', $referral_data);
                    $getmetafromuser = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($getcurrentuserid, '_update_user_order');
                    $getorderlist[] = $order_id;
                    if (is_array($getmetafromuser)) {
                        $mainmerge = array_merge($getmetafromuser, $getorderlist);
                    } else {
                        $mainmerge = $getorderlist;
                    }
                    update_user_meta($getcurrentuserid, '_update_user_order', $mainmerge);
                }
            }
        }

        public static function rs_show_referrer_name_in_home_page($query) {
            $get_user_type = get_option('rs_select_type_of_user_for_referral');
            $check_user_restriction = rs_function_to_check_the_restriction_for_referral($get_user_type);
            if (isset($_GET['ref']) && !is_user_logged_in() && $check_user_restriction) {
                get_header();
                ?>
                <div class="referral_field" style="margin-top:40px;">
                    <h4><?php echo do_shortcode(get_option('rs_show_hide_generate_referral_message_text')); ?></h4>
                </div>
                <style>
                    h4 {text-align:center;}
                </style>
                <?php
            }
            return $query;
        }

        public static function rs_fp_rewardsystem($atts) {
            if (is_user_logged_in()) {
                $get_user_type = get_option('rs_select_type_of_user_for_referral');
                $check_user_restriction = rs_function_to_check_the_restriction_for_referral($get_user_type);
                if ($check_user_restriction) {
                    ob_start();
                    extract(shortcode_atts(array(
                        'referralbutton' => 'show',
                        'referraltable' => 'show',
                                    ), $atts));
                    if ($referralbutton == 'show') {
                        RSFunctionForReferAFriend::generate_referral_key();
                    }
                    if ($referraltable == 'show') {
                        RSFunctionForReferAFriend::list_table_array();
                    }
                    $maincontent = ob_get_clean();
                    return $maincontent;
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

        public static function shortcode_for_static_referral_link() {
            if (is_user_logged_in()) {
                $get_user_type = get_option('rs_select_type_of_user_for_referral');
                $check_user_restriction = rs_function_to_check_the_restriction_for_referral($get_user_type);
                if ($check_user_restriction) {
                    ob_start();
                    $currentuserid = get_current_user_id();
                    $objectcurrentuser = get_userdata($currentuserid);
                    if (get_option('rs_generate_referral_link_based_on_user') == '1') {
                        $referralperson = is_object($objectcurrentuser) ? $objectcurrentuser->user_login : 'Guest';
                    } else {
                        $referralperson = $currentuserid;
                    }

                    $refurl = add_query_arg('ref', $referralperson, get_option('rs_static_generate_link'));
                    ?><h3><?php echo get_option('rs_my_referral_link_button_label'); ?></h3><?php
                    echo $refurl;
                    $maincontent = ob_get_clean();
                    return $maincontent;
                } else {
                    $message = get_option('rs_msg_for_restricted_user');
                    if (get_option('rs_display_msg_when_access_is_prevented') === '1') {
                        echo '<br>' . $message;
                    }
                }
            } else {
                ob_start();
                $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
                $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
                $linkforlogin = add_query_arg('redirect_to', get_permalink(), $myaccountlink);
                $message = get_option('rs_message_shortcode_guest_display');
                $login = get_option('rs_message_shortcode_login_name');
                echo '<br>' . $message . ' <a href=' . $linkforlogin . '> ' . $login . '</a>';
                $maincontent = ob_get_clean();
                return $maincontent;
            }
        }

    }

    RSFunctionForReferAFriend::init();
}