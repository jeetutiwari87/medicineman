<?php
$foodfarm_settings = foodfarm_check_theme_options();
?>
<div class="header-top">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-sm-9 col-xs-8">
                <div class="header-contact">
                    <div class="dib link-contact">
                        <?php if (!empty($foodfarm_settings['header-contact-phonenumber'])): ?>
                            <p><a href="callto:<?php echo $foodfarm_settings['header-contact-phonenumber'] ?>"><i class="fa fa-phone"></i><span><?php echo $foodfarm_settings['header-contact-phonenumber'] ?></span></a></p>
                        <?php endif; ?>
                        <?php if (!empty($foodfarm_settings['header-contact-email'])): ?>
                            <p><a href="mailto:<?php echo $foodfarm_settings['header-contact-email'] ?>"><i class="fa fa-envelope"></i><span><?php echo $foodfarm_settings['header-contact-email'] ?></span></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-sm-3 col-xs-4">
                <?php
                $compare = false;
                if (class_exists('YITH_WOOCOMPARE')) {
                    $compare = true;
                }
                $wishlist = false;
                if (class_exists('YITH_WCWL')) {
                    $wishlist = true;
                }
                $myaccount_page_id = get_option('woocommerce_myaccount_page_id');
                $cart_page_id = get_option('woocommerce_cart_page_id');
                $checkout_page_id = get_option('woocommerce_checkout_page_id');
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
                        <li class="customlinks"><a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>"><i class="fa fa-user"></i> <?php echo esc_html__('My Account', 'foodfarm') ?></a>
                        </li>
                        <?php if (!is_user_logged_in()): ?>
                            <li class="customlinks"><a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>"><i class="fa fa-lock" aria-hidden="true"></i>
 <?php echo esc_html__('Login / Register', 'foodfarm') ?></a></li>
                        <?php else: ?>
                            <li class="customlinks"><a href="<?php echo esc_url($logout_url) ?>"><i class="fa fa-lock" aria-hidden="true"></i>
 <?php echo esc_html__('Logout', 'foodfarm') ?></a></li>
                        <?php endif; ?>
                        <li class="customlinks"><a href="<?php echo esc_url(get_permalink($cart_page_id)); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
 <?php echo esc_html__('Shopping Cart', 'foodfarm') ?></a>
                        </li>
                        <?php if ($wishlist): ?>
                            <li class="customlinks"><a class="update-wishlist" href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>"><i class="fa fa-heart"></i> <?php echo esc_html__('Wishlist', 'foodfarm') ?> <span>(<?php echo YITH_WCWL()->count_products(); ?>)</span></a></li>
                        <?php endif; ?>
                        <li class="customlinks"><a href="<?php echo esc_url(get_permalink($checkout_page_id)); ?>"><i class="fa fa-sign-in" aria-hidden="true"></i> <?php echo esc_html__('Checkout', 'foodfarm') ?></a>
                        </li>
                    </ul>
                    <ul class="hidden-lg hidden-md display-inline">
                        <li class="dib customlinks">
                            <a href="#" aria-expanded="false" aria-haspopup="true" data-toggle="dropdown">
                                <i class="fa fa-user"></i><span><?php echo esc_html__('My Account','foodfarm');?></span> 
                                <i class="fa fa-caret-down"></i>
                            </a>
                            <div class="dib header-profile dropdown-menu">
                                <ul>
                                    <li><a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>"><?php echo esc_html__('My Account', 'foodfarm') ?></a></li>
                                    <?php if ($wishlist && $foodfarm_settings['product-wishlist']): ?>
                                        <li><a class="update-wishlist" href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>"><?php echo esc_html__('Wishlist', 'foodfarm') ?> <span>(<?php echo yith_wcwl_count_products(); ?>)</span></a></li>
                                    <?php endif; ?>
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 menu-left">
                <?php
                $before_items_wrap = '<button class="btn-open"><i class="fa fa-bars"></i></button>';

                if ($foodfarm_settings['logo_4']) {
                    $logo_img = '';
                    if ($foodfarm_settings['logo_4'] && $foodfarm_settings['logo_4']['url']) {
                        $logo_img = '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo_4']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
                    }
                    if(is_front_page()){
                    	$page_logo = '<h1 class="header-logo">';
                    	$page_end_logo = '</h1>';
                    }
                    else{
                    	$page_logo ='<h2 class="header-logo">';
                    	$page_end_logo = '</h2>';
                    }
                    $before_items_wrap .= '<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 no-padding">
                                                '.$page_logo.'
                                                    <a href="' . esc_url(home_url('/')) . '" rel="home">' . $logo_img . '</a>
                                                '.$page_end_logo.'
                                            </div>';
                }
                $after_item_wrap = '<div class="right-header">';
                if ($foodfarm_settings['header-search']) {
                    $search_template = foodfarm_get_search_form();
                    $after_item_wrap .= '<div class="search-block-top">' . $search_template . '</div>';
                }
                $after_item_wrap .= '</div>';
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'mega-menu',
                        'items_wrap' => $before_items_wrap . '<div class=" col-lg-9 col-md-12 col-sm-12 col-xs-12 no-padding">' . $after_item_wrap . '<ul id="%1$s" class="%2$s">%3$s</ul>' . '</div>',
                        'walker' => new Foodfarm_Primary_Walker_Nav_Menu()
                            )
                    );
                }
                ?>
            </div>
        </div>
    </div>
</nav>