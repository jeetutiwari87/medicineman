<?php    
//get search template
if(! function_exists('foodfarm_get_search_ajax')){
    function foodfarm_get_search_ajax(){
        global $foodfarm_settings;
        $output = '';
        ob_start();
        ?>   
            <span class="btn-search" onclick="toggleFilter(this);"><i class="fa fa-search"></i></span>
            <div class="top-search content-filter">
                <form class="ajax-search" method="post" action="<?php echo esc_url(home_url('/'));?>" >
                    <div class="woosearch-input-box hidecat">
                        <input type="text" name="s" class="ajax-search-input woocommerce-product-search product-search search-field" value="" placeholder="<?php echo esc_attr_x( 'Enter keyword&hellip;', 'placeholder', 'efarm' ); ?>" autocomplete="off" data-number="4" data-keypress="2">
                    <div><div class="woosearch-results"></div></div>
                    </div>        
                    <button type="submit" class="woosearch-submit submit btn-search"> 
                        <i class="pe-7s-search"></i>
                        <i class="fa fa-spin"></i>
                    </button>            
                    <input type="hidden" name="post_type" value="product" />
                </form> 
            </div>  
        <?php
        $output .= ob_get_clean();
        return $output;
    }
}
if ( ! function_exists( 'foodfarm_get_search_form' ) ) {
function foodfarm_get_search_form() {
    global $foodfarm_settings;
    if(isset($foodfarm_settings['enable_search_ajax']) && $foodfarm_settings['enable_search_ajax']){
        $output = foodfarm_get_search_ajax();
        return $output;  
    }else{
        $template = get_search_form(false);
        
        if(class_exists( 'WooCommerce' )) {
            if(isset($foodfarm_settings['header_search_type']) && $foodfarm_settings['header_search_type'] =='1'){
                $template = get_product_search_form(false);
            }
        }
        $output = '';
        ob_start();
        ?>
            <span class="btn-search" onclick="toggleFilter(this);"><i class="fa fa-search"></i></span>
            <div class="top-search content-filter">
                <?php echo $template ?>
            </div>
        <?php
        $output .= ob_get_clean();
        return $output;  
              
    }
    
}
}
//mini cart template
if ( class_exists( 'WooCommerce' ) ) {
    function foodfarm_get_minicart_template() {
        global $woocommerce;
        $cart_item_count = WC()->cart->cart_contents_count;
        $header_type = foodfarm_get_header_type();
        $totalamount = $woocommerce->cart->get_cart_total();  
        $output = '';
        ob_start();
        ?>
            <?php if($header_type == '7') :?>
                <a class="cart_label" onclick="toggleFilter(this);" href="javascript:void(0);">
                    <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                    <p class="number-product"><?php echo esc_html($cart_item_count); ?></p> 
                     <span><?php echo esc_html__('items ','foodfarm');?>- </span>
                    <span><?php echo $totalamount;?></span> 
                </a>                
            <?php else:?>
                <a class="cart_label h_icon" onclick="toggleFilter(this);" href="javascript:void(0);">
                    <?php if($header_type != '2') :?>
                        <i class="fa fa-shopping-basket"></i>
                    <?php else :?>
                        <span class="icon-6"></span>
                    <?php endif;?> 
                     <?php if($header_type != '8') :?>
                    <p class="number-product"><?php echo $cart_item_count ?></p> 
                    <?php endif;?>  
                </a>
            <?php endif;?> 
            <div class="cart-block content-filter">
                <div class="widget_shopping_cart_content">
                </div>
            </div>
        <?php
        $output .= ob_get_clean();
        return $output;
    }
}
// top link myaccout
function foodfarm_myaccount_toplinks() {
    $wishlist = false;
    global $foodfarm_settings;
    if(class_exists('YITH_WCWL')) {
        $wishlist = true;
    }
    $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' ); 
    $logout_url = wp_logout_url('my-account');
    $output = '';
    ob_start();
    ?>
    <?php if (isset($foodfarm_settings['header-accountlink']) && $foodfarm_settings['header-accountlink']):?>
    <ul>
        <li class="dib customlinks">
            <a class="current-open h_icon" onclick="toggleFilter(this);" href="javascript:void(0);">
                <?php if(foodfarm_get_header_type() =='9'):?>
                    <i class="fa fa-user"></i>
                <?php else:?>
                    <i class="fa fa-gear"></i>
                <?php endif;?>
            </a>
            <div class="dib header-profile dropdown-menu content-filter">
                    <ul>
                        <li><a href="<?php echo get_permalink( $myaccount_page_id ); ?>"><?php echo esc_html__('My Account', 'foodfarm') ?></a></li>
                        <?php if($wishlist && $foodfarm_settings['product-wishlist']): ?>
                        <li><a class="update-wishlist" href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>"><?php echo esc_html__('Wishlist', 'foodfarm') ?> <span>(<?php echo yith_wcwl_count_products(); ?>)</span></a></li>
                        <?php endif; ?>
                        <?php if (class_exists( 'YITH_WOOCOMPARE' ) && $foodfarm_settings['product-compare'] ) :?>
                        <li>
                            <?php
                                foodfarm_compare_toplink();
                            ?>
                        </li>
                        <?php endif;?>
                        <?php if ( !is_user_logged_in() ) :?> 
						<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php echo esc_html__('Login','foodfarm'); ?>"><?php echo esc_html__('Login','foodfarm'); ?></a></li>
						<li><a href="<?php echo get_permalink(952); ?>" title="<?php echo esc_html__('Register','foodfarm'); ?>"><?php echo esc_html__(' Register','foodfarm'); ?></a></li>
						
                        <?php else :?>
                        <li><a href="<?php echo esc_url($logout_url); ?>"><?php echo esc_html__('Logout', 'foodfarm') ?></a></li>
                        <?php endif; ?>
                    </ul>
            </div>
        </li>
    </ul>
    <?php endif;?>    
   <?php
   $output .= ob_get_clean();
    return $output;
}
function foodfarm_get_layout() {
    global $wp_query, $foodfarm_settings, $foodfarm_layout;
    $result = '';
    if (empty($foodfarm_layout)) {
        $result = isset($foodfarm_settings['layout']) ? $foodfarm_settings['layout'] : 'fullwidth';
        if (is_404()) {
            $result = 'fullwidth';
        } else if (is_category()) {
            $result = $foodfarm_settings['post-layout'];
        } else if (is_archive()) {
            if (function_exists('is_shop') && is_shop()) {
                $shop_layout = get_post_meta(wc_get_page_id('shop'), 'layout', true);
                $result = !empty($shop_layout) && $shop_layout != 'default' ? $shop_layout : $foodfarm_settings['shop-layout'];
            } else {
                if (is_post_type_archive('gallery')) {
                    $result = $foodfarm_settings['gallery-layout'];
                } 
                else if(is_post_type_archive('recipe')){
                    $result = $foodfarm_settings['recipe-layout']; 
                }
                else if(is_post_type_archive('pressmedia')){
                    $result = $foodfarm_settings['pressmedia-layout']; 
                }
                else {
                    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                    if ($term) {
                        $tax_layout = get_metadata($term->taxonomy, $term->term_id, 'layout', true);
                        switch ($term->taxonomy) {
                            case 'product_cat':
                                if(!empty($tax_layout) && $tax_layout != 'default') {
                                    $result = $tax_layout;
                                } else {
                                    $result = $foodfarm_settings['shop-layout'];
                                }
                                break;
                            case 'product_tag':
                                $result = $foodfarm_settings['shop-layout'];
                                break;
                            case 'gallery_cat':
                                $result = $foodfarm_settings['gallery-layout'];
                                break;
                            case 'recipe_cat':
                                $result = $foodfarm_settings['recipe-layout'];
                                break;
                            case 'pressmedia_cat':
                                $result = $foodfarm_settings['pressmedia-layout'];
                                break;        
                            case 'gallery':
                                $result = $foodfarm_settings['post-layout'];
                                break;
                            default:
                                $result = $foodfarm_settings['layout'];
                        }
                    }
                }
            }
        } else {
            if (is_singular()) {
                $single_layout = get_post_meta(get_the_id(), 'layout', true);
                if (!empty($single_layout) && $single_layout != 'default') {
                    $result = $single_layout;
                } else {
                    switch (get_post_type()) {
                        case 'gallery':
                            $result = $foodfarm_settings['gallery-layout'];
                            break;
                        case 'recipe':
                            $result = $foodfarm_settings['recipe-layout'];
                            break;
                        case 'pressmedia':
                            $result = $foodfarm_settings['pressmedia-layout'];
                            break;
                        case 'product':
                            $result = $foodfarm_settings['single-product-layout'];
                            break;
                        case 'post':
                            $result = $foodfarm_settings['post-layout'];
                            break;
                        default:
                            $result = $foodfarm_settings['layout'];
                    }
                }
            } else {
                if (is_home() && !is_front_page()) {
                    $result = $foodfarm_settings['post-layout'];
                }
            }
        }
        $foodfarm_layout = $result;
    }    
    return $foodfarm_layout;
}
//get global sidebar position
function foodfarm_get_sidebar_position() {
    $result = '';
    global $wp_query, $foodfarm_settings, $foodfarm_sidebar_pos;
    if(empty($foodfarm_sidebar_pos)){
        $result = isset($foodfarm_settings['sidebar-position']) ? $foodfarm_settings['sidebar-position'] : 'none';
        if (is_404()) {
            $result = 'none';
        } else if (is_category()) {
            $cat = $wp_query->get_queried_object();
            $cat_sidebar = get_metadata('category', $cat->term_id, 'sidebar_position', true);
            if (!empty($cat_sidebar) && $cat_sidebar != 'default') {
                    $result = $cat_sidebar;
                }
            else{   
                $result = $foodfarm_settings['post-sidebar-position'];
            }
        } else if (is_archive()) {
            if (function_exists('is_shop') && is_shop()) {
                $shop_sidebar_position = get_post_meta(wc_get_page_id('shop'), 'sidebar_position', true);
                $result = !empty($shop_sidebar_position) && $shop_sidebar_position != 'default' ? $shop_sidebar_position : $foodfarm_settings['shop-sidebar-position'];
            } else {
                if (is_post_type_archive('gallery')) {
                    if(isset($foodfarm_settings['gallery-sidebar-position'])){
                        $result = $foodfarm_settings['gallery-sidebar-position'];
                    }else{
                        $result = $foodfarm_settings['sidebar-position'];
                    }
                }else if(is_post_type_archive('recipe')){
                    if(isset($foodfarm_settings['recipe-sidebar-position'])){
                        $result = $foodfarm_settings['recipe-sidebar-position'];                        
                    }else{
                        $result = $foodfarm_settings['sidebar-position'];
                    }
                }else if(is_post_type_archive('pressmedia')){
                    if(isset($foodfarm_settings['press-sidebar-position'])){
                        $result = $foodfarm_settings['press-sidebar-position'];                        
                    }else{
                        $result = $foodfarm_settings['sidebar-position'];
                    }
                }
                else {
                    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                    if ($term) {
                        $tax_sidebar_pos = get_metadata($term->taxonomy, $term->term_id, 'sidebar_position', true);
                        switch ($term->taxonomy) {
                            case 'product_cat':
                                if(!empty($tax_sidebar_pos) && $tax_sidebar_pos != 'default') {
                                    $result = $tax_sidebar_pos;
                                } else {
                                    $result = $foodfarm_settings['shop-sidebar-position'];
                                }
                                break;
                            case 'product_tag':
                                $result = $foodfarm_settings['shop-sidebar-position'];
                                break;
                            case 'gallery_cat':
                                $result = $foodfarm_settings['gallery-sidebar-position'];
                                break;
                            case 'recipe_cat':
                                $result = $foodfarm_settings['recipe-sidebar-position'];
                                break;
                            case 'pressmedia_cat':
                                $result = $foodfarm_settings['press-sidebar-position'];
                                break;
                            case 'recipe_tag':
                                $result = $foodfarm_settings['recipe-sidebar-position'];
                                break;
                            case 'category':
                                if(!empty($tax_sidebar_pos) && $tax_sidebar_pos != 'default') {
                                    $result = $tax_sidebar_pos;
                                } else {
                                    $result = $foodfarm_settings['post-sidebar-position'];
                                }
                                break;
                            case 'tag':
                                    $result = $foodfarm_settings['post-sidebar-position'];
                                break; 
                            default:
                                $result = $foodfarm_settings['sidebar-position'];
                        }
                    }
                }
            }
        } else {
            if (is_singular()) {
                $single_sidebar_position = get_post_meta(get_the_id(), 'sidebar_position', true);
                if (!empty($single_sidebar_position) && $single_sidebar_position != 'default') {
                    $result = $single_sidebar_position;
                } else {
                    switch (get_post_type()) {
                        case 'gallery':
                            $result = $foodfarm_settings['gallery-sidebar-position'];
                            break;
                        case 'product':
                            $result = $foodfarm_settings['single-product-sidebar-position'];
                            break;
                        case 'recipe':
                            if(isset($foodfarm_settings['recipe-sidebar-position'])){
                                $result = $foodfarm_settings['recipe-sidebar-position'];
                            }else{
                                $result = $foodfarm_settings['sidebar-position'];
                            }
                            break;
                        case 'pressmedia':
                            if(isset($foodfarm_settings['press-sidebar-position'])){
                                $result = $foodfarm_settings['press-sidebar-position'];
                            }else{
                                $result = $foodfarm_settings['sidebar-position'];
                            }
                            break;    
                        case 'post':
                            $result = $foodfarm_settings['post-sidebar-position'];
                            break;
                        default:
                            $result = $foodfarm_settings['sidebar-position'];
                    }
                }
            } else {
                if (is_home() && !is_front_page()) {
                    $result = $foodfarm_settings['post-sidebar-position'];
                }
            }
        }
        $foodfarm_sidebar_pos = $result;
    }
    return $foodfarm_sidebar_pos;
}

