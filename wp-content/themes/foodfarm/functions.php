<?php
$theme = wp_get_theme();
define('foodfarm_version', $theme->get('Version'));
define('foodfarm_lib', get_template_directory() . '/inc');
define('foodfarm_admin', foodfarm_lib . '/admin');
define('foodfarm_plugins', foodfarm_lib . '/plugins');
define('foodfarm_functions', foodfarm_lib . '/functions');
define('foodfarm_metaboxes', foodfarm_functions . '/metaboxes');
define('foodfarm_css', get_template_directory_uri() . '/css');
define('foodfarm_js', get_template_directory_uri() . '/js');

require_once(foodfarm_admin . '/functions.php');
require_once(foodfarm_functions . '/functions.php');
require_once(foodfarm_metaboxes . '/functions.php');
require_once(foodfarm_plugins . '/functions.php');
// Set up the content width value based on the theme's design and stylesheet.
if (!isset($content_width)) {
    $content_width = 1140;
}
global $foodfarm_settings;
if (!function_exists('foodfarm_setup')) {

    function foodfarm_setup() {
        load_theme_textdomain('foodfarm', get_template_directory() . '/languages');
        add_editor_style( array( 'style.css', 'style_rtl.css' ) );
        add_theme_support( 'title-tag' );
        add_theme_support('automatic-feed-links');
        // register menus
        register_nav_menus( array(
            'primary' => esc_html__('Primary Menu', 'foodfarm'),
            'primary_services' => esc_html__('Services Menu', 'foodfarm'),
            'vertical_menu' => esc_html__('Vertical Menu', 'foodfarm'),
            'bakery_menu' => esc_html__('Bakery Menu', 'foodfarm'),
            'fruit_menu' => esc_html__('Fruits Menu', 'foodfarm'),
            'flower_menu' => esc_html__('Flower Farm Menu', 'foodfarm'),
        ));
        add_theme_support( 'custom-header' );
        add_theme_support( 'custom-background' );
        add_theme_support( 'post-thumbnails' );
        add_image_size('foodfarm-recipes-carousel', 498, 305, true);
        add_image_size('foodfarm-recipes-grid', 295, 295, true);
        add_image_size('foodfarm-recipe-grid-2', 555, 367, true);
        add_image_size('foodfarm-gallery-grid', 480, 352, true);
        add_image_size('foodfarm-gallery-grid_2', 480, 407, true);
        add_image_size('foodfarm-blog-small', 132, 127, true);
        add_image_size('foodfarm-blog-grid', 365, 392, true);
        add_image_size('foodfarm-blog-grid-2', 360, 208, true);
        add_image_size('foodfarm-blog-grid-3', 555, 280, true);
        add_image_size('foodfarm-blog-grid-4', 555, 300, true);
        add_image_size('foodfarm-blog-grid-6', 356, 231, true);
        add_image_size('foodfarm-blog-sticky-1', 554, 350, true);
        add_image_size('foodfarm-blog-sticky-2', 554, 610, true);
        add_image_size('foodfarm-blog-list', 848, 380, true);
        add_image_size('foodfarm-recipe-list', 475, 262, true);
        add_image_size('foodfarm-knowledge-list', 458, 264, true);
        add_image_size('foodfarm-recipe-single', 859, 527, true);     
        add_image_size('foodfarm-member', 100, 100, true);  
        add_image_size('foodfarm-testimonial', 234, 141, true);  
    }

}
add_action('after_setup_theme', 'foodfarm_setup');

