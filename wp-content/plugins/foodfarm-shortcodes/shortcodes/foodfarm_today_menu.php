<?php

// foodfarm_recipes
add_shortcode('foodfarm_today_menu', 'foodfarm_shortcode_today_menu');
add_action('vc_build_admin_page', 'foodfarm_load_today_menu_shortcode');
add_action('vc_after_init', 'foodfarm_load_today_menu_shortcode');

function foodfarm_shortcode_today_menu($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_today_menu'))
        include $template;
    return ob_get_clean();
}
function foodfarm_load_today_menu_shortcode() {
    $custom_class = foodfarm_vc_custom_class();
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Today Menu', 'foodfarm'),
        'base' => 'foodfarm_today_menu',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("First Title", "foodfarm"),
                "param_name" => "first_title",
                "value" => "Recipes",
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Last Title", "foodfarm"),
                "param_name" => "last_title",
                "value" => " of The Day",
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
                "type" => "textarea",
                "heading" => esc_html__("Description", 'foodfarm'),
                "param_name" => "description",
                'description' => esc_html__('Enter short description', 'foodfarm')
            ),
             array(
                "type" => "textfield",
                "heading" => esc_html__("Sale", "foodfarm"),
                "param_name" => "sale",
                "value" => "30%",
                "admin_label" => true
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Number of today menu to show", "foodfarm"),
                "param_name" => "number",
                "value" => "6",
                "admin_label" => true
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Today Menu Columns", 'foodfarm'),
                "param_name" => "columns",
                'std' => 4,
                'value' => array(
                    esc_html__('2 Columns', 'foodfarm') => '2',
                    esc_html__('3 Columns', 'foodfarm') => '3',
                    esc_html__('4 Columns', 'foodfarm') => '4',
                ),
            ),
            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_Recipes')) {
        class WPBakeryShortCode_Foodfarm_Recipes extends WPBakeryShortCode {
        }
    }
}