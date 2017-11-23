<?php
extract(shortcode_atts(array(
    'number' => '18',
    'image_filter'  => false,
    'slides_on_desk' => '7',
    'slides_on_tabs' => '5',
    'slides_on_mob' => '3',
    'slides_on_mob_small' => '2',
    'el_class' => ''
                ), $atts));
$el_class = foodfarm_shortcode_extract_class( $el_class );
$slide_id = 'brand_' . wp_rand();
$output = '<div class="brands-container' .$el_class. '"';        
$output .= '>';
ob_start();
// $title = foodfarm_shortcode_js_remove_wpautop($content, true);
?>
<div class="clients-content <?php if($image_filter == true) {echo 'image-turn-white';}?>">
    <div class="own-carousel controls-custom-style" id="<?php echo $slide_id; ?>">
        <?php
        $args = array(
		'hide_empty' => false
        );

        $brands = get_terms('yith_product_brand', $args);
        foreach ($brands as $_brand) {
            $thumbnail_id = get_woocommerce_term_meta($_brand->term_id, 'thumbnail_id');
            $_brand->thumbnail_id = $thumbnail_id;
            $_brand->brand_url = get_term_link($_brand->slug, 'yith_product_brand');
            $image = wp_get_attachment_image_src($thumbnail_id, 'full');
            if ($image) {
                echo sprintf('<div class="brands-content"><div class="brands-img"><a title="' . $_brand->name . '" href="%s"><img src="%s" alt="%s"  width= "153"/></a></div></div>', get_term_link($_brand), $image[0], $image[1], $image[2], $_brand->name);
            } else {
                echo sprintf('<div class="brands-content"><div class="brands-img"><a title="' . $_brand->name . '" href="%s"><img src="%s" alt="%s"  width= "153"/></a></div></div>', get_term_link($_brand), wc_placeholder_img_src(), $_brand->name);
            }
        }
        ?>
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
            728:{items:<?php echo esc_js($slides_on_mob);?>},
            992:{items:<?php echo esc_js($slides_on_tabs);?>},
            1200:{items:<?php echo esc_js($slides_on_desk);?>}
        },
        }); //end: owl
    });
</script>

<?php
    $output .= ob_get_clean();

    $output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_brands' ) . "\n";

    echo $output;

wp_reset_query();