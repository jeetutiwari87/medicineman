<?php
$output = $item_delay = $el_class = '';
extract(shortcode_atts(array(
	'container_type' => '',
    'item_delay' => '',
    'el_class' => ''
), $atts));
$count_item = 0.2; 
$animation_delay = '';
if($item_delay) {
    $animation_delay = ' data-sr="wait '. $count_item .'s"';  
}
$count_item += 0.2;
$el_class = foodfarm_shortcode_extract_class( $el_class );
if($container_type == '2'){
	$layout_class = 'container-fluid';
}elseif($container_type =='3'){
	$layout_class = 'wide-container';
}
else{
	$layout_class = 'container';
}
$output = '<div class="foodfarm-container ' . $layout_class . ' ' . $el_class . '"';
if ($animation_delay)
    $output .= ''.$animation_delay.'';
$output .= '>';
$output .= do_shortcode($content);
$output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_container' ) . "\n";

echo $output;