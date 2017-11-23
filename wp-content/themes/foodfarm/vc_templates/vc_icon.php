<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $content 
 * @var $type
 * @var $icon_fontawesome
 * @var $icon_openiconic
 * @var $icon_typicons
 * @var $icon_entypo
 * @var $icon_linecons
 * @var $color
 * @var $custom_color
 * @var $background_style
 * @var $background_color
 * @var $custom_background_color
 * @var $size
 * @var $align
 * @var $el_class
 * @var $link
 * @var $css_animation
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Icon
 */
$type = $icon_hover = $icon_fontawesome = $icon_openiconic = $icon_typicons =
$icon_entypo = $icon_linecons = $color = $custom_color =
$background_style = $background_color = $custom_background_color =
$size = $align = $image_icon = $el_class = $link = $css_animation = $layout_style = $css = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

// Enqueue needed icon font.
vc_icon_element_fonts_enqueue( $type );

$url = vc_build_link( $link );
$has_style = false;
if ( strlen( $background_style ) > 0 ) {
	$has_style = true;
	if ( false !== strpos( $background_style, 'outline' ) ) {
		$background_style .= ' vc_icon_element-outline'; // if we use outline style it is border in css
	} else {
		$background_style .= ' vc_icon_element-background';
	}
}

$iconClass = isset( ${'icon_' . $type} ) ? esc_attr( ${'icon_' . $type} ) : 'fa fa-adjust';
$iconTag = "";
if($icon_foodfarmfont =='icon-2'){
	$iconTag.= '';
}elseif($icon_foodfarmfont =='icon-4'){
	$iconTag.= '';
}
$style = '';
if ( 'custom' === $background_color ) {
	if ( false !== strpos( $background_style, 'outline' ) ) {
		$style = 'border-color:' . $custom_background_color.'; ';
	} else {
		$style = 'background-color:' . $custom_background_color.'; ';
	}
}
if($icon_border_style != "none"){
	$style .= 'border-style:' . $icon_border_style;
}
$style = $style ? ' style="' . esc_attr( $style ) . '"' : '';
$cus_style = "";
$i_size_class="";
if('custom' === $color){
	$cus_style .= 'style="color:'. esc_attr( $custom_color ). '!important;';
	if($size != ""){
		if (strcspn($size, '0123456789') != strlen($size)){
			$cus_style .= ' font-size:'. esc_attr( $size ). 'px';
		}else{
			$i_size_class .= 'vc_icon_element-size-'.esc_attr( $size );
		}
	}
	$cus_style .='"';
}else{
	if($size != ""){
		if (strcspn($size, '0123456789') != strlen($size)){
			$cus_style .= 'style=" font-size:'. esc_attr( $size ). 'px"';
		}else{
			$i_size_class .= 'vc_icon_element-size-'.esc_attr( $size );
		}
	}
}
if($layout_style =='style2'){
	$css_class .= ' ff_icon_style2';
}
$desc = foodfarm_shortcode_js_remove_wpautop($content, true);
$bgImage = wp_get_attachment_url($image_icon);
?>

<div
	class="vc_icon_element vc_icon_element-outer<?php echo strlen( $css_class ) > 0 ? ' ' . trim( esc_attr( $css_class ) ) : ''; ?> vc_icon_element-align-<?php echo esc_attr( $align ); ?><?php if ( $has_style ) { echo ' vc_icon_element-have-style'; } ?> <?php if ( $content ) { echo 'about-icon'; } ?> <?php if($icon_hover != true){echo 'hover-off'; } ?>">
	<?php if($desc != ''):?>
		<div class="icon">
	<?php endif;?>
	<?php if($bgImage):?>
		<?php if ( strlen( $link ) > 0 && strlen( $url['url'] ) > 0 ) {
				echo '<a class="vc_icon_element-link" href="' . esc_attr( $url['url'] ) . '" title="' . esc_attr( $url['title'] ) . '" target="' . ( strlen( $url['target'] ) > 0 ? esc_attr( $url['target'] ) : '_self' ) . '">';
			}?>
		<img src="<?php echo esc_url($bgImage);?>" alt="img">
		</a>
	<?php else:?>
	<div class="vc_icon_element-inner vc_icon_element-color-<?php echo esc_attr( $color ); ?><?php if ( $has_style ) { echo ' vc_icon_element-have-style-inner'; } ?> <?php echo esc_attr($i_size_class);?> vc_icon_element-style-<?php echo esc_attr( $background_style ); ?> vc_icon_element-background-color-<?php echo esc_attr( $background_color ); ?>"<?php echo $style ?>><span class="vc_icon_element-icon <?php echo esc_attr($iconClass); ?>" <?php echo $cus_style; ?>><?php echo $iconTag; ?></span><?php
			if ( strlen( $link ) > 0 && strlen( $url['url'] ) > 0 ) {
				echo '<a class="vc_icon_element-link" href="' . esc_attr( $url['url'] ) . '" title="' . esc_attr( $url['title'] ) . '" target="' . ( strlen( $url['target'] ) > 0 ? esc_attr( $url['target'] ) : '_self' ) . '"></a>';
			}
		?></div>
	<?php endif;?>
	<?php if($desc != ''):?>
		</div>
	<?php endif;?>
	<?php if($desc != ''):?>
		<div class="desc">
			<?php echo $desc; ?>
		</div>
	<?php endif;?>
</div>
