<?php

add_shortcode('foodfarm_brands', 'foodfarm_shortcode_brands');
add_action('vc_build_admin_page', 'foodfarm_load_brands_shortcode');
add_action('vc_after_init', 'foodfarm_load_brands_shortcode');

function foodfarm_shortcode_brands($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_brands'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_brands_shortcode() {
    $custom_class = foodfarm_vc_custom_class();

    vc_map( array(
        'name' => esc_html__('Foodfarm Brands', 'foodfarm'),
        'base' => 'foodfarm_brands',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("Posts Count", "foodfarm"),
                "param_name" => "number",
                "value" => "18",
                "admin_label" => true
            ),
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Change the color of all images to white?", 'foodfarm'),
                "param_name" => "image_filter",
                'value' => array(esc_html__('Yes', 'foodfarm') => 'yes')
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 ult_margin_bottom",
                "heading" => esc_html__("Items On Desktop", "foodfarm"),
                "param_name" => "slides_on_desk",
                "value" => "7",
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 ult_margin_bottom",
                "heading" => esc_html__("Items On Tabs", "foodfarm"),
                "param_name" => "slides_on_tabs",
                "value" => "5",
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 ult_margin_bottom",
                "heading" => esc_html__("Items On Mobile", "foodfarm"),
                "param_name" => "slides_on_mob",
                "value" => "3",
            ),
            array(
                "type" => "number",
                "class" => "",
                "edit_field_class" => "vc_col-sm-4 ult_margin_bottom",
                "heading" => esc_html__("Items On Mobile Small", "foodfarm"),
                "param_name" => "slides_on_mob_small",
                "value" => "2",
            ),

            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_Brands')) {
        class WPBakeryShortCode_Foodfarm_Brands extends WPBakeryShortCode {
        }
    }
}