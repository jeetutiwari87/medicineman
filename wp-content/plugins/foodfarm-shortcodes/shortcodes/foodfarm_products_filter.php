<?php

// foodfarm_products_filter
add_shortcode('foodfarm_products_filter', 'foodfarm_shortcode_products_filter');
add_action('vc_build_admin_page', 'foodfarm_load_products_filter_shortcode');
add_action('vc_after_init', 'foodfarm_load_products_filter_shortcode');

function foodfarm_shortcode_products_filter($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_products_filter'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_products_filter_shortcode() {
    $animation_type = foodfarm_vc_animation_type();
    $animation_duration = foodfarm_vc_animation_duration();
    $animation_delay = foodfarm_vc_animation_delay();
    $custom_class = foodfarm_vc_custom_class();
    $order_way_values = foodfarm_vc_woo_order_way();

    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Products Filter', 'foodfarm'),
        'base' => 'foodfarm_products_filter',
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
                "value" => "Products",
                "admin_label" => true
            ),
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Show title", 'foodfarm'),
                "param_name" => "show_title",
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'foodfarm') => 'yes')
            ),            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Product style", 'foodfarm'),
                "param_name" => "product_style",
                'std' => 'default',
                'value' => array(
                    esc_html__('Default', 'foodfarm') => 'default',
                    esc_html__('Style 2', 'foodfarm') => 'product_type_2',
                    esc_html__('Style 3', 'foodfarm') => 'product_type_3',
                ),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("ID Category Parent", "foodfarm"),
                "param_name" => "category_parent",
                "value" => 0,
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("ID excluded categories", "foodfarm"),
                "param_name" => "exclude_cat",
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Slug Name Category", "foodfarm"),
                "param_name" => "slug_name",
                "value" => "",
                "admin_label" => true,
            ),            
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            // Show filter
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Show filter", 'foodfarm'),
                "param_name" => "show_filter",
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'js_composer') => 'yes')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Filter Layout", 'foodfarm'),
                "param_name" => "filter_layout",
                'std' => '1',
                'value' => array(
                    esc_html__('Tab filter layout 1', 'foodfarm') => '1',
                    esc_html__('Tab filter layout 2', 'foodfarm') => '2',
                    esc_html__('Tab filter layout 3', 'foodfarm') => '3',
                    esc_html__('Tab filter layout 4', 'foodfarm') => '4',
                    esc_html__('Tab filter layout 5 (Show filter icon)', 'foodfarm') => '5',
                ),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Number of products_filter to show", "foodfarm"),
                "param_name" => "number",
                "value" => "8",
                "admin_label" => true
            ),
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Show loadmore button", 'foodfarm'),
                "param_name" => "load_more",
                'std' => 'no',
                'value' => array(esc_html__('Yes', 'foodfarm') => 'yes')
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Button text", "foodfarm"),
                "param_name" => "btn_text",
                "value" => "Load More",
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

    if (!class_exists('WPBakeryShortCode_Foodfarm_Products_Filter')) {
        class WPBakeryShortCode_Foodfarm_Products_Filter extends WPBakeryShortCode {
        }
    }
}