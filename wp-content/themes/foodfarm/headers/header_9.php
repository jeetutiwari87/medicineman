<?php
$foodfarm_settings = foodfarm_check_theme_options();
?>
<nav class="main-navigation" id="site-navigation">
    <div class="container">
        <div class="">
            <?php if (is_front_page()) : ?>
                <h1 class="header-logo">
                <?php else : ?>
                    <h2 class="header-logo">
                    <?php endif; ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php
                        if ($foodfarm_settings['logo_9'] && $foodfarm_settings['logo_9']['url']):
                            echo '<img class="" width="81" height="auto" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo_9']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
                        elseif ($foodfarm_settings['logo'] && $foodfarm_settings['logo']['url']):
                            echo '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
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
            <div class="header_menu">         
                <?php
                $before_items_wrap = '';
                if ($foodfarm_settings['header-search']) {
                    $search_template = foodfarm_get_search_form();
                    $after_item_wrap = '<div class="search-block-top">' . $search_template . '</div>';
                }                
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'mega-menu',
                        'items_wrap' => $before_items_wrap . '<button class="open-menu-mobile hidden-lg hidden-md"><i class="fa fa-bars"></i></button>'  . '<ul id="%1$s" class="%2$s">%3$s</ul>'. $after_item_wrap,
                        'walker' => new Foodfarm_Primary_Walker_Nav_Menu()
                            )
                    );
                }
                ?>
            </div> 
            <div class="header-right"> 
                <?php if (isset($foodfarm_settings['header-minicart']) && $foodfarm_settings['header-minicart'] && class_exists('WooCommerce')) :
                    $minicart_template = foodfarm_get_minicart_template();?>
                    <div id="mini-cart" class="mini-cart display-inline-b"> <?php echo $minicart_template; ?> </div>
                <?php endif; ?>
                <?php if (isset($foodfarm_settings['header-accountlink']) && $foodfarm_settings['header-accountlink']) :?>                        
                <?php 
                    $toplink_template = foodfarm_myaccount_toplinks();
                    echo '<div class="header-account top-link">' . $toplink_template . '</div>';
                ?>
                <?php endif; ?>
            </div> 
        </div>
    </div>
</nav>
<div class="header-mobile">
    <a id="advanced-menu-hide" href="#" class="close-menu-mobile"><i class="fa fa-times" aria-hidden="true"></i></a>
    <?php if (isset($foodfarm_settings['header-accountlink']) && $foodfarm_settings['header-accountlink']) :?> 
        <?php 
            $toplink_template = foodfarm_myaccount_toplinks();
            echo '<div class="header-account top-link">' . $toplink_template . '</div>';
        ?>       
    <?php endif; ?>
    <nav class="mobile-advanced" id="mobile-advanced">
        <?php     
            $before_items_wrap = '';        
            if ($foodfarm_settings['header-search']) {
                $search_template = foodfarm_get_search_form();
                $before_items_wrap = '<div class="search-block-top">' . $search_template . '</div>';
            }            
            $after_item_wrap = '';
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'mega-menu',
                    'items_wrap' => $before_items_wrap . '<ul id="%1$s" class="%2$s">%3$s</ul>' . $after_item_wrap,
                    'walker' => new foodfarm_Primary_Walker_Nav_Menu()
                        )
                );
        }?>
    </nav>
</div>