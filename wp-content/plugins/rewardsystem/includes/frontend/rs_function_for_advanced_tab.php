<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('RSFunctionForAdvanced')) {

    class RSFunctionForAdvanced {

        public static function init() {
            if (get_option('rs_load_script_styles') == 'wp_head') {
                add_action('wp_head', array(__CLASS__, 'rs_load_script_from_header_or_footer'));
            } else {
                add_action('wp_footer', array(__CLASS__, 'rs_load_script_from_header_or_footer'));
            }
        }

        public static function rs_load_script_from_header_or_footer() {
            //My Account 
            if (is_account_page()) {
                RSFunctionForEmailTemplate::get_the_checkboxvalue_from_myaccount_page();

                RSFunctionForNominee::rs_chosen_for_nominee_in_my_account_tab();
            }
            //My Account and Page
            if (is_account_page() || is_page()) {
                RSFunctionForMyAccount::add_script_to_my_account();
            }

            //My Account and Checkout
            if (is_account_page() || is_checkout()) {
                if (get_option('rs_show_hide_nominee_field') == '1') {
                    RSFunctionForNominee::ajax_for_saving_nominee();
                }
            }

            //Cart Check out is_cart() is_checkout()
            if (is_cart() || is_checkout()) {
                RSFunctionForCart::validation_in_my_cart();

                RSFunctionForCart::test_coupon();
            }

            if (is_product()) {
                RSFunctionForSocialRewards::add_fb_style_hide_comment_box();
            }else{
                RSFunctionForSocialRewards::add_fb_style_hide_comment_box_post();
            }

            if (get_option('rs_product_purchase_activated') == 'yes') {
                RSFUnctinforVariableProduct::display_purchase_msg_for_variable_product();
            }

            RSBookingCompatibility::booking_compatible();
            RSFunctionForReferAFriend::rs_script_to_generate_referral_link();
            RSFunctionForGiftVoucher::rs_script_to_redeem_voucher();
        }

    }

    RSFunctionForAdvanced::init();
}