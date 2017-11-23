<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment">
	<h3><?php echo esc_html__( 'Select Payment Method', 'foodfarm' ); ?></h3>
	<?php if ( WC()->cart->needs_payment() ) : ?>
		
			<?php
				if ( ! empty( $available_gateways ) ) {
					echo '<select name="payment_method" id="payment_method_select">';
					    foreach ($available_gateways as $gateway) { ?>
					         <option value="payment_method_<?php echo esc_attr($gateway->id); ?>"><?php echo $gateway->get_title(); ?> </option>
					   <?php  }
					echo '</select>';

					foreach ($available_gateways as $gateway) { 
							if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
								<div class="payment_box payment_method_<?php echo $gateway->id; ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
									<?php $gateway->payment_fields(); ?>
								</div>
							<?php endif; 
					}
				} else {
				echo '<ul class="wc_payment_methods payment_methods methods">';
					echo '<li>' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'foodfarm' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'foodfarm' ) ) . '</li>';
				echo '</ul>';
				}
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
				var defaultmethod = jQuery('input[name=payment_method]:checked').val();
				jQuery('select#payment_method_select').val('payment_method_'+ defaultmethod);	
				});			
				jQuery('select#payment_method_select').on('change', function (e) {
				    var optionSelected = jQuery("option:selected", this);
				    var valueSelected = this.value;
				    jQuery('#' + valueSelected).prop("checked", true).trigger("click");
				});
			</script>
		<ul class="wc_payment_methods payment_methods methods hidden">
			<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					echo '<li>' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_country() ? __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : __( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>';
				}
			?>
		</ul>		
	<?php endif; ?>
	<div class="form-row place-order mobile-hide">
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
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
