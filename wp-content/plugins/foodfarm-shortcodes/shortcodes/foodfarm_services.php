<?php

// foodfarm_latest_posts
add_shortcode('foodfarm_services', 'foodfarm_shortcode_services');
add_action('vc_build_admin_page', 'foodfarm_load_services_shortcode');
add_action('vc_after_init', 'foodfarm_load_services_shortcode');

function foodfarm_shortcode_services($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_services'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_services_shortcode() {
    $custom_class = foodfarm_vc_custom_class();

    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Services', 'foodfarm'),
        'base' => 'foodfarm_services',
        'category' => esc_html__('Foodfarm', 'foodfarm'),
        'icon' => 'foodfarm_vc_icon',
        'weight' => - 50,
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Layout", 'foodfarm'),
                "param_name" => "layout",
                'std' => 'default',
                'value' => array(
                    esc_html__('Default', 'foodfarm') => 'default',
                    esc_html__('Layout 2 (Flower Farm)', 'foodfarm') => 'layout_2',
                    esc_html__('Layout 3', 'foodfarm') => 'layout_3',
                ),
            ),            
            array(
                "type" => "textfield",
                "heading" => esc_html__("Title", 'foodfarm'),
                "param_name" => "title",
                "value" => "",
            ),
            array(
                "type" => "vc_link",
                "heading" => esc_html__("Link", 'foodfarm'),
                "param_name" => "title_url",
                "value" => "",
                'description' => esc_html__('Link to page services details.', 'foodfarm'),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Button text", 'foodfarm'),
                "param_name" => "btn_text",
                "value" => "",
                'dependency' => array(
                    'element' => 'layout',
                    'value' => 'layout_2',
                ),                
            ),            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Title align", 'foodfarm'),
                "param_name" => "title_align",
                'std' => 'left',
                'value' => array(
                    esc_html__('Center', 'foodfarm') => 'center',
                    esc_html__('Left', 'foodfarm') => 'left',
                    esc_html__('Right', 'foodfarm') => 'right',
                ),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Block ALignment", 'foodfarm'),
                "param_name" => "position",
                'std' => 'left',
                'value' => array(
                    esc_html__('Center', 'foodfarm') => 'center',
                    esc_html__('Left', 'foodfarm') => 'left',
                    esc_html__('Right', 'foodfarm') => 'right',
                ),
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__('Title Color', 'foodfarm'),
                'param_name' => 'title_color',
                'admin_label' => true
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Title font size", 'foodfarm'),
                "param_name" => "title_font_size",
                "value" => "",
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Icon type", 'foodfarm'),
                "param_name" => "select_icon",
                'std' => 'icon',
                'group' => 'Icon setting',
                'value' => array(
                    esc_html__('Font Icon', 'foodfarm') => 'icon',
                    esc_html__('Image', 'foodfarm') => 'image',
                ),
            ),            
            array(
                'type' => 'attach_image',
                'heading' => esc_html__('Image icon', 'foodfarm'),
                'param_name' => 'image_icon',
                'value' => '',
                'dependency' => array(
                    'element' => 'select_icon',
                    'value' => 'image',
                ),
                'group' => 'Icon setting',
                'description' => esc_html__( 'Upload image.', 'foodfarm' ),
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
                'dependency' => array(
                    'element' => 'select_icon',
                    'value' => 'icon',
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
                'type' => 'colorpicker',
                'heading' => esc_html__('Icon Color', 'foodfarm'),
                'param_name' => 'icon_color',
                'admin_label' => true,
                'group' => 'Icon setting',
            ),
            array(
                'type' => 'number',
                'heading' => esc_html__('Icon size (px)', 'foodfarm'),
                'param_name' => 'icon_size',
                'admin_label' => true,
                'group' => 'Icon setting',
            ),

            array(
                'type' => 'checkbox',
                'heading' => esc_html__("Show background title", "foodfarm"),
                'param_name' => 'view_background',
                'std' => 'no',
                'value' => array(esc_html__('Yes', 'foodfarm') => 'yes'),
                'dependency' => array(
                    'element' => 'layout',
                    'value' => 'default',
                ),                
            ),
            array(
                'type' => 'attach_image',
                'heading' => esc_html__('Background Title', 'foodfarm'),
                'param_name' => 'background_image',
                'value' => '',
                'dependency' => array(
                    'element' => 'view_background',
                    'value' => 'yes',
                ),
                'description' => esc_html__( 'Upload background image.', 'foodfarm' ),
            ),
            array(
                'type' => 'textarea_html',
                'heading' => esc_html__('Services info', 'foodfarm'),
                'param_name' => 'content',
            ),
            $custom_class
        )
    ) );

    if (!class_exists('WPBakeryShortCode_Foodfarm_Services')) {
        class WPBakeryShortCode_Foodfarm_Services extends WPBakeryShortCode {
        }
    }
}