add_action('admin_enqueue_scripts', 'foodfarm_admin_scripts_css');
function foodfarm_admin_scripts_css() {
    if(is_rtl()){
        wp_enqueue_style('foodfarm_admin_rtl_css', foodfarm_css . '/admin-rtl.css', false);
    }
    else{
        wp_enqueue_style('foodfarm_admin_css', foodfarm_css . '/admin.css', false);
    }
}
add_action('admin_enqueue_scripts', 'foodfarm_admin_scripts_js');
function foodfarm_admin_scripts_js() {
    wp_enqueue_script('iris');
    wp_register_script('foodfarm_admin_js', foodfarm_js . '/un-minify/admin.js', array('common', 'jquery', 'media-upload', 'thickbox'), foodfarm_version, true);
    wp_enqueue_script('foodfarm_admin_js');
    wp_localize_script('foodfarm_admin_js', 'foodfarm_params', array(
        'foodfarm_version' => foodfarm_version,
    ));
}
function foodfarm_scripts_styles() {
    global $foodfarm_settings;
    $foodfarm_main_color = foodfarm_get_meta_value('main_color');
    $foodfarm_h_color = foodfarm_get_meta_value('h_color');
    $foodfarm_custom_css ='';
    //Custom font
    if (isset($foodfarm_h_color) && $foodfarm_h_color != '' ){
        $foodfarm_custom_css .= "
        .footer-v4 .newsletter-footer button.btn-default:hover,
        .footer-v5 .newsletter-footer button.btn-default:hover,
        .woocommerce-tabs #tab-revssiews .form-submit input:hover,
        .price_slider_amount .button:hover,
        .btn-default:hover, 
        .btn-default:focus, 
        .btn-default:active, 
        .btn-default:active:focus, 
        .btn-default:focus:active,
        .vc_btn3.vc_btn3-color-grey.vc_general.btn , 
        .vc_btn3.vc_btn3-color-grey.vc_btn3-style-custom.vc_general.btn:hover,
        .woocommerce table.wishlist_table tr.cart_item td.product-add-to-cart div.add-to-cart a.button:hover,
        .woocommerce-account .woocommerce-MyAccount-content input[type='submit']:hover,
        .footer-v6 .newsletter-footer button.btn-default:hover,
        .prd_cat_count:hover,.h-bg .ubtn,.h-bg .ubtn-link,
        .main-bg .ubtn:hover, .main-bg .ubtn-link:hover,
        .prd_cat_count:before, .prd_cat_count:after,.main-bg .ubtn-top-bg .ubtn-hover,
        .footer-v9 .newsletter-footer form button[type='submit']:hover{
            background: {$foodfarm_h_color}; 
        }
        @media (min-width: 768px){
            .prd_cat_count:hover{
               background: {$foodfarm_h_color};  
            }
        }
        .blog-grid-style4 .read-more a:hover,
        #pre_order_prd .pre_order_price{
            color: {$foodfarm_h_color};
        }
        ";
    }
    if (isset($foodfarm_main_color) && $foodfarm_main_color != '' ) :
        ?>
        <?php 
            $foodfarm_custom_css .= "
            .blog-grid-style3.blog-content .post-name a:hover,
            .blog-grid-style3 .blog-info .info:hover i,
            .blog-grid-style3 .read-more a,
            .blog-grid-style3 .blog-info .info a:hover,
            .product_type_2 .star-rating::before,
            .footer-v7 .footer-contact-time .icon-title .icon-8,
            .header-v7 .main-navigation .mini-cart .cart_label i,
            .header-v7 .main-navigation .mega-menu > li > a:hover,
            .main_color,.header-v7 .mega-menu li.megamenu .dropdown-menu li a:hover,
            .header-v7 .main-navigation .mini-cart .cart_label i,
            .blog-grid-style3 .read-more a,
            .header-v5 .top-link .customlinks > a i,
            .header-v5 .cart_label i,
            .header-v5 .contact_v5 i,
            .header-v4 .top-link .customlinks i,
            .services-part .services-icon i,
            .footer-v4 .widget li a:hover,
            .footer-v1 address a:hover,
            .icon-2 .path1::before, .icon-2 .path2::before, .icon-2 .path3::before,
            .vc_icon_element.about-icon:not(.hover-off) .icon .vc_icon_element-inner .vc_icon_element-icon span::before,
            .about-icon .desc h5,
            .about-icon .desc h3,
            .vc_icon_element.about-icon:not(.hover-off) .icon .vc_icon_element-inner .vc_icon_element-icon,
            .widget_shopping_cart_content .cart-info .product-name a:hover,
            .widget_shopping_cart_content .remove-product:hover,
            a:focus, a:hover,
            .search-block-top .btn-search:hover,
            .top-link .customlinks > a:hover,
            .mega-menu .dropdown-menu li a:hover,
            .vc_icon_element.about-icon
            .icon .vc_icon_element-inner .vc_icon_element-icon,
            .custom-icon-class,
            .vc_icon_element.icon .vc_icon_element-inner .vc_icon_element-icon > span,
            .widget.widget_tag_cloud a:hover,
            .blog-info .info:hover i,
            .links-info .info a.liked,
            .woocommerce-account form.login p.lost_password a,
            .category .blog-info a:hover,
            .promo-banner h2 a:hover,
            .my_account header.title a,
            .woocommerce-account table.my_account_orders tbody a.view,
            .shop_table .product-thumbnail > a:hover,
            .product-desc .add-to-cart span,
            .product-desc .add-to a,
            .product-desc h3 a:hover,
            .star-rating span::before,
            .single_add_to_cart_button,
            .recipes-content .blog-item .post-name a,
            .icon-title .icon-8,
            .gallery-desc a:hover,
            .blog-content .post-name a:hover,
            .blog-ful .blog-item .post-name a,
            .blog-info .read-more a,
            .list-info li i,
            .search-block-top .btn-search:focus, .search-block-top .btn-search:active,
            .header-v2 .link-contact p a:hover,
            .header-v2 .top-link .customlinks:hover > a,
            .featured-package .product-desc h3 a,
            .vertical-menu .mega-menu li a:hover,
            .star-rating::before,
            .footer-v3 address a:hover,
            .controls-custom .owl-controls .owl-buttons div:hover,
            #yith-quick-view-close:hover,
            table.compare-list .stock td span,
            table.compare-list .remove td a:hover,
            .page-numbers .current,
            .page-numbers > li:hover a, .page-numbers > li:hover span,
            .viewmode-toggle a:hover, .viewmode-toggle a:focus, .viewmode-toggle a.active,
            .images .views-block .controls-custom .owl-controls .owl-buttons div:hover,
            .info .summary .share-email a:hover,
            .nav-tabs > li > a:hover, .nav-tabs > li > a:focus,
            .addthis_sharing_toolbox .f-social li:hover a,
            .nav-tabs > li.active > a,
            .stars a.active::after,
            .yith-woocompare-widget ul.products-list li a.title:hover,
            .blog .blog-ful .blog-item .post-name a:hover,
            .blog .blog-info a:hover,
            .widget_post_blog .blog-content .post-name a:hover,
            .widget_post_blog .blog-info .info a:hover,
            .recentcomments a:hover,
            .blog .blog-ful .blog-post-info .info:hover i,
            .blog_post_desc p a,
            .links-info .info:hover a,
            .breadcrumb li a:hover,
            .recipes-list-container .read-more a,
            .knowledge-list-content ul li::before,
            .knowledge-list-content ul li a:hover,
            .recipes-list-container .blog-info a:hover,
            .press-media .read-more a,
            .recipes-prep .icon, .recipes-servings .icon,
            .vc_tta-tabs.vc_tta-style-foodfarm_style ul.vc_tta-tabs-list li.vc_active a,
            .vc_tta-tabs.vc_tta-style-foodfarm_style ul.vc_tta-tabs-list li:hover a,
            .tooltip-content a,
            .content-desc .button-404 .btn-default-2,
            .coming-sub h2 span,
            .footer-location-container .location-info .phone-number,
            .footer-location-container .location-info p a:hover,
            .contact-desc .number,
            .vertical-menu .mega-menu li .dropdown-menu li a:hover,
            .shop_table td.product-name a:hover, 
            .side-breadcrumb-2 .page-title h2,
            .category .blog-info a:hover, 
            .search.search-results .blog-info a:hover,
            .date .blog-info a:hover,
            .blog-info .blog-comment:hover,
            .vc_wp_custommenu.wpb_content_element .widget_nav_menu ul > li > a:hover,
            .vc_wp_custommenu.wpb_content_element .widget_nav_menu ul > li > a:before,
            .ads_border_1 span,
            .btn.btn-organic:hover,
            .header-v4 .link-contact p i,
            .header-v4 .mega-menu li a:hover, .header-v4 .mega-menu li a:focus,
            .right-services .services-button,
            .vc_wp_custommenu.wpb_content_element .widget_nav_menu .menu-service-detail-container ul > li > a::before,
            .vc_wp_custommenu.wpb_content_element .widget_nav_menu .menu-service-detail-container ul > li > a:hover,.main_color, .home7_btn_slider:hover,
            .footer-v7 .footer-contact-info a:hover,.product-loadmore > a.btn:hover{
                color: {$foodfarm_main_color};
            }
            .home7_btn_slider:hover{
                border-bottom-color: {$foodfarm_main_color};
            }
            .blog-grid-style3 .read-more a,
            .product_type_2 .product-grid .product-content .add-to-cart:hover,
            .product_type_2 .product-grid .product-content .add-to:hover,
            .product_type_2 .product-grid .product-content .quick-view:hover,
            .footer-v7 .menu-footer-social ul > li a:hover{
                border:1px solid {$foodfarm_main_color};
            }
            .blog-grid-style3 .read-more a:hover,
            product_type_2 .product-grid .product-content .add-to-cart:hover,
            .product_type_2 .product-grid .product-content .add-to-cart:hover a,
            .product_type_2 .product-grid .product-content .add-to:hover,
            .product_type_2 .product-grid .product-content .add-to:hover a,
            .product_type_2 .product-grid .product-content .quick-view:hover,
            .header-v7 .main-navigation .mega-menu > li > a:before,
            .header-v7 .header-bottom .header_contact_info .search-block-top .top-search .btn-search,
            .footer-v7 .newsletter-footer button.btn,
            .footer-v7 .menu-footer-social ul > li a:hover,
            .mc4wp-alert.mc4wp-success,
             .footer-v7 .widget-title-border:before,.product-loadmore > a.btn{
                background: {$foodfarm_main_color};
            }
            .blog-grid-style3 .read-more a,.product-loadmore > a.btn,
            a#product-loadmore:focus, a#product-loadmore:active, a#product-loadmore:active:focus,
            .product_type_2 .product-grid .product-content .add-to-cart:hover a{
                border-color: {$foodfarm_main_color};
            }
            .header-v7 .main-navigation .mega-menu > li > a:before,
            .header-v7 .header-top,
            .header-v7 .header-bottom .header_contact_info .search-block-top .top-search .btn-search,
            .footer-v7 .menu-footer-social ul > li a:hover,
            .button_member, .footer-v4 .newsletter-footer button.btn-default, 
            .footer-v5 .newsletter-footer button.btn-default, .style_middle .line, 
            .promo-banner .text_block_over h3::before, .ares .tp-bullet:hover, 
            .ares .tp-bullet.selected, .header-v5 .menu-primary-menu-container, 
            .header-v5 .mini-cart .number-product, .services-overlay::before, 
            .woocommerce .widget_layered_nav ul.yith-wcan-label li a:hover, 
            .woocommerce-page .widget_layered_nav ul.yith-wcan-label li a:hover, 
            .woocommerce .widget_layered_nav ul.yith-wcan-label li.chosen a, 
            .woocommerce-page .widget_layered_nav ul.yith-wcan-label li.chosen a, 
            .link-network li:hover, .tooltip-inner, .content-desc .button-404 .btn-default-2:hover,
             .page-numbers.page-secondary > li:hover a, .page-numbers.page-secondary > li:hover span, 
             .page-numbers.page-secondary .current, .blog_post_desc ul li::before, 
             .woocommerce-tabs #tab-reviews .form-submit input, 
             .header-v2 .top-link .customlinks.link-checkout .number-product, 
             .footer .widget-title-border::before, .recipes .owl-theme .owl-controls .owl-buttons div:hover, .recipes .owl-theme .owl-controls .owl-buttons div:active, .recipes .owl-theme .owl-controls .owl-buttons div:focus, .product-desc .add-to-cart span:hover, .nav-tabs.btn-filter li a.active, .nav-tabs.btn-filter li a:hover, .nav-tabs.btn-filter li a:focus, .btn-default, .vc_btn3.vc_btn3-style-custom.btn-default, .vc_btn3.vc_btn3-style-custom.btn-default:hover, blockquote:before, .vc_btn3.vc_btn3-color-grey.vc_general.btn:hover, .vc_btn3.vc_btn3-color-grey.vc_btn3-style-custom.vc_general.btn, .product-desc .add-to a:hover, .single_add_to_cart_button span, .blog-date, .vc_tta-style-foodfarm_style .vc_tta-panel-title > a i, .text-center .icon-title::before, .icon-title::before, .text-center .icon-title::after, .footer .widget-title-border::before, .footer-v2 .newsletter-footer .btn, .widget_price_filter .ui-slider .ui-slider-range, .widget_price_filter .ui-slider .ui-slider-handle, .price_slider_amount .button, .woocommerce .woocommerce-message, .nav-tabs > li.active > a::before, .main-sidebar .searchform .button, .vc_tta-tabs.vc_tta-style-foodfarm_style ul.vc_tta-tabs-list li.vc_active a .vc_tta-title-text::before, .woocommerce table.wishlist_table tr.cart_item td.product-add-to-cart div.add-to-cart a.button, .header-v1 .mini-cart .number-product, .header-v3 .mini-cart .number-product, .product_type_2 .product-grid .product-content .add-to-cart:hover, .product_type_2 .product-grid .product-content .add-to-cart:hover a, .product_type_2 .product-grid .product-content .add-to:hover, .product_type_2 .product-grid .product-content .add-to:hover a, .product_type_2 .product-grid .product-content .quick-view:hover, .woocommerce-account .woocommerce-MyAccount-content input[type='submit'],
             .footer-v7 .newsletter-footer button.btn,
             .blog-grid-style3 .read-more a:hover, .mc4wp-alert.mc4wp-success,
             .footer-v7 .widget-title-border:before,.product-loadmore > a.btn{
                    background: {$foodfarm_main_color};
            }
            ";                 
        ?>        
    <?php endif;  
        if(isset($foodfarm_settings['header8-bg'])){
            $foodfarm_custom_css .= "
                .header-v8{background: {$foodfarm_settings['header8-bg']}; }
            ";
        }
        if(isset($foodfarm_settings['header8-top-bg']) || isset($foodfarm_settings['header8-top-text-color'])){
            $foodfarm_custom_css .= "
                .header-v8 .header-top {
                    background: {$foodfarm_settings['header8-top-bg']}; 
                    color: {$foodfarm_settings['header8-top-text-color']};
                }
            ";
        }   
        if(isset($foodfarm_settings['header7-menu-text']) 
            || isset($foodfarm_settings['header8-menu-hover'])){
            $foodfarm_custom_css .= "
                .header-v8 .mega-menu > li > a{color: {$foodfarm_settings['header7-menu-text']}; }
                .header-v8 .mega-menu > li > a:hover{color: {$foodfarm_settings['header8-menu-hover']}; }
            ";
        }   
        if(isset($foodfarm_settings['header8-menu-border'])){
            $foodfarm_custom_css .= "
                .header-v8 .main-navigation{border-color: {$foodfarm_settings['header8-menu-border']}; }
            ";
        }    
        if(isset($foodfarm_settings['header8-text-phonenumber_color'])){
            $foodfarm_custom_css .= "
                .header-v8 .header-top .header-contact span{color: {$foodfarm_settings['header8-text-phonenumber_color']}; }
            ";            
        }   
        if(isset($foodfarm_settings['footer-bg-8']) || isset($foodfarm_settings['footer-text-8'])){
            $foodfarm_custom_css .= "
                .footer-v8  {
                    background: {$foodfarm_settings['footer-bg-8']}; 
                    color: {$foodfarm_settings['footer-text-8']};
                }
                .footer-v8 .footer-bottom address{
                    color: {$foodfarm_settings['footer-text-8']};
                }
            ";            
        }  
        if(isset($foodfarm_settings['footer-title-7'])){
            $foodfarm_custom_css .= "
                .footer-v8.footer .widget-title  {
                    color: {$foodfarm_settings['footer-title-7']};
                }
            ";            
        }  
       
        if(isset($foodfarm_settings['menu_spacing']) && $foodfarm_settings['menu_spacing'] !=''){
            $foodfarm_custom_css .= "
                @media (min-width: 992px){
                    .mega-menu > li > a{
                        padding-left: {$foodfarm_settings['menu_spacing']['margin-left']} !important;
                        padding-top: {$foodfarm_settings['menu_spacing']['margin-top']} !important;
                        padding-right: {$foodfarm_settings['menu_spacing']['margin-right']} !important;
                        padding-bottom: {$foodfarm_settings['menu_spacing']['margin-bottom']} !important;
                    }
                }
            ";        
        } 
        if(isset($foodfarm_settings['logo_width']) && $foodfarm_settings['logo_width'] !=''){
            $foodfarm_custom_css .= "
                .header-logo img{
                    width: {$foodfarm_settings['logo_width']['width']} !important;
                }
            ";         
        }             
        if(isset($foodfarm_settings['header-top-bg']) && $foodfarm_settings['header-top-bg'] != ''){
            $foodfarm_custom_css .= "
                .header-top  {
                    background: {$foodfarm_settings['header-top-bg']};
                }
            ";            
        }  
        if(isset($foodfarm_settings['header-menu-color']) && $foodfarm_settings['header-menu-color'] != ''){
            $foodfarm_custom_css .= "
                @media (min-width:992px){
                    .mega-menu > li > a   {
                        color: {$foodfarm_settings['header-menu-color']};
                    }
                }
            ";            
        }    
        if(isset($foodfarm_settings['header-nav-border_color']) && $foodfarm_settings['header-nav-border_color'] != ''){
            $foodfarm_custom_css .= "
                .main-navigation,.mega-menu > li > a,.mega-menu > li:first-child > a   {
                    border-color: {$foodfarm_settings['header-nav-border_color']};
                }
            ";            
        } 
        if(isset($foodfarm_settings['header-top_color']) && $foodfarm_settings['header-top_color'] != ''){
            $foodfarm_custom_css .= "
                .link-contact p a,.header-v1 .top-link .customlinks > a{
                    color: {$foodfarm_settings['header-top_color']};
                }
            ";            
        }
        if(isset($foodfarm_settings['header2-bg']) && $foodfarm_settings['header2-bg'] != ''){
            $foodfarm_custom_css .= "
                .header-v2{
                    background: {$foodfarm_settings['header2-bg']};
                }
            ";            
        }  
        if(isset($foodfarm_settings['header2-menu-color']) && $foodfarm_settings['header2-menu-color'] != ''){
            $foodfarm_custom_css .= "
                @media (min-width:992px){
                    .header-v2 .mega-menu>li>a {
                        color: {$foodfarm_settings['header2-menu-color']};
                    }
                }
            ";            
        }
        if(isset($foodfarm_settings['header2-nav-border_color']) && $foodfarm_settings['header2-nav-border_color'] != ''){
            $foodfarm_custom_css .= "
                .header-v2 .main-navigation    {
                    border-color: {$foodfarm_settings['header2-nav-border_color']};
                }
            ";            
        }
        if(isset($foodfarm_settings['header2-top_color']) && $foodfarm_settings['header2-top_color'] != ''){
            $foodfarm_custom_css .= "
                .header-v2 .link-contact p a{
                    color: {$foodfarm_settings['header2-top_color']};
                }
            ";            
        }                    
        if(isset($foodfarm_settings['header3-menu-color']) && $foodfarm_settings['header3-menu-color'] != ''){
            $foodfarm_custom_css .= "
                .header-v3 .right-header .btn-search,.header-v3 .right-header .cart_label,
                .header-v3.top-link .customlinks>a{
                    color: {$foodfarm_settings['header3-menu-color']};
                }
                @media (min-width:992px){
                    ..header-v3 .main-navigation .mega-menu>li>a{
                        color: {$foodfarm_settings['header3-menu-color']};
                    }
                }
            ";            
        }           
        if(isset($foodfarm_settings['header3-border_color']) && $foodfarm_settings['header3-border_color'] != ''){
            $foodfarm_custom_css .= "
                .header-v3 .right-header .search-block-top, .header-v3 .right-header .mini-cart, .header-v3 .right-header .top-link   {
                    border-color: {$foodfarm_settings['header3-border_color']};
                }
            ";            
        }          
        if(isset($foodfarm_settings['header4-top-color']) && $foodfarm_settings['header4-top-color'] != ''){
            $foodfarm_custom_css .= "
                .header-v4 .link-contact p a,.header-v4 .top-link .customlinks > a{
                    color: {$foodfarm_settings['header4-top-color']};
                }
            ";            
        }
        if(isset($foodfarm_settings['header4-top-bg']) && $foodfarm_settings['header4-top-bg'] != ''){
            $foodfarm_custom_css .= "
                .header-v4 .header-top {
                    background: {$foodfarm_settings['header4-top-bg']};
                }
            ";            
        }         
        if(isset($foodfarm_settings['header4-bg']) && $foodfarm_settings['header4-bg'] != ''){
            $foodfarm_custom_css .= "
                .header-v4 .main-navigation{
                    background: {$foodfarm_settings['header4-bg']};
                }
            ";            
        }  
        if(isset($foodfarm_settings['header4-menu-color']) && $foodfarm_settings['header4-menu-color'] != ''){
            $foodfarm_custom_css .= "
                .header-v4 .right-header{
                    color: {$foodfarm_settings['header4-menu-color']};
                }
                @media (min-width:992px){
                    .header-v4 .mega-menu > li > a{
                        color: {$foodfarm_settings['header4-menu-color']};
                    }
                }
            ";            
        }            
        if(isset($foodfarm_settings['header5-top-bg']) && $foodfarm_settings['header5-top-bg'] != ''){
            $foodfarm_custom_css .= "
                .header-v5 .header-top {
                    background: {$foodfarm_settings['header5-top-bg']};
                }
            ";            
        }         
        if(isset($foodfarm_settings['header5-bg']) && $foodfarm_settings['header5-bg'] != ''){
            $foodfarm_custom_css .= "
                .header-v5{
                    background: {$foodfarm_settings['header5-bg']};
                }
            ";            
        } 
        if(isset($foodfarm_settings['header5-top-color']) && $foodfarm_settings['header5-top-color'] != ''){
            $foodfarm_custom_css .= "
                .header-v5 .header-slogan .link-contact p,.header-v5 .top-link .customlinks>a{
                    color: {$foodfarm_settings['header5-top-color']};
                }
            ";            
        }                
        if(isset($foodfarm_settings['header5-menu-color']) && $foodfarm_settings['header5-menu-color'] != ''){
            $foodfarm_custom_css .= "
                .header-v5 .search-block-top>.btn-search,.header-v5 .mini-cart span{
                    color: {$foodfarm_settings['header5-menu-color']};
                }
                @media (min-width:992px){
                    .header-v5 .main-navigation .mega-menu>li>a{
                        color: {$foodfarm_settings['header5-menu-color']};
                    }
                }
            ";            
        } 
        if(isset($foodfarm_settings['header6-bg']) && $foodfarm_settings['header6-bg'] != ''){
            $foodfarm_custom_css .= "
                .header-v6{
                    background: {$foodfarm_settings['header6-bg']};
                }
            ";            
        } 
        if(isset($foodfarm_settings['header6-top-color']) && $foodfarm_settings['header6-top-color'] != ''){
            $foodfarm_custom_css .= "
                .header-v6 .link-contact span,.header-v6 .top-link .customlinks>a{
                    color: {$foodfarm_settings['header6-top-color']};
                }
            ";            
        }     
        if(isset($foodfarm_settings['header6-menu-color']) && $foodfarm_settings['header6-menu-color'] != ''){
            $foodfarm_custom_css .= "
                .header-v6 .right-header{
                    color: {$foodfarm_settings['header6-menu-color']};
                }
                @media (min-width:992px){
                    .header-v6 .mega-menu>li>a{
                        color: {$foodfarm_settings['header6-menu-color']};
                    }
                }
            ";            
        }  
        if(isset($foodfarm_settings['footer-bottom-bg']) && $foodfarm_settings['footer-bottom-bg'] != ''){
            $foodfarm_custom_css .= "
                .footer-v1 .footer-bottom, .footer-v3 .footer-bottom{
                    background: {$foodfarm_settings['footer-bottom-bg']};
                }
            ";
        }   
        if(isset($foodfarm_settings['footer2-bottom-bg']) && $foodfarm_settings['footer2-bottom-bg'] != ''){
            $foodfarm_custom_css .= "
                .footer-v2 .footer-bottom{
                    background: {$foodfarm_settings['footer2-bottom-bg']};
                }
            ";
        }          
        if(isset($foodfarm_settings['footer-left-color']) && $foodfarm_settings['footer-left-color'] != ''){
            $foodfarm_custom_css .= "
                .footer-home p, .footer-home .list-info li, .footer-home .list-info li a{
                    color: {$foodfarm_settings['footer-left-color']};
                }
            ";
        }       
        if(isset($foodfarm_settings['footer-wtitle-color']) && $foodfarm_settings['footer-wtitle-color'] != ''){
            $foodfarm_custom_css .= "
                .footer .widget-title, .footer .newsletter-footer .newsletter-title,
                .footer-v3 .newsletter-footer h4{
                    color: {$foodfarm_settings['footer-wtitle-color']};
                }
            ";
        }           
        if(isset($foodfarm_settings['footer-link-color']) && $foodfarm_settings['footer-link-color'] != ''){
            $foodfarm_custom_css .= "
                .footer a,.footer-v4 .widget li a,.footer-v5 .widget li a{
                    color: {$foodfarm_settings['footer-link-color']};
                }
            ";
        }      
        if(isset($foodfarm_settings['footer-bottom-text-color']) && $foodfarm_settings['footer-bottom-text-color'] != ''){
            $foodfarm_custom_css .= "
                .footer address,.footer-v1 address a{
                    color: {$foodfarm_settings['footer-bottom-text-color']};
                }
            ";
        }       
        if(isset($foodfarm_settings['footer-col-border']) && $foodfarm_settings['footer-col-border'] != ''){
            $foodfarm_custom_css .= "
                .footer-menu-list .list-style,.footer-menu-list .list-style:last-child,.newsletter-footer{
                    border-color: {$foodfarm_settings['footer-col-border']};
                }
            ";
        }
        if(isset($foodfarm_settings['footer2-wtitle-color']) && $foodfarm_settings['footer2-wtitle-color'] != ''){
            $foodfarm_custom_css .= "
                .footer-v2 .newsletter-footer h4,.footer .footer-v2 .widget-title{
                    color: {$foodfarm_settings['footer2-wtitle-color']};
                }
            ";
        }       
        if(isset($foodfarm_settings['footer2-left-color']) && $foodfarm_settings['footer2-left-color'] != ''){
            $foodfarm_custom_css .= "
                .footer-v2 .footer-home p,.footer-v2 .footer-home .list-info li, .footer-v2 .footer-home .list-info li a{
                    color: {$foodfarm_settings['footer2-left-color']};
                }
            ";
        }       
        if(isset($foodfarm_settings['footer3-left-color']) && $foodfarm_settings['footer3-left-color'] != ''){
            $foodfarm_custom_css .= "
                .footer-v3 .footer-home p{
                    color: {$foodfarm_settings['footer3-left-color']};
                }
            ";
        }       
        if(isset($foodfarm_settings['footer-bg-4']) && $foodfarm_settings['footer-bg-4'] != ''){
            $foodfarm_custom_css .= "
                .footer-v4 .footer-top,.footer-v5 .footer-top{
                    background: {$foodfarm_settings['footer-bg-4']};
                }
            ";
        }   
        if(isset($foodfarm_settings['footer4-bottom-bg']) && $foodfarm_settings['footer4-bottom-bg'] != ''){
            $foodfarm_custom_css .= "
                .footer-v4 .footer-bottom,.footer-v5 .footer-bottom{
                    background: {$foodfarm_settings['footer4-bottom-bg']};
                }
            ";
        }  
        if(isset($foodfarm_settings['footer4-left-color']) && $foodfarm_settings['footer4-left-color'] != ''){
            $foodfarm_custom_css .= "
                .footer-v4 .footer-home p,.footer-v4 .footer-home .list-info li, .footer-v4 .footer-home .list-info li a,.footer-v5 .footer-home p,.footer-v5 .footer-home .list-info li, .footer-v5 .footer-home .list-info li a{
                    color: {$foodfarm_settings['footer4-left-color']};
                }
            ";
        }       
        if(isset($foodfarm_settings['footer6-bottom-text-color']) && $foodfarm_settings['footer6-bottom-text-color'] != ''){
            $foodfarm_custom_css .= "
                .footer-v6 .footer-bottom address{
                    color: {$foodfarm_settings['footer6-bottom-text-color']};
                }
            ";
        }     
        if(isset($foodfarm_settings['footer6-left-color']) && $foodfarm_settings['footer6-left-color'] != ''){
            $foodfarm_custom_css .= "
                .footer-v6 .footer-center .list-info li,.footer-v6 .footer-center .list-info li a{
                    color: {$foodfarm_settings['footer6-left-color']};
                }
            ";
        }     
        if(isset($foodfarm_settings['header9-bg']) && $foodfarm_settings['header9-bg'] != ''){
            $foodfarm_custom_css .= "
                .header-v9 .main-navigation{
                    background: {$foodfarm_settings['header9-bg']};
                }
            ";
        }  
        if(isset($foodfarm_settings['header9-menu-color']) && $foodfarm_settings['header9-menu-color'] != ''){
            $foodfarm_custom_css .= "
                .header-v9 .mega-menu > li > a, .header-v9 .right-header, .header-v9 a.cart_label,
                .header-v9 .search-block-top > .btn-search,.header-v9 .header-right .h_icon,.header-v9 .open-menu-mobile{
                    color: {$foodfarm_settings['header9-menu-color']};
                }
            ";
        }  
        if(isset($foodfarm_settings['footer9-bg']) && $foodfarm_settings['footer9-bg'] !=''){
            $foodfarm_custom_css .= "
                .footer-v9{
                    background-image: url('{$foodfarm_settings['footer9-bg']['background-image']}');
                    background-repeat: {$foodfarm_settings['footer9-bg']['background-repeat']};
                    background-position: {$foodfarm_settings['footer9-bg']['background-position']};
                    background-size: {$foodfarm_settings['footer9-bg']['background-size']};
                    background-attachment: {$foodfarm_settings['footer9-bg']['background-attachment']};  
                    background-color:  {$foodfarm_settings['footer9-bg']['background-color']};  
                }
            ";            
        }    
        if(isset($foodfarm_settings['footer9-newletter_bg']) && $foodfarm_settings['footer9-newletter_bg'] !=''){
            $foodfarm_custom_css .= "
                .footer-v9 .newsletter-footer{ 
                    background-color:  {$foodfarm_settings['footer9-newletter_bg']};  
                }
            ";             
        }  
        if(isset($foodfarm_settings['footer9-newletter-color']) && $foodfarm_settings['footer9-newletter-color']){
             $foodfarm_custom_css .= "
                .footer-v9 .newsletter-footer .newsletter-title h4{ 
                    color:  {$foodfarm_settings['footer9-newletter-color']};  
                }
            ";            
        }    
        if(isset($foodfarm_settings['footer9-text-color']) && $foodfarm_settings['footer9-text-color'] !=''){
             $foodfarm_custom_css .= "
                .footer-v9 .list-info li, .footer-v9 .list-info li a, .footer-v9 a{ 
                    color:  {$foodfarm_settings['footer9-text-color']};  
                }
            ";              
        }  
        if(isset($foodfarm_settings['footer9-bottom-color']) && $foodfarm_settings['footer9-bottom-color'] !=''){
             $foodfarm_custom_css .= "
                .footer-v9 .footer-bottom address, .footer-v9 .payment li a{ 
                    color:  {$foodfarm_settings['footer9-bottom-color']};  
                }
            ";             
        }  
        if(isset($foodfarm_settings['footer9-bottom-bg']) && $foodfarm_settings['footer9-bottom-bg'] !=''){
             $foodfarm_custom_css .= "
                .footer-v9 .footer-bottom{ 
                    background:  {$foodfarm_settings['footer9-bottom-bg']};  
                }
            ";             
        }           
        if(isset($foodfarm_settings['footer9-title-color']) && $foodfarm_settings['footer9-title-color'] !=''){
             $foodfarm_custom_css .= "
                .footer .footer-v9 .widget-title{ 
                    color:  {$foodfarm_settings['footer9-title-color']};  
                }
            ";             
        } 
        if(isset($foodfarm_settings['footer9-social-color'])&& $foodfarm_settings['footer9-social-color'] !=''){
            $foodfarm_custom_css .= "
                .footer-v9 .menu-footer-social li a{
                    border-color: {$foodfarm_settings['footer9-social-color']};
                    color: {$foodfarm_settings['footer9-social-color']};
                }
            ";
        }                                              
    //Load font icon css
    // wp_enqueue_style('foodfarm-font-common', get_template_directory_uri() . '/css/icon-font.css?ver=' . foodfarm_version);
    wp_enqueue_style('foodfarm-font', get_template_directory_uri() . '/css/pe-icon/pe-icon-7-stroke.css?ver=' . foodfarm_version);
    wp_enqueue_style('foodfarm-prettyphoto', get_template_directory_uri() . '/css/prettyPhoto.css?ver=' . foodfarm_version);
    wp_enqueue_style('slick', get_template_directory_uri() . '/css/slick.css?ver=' . foodfarm_version);
    if (is_rtl()) {
        //Load plugins RTL css
        wp_enqueue_style('foodfarm-plugins-rtl', get_template_directory_uri() . '/css/plugins_rtl.css?ver=' . foodfarm_version);
        //Load theme RTL css
        wp_enqueue_style('foodfarm-theme-rtl', get_template_directory_uri() . '/css/theme_rtl.css?ver=' . foodfarm_version);
    }
    else{
        //Load plugins css
        wp_enqueue_style('foodfarm-plugins', get_template_directory_uri() . '/css/plugins.css?ver=' . foodfarm_version);
        //Load theme css
        wp_enqueue_style('foodfarm-theme', get_template_directory_uri() . '/css/theme.css?ver=' . foodfarm_version);
    }
    // Load skin stylesheet
    wp_enqueue_style('foodfarm-skin', foodfarm_css . '/config/skin.css?ver=' . foodfarm_version);
    wp_add_inline_style( 'foodfarm-skin', $foodfarm_custom_css );
    // Loads our main stylesheet.
    wp_enqueue_style('foodfarm-style', get_stylesheet_uri());
    // Load Google Fonts
    $gfont = array('Open+Sans','Courgette','Lora','Dancing+Script');
    if (isset($foodfarm_settings['general-font']['google']) && $foodfarm_settings['general-font']['google']) {
        $font = urlencode($foodfarm_settings['general-font']['font-family']);
        if (!in_array($font, $gfont))
            $gfont[] = $font;
    }
    $font_family = '';
    foreach ($gfont as $font)
        $font_family .= $font . ':300,300italic,400,400italic,600,600italic,700,700italic,800,800italic%7C';

    if ($font_family) {
        wp_register_style( 'foodfarm-google-fonts', "//fonts.googleapis.com/css?family=" . $font_family . "&amp;subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese" );
        wp_enqueue_style( 'foodfarm-google-fonts' );
    }
}
add_action('wp_enqueue_scripts', 'foodfarm_scripts_styles');

