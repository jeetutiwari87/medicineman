<?php

// foodfarm instagram feed
add_shortcode('foodfarm_instagram_feed', 'foodfarm_shortcode_instagram_feed');
add_action('vc_build_admin_page', 'foodfarm_load_instagram_feed_shortcode');
add_action('vc_after_init', 'foodfarm_load_instagram_feed_shortcode');

function foodfarm_shortcode_instagram_feed($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_instagram_feed'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_instagram_feed_shortcode() {
    $custom_class = foodfarm_vc_custom_class();
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Instagram Feed', 'foodfarm'),
        'base' => 'foodfarm_instagram_feed',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("Per page", 'foodfarm'),
                "param_name" => "per_page",
                "value" => esc_html__("12", 'foodfarm'),
                'description' => esc_html__('This field  determines how many blogs to show on the page', 'foodfarm')
            ),
            array(
                'type' => 'checkbox',
                'heading' => esc_html__("Show Text", "foodfarm"),
                'param_name' => 'show_text',
                'std' => 'yes',
                'value' => array( esc_html__( 'Yes', 'foodfarm' ) => 'yes' )
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Name account", 'foodfarm'),
                "param_name" => "name_account",
                'dependency' => array(
                    'element' => 'show_text',
                    'value' => 'yes'
                ),
                'description' => esc_html__('Example: arrowpress', 'foodfarm')
            ),
            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_Instagram_Feed')) {
        class WPBakeryShortCode_Foodfarm_Instagram_Feed extends WPBakeryShortCode {
        }
    }
}