<?php

function foodfarm_default_meta_data() {
    $foodfarm_layout = foodfarm_layouts();
    $foodfarm_sidebar_position = foodfarm_sidebar_position();
    $foodfarm_sidebars = foodfarm_sidebars();
    $foodfarm_header_layout = foodfarm_header_types();
    $foodfarm_footer_layout = foodfarm_footer_types();
    
    return array(
        // header
        'header' => array(
            'name' => 'header',
            'title' => esc_html__('Header Layout', 'foodfarm'),
            'type' => 'select',
            'options' => $foodfarm_header_layout,
            'default' => 'default'
        ),
        //footer
        'footer' => array(
            'name' => 'footer',
            'title' => esc_html__('Footer Layout', 'foodfarm'),
            'type' => 'select',
            'options' => $foodfarm_footer_layout,
            'default' => 'default'
        ),
        // Breadcrumbs
        'breadcrumbs' => array(
            'name' => 'breadcrumbs',
            'title' => esc_html__('Breadcrumbs', 'foodfarm'),
            'desc' => esc_html__('Hide breadcrumbs', 'foodfarm'),
            'type' => 'checkbox'
        ),
        'page_title' => array(
            'name' => 'page_title',
            'title' => esc_html__('Page Title', 'foodfarm'),
            'desc' => esc_html__('Hide Page Title', 'foodfarm'),
            'type' => 'checkbox'
        ),
        'show_header' => array(
            'name' => 'show_header',
            'title' => esc_html__('Header', 'foodfarm'),
            'desc' => esc_html__('Hide header', 'foodfarm'),
            'type' => 'checkbox'
        ),
        //  Show Footer
        'show_footer' => array(
            'name' => 'show_footer',
            'title' => esc_html__('Footer', 'foodfarm'),
            'desc' => esc_html__('Hide footer', 'foodfarm'),
            'type' => 'checkbox'
        ),
        //sidebar position
        'sidebar_position' => array(
            'name' => 'sidebar_position',
            'type' => 'select',
            'title' => esc_html__('Sidebar Position', 'foodfarm'),
            'options' => $foodfarm_sidebar_position,
            'default' => 'default'
        ),
        //sidebar
        'sidebar' => array(
            'name' => 'sidebar',
            'type' => 'select',
            'title' => esc_html__('Sidebar', 'foodfarm'),
            'options' => $foodfarm_sidebars,
            'default' => 'default'
        ),
        // layout
        'layout' => array(
            'name' => 'layout',
            'title' => esc_html__('Layout', 'foodfarm'),
            'type' => 'select',
            'options' => $foodfarm_layout,
            'default' => 'default'
        ),
        'show_slider' => array(
            'name' => 'show_slider',
            'title' => esc_html__('Show Slider', 'foodfarm'),
            'desc' => esc_html__('Enable slider', 'foodfarm'),
            'type' => 'checkbox'
        ),
        'select_slider' => array(
            'name' => 'select_slider',
            'title' => esc_html__('Select Revolution Slider', 'foodfarm'),
            'type' => 'select',
            'options' => foodfarm_rev_sliders_in_array(),
            'default' => 'default'
        ),
    );
}

