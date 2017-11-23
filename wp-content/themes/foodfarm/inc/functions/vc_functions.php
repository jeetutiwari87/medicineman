<?php
function foodfarm_vc_tta_accordion() {
    $attributes = array(
        array(
            'type' => 'dropdown',
            'param_name' => 'style',
            'value' => array(
                esc_html__('Foodfarm style', 'foodfarm') => 'foodfarm_style',
                esc_html__('Classic', 'foodfarm') => 'classic',
                esc_html__('Modern', 'foodfarm') => 'modern',
                esc_html__('Flat', 'foodfarm') => 'flat',
                esc_html__('Outline', 'foodfarm') => 'outline',
            ),
            'heading' => esc_html__('Style', 'foodfarm'),
            'description' => esc_html__('Select accordion display style.', 'foodfarm'),
        ),
    );
    vc_add_params('vc_tta_accordion', $attributes); // Note: 'vc_message' was used as a base for "Message box" element
}

add_action('vc_before_init', 'foodfarm_vc_tta_accordion');
function foodfarm_vc_tta_tabs() {
    $attributes = array(
        array(
            'type' => 'dropdown',
            'param_name' => 'style',
            'value' => array(
                esc_html__( 'Foodfarm style', 'foodfarm') => 'foodfarm_style',
                esc_html__( 'Classic', 'foodfarm' ) => 'classic',
                esc_html__( 'Modern', 'foodfarm' ) => 'modern',
                esc_html__( 'Flat', 'foodfarm' ) => 'flat',
                esc_html__( 'Outline', 'foodfarm' ) => 'outline',
            ),
            'heading' => esc_html__('Style', 'foodfarm'),
            'description' => esc_html__('Select tabs display style.', 'foodfarm'),
        ),
    );
    vc_add_params('vc_tta_tabs', $attributes); 
}

add_action('vc_before_init', 'foodfarm_vc_tta_tabs');
function foodfarm_iconpicker_type_pestrokefont( $icons ) {
    $pestrokefont_icons = array(
        array( 'pe-7s-back-2' => 'Back 2' ),
        array( 'pe-7s-piggy' => 'Piggy' ),
        array( 'pe-7s-gift' => 'Gift' ),
        array( 'pe-7s-arc' => 'Archor' ),
        array( 'pe-7s-plane' => 'Plane' ),
        array( 'pe-7s-help2' => 'Help' ),
        array( 'pe-7s-clock' => 'Clock' ),
        array( 'pe-7s-junk' => 'Junk' ),
        array( 'pe-7s-edit' => 'Edit' ),
        array( 'pe-7s-download' => 'Download' ),
        array( 'pe-7s-config' => 'Config' ),
        array( 'pe-7s-drop' => 'Drop' ),
        array( 'pe-7s-refresh' => 'Refresh' ),
        array( 'pe-7s-album' => 'Album' ),
        array( 'pe-7s-diamond' => 'Diamond' ),
        array( 'pe-7s-door-lock' => 'Door lock' ),
        array( 'pe-7s-photo' => 'Photo' ),
        array( 'pe-7s-settings' => 'Settings' ),
        array( 'pe-7s-volume' => 'Volumn' ),
        array( 'pe-7s-users' => 'Users' ),
        array( 'pe-7s-tools' => 'Tools' ),
        array( 'pe-7s-star' => 'Star' ),
        array( 'pe-7s-like2' => 'Like' ),
        array( 'pe-7s-map-2' => 'Map 2' ),
        array( 'pe-7s-call' => 'Call' ),
        array( 'pe-7s-mail' => 'Mail' ),
        array( 'pe-7s-way' => 'Way' ),
        array( 'pe-7s-edit' => 'Edit' ),
        array( 'pe-7s-drop' => 'Drop' ),
        array( 'pe-7s-download' => 'Download' ),
        array( 'pe-7s-config' => 'Config' ),
        array( 'pe-7s-junk' => 'Junk' ),
    );

    return array_merge( $icons, $pestrokefont_icons );
}

