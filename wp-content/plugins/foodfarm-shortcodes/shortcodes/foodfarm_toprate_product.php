<?php

// foodfarm_toprate_product
add_shortcode('foodfarm_toprate_product', 'foodfarm_shortcode_toprate_product');
add_action('vc_build_admin_page', 'foodfarm_load_toprate_product_shortcode');
add_action('vc_after_init', 'foodfarm_load_toprate_product_shortcode');

function foodfarm_shortcode_toprate_product($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_toprate_product'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_toprate_product_shortcode() {
    $custom_class = foodfarm_vc_custom_class();

    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Top Rate Product', 'foodfarm'),
        'base' => 'foodfarm_toprate_product',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("Number of toprate_product to show", "foodfarm"),
                "param_name" => "number",
                "value" => "6",
                "admin_label" => true
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 items_to_show ult_margin_bottom",
                "heading" => esc_html__("Items On Desktop Large", "foodfarm"),
                "param_name" => "slides_on_desk",
                "value" => "6",
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 items_to_show ult_margin_bottom",
                "heading" => esc_html__("Items On Desktop", "foodfarm"),
                "param_name" => "slides_on_tabs",
                "value" => "4",
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 items_to_show ult_margin_bottom",
                "heading" => esc_html__("Items On Mobile", "foodfarm"),
                "param_name" => "slides_on_mob",
                "value" => "2",
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 items_to_show ult_margin_bottom",
                "heading" => esc_html__("Items On Mobile Small", "foodfarm"),
                "param_name" => "slides_on_mob_small",
                "value" => "1",
            ),     
            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_Toprate_Product')) {
        class WPBakeryShortCode_Foodfarm_Toprate_Product extends WPBakeryShortCode {
        }
    }
}