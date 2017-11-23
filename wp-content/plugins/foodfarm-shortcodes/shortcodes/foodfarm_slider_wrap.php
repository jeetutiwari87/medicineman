<?php

// foodfarm_slider_wrap
add_shortcode('foodfarm_slider_wrap', 'foodfarm_shortcode_slider_wrap');
add_action('vc_build_admin_page', 'foodfarm_load_slider_wrap');
add_action('vc_after_init', 'foodfarm_load_slider_wrap');

function foodfarm_shortcode_slider_wrap($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_slider_wrap'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_slider_wrap() {
    $custom_class = foodfarm_vc_custom_class();
    $animation_type = foodfarm_animation_custom();
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Slider Wrap', 'foodfarm'),
        'base' => 'foodfarm_slider_wrap',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_container',
        'is_container' => true,
        'js_view' => 'VcColumnView',
        'weight' => - 50,
        "params" => array(
			array(
                "type" => "checkbox",
                "heading" => esc_html__("Auto Play", 'foodfarm-shortcodes'),
                "param_name" => "auto_play",
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'js_composer') => 'yes')
            ), 
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Dots Navigation", 'foodfarm-shortcodes'),
                "param_name" => "show_dot",
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'js_composer') => 'yes')
            ), 
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Dots style", "foodfarm-shortcodes"),
                "param_name" => "dot_style",
                'std' => '2',
                'value' => array(
                    esc_html__('Bottom', 'foodfarm-shortcodes') => 'dot_style1',
                    esc_html__('Left', 'foodfarm-shortcodes') => 'dot_style2',
                ),
            ),             
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Navigation Arrows", 'foodfarm-shortcodes'),
                "param_name" => "show_nav",
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'js_composer') => 'yes')
            ),
            $custom_class,
            array(
                'type' => 'css_editor',
                'heading' => esc_html__( 'Css','foodfarm-shortcodes' ),
                'param_name' => 'css',
                'group' => esc_html__( 'Design Option','foodfarm-shortcodes' ),
            )            
        )
    ) );

    if (!class_exists('WPBakeryShortCode_foodfarm_slider_wrap')) {
        class WPBakeryShortCode_foodfarm_slider_wrap extends WPBakeryShortCodesContainer {
        }
    }
}