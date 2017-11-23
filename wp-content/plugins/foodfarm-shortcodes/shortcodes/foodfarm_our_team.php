<?php

// foodfarm_our_team
add_shortcode('foodfarm_our_team', 'foodfarm_shortcode_our_team');
add_action('vc_build_admin_page', 'foodfarm_load_our_team_shortcode');
add_action('vc_after_init', 'foodfarm_load_our_team_shortcode');

function foodfarm_shortcode_our_team($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_our_team'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_our_team_shortcode() {
    $animation_type = foodfarm_vc_animation_type();
    $custom_class = foodfarm_vc_custom_class();

    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Our Team', 'foodfarm'),
        'base' => 'foodfarm_our_team',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "textarea_html",
                "heading" => esc_html__("Title", 'foodfarm'),
                "param_name" => "content",
                'description' => esc_html__('Set the title for our team block', 'foodfarm')
            ),
            array(
                "type" => "number",
                "heading" => esc_html__("Number of post entries per page", 'foodfarm'),
                "param_name" => "number",
                "value" => esc_html__(3, 'foodfarm'),
                'description' => esc_html__('Set the total number of entries to show per page', 'foodfarm')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Layout", 'foodfarm'),
                "param_name" => "layout",
                'std' => 'grid',
                'value' => array(
                    esc_html__('List Image', 'foodfarm') => 'list',
                    esc_html__('Grid', 'foodfarm') => 'grid',
                ),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Category IDs", "foodfarm"),
                "description" => esc_html__("comma separated list of category ids", "foodfarm"),
                "param_name" => "cat",
                "admin_label" => true,
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Items to show on Desktop", "foodfarm"),
                "param_name" => "items_desktop",
                'std' => 3,
                'value' => array(
                    esc_html__('4', 'foodfarm') => 4,
                    esc_html__('3', 'foodfarm') => 3,
                    esc_html__('2', 'foodfarm') => 2,
                    esc_html__('1', 'foodfarm') => 1,
                ),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Items to show on Tablets", "foodfarm"),
                "param_name" => "items_tablets",
                'std' => 2,
                'value' => array(
                    esc_html__('4', 'foodfarm') => 4,
                    esc_html__('3', 'foodfarm') => 3,
                    esc_html__('2', 'foodfarm') => 2,
                    esc_html__('1', 'foodfarm') => 1,
                ),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Items to show on Mobile", "foodfarm"),
                "param_name" => "items_mobile",
                'std' => 1,
                'value' => array(
                    esc_html__('4', 'foodfarm') => 4,
                    esc_html__('3', 'foodfarm') => 3,
                    esc_html__('2', 'foodfarm') => 2,
                    esc_html__('1', 'foodfarm') => 1,
                ),
            ),
            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_Our_Team')) {
        class WPBakeryShortCode_Foodfarm_Our_Team extends WPBakeryShortCode {
        }
    }
}