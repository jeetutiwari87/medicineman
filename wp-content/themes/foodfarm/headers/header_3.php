<?php
$foodfarm_settings = foodfarm_check_theme_options();
?>
<nav class="main-navigation" id="site-navigation">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 menu-left">
                <?php
                $before_items_wrap = '';

                if ($foodfarm_settings['logo']) {
                    $logo_img = '';
                    if ($foodfarm_settings['logo'] && $foodfarm_settings['logo']['url']) {
                        $logo_img = '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
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
                if ($foodfarm_settings['header-minicart'] && class_exists('WooCommerce')) {
                    $minicart_template = foodfarm_get_minicart_template();
                    $after_item_wrap .= '<div id="mini-cart" class="mini-cart">' . $minicart_template . '</div>';
                }
                $toplink_template = foodfarm_myaccount_toplinks();
                $after_item_wrap .= '<div class="top-link">' . $toplink_template . '</div>';
                $after_item_wrap .= '</div>';
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'mega-menu',
                        'items_wrap' => $before_items_wrap . '<div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 no-padding"><button class="btn-open"><i class="fa fa-bars"></i></button>' . $after_item_wrap . '<ul id="%1$s" class="%2$s">%3$s</ul>' . '</div>',
                        'walker' => new Foodfarm_Primary_Walker_Nav_Menu()
                            )
                    );
                }
                ?>
            </div>
            <!--Menu vertical -->
            <?php
			if(is_front_page()){
			if ($foodfarm_settings['verticle-menu']) : ?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="open-vertical"><i class="fa fa-gear"></i></div>
                    <div class="vertical-menu">
                        <div class="title-menu">
                            <h2><span><?php echo esc_html__('Our', 'foodfarm'); ?></span> <?php echo esc_html__('Categories', 'foodfarm'); ?></h2>
                        </div>
                        <div class="vertical-menu-content">
                            <?php
                            if (has_nav_menu('vertical_menu')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'vertical_menu',
                                    'menu_class' => 'mega-menu',
                                    'walker' => new Foodfarm_Primary_Walker_Nav_Menu()
                                        )
                                );
                            }
                            ?>
                        </div>  
                    </div>
                </div>
            <?php endif; 
			}?>
            <!--end menu vertical -->
        </div>
    </div>
</nav>