<?php

// foodfarm_product_list
add_shortcode('foodfarm_product_list', 'foodfarm_shortcode_product_list');
add_action('vc_build_admin_page', 'foodfarm_load_product_list_shortcode');
add_action('vc_after_init', 'foodfarm_load_product_list_shortcode');

function foodfarm_shortcode_product_list($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_product_list'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_product_list_shortcode() {
    $custom_class = foodfarm_vc_custom_class();
	$order_by_values = foodfarm_vc_woo_order_by();
    $order_way_values = foodfarm_vc_woo_order_way();
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Product Small List', 'foodfarm'),
        'base' => 'foodfarm_product_list',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Product Type", 'foodfarm'),
                "param_name" => "product_type",
                'std' => 'new_products',
                'value' => array(
                    esc_html__('Recent Products', 'foodfarm') => 'new_products',
                    esc_html__('On Sale', 'foodfarm') => 'on_sale',
                    esc_html__('Featured', 'foodfarm') => 'featured',
                    esc_html__('Top Rated', 'foodfarm') => 'top_rated',
                )
            ),
			array(
                "type" => "number",
                "heading" => esc_html__("Number of product to show", "foodfarm"),
                "param_name" => "number",
                "value" => 4,
                "admin_label" => true
            ),   
            array(
                "type" => "textfield",
                "heading" => esc_html__("Slug Name of parent category", "foodfarm"),
                "param_name" => "slug_name",
                "value" => "",
                "admin_label" => true,
            ),
			array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order way', 'js_composer' ),
                'param_name' => 'order_way',
                'value' => $order_way_values,
                'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
			array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order by', 'js_composer' ),
                'param_name' => 'order_by',
                'value' => $order_by_values,
                'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
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

    if (!class_exists('WPBakeryShortCode_Foodfarm_Product_Slide')) {
        class WPBakeryShortCode_Foodfarm_Product_Slide extends WPBakeryShortCode {
        }
    }
}