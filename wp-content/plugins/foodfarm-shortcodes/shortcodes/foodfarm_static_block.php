<?php

// foodfarm_static_block
add_shortcode('foodfarm_static_block', 'foodfarm_shortcode_static_block');
add_action('vc_build_admin_page', 'foodfarm_load_static_block_shortcode');
add_action('vc_after_init', 'foodfarm_load_static_block_shortcode');
function foodfarm_shortcode_static_block($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_static_block'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_static_block_shortcode() {
    $custom_class = foodfarm_vc_custom_class();
    $block_options = array();
    $block_options[0] = esc_html__('Choose a block to display', 'foodfarm');
    $args = array(
        'numberposts'       => -1,
        'post_type'         => 'block',
        'post_status'       => 'publish',
    );
    $posts = get_posts($args);
    foreach( $posts as $_post ){
        $block_options[$_post->post_title] = $_post->post_title;
    }
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Static Block', 'foodfarm'),
        'base' => 'foodfarm_static_block',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Static Block", 'foodfarm'),
                "param_name" => "static",
                'value' =>  $block_options,
                "admin_label" => true
            ),
            $custom_class
        )
    ));

    if (!class_exists('WPBakeryShortCode_Foodfarm_Static_Block')) {
        class WPBakeryShortCode_Foodfarm_Static_Block extends WPBakeryShortCode {
        }
    }
}