//Disable all woocommerce styles
add_filter('woocommerce_enqueue_styles', '__return_false');

function foodfarm_scripts_js() {
    global $foodfarm_settings, $wp_query;
    $cat = $wp_query->get_queried_object();
    if(isset($cat->term_id)){
    $woo_cat = $cat->term_id;
    }else{
        $woo_cat = '';
    }
    $shop_list = '';
    if ( class_exists( 'WooCommerce' ) ) {
    $shop_list = is_product_category();
    }
    $product_list_mode = get_metadata('product_cat', $woo_cat, 'list_mode_product', true);
    $header_sticky_mobile = isset($foodfarm_settings['header-sticky-mobile'])? $foodfarm_settings['header-sticky-mobile'] : '';    
    $ff_main_color = isset($foodfarm_settings['primary-color'])? $foodfarm_settings['primary-color'] : '#94c347';
    $ff_text_day = (isset($foodfarm_settings['under_contr-day']) && $foodfarm_settings['under_contr-day'] != '') ? $foodfarm_settings['under_contr-day'] : 'Days'; 
    $ff_text_hour = (isset($foodfarm_settings['under_contr-hour']) && $foodfarm_settings['under_contr-hour'] != '') ? $foodfarm_settings['under_contr-hour'] : 'Hours';  
    $ff_text_min = (isset($foodfarm_settings['under_contr-min']) && $foodfarm_settings['under_contr-min'] != '') ? $foodfarm_settings['under_contr-min'] : 'Minutes';  
    $ff_text_sec = (isset($foodfarm_settings['under_contr-sec']) && $foodfarm_settings['under_contr-sec'] != '') ? $foodfarm_settings['under_contr-sec'] : 'Seconds';           
    // comment reply
    if ( is_singular() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    // Loads our main js.
    
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), foodfarm_version, true);
    wp_enqueue_script('prety-photo', get_template_directory_uri() . '/js/un-minify/jquery.prettyPhoto.js', array('jquery'), foodfarm_version, true);
    wp_enqueue_script('imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array(), foodfarm_version, true);
    wp_enqueue_script('isotope', get_template_directory_uri() . '/js/isotope.pkgd.min.js', array(), foodfarm_version, true);
    wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js', array(), foodfarm_version, true);
    wp_enqueue_script('owlcarousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), foodfarm_version, true);
    wp_enqueue_script('time-circles', get_template_directory_uri() . '/js/un-minify/time-circles.js', array(), foodfarm_version, true);
    wp_enqueue_script('scrollreveal', get_template_directory_uri() . '/js/un-minify/scrollReveal.js', array(), foodfarm_version, true);
    wp_enqueue_script('elevate-zoom', get_template_directory_uri() . '/js/un-minify/jquery.elevatezoom.js', array('jquery'), foodfarm_version, true);
    wp_enqueue_script('validate', get_template_directory_uri() . '/js/jquery.validate.min.js', array('jquery'), foodfarm_version);
    wp_enqueue_script('foodfarm-script', get_template_directory_uri() . '/js/un-minify/functions.js', array(), foodfarm_version, true);
    wp_localize_script('foodfarm-script', 'foodfarm_params', array(
        'ajax_url' => esc_js(admin_url( 'admin-ajax.php' )),
        'ajax_loader_url' => esc_js(str_replace(array('http:', 'https'), array('', ''), foodfarm_css . '/images/ajax-loader.gif')),
        'ajax_cart_added_msg' => esc_html__('A product has been added to cart.', 'foodfarm'),
        'ajax_compare_added_msg' => esc_html__('A product has been added to compare', 'foodfarm'),
        'type_product' => $product_list_mode,
        'shop_list' => $shop_list,
        'ff_text_day' => $ff_text_day,
        'ff_text_hour' => $ff_text_hour,
        'ff_text_min' => $ff_text_min,
        'ff_text_sec' => $ff_text_sec,        
        'ff_main_color' => $ff_main_color,
        'header_sticky' => $foodfarm_settings['header-sticky'],
        'header_sticky_mobile' => $header_sticky_mobile
    ));
}
add_action('wp_enqueue_scripts', 'foodfarm_scripts_js');
function foodfarm_override_mce_options($initArray) {
    $opts = '*[*]';
    $initArray['valid_elements'] = $opts;
    $initArray['extended_valid_elements'] = $opts;
    return $initArray;
} 
add_filter('tiny_mce_before_init', 'foodfarm_override_mce_options'); 

function foodfarm_get_current_url($echo = true) {
    global $wp;
    if($echo) {
        echo home_url(add_query_arg(array(),$wp->request));
    } else {
        return home_url(add_query_arg(array(),$wp->request));
    }
}

if (class_exists( 'YITH_WOOCOMPARE' ) ){
    function foodfarm_compare_page_title($sep = '', $display = true, $title = '') {
        if($title != '') {
            return esc_attr($title);
        }
    }
    add_filter( 'wp_title', 'foodfarm_compare_page_title', 100, 3 );
}
//Defer parsing of JavaScript
if (!(is_admin() )) {
    function foodfarm_defer_parsing_of_js ( $url ) {
        if ( FALSE === strpos( $url, '.js' ) ) return $url;
        if ( strpos( $url, 'jquery.js' ) ) return $url;
        // return "$url' defer ";
        return "$url' defer onload='";
    }
    add_filter( 'clean_url', 'foodfarm_defer_parsing_of_js', 11, 1 );
}

function filter_woocommerce_loop_add_to_cart_link( $quantity, $product ) 
{ 


    return sprintf('<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">
    <i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;%s</a>',

    esc_url( $product->add_to_cart_url() ),
    esc_attr( isset( $quantity ) ? $quantity : 1 ),
    esc_attr( $product->get_id() ),
    esc_attr( $product->get_sku() ),
    esc_attr( isset( $class ) ? $class : 'button' ),
    esc_html( $product->add_to_cart_text()),$product );

}; 

add_filter( 'woocommerce_loop_add_to_cart_link', 'filter_woocommerce_loop_add_to_cart_link', 10, 2 ); 


add_filter('widget_text','do_shortcode');


 function instagram_link_shortcode() {
	global $wp;
   $current_url =  home_url( $wp->request );   
	return 'https://www.instagram.com/'; 
}
add_shortcode('instagram_share_url', 'instagram_link_shortcode');

function facebook_link_shortcode() {
	global $wp;
   $current_url =  home_url( $wp->request );   
  
	return "http://www.facebook.com/sharer.php?u=".$current_url; 
}
add_shortcode('facebook_share_url', 'facebook_link_shortcode');

function google_link_shortcode() {
	global $wp;
	$current_url =  home_url( $wp->request );  
	return "https://plus.google.com/share?url=".$current_url;
}
add_shortcode('google_share_url', 'google_link_shortcode');

function twitter_link_shortcode() {
	global $wp;
   $current_url =  home_url( $wp->request );  
 
	return  'https://twitter.com/share?url='.$current_url.'&text='.get_the_title(); 
}
add_shortcode('twitter_share_url', 'twitter_link_shortcode');

function email_link_shortcode() {
	global $wp;
   $current_url =  home_url( $wp->request );  
 
	return  'mailto:?subject=I wanted you to see this site&amp;body=Check out this site '.$current_url; 

	//'https://twitter.com/share?url='.$current_url.'&text='.get_the_title(); 
}
add_shortcode('email_share_url', 'email_link_shortcode');





add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );



