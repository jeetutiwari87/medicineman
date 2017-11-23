<?php

// foodfarm_we_doing
add_shortcode('foodfarm_we_doing', 'foodfarm_shortcode_we_doing');
add_action('vc_build_admin_page', 'foodfarm_load_we_doing_shortcode');
add_action('vc_after_init', 'foodfarm_load_we_doing_shortcode');

function foodfarm_shortcode_we_doing($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_we_doing'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_we_doing_shortcode() {
    $custom_class = foodfarm_vc_custom_class();
    vc_map( array(
        'name' => "Foodfarm " . esc_html__('What we doing', 'foodfarm'),
        'base' => 'foodfarm_we_doing',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("Title", 'foodfarm'),
                "param_name" => "title",
                "admin_label" => true,
            ),  
            array(
                "type" => "textarea",
                "heading" => esc_html__("Description", "foodfarm"),
                "param_name" => "description",
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Icon library', 'foodfarm'),
                'value' => array(
                    esc_html__('Font Awesome', 'foodfarm') => 'fontawesome',
                    esc_html__('Stroke Icons 7', 'foodfarm') => 'pestrokefont',
                    esc_html__('Font Foodfarm', 'foodfarm') => 'foodfarmfont',
                    esc_html__('Open Iconic', 'foodfarm') => 'openiconic',
                    esc_html__('Typicons', 'foodfarm') => 'typicons',
                    esc_html__('Entypo', 'foodfarm') => 'entypo',
                    esc_html__('Linecons', 'foodfarm') => 'linecons',
                ),
                'group' => 'Icon setting',
                'param_name' => 'icon_type',
                'description' => esc_html__('Select icon library.', 'foodfarm'),
            ),
            array(
                'type' => 'iconpicker',
                'heading' => esc_html__('Icon', 'foodfarm'),
                'param_name' => 'icon_pestrokefont',
                'settings' => array(
                    'emptyIcon' => false, // default true, display an "EMPTY" icon?
                    'type' => 'pestrokefont',
                    'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'pestrokefont',
                ),
                'weight' => 9,
                'description' => esc_html__('Select icon from library.', 'foodfarm'),
                'group' => 'Icon setting',
            ),
            array(
                'type' => 'iconpicker',
                'heading' => esc_html__( 'Icon', 'foodfarm' ),
                'param_name' => 'icon_foodfarm',
                'settings' => array(
                    'emptyIcon' => false, // default true, display an "EMPTY" icon?
                    'type' => 'foodfarmfont',
                    'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'foodfarmfont',
                ),
                'description' => esc_html__( 'Select icon from library.', 'foodfarm' ),
                'group' => 'Icon setting',
            ),
            array(
                'type' => 'iconpicker',
                'heading' => esc_html__('Icon', 'foodfarm'),
                'param_name' => 'icon_fontawesome',
                'settings' => array(
                    'emptyIcon' => false, // default true, display an "EMPTY" icon?
                    'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'fontawesome',
                ),
                'description' => esc_html__('Select icon from library.', 'foodfarm'),
                'group' => 'Icon setting',
            ),
            array(
                'type' => 'iconpicker',
                'heading' => esc_html__('Icon', 'foodfarm'),
                'param_name' => 'icon_openiconic',
                'settings' => array(
                    'emptyIcon' => false, // default true, display an "EMPTY" icon?
                    'type' => 'openiconic',
                    'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'openiconic',
                ),
                'description' => esc_html__('Select icon from library.', 'foodfarm'),
                'group' => 'Icon setting',
            ),
            array(
                'type' => 'iconpicker',
                'heading' => esc_html__('Icon', 'foodfarm'),
                'param_name' => 'icon_typicons',
                'settings' => array(
                    'emptyIcon' => false, // default true, display an "EMPTY" icon?
                    'type' => 'typicons',
                    'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'typicons',
                ),
                'description' => esc_html__('Select icon from library.', 'foodfarm'),
                'group' => 'Icon setting',
            ),
            array(
                'type' => 'iconpicker',
                'heading' => esc_html__('Icon', 'foodfarm'),
                'param_name' => 'icon_entypo',
                'settings' => array(
                    'emptyIcon' => false, // default true, display an "EMPTY" icon?
                    'type' => 'entypo',
                    'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'entypo',
                ),
                'description' => esc_html__('Select icon from library.', 'foodfarm'),
                'group' => 'Icon setting',
            ),
            array(
                'type' => 'iconpicker',
                'heading' => esc_html__('Icon', 'foodfarm'),
                'param_name' => 'icon_linecons',
                'settings' => array(
                    'emptyIcon' => false, // default true, display an "EMPTY" icon?
                    'type' => 'linecons',
                    'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'linecons',
                ),
                'description' => esc_html__('Select icon from library.', 'foodfarm'),
                'group' => 'Icon setting',
            ),
            array(
                'type' => 'attach_image',
                'heading' => esc_html__('Image', 'foodfarm'),
                'param_name' => 'image',
                'value' => '',
                'description' => esc_html__( 'Upload image.', 'foodfarm' ),
                'dependency' => array(
                    'element' => 'layout',
                    'value' => array('style2'),
                ),
            ),
            array(
             "type" => "vc_link",
             "heading" => esc_html__("Link", 'foodfarm'),
             "param_name" => "link",
            ),
            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_we_doing')) {
        class WPBakeryShortCode_Foodfarm_we_doing extends WPBakeryShortCode {
        }
    }
}
