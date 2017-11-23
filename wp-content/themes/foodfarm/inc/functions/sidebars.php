<?php
add_action('widgets_init', 'foodfarm_register_sidebars');

function foodfarm_register_sidebars() {
    
    register_sidebar(array(
        'name' => esc_html__('General Sidebar', 'foodfarm'),
        'id' => 'general-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget general-sidebar %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title widget-title-border-2">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar( array(
        'name' => esc_html__('Blog Sidebar', 'foodfarm'),
        'id' => 'blog-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title widget-title-border-2">',
        'after_title' => '</h3>',
    ) );
    if( function_exists('is_plugin_active') && is_plugin_active( 'foodfarm-post-types/functions.php' ) ) {
        register_sidebar( array(
            'name' => esc_html__('Recipe Sidebar', 'foodfarm'),
            'id' => 'recipe-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => "</aside>",
            'before_title' => '<h3 class="widget-title widget-title-border-2">',
            'after_title' => '</h3>',
        ) );
    }
    register_sidebar( array(
        'name' => esc_html__('Press Media Sidebar', 'foodfarm'),
        'id' => 'press-media-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title widget-title-border-2">',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => esc_html__('Left Wedoing Sidebar', 'foodfarm'),
        'id' => 'left-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title widget-title-border-2">',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => esc_html__('About us', 'foodfarm'),
        'id' => 'about-us',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title widget-title-border-2">',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => esc_html__('Right Wedoing Sidebar', 'foodfarm'),
        'id' => 'right-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title widget-title-border-2">',
        'after_title' => '</h3>',
    ) );
    register_sidebar(array(
        'name' => esc_html__('Left Footer', 'foodfarm'),
        'id' => 'left-footer',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4> ',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Widget 1', 'foodfarm'),
        'id' => 'footer-column-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4> ',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Widget 2', 'foodfarm'),
        'id' => 'footer-column-2',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Widget 3', 'foodfarm'),
        'id' => 'footer-column-3',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Widget 4', 'foodfarm'),
        'id' => 'footer-column-4',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Service 1', 'foodfarm'),
        'id' => 'footer-service-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4> ',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Service 2', 'foodfarm'),
        'id' => 'footer-service-2',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Service 3', 'foodfarm'),
        'id' => 'footer-service-3',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Service 4', 'foodfarm'),
        'id' => 'footer-service-4',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Store 1', 'foodfarm'),
        'id' => 'footer-store-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4> ',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Store 2', 'foodfarm'),
        'id' => 'footer-store-2',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Store 3', 'foodfarm'),
        'id' => 'footer-store-3',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Store 4', 'foodfarm'),
        'id' => 'footer-store-4',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Bakery 1', 'foodfarm'),
        'id' => 'footer-bakery-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4> ',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Bakery 2', 'foodfarm'),
        'id' => 'footer-bakery-2',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Bakery 3', 'foodfarm'),
        'id' => 'footer-bakery-3',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Bakery 4', 'foodfarm'),
        'id' => 'footer-bakery-4',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Farm Fruit 1', 'foodfarm'),
        'id' => 'footer7-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4> ',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Farm Fruit 2', 'foodfarm'),
        'id' => 'footer7-2',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Farm Fruit 3', 'foodfarm'),
        'id' => 'footer7-3',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Farm Fruit 4', 'foodfarm'),
        'id' => 'footer7-4',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Flower 1', 'foodfarm'),
        'id' => 'footer8-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4> ',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Flower 2', 'foodfarm'),
        'id' => 'footer8-2',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Flower 3', 'foodfarm'),
        'id' => 'footer8-3',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Flower 4', 'foodfarm'),
        'id' => 'footer8-4',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    )); 
    register_sidebar(array(
        'name' => esc_html__('Footer Flower 5', 'foodfarm'),
        'id' => 'footer8-5',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));       
    register_sidebar(array(
        'name' => esc_html__('Footer Newsletter', 'foodfarm'),
        'id' => 'footer-newsletter',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer9 column 1', 'foodfarm'),
        'id' => 'footer9-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4> ',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer9 column 2', 'foodfarm'),
        'id' => 'footer9-2',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer9 column 3', 'foodfarm'),
        'id' => 'footer9-3',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer9 column 4', 'foodfarm'),
        'id' => 'footer9-4',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h4 class="widget-title widget-title-border">',
        'after_title' => '</h4>',
    )); 
    if(function_exists('is_plugin_active') && is_plugin_active( 'wp-knowledgebase/wp-knowledgebase.php' ) ) {
        register_sidebar(array(
            'name' => esc_html__('WP Knowledgebase Sidebar','foodfarm'),
            'id' => 'kbe_cat_widget',
            'description' => esc_html__('WP Knowledgebase sidebar area','foodfarm'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }
    if (class_exists('Woocommerce')) {

        register_sidebar(array(
            'name' => esc_html__('Shop Sidebar', 'foodfarm'),
            'id' => 'shop-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => "</aside>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Single Product Sidebar', 'foodfarm'),
            'id' => 'single-product-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => "</aside>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }
}