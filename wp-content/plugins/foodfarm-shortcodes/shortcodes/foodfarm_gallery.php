<?php

// foodfarm_gallery
add_shortcode('foodfarm_gallery', 'foodfarm_shortcode_gallery');
add_action('vc_build_admin_page', 'foodfarm_load_gallery_shortcode');
add_action('vc_after_init', 'foodfarm_load_gallery_shortcode');

function foodfarm_shortcode_gallery($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_gallery'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_gallery_shortcode() {
    $custom_class = foodfarm_vc_custom_class();
    $order_by_values = foodfarm_vc_woo_order_by();
    $order_way_values = foodfarm_vc_woo_order_way();
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Gallery', 'foodfarm'),
        'base' => 'foodfarm_gallery',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("First Title", "foodfarm"),
                "param_name" => "first_title",
                "value" => "Our",
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Last Title", "foodfarm"),
                "param_name" => "last_title",
                "value" => "Gallery",
                "admin_label" => true
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Type Icon Title", 'foodfarm'),
                "param_name" => "icon_title",
                'std' => 'icon_1',
                'value' => array(
                    esc_html__('Icon 1', 'foodfarm') => 'icon_1',
                    esc_html__('Icon 2', 'foodfarm') => 'icon_2',
                ),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Number of gallery to show", "foodfarm"),
                "param_name" => "number",
                "value" => "8",
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Category IDs", "foodfarm"),
                "description" => esc_html__("comma separated list of category ids", "foodfarm"),
                "param_name" => "cat",
                "admin_label" => true
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Layout", 'foodfarm'),
                "param_name" => "layout",
                'std' => 'layout_1',
                'value' => array(
                    esc_html__('Layout 1', 'foodfarm') => 'layout_1',
                    esc_html__('Layout 2', 'foodfarm') => 'layout_2',
                ),
            ),
            array(
                'type' => 'textarea_html',
                'heading' => esc_html__('Description', 'foodfarm'),
                'param_name' => 'content',
                'dependency' => array(
                    'element' => 'layout',
                    'value' => array('layout_2')
                )
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Gallery Columns", 'foodfarm'),
                "param_name" => "columns",
                'std' => 3,
                'value' => array(
                    esc_html__('2 Columns', 'foodfarm') => '2',
                    esc_html__('3 Columns', 'foodfarm') => '3',
                    esc_html__('4 Columns', 'foodfarm') => '4',
                ),
            ),
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Show view more", 'foodfarm'),
                "param_name" => "show_viewmore",
                'value' => array(esc_html__('Yes', 'foodfarm') => 'yes')
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

    if (!class_exists('WPBakeryShortCode_Foodfarm_Gallery')) {
        class WPBakeryShortCode_Foodfarm_Gallery extends WPBakeryShortCode {
        }
    }
}