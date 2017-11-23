<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="col-md-6 col-sm-12 col-xs-12 media">
	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>
	</div>
	<div class="col-md-6 col-sm-12 col-xs-12 info">
		<div class="summary entry-summary">
			<div class="product-desc">
				<?php
					/**
					 * woocommerce_single_product_summary hook.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 */
					do_action( 'woocommerce_single_product_summary' );
				?>
			</div>
			<?php
			$foodfarm_settings = foodfarm_check_theme_options();
		    ?>
		    <div class="share-links">
			    <?php if($foodfarm_settings['product-mail-friend']) :?>
			        <div class="share-email">
			            <a href="mailto:blank?subject=<?php the_title(); ?>&amp;body= <?php the_permalink() ?>"><?php echo esc_html__('Email to Friends','foodfarm');?></a>
			        </div>
			    <?php endif;?>
			    <?php if($foodfarm_settings['product-share']) :?>
				<div class="addthis_sharing_toolbox">
					<p class="big-text"><?php echo esc_html__('Share','foodfarm');?>: </p>
					<div class="f-social">
						<ul>
							<li><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
							<li><a href="https://plus.google.com/share?url=<?php the_permalink();?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
							<li><a href="https://twitter.com/share?url=<?php echo urlencode(get_the_permalink()); ?>&amp;text=<?php echo urlencode(get_the_title()); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
							<li><a href="http://www.linkedin.com/shareArticle?url=<?php echo urlencode(get_the_permalink()); ?>&amp;title=<?php echo urlencode(get_the_title()); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
						</ul>
					</div>
				</div>
			<?php endif;?>
			</div>
		</div><!-- .summary -->
	</div>	

	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
