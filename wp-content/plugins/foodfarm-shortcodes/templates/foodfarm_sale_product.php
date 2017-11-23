<?php
$output  = $number = $slides_on_desk = $slides_on_tabs  = $slides_on_mob = $slides_on_mob_small= $per_slide = $el_class = '';
$number = 6;
extract(shortcode_atts(array(
    'number' => 6,
    'slides_on_desk' =>6,
    'slides_on_tabs' =>4,
    'slides_on_mob' => 2,
    'slides_on_mob_small' => 1,
    'el_class' => ''
), $atts));

global $woocommerce, $woocommerce_loop;
$args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page' => $number,
    'orderby' => 'DESC',
    'order' => 'date',
);
$product_ids_on_sale = woocommerce_get_product_ids_on_sale();
$meta_query = array();
$meta_query[] = $woocommerce->query->visibility_meta_query();
$meta_query[] = $woocommerce->query->stock_status_meta_query();
$args['meta_query'] = $meta_query;
$args['post__in'] = $product_ids_on_sale;
$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, array('per_page' => $number, 'orderby' => 'date', 'order' => 'desc')));
    $el_class = foodfarm_shortcode_extract_class($el_class);
    $output = '<div class="our-products-tab tabs-fillter ' . $el_class . '"';
    $output .= '>';
    ob_start();
if ($products->have_posts()) :

    $slide_id = 'product_slide_' . wp_rand();
    $breakpoints = '[320, ' . $slides_on_mob_small . '], [375, ' . $slides_on_mob . '], [729, ' . $slides_on_tabs . '], [981, ' . $slides_on_tabs . '], [1380, ' . $slides_on_desk . ']';
    
    ?>
    <div class="row product-grid">
        <div id="<?php echo $slide_id;?>" class="own-carousel controls-custom product-grid">
        <?php while ($products->have_posts()) : $products->the_post(); ?>
            <?php wc_get_template_part( 'content', 'product-custom' ); ?>
        <?php endwhile; ?>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(function ($) {
        var owl = $("#<?php echo esc_js($slide_id); ?>");
        owl.owlCarousel({
            <?php if (is_rtl()) :?>
                rtl:true,
            <?php endif;?>
            dots:false,
            loop:true,
            margin:10,
            nav:true,
            navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
            responsive:{
                0:{ items:<?php echo esc_js($slides_on_mob_small);?>},
                375:{ items:<?php echo esc_js($slides_on_mob);?>},
                728:{items:<?php echo esc_js($slides_on_tabs);?>},
                1380:{items:<?php echo esc_js($slides_on_desk);?>}
             }
        }); //end: owl
    });
    </script>

    <?php
endif;

wp_reset_postdata();

$output .= ob_get_clean();

$output .= '</div>' . foodfarm_shortcode_end_block_comment('foodfarm_sale_product') . "\n";

echo $output;