//get global sidebar
function foodfarm_get_sidebar() {
    $result = '';
    global $wp_query, $foodfarm_settings, $foodfarm_sidebar;
    if(empty($foodfarm_sidebar)){
        $result = isset($foodfarm_settings['sidebar']) ? $foodfarm_settings['sidebar'] : 'none';
        if (is_404()) {
            $result = 'none';
        } else if (is_category()) {
            $cat = $wp_query->get_queried_object();
            $cat_sidebar = get_metadata('category', $cat->term_id, 'sidebar', true);
            if (!empty($cat_sidebar) && $cat_sidebar != 'default') {
                    $result = $cat_sidebar;
                }
            else{   
                $result = $foodfarm_settings['post-sidebar'];
            }
        } else if (is_archive()) {
            if (function_exists('is_shop') && is_shop()) {
                $shop_sidebar = get_post_meta(wc_get_page_id('shop'), 'sidebar', true);
                $result = !empty($shop_sidebar) && $shop_sidebar != 'default' ? $shop_sidebar : $foodfarm_settings['shop-sidebar'];
            } else {
                if (is_post_type_archive('gallery')) {
                    if(isset($foodfarm_settings['gallery-sidebar'])){
                        $result = $foodfarm_settings['gallery-sidebar'];  
                    }else{
                        $result = $foodfarm_settings['sidebar']; 
                    }  
                } else if(is_post_type_archive('recipe')){
                    if(isset($foodfarm_settings['recipe-sidebar'])){
                        $result = $foodfarm_settings['recipe-sidebar'];  
                    }else{
                        $result = $foodfarm_settings['sidebar']; 
                    }  
                } else if(is_post_type_archive('pressmedia')){
                    if(isset($foodfarm_settings['press-sidebar'])){
                        $result = $foodfarm_settings['press-sidebar'];  
                    }else{
                        $result = $foodfarm_settings['sidebar']; 
                    }  
                } else {
                    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                    if ($term) {
                        $tax_sidebar = get_metadata($term->taxonomy, $term->term_id, 'sidebar', true);
                        switch ($term->taxonomy) {
                            case 'product_cat':
                                if(!empty($tax_sidebar) && $tax_sidebar != 'default') {
                                    $result = $tax_sidebar;
                                } else {
                                    $result = $foodfarm_settings['shop-sidebar'];
                                }
                                break;
                            case 'product_tag':
                                $result = $foodfarm_settings['shop-sidebar'];
                                break;
                            case 'gallery_cat':
                                if(isset($foodfarm_settings['gallery-sidebar'])){
                                    $result = $foodfarm_settings['gallery-sidebar'];
                                }else{
                                    $result = $foodfarm_settings['sidebar'];
                                }
                                break;
                            case 'recipe_cat':
                                if(isset($foodfarm_settings['recipe-sidebar'])){
                                    $result = $foodfarm_settings['recipe-sidebar'];
                                }else{
                                    $result = $foodfarm_settings['sidebar'];
                                }
                                break;
                            case 'pressmedia_cat':
                                if(isset($foodfarm_settings['press-sidebar'])){
                                    $result = $foodfarm_settings['press-sidebar'];
                                }else{
                                    $result = $foodfarm_settings['sidebar'];
                                }
                                break;    
                            case 'recipe_tag':
                                if(isset($foodfarm_settings['recipe-sidebar'])){
                                    $result = $foodfarm_settings['recipe-sidebar'];
                                }else{
                                    $result = $foodfarm_settings['sidebar'];
                                }
                                break;
                            case 'category':
                                if(!empty($tax_sidebar) && $tax_sidebar != 'default') {
                                    $result = $tax_sidebar;
                                } else {
                                    $result = $foodfarm_settings['post-sidebar'];
                                }
                                break;
                            case 'tag':
                                $result = $foodfarm_settings['post-sidebar'];
                                break; 
                            default:
                                $result = $foodfarm_settings['sidebar'];
                        }
                    }
                }
            }
        } else {
            if (is_singular()) {
                $single_sidebar = get_post_meta(get_the_id(), 'sidebar', true);
                if (!empty($single_sidebar) && $single_sidebar != 'default') {
                    $result = $single_sidebar;
                } else {
                    switch (get_post_type()) {
                        case 'gallery':
                            $result = $foodfarm_settings['gallery-sidebar'];
                            break;
                        case 'product':
                            $result = $foodfarm_settings['single-product-sidebar'];
                            break;
                        case 'recipe':
                            $result = $foodfarm_settings['recipe-sidebar'];
                            break;
                        case 'pressmedia':
                            $result = $foodfarm_settings['press-sidebar'];
                            break;    
                        case 'post':
                            $result = $foodfarm_settings['post-sidebar'];
                            break;
                        default:
                            $result = $foodfarm_settings['sidebar'];
                    }
                }
            } else {
                if (is_home() && !is_front_page()) {
                    $result = $foodfarm_settings['post-sidebar'];
                }
            }
        }
        $foodfarm_sidebar = $result;
    } 
    return $foodfarm_sidebar;   
}

