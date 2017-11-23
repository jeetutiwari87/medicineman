<?php

// foodfarm_products_category
add_shortcode('foodfarm_products_category', 'foodfarm_shortcode_products_category');
add_action('vc_build_admin_page', 'foodfarm_load_products_category_shortcode');
add_action('vc_after_init', 'foodfarm_load_products_category_shortcode');

function foodfarm_shortcode_products_category($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_products_category'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_products_category_shortcode() {
    $animation_type = foodfarm_vc_animation_type();
    $animation_duration = foodfarm_vc_animation_duration();
    $animation_delay = foodfarm_vc_animation_delay();
    $custom_class = foodfarm_vc_custom_class();
    $order_by_values = foodfarm_vc_woo_order_by();
    $order_way_values = foodfarm_vc_woo_order_way();
    $args = array(
        'type' => 'post',
        'child_of' => 0,
        'parent' => '',
        'orderby' => 'id',
        'order' => 'ASC',
        'hide_empty' => false,
        'hierarchical' => 1,
        'exclude' => '',
        'include' => '',
        'number' => '',
        'taxonomy' => 'product_cat',
        'pad_counts' => false,

    );
    $categories = get_categories( $args );
    $product_categories_dropdown = array(
        esc_html__('Select', 'foodfarm') => ''
    );
    if (class_exists('Vc_Vendor_Woocommerce')) {
        $vc_vendor_wc = new Vc_Vendor_Woocommerce();
        $vc_vendor_wc->getCategoryChilds( 0, 0, $categories, 0, $product_categories_dropdown );
    }
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Products Category', 'foodfarm'),
        'base' => 'foodfarm_products_category',
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
                "type" => "textfield",
                "heading" => esc_html__("Number of products_category to show", "foodfarm"),
                "param_name" => "number",
                "value" => "8",
                "admin_label" => true
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( esc_html__( 'Select how to sort retrieved products_category. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Category', 'js_composer' ),
                'value' => $product_categories_dropdown,
                'param_name' => 'category',
                'description' => esc_html__( 'Product category list', 'js_composer' ),
                'admin_label' => true
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 items_to_show ult_margin_bottom",
                "heading" => esc_html__("Items On Large Desktop", "js_composer"),
                "param_name" => "slides_on_desk",
                "value" => "5",
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 items_to_show ult_margin_bottom",
                "heading" => esc_html__("Items On Tabs & Desktop", "js_composer"),
                "param_name" => "slides_on_tabs",
                "value" => "3",
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 items_to_show ult_margin_bottom",
                "heading" => esc_html__("Items On Mobile", "js_composer"),
                "param_name" => "slides_on_mob",
                "value" => "2",
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 items_to_show ult_margin_bottom",
                "heading" => esc_html__("Items On Mobile Small", "js_composer"),
                "param_name" => "slides_on_mob_small",
                "value" => "1",
            ), 
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Show Pagination", 'foodfarm'),
                "param_name" => "show_dot",
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'js_composer') => 'yes')
            ), 
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Show Navigation", 'foodfarm'),
                "param_name" => "show_nav",
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'js_composer') => 'yes')
            ),  
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Style Navigation", 'foodfarm'),
                "param_name" => "style_navigation",
                'std' => 'left',
                'value' => array(
                    esc_html__('Postion Top', 'foodfarm') => 'style_top',
                    esc_html__('Postion Middle', 'foodfarm') => 'style_middle',
                ),
                'dependency' => array(
                    'element' => 'show_nav',
                    'value' => 'yes'
                )
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

    if (!class_exists('WPBakeryShortCode_Foodfarm_Products_Category')) {
        class WPBakeryShortCode_Foodfarm_Products_Category extends WPBakeryShortCode {
        }
    }
}