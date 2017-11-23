<?php
$output = $el_class = $layout = $dot_style = '';
extract(shortcode_atts(array(
	'show_dot' => 'yes',
    'show_nav' => 'yes',
    'auto_play' => 'yes',
    'dot_style'	=> '',
    'el_class' => '',
    'css' => ''
                ), $atts));
$slide_class = 'slider_wrap' . wp_rand();
$el_class = foodfarm_shortcode_extract_class($el_class);
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'foodfarm_slider_wrap', $atts );
ob_start();
?>
	<?php 
	$output = '<div class="slider-wrap-container ' . esc_html($el_class) . '"';
	$output .= '>';
	?>
	<div id="<?php echo esc_js($slide_class); ?>" class="slider_wrap owl-carousel <?php echo esc_attr($dot_style);?>" data-max-items = 1>
	  <?php echo do_shortcode($content); ?>
	</div>
	<script type="text/javascript">
        jQuery(function ($) {
            $(document).ready(function(){
				var owl = $("#<?php echo esc_js($slide_class); ?>");
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
					items: 1
				}); //end: owl
			});
        });
    </script>		
<?php
$output .= ob_get_clean();
$output .= '</div>' . foodfarm_shortcode_end_block_comment('foodfarm_slider_wrap') . "\n";

echo $output;


wp_reset_postdata();