function foodfarm_get_header_type() {
    $result = '';
    global $foodfarm_settings, $wp_query, $header_type;
    if (empty($header_type)) {
        $result = isset($foodfarm_settings['header-type']) ? $foodfarm_settings['header-type'] : 1;
        if (is_category()) {
            $cat = $wp_query->get_queried_object();
            $cat_layout = get_metadata('category', $cat->term_id, 'header', true);
            if (!empty($cat_layout) && $cat_layout != 'default') {
                    $result = $cat_layout;
                }
            else{   
                $result = $foodfarm_settings['header-type'];
            }
        } else if (is_archive()) {
            if (function_exists('is_shop') && is_shop()) {
                $shop_layout = get_post_meta(wc_get_page_id('shop'), 'header', true);
                if(!empty($shop_layout) && $shop_layout != 'default') {
                    $result = $shop_layout;
                }
            } 
        } else {
            if (is_singular()) {
                $single_layout = get_post_meta(get_the_id(), 'header', true);
                if (!empty($single_layout) && $single_layout != 'default') {
                    $result = $single_layout;
                }
            } else {
                if (!is_home() && is_front_page()) {
                    $result = $foodfarm_settings['header-type'];
                } else if (is_home() && !is_front_page()) {
                    $posts_page_id = get_option( 'page_for_posts' );
                    $posts_page_layout = get_post_meta($posts_page_id, 'header', true);
                    if (!empty($posts_page_layout) && $posts_page_layout != 'default') {
                        $result = $posts_page_layout;
                    }
                }
            }
        }
        $header_type = $result;
    }
    return $header_type;
}