add_filter( 'vc_iconpicker-type-pestrokefont', 'foodfarm_iconpicker_type_pestrokefont' );
function foodfarm_vc_icon() {
    $attributes = array(
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Image icon', 'foodfarm'),
            'param_name' => 'image_icon',
            'value' => '',
            'description' => esc_html__( 'Upload image.', 'foodfarm' ),
            'weight' => 10,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Icon library', 'foodfarm'),
            'value' => array(
                esc_html__('Font Awesome', 'foodfarm') => 'fontawesome',
                esc_html__('Stroke Icons 7', 'foodfarm') => 'pestrokefont',
                esc_html__('Foodfarm Icon', 'foodfarm') => 'foodfarmfont',
                esc_html__('Open Iconic', 'foodfarm') => 'openiconic',
                esc_html__('Typicons', 'foodfarm') => 'typicons',
                esc_html__('Entypo', 'foodfarm') => 'entypo',
                esc_html__('Linecons', 'foodfarm') => 'linecons',
            ),
            'admin_label' => true,
            'param_name' => 'type',
            'weight' => 10,
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
                'element' => 'type',
                'value' => 'pestrokefont',
            ),
            'weight' => 9,
            'description' => esc_html__('Select icon from library.', 'foodfarm'),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__('Icon', 'foodfarm'),
            'param_name' => 'icon_foodfarmfont',
            'settings' => array(
                'emptyIcon' => false, // default true, display an "EMPTY" icon?
                'type' => 'foodfarmfont',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'foodfarmfont',
            ),
            'weight' => 9,
            'description' => esc_html__('Select icon from library.', 'foodfarm'),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__('Icon', 'foodfarm'),
            'param_name' => 'icon_fontawesome',
            'value' => 'fa fa-adjust', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'iconsPerPage' => 4000,
            // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'fontawesome',
            ),
            'description' => esc_html__('Select icon from library.', 'foodfarm'),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__('Icon', 'foodfarm'),
            'param_name' => 'icon_openiconic',
            'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false, // default true, display an "EMPTY" icon?
                'type' => 'openiconic',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'openiconic',
            ),
            'description' => esc_html__('Select icon from library.', 'foodfarm'),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__('Icon', 'foodfarm'),
            'param_name' => 'icon_typicons',
            'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false, // default true, display an "EMPTY" icon?
                'type' => 'typicons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'typicons',
            ),
            'description' => esc_html__('Select icon from library.', 'foodfarm'),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__('Icon', 'foodfarm'),
            'param_name' => 'icon_entypo',
            'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false, // default true, display an "EMPTY" icon?
                'type' => 'entypo',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'entypo',
            ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__('Icon', 'foodfarm'),
            'param_name' => 'icon_linecons',
            'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false, // default true, display an "EMPTY" icon?
                'type' => 'linecons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'linecons',
            ),
            'description' => esc_html__('Select icon from library.', 'foodfarm'),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon alignment', 'foodfarm' ),
            'param_name' => 'align',
            'value' => array(
                esc_html__( 'Left', 'foodfarm' ) => 'left',
                esc_html__( 'Right', 'foodfarm' ) => 'right',
                esc_html__( 'Center', 'foodfarm' ) => 'center',
                esc_html__( 'Inline', 'foodfarm' ) => 'inline',
            ),
            'description' => esc_html__( 'Select icon alignment.', 'foodfarm' ),
             "group"     => "Icon Style",
        ),
        array(
            'type' => 'number',
            'heading' => esc_html__( 'Size', 'foodfarm' ),
            'param_name' => 'size',
            "value" => "14",
            'description' => esc_html__( 'Icon size (px)', 'foodfarm' ),
             "group"     => "Icon Style",
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Border Style", "foodfarm"),
            "param_name" => "icon_border_style",
            "value" => array(
                esc_html__("None","foodfarm") => "none",
                esc_html__("Solid","foodfarm")   => "solid",
                esc_html__("Dashed","foodfarm") => "dashed",
                esc_html__("Dotted","foodfarm") => "dotted",
                esc_html__("Double","foodfarm") => "double",
                esc_html__("Inset","foodfarm") => "inset",
                esc_html__("Outset","foodfarm") => "outset",
            ),
            "description" => esc_html__("Select the border style for icon.","foodfarm"),
            "dependency" => Array("element" => "background_style", "value" => array("rounded-outline","boxed-outline", "rounded-less-outline")),
            "group"     => "Icon Style",
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Enable hover state for icon', 'foodfarm' ),
            'param_name' => 'icon_hover',
            'value' => true,
             "group"     => "Icon Style",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Layout style', 'foodfarm' ),
            'param_name' => 'layout_style',
            'value' => array(
                esc_html__( 'Style 1', 'foodfarm' ) => 'style1',
                esc_html__( 'Style 2', 'foodfarm' ) => 'style2',
            ),
            'default' => 'style1',
            'description' => esc_html__( 'Select icon alignment.', 'foodfarm' ),
             "group"     => "Content",
        ),        
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => esc_html__( "Content", "foodfarm" ),
            "param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
            "description" => esc_html__( "Enter your content.", "foodfarm" ),
            'group' => esc_html__( 'Content', 'foodfarm' )
        )
    );

    vc_add_params('vc_icon', $attributes);

}

add_action('vc_before_init', 'foodfarm_vc_icon');