add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { 
$u_meta = get_user_meta($user->ID, 'gov_id_one', true);
$uploads = wp_upload_dir();

$uploaddir = 'http://medicinemanshop.ca/wp-content/uploads/users/'.$u_meta; 
		 
?>

	<h3>Government ID *</h3>

	<table class="form-table">

		<tr>
			<th><label for="twitter">Government ID *</label></th>

			<td>
				<?php if(!empty($u_meta)){
				?>	
				<a href="<?php echo $uploaddir; ?>" download> Click Here To Download Government Id</a>
				<?php 	
				}else{
					echo 'No Government Id Uploaded By User';
					
				}?>
				 
			</td>
		</tr>

	</table>
<?php }


function new_modify_user_table( $column ) {
    $column['gov_id'] = 'Government ID'; 
    $column['is_old_user'] = 'Old User'; 
    $column['is_gift_send'] = 'Gift Status'; 
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
			case 'gov_id' :
			$u_meta = get_user_meta($user_id, 'gov_id_one', true);
			$uploads = wp_upload_dir(); 
			$uploaddir = 'http://medicinemanshop.ca/wp-content/uploads/users/'.$u_meta; 
			 if(!empty($u_meta)){
				 
				return '<a href="'.$uploaddir.'" download> Click Here To Download Government Id</a>';
		 
				}else{
					return 'No Government Id Uploaded By User';
					
				}  
            break;
			case 'is_old_user' :
			$old = get_user_meta($user_id, 'is_old_user', true);
			 
			 if($old == 'true'){
				 
				return 'Old User';
		 
				}else{
				return 'New User';
					
				}  
            break;
			case 'is_gift_send' :
			$u_meta = get_user_meta($user_id, 'is_gift_send', true);
			 
			 if($u_meta == 'true'){
				 
				return 'Gift Sent';
		 
				}else{
					return 'Gift Not Sent';
					
				}  
            break;
			
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );


