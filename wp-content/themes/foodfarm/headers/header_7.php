<?php
$foodfarm_settings = foodfarm_check_theme_options();
?>
<div class="header-top">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-8 col-xs-9">
                <div class="header-contact">
                    <div class="dib text-slogan">
                        <?php if(isset($foodfarm_settings['header7-top-text']) && $foodfarm_settings['header7-top-text'] != ''):?>
                            <p><?php echo esc_html($foodfarm_settings['header7-top-text']);?></p>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-4 col-xs-3">
                <?php
                global $woocommerce;
                $compare = false;
                if (class_exists('YITH_WOOCOMPARE')) {
                    $compare = true;
                }
                $wishlist = false;
                if (class_exists('YITH_WCWL')) {
                    $wishlist = true;
                }
                $myaccount_page_id = get_option('woocommerce_myaccount_page_id');
                $logout_url = wp_logout_url(get_permalink($myaccount_page_id));
                if (get_option('woocommerce_force_ssl_checkout') == 'yes') {
                    $logout_url = str_replace('http:', 'https:', $logout_url);
                }
                ?>
                <div class="top-link">
                    <ul class="hidden-sm hidden-xs display-inline">
                        <?php
                            foodfarm_show_language_dropdown();
                            foodfarm_show_currencies_dropdown();   
                        ?>                         
                        <li class="customlinks"><a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>"><?php echo esc_html__('My Account', 'foodfarm') ?></a></li>

                        <?php if (!is_user_logged_in()): ?>
                            <li class="customlinks"><a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>"><?php echo esc_html__('Login / Register', 'foodfarm') ?></a></li>
                        <?php else: ?>
                            <li class="customlinks"><a href="<?php echo esc_url($logout_url) ?>"><?php echo esc_html__('Logout', 'foodfarm') ?></a></li>
                        <?php endif; ?>
                        <?php 
                        if ( class_exists( 'WooCommerce' ) ) {
                            $cart_item_count = WC()->cart->cart_contents_count;
                        }
                        ?>
                        <?php if (class_exists( 'WooCommerce' ) &&  $cart_item_count> 0 ) :
                            echo '<li class="customlinks" ><a href="' . esc_url($woocommerce->cart->get_checkout_url()) . '">' . esc_html__( 'Checkout','foodfarm' ) . '</a></li>';
                        endif;?>                         
                    </ul>
                    <ul class="hidden-lg hidden-md display-inline">
                         <?php
                            foodfarm_show_language_dropdown();
                            foodfarm_show_currencies_dropdown();   
                        ?>                    
                        <li class="dib customlinks">
                            <a href="#" aria-expanded="false" aria-haspopup="true" data-toggle="dropdown">
                                <i class="fa fa-user"></i><span><?php echo esc_html__('My Account','foodfarm');?></span> 
                                <i class="fa fa-caret-down"></i>
                            </a>
                            <div class="dib header-profile dropdown-menu">
                                <ul>
                                    <li><a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>"><?php echo esc_html__('My Account', 'foodfarm') ?></a></li>
                                    <?php if (!is_user_logged_in()): ?>
                                        <li><a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>"><?php echo esc_html__('Login / Register', 'foodfarm') ?></a></li>
                                    <?php else: ?>
                                        <li><a href="<?php echo esc_url($logout_url) ?>"><?php echo esc_html__('Logout', 'foodfarm') ?></a></li>
                                    <?php endif; ?>
                                    <?php 
                                    if ( class_exists( 'WooCommerce' ) ) {
                                        $cart_item_count = WC()->cart->cart_contents_count;
                                    }
                                    ?>
                                    <?php if (class_exists( 'WooCommerce' ) &&  $cart_item_count> 0 ) :
                                        echo '<li><a href="' . esc_url($woocommerce->cart->get_checkout_url()) . '" >' . esc_html__( 'Checkout','foodfarm' ) . '</a></li>';
                                    endif;?>                                    
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header-bottom">
    <div class="container">
        <div class="col-md-3 col-sm-12 col-xs-12">
            <?php if (is_front_page()) : ?>
                <h1 class="header-logo">
                <?php else : ?>
                    <h2 class="header-logo">
                    <?php endif; ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php
                        if (isset($foodfarm_settings['logo_7']) && $foodfarm_settings['logo_7'] && $foodfarm_settings['logo_7']['url']):
                            echo '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo_7']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
                        else:
                            bloginfo('name');
                        endif;
                        ?>
                    </a>
                    <?php if (is_front_page()) : ?>
                </h1>
            <?php else : ?>
                </h2>
            <?php endif; ?>
        </div>
        <div class="col-md-5 col-sm-12 col-xs-12 hidden-xs">
            <?php if (isset($foodfarm_settings['header7-banner']) && !empty($foodfarm_settings['header7-banner'])): ?>
                <div class="header-banner">         
                    <?php echo wp_kses($foodfarm_settings['header7-banner'],                          
                    array(
                        'h5' => array(
                            'class' => array(),
                        ),
                        'h6' => array(
                            'class' => array(),
                        ),
                        'h4' => array(
                            'class' => array(),
                        ), 
                        'h3' => array(
                            'class' => array(),
                        ), 
                        'h2' => array(
                            'class' => array(),
                        ),                                                        
                        'a' => array(
                            'href' => array(),
                            'title' => array(),
                            'target' => array(),
                        ),
                        'i' => array(
                            'class' => array(),
                            'aria-hidden' => array(),
                        ),                                
                    )); ?>
                </div>
            <?php endif;?>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 header_contact_info">
        <?php if (!empty($foodfarm_settings['header-contact-phonenumber'])): ?>
            <a href="callto:<?php echo $foodfarm_settings['header-contact-phonenumber'] ?>"><i class="main_color fa fa-phone"></i><span><?php echo $foodfarm_settings['header-contact-phonenumber'] ?></span></a>
        <?php endif; ?>
        <?php if (!empty($foodfarm_settings['header-contact-email'])): ?>
            <a href="mailto:<?php echo $foodfarm_settings['header-contact-email'] ?>"><i class="main_color fa fa-envelope"></i><span><?php echo $foodfarm_settings['header-contact-email'] ?></span></a>
        <?php endif; ?>  
        <?php  if ($foodfarm_settings['header-search']) {
                    $search_template = foodfarm_get_search_form();?>
                    <div class="search-block-top"><?php echo $search_template; ?></div>
        <?php   } ?>
        </div>     
    </div>
