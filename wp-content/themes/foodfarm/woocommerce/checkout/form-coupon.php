<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! wc_coupons_enabled() ) {
	return;
}

if ( ! WC()->cart->applied_coupons ) {
    $info_message = apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'foodfarm' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'foodfarm' ) . '</a>' );
    wc_print_notice( $info_message, 'notice' );
}
?>
<form class="checkout_coupon" method="post" style="display:none">

	<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'foodfarm' ); ?>" id="coupon_code" value="" />
	<input type="submit" class="button btn btn-default" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'foodfarm' ); ?>" />

	<div class="clear"></div>
</form>