add_action( 'show_user_profile', 'my_show_gift_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_gift_extra_profile_fields' );

function my_show_gift_extra_profile_fields( $user ) { 

$old = get_user_meta($user->ID, 'is_old_user', true);
			 
 if($old == 'true'){
?>

	<h3>Gift Status</h3>

	<table class="form-table">

		<tr>
			<th><label for="twitter">Gift Status</label></th>

			<td>
				 <select name="is_gift_send">
					<option value="false"> Gift Not Send </option>
					<option value="true">Gift Send</option>
				 </select> 
			</td>
		</tr>

	</table>
  <?php } }
  
 
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );


// Send notification email when customer changes address.
add_action( 'woocommerce_customer_save_address','notify_admin_customer_address_change', 10, 2);
function notify_admin_customer_address_change( $user_id ) {
 
 
global $woocommerce, $current_user;
  $telephone = $_POST['shipping_phone'];
 
   if ( !empty( $telephone ) ) {
			global $wpdb;
				$querystr = "SELECT * FROM md3_old_user WHERE phone = $telephone"; 
				$pageposts = $wpdb->get_results($querystr, OBJECT);
				 
				
				 if(count($pageposts) > 0){  
				  $old = get_user_meta($user_id, 'is_old_user', true);
				 if($old == 'true'){
				 
				 
				 }else{
				 
					update_user_meta($user_id, 'is_old_user', 'true');
					update_user_meta($user_id, 'is_gift_send', 'false');
				 }
				
					
		 
				} 
				}
		  
		 
}


