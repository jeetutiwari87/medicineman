<?php
$foodfarm_settings = foodfarm_check_theme_options();
?>
<div class="header-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-8 hidden-xs">
                <div class="header-contact">
                    <div class="dib link-contact">
                        <?php if (!empty($foodfarm_settings['header-time'])): ?>
                            <p><i class="fa fa-clock-o" aria-hidden="true"></i><span><?php echo $foodfarm_settings['header-time'] ?></span></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
                <?php
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
                    <ul>
                        <?php
                                foodfarm_show_language_dropdown();
                                foodfarm_show_currencies_dropdown();   
                        ?>
                        <?php if ($foodfarm_settings['header-minicart'] && class_exists('WooCommerce')) :?>
                            <?php $minicart_template = foodfarm_get_minicart_template(); ?>
                        <li class="customlinks link-checkout">
                            <div id="mini-cart" class="mini-cart">
                                <?php echo $minicart_template; ?>
                            </div>  
                        </li>
                        <?php endif;?>
                        <li class="customlinks">
                            <a href="#" aria-expanded="false" aria-haspopup="true" data-toggle="dropdown">
                                <i class="fa fa-user"></i> <?php echo esc_html__('My Account', 'foodfarm') ?>
                            </a>
                            <div class="dib header-profile dropdown-menu">
                                <ul>
                                    <li><a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>"><?php echo esc_html__('My Account', 'foodfarm') ?></a></li>
                                    <?php if (class_exists('YITH_WOOCOMPARE') && $foodfarm_settings['product-compare']) { ?>
                                        <li>
                                            <?php foodfarm_compare_toplink(); ?>
                                        </li>
                                    <?php } ?>
                                    <?php if (!is_user_logged_in()): ?>
                                        <li><a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>"><?php echo esc_html__('Login / Register', 'foodfarm') ?></a></li>
                                    <?php else: ?>
                                        <li><a href="<?php echo esc_url($logout_url) ?>"><?php echo esc_html__('Logout', 'foodfarm') ?></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Menu -->
<nav class="main-navigation" id="site-navigation">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 logo_center">
                <?php
                $before_items_wrap = '<button class="btn-open"><i class="fa fa-bars"></i></button>';

                if ($foodfarm_settings['logo_6']) {
                    $logo_img = '';
                    if ($foodfarm_settings['logo_6'] && $foodfarm_settings['logo_6']['url']) {
                        $logo_img = '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo_6']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
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
                    $after_item_wrap .= '<div class="search-block-top">' . $search_template . '</div>';
                }
                $after_item_wrap .= '</div>';
                if (has_nav_menu('bakery_menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'bakery_menu',
                        'menu_class' => 'mega-menu',
                        'items_wrap' => $before_items_wrap . '<ul id="%1$s" class="%2$s">%3$s</ul>'. $after_item_wrap,
                        'walker' => new Foodfarm_Primary_Walker_Nav_Menu()
                            )
                    );
                }
                ?>
            </div>
        </div>
    </div>
</nav>