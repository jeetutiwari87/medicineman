<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
<?php
$foodfarm_settings = foodfarm_check_theme_options();
$foodfarm_sidebar_pos = foodfarm_get_sidebar_position();
$foodfarm_sidebar = foodfarm_get_sidebar();
$foodfarm_layout = foodfarm_get_layout();
?>
<?php if($foodfarm_layout == 'fullwidth') :?>
<div class="container">
	<div class="row">	
	<?php endif;?>
		<div class="<?php if (($foodfarm_sidebar_pos == 'left-sidebar' || $foodfarm_sidebar_pos == 'right-sidebar') && $foodfarm_sidebar && is_active_sidebar($foodfarm_sidebar)) echo 'col-lg-9 col-md-9 col-sm-12 col-xs-12 main-sidebar'; else echo 'content-main'; ?> <?php if ($foodfarm_sidebar_pos == 'left-sidebar' && is_active_sidebar($foodfarm_sidebar)){echo 'f-right';}?>">
			<div class="row">
				<?php
					/**
					 * woocommerce_before_main_content hook.
					 *
					 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
					 * @hooked woocommerce_breadcrumb - 20
					 */
					do_action( 'woocommerce_before_main_content' );
				?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php wc_get_template_part( 'content', 'single-product' ); ?>

					<?php endwhile; // end of the loop. ?>

				<?php
					/**
					 * woocommerce_after_main_content hook.
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action( 'woocommerce_after_main_content' );
				?>
			</div>
		</div>
		<?php get_sidebar() ?>
		<?php 
			/**
			 * woocommerce_related_after hook.
			 *
			 * @hooked woocommerce_output_related_products - 10
			 * @hooked foodfarm_banner_single_product - 20
			 */
			do_action('woocommerce_related_after');
		?>
<?php if($foodfarm_layout == 'fullwidth') :?>		
	</div>	
</div>
<?php endif;?>

<?php get_footer( 'shop' ); ?>
