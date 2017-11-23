<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $wp_query;
$foodfarm_settings = foodfarm_check_theme_options();
// Store loop count we're currently on
if (empty($woocommerce_loop['loop'])) {
    $woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if (empty($woocommerce_loop['columns'])) {
    $woocommerce_loop['columns'] = apply_filters('loop_shop_columns', 4);
}

// Ensure visibility
if (!$product || !$product->is_visible()) {
    return;
}

// Increase loop count
$woocommerce_loop['loop'] ++;

// Extra post classes
$classes = array();
if (0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns']) {
    $classes[] = 'first';
}
if (0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns']) {
    $classes[] = 'last';
}
$cat = $wp_query->get_queried_object();
if(isset($cat->term_id)){
$woo_cat = $cat->term_id;
}else{
    $woo_cat = '';
}
$category_cols = get_metadata('product_cat', $woo_cat, 'category_cols', true);
$cols_md = '';
$cols_sm = '';
$cols_xs = '';
if(!is_product_category()){
    switch ($foodfarm_settings['product-cols']) {
        case 2: $cols_md = 'col-md-6';
            $cols_sm = 'col-sm-4';
            $cols_xs = 'col-xs-12';
            break;
        case 3: $cols_md = 'col-md-4';
            $cols_sm = 'col-sm-4';
            $cols_xs = 'col-xs-12';
            break;
        default: $cols_md = 'col-md-3';
            $cols_sm = 'col-sm-4';
            $cols_xs = 'col-xs-12';
            break;
    }
} else{
//echo $category_cols; die;
    switch ($category_cols) {
        case 2: $cols_md = 'col-md-3';
        $cols_sm = 'col-sm-4';
            $cols_xs = 'col-xs-12';
            break;
        case 4: $cols_md = 'col-md-3';
         $cols_sm = 'col-sm-4';
            $cols_xs = 'col-xs-12';
            break;
        default: $cols_md = 'col-md-4';
            $cols_sm = 'col-sm-4';
            $cols_xs = 'col-xs-12';
            break;
    }
}
$classes[] = $cols_md . ' ' . $cols_sm . ' ' . $cols_xs;
?>
<li <?php post_class($classes) ?>>

    <div class="product-content">
        <div class="product-img">
                <?php
                /**
                 * woocommerce_before_shop_loop_item_title hook
                 *
                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                 * @hooked woocommerce_template_loop_product_thumbnail - 10
                 */
                do_action('woocommerce_before_shop_loop_item_title');
                ?>
        </div>
        <div class="product-desc">  
	        <div class="product-action product-action-grid">
	                <?php
	                /**
	                 * woocommerce_shop_loop_item_title hook
	                 *
	                 * @hooked woocommerce_template_loop_product_title - 10
	                 */
	                do_action('woocommerce_shop_loop_item_title');
	                ?>
	        </div>  
            <h3><a href="<?php the_permalink(); ?>" class="product-name"><?php the_title(); ?></a></h3>
            <?php
            /**
             * woocommerce_after_shop_loop_item_title hook
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action('woocommerce_after_shop_loop_item_title');
            ?>
            <div class="product-action product-action-list">
            <?php
            do_action('woocommerce_list_shop_loop_custom');
            ?>
            </div>

        </div>
    </div>	

</li>
