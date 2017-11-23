<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
<?php
global $wp_query;
$foodfarm_settings = foodfarm_check_theme_options();
$foodfarm_sidebar_pos = foodfarm_get_sidebar_position();  
$foodfarm_sidebar = foodfarm_get_sidebar();
$foodfarm_layout = foodfarm_get_layout();
$cat = $wp_query->get_queried_object();
if(isset($cat->term_id)){
	$woo_cat = $cat->term_id;
}else{
	$woo_cat = '';
}
$product_list_mode = get_metadata('product_cat', $woo_cat, 'list_mode_product', true);
?>
<?php if($foodfarm_layout == 'fullwidth') :?>
<div class="container">
	<div class="row">	 
	<?php endif;?>
		<!--div class="<?php if (($foodfarm_sidebar_pos == 'left-sidebar' || $foodfarm_sidebar_pos == 'right-sidebar') && $foodfarm_sidebar && is_active_sidebar($foodfarm_sidebar)) echo 'col-lg-9 col-md-9 col-sm-12 col-xs-12 main-sidebar'; else echo 'content-main'; ?> <?php if ($foodfarm_sidebar_pos == 'left-sidebar' && is_active_sidebar($foodfarm_sidebar)){echo 'f-right';}?>"-->
		<div class="<?php if (($foodfarm_sidebar_pos == 'left-sidebar' || $foodfarm_sidebar_pos == 'right-sidebar') && $foodfarm_sidebar && is_active_sidebar($foodfarm_sidebar)) echo 'col-lg-9 col-md-9 col-sm-12 col-xs-12 main-sidebar'; else echo 'col-lg-9 col-md-9 col-sm-12 col-xs-12 main-sidebar f-right'; ?> <?php if ($foodfarm_sidebar_pos == 'left-sidebar' && is_active_sidebar($foodfarm_sidebar)){echo 'f-right';}?>">

			<?php if ( have_posts() ) : ?>
			    <div class="tooltbars">
					<?php
						/**
						 * woocommerce_archive_description hook.
						 *
						 * @hooked woocommerce_taxonomy_archive_description - 10
						 * @hooked woocommerce_product_archive_description - 10
						 */
						do_action( 'woocommerce_archive_description' );
					?>
					<div class="col-md-6 col-sm-10 col-xs-9 no-padding">
						<div class="select-tooltbars">
						<?php
							/**
							 * woocommerce_before_shop_loop hook.
							 *
							 * @hooked woocommerce_result_count - 20
							 * @hooked woocommerce_catalog_ordering - 30
							 */
							do_action( 'woocommerce_before_shop_loop' );
						?>
						</div>
					</div>
				</div>

				<?php woocommerce_product_loop_start(); ?>

					<?php woocommerce_product_subcategories(); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php wc_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php
					/**
					 * woocommerce_after_shop_loop hook.
					 *
					 * @hooked woocommerce_pagination - 10
					 */
					do_action( 'woocommerce_after_shop_loop' );
				?>

			<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

				<?php wc_get_template( 'loop/no-products-found.php' ); ?>

			<?php endif; ?>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-sidebar "><?php  if ( is_active_sidebar( 'shop-sidebar' ) ) : //check the sidebar if used.
dynamic_sidebar( 'shop-sidebar' );  // show the sidebar.
  ?></div>

	</div>	
</div>
<?php endif;?>
<?php get_footer( 'shop' ); ?>
