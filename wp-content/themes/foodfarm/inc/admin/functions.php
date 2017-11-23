<?php

if (!class_exists('ReduxFramework') && file_exists(foodfarm_admin . '/ReduxCore/framework.php')) {
    require_once( foodfarm_admin . '/ReduxCore/framework.php' );
}

require_once( foodfarm_admin . '/settings/settings.php' );
require_once( foodfarm_admin . '/settings/save_settings.php' );

function foodfarm_check_theme_options() {
    // check default options
    global $foodfarm_settings;
    if(!get_option('foodfarm_settings')) {
        ob_start();
        include(foodfarm_plugins . '/importer/data_import/options_data/option_demo_1.php');
        $options = ob_get_clean();
        $foodfarm_default_settings = json_decode($options, true);

        foreach ($foodfarm_default_settings as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key1 => $value1) {
                    if (!isset($foodfarm_settings[$key][$key1]) || !$foodfarm_settings[$key][$key1]) {
                        $foodfarm_settings[$key][$key1] = $foodfarm_default_settings[$key][$key1];
                    }
                }
            } else {
                if (!isset($foodfarm_settings[$key])) {
                    $foodfarm_settings[$key] = $foodfarm_default_settings[$key];
                }
            }
        }
    }

    return $foodfarm_settings;
}

if(!class_exists('ReduxFramework')) {
    foodfarm_check_theme_options();
}
//get theme demo options
function foodfarm_theme_types() {
    return array(
        '1' => esc_html__("Demo 1", 'foodfarm'),
        '2' => esc_html__("Demo 2", 'foodfarm'),
        '3' => esc_html__("Demo 3", 'foodfarm'),
        '4' => esc_html__("Demo 4", 'foodfarm'),
        '5' => esc_html__("Demo 5", 'foodfarm'),
        '6' => esc_html__("Demo 6", 'foodfarm')
    );
}
//get theme layout options
function foodfarm_layouts() {
    return array(
        'default' => esc_html__('Default Layout', 'foodfarm'),
        'wide' => esc_html__('Wide', 'foodfarm'),
        'fullwidth' => esc_html__('Full width', 'foodfarm')
    );
}
//get theme sidebar position options
function foodfarm_sidebar_position() {
    return array(
        'default' => esc_html__('Default Position', 'foodfarm'),
        'left-sidebar' => esc_html__('Left', 'foodfarm'),
        'right-sidebar' => esc_html__('Right', 'foodfarm'),
        'none' => esc_html__('None', 'foodfarm')
    );
}
function foodfarm_rev_sliders_in_array(){
    if (class_exists('RevSlider')) {
        $theslider     = new RevSlider();
        $arrSliders = $theslider->getArrSliders();
        $arrA     = array();
        $arrT     = array();
        foreach($arrSliders as $slider){
            $arrA[]     = $slider->getAlias();
            $arrT[]     = $slider->getTitle();
        }
        if($arrA && $arrT){
            $result = array_combine($arrA, $arrT);
        }
        else
        {
            $result = false;
        }
        return $result;
    }
}
function foodfarm_header_types() {
    return array(
        'default' => esc_html__('Default Header', 'foodfarm'),
        '1' => esc_html__('Header Type 1', 'foodfarm'),
        '2' => esc_html__('Header Type 2', 'foodfarm'),
        '3' => esc_html__('Header Type 3', 'foodfarm'),
        '4' => esc_html__('Header Type 4', 'foodfarm'),
        '5' => esc_html__('Header Type 5', 'foodfarm'),
        '6' => esc_html__('Header Type 6', 'foodfarm'),
        '7' => esc_html__('Header Type 7', 'foodfarm'),
        '8' => esc_html__('Header Type 8', 'foodfarm'),
        '9' => esc_html__('Header Type 9', 'foodfarm'),
    );
}

function foodfarm_footer_types() {
    return array(
        'default' => esc_html__('Default Footer', 'foodfarm'),
        '1' => esc_html__('Footer Type 1', 'foodfarm'),
        '2' => esc_html__('Footer Type 2', 'foodfarm'),
        '3' => esc_html__('Footer Type 3', 'foodfarm'),
        '4' => esc_html__('Footer Type 4', 'foodfarm'),
        '5' => esc_html__('Footer Type 5', 'foodfarm'),
        '6' => esc_html__('Footer Type 6', 'foodfarm'),
        '7' => esc_html__('Footer Type 7', 'foodfarm'),
        '8' => esc_html__('Footer Type 8', 'foodfarm'),
        '9' => esc_html__('Footer Type 9', 'foodfarm'),
    );
}
function foodfarm_product_columns() {
    return array(
        "2" => esc_html__("2", 'foodfarm'),
        "3" => esc_html__("3", 'foodfarm'),
        "4" => esc_html__("4", 'foodfarm'),
    );
}
function foodfarm_product_type() {
    return array(
        "only-grid" => esc_html__("Only Grid", 'foodfarm'),
        "only-list" => esc_html__("Only List", 'foodfarm'),
        "grid-default" => esc_html__("Grid (default) / List", 'foodfarm'),
        "list-default" => esc_html__("List (default) / Grid", 'foodfarm'),
    );
}
function foodfarm_blog_columns() {
    return array(
        "2" => esc_html__("2", 'foodfarm'),
        "3" => esc_html__("3", 'foodfarm'),
        "4" => esc_html__("4", 'foodfarm'),
    );
}
function foodfarm_gallery_columns() {
    return array(
        "2" => esc_html__("2", 'foodfarm'),
        "3" => esc_html__("3", 'foodfarm'),
        "4" => esc_html__("4", 'foodfarm'),
    );
}
function foodfarm_page_gallery_layouts(){
    return array(
        "grid" => esc_html__("Grid", 'foodfarm'),
        "masonry" => esc_html__("Masonry", 'foodfarm'),
    );
}