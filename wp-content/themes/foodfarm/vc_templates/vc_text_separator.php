<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $content
 * @var $big_color
 * @var $big_title
 * @var $title_align
 * @var $el_width
 * @var $style
 * @var $title
 * @var $align
 * @var $color
 * @var $accent_color
 * @var $el_class
 * @var $layout
 * @var $css
 * @var $border_width
 * @var $add_icon
 * Icons:
 * @var $i_type
 * @var $i_icon_fontawesome
 * @var $i_icon_openiconic
 * @var $i_icon_typicons
 * @var $i_icon_entypo
 * @var $i_icon_linecons
 * @var $i_color
 * @var $i_custom_color
 * @var $i_background_style
 * @var $i_background_color
 * @var $i_custom_background_color
 * @var $i_size
 * @var $i_css_animation
 * Shortcode class
 * @var $this WPBakeryShortcode_Vc_Text_Separator
 */

$title_align = $big_align = $icon_cus_color = $cus_icon_class = $el_width = $style = $align =
$color = $accent_color = $el_class = $layout = $css =
$border_width = $add_icon = $i_type = $i_icon_fontawesome =
$i_icon_openiconic = $i_icon_typicons = $i_icon_entypo =
$i_icon_linecons = $i_color = $i_custom_color =
$i_background_style = $i_background_color =
$i_custom_background_color = $i_size = $i_css_animation = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class = 'vc_separator wpb_content_element';
if( '' !== $cus_icon_class){
	$class .= ' custom-separator';
}
$class .= ( '' !== $title_align ) ? ' vc_' . $title_align : '';
$class .= ( '' !== $el_width ) ? ' vc_sep_width_' . $el_width : ' vc_sep_width_100';
$class .= ( '' !== $style ) ? ' vc_sep_' . $style : '';
$class .= ( '' !== $border_width ) ? ' vc_sep_border_width_' . $border_width : '';
$class .= ( '' !== $align ) ? ' vc_sep_pos_' . $align : '';

$class .= ( 'separator_no_text' === $layout ) ? ' vc_separator_no_text' : '';
if ( '' !== $color ) {
	$class .= ' vc_sep_color_' . $color;
}
$inline_css = ( 'custom' === $color && '' !== $accent_color ) ? ' style="' . vc_get_css_color( 'border-color', $accent_color ) . '"' : '';

$class_to_filter = $class;
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$css_class = esc_attr( trim( $css_class ) );
$icon = '';
$icon_size ="";
$cus_i_color ="";
if('' !== $icon_cus_color){
	$cus_i_color .= " style= color:".$icon_cus_color.'';
}
$icon_size = $icon_size ? ' style="' . esc_attr( $icon_size ) . '"' : '';
if ( 'true' === $add_icon ) {
	vc_icon_element_fonts_enqueue( $i_type );
	if( '' !== $cus_icon_class){
		$icon = '<span class="custom-icon-class '.$cus_icon_class.'"'.$cus_i_color.'></span>';
	}else{
		$icon = $this->getVcIcon( $atts );
	}
	
}

$content_o = '';
if ( $icon ) {
	$content_o = $icon;
}
$big_title = foodfarm_shortcode_js_remove_wpautop($content, true);
if ( '' !== $title && 'separator_no_text' !== $layout ) {
	$css_class .= ' vc_separator-has-text';
	$content_o .= '' . $title . '';
}
$big_title_class ="";
if('big_left' === $big_align){
	$big_title_class .= 'text-left ';
}else if('big_right' === $big_align){
	$big_title_class .= 'text-right ';
}else{
	$big_title_class .= 'text-center ';
}

$new_css_class = $css_class;
$cus_class="";
if (strpos($css_class, 'vc_custom') !== false) {
	$pos = strpos($css_class, "vc_custom");
	$cus_class = substr($css_class,$pos);
	$new_css_class = str_replace($cus_class,"",$css_class);
}
$big_style ="";
if('' !== $big_color){
	$big_style .= ' style="color:'.$big_color.'"';
	$big_title_class .= 'custom-color';
}
?>

<?php if('' !== $big_title):?>
<div class="entry-title title-icon <?php echo esc_attr($big_title_class).esc_attr($cus_class);?>" <?php echo $big_style;?>>
	<div class="big-title"><?php echo $big_title;?></div>
<?php endif; ?>
<?php
$separatorHtml = '<div class="'.$new_css_class.'"><span class="vc_sep_holder vc_sep_holder_l"><span '.$inline_css.' class="vc_sep_line"></span></span>'.$content_o.'<span class="vc_sep_holder vc_sep_holder_r"><span '.$inline_css.' class="vc_sep_line"></span></span>
</div>';
echo $separatorHtml;
if('' !== $big_title){
	echo '</div>';
}
