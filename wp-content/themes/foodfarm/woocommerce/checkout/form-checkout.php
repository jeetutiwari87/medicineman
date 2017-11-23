<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', esc_html__( 'You must be logged in to checkout.', 'foodfarm' ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	<div class="row">
	<div class="order-review <?php if ( sizeof( $checkout->checkout_fields ) > 0 ) {echo 'col-md-4 col-sm-12 col-xs-12'; }?>">
		<h3 id="order_review_heading"><?php echo esc_html__( 'Payment Options', 'foodfarm' ); ?></h3>

		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		<div id="order_review" class="woocommerce-checkout-review-order">
			<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		</div>

		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
	</div>
	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="col-md-4 col-sm-12 col-xs-12 customer-details">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
			<div class="col-md-4 col-sm-12 col-xs-12 customer-details">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	</div>
	<div class="form-row place-order destop-hide">
		<noscript>
			<?php echo esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'foodfarm' ); ?>
			<br/><input type="submit" class="button btn btn-default alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'foodfarm' ); ?>" />
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
		<?php $order_button_text = apply_filters( 'woocommerce_pay_order_button_text', esc_html__( 'Order now', 'foodfarm' ) );?>
		<?php echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button btn btn-default btn-full alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>
	</div>
</form>


<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
