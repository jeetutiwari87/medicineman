<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSBuyingRewardPoints')) {

    class RSBuyingRewardPoints {        

        public static function init() {
            add_action('woocommerce_process_product_meta', array(__CLASS__, 'save_admin_field_buying_reward_points'));
            add_action('admin_head', array(__CLASS__, 'rs_show_hide_buy_reward_points'));
            add_action('woocommerce_product_options_advanced', array(__CLASS__, 'rs_admin_field_buying_reward_points'));
        }

        /* Add Admin Field Buying Reward Points */

        public static function rs_admin_field_buying_reward_points() {
            global $post;
            if (is_admin()) {
                woocommerce_wp_select(array(
                    'id' => '_rewardsystem_buying_reward_points',
                    'class' => '_rewardsystem_buying_reward_points',
                    'label' => __('Enable Buying of SUMO Reward Points', 'rewardsystem'),
                    'options' => array(
                        'no' => __('Disable', 'rewardsystem'),
                        'yes' => __('Enable', 'rewardsystem'),
                    )
                ));
                woocommerce_wp_text_input(
                        array(
                            'id' => '_rewardsystem_assign_buying_points',
                            'class' => 'show_if_buy_reward_points_enable',
                            'name' => '_rewardsystem_assign_buying_points',
                            'label' => __('Buy Reward Points', 'rewardsystem')
                ));
            }
        }

        /* Save Admin Field for Buying Reward Points */

        public static function save_admin_field_buying_reward_points($post_id) {
            $woocommerce_buying_reward_select = $_POST['_rewardsystem_buying_reward_points'];
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem_buying_reward_points', $woocommerce_buying_reward_select);
            $woocommerce_rewardpoints = $_POST['_rewardsystem_assign_buying_points'];
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem_assign_buying_points', $woocommerce_rewardpoints);
        }

        public static function rs_show_hide_buy_reward_points() {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#product-type').change(function () {
                        if (jQuery(this).val() == 'variable') {
                            jQuery('#_rewardsystem_enable_point_price').parent().hide();
                            jQuery('#_rewardsystem_enable_point_price_type').parent().hide();
                        } else {
                            jQuery('#_rewardsystem_enable_point_price').parent().show();
                            jQuery('#_rewardsystem_enable_point_price_type').parent().show();
                        }
                    });

                    jQuery('#publish').click(function (e) {
                        if (jQuery('._rewardsystem_enable_point_price_type').val() == '2') {
                            if (jQuery('#_rewardsystem__points').val() == '') {
                                jQuery('#_rewardsystem__points').css({
                                    "border": "1px solid red",
                                    "background": "#FFCECE"
                                });
                                // jQuery("#_rewardsystem__points").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter points</div>");
                                if (jQuery('#_rewardsystem__points').parent().find('.wc_error_tip').size() == 0) {
                                    var offset = jQuery('#_rewardsystem__points').position();
                                    jQuery('#_rewardsystem__points').after('<div class="wc_error_tip">' + " Please Enter Points" + '</div>');
                                    jQuery('.wc_error_tip')
                                            .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                            .css('top', offset.top + jQuery(this).height())
                                            .fadeIn('100000000');
                                }

                                e.preventDefault();
                            }

                        }
                    });
                });
            </script>
            <?php

        }

       

    }

    RSBuyingRewardPoints::init();
}