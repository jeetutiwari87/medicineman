<?php

// foodfarm_blog
add_shortcode('foodfarm_blog', 'foodfarm_shortcode_blog');
add_action('vc_build_admin_page', 'foodfarm_load_blog_shortcode');
add_action('vc_after_init', 'foodfarm_load_blog_shortcode');

function foodfarm_shortcode_blog($atts, $content = null) {
    ob_start();
    if ($template = foodfarm_shortcode_template('foodfarm_blog'))
        include $template;
    return ob_get_clean();
}

function foodfarm_load_blog_shortcode() {
    $custom_class = foodfarm_vc_custom_class();
    $order_by_values = foodfarm_vc_woo_order_by();
    $order_way_values = foodfarm_vc_woo_order_way();

    vc_map( array(
        'name' => "Foodfarm " . esc_html__('Blog', 'foodfarm'),
        'base' => 'foodfarm_blog',
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
                "value" => "Blog",
                "admin_label" => true
            ),
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Show title", 'foodfarm'),
                "param_name" => "show_title",
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'foodfarm') => 'yes')
            ),               
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Select icon list", 'foodfarm'),
                "param_name" => "icon_list",
                'std' => 'default',
                'value' => array(
                    esc_html__('None', 'foodfarm') => 'none',
                    esc_html__('Default icon', 'foodfarm') => 'default',
                    esc_html__('Font icon family', 'foodfarm') => 'font_family',
                ),
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
                'dependency' => array(
                    'element' => 'icon_list',
                    'value' => 'font_family',
                ),                 
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
                "type" => "textarea",
                "heading" => esc_html__("Description", 'foodfarm'),
                "param_name" => "description",
                'description' => esc_html__('Enter short description', 'foodfarm')
            ),
            array(
                "type" => "number",
                "heading" => esc_html__("Number of blog to show", "foodfarm"),
                "param_name" => "number",
                "value" => 4,
                "admin_label" => true
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Display", 'foodfarm'),
                "param_name" => "post_display_type",
                'std' => '',
                'value' => array(
                    esc_html__('Recent', 'foodfarm') => 'recent',
                    esc_html__('Featured', 'foodfarm') => 'featured',
                    esc_html__('Most Viewed', 'foodfarm') => 'most-viewed',
                ),
                'group' => 'Data'
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( esc_html__( 'Select how to sort retrieved products_category. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                'group' => 'Data'
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                'group' => 'Data'
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Category IDs", "foodfarm"),
                "description" => esc_html__("comma separated list of category ids", "foodfarm"),
                "param_name" => "cat",
                "admin_label" => true,
                'group' => 'Data'
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Layout", 'foodfarm'),
                "param_name" => "layout",
                'std' => 'list',
                'value' => array(
                    esc_html__('List', 'foodfarm') => 'list',
                    esc_html__('Grid', 'foodfarm') => 'grid',
                    esc_html__('Grid 2', 'foodfarm') => 'grid2',
                    esc_html__('Grid style 3', 'foodfarm') => 'grid3',
                    esc_html__('Grid style 4', 'foodfarm') => 'grid4',
                    esc_html__('Grid style 5', 'foodfarm') => 'grid5',
                    esc_html__('Grid style 6', 'foodfarm') => 'grid6',
                ),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Button Text", "foodfarm"),
                "param_name" => "btn_text",
            ),            
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Type sticky", 'foodfarm'),
                "param_name" => "sticky_type",
                'std' => 'left',
                'value' => array(
                    esc_html__('List Default', 'foodfarm') => 'list-default',
                    esc_html__('List With Sticky Left', 'foodfarm') => 'left',
                    esc_html__('List With Sticky Right', 'foodfarm') => 'right',
                ),
                'dependency' => array(
                    'element' => 'layout',
                    'value' => array('list')
                )
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Blog Columns", 'foodfarm'),
                "param_name" => "columns",
                'std' => 4,
                'value' => array(
                    esc_html__('2 Columns', 'foodfarm') => '2',
                    esc_html__('3 Columns', 'foodfarm') => '3',
                    esc_html__('4 Columns', 'foodfarm') => '4',
                ),
                'dependency' => array(
                    'element' => 'layout',
                    'value' => array('grid', 'grid2','grid3','grid4','grid5','grid6')

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

    if (!class_exists('WPBakeryShortCode_Foodfarm_Blog')) {
        class WPBakeryShortCode_Foodfarm_Blog extends WPBakeryShortCode {
        }
    }
}