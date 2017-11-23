<?php

// foodfarm_latest_posts
add_shortcode('foodfarm_testimonial', 'foodfarm_shortcode_testimonial');
add_action('vc_build_admin_page', 'foodfarm_load_testimonial_shortcode');
add_action('vc_after_init', 'foodfarm_load_testimonial_shortcode');

function foodfarm_shortcode_testimonial($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_testimonial'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_testimonial_shortcode() {
    $animation_type = foodfarm_vc_animation_type();
    $custom_class = foodfarm_vc_custom_class();
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Testimonial', 'foodfarm'),
        'base' => 'foodfarm_testimonial',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("First Title", "foodfarm"),
                "param_name" => "first_title",
                "value" => "Happy",
                "admin_label" => true,
                'dependency' => array(
                    'element' => 'layout',
                    'value' => array('slide')
                )
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Last Title", "foodfarm"),
                "param_name" => "last_title",
                "value" => "Client",
                "admin_label" => true,
                'dependency' => array(
                    'element' => 'layout',
                    'value' => array('slide')
                )
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Layout", 'foodfarm'),
                "param_name" => "layout",
                'std' => 'testimonial',
                'value' => array(
                    esc_html__('Testimonial', 'foodfarm') => 'slide',
                    esc_html__('Testimonial 2', 'foodfarm') => 'slide2',
                    esc_html__('Testimonial 3', 'foodfarm') => 'slide3',
                    esc_html__('Testimonial 4', 'foodfarm') => 'slide4',
                    esc_html__('Testimonial 5', 'foodfarm') => 'slide5',
                ),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Slug of testimonials", "foodfarm"),
                "description" => esc_html__("Enter slug of posts you want to display (comma separated list of slugs", "foodfarm"),
                "param_name" => "slug",
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__('Title Color', 'foodfarm'),
                'param_name' => 'title_color',
                'admin_label' => true
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__('Icon Color', 'foodfarm'),
                'param_name' => 'icon_color',
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'layout',
                    'value' => array('slide','slide2','slide3'),
                ),                  
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__('Text Color', 'foodfarm'),
                'param_name' => 'text_color',
                'admin_label' => true
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Posts Count", "foodfarm"),
                "param_name" => "number",
                "value" => "3",
                "admin_label" => true
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Block Align", 'foodfarm'),
                "param_name" => "block_align",
                'std' => 'center',
                'value' => array(
                    esc_html__('Center', 'foodfarm') => 'center',
                    esc_html__('Left', 'foodfarm') => 'left',
                    esc_html__('Right', 'foodfarm') => 'right',
                ),
                'dependency' => array(
                    'element' => 'layout',
                    'value' => array('slide','slide5')
                )
            ),
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Show Name Author", 'foodfarm'),
                "param_name" => "show_name",
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'js_composer') => 'yes')
            ),  
            array(
                'type' => 'checkbox',
                'heading' => esc_html__("Item delay", "foodfarm"),
                'param_name' => 'item_delay',
                'std' => 'yes',
                'value' => array( esc_html__( 'Yes', 'foodfarm' ) => 'yes' )
            ),
            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_Testimonial')) {
        class WPBakeryShortCode_Foodfarm_Testimonial extends WPBakeryShortCode {
        }
    }
}