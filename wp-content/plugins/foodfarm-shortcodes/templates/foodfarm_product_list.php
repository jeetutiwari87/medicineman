<?php
$output = $product_type = $number = $category_parent = $slug_name = $item_delay =  $el_class = '';
extract(shortcode_atts(array(
    'product_type' => '',
    'number' => 6,
	'order_way' => 'desc',
	'order_by' => 'date',
    'slug_name' => '',
	'item_delay' => 'yes',
    'el_class' => ''
), $atts));

$args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page' => $number,
    'order' => $order_way,
    'orderby' => $order_by,
	// 'product_cat' => $slug_name
);
if ($product_type == "on_sale") {
		$args = array(
		'posts_per_page'    => $number,
		'no_found_rows'     => 1,
		'post_status'       => 'publish',
		'post_type'         => 'product',
		'meta_query'        => WC()->query->get_meta_query(),
		'product_cat' => $slug_name,
		'meta_query'     => array(
	        'relation' => 'OR',
	        array( // Simple products type
	            'key'           => '_sale_price',
	            'value'         => 0,
	            'compare'       => '>',
	            'type'          => 'numeric'
	        ),
	        array( // Variable products type
	            'key'           => '_min_variation_sale_price',
	            'value'         => 0,
	            'compare'       => '>',
	            'type'          => 'numeric'
	        )
	    )
	);
}
if ($product_type == "featured") {
        $meta_query  = WC()->query->get_meta_query();
        $tax_query   = WC()->query->get_tax_query();
        $tax_query[] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        );

	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => $number,
		'orderby'             => $order_by,
		'order'               => $order_way,
		'meta_query'          => $meta_query,
		'tax_query'           => $tax_query, 
	);
}
if ($product_type == "top_rated") {
	add_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses'));
	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => $number,
		'orderby'             => $order_by,
		'order'               => $order_way,
		'meta_query'          => WC()->query->get_meta_query()
	);
}

if ($slug_name){
    $catArray = explode(',', $slug_name);
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $catArray,
        ),
    );
    $args['product_cat'] = $slug_name;
}

$posts = new WP_Query($args);
$el_class = foodfarm_shortcode_extract_class($el_class);
ob_start();
if ($posts->have_posts()) :
 $slide_id = 'on_sale_' . wp_rand();

?>
<?php 
    $count_item = 0.2; 
    $animation_delay = '';
    if($item_delay) {
        $animation_delay = ' data-sr="wait '. $count_item .'s"';  
    }
    $count_item += 0.2;
?>

	<ul class="product-small-list <?php echo esc_attr( $el_class );?>" >
		<?php while ($posts->have_posts()) : $posts->the_post(); ?>
			<?php global $product; ?>

			<li class="item-product-grid" <?php echo $animation_delay;?>>
				<div class="product-content">
					<div class="product-img">
						<a class="product-image" href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
							<?php echo $product->get_image('foodfarm-member'); ?>
						</a>
					</div>
					<div class="product-desc">
						<h3 class="product_title">
							<a class="product-name" href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
							<?php echo $product->get_title(); ?>
							</a>
						</h3>
						<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
						<div class="price">
							<?php echo $product->get_price_html(); ?>
						</div>	
					</div>
				</div>	
			</li>
		<?php endwhile; ?>
	</ul>
<?php

    $output .= ob_get_clean();

    $output .= foodfarm_shortcode_end_block_comment( 'foodfarm_products_list' ) . "\n";
endif;
    echo $output;

wp_reset_postdata();