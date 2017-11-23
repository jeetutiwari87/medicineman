<?php
$output  = $item_delay = $number = $slides_on_desk = $slides_on_tabs  = $slides_on_mob = $slides_on_mob_small= $per_slide = $el_class = '';
$number = 8;
extract(shortcode_atts(array(
    'first_title' =>'Our',
    'last_title' => 'Product',
    'number' => 8,
    'orderby' => 'date',
    'order' => 'desc',
    'category' => '',
    'slides_on_desk' =>5,
    'slides_on_tabs' =>3,
    'slides_on_mob' => 2,
    'slides_on_mob_small' => 1,
    'show_dot' => 'yes',
    'show_nav' => 'yes',
    'style_navigation' => 'style_top',
    'item_delay' => 'yes',
    'el_class' => ''
), $atts));

$args = array(
        'posts_per_page' => '12',
        'product_cat' => $category,
        'post_type' => 'product',
        'orderby' => $orderby,
        'order' => $order
    );
if($style_navigation == 'style_middle'){
    $nav_class = 'style_middle';
}
else{
    $nav_class = '';
}
// delay
$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, array('per_page' => $number, 'orderby' => 'date', 'order' => 'desc')));
    $el_class = foodfarm_shortcode_extract_class($el_class);
    $output = '<div class="categories-product ' . $el_class . ' ' . $nav_class . '"';
    $output .= '>';
    ob_start();
if ($products->have_posts()) :
    $slide_id = 'product_slide_' . wp_rand();
    $breakpoints = '[320, ' . $slides_on_mob_small . '], [375, ' . $slides_on_mob . '], [729, ' . $slides_on_tabs . '], [981, ' . $slides_on_tabs . '], [1380, ' . $slides_on_desk . ']';
    
    $count_item = 0.2; 
    $animation_delay = '';
    if($item_delay) {
        $animation_delay = ' data-sr="wait '. $count_item .'s"';  
    }
    $count_item += 0.2;
    ?>
    <?php if($first_title || $last_title) :?>
        <div class="entry-title">
            <h2><?php echo $first_title;?> <span><?php echo $last_title;?></span></h2>
            <div class="line"></div>
        </div>
    <?php endif;?>
    <div class="product-grid" <?php echo $animation_delay; ?>>
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
            <?php if($show_dot) :?>
            dots:true,
            <?php else:?>
            dots:false,
            <?php endif;?>
            <?php if($show_nav) :?>
            nav:true,
            <?php else:?>
            nav:false,
            <?php endif;?>
            loop:true,
            margin:10,
            navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
            responsive:{
                0:{ items:<?php echo esc_js($slides_on_mob_small);?>},
                375:{ items:<?php echo esc_js($slides_on_mob);?>},
                728:{items:<?php echo esc_js($slides_on_tabs);?>},
                992:{items:<?php echo esc_js($slides_on_tabs);?>},
                1380:{items:<?php echo esc_js($slides_on_desk);?>}
             }
        }); //end: owl
    });
    </script>
    
    <?php 
    $output .= ob_get_clean();

    $output .= '</div>' . foodfarm_shortcode_end_block_comment('foodfarm_product_category') . "\n";

    echo $output;
    ?>
    <?php
endif;

wp_reset_postdata();