function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'is_gift_send', $_POST['is_gift_send'] );
}

function email_template($content){
	
	$html = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- If you delete this tag, the sky will fall on your head -->
<meta name="viewport" content="width=device-width" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Medicine Man </title>
<style>
 * {margin:0;padding:0;}
* { font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; }
img {max-width: 100%; }
.collapse {margin:0;padding:0;}
body {-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; width: 100%!important; height: 100%;}
a { color: #2BA6CB;}
.btn {text-decoration:none;color: #FFF;background-color: #666;padding:10px 16px;font-weight:bold;margin-right:10px;text-align:center;cursor:pointer;display: inline-block;}
p.callout {padding:15px;background-color:#ECF8FF;margin-bottom: 15px;}
.callout a {font-weight:bold;color: #2BA6CB;}
table.social {/* 	padding:15px; */background-color: #ebebeb;}
.social .soc-btn {padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;}
a.fb { background-color: #3B5998!important; }
a.tw { background-color: #1daced!important; }
a.gp { background-color: #DB4A39!important; }
a.ms { background-color: #000!important; }
.sidebar .soc-btn { display:block;width:100%;}
table.head-wrap { width: 100%;}
.header.container table td.logo { padding: 15px; }
.header.container table td.label { padding: 15px; padding-left:0px;}
table.body-wrap { width: 100%;}
table.footer-wrap { width: 100%;	clear:both!important;}
.footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
.footer-wrap .container td.content p {font-size:10px;font-weight: bold;}
h1,h2,h3,h4,h5,h6 {font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;}
h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
h1 { font-weight:200; font-size: 44px;}
h2 { font-weight:200; font-size: 37px;}
h3 { font-weight:500; font-size: 27px;}
h4 { font-weight:500; font-size: 23px;}
h5 { font-weight:900; font-size: 17px;}
h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#fff;}
.collapse { margin:0!important;}
p, ul { margin-bottom: 10px; font-weight: normal; font-size:14px;line-height:1.6;}
p.lead { font-size:17px; }
p.last { margin-bottom:0px;}
ul li {margin-left:5px;list-style-position: inside;}
ul.sidebar {background:#ebebeb;display:block;list-style-type: none;}
ul.sidebar li { display: block; margin:0;}
ul.sidebar li a {text-decoration:none;color: #666;padding:10px 16px;/* 	font-weight:bold; */margin-right:10px;/* 	text-align:center; */cursor:pointer;border-bottom: 1px solid #777777;border-top: 1px solid #FFFFFF;display:block;margin:0;
}
ul.sidebar li a.last { border-bottom-width:0px;}
ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
.container {display:block!important;max-width:600px!important;margin:0 auto!important;clear:both!important;}
.content {padding:15px;max-width:600px;margin:0 auto;display:block; }
.content table { width: 100%; }
.column {width: 300px;float:left;}
.column tr td { padding: 15px; }
.column-wrap { padding:0!important; margin:0 auto; max-width:600px!important;}
.column table { width:100%;}
.social .column {width: 280px;min-width: 279px;float:left;}
.clear { display: block; clear: both; }
@media only screen and (max-width: 600px) {
a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
div[class="column"] { width: auto!important; float:none!important;}
table.social div[class="column"] {
width:auto!important;
}
}
</style>
</head>
<body bgcolor="#FFFFFF">
<!-- HEADER -->
<table class="head-wrap" bgcolor="#3A3A3B">
	<tr>
		<td></td>
		<td class="header container">
			
				<div class="content">
					<table bgcolor="#3A3A3B">
					<tr>
						<td><img src="http://medicinemanshop.ca/wp-content/uploads/2017/08/medicineman-logo.png" /></td>
						<td align="right"><h6 class="collapse">Medicine Man</h6></td>
					</tr>
				</table>
				</div>
		</td>
		<td></td>
	</tr>
</table><!-- /HEADER -->
<!-- BODY -->
<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" bgcolor="#FFFFFF"> 
			<div class="content">
			<table>
				<tr>
					<td>
						 '.$content.'
						<table class="social" width="100%">
							<tr>
								<td>
									
									<!--- column 1 -->
									<table align="left" class="column">
										<tr>
											<td>				
												
												<h5 class=""> Thanks and enjoy!</h5>
												<p class=""> Medicine Man team</p>
						
											</td>
										</tr>
									</table><!-- /column 1 -->	
									
									<!--- column 2 -->
									<!--table align="left" class="column">
										<tr>
											<td>				
																			
												<h5 class="">Contact Info:</h5>												
												<p>Phone: <strong>xxx.xxx.xxx</strong><br/>
												Email: <strong><a href="emailto:hello@medicineman.menu">hello@medicineman.menu</a></strong></p>
                
											</td>
										</tr>
									</table--><!-- /column 2 -->
									
									<span class="clear"></span>	
									
								</td>
							</tr>
						</table><!-- /social & contact -->
					
					
					</td>
				</tr>
			</table>
			</div>
									
		</td>
		<td></td>
	</tr>
</table><!-- /BODY --> 
<!-- FOOTER -->
<table class="footer-wrap">
	<tr>
		<td></td>
		<td class="container">
			
				<!-- content -->
				<div class="content">
				<table>
				<tr>
					<td align="center">
						<p>
							<a target="_blank" href="http://medicinemanshop.ca/terms-conditions/">Terms</a> |
							<a target="_blank" href="http://medicinemanshop.ca/privacy-policy/">Privacy</a>  
							 
						</p>
					</td>
				</tr>
			</table>
				</div>
		</td>
		<td></td>
	</tr>
</table>
</body>
</html>';
return $html; 
	
}

// Register New Order Statuses
function wpex_wc_register_post_statuses() {
    register_post_status( 'wc-shipped', array(
        'label'                     => _x( 'Shipped', 'WooCommerce Order status', 'text_domain' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Approved (%s)', 'Approved (%s)', 'text_domain' )
    ) );
}
add_filter( 'init', 'wpex_wc_register_post_statuses' );

// Add New Order Statuses to WooCommerce
function wpex_wc_add_order_statuses( $order_statuses ) {
    $order_statuses['wc-shipped'] = _x( 'Shipped', 'WooCommerce Order status', 'text_domain' );
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wpex_wc_add_order_statuses' );

/*
function order_status_changed_to_shipped( $post_id, $post, $update ) {
   echo $new_status;
   die;
	if($new_status == 'shipped'){
	
	$order = wc_get_order( $id );

		// Get the order ID
		$order_id = $order->get_id();
		global $wpdb;
		 
		echo "SELECT meta_value FROM md3_postmeta where post_id = $order_id and meta_key = 'woo_shipment_type'";
		$myrows = $wpdb->get_results( "SELECT meta_value FROM md3_postmeta where post_id = $order_id and meta_key = 'woo_shipment_type'" );
		print_r($myrows);
		die('tetstwet');
		echo $woo_shipment_type = get_post_meta( $order_id, 'woo_shipment_type' ); 	 
		echo $woo_order_tracking_number	 = get_post_meta( $order_id ,'woo_order_tracking_number'); 
		echo '<per>';
	 
		$content2 = '<h3>Hello, '.$order->billing_first_name.'</h3>
						<br>
						<h3><small>Your order status changed <b>'.$old_status.'</b> to  <b>Shipped</b>. You can Track your order using below Tracking Id.</small></h3>
						 <br/>
						<h4>Order Tracking ID :</h4> 
						<p>id: '.$id.'<p> 
						<p>Click here to track your order or use below url.<p> 
						<p>https://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?execution=e1s1<p> 
						<br/>';
		$html1 = email_template($content2);	 
 
		$to = 'manishchhipa007@gmail.com' ; //$order->billing_email;
		   
		   
		wc_mail($to, __('Your Order #'.$id.' has been Shipped '), $html1);	
die('test');
	}
		die('sdfsdfsd');
}

add_action( 'save_post', 'order_status_changed_to_shipped', 10, 3 );*/

 function attributes_save_postdata($post_id) {
	 
	$new_status = $_POST['order_status'];
	if($new_status == 'wc-shipped'){ 
	$order = wc_get_order( $post_id );
	
	$woo_shipment_type = $_POST['fields']['field_59d5e222be91b'];
	
	if($woo_shipment_type == 'Canada Post'){
	$woo_order_tracking_number = $_POST['fields']['field_59d5e273be91c'];
	 $content2 = '<h3>Hello, '.$order->shipping_first_name.'</h3>
						<br>
						<h3><small>Your order status changed <b>Shipped</b>. You can Track your order using below Tracking Id.</small></h3>
						 <br/>
						<h4>Order Tracking ID :</h4> 
						<p>Tracking Number: '.$woo_order_tracking_number.'<p> 
						<p>Click <a target="_blank" href="https://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?execution=e1s1">here</a> to track your order or use below url.<p> 
						<p>https://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?execution=e1s1<p> 
						<br/>';
	}else{
	$content2 = '<h3>Hello, '.$order->shipping_first_name.'</h3>
						<br>
						<h3><small>Your order status changed to <b>Shipped</b>. You can Track your order using below Tracking Id.</small></h3>
						 <br/>
						<h4>Order Tracking ID :</h4> 
						<p>Order Order/Tracking ID: '.$post_id.'<p> 
						<p>Click <a  target="_blank" href="http://medicinemanshop.ca/demo/order-track/">here</a> to track your order or use below url.<p> 
						<p>http://medicinemanshop.ca/order-track/<p> 
						<br/>';
	
	}
	
	 $html1 = email_template($content2);	 
 
	 $to = $order->shipping_email; 
	 wc_mail($to, __('Your Order #'.$id.' has been Shipped '), $html1);	
 
	}
	
	
 
}

add_action('save_post', 'attributes_save_postdata');


 
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
 
function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
    unset($fields['order']['order_comments']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_email']);
    unset($fields['billing']['billing_city']);
    return $fields;
}

/*
//This code paste on funtion.php  
//For upload image

add_filter( 'woocommerce_shipping_fields', 
'woo_filter_upload_shipping'        );

function woo_filter_upload_shipping( $address_fields ) { 
//  $address_fields['file_upload']['required'] = true;

$address_fields['file_upload'] = array(
//'label'     => __('Upload your ID', 'woocommerce'),
'required'  => false,
'class'     => array('form-row-wide'),
'clear'     => true
);

 return $address_fields;
 }

 //Using this function to show Upload field on your checkout page.

 function add_file_field(){

 $uploadFile   = "";
 $uploadFile   .='<div id="upload_CNIC_image">';
 $uploadFile .='<input id="file_upload" name="file_upload"
 type="file"    multiple="true">';
 $uploadFile .='<span id="">';
 $uploadFile .='</span>';
 $uploadFile .='</div>';
 echo $uploadFile;
 }
 add_action('woocommerce_after_order_notes','add_file_field');
*/


function remove_core_updates(){
    global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates');
add_filter('pre_site_transient_update_plugins','remove_core_updates');
add_filter('pre_site_transient_update_themes','remove_core_updates');



add_action( 'woocommerce_thankyou', 'bbloomer_checkout_save_user_meta');
function bbloomer_checkout_save_user_meta($order_id) {
 
    $order = new WC_Order( $order_id );
	 
	if($order->shipping_city == 'Calgary' || $order->shipping_city == 'Calgary' )
	{
		
	$myuser_id = (int)$order->user_id;
    $user_info = get_userdata($myuser_id);
    
	$content2 = '<h3>Hi '.$order->shipping_first_name.',</h3>
				
				<p> Medicine Man welcomes you to our shop! </p>
				<p>By purchasing through our website, you can collect reward points and use them for exclusive gifts or lots of discounts on your future purchases.</p>
				<p>Alternatively, if you are a Calgary local, you can contact us at <a>+1 (587) 410-7143</a> to place an order for same day delivery.</p>
				 <br/>
					
				<br/>'; 
	   $html1 = email_template($content2);	 
 
	 $to = $order->shipping_email; 
	 wc_mail($to, __('Thank you for shopping from Calgary.'), $html1);	
 
	
	}
    
    return $order_id;
	
	
}

add_filter('woocommerce_add_cart_item_data','wdm_add_item_data',1,10);
function wdm_add_item_data($cart_item_data, $product_id) {

    global $woocommerce;
    $new_value = array();
    $new_value['_quentity_type'] = $_POST['quentity_type'];
    $new_value['_quentity_number'] = $_POST['quentity_number'];

    if(empty($cart_item_data)) {
        return $new_value;
    } else {
        return array_merge($cart_item_data, $new_value);
    }
}
/*
 function iconic_display_engraving_text_cart( $product_name, $cart_item, $cart_item_key ) {
 
    if ( empty( $cart_item['_quentity_type'] ) ) {
        return $product_name;
    }
 
    return sprintf( '%s <p><strong>%s</strong>: %s</p>', $product_name, __( 'Engraving', 'iconic' ), $cart_item['_quentity_type'] .'('. $cart_item['_quentity_number']. ')' );
}
 
add_filter( 'woocommerce_cart_item_name', 'iconic_display_engraving_text_cart', 10, 3 );*/

function iconic_add_engraving_text_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( empty( $values['_quentity_type'] ) ) {
        return;
    }
 
 	$dummyName = array('ounce' => 'OUNCE','half-ounce' => 'HALF OUNCE','quarter' => 'QUARTER','eighth' => 'EIGHTH');
	 $dummyVal = array('ounce' => '8','half-ounce' => '4','quarter' => '2','eighth' => '1');
    $item->add_meta_data( __( 'Product Type', '_quentity_type' ), $dummyName[$values['_quentity_type']] .' ('. $values['_quentity_number']. ')' );
    
}
 
add_action( 'woocommerce_checkout_create_order_line_item', 'iconic_add_engraving_text_to_order_items', 10, 4 );
 
?>