function foodfarm_get_footer_type() {
    $result = '';
    global $foodfarm_settings, $wp_query, $footer_type;
    if(empty($footer_type)){
        $result = isset($foodfarm_settings['footer-type']) ? $foodfarm_settings['footer-type'] : 1;
        if (is_category()) {
            $cat = $wp_query->get_queried_object();
            $cat_layout = get_metadata('category', $cat->term_id, 'footer', true);
            if (!empty($cat_layout) && $cat_layout != 'default') {
                    $result = $cat_layout;
                }
            else{   
                $result = $foodfarm_settings['footer-type'];
            }
        } else if (is_archive()) {
            if (function_exists('is_shop') && is_shop()) {
                $shop_layout = get_post_meta(wc_get_page_id('shop'), 'footer', true);
                if(!empty($shop_layout) && $shop_layout != 'default') {
                    $result = $shop_layout;
                }
            }
        } else {
            if (is_singular()) {
                $single_layout = get_post_meta(get_the_id(), 'footer', true);
                if (!empty($single_layout) && $single_layout != 'default') {
                    $result = $single_layout;
                }
            } else {
                if (!is_home() && is_front_page()) {
                    $result = $foodfarm_settings['footer-type'];
                } else if (is_home() && !is_front_page()) {
                    $posts_page_id = get_option( 'page_for_posts' );
                    $posts_page_layout = get_post_meta($posts_page_id, 'footer', true);
                    if (!empty($posts_page_layout) && $posts_page_layout != 'default') {
                        $result = $posts_page_layout;
                    }
                }
            }
        }        
        $footer_type = $result;
    }  
    return $footer_type;  
}

