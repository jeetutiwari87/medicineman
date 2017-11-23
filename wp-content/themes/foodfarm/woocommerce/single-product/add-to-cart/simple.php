<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product,$foodfarm_settings;

if ( ! $product->is_purchasable() ) {
	return;
}

?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
?>




<?php if(isset($foodfarm_settings['product-cart'])){
	if($foodfarm_settings['product-cart']){?>
		<?php if ( $product->is_in_stock() ) : ?>

			<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

			<?php 
			global $post; 
			 $is_vari =  get_field('is_product_for_variations'); 
			 
			 if($is_vari == 'Yes'){ 
					
					$quentity_number = 1;
					if( isset( $_POST['quentity_type'])){
						if($_POST['quentity_type'] == 'ounce'){ $quentity_number = 8; }
						if($_POST['quentity_type'] == 'half-ounce'){$quentity_number = 4;}
						if($_POST['quentity_type'] == 'quarter'){$quentity_number = 2;}
						if($_POST['quentity_type'] == 'eighth'){$quentity_number = 1;}


					}  
			 ?>
					<div class="customeBox">
					<table class="variationss" cellspacing="0">
						<tbody>
							<tr>
								<td class="label"><label for="attribute_quantity_ctm">Quantity</label></td>
								<td class="value">
								
								<select id="attribute_quantity_ctm" class="" name="attribute_quantity_ctm">
									<option <?php if( isset( $_POST['quentity_type'])){if($_POST['quentity_type'] == 'ounce'){ echo 'selected';} } ?> data-qut = '8' value="ounce" class=" ">OUNCE</option>
									<option  <?php if( isset( $_POST['quentity_type'])){if($_POST['quentity_type'] == 'half-ounce'){ echo 'selected';} } ?> data-qut = '4'  value="half-ounce" class=" ">HALF OUNCE</option>
									<option <?php if( isset( $_POST['quentity_type'])){if($_POST['quentity_type'] == 'quarter'){ echo 'selected';} } ?> data-qut = '2'  value="quarter" class=" ">QUARTER</option>
									<option <?php if( isset( $_POST['quentity_type'])){if($_POST['quentity_type'] == 'eighth'){ echo 'selected';} }else{ echo 'selected';} ?> data-qut = '1'  value="eighth"  class=" ">EIGHTH</option>
								</select> 						
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				 <form class="cart customefBox" method="post" enctype='multipart/form-data'>
					<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
					<label class="qty"><?php echo esc_html__('Qty:','foodfarm') ?></label>
					<div style="display: none;" id="type_qun_input" class="type_qun"> <?php echo $quentity_number; ?> </div>
					<span style="display: none;"> X </span>
					<?php
						/*if ( ! $product->is_sold_individually() ) {
							woocommerce_quantity_input( array(
								'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
								'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
								'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
							) );
						}
					*/
				
				 
					?>
					<div class="quantity buttons_added">
						<div class="qty-number">
						<span class="increase-qty1 minus1" onclick="">-</span>
						</div>
						<input id="quantity_dummy" step="1" min="1" max="" name="quantity_dummy" value="<?php if( isset( $_POST['quantity_dummy'])){ echo $_POST['quantity_dummy']; }else{ echo '1' ;}  ?>" title="Qty" class="input-text qty text" size="4" type="number">
						<div class="qty-number"><span class="increase-qty1 plus1" onclick="">+</span></div>
					</div>
					<span style="display: none;"> = </span>
					<div style="display: none;" id="total_ctm_input" class="total_ctm"> <?php if( isset( $_POST['quantity'])){ echo $_POST['quantity']; }else{ echo '1' ;}  ?> </div>
						<input id="quantity" name="quantity" value="<?php if( isset( $_POST['quantity'])){ echo $_POST['quantity']; }else{ echo '1' ;}  ?>"  type="hidden" />
						<input id="quentity_type" type="hidden" name="quentity_type" value="<?php if( isset( $_POST['quentity_type'])){ echo $_POST['quentity_type']; }else{ echo 'eighth' ;}  ?>" />
						<input id="quentity_number" type="hidden" name="quentity_number" value="<?php echo $quentity_number; ?>" />
					
					
					<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />
				
					<button type="submit" class="single_add_to_cart_button button alt"><span class="icon-6"></span><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

					<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
				</form>
				
				<script>
					jQuery(document).ready(function(){
						jQuery('#attribute_quantity_ctm').change(function(){
								var type = jQuery(this).val();
								var qut1 = jQuery('option:selected', this).attr('data-qut');
								 
								jQuery('#quentity_type').val(type); 
								jQuery('#type_qun_input').html(qut1); 
								jQuery('#quantity_dummy').val(1);
								jQuery('#total_ctm_input').html(qut1*1);
								jQuery('#quentity_number').html(1);
								jQuery('#quantity').val(qut1*1);
						})
						jQuery('.plus1').click(function(){
							 var input = jQuery('#quantity_dummy').val();
								jQuery('#quantity_dummy').val(parseInt(input)+1);
								updateQun();
						})
						jQuery('.minus1').click(function(){
							 var input = jQuery('#quantity_dummy').val();
								if (input > 0) {
								jQuery('#quantity_dummy').val(parseInt(input)-1);
								updateQun();
								}
						})
						
					 
		
		 
					})
					function updateQun(){
						 var qun =  jQuery('#attribute_quantity_ctm option:selected').attr('data-qut');
								 var qut2 = jQuery('#quantity_dummy').val();
								 
								 jQuery('#total_ctm_input').html(qun*qut2); 
								 jQuery('#quantity').val(qun*qut2);
								 jQuery('#quentity_number').val(qut2);
					}
					
				</script>
				
				
			 <?php }else{ ?>
			 
			 <form class="cart" method="post" enctype='multipart/form-data'>
			 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
			 	<label class="qty"><?php echo esc_html__('Qty:','foodfarm') ?></label>
			 	<?php
			 		if ( ! $product->is_sold_individually() ) {
			 			woocommerce_quantity_input( array(
			 				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
			 				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
			 				'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
			 			) );
			 		}
			 	?>

			 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />

			 	<button type="submit" class="single_add_to_cart_button button alt"><span class="icon-6"></span><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

				<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
			</form>
			 
			<?php  } ?>
			
		  
				
			<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

		<?php endif; ?>
	<?php }?>
<?php }?>


