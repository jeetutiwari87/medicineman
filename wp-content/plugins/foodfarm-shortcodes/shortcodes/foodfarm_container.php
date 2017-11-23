<?php

// foodfarm Container
add_shortcode('foodfarm_container', 'foodfarm_shortcode_container');
add_action('vc_build_admin_page', 'foodfarm_load_container_shortcode');
add_action('vc_after_init', 'foodfarm_load_container_shortcode');

function foodfarm_shortcode_container($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_container'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_container_shortcode() {
    $custom_class = foodfarm_vc_custom_class();
    vc_map( array(
        "name" => "Foodfarm " . esc_html__("Container", 'foodfarm'),
        "base" => "foodfarm_container",
        "category" => esc_html__("Foodfarm", 'foodfarm'),
        "icon" => "foodfarm_vc_container",
        'is_container' => true,
        'weight' => - 50,
        "show_settings_on_create" => false,
        'js_view' => 'VcColumnView',
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Container Type", 'foodfarm'),
                "param_name" => "container_type",
                'std' => 1,
                'value' => array(
                    esc_html__('Container', 'foodfarm') => '1',
                    esc_html__('Container Fluid', 'foodfarm') => '2',
                    esc_html__('Wide container', 'foodfarm') => '3',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => esc_html__("Item delay", "foodfarm"),
                'param_name' => 'item_delay',
                'value' => array( esc_html__( 'Yes', 'foodfarm' ) => 'yes' )
            ),
            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_Container')) {
        class WPBakeryShortCode_Foodfarm_Container extends WPBakeryShortCodesContainer {
        }
    }
}