</div>
<!-- Menu -->
<nav id="site-navigation" class="main-navigation">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 menu-left">
                <?php
                $before_items_wrap = '<button class="btn-open"><i class="fa fa-bars"></i></button>';
                if ($foodfarm_settings['logo_7']) {
                    $logo_img = '';
                    if ($foodfarm_settings['logo_7'] && $foodfarm_settings['logo_7']['url']) {
                        $logo_img = '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo_7']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
                    }
                    if(is_front_page()){
                        $page_logo = '<h1 class="header-logo">';
                        $page_end_logo = '</h1>';
                    }
                    else{
                        $page_logo ='<h2 class="header-logo">';
                        $page_end_logo = '</h2>';
                    }
                    $before_items_wrap .=$page_logo.'
                                                    <a href="' . esc_url(home_url('/')) . '" rel="home">' . $logo_img . '</a>
                                                '.$page_end_logo;
                }
                $after_item_wrap = '<div class="right-header">';
                if ($foodfarm_settings['header-search']) {
                    $search_template = foodfarm_get_search_form();
                    $after_item_wrap .= '<div class="search-block-top">'.$search_template.'</div>';
                }
                if ($foodfarm_settings['header-minicart'] && class_exists('WooCommerce')) {
                    $minicart_template = foodfarm_get_minicart_template();
                    $after_item_wrap .= '<div id="mini-scart" class="mini-cart">' . $minicart_template . '</div>';
                }
                $after_item_wrap .= '</div>';
                
                if (has_nav_menu('fruit_menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'fruit_menu',
                        'menu_class' => 'mega-menu',
                        'items_wrap' => $before_items_wrap . '<ul id="%1$s" class="%2$s">%3$s</ul>' . $after_item_wrap,
                        'walker' => new Foodfarm_Primary_Walker_Nav_Menu()
                            )
                    );
                }else if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'mega-menu',
                        'items_wrap' => $before_items_wrap . '<ul id="%1$s" class="%2$s">%3$s</ul>' . $after_item_wrap,
                        'walker' => new Foodfarm_Primary_Walker_Nav_Menu()
                            )
                    );
                }
                ?>
            </div>
        </div>
    </div>
</nav>