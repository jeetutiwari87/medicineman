<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSFunctionToApplyCoupon')) {

    class RSFunctionToApplyCoupon {

        public static function init() {
            add_action('wp_head', array(__CLASS__, 'apply_matched_coupons'));

            add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'remove_coupon_after_place_order'), 10, 2);

            add_filter('woocommerce_coupon_message', array(__CLASS__, 'get_coupon_code_data_sucess'), 10, 2);

            add_filter('woocommerce_coupon_error', array(__CLASS__, 'get_coupon_code_data'), 10, 3);

            add_filter('woocommerce_coupon_error', array(__CLASS__, 'rs_error_msg_for_autoredeem'), 10, 3);

            add_filter('woocommerce_coupons_enabled', array(__CLASS__, 'hide_coupon_field_on_checkout'));

            add_filter('woocommerce_checkout_coupon_message', array(__CLASS__, 'hide_coupon_message'));
        }

        public static function rs_common_function_to_return_msg($message, $hidemsg) {
            if (isset($_POST['rs_apply_coupon_code']) || isset($_POST['rs_apply_coupon_code1']) || isset($_POST['rs_apply_coupon_code2'])) {
                $point_to_redeem_non_zero = $_POST['rs_apply_coupon_code_field'];
                if ($point_to_redeem_non_zero != '0' || '') {
                    if ($hidemsg == 'no') {
                        if (get_option('woocommerce_enable_coupons') == 'yes') {
                            return true;
                        }
                    }
                    if ($hidemsg == 'yes') {
                        if (get_option('rs_show_hide_coupon_field_checkout') == '2') {
                            $message = '';
                            return $message;
                        }
                    }
                }
            }
            return $message;
        }

        public static function hide_coupon_message($message) {
            if (is_checkout()) {
                $hidemsg = 'yes';
                $message = self::rs_common_function_to_return_msg($message, $hidemsg);
            }
            return $message;
        }

        public static function hide_coupon_field_on_checkout($enabled) {
            if (is_checkout()) {
                $hidemsg = 'no';
                if (get_option('rs_show_hide_coupon_field_checkout') == '2') {
                    $enabled = false;
                }
                $enabled = self::rs_common_function_to_return_msg($enabled, $hidemsg);
            }
            return $enabled;
        }

        public static function apply_matched_coupons() {                        
            global $woocommerce;
            $point_to_redeem = '1';
            $user_ID = get_current_user_id();
            if (isset($_POST['apply_coupon'])) {
                $coupon_code = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_ID, 'nickname', true); // Code
            }
            if (isset($_POST['rs_apply_coupon_code']) || isset($_POST['rs_apply_coupon_code1']) || isset($_POST['rs_apply_coupon_code2'])) {
                if (isset($_POST['rs_apply_coupon_code_field'])) {
                    $point_to_redeem_non_zero = $_POST['rs_apply_coupon_code_field'];
                    if ($point_to_redeem_non_zero != '0' || '') {
                        $getinfousernickname = get_user_by('id', $user_ID);
                        $couponcodeuserlogin = is_object($getinfousernickname) ? $getinfousernickname->user_login : 'Guest';
                        $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                        if (!is_array(get_option('rs_select_products_to_enable_redeeming'))) {
                            $allowproducts = explode(',', get_option('rs_select_products_to_enable_redeeming'));
                        } else {
                            $allowproducts = get_option('rs_select_products_to_enable_redeeming');
                        }

                        if (!is_array(get_option('rs_exclude_products_to_enable_redeeming'))) {
                            $excludeproducts = explode(',', get_option('rs_exclude_products_to_enable_redeeming'));
                        } else {
                            $excludeproducts = get_option('rs_exclude_products_to_enable_redeeming');
                        }
                        $allowcategory = get_option('rs_select_category_to_enable_redeeming');
                        $excludecategory = get_option('rs_exclude_category_to_enable_redeeming');
                        $coupon = array(
                            'post_title' => 'sumo_' . strtolower($couponcodeuserlogin),
                            'post_content' => '',
                            'post_status' => 'publish',
                            'post_author' => get_current_user_id(),
                            'post_type' => 'shop_coupon',
                        );
                        $oldcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_ID, 'redeemcouponids', true);
                        wp_delete_post($oldcouponid, true);
                        $new_coupon_id = wp_insert_post($coupon);                        
                        update_user_meta($user_ID, 'redeemcouponids', $new_coupon_id);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'carttotal', $woocommerce->cart->subtotal); //subtotal_ex_tax -> subtotal due to incorrect point reedming when inclusive tax is selected in woocommerce
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'cartcontenttotal', $woocommerce->cart->cart_contents_count);
                        $discount_type = 'fixed_cart';
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'discount_type', $discount_type);
                        $getmaxruleoption = get_option('rs_max_redeem_discount');
                        $getfixedmaxoption = get_option('rs_fixed_max_redeem_discount');
                        $getpercentmaxoption = get_option('rs_percent_max_redeem_discount');
                        $errpercentagemsg = get_option('rs_errmsg_for_max_discount_type');
                        $errpercentagemsg1 = str_replace('[percentage] %', $getfixedmaxoption, $errpercentagemsg);
                        $point_control = wc_format_decimal(get_option('rs_redeem_point'));
                        $point_control_price = RSMemberFunction::user_role_based_redeem_points($user_ID);
                        if (get_option('woocommerce_prices_include_tax') == 'yes') {
                            if (get_option('woocommerce_tax_display_cart') == 'incl') {
                                $get_cart_total_for_redeem = $woocommerce->cart->subtotal;
                            } else {
                                $get_cart_total_for_redeem = $woocommerce->cart->subtotal;
                            }
                        } else {
                            if (get_option('woocommerce_tax_display_cart') == 'incl') {
                                $get_cart_total_for_redeem = $woocommerce->cart->subtotal_ex_tax;
                            } else {
                                $get_cart_total_for_redeem = $woocommerce->cart->subtotal_ex_tax;
                            }
                        }
                        if (get_option('rs_apply_redeem_basedon_cart_or_product_total') == '1') {
                            if (isset($_POST['rs_apply_coupon_code2'])) {
                                $limitation_percentage_for_redeeming_for_button = get_option('rs_percentage_cart_total_redeem_checkout');
                            }
                            if (isset($_POST['rs_apply_coupon_code1'])) {
                                $limitation_percentage_for_redeeming_for_button = get_option('rs_percentage_cart_total_redeem');
                            }
                            if (isset($_POST['rs_apply_coupon_code2']) || isset($_POST['rs_apply_coupon_code1'])) {

                                $reddem_value_in_amount_percent = $limitation_percentage_for_redeeming_for_button / 100;
                                $reddem_points_for_total = $reddem_value_in_amount_percent * $get_cart_total_for_redeem;
                                $coupon_value_in_amount = $reddem_points_for_total;
                                if ($getmaxruleoption == '1') {
                                    if ($getfixedmaxoption != '') {
                                        if ($reddem_points_for_total > $getfixedmaxoption) {
                                            $coupon_value_in_amount = $getfixedmaxoption;
                                            $errpercentagemsg1 = str_replace('[percentage] %', $getfixedmaxoption, $errpercentagemsg);
                                            wc_add_notice(__($errpercentagemsg1), 'error');
                                        } else {
                                            $coupon_value_in_amount = $reddem_points_for_total;
                                        }
                                    }
                                } else {
                                    if ($getmaxruleoption == '2') {
                                        if ($getpercentmaxoption != '') {
                                            $percentageproduct = $getpercentmaxoption / 100;
                                            $getpricepercent = $percentageproduct * $get_cart_total_for_redeem;

                                            if ($getpricepercent > $reddem_points_for_total) {
                                                $coupon_value_in_amount = $reddem_points_for_total;
                                            } else {
                                                $coupon_value_in_amount = $getpricepercent;
                                                $errpercentagemsg1 = str_replace('[percentage] ', $getpercentmaxoption, $errpercentagemsg);
                                                wc_add_notice(__($errpercentagemsg1), 'error');
                                            }
                                        }
                                    }
                                }
                            }

                            if (isset($_POST['rs_apply_coupon_code'])) {
                                $point_to_redeem = $_POST['rs_apply_coupon_code_field'];
                                $revised_amount = $point_to_redeem / $point_control;
                                $coupon_value_in_amount_field = $revised_amount * $point_control_price;
                                $coupon_value_in_amount = $coupon_value_in_amount_field;
                                if ($getmaxruleoption == '1') {
                                    if ($getfixedmaxoption != '') {
                                        if ($coupon_value_in_amount_field > $getfixedmaxoption) {
                                            $coupon_value_in_amount = $getfixedmaxoption;
                                            $errpercentagemsg1 = str_replace('[percentage] %', $getfixedmaxoption, $errpercentagemsg);
                                            wc_add_notice(__($errpercentagemsg1), 'error');
                                        } else {
                                            $coupon_value_in_amount = $coupon_value_in_amount_field;
                                        }
                                    }
                                } else {
                                    if ($getmaxruleoption == '2') {
                                        if ($getpercentmaxoption != '') {
                                            $percentageproduct = $getpercentmaxoption / 100;
                                            $getpricepercent = $percentageproduct * $get_cart_total_for_redeem;
                                            if ($getpricepercent > $coupon_value_in_amount_field) {
                                                $coupon_value_in_amount = $coupon_value_in_amount_field;
                                            } else {
                                                $coupon_value_in_amount = $getpricepercent;
                                                $errpercentagemsg1 = str_replace('[percentage] ', $getpercentmaxoption, $errpercentagemsg);
                                                wc_add_notice(__($errpercentagemsg1), 'error');
                                            }
                                        }
                                    }
                                }
                            }
                            $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                            $point_control = wc_format_decimal(get_option('rs_redeem_point'));
                            $point_control_price = RSMemberFunction::user_role_based_redeem_points($user_ID);
                            $revised_amount = $coupon_value_in_amount * $point_control;
                            $coupon_value_in_points = $revised_amount / $point_control_price;
                            $user_current_points_in_value = $user_current_points / $point_control;
                            $user_current_points1 = $user_current_points_in_value * $point_control_price;
                            if ($coupon_value_in_points > $user_current_points) {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $user_current_points1);
                                do_action('fp_apply_reward_points', $point_to_redeem_non_zero, $user_current_points1);
                            } else {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $coupon_value_in_amount);
                                do_action('fp_apply_reward_points', $point_to_redeem_non_zero, $coupon_value_in_amount);
                            }
                        } else {
                            $getsumofselectedproduct = self::get_sum_of_selected_products('sumo', '', '');

                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'carttotal', $getsumofselectedproduct);

                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'cartcontenttotal', $getsumofselectedproduct);
                            if (isset($_POST['rs_apply_coupon_code2'])) {
                                $limitation_percentage_for_redeeming_for_button = get_option('rs_percentage_cart_total_redeem_checkout');
                            }
                            if (isset($_POST['rs_apply_coupon_code1'])) {
                                $limitation_percentage_for_redeeming_for_button = get_option('rs_percentage_cart_total_redeem');
                            }
                            if (isset($_POST['rs_apply_coupon_code2']) || isset($_POST['rs_apply_coupon_code1'])) {
                                $reddem_value_in_amount_percent = $limitation_percentage_for_redeeming_for_button / 100;
                                $reddem_points_for_total = $reddem_value_in_amount_percent * $getsumofselectedproduct;
                                $coupon_value_in_amount = $reddem_points_for_total;
                                if ($reddem_points_for_total > $getsumofselectedproduct) {
                                    $reddem_points_for_total = $getsumofselectedproduct;
                                    $coupon_value_in_amount = $reddem_points_for_total;
                                }
                                if ($getmaxruleoption == '1') {
                                    if ($getfixedmaxoption != '') {
                                        if ($reddem_points_for_total > $getfixedmaxoption) {
                                            $coupon_value_in_amount = $getfixedmaxoption;
                                            $errpercentagemsg1 = str_replace('[percentage] %', $getfixedmaxoption, $errpercentagemsg);
                                            wc_add_notice(__($errpercentagemsg1), 'error');
                                        } else {
                                            $coupon_value_in_amount = $reddem_points_for_total;
                                        }
                                    }
                                } else {
                                    if ($getmaxruleoption == '2') {
                                        if ($getpercentmaxoption != '') {
                                            $percentageproduct = $getpercentmaxoption / 100;
                                            $getpricepercent = $percentageproduct * $getsumofselectedproduct;

                                            if ($getpricepercent > $reddem_points_for_total) {
                                                $coupon_value_in_amount = $reddem_points_for_total;
                                            } else {
                                                $coupon_value_in_amount = $getpricepercent;
                                                $errpercentagemsg1 = str_replace('[percentage] ', $getpercentmaxoption, $errpercentagemsg);
                                                wc_add_notice(__($errpercentagemsg1), 'error');
                                            }
                                        }
                                    }
                                }
                            }

                            if (isset($_POST['rs_apply_coupon_code'])) {
                                $point_to_redeem = $_POST['rs_apply_coupon_code_field'];
                                $revised_amount = $point_to_redeem / $point_control;
                                $coupon_value_in_amount_field = $revised_amount * $point_control_price;
                                $coupon_value_in_amount = $coupon_value_in_amount_field;
                                $getsumofselectedproduct = self::get_sum_of_selected_products('sumo', '', '');
                                if ($coupon_value_in_amount_field > $getsumofselectedproduct) {
                                    $coupon_value_in_amount_field = $getsumofselectedproduct;
                                    $coupon_value_in_amount = $coupon_value_in_amount_field;
                                }

                                if ($getmaxruleoption == '1') {
                                    if ($getfixedmaxoption != '') {
                                        if ($coupon_value_in_amount_field > $getfixedmaxoption) {
                                            $coupon_value_in_amount = $getfixedmaxoption;
                                            $errpercentagemsg1 = str_replace('[percentage] %', $getfixedmaxoption, $errpercentagemsg);
                                            wc_add_notice(__($errpercentagemsg1), 'error');
                                        } else {
                                            $coupon_value_in_amount = $coupon_value_in_amount_field;
                                        }
                                    }
                                } else {
                                    if ($getmaxruleoption == '2') {
                                        if ($getpercentmaxoption != '') {
                                            $percentageproduct = $getpercentmaxoption / 100;
                                            $getpricepercent = $percentageproduct * $getsumofselectedproduct;
                                            if ($getpricepercent > $coupon_value_in_amount_field) {
                                                $coupon_value_in_amount = $coupon_value_in_amount_field;
                                            } else {
                                                $coupon_value_in_amount = $getpricepercent;
                                                $errpercentagemsg1 = str_replace('[percentage] ', $getpercentmaxoption, $errpercentagemsg);
                                                wc_add_notice(__($errpercentagemsg1), 'error');
                                            }
                                        }
                                    }
                                }
                            }
                            $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                            $point_control = wc_format_decimal(get_option('rs_redeem_point'));
                            $point_control_price = RSMemberFunction::user_role_based_redeem_points($user_ID);
                            $revised_amount = $coupon_value_in_amount * $point_control;
                            $coupon_value_in_points = $revised_amount / $point_control_price;
                            $user_current_points_in_value = $user_current_points / $point_control;
                            $user_current_points1 = $user_current_points_in_value * $point_control_price;
                            if ($coupon_value_in_points > $user_current_points) {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $user_current_points1);
                                do_action('fp_apply_reward_points', $point_to_redeem_non_zero, $user_current_points1);
                            } else {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $coupon_value_in_amount);
                                do_action('fp_apply_reward_points', $point_to_redeem_non_zero, $coupon_value_in_amount);
                            }
                            WC()->session->set('fp_rs_redeem_amount', $getsumofselectedproduct);
                        }
                        if (get_option('rs_coupon_applied_individual') == 'yes') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'individual_use', 'yes');
                        } else {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'individual_use', 'no');
                        }

                        //Redeeming only for Selected Products option start
                        $enableproductredeeming = get_option('rs_enable_redeem_for_selected_products');
                        if ($enableproductredeeming == 'yes') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'product_ids', implode(',', array_filter(array_map('intval', $allowproducts))));
                        }
                        $excludeproductredeeming = get_option('rs_exclude_products_for_redeeming');
                        if ($excludeproductredeeming == 'yes') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'exclude_product_ids', implode(',', array_filter(array_map('intval', $excludeproducts))));
                        }
                        $enablecategoryredeeming = get_option('rs_enable_redeem_for_selected_category');
                        if ($enablecategoryredeeming == 'yes') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'product_categories', implode(',', array_filter(array_map('intval', $allowcategory))));
                        }
                        $excludecategoryredeeming = get_option('rs_exclude_category_for_redeeming');
                        if ($excludecategoryredeeming == 'yes') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'exclude_product_categories', implode(',', array_filter(array_map('intval', $excludecategory))));
                        }


                        //Redeeming only for Selected Products option End
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'usage_limit', '1');
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'expiry_date', '');
                        if (get_option('rs_apply_redeem_before_tax') == '1') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
                        } else {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'apply_before_tax', 'no');
                        }
                        if (get_option('rs_apply_shipping_tax') == '1') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'free_shipping', 'yes');
                        } else {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'free_shipping', 'no');
                        }

                        if ($woocommerce->cart->has_discount('sumo_' . strtolower($couponcodeuserlogin)))
                            return;
                        if (!$woocommerce->cart->has_discount('sumo_' . strtolower($couponcodeuserlogin))) {
                            $woocommerce->cart->add_discount('sumo_' . strtolower($couponcodeuserlogin));
                        }
                    }
                }
            }
        }

        public static function get_sum_of_selected_products($coupontype, $user_current_points1, $user_current_points) {

            global $woocommerce;

            $includeproductid = get_option('rs_select_products_to_enable_redeeming');

            if (is_array($includeproductid)) {
                $include_productid = (array) $includeproductid; // Compatible for Old WooCommerce Version
            } else {
                $include_productid = (array) explode(',', $includeproductid); // Compatible with Latest Version
            }

            $excludeproductid = get_option('rs_exclude_products_to_enable_redeeming');
            if (is_array($excludeproductid)) {
                $exclude_productid = (array) $excludeproductid; // Compatible for Old WooCommerce Version
            } else {
                $exclude_productid = (array) explode(',', $excludeproductid); // Compatible with Latest Version
            }

            $includecategory = get_option('rs_select_category_to_enable_redeeming');
            if (is_array($includecategory)) {
                $include_category = (array) $includecategory; // Compatible for Old WooCommerce Version            
            } else {
                $include_category = (array) explode(',', $includecategory); // Compatible with Latest Version            
            }

            $excludecategory = get_option('rs_exclude_category_to_enable_redeeming');
            if (is_array($excludecategory)) {
                $exclude_category = (array) $excludecategory; // Compatible for Old WooCommerce Version
            } else {
                $exclude_category = (array) explode(',', $excludecategory); // Compatible with Latest Version
            }
            $cart_contents = $woocommerce->cart->cart_contents;
            $totalselectedvalue = array();
            foreach ($cart_contents as $key => $value) {
                $productid = $value['product_id'];
                $variationid = $value['variation_id'] != ('' || 0) ? $value['variation_id'] : $value['product_id'];
                $productcategorys = get_the_terms($productid, 'product_cat');
                $line_total = ($coupontype == 'sumo' || $coupontype == 'auto') ? $value['line_subtotal'] : $value['line_subtotal'];
                $line_tax = $value['line_tax'];
                if (get_option('woocommerce_prices_include_tax') === 'yes') {
                    $line_total = $line_total + $line_tax;
                } else {
                    $$line_tax = $line_tax;
                }
                /* Checking whether the Product has Category */
                if ($productcategorys != false) {
                    $getcount = count($productcategorys);
                    if ($getcount >= '1') {
                        foreach ($productcategorys as $productcategory) {
                            $termid = $productcategory->term_id;
                            if (get_option('rs_enable_redeem_for_selected_category') == 'yes') {
                                if (get_option('rs_select_category_to_enable_redeeming') != '') {
                                    if (in_array($termid, $include_category)) {
                                        $totalselectedvalue[$productid] = $line_total;
                                    }
                                }
                            }
                            if (get_option('rs_exclude_category_for_redeeming') == 'yes') {
                                if (get_option('rs_exclude_category_to_enable_redeeming') != '') {
                                    if (in_array($termid, $exclude_category)) {
                                        $totalselectedvalue[$productid] = $line_total;
                                    }
                                }
                            }
                        }
                    } else {
                        @$termid = $productcategorys[0]->term_id;
                        if (get_option('rs_enable_redeem_for_selected_category') == 'yes') {
                            if (get_option('rs_select_category_to_enable_redeeming') != '') {
                                if (in_array($termid, $include_category)) {
                                    $totalselectedvalue[$productid] = $line_total;
                                }
                            }
                        }
                        if (get_option('rs_exclude_category_for_redeeming') == 'yes') {
                            if (get_option('rs_exclude_category_to_enable_redeeming') != '') {
                                if (!in_array($termid, $exclude_category)) {
                                    $totalselectedvalue[$productid] = $line_total;
                                }
                            }
                        }
                    }
                }

                if (get_option('rs_enable_redeem_for_selected_products') == 'yes') {
                    if (get_option('rs_select_products_to_enable_redeeming') != '') {
                        if (in_array($variationid != '' || 0 ? $variationid : $productid, $include_productid)) {
                            $totalselectedvalue[] = $line_total;
                        }
                    }
                }
                if (get_option('rs_exclude_products_for_redeeming') == 'yes') {
                    if (get_option('rs_exclude_products_to_enable_redeeming') != '') {
                        $product_id = $variationid != '' || 0 ? $variationid : $productid;
                        if (!in_array($product_id, $exclude_productid)) {
                            $totalselectedvalue[] = $line_total;
                        }
                    }
                }
                if (get_option('rs_enable_redeem_for_selected_products') == 'no' && (get_option('rs_exclude_products_for_redeeming') == 'no') && get_option('rs_exclude_category_for_redeeming') == 'no' && get_option('rs_enable_redeem_for_selected_category') == 'no') {
                    if ($coupontype == 'sumo' || $coupontype == 'auto') {
                        $totalselectedvalue[] = $line_total;
                    }
                }
            }
            if (empty($totalselectedvalue)) {
                $totalselectedvalue[] = $woocommerce->cart->subtotal;
            }
            if ($coupontype == 'sumo') {
                if (isset($_POST['rs_apply_coupon_code_field'])) {
                    $redeemingpoints = $_POST['rs_apply_coupon_code_field'];
                    $point_control = wc_format_decimal(get_option('rs_redeem_point'));
                    $point_control_price = RSMemberFunction::user_role_based_redeem_points(get_current_user_id());
                    $revised_amount = $redeemingpoints / $point_control;
                    $newamount = $revised_amount * $point_control_price;
                    if (isset($_POST['rs_apply_coupon_code'])) {
                        return array_sum($totalselectedvalue);
                    }
                    if (isset($_POST['rs_apply_coupon_code1'])) {
                        return array_sum($totalselectedvalue);
                    }
                    if (isset($_POST['rs_apply_coupon_code2'])) {
                        return array_sum($totalselectedvalue);
                    }
                }
            }

            if ($coupontype == 'auto') {
                $current_carttotal_amount = array_sum($totalselectedvalue);
                return $current_carttotal_amount;
            }
        }

        /*
         * Function to delete the coupon after order placed
         */

        public static function remove_coupon_after_place_order($order_id, $order_post) {
            $order = new WC_Order($order_id);            
            $order_user_id = rs_get_order_obj($order);
            $order_user_id = $order_user_id['order_userid'];
            $getuserdata = get_user_by('id', $order_user_id);
            $getusername = isset($getuserdata->user_login) ? ($getuserdata->user_login) : '';
            $couponname = 'sumo_' . strtolower($getusername);
            $auto_redeem_name = 'auto_redeem_' . strtolower($couponname);
            foreach ($order->get_used_coupons() as $newcoupon) {
                if ($couponname == $newcoupon) {
                    $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($order_user_id, 'redeemcouponids');
                    wp_trash_post($getcouponid);
                }
                if ($auto_redeem_name == $newcoupon) {
                    $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($order_user_id, 'auto_redeemcoupon_ids');
                    wp_trash_post($getcouponid);
                }
            }

            $getoption = get_option('rs_enable_redeem_for_order');
            if ($getoption == 'no') {
                $getoption = get_option('rs_disable_point_if_coupon');
            }
            update_post_meta($order_id, 'rs_check_enable_option_for_redeeming', $getoption);
        }

        public static function update_redeem_reward_points_to_user($order_id, $orderuserid) {
            if ($orderuserid != '') {
// Inside Loop
                $order = new WC_Order($order_id);
                $rewardpointscoupons = $order->get_items(array('coupon'));
                $getuserdatabyid = get_user_by('id', $orderuserid);
                $getusernickname = is_object($getuserdatabyid) ? $getuserdatabyid->user_login : 'Guest';
                $maincouponchecker = 'sumo_' . strtolower($getusernickname);
                $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
                foreach ($rewardpointscoupons as $couponcode => $value) {
                    if ($maincouponchecker == $value['name'] || $auto_redeem_name == $value['name']) {
                        if (get_option('rewardsystem_looped_over_coupon' . $order_id) != '1') {                            
                            $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($orderuserid, 'redeemcouponids');
                            $currentamount = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($getcouponid, 'coupon_amount');
                            if ($auto_redeem_name == $value['name']) {
                                $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($orderuserid, 'auto_redeemcoupon_ids');
                                $currentamount = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($getcouponid, 'coupon_amount');
                            }
                            $current_conversion = wc_format_decimal(get_option('rs_redeem_point'));
                            $point_amount = RSMemberFunction::user_role_based_redeem_points($orderuserid);
                            $redeem_amount_value = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'fp_rs_redeemed_points_value');
                            $total = $value['discount_amount'];
                            if (get_option('woocommerce_tax_display_cart') == 'incl' && get_option('woocommerce_prices_include_tax') == 'no') {
                                $total = $value['discount_amount'] + $value['discount_amount_tax'];
                            } elseif (get_option('woocommerce_tax_display_cart') == 'incl' && get_option('woocommerce_prices_include_tax') == 'yes') {
                                $total = $value['discount_amount'] + $value['discount_amount_tax'];
                            } elseif (get_option('woocommerce_tax_display_cart') == 'excl' && get_option('woocommerce_prices_include_tax') == 'yes') {
                                if (get_option('rs_redeem_field_type_option') == '2') {
                                    $total = $value['discount_amount'] + $value['discount_amount_tax'];
                                }
                            }
                            $redeemedamount = $total * $current_conversion;
                            $redeemedpoints = $redeemedamount / $point_amount;
                            $fp_earned_points_sms = true;
                            if ($fp_earned_points_sms == true) {
                                if (get_option('rs_sms_activated') == 'yes' && get_option('rs_enable_send_sms_to_user') == 'yes') {
                                    if (get_option('rs_send_sms_redeeming_points') == 'yes') {
                                        if (get_option('rs_sms_sending_api_option') == '1') {
                                            RSFunctionForSms::send_sms_twilio_api($order_id);
                                        } else {
                                            RSFunctionForSms::send_sms_nexmo_api($order_id);
                                        }
                                    }
                                }
                            }
                        }
                        return $redeemedpoints;
                        update_option('rewardsystem_looped_over_coupon' . $order_id, '1');
                    }
                }
            }
        }        

        public static function get_coupon_code_data($msg, $msg_code, $object) {
            global $woocommerce;
            foreach ($woocommerce->cart->applied_coupons as $code) {
                $coupon = new WC_Coupon($code);
                $coupon_obj = rs_get_coupon_obj($coupon);
                $couponcode = $coupon_obj['coupon_code'];
                $user_ID = get_current_user_id();
                $getuserdatabyid = get_user_by('id', $user_ID);
                $getusernickname = isset($getuserdatabyid->user_login) ? $getuserdatabyid->user_login : "";
                $maincouponchecker = 'sumo_' . strtolower($getusernickname);
                if ($couponcode == $maincouponchecker) {
                    if ($msg_code == 104) {
                        $msg_code = 204;
                    } else {
                        $msg_code = $msg_code;
                    }
                }
                switch ($msg_code) {
                    case 204 :
                        $msg = __(get_option('rs_coupon_applied_individual_error_msg'), 'rewardsystem');
                        break;
                    default:
                        $msg = $msg;
                        break;
                }
            }
            return $msg;
        }

        public static function rs_error_msg_for_autoredeem($msg, $msg_code, $object) {
            $autoredeemenable = get_option('rs_enable_disable_auto_redeem_points');
            $user_ID = get_current_user_id();
            $getuserdatabyid = get_user_by('id', $user_ID);
            $getusernickname = isset($getuserdatabyid->user_login) ? $getuserdatabyid->user_login : "";
            $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
            $coupon_obj = rs_get_coupon_obj($object);
            $couponcode = $coupon_obj['coupon_code'];
            $coupon_code = isset($couponcode) ? $couponcode : "";
            if ($coupon_code == $auto_redeem_name) {
                switch ($msg_code) {
                    case 109 :
                        $msg = __('Auto Redeem is not applicable to your cart contents.', 'rewardsystem');
                        break;
                    case 113 :
                        $msg = __('Auto Redeem is not applicable to your cart contents.', 'rewardsystem');
                        break;
                    case 101 :
                        $msg = __('Auto Redeem is not applicable to your cart contents.', 'rewardsystem');
                        break;
                    default:
                        $msg = $msg;
                        break;
                }
            }
            return $msg;
        }

        public static function get_coupon_code_data_sucess($msg, $msg_code) {
            global $woocommerce;
            foreach ($woocommerce->cart->applied_coupons as $code) {
                $coupon = new WC_Coupon($code);
                $coupon_obj = rs_get_coupon_obj($coupon);
                $autocoupon = $coupon_obj['coupon_code'];
                $user_ID = get_current_user_id();
                $getuserdatabyid = get_user_by('id', $user_ID);
                $getusernickname = isset($getuserdatabyid->user_login) ? $getuserdatabyid->user_login : "";
                $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
                if ($auto_redeem_name == $autocoupon) {
                    if (count($woocommerce->cart->get_coupons()) == 1) {
                        if ($msg_code == 200) {
                            $msg_code = 501;
                        }
                    }
                }
                switch ($msg_code) {
                    case 501:
                        if (get_option('rs_show_hide_message_for_redeem') == '1') {
                            $msg = __('Auto' . get_option('rs_success_coupon_message'), 'rewardsystem');
                        } else {
                            $msg = '';
                        }
                        break;
                    case 200 :
                        if (isset($_POST['rs_apply_coupon_code'])) {
                            if (get_option('rs_show_hide_message_for_redeem') == '1') {
                                $msg = __(get_option('rs_success_coupon_message'), 'rewardsystem');
                            } else {
                                $msg = '';
                            }
                            ?>
                            <?php
                            if (get_option('rs_redeem_field_type_option') == '2') {
                                ?>
                                <script type="text/javascript">
                                    jQuery(document).ready(function () {
                                        jQuery("#mainsubmi").parent().hide();
                                    });</script>
                                <?php
                            }
                            if (get_option('rs_redeem_field_type_option_checkout') == '2') {
                                ?>
                                <div class="sumo_reward_point_hide_field_script" data-sumo_coupon="yes">
                                    <script type="text/javascript">
                                        jQuery(document).ready(function () {
                                            jQuery("#mainsubmi").parent().hide();
                                        });
                                    </script>
                                </div>
                                <?php
                            }
                        }
                        break;
                    default:
                        $msg = '';
                        break;
                }
            }
            return $msg;
        }

    }

    RSFunctionToApplyCoupon::init();
}