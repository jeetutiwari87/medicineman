<?php

$output = $title = $orderby = $parent= $pad_count = $order = $hide_empty = $btn_text = $dis_img = $bg_hcolor = $bg_color= $title_color= $el_class = $ex_cat = '';
$number = 12;
$columns = 4;
extract( shortcode_atts( array(
    'parent' => '',
    'number' => 12,
    'ex_cat' => '',
    'columns' => 4,
    'orderby' => 'date',
    'order' => 'desc',
    'view_more' => 'yes',
	'hide_empty' => 'yes',
    'btn_text' => '',
    'pad_count' => '',
    'dis_img' => 'yes',
    'el_class' => '',
    'title_color' => '',
    'bg_color' => '',
    'bg_hcolor' => '',   
), $atts ) );

$id ='';
$el_class = foodfarm_shortcode_extract_class( $el_class );
$hide_empty_v = $hide_empty == 'yes' ? true :false;
$pad_count_v = $pad_count == 'yes' ? true:false;
if($parent !=''){
	$idObj = get_term_by('slug', $parent, 'product_cat') ;
	$id = $idObj->term_id;
}
// get terms and workaround WP bug with parents/pad counts
if($id !=''){
	if($ex_cat !=''){
	$args = array(
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty_v,
		'pad_counts' => $pad_count_v,
		'child_of'   => $id,
		'taxonomy' => 'product_cat',
		'number' 	 => $number,
		'exclude' => $ex_cat,
	);
	}else{
		$args = array(
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty_v,
			'pad_counts' => $pad_count_v,
			'child_of'   => $id,
			'taxonomy' => 'product_cat',
			'number' 	 => $number,
		);
	}
}else{
	$args = array(
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty_v,
		'pad_counts' => $pad_count_v,
		'taxonomy' => 'product_cat',
		'number' 	 => $number,
	);	
}

$output = '<div class="ff-product-categories-shortcode wpb_content_element ' . $el_class . '"';
$output .= '>';
global $woocommerce_loop;

$product_categories = get_terms(  $args );
$columns_v = absint( $columns );
$woocommerce_loop['columns'] = $columns_v;

ob_start();

if ( $product_categories ) {
	woocommerce_product_loop_start();

	foreach ( $product_categories as $category ) {?>
		<li <?php wc_product_cat_class( '', $category ); ?>>
			<div class="prd_cat_count">
				<div class="prd_count_inner">
					<?php
					/**
					 * woocommerce_before_subcategory_title hook.
					 *
					 * @hooked woocommerce_subcategory_thumbnail - 10
					 */
					
					if($dis_img !='no'){
						do_action( 'woocommerce_before_subcategory_title', $category );
					}
					
					?>
					<h3>
					<?php echo '<a href="'. get_term_link( $category, 'product_cat' ) .'">';?>
					<?php echo esc_html($category->name);?><?php if ( $category->count > 0 ){
							echo '<span class="hidden-ms hidden-md hidden-lg"> (' . esc_html($category->count) . ') </span>';
						}
						?>
					<?php echo '</a>';?>
						</h3>
					<div class="figcaption">
						<?php if ( $category->count > 0 ){
							echo '<p class="number_prds">' . esc_html($category->count) . '</p>';
						}
						?>
				        <p class="name_prd"><?php echo esc_html($category->name).' '.esc_html__('Products','foodfarm');?></p>
						<?php 
						if($btn_text !=''){
							echo '<a class="cat_link btn btn-default btn-no-radius" href="'. get_term_link( $category, 'product_cat' ) .'">'.esc_html($btn_text).'</a>';
						}
						?>
				    </div>
			    </div>
			</div>
		</li>
	<?php }
	woocommerce_product_loop_end();
	woocommerce_reset_loop();	
}
?>
<?php
$output .= '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
$output .= '</div>' . foodfarm_shortcode_end_block_comment('foodfarm_product_categories') . "\n";
echo $output;
wp_reset_postdata();