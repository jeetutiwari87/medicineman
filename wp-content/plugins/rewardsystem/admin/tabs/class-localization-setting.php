<?php

/*
 * Localization Setting Tab
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSLocalization')) {

    class RSLocalization {

        public static function init() {

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_localization', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_localization', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system

            if (class_exists('bbPress')) {
                add_filter('woocommerce_rewardsystem_localization_settings', array(__CLASS__, 'add_message_for_create_topic'));
            }

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'));
            
            add_action('fp_action_to_reset_settings_rewardsystem_localization', array(__CLASS__, 'rs_function_to_reset_localization_tab'));
        }

        public static function add_message_for_create_topic($settings) {
            $updated_settings = array();

            foreach ($settings as $section) {
                $updated_settings[] = $section;
                if (isset($section['id']) && '_rs_referral_log_localization_settings' == $section['id'] &&
                        isset($section['type']) && 'sectionend' == $section['type']) {
                    $updated_settings[] = array(
                        'name' => __('Reward Points Log Create or Replied Topic', 'rewardsystem'),
                        'type' => 'title',
                        'id' => '_rs_reward_points_log_for_topic',
                    );
                    $updated_settings[] = array(
                        'name' => __('Create Topic Reward Points Log', 'rewardsystem'),
                        'id' => '_rs_localize_reward_points_for_create_topic',
                        'type' => 'textarea',
                        'std' => 'Points Earned for Create Topic',
                        'default' => 'Points Earned for Create Topic',
                        'newids' => '_rs_localize_reward_points_for_create_topic',
                    );

                    $updated_settings[] = array(
                        'name' => __('Replied Topic Reward Points Log', 'rewardsystem'),
                        'id' => '_rs_localize_reward_points_for_replied_topic',
                        'type' => 'textarea',
                        'std' => 'Points Earned for Replied Topic',
                        'newids' => '_rs_localize_reward_points_for_replied_topic',
                    );
                    $updated_settings[] = array(
                        'type' => 'sectionend',
                        'id' => '_rs_reward_points_log_for_topic'
                    );
                }
            }
            return $updated_settings;
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_localization'] = __('Localization', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;

            return apply_filters('woocommerce_rewardsystem_localization_settings', array(
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Registration Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_log_registration_reward_points',
                ),
                array(
                    'name' => __('Registration Reward Points Log', 'rewardsystem'),
                    'id' => '_rs_localize_points_earned_for_registration',
                    'type' => 'textarea',
                    'std' => 'Points Earned for Registration',
                    'default' => 'Points Earned for Registration',
                    'newids' => '_rs_localize_points_earned_for_registration',
                ),
                array(
                    'name' => __('Referral Registration Reward Points Log', 'rewardsystem'),
                    'id' => '_rs_localize_points_earned_for_referral_registration',
                    'type' => 'textarea',
                    'std' => 'Points Earned for Referral Registration by {registereduser}',
                    'default' => 'Points Earned for Referral Registration by {registereduser}',
                    'newids' => '_rs_localize_points_earned_for_referral_registration',
                ),
                array('type' => 'sectionend', 'id' => '_rs_log_registration_reward_points'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Purchase Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_product_purchase_log_localization_settings',
                ),
                array(
                    'name' => __('Product Purchase Log displayed in MasterLog - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_product_purchase_reward_points',
                    'type' => 'textarea',
                    'std' => 'Points Earned for Purchasing the Product #{itemproductid} with Order {currentorderid}',
                    'default' => 'Points Earned for Purchasing the Product #{itemproductid} with Order {currentorderid}',
                    'newids' => '_rs_localize_product_purchase_reward_points',
                ),
                array(
                    'name' => __('Product Purchase Log displayed in My Reward Table - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_points_earned_for_purchase_main',
                    'type' => 'textarea',
                    'std' => 'Points Earned for Purchasing the Product of Order {currentorderid}',
                    'default' => 'Points Earned for Purchasing the Product of Order {currentorderid}',
                    'newids' => '_rs_localize_points_earned_for_purchase_main',
                ),
                array('type' => 'sectionend', 'id' => '_rs_product_purchase_log_localization_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referral Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_referral_log_localization_settings',
                ),                
                array(
                    'name' => __('Referral Product Purchase Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_referral_reward_points_for_purchase',
                    'type' => 'textarea',
                    'std' => 'Referral Reward Points earned for Purchase {itemproductid} by {purchasedusername}',
                    'default' => 'Referral Reward Points earned for Purchase {itemproductid} by {purchasedusername}',
                    'newids' => '_rs_localize_referral_reward_points_for_purchase',
                ),
                array(
                    'name' => __('Getting Referred Log for Product Purchase - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_referral_reward_points_for_purchase_gettin_referred',
                    'type' => 'textarea',
                    'std' => 'Getting Referred Reward Points earned for Purchase {itemproductid}',
                    'default' => 'Getting Referred Reward Points earned for Purchase {itemproductid}',
                    'newids' => '_rs_localize_referral_reward_points_for_purchase_gettin_referred',
                ),
                array(
                    'name' => __('Getting Referred Log for Registration', 'rewardsystem'),
                    'id' => '_rs_localize_referral_reward_points_gettin_referred',
                    'type' => 'textarea',
                    'std' => 'Points for Getting Referred',
                    'default' => 'Points for Getting Referred',
                    'newids' => '_rs_localize_referral_reward_points_gettin_referred',
                ),
                array('type' => 'sectionend', 'id' => '_rs_referral_log_localization_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Redeemed Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_product_redeeming_settings',
                ),
                array(
                    'name' => __('Points Redeemed Log - Deducted from Account', 'rewardsystem'),
                    'id' => '_rs_localize_points_redeemed_towards_purchase',
                    'type' => 'textarea',
                    'std' => 'Points Redeemed Towards Purchase for Order {currentorderid}',
                    'default' => 'Points Redeemed Towards Purchase for Order {currentorderid}',
                    'newids' => '_rs_localize_points_redeemed_towards_purchase',
                ),
                array('type' => 'sectionend', 'id' => '_rs_product_redeeming_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points for Payment Gateway Usage Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_payment_gateway_reward_points',
                ),
                array(
                    'name' => __('Payment Gateway Reward Points Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_payment_gateway_message',
                    'type' => 'textarea',
                    'std' => 'Reward Points for Using Payment Gateway {payment_title}',
                    'default' => 'Reward Points for Using Payment Gateway {payment_title}',
                    'newids' => '_rs_localize_reward_for_payment_gateway_message',
                ),
                array(
                    'name' => __('Payment Gateway Reward Points Log - Revoked', 'rewardsystem'),
                    'id' => '_rs_localize_revise_reward_for_payment_gateway_message',
                    'type' => 'textarea',
                    'std' => 'Revised Reward Points for Using Payment Gateway {payment_title}',
                    'default' => 'Revised Reward Points for Using Payment Gateway {payment_title}',
                    'newids' => '_rs_localize_revise_reward_for_payment_gateway_message',
                ),
                array('type' => 'sectionend', 'id' => '_rs_payment_gateway_reward_points'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('SUMO Reward Points Payment Gateway Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_points_gateway_localization',
                ),
                array(
                    'name' => __('SUMO Reward Points Payment Gateway Redeemed Log', 'rewardsystem'),
                    'id' => '_rs_reward_points_gateway_log_localizaation',
                    'type' => 'textarea',
                    'std' => 'Points Redeemed for using Reward Points Gateway {currentorderid}',
                    'default' => 'Points Redeemed for using Reward Points Gateway {currentorderid}',
                    'newids' => '_rs_reward_points_gateway_log_localizaation',
                ),
                array(
                    'name' => __('Subscription Product Auto Renewal Log', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_using_subscription',
                    'type' => 'textarea',
                    'std' => 'Points Redeemed For Renewal Of Subscription {subscription_id}',
                    'default' => 'Points Redeemed For Renewal Of Subscription {subscription_id}',
                    'newids' => '_rs_localize_reward_for_using_subscription',
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_points_gateway_localization'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Social Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_localize_social_reward_points',
                ),
                array(
                    'name' => __('Facebook Like Reward Points Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_facebook_like',
                    'type' => 'textarea',
                    'std' => 'Reward for Social Facebook Like',
                    'default' => 'Reward for Social Facebook Like',
                    'newids' => '_rs_localize_reward_for_facebook_like',
                ),
                array(
                    'name' => __('Facebook Share Reward Points Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_facebook_share',
                    'type' => 'textarea',
                    'std' => 'Reward for Social Facebook Share',
                    'default' => 'Reward for Social Facebook Share',
                    'newids' => '_rs_localize_reward_for_facebook_share',
                ),
                array(
                    'name' => __('Twitter Tweet Reward Points Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_twitter_tweet',
                    'type' => 'textarea',
                    'std' => 'Reward for Social Twitter Tweet',
                    'default' => 'Reward for Social Twitter Tweet',
                    'newids' => '_rs_localize_reward_for_twitter_tweet',
                ),
                array(
                    'name' => __('Twitter Follow Reward Points Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_twitter_follow',
                    'type' => 'textarea',
                    'std' => 'Reward for Social Twitter Follow',
                    'default' => 'Reward for Social Twitter Follow',
                    'newids' => '_rs_localize_reward_for_twitter_follow',
                ),
                array(
                    'name' => __('Google Plus Reward Points Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_google_plus',
                    'type' => 'textarea',
                    'std' => 'Reward for Social Google Plus',
                    'default' => 'Reward for Social Google Plus',
                    'newids' => '_rs_localize_reward_for_google_plus',
                ),
                array(
                    'name' => __('VK.Com Like Reward Points Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_vk',
                    'type' => 'textarea',
                    'std' => 'Reward for Social VK.Com Like',
                    'default' => 'Reward for Social VK.Com Like',
                    'newids' => '_rs_localize_reward_for_vk',
                ),
                array(
                    'name' => __('Instagram Follow Reward Points Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_instagram',
                    'type' => 'textarea',
                    'std' => 'Reward for Social Instagram Follow',
                    'default' => 'Reward for Social Instagram Follow',
                    'newids' => '_rs_localize_reward_for_instagram',
                ),
                  array(
                    'name' => __('OK.ru Share Reward Points Log - Earned', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_ok_follow',
                    'type' => 'textarea',
                    'std' => 'Reward for Social OK.ru Share',
                    'default' => 'Reward for Social OK.ru Share',
                    'newids' => '_rs_localize_reward_for_ok_follow',
                ),
                array('type' => 'sectionend', 'id' => '_rs_localize_social_reward_points'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Review Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_review_localize_settings',
                ),
                array(
                    'name' => __('Product Review Reward Points Log', 'rewardsystem'),
                    'id' => '_rs_localize_points_earned_for_product_review',
                    'type' => 'textarea',
                    'std' => 'Reward for Reviewing a Product {reviewproductid}',
                    'default' => 'Reward for Reviewing a Product {reviewproductid}',
                    'newids' => '_rs_localize_points_earned_for_product_review',
                ),
                array('type' => 'sectionend', 'id' => '_rs_review_localize_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Blog Post Creation Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_blogposts_localize_settings',
                ),
                array(
                    'name' => __('Blog Post Creation Reward Points Log', 'rewardsystem'),
                    'id' => '_rs_localize_points_earned_for_post',
                    'type' => 'textarea',
                    'std' => 'Reward for Posting {postid}',
                    'default' => 'Reward for Posting {postid}',
                    'newids' => '_rs_localize_points_earned_for_post',
                ),
                array('type' => 'sectionend', 'id' => '_rs_blogposts_localize_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Blog Post Comment Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_post_review_localize_settings',
                ),
                array(
                    'name' => __('Blog Post Comment Reward Points Log Settings', 'rewardsystem'),
                    'id' => '_rs_localize_points_earned_for_post_review',
                    'type' => 'textarea',
                    'std' => 'Reward for Commenting a Post {postid}',
                    'default' => 'Reward for Commenting a Post {postid}',
                    'newids' => '_rs_localize_points_earned_for_post_review',
                ),
                array('type' => 'sectionend', 'id' => '_rs_post_review_localize_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Creation Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_product_localize_settings',
                ),
                array(
                    'name' => __('Product Creation Reward Points Log', 'rewardsystem'),
                    'id' => '_rs_localize_points_earned_for_product_creation',
                    'type' => 'textarea',
                    'std' => 'Reward Points for Creating a Product {ProductName}',
                    'default' => 'Reward Points for Creating a Product {ProductName}',
                    'newids' => '_rs_localize_points_earned_for_product_creation',
                ),
                array('type' => 'sectionend', 'id' => '_rs_product_localize_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Page Comment Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_page_review_localize_settings',
                ),
                array(
                    'name' => __('Page Comment Reward Points Log', 'rewardsystem'),
                    'id' => '_rs_localize_points_earned_for_page_review',
                    'type' => 'textarea',
                    'std' => 'Reward for Commenting a Page {pagename}',
                    'default' => 'Reward for Commenting a Page {pagename}',
                    'newids' => '_rs_localize_points_earned_for_page_review',
                ),
                array('type' => 'sectionend', 'id' => '_rs_page_review_localize_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Daily Login Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_reward_points_log_for_login_settings',
                ),
                array(
                    'name' => __('Daily Login Reward Points Log', 'rewardsystem'),
                    'id' => '_rs_localize_reward_points_for_login',
                    'type' => 'textarea',
                    'std' => 'Points Earned for today login',
                    'default' => 'Points Earned for today login',
                    'newids' => '_rs_localize_reward_points_for_login',
                ),
                array('type' => 'sectionend', 'id' => '_rs_reward_points_log_for_login_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Buying Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_buying_reward_points_localization',
                ),
                array(
                    'name' => __('Buying Reward Points Log', 'rewardsystem'),
                    'id' => '_rs_localize_buying_reward_points_log',
                    'type' => 'textarea',
                    'std' => 'Bought Reward Points  {currentorderid}',
                    'default' => 'Bought Reward Points  {currentorderid}',
                    'newids' => '_rs_localize_buying_reward_points_log',
                ),
                array('type' => 'sectionend', 'id' => '_rs_buying_reward_points_localization'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Product Purchase Reward Points Revised Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_revise_purchase_log_settings',
                ),
                array(
                    'name' => __('Product Purchase Log displayed in MasterLog - Revoked', 'rewardsystem'),
                    'id' => '_rs_log_revise_product_purchase',
                    'type' => 'textarea',
                    'std' => 'Revised Product Purchase {productid}',
                    'default' => 'Revised Product Purchase {productid}',
                    'newids' => '_rs_log_revise_product_purchase',
                ),
                array(
                    'name' => __('Product Purchase Log displayed in My Reward Table - Revoked', 'rewardsystem'),
                    'id' => '_rs_log_revise_product_purchase_main',
                    'type' => 'textarea',
                    'std' => 'Revised Product Purchase {currentorderid}',
                    'default' => 'Revised Product Purchase {currentorderid}',
                    'newids' => '_rs_log_revise_product_purchase_main',
                ),
                array('type' => 'sectionend', 'id' => '_rs_revise_purchase_log_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referral Product Purchase Reward Points Revised Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_revise_referral_purchase_log_settings',
                ),
                array(
                    'name' => __('Referral Product Purchase Log - Revoked', 'rewardsystem'),
                    'id' => '_rs_log_revise_referral_product_purchase',
                    'type' => 'textarea',
                    'std' => 'Revised Referral Product Purchase {productid}',
                    'default' => 'Revised Referral Product Purchase {productid}',
                    'newids' => '_rs_log_revise_referral_product_purchase',
                ),
                array('type' => 'sectionend', 'id' => '_rs_revise_referral_purchase_log_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Redeemed Reward Points Revised Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_revise_product_redeeming_settings',
                ),
                array(
                    'name' => __('Points Redeemed Log - Added to Account', 'rewardsystem'),
                    'id' => '_rs_log_revise_points_redeemed_towards_purchase',
                    'type' => 'textarea',
                    'std' => 'Revise Points Redeemed Towards Purchase {currentorderid}',
                    'default' => 'Revise Points Redeemed Towards Purchase {currentorderid}',
                    'newids' => '_rs_log_revise_points_redeemed_towards_purchase',
                ),
                array('type' => 'sectionend', 'id' => '_rs_revise_product_redeeming_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Getting Referred Reward Points for Product Purchase Revised Log settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_revise_getting_referred_log_settings',
                ),
                array(
                    'name' => __('Getting Referred Log for Product Purchase - Revoked', 'rewardsystem'),
                    'id' => '_rs_log_revise_getting_referred_product_purchase',
                    'type' => 'textarea',
                    'std' => 'Revised Getting Referred Product Purchase {productid}',
                    'default' => 'Revised Getting Referred Product Purchase {productid}',
                    'newids' => '_rs_log_revise_getting_referred_product_purchase',
                ),
                array('type' => 'sectionend', 'id' => '_rs_revise_getting_referred_log_settings'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Referral Registration Points Revised upon Account Deletion Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_localize_revise_points_for_deleted_user',
                ),
                array(
                    'name' => __('Referral Account Sign up Log - Revoked on User Deletion', 'rewardsystem'),
                    'id' => '_rs_localize_referral_account_signup_points_revised',
                    'type' => 'textarea',
                    'std' => 'Referral Account Sign up Points Revised with Referred User Deleted {usernickname}',
                    'default' => 'Referral Account Sign up Points Revised with Referred User Deleted {usernickname}',
                    'newids' => '_rs_localize_referral_account_signup_points_revised',
                ),
                array(
                    'name' => __('Referral Product Purchase Log - Revoked on User Deletion', 'rewardsystem'),
                    'id' => '_rs_localize_revise_points_for_referral_purchase',
                    'type' => 'textarea',
                    'std' => 'Revised Referral Reward Points earned for Purchase {productid} by deleted user {usernickname}',
                    'default' => 'Revised Referral Reward Points earned for Purchase {productid} by deleted user {usernickname}',
                    'newids' => '_rs_localize_revise_points_for_referral_purchase',
                ),
                array(
                    'name' => __('Getting Referred Log for Product Purchase - Revoked on User Deletion', 'rewardsystem'),
                    'id' => '_rs_localize_revise_points_for_getting_referred_purchase',
                    'type' => 'textarea',
                    'std' => 'Revised Getting Referred Reward Points earned for Purchase {productid} by deleted user {usernickname}',
                    'default' => 'Revised Getting Referred Reward Points earned for Purchase {productid} by deleted user {usernickname}',
                    'newids' => '_rs_localize_revise_points_for_getting_referred_purchase',
                ),
                array('type' => 'sectionend', 'id' => '_rs_localize_revise_points_for_deleted_user'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Social Reward Points Revised Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_localize_social_redeeming',
                ),
                array(
                    'name' => __('Facebook Like Reward Points Log - Revoked', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_facebook_like_revised',
                    'type' => 'textarea',
                    'std' => 'Reward for Social Facebook Like is Revised',
                    'default' => 'Reward for Social Facebook Like is Revised',
                    'newids' => '_rs_localize_reward_for_facebook_like_revised',
                ),
                array(
                    'name' => __('Google Plus Reward Points Log - Revoked', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_google_plus_revised',
                    'type' => 'textarea',
                    'std' => 'Reward for Social Google Plus is Revised',
                    'default' => 'Reward for Social Google Plus is Revised',
                    'newids' => '_rs_localize_reward_for_google_plus_revised',
                ),
                array(
                    'name' => __('VK.Com Like Reward Points Log - Revoked', 'rewardsystem'),
                    'id' => '_rs_localize_reward_for_vk_like_revised',
                    'type' => 'textarea',
                    'std' => 'Reward for Social VK.Com Like is Revised',
                    'default' => 'Reward for Social VK.Com Like is Revised',
                    'newids' => '_rs_localize_reward_for_vk_like_revised',
                ),
                array('type' => 'sectionend', 'id' => '_rs_localize_social_redeeming'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Send Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_log_for_sendpoints',
                ),
                array(
                    'name' => __('Points Received through Send Points Log - Receiver', 'rewardsystem'),
                    'id' => '_rs_localize_log_for_reciver',
                    'type' => 'textarea',
                    'std' => '[name] Received [points] Points from [user]',
                    'default' => '[name] Received [points] Points from [user]',
                    'newids' => '_rs_localize_log_for_reciver',
                ),
                array(
                    'name' => __('Send Points Request Approved Log - Sender', 'rewardsystem'),
                    'id' => '_rs_localize_log_for_sender',
                    'type' => 'textarea',
                    'std' => '[name] [points] Points has been Approved by Admin Successfully and Sent to [user]',
                    'default' => '[name] [points] Points has been Approved by Admin Successfully and Sent to [user]',
                    'newids' => '_rs_localize_log_for_sender',
                ),
                array(
                    'name' => __('Send Points Request Submitted Log - Sender', 'rewardsystem'),
                    'id' => '_rs_localize_log_for_sender_after_submit',
                    'type' => 'textarea',
                    'std' => 'Your request to Send Points is Submitted Successfully and waiting for Admin Approval.',
                    'default' => 'Your request to Send Points is Submitted Successfully and waiting for Admin Approval.',
                    'newids' => '_rs_localize_log_for_sender_after_submit',
                ),
                array(
                    'name' => __('Send Points Request Rejected Log', 'rewardsystem'),
                    'id' => '_rs_localize_points_to_send_log_revised',
                    'type' => 'textarea',
                    'std' => 'Admin has been Rejected Your Request to Send Points.So Your Requested Points to Send were revised to your account',
                    'default' => 'Admin has been Rejected Your Request to Send Points.So Your Requested Points to Send were revised to your account',
                    'newids' => '_rs_localize_points_to_send_log_revised',
                ),
                array('type' => 'sectionend', 'id' => 'rs_log_for_sendpoints'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Voucher Code Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_voucher_code_log_localization',
                ),
                array(
                    'name' => __('Voucher Code Redeemed Log', 'rewardsystem'),
                    'id' => '_rs_localize_voucher_code_usage_log_message',
                    'type' => 'textarea',
                    'std' => 'Redeem Voucher Code {rsusedvouchercode}',
                    'default' => 'Redeem Voucher Code {rsusedvouchercode}',
                    'newids' => '_rs_localize_voucher_code_usage_log_message',
                ),
                array('type' => 'sectionend', 'id' => '_rs_voucher_code_log_localization'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Coupon Reward Points Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_coupon_reward_points_localization',
                ),
                array(
                    'name' => __('Reward Points for Coupon Usage Log', 'rewardsystem'),
                    'id' => '_rs_localize_coupon_reward_points_log',
                    'type' => 'textarea',
                    'std' => 'Points Earned for using Coupons',
                    'default' => 'Points Earned for using Coupons',
                    'newids' => '_rs_localize_coupon_reward_points_log',
                ),
                array('type' => 'sectionend', 'id' => '_rs_coupon_reward_points_localization'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Reward Points Earning Threshold Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_log_for_max_earning',
                ),
                array(
                    'name' => __('Maximum Threshold for Total Points Log', 'rewardsystem'),
                    'id' => '_rs_localize_max_earning_points_log',
                    'type' => 'textarea',
                    'std' => 'You Cannot Earn More than [rsmaxpoints]',
                    'default' => 'You Cannot Earn More than [rsmaxpoints]',
                    'newids' => '_rs_localize_max_earning_points_log',
                ),
                array('type' => 'sectionend', 'id' => 'rs_log_for_max_earning'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Cashback Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_log_for_points_to_cash',
                ),
                array(
                    'name' => __('Cashback Request Log displayed in My Reward Table - Submitted', 'rewardsystem'),
                    'id' => '_rs_localize_points_to_cash_log',
                    'type' => 'textarea',
                    'std' => 'Points Requested For Cashback',
                    'default' => 'Points Requested For Cashback',
                    'newids' => '_rs_localize_points_to_cash_log',
                ),
                array(
                    'name' => __('Cashback Request Log displayed in My Reward Table - Cancelled', 'rewardsystem'),
                    'id' => '_rs_localize_points_to_cash_log_revised',
                    'type' => 'textarea',
                    'std' => 'Admin has been Cancelled your Request For Cashback.So Your Requested Cashback Points were revised to your account',
                    'default' => 'Admin has been Cancelled your Request For Cashback.So Your Requested Cashback Points were revised to your account',
                    'newids' => '_rs_localize_points_to_cash_log_revised',
                ),
                array(
                    'name' => __('Cashback Request Log displayed in My Cashback Table - Submitted', 'rewardsystem'),
                    'id' => '_rs_localize_points_to_cash_log_in_my_cashback_table',
                    'type' => 'textarea',
                    'std' => 'You have Requested [pointstocashback] points for Cashback ([cashbackamount])',
                    'default' => 'You have Requested [pointstocashback] points for Cashback ([cashbackamount])',
                    'newids' => '_rs_localize_points_to_cash_log_in_my_cashback_table',
                ),
                array('type' => 'sectionend', 'id' => 'rs_log_for_points_to_cash'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Nominee Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_log_for_nominee',
                ),
                array(
                    'name' => __('Nominated Product Purchase Reward Points Log - Receiver', 'rewardsystem'),
                    'id' => '_rs_localize_log_for_nominee',
                    'type' => 'textarea',
                    'std' => '[name] Received [points] Points from [user]',
                    'default' => '[name] Received [points] Points from [user]',
                    'newids' => '_rs_localize_log_for_nominee',
                ),
                array(
                    'name' => __('Nominated Product Purchase Reward Points Log - Sender', 'rewardsystem'),
                    'id' => '_rs_localize_log_for_nominated_user',
                    'type' => 'textarea',
                    'std' => '[name] [points] Points has been nominated to [user]',
                    'default' => '[name] [points] Points has been nominated to [user]',
                    'newids' => '_rs_localize_log_for_nominated_user',
                ),
                array('type' => 'sectionend', 'id' => 'rs_log_for_nominee'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Import/Export Log Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_log_for_import_export',
                ),
                array(
                    'name' => __('Points Imported Log - Added to Existing Points', 'rewardsystem'),
                    'id' => '_rs_localize_log_for_import_add',
                    'type' => 'textarea',
                    'std' => '[points] Points were added with existing points by importing',
                    'default' => '[points] Points were added with existing points by importing',
                    'newids' => '_rs_localize_log_for_import_add',
                ),
                array(
                    'name' => __('Points Imported Log - Override Existing Points', 'rewardsystem'),
                    'id' => '_rs_localize_log_for_import_override',                    
                    'type' => 'textarea',
                    'std' => '[points] Points were overrided by importing',
                    'default' => '[points] Points were overrided by importing',
                    'newids' => '_rs_localize_log_for_import_override',
                ),
                array('type' => 'sectionend', 'id' => 'rs_log_for_import_export'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Shortcode used in Localization', 'rewardsystem'),
                    'type' => 'title',
                    'id' => 'rs_shortcode_for_localization'
                ),
                array(
                    'type' => 'title',
                    'desc' => '<b>{productname},{ProductName}</b> - To display product name in log<br><br>'
                    . '<b>{itemproductid}, {productid}, {reviewproductid}, {postid}, {rsbuyiedrewardpoints}</b> - To display product id n log<br><br>'
                    . '<b>{purchasedusername}</b> - To display purchased username in log<br><br>'
                    . '<b>{currentorderid}</b> - To display order id in log<br><br>'
                    . '<b>{registereduser}, {usernickname}</b> - To display username in log<br><br>'
                    . '<b>[name]</b> - To display receiver name in send points and nominee log<br><br>'
                    . '<b>[points]</b> - To display points received  in send points and nominee log<br><br>'
                    . '<b>[user]</b> - To display sender name in send points and nominee log<br><br>'                    
                    . '<b>{pagename}</b> - To display commented page name<br><br>'
                    . '<b>{payment_title}</b> - To display payment gateway name<br><br>'
                    . '<b>{subscription_id}</b> - To display subscription id in points redeemed in subscription renewal log<br><br>'
                    . '<b>{rsusedvouchercode}</b> - To display voucher code<br><br>'
                    . '<b>[rsmaxpoints]</b> - To display maximum threshold value for points<br><br>'
                    . '<b>[pointstocashback]</b> - To display points requested for cashback<br><br>'
                    . '<b>[cashbackamount]</b> - To display equivalent amount for requested cashback points'
                ),
                array('type' => 'sectionend', 'id' => 'rs_shortcode_for_localization'),
                array(
                    'type' => 'rs_wrapper_end',
                ),                
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {

            woocommerce_admin_fields(RSLocalization::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSLocalization::reward_system_admin_fields());
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSLocalization::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }
        
        public static function rs_function_to_reset_localization_tab() {
            $settings = RSLocalization::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);            
        }

    }

    RSLocalization::init();
}