function foodfarm_vc_btn() {
    $attributes = array(
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Color', 'foodfarm' ),
            'param_name' => 'color',
            'description' => esc_html__( 'Select button color.', 'foodfarm' ),
            'dependency' => array(
                'element' => 'style',
                'value_not_equal_to' => array(
                    'custom',
                    'outline-custom',
                ),
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon Alignment', 'foodfarm' ),
            'description' => esc_html__( 'Select icon alignment.', 'foodfarm' ),
            'param_name' => 'i_align',
            'value' => array(
                esc_html__( 'Left', 'foodfarm' ) => 'left',
                esc_html__( 'Right', 'foodfarm' ) => 'right',
            ),
            'dependency' => array(
                'element' => 'add_icon',
                'value' => 'true',
            ),
            'group'    => 'Icon Design',
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Style', 'foodfarm' ),
            'description' => esc_html__( 'Select button display style.', 'foodfarm' ),
            'param_name' => 'style',
            // partly compatible with btn2, need to be converted shape+style from btn2 and btn1
            'value' => array(
                esc_html__( 'Modern', 'foodfarm' ) => 'modern',
                esc_html__( 'Classic', 'foodfarm' ) => 'classic',
                esc_html__( 'Flat', 'foodfarm' ) => 'flat',
                esc_html__( 'Outline', 'foodfarm' ) => 'outline',
                esc_html__( '3d', 'foodfarm' ) => '3d',
                esc_html__( 'Custom', 'foodfarm' ) => 'custom',
                esc_html__( 'Outline custom', 'foodfarm' ) => 'outline-custom',
                esc_html__( 'Foodfarm default style', 'foodfarm' ) => 'btn-history',
            ),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Background color for icon', 'foodfarm' ),
            'description' => esc_html__( 'Select icon background.', 'foodfarm' ),
            'param_name' => 'icon_bg_color',
            'dependency' => array(
                'element' => 'add_icon',
                'value' => 'true',
            ),
            'group'    => 'Icon Design',
        ),
    );
    vc_add_params('vc_btn', $attributes); // Note: 'vc_message' was used as a base for "Message box" element
}

add_action('vc_before_init', 'foodfarm_vc_btn');

function foodfarm_vc_text_separator() {
    $attributes = array(
        array(
            'type' => 'textarea_html',
            'heading' => esc_html__( 'Title', 'foodfarm' ),
            'param_name' => 'content',
            'holder' => 'div',
            'description' => esc_html__( 'Add title to display above separator.', 'foodfarm' ),
            'group' => "Title",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Title position', 'foodfarm' ),
            'param_name' => 'big_align',
            'value' => array(
                esc_html__( 'Center', 'foodfarm' ) => 'big_center',
                esc_html__( 'Left', 'foodfarm' ) => 'big_left',
                esc_html__( 'Right', 'foodfarm' ) => 'big_right',
            ),
            'description' => esc_html__( 'Select title location.', 'foodfarm' ),
            'group' => "Title",
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Title Color', 'foodfarm' ),
            'description' => esc_html__( 'Select color for title.', 'foodfarm' ),
            'param_name' => 'big_color',
            'group'    => 'Title',
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Text to separator', 'foodfarm' ),
            'param_name' => 'title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add text to separator.', 'foodfarm' ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Text separator position', 'foodfarm' ),
            'param_name' => 'title_align',
            'value' => array(
                esc_html__( 'Center', 'foodfarm' ) => 'separator_align_center',
                esc_html__( 'Left', 'foodfarm' ) => 'separator_align_left',
                esc_html__( 'Right', 'foodfarm' ) => 'separator_align_right',
            ),
            'description' => esc_html__( 'Select Text separator location.', 'foodfarm' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Icon class', 'foodfarm' ),
            'param_name' => 'cus_icon_class',
            'description' => esc_html__( 'Input icon class.', 'foodfarm' ),
            'dependency' => array(
                'element' => 'add_icon',
                'value' => 'true',
            ),
            'group' => 'Custom icon class',
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Color for custom icon', 'foodfarm' ),
            'param_name' => 'icon_cus_color',
            'group'    => 'Custom icon class',
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'CSS box', 'foodfarm' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design Options', 'foodfarm' ),
            'weight' => 0,
        ),
    );
    vc_add_params('vc_text_separator', $attributes); 
    // vc_remove_param('vc_text_separator', 'i_size'); 
}
add_action('vc_before_init', 'foodfarm_vc_text_separator');

function foodfarm_vc_row() {
    $attributes = array(
        array(
            'type' => 'checkbox',
            'heading' => esc_html__("Wrap inside column in container", "foodfarm"),
            'param_name' => 'wrap_container',
            'value' => array( esc_html__( 'Yes', 'foodfarm' ) => 'yes' ),
            'weight' => 5,
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__("Hide background in mobile", "foodfarm"),
            'param_name' => 'hide_bg_mobile',
            'value' => array( esc_html__( 'Yes', 'foodfarm' ) => 'yes' ),
            'weight' => 5,
        ),
    );
    vc_add_params('vc_row', $attributes); 
}
add_action('vc_before_init', 'foodfarm_vc_row'); 