//get search template
if ( ! function_exists( 'foodfarm_breadcrumbs' ) ) {
    function foodfarm_breadcrumbs() {
        global $post, $wp_query, $author, $foodfarm_settings;

        $prepend = '';
        $before = '<li>';
        $after = '</li>';
        $home = esc_html__('Home', 'foodfarm');

        $shop_page_id = false;
        $shop_page = false;
        $front_page_shop = false;
        if ( defined( 'WOOCOMMERCE_VERSION' ) ) {
            $permalinks   = get_option( 'woocommerce_permalinks' );
            $shop_page_id = wc_get_page_id( 'shop' );
            $shop_page    = get_post( $shop_page_id );
            $front_page_shop = get_option( 'page_on_front' ) == wc_get_page_id( 'shop' );
        }

        // If permalinks contain the shop page in the URI prepend the breadcrumb with shop
        if ( $shop_page_id && $shop_page && strstr( $permalinks['product_base'], '/' . $shop_page->post_name ) && get_option( 'page_on_front' ) != $shop_page_id ) {
            $prepend = $before . '<a href="' . get_permalink( $shop_page ) . '">' . $shop_page->post_title . '</a> ' . $after;
        }

        if ( ( ! is_home() && ! is_front_page() && ! ( is_post_type_archive() && $front_page_shop ) ) || is_paged() ) {
            echo '<ul class="breadcrumb">';

            if ( ! empty( $home ) ) {
                echo $before . '<a class="home" href="' . apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) . '">' . $home . '</a>' . $after;
            }

            if ( is_home() ) {

                echo $before . single_post_title('', false) . $after;

            } else if ( is_category()) {

                if ( get_option( 'show_on_front' ) == 'page' ) {
                    echo $before . '<a href="' . get_permalink( get_option('page_for_posts' ) ) . '">' . get_the_title( get_option('page_for_posts', true) ) . '</a>' . $after;
                }

                $cat_obj = $wp_query->get_queried_object();
                $this_category = get_category( $cat_obj->term_id );

                echo $before . single_cat_title( '', false ) . $after;

            } elseif ( is_search() ) {

                echo $before . esc_html__( 'Search results for &ldquo;', 'foodfarm' ) . get_search_query() . '&rdquo;' . $after;

            } elseif ( is_tax('product_cat') || is_tax('portfolio_cat')) {

                echo $prepend;

                if ( is_tax('portfolio_cat') ) {
                    $post_type = get_post_type_object( 'portfolio' );
                    echo $before . '<a href="' . get_post_type_archive_link( 'portfolio' ) . '">' . $post_type->labels->singular_name . '</a>' . $after;
                }
                $current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

                $ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );

                foreach ( $ancestors as $ancestor ) {
                    $ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );

                    echo $before . '<a href="' . get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) . '">' . esc_html( $ancestor->name ) . '</a>' . $after;
                }

                echo $before . esc_html( $current_term->name ) . $after;

            } elseif ( is_tax('product_tag') ) {

                $queried_object = $wp_query->get_queried_object();
                echo $prepend . $before . ' ' . esc_html__( 'Products tagged &ldquo;', 'foodfarm' ) . $queried_object->name . '&rdquo;' . $after;

            } elseif ( is_tax('recipe_cat') || is_tax('recipe_tag') ){
                if(isset($foodfarm_settings['recipe_cat_slug'])){
                    $recipe_cat_slug = $foodfarm_settings['recipe_cat_slug'];
                }
                else {$recipe_cat_slug = "recipe_cat"; } 

                if(isset($foodfarm_settings['recipe_tag_slug'])){
                    $recipe_tag_slug = $foodfarm_settings['recipe_tag_slug'];
                }
                else {$recipe_tag_slug = "recipe_tag"; }                                 
                if(is_tax('recipe_cat')){
                    echo $prepend;

                    $current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

                    $ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );

                    foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );

                        echo $before . '<a href="' . get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) . '">' . esc_html( $ancestor->name ) . '</a>' . $after;
                    }

                    echo $before . esc_html( $current_term->name ) . $after;
                }else{
                    $queried_object = $wp_query->get_queried_object();
                        echo $prepend . $before . ' ' . esc_html($recipe_tag_slug).'   /   '. $queried_object->name .  $after;
                }
            } elseif( is_tax('kbe_tags')){
                $queried_object = $wp_query->get_queried_object();
                echo $prepend . $before . ' ' . esc_html__( 'Knowledge tagged &ldquo;', 'foodfarm' ) . $queried_object->name . '&rdquo;' . $after;
            }  elseif ( is_tax('kbe_taxonomy')){

                echo $prepend;

                $current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

                $ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );

                foreach ( $ancestors as $ancestor ) {
                    $ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );

                    echo $before . '<a href="' . get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) . '">' . esc_html( $ancestor->name ) . '</a>' . $after;
                }

                echo $before . esc_html( $current_term->name ) . $after;
            }elseif ( is_day() ) {

                echo $before . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter . $after;
                echo $before . '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $after;
                echo $before . get_the_time('d') . $after;

            } elseif ( is_month() ) {

                echo $before . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $after;
                echo $before . get_the_time('F') . $after;

            } elseif ( is_year() ) {

                echo $before . get_the_time('Y') . $after;

            } elseif ( is_post_type_archive('product') && get_option('page_on_front') !== $shop_page_id ) {

                $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';

                if ( ! $_name ) {
                    $product_post_type = get_post_type_object( 'product' );
                    $_name = $product_post_type->labels->singular_name;
                }

                if ( is_search() ) {

                    echo $before . '<a href="' . get_post_type_archive_link('product') . '">' . $_name . '</a>' . esc_html__( 'Search results for &ldquo;', 'foodfarm' ) . get_search_query() . '&rdquo;' . $after;

                } elseif ( is_paged() ) {

                    echo $before . '<a href="' . get_post_type_archive_link('product') . '">' . $_name . '</a>' . $after;

                } else {

                    echo $before . $_name . $after;

                }

            } else if(is_post_type_archive('recipe')){
                if(isset($foodfarm_settings['recipe-title']) && $foodfarm_settings['recipe-title'] !=""){
                    $post_type = get_post_type_object( get_post_type() );
                    $slug = $post_type->rewrite;
                    echo $before . '<a href="' . get_post_type_archive_link( get_post_type() ) . '">' .force_balance_tags($foodfarm_settings['recipe-title']). '</a>' . $after;
                    // echo $before . $after;
                }else{
                    echo $before . '<a href="' . get_post_type_archive_link() . '">' . esc_html__( 'Recipes', 'foodfarm' ) . '</a>' . $after;
                }

                    
            } elseif ( is_single() && ! is_attachment() ) {

                if ( 'product' == get_post_type() ) {

                    echo $prepend;

                    if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                        $main_term = $terms[0];
                        $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                        $ancestors = array_reverse( $ancestors );

                        foreach ( $ancestors as $ancestor ) {
                            $ancestor = get_term( $ancestor, 'product_cat' );

                            if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                                echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a>' . $after;
                            }
                        }

                        echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a>' . $after;

                    }

                    echo $before . get_the_title() . $after;

                }elseif('recipe' == get_post_type()){
                    if(isset($foodfarm_settings['recipe_slug'])){
                        $recipe_slug = $foodfarm_settings['recipe_slug'];
                    }
                    else {$recipe_slug =  $post_type->labels->singular_name; }  
                        $post_type = get_post_type_object( get_post_type() );
                        $slug = $post_type->rewrite;
                        echo $before . '<a href="' . get_post_type_archive_link( get_post_type() ) . '">' . esc_html($recipe_slug) . '</a>' . $after;
                        echo $before . get_the_title() . $after;                                      
                } elseif ( 'post' != get_post_type() ) {
                    $post_type = get_post_type_object( get_post_type() );
                    $slug = $post_type->rewrite;
                    echo $before . '<a href="' . get_post_type_archive_link( get_post_type() ) . '">' . $post_type->labels->singular_name . '</a>' . $after;
                    echo $before . get_the_title() . $after;

                } else {

                    if ( 'post' == get_post_type() && get_option( 'show_on_front' ) == 'page' ) {
                        echo $before . '<a href="' . get_permalink( get_option('page_for_posts' ) ) . '">' . get_the_title( get_option('page_for_posts', true) ) . '</a>' . $after;
                    }

                    $cat = current( get_the_category() );
                    if ( ( $parents = get_category_parents( $cat, TRUE, $after . $before ) ) && ! is_wp_error( $parents ) ) {
                        echo $before . substr( $parents, 0, strlen($parents) - strlen($after . $before) ) . $after;
                    }
                    echo $before . get_the_title() . $after;

                }

            } elseif ( is_404() ) {

                echo $before . esc_html__( 'Error 404', 'foodfarm' ) . $after;

            } elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' ) {

                $post_type = get_post_type_object( get_post_type() );

                if ( $post_type ) {
                    echo $before . $post_type->labels->singular_name . $after;
                }

            } elseif ( is_attachment() ) {

                $parent = get_post( $post->post_parent );
                $cat = get_the_category( $parent->ID );
                $cat = $cat[0];
                if ( ( $parents = get_category_parents( $cat, TRUE, $after . $before ) ) && ! is_wp_error( $parents ) ) {
                    echo $before . substr( $parents, 0, strlen($parents) - strlen($after . $before) ) . $after;
                }
                echo $before . '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>'. $after;
                echo $before . get_the_title() . $after;

            } elseif ( is_page() && !$post->post_parent ) {

                echo $before . get_the_title() . $after;

            } elseif ( is_page() && $post->post_parent ) {

                $parent_id  = $post->post_parent;
                $breadcrumbs = array();

                while ( $parent_id ) {
                    $page = get_post( $parent_id );
                    $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title( $page->ID ) . '</a>';
                    $parent_id  = $page->post_parent;
                }

                $breadcrumbs = array_reverse( $breadcrumbs );

                foreach ( $breadcrumbs as $crumb ) {
                    echo $before . $crumb . $after;
                }

                echo $before . get_the_title() . $after;

            } elseif ( is_search() ) {

                echo $before . esc_html__( 'Search results for &ldquo;', 'foodfarm' ) . get_search_query() . '&rdquo;' . $after;

            } elseif ( is_tag() ) {

                echo $before . esc_html__( 'Posts tagged &ldquo;', 'foodfarm' ) . single_tag_title('', false) . '&rdquo;' . $after;

            } elseif ( is_author() ) {

                $userdata = get_userdata($author);
                echo $before . esc_html__( 'Author:', 'foodfarm' ) . ' ' . $userdata->display_name . $after;

            }

            if ( get_query_var( 'paged' ) ) {
                echo $before . '&nbsp;(' . esc_html__( 'Page', 'foodfarm' ) . ' ' . get_query_var( 'paged' ) . ')' . $after;
            }

            echo '</ul>';
        } else {
            if ( is_home() && !is_front_page() ) {
                echo '<ul class="breadcrumb">';

                if ( ! empty( $home ) ) {
                    echo $before . '<a class="home" href="' . apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) . '">' . $home . '</a>' . $after;

                    echo $before . force_balance_tags($foodfarm_settings['blog-title']) . $after;
                }

                echo '</ul>';
            }
        }
    }
}
if ( ! function_exists ( 'foodfarm_page_title' ) ) {
    function foodfarm_page_title() {

        global $foodfarm_settings, $post, $wp_query, $author;

        $home = esc_html__('Home', 'foodfarm');

        $shop_page_id = false;
        $front_page_shop = false;
        if ( defined( 'WOOCOMMERCE_VERSION' ) ) {
            $shop_page_id = wc_get_page_id( 'shop' );
            $front_page_shop = get_option( 'page_on_front' ) == wc_get_page_id( 'shop' );
        }

        if ( ( ! is_home() && ! is_front_page() && ! ( is_post_type_archive() && $front_page_shop ) ) || is_paged() ) {

            if ( is_home() ) {

            } else if ( is_category() ) {

                echo single_cat_title( '', false );

            } elseif ( is_search() ) {

                echo esc_html__( 'Search results for &ldquo;', 'foodfarm' ) . get_search_query() . '&rdquo;';

            } elseif ( is_tax('product_cat') || is_tax('portfolio_cat')) {

                $current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

                echo esc_html( $current_term->name );

            } elseif ( is_tax('recipe_cat') || is_tax('recipe_tag') ) {

                $queried_object = $wp_query->get_queried_object();
                echo  $queried_object->name ;

            } elseif ( is_tax('product_tag') ) {

                $queried_object = $wp_query->get_queried_object();
                echo esc_html__( 'Products tagged &ldquo;', 'foodfarm' ) . $queried_object->name . '&rdquo;';

            } elseif(is_tax('kbe_tags')){
                 echo esc_html__( 'Knowledge tagged &ldquo;', 'foodfarm' ) . get_queried_object()->name . '&rdquo;';
            } elseif(is_tax('kbe_taxonomy')){
                 echo esc_html( get_queried_object()->name );
            } elseif ( is_day() ) {

                printf( esc_html__( 'Daily Archives: %s', 'foodfarm' ), get_the_date() );

            } elseif ( is_month() ) {

                printf( esc_html__( 'Monthly Archives: %s', 'foodfarm' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'foodfarm' ) ) );

            } elseif ( is_year() ) {

                printf( esc_html__( 'Yearly Archives: %s', 'foodfarm' ), get_the_date( _x( 'Y', 'yearly archives date format', 'foodfarm' ) ) );

            } elseif ( is_post_type_archive('product') && get_option('page_on_front') !== $shop_page_id ) {

                $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';

                if ( ! $_name ) {
                    $product_post_type = get_post_type_object( 'product' );
                    $_name = $product_post_type->labels->singular_name;
                }

                if ( is_search() ) {
                    echo esc_html__( 'Search results for &ldquo;', 'foodfarm' ) . get_search_query() . '&rdquo;';
                } elseif ( is_paged() ) {
                    echo $_name;
                } else {

                    echo $_name;

                }

            } elseif ( is_post_type_archive('gallery') ) {

                $post_type = get_post_type_object( 'gallery' );
                echo $post_type->labels->name;

            } elseif ( is_post_type_archive('kbe_knowledgebase') ) {

                $post_type = get_post_type_object( 'kbe_knowledgebase' );
                 echo esc_html__( 'Knowledge', 'foodfarm' );

            } else if(is_post_type_archive('recipe')){
                if(isset($foodfarm_settings['recipe-title']) && $foodfarm_settings['recipe-title'] !=""){
                    echo force_balance_tags($foodfarm_settings['recipe-title']);
                }else{
                    echo esc_html__( 'Recipes', 'foodfarm' );
                }
                    
            }else if(is_post_type_archive('pressmedia')){
                if(isset($foodfarm_settings['press-media-title']) && $foodfarm_settings['press-media-title'] !=""){
                    echo force_balance_tags($foodfarm_settings['press-media-title']);
                }else{
                    echo esc_html__( 'Press Media', 'foodfarm' );
                }
                    
            }
            else if ( is_post_type_archive() ) {
                sprintf( esc_html__( 'Archives: %s', 'foodfarm' ), post_type_archive_title( '', false ) );
            } elseif ( is_single() && ! is_attachment() ) {

                if ( 'gallery' == get_post_type() ) {

                    echo get_the_title();

                } else {

                    echo get_the_title();

                }

            } elseif ( is_404() ) {

                echo esc_html__( 'Error 404', 'foodfarm' );

            } elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' ) {

                $post_type = get_post_type_object( get_post_type() );

                if ( $post_type ) {
                    echo $post_type->labels->singular_name;
                }

            } elseif ( is_attachment() ) {

                echo get_the_title();

            } elseif ( is_page() && !$post->post_parent ) {

                echo get_the_title();

            } elseif ( is_page() && $post->post_parent ) {

                echo get_the_title();

            } elseif ( is_search() ) {

                echo esc_html__( 'Search results for &ldquo;', 'foodfarm' ) . get_search_query() . '&rdquo;';

            } elseif ( is_tag() ) {

                echo esc_html__( 'Posts tagged &ldquo;', 'foodfarm' ) . single_tag_title('', false) . '&rdquo;';

            } elseif ( is_author() ) {

                $userdata = get_userdata($author);
                echo esc_html__( 'Author:', 'foodfarm' ) . ' ' . $userdata->display_name;

            }

            if ( get_query_var( 'paged' ) ) {
                echo ' (' . esc_html__( 'Page', 'foodfarm' ) . ' ' . get_query_var( 'paged' ) . ')';
            }
        } else {
            if ( is_home() && !is_front_page() ) {
                if ( ! empty( $home ) ) {
                    echo force_balance_tags($foodfarm_settings['blog-title']);
                }
            }
        }
    }
}
?>