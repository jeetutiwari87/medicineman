<?php

// foodfarm_product_categories
add_shortcode('foodfarm_product_categories', 'foodfarm_shortcode_product_categories');
add_action('vc_build_admin_page', 'foodfarm_load_product_categories_shortcode');
add_action('vc_after_init', 'foodfarm_load_product_categories_shortcode');

function foodfarm_shortcode_product_categories($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_product_categories'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_product_categories_shortcode() {
    $custom_class = foodfarm_vc_custom_class();
    $order_by_values = foodfarm_vc_woo_order_by();
    $order_way_values = foodfarm_vc_woo_order_way();
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Product categories', 'foodfarm-shortcodes'),
        'base' => 'foodfarm_product_categories',
        'category' => esc_html__('Foodfarm', 'foodfarm-shortcodes'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array( 
			// array(
   //              'type' => 'dropdown',
   //              'heading' => __( 'Shortcodes Layout', 'foodfarm-shortcodes' ),
   //              'param_name' => 'shortcodes_layout',
   //              'std' => '',
			// 	'value' => array(
   //                  esc_html__('Rencent Products', 'foodfarm-shortcodes') => 'recent_products',
   //                  esc_html__('Featured Products', 'foodfarm-shortcodes') => 'featured_products',
   //                  esc_html__('Best-Selling Products', 'foodfarm-shortcodes') => 'best_selling_products',
   //                  esc_html__('Top Rated Products', 'foodfarm-shortcodes') => 'top_rated_products',
   //                  esc_html__('Sale Products', 'foodfarm-shortcodes') => 'sale_products',
   //              ),
   //              "admin_label" => true,
   //          ),
            array(
                'type' => 'number',
                'heading' => esc_html__( 'Number', 'foodfarm-shortcodes' ),
                'value' => 12,
                'param_name' => 'number',
                'description' => esc_html__( 'The `number` field is used to display the number of categories.', 'foodfarm-shortcodes' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Columns', 'foodfarm-shortcodes' ),
                'param_name' => 'columns',
                'std' => '4',
                'value' => array(
                    esc_html__('2 Columns', 'foodfarm') => '2',
                    esc_html__('3 Columns', 'foodfarm') => '3',
                    esc_html__('4 Columns', 'foodfarm') => '4',
                    esc_html__('5 Columns', 'foodfarm') => '5',
                    esc_html__('6 Columns', 'foodfarm') => '6',
                ),
				'admin_label' => true,
            ),
			array(
                "type" => "textfield",
                "heading" => esc_html__("Slug Name of parent category", "foodfarm-shortcodes"),
                "param_name" => "parent",
                "value" => '',
                "admin_label" => true,
                'description' => esc_html__( 'Enter slug name of parent category to get all child categories.', 'foodfarm-shortcodes' ),                
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("ID of excluded categories", "foodfarm-shortcodes"),
                "param_name" => "ex_cat",
                "value" => '',
                'description' => esc_html__( 'Enter ID of categories you want to hide (seperate each ID by comma)', 'foodfarm-shortcodes' ),                
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'foodfarm-shortcodes' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'foodfarm-shortcodes' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'foodfarm-shortcodes' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'foodfarm-shortcodes' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
			array(
                "type" => "checkbox",
                "heading" => esc_html__("Show button", 'foodfarm-shortcodes'),
                "param_name" => "view_more",
				'std' => 'yes',
                'value' => array(esc_html__('Yes', 'foodfarm-shortcodes') => 'yes'),       
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Hide Empty', 'foodfarm-shortcodes' ),
                'param_name' => 'hide_empty',
                'std' => 'yes',
                'value' => array(
                    esc_html__('Yes', 'foodfarm') => 'yes',
                    esc_html__('No', 'foodfarm') => 'no',
                ),                
                'description' => esc_html__( 'Hide empty cateogries','foodfarm' ),
            ),  
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Display product count', 'foodfarm-shortcodes' ),
                'param_name' => 'pad_count',
                'std' => 'yes',
                'value' => array(
                    esc_html__('Yes', 'foodfarm') => 'yes',
                    esc_html__('No', 'foodfarm') => 'no',
                ),                
            ),  
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Display image', 'foodfarm-shortcodes' ),
                'param_name' => 'dis_img',
                'std' => 'yes',
                'value' => array(
                    esc_html__('Yes', 'foodfarm') => 'yes',
                    esc_html__('No', 'foodfarm') => 'no',
                ),                
            ),                               
            array(
                "type" => "textfield",
                "heading" => esc_html__("Button text", 'foodfarm-shortcodes'),
                "param_name" => "btn_text",
                "value" => "",                
            ),                        
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__('Title Color', 'foodfarm'),
                'param_name' => 'title_color',
                'group'   => 'Style',
            ),
            array(
                "type" => "colorpicker",
                "heading" => esc_html__("Background color", 'foodfarm'),
                "param_name" => "bg_color",
                'group'   => 'Style',
            ), 
            array(
                "type" => "colorpicker",
                "heading" => esc_html__("Background hover color", 'foodfarm'),
                "param_name" => "bg_hcolor",
                'group'   => 'Style',
            ),                                 
            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_Product_Categories')) {
        class WPBakeryShortCode_Foodfarm_Product_Categories extends WPBakeryShortCode {
        }
    }
}