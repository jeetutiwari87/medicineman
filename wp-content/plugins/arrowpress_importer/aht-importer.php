<?php
/*
Plugin Name: ArrowPress Importer
Plugin URI: 
Description: Import Demo Content For Theme
Version: 1.0.0
Author: ArrowPress
Author URI: 
*/


add_action('admin_menu', 'aht_add_demo_import_page');

if ( ! function_exists('aht_add_demo_import_page'))
{
    function aht_add_demo_import_page()
    {
        add_theme_page( esc_html__( 'Import Demos', 'foodfarm' ) , esc_html__( 'Import Demos', 'foodfarm' ) , 'manage_options' , 'aht_demo_import' , 'aht_demo_import' );
    }
}
function aht_demo_types() {
    return array(
        'big-farm' => array('alt' => esc_html__('Home Big Farm', 'foodfarm'), 'img' => plugin_dir_url( __FILE__ ) . '/assets/images/big-farm.jpg'),
        'medium-farm' => array('alt' => esc_html__('Home Medium Farm', 'foodfarm'), 'img' => plugin_dir_url( __FILE__ ) . '/assets/images/medium-farm.jpg'),
        'food-market' => array('alt' => esc_html__('Home Food Market', 'foodfarm'), 'img' => plugin_dir_url( __FILE__ ) . '/assets/images/food-market.jpg'),
        'farm-services' => array('alt' => esc_html__('Home Farm Services', 'foodfarm'), 'img' => plugin_dir_url( __FILE__ ) . '/assets/images/farm-services.jpg'),
        'food-store' => array('alt' => esc_html__('Home Food Store', 'foodfarm'), 'img' => plugin_dir_url( __FILE__ ) . '/assets/images/food-store.jpg'),
        'bakery-cafe' => array('alt' => esc_html__('Home Bakery Cafe', 'foodfarm'), 'img' => plugin_dir_url( __FILE__ ) . '/assets/images/bakery-cafe.jpg'),
        'farm-fruits' => array('alt' => esc_html__('Home Farm Fruits', 'foodfarm'), 'img' => plugin_dir_url( __FILE__ ) . '/assets/images/farm-fruits.jpg'),
        'flower-farm' => array('alt' => esc_html__('Home Flower Farm', 'foodfarm'), 'img' => plugin_dir_url( __FILE__ ) . '/assets/images/flower-farm.jpg'),
        'fresh-meat' => array('alt' => esc_html__('Home Fresh Meat', 'foodfarm'), 'img' => plugin_dir_url( __FILE__ ) . '/assets/images/fresh-meat.jpg'),
    );
}
function aht_import_widgets( $widget_data ) {
    $json_data = $widget_data;
    $json_data = json_decode( $json_data, true );

    $sidebar_data = $json_data[0];
    $widget_data  = $json_data[1];

    foreach ($widget_data as $widget_data_title => $widget_data_value) {
        $widgets[$widget_data_title] = '';
        foreach ($widget_data_value as $widget_data_key => $widget_data_array) {
            if (is_int($widget_data_key)) {
                if($widget_data_title == 'nav_menu') {
                    if($widget_data_key == 2) {
                        $sidebar_menu = wp_get_nav_menu_object('Category');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;     
                        }
                    } elseif($widget_data_key == 3) {
                        $sidebar_menu = wp_get_nav_menu_object('Information');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 4) {
                        $sidebar_menu = wp_get_nav_menu_object('Policies');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 8) {
                        $sidebar_menu = wp_get_nav_menu_object('Services');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 9) {
                        $sidebar_menu = wp_get_nav_menu_object('Information');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 10) {
                        $sidebar_menu = wp_get_nav_menu_object('Shop By Category');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 11) {
                        $sidebar_menu = wp_get_nav_menu_object('Information');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 12) {
                        $sidebar_menu = wp_get_nav_menu_object('Our Products');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 13) {
                        $sidebar_menu = wp_get_nav_menu_object('Policies');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 14) {
                        $sidebar_menu = wp_get_nav_menu_object('"Information",');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 15) {
                        $sidebar_menu = wp_get_nav_menu_object('"My account",');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    }
                    elseif($widget_data_key == 16) {
                        $sidebar_menu = wp_get_nav_menu_object('"Our Products",');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 17) {
                        $sidebar_menu = wp_get_nav_menu_object('"Information",');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    } elseif($widget_data_key == 18) {
                        $sidebar_menu = wp_get_nav_menu_object('"Policies",');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    }
                    elseif($widget_data_key == 19) {
                        $sidebar_menu = wp_get_nav_menu_object('"Information",');
                        if($sidebar_menu->term_id) {
                            $widget_data[$widget_data_title][$widget_data_key]['nav_menu'] = $sidebar_menu->term_id;                        
                        }
                    }
                }
                $widgets[$widget_data_title][$widget_data_key] = 'on';
            }
        }
    }
    unset( $widgets[""] );

    foreach ( $sidebar_data as $title => $sidebar ) {
        $count = count( $sidebar );
        for ( $i = 0; $i < $count; $i ++ ) {
            $widget               = array();
            $widget['type']       = trim( substr( $sidebar[ $i ], 0, strrpos( $sidebar[ $i ], '-' ) ) );
            $widget['type-index'] = trim( substr( $sidebar[ $i ], strrpos( $sidebar[ $i ], '-' ) + 1 ) );
            if ( ! isset( $widgets[ $widget['type'] ][ $widget['type-index'] ] ) ) {
                unset( $sidebar_data[ $title ][ $i ] );
            }
        }
        $sidebar_data[ $title ] = array_values( $sidebar_data[ $title ] );
    }

    foreach ( $widgets as $widget_title => $widget_value ) {
        foreach ( $widget_value as $widget_key => $widget_value ) {
            $widgets[ $widget_title ][ $widget_key ] = $widget_data[ $widget_title ][ $widget_key ];
        }
    }

    $sidebar_data = array( array_filter( $sidebar_data ), $widgets );

    aht_widget_parse_import_data( $sidebar_data );
}
function aht_widget_parse_import_data( $import_array ) {
    global $wp_registered_sidebars;
    $sidebars_data    = $import_array[0];
    $widget_data      = $import_array[1];
    $current_sidebars = get_option( 'sidebars_widgets' );
    $new_widgets      = array();

    foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

        foreach ( $import_widgets as $import_widget ) :
            //if the sidebar exists
            if ( isset( $wp_registered_sidebars[ $import_sidebar ] ) ) :
                $title               = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
                $index               = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
                $current_widget_data = get_option( 'widget_' . $title );
                $new_widget_name     = aht_get_new_widget_name( $title, $index );
                $new_index           = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

                if ( ! empty( $new_widgets[ $title ] ) && is_array( $new_widgets[ $title ] ) ) {
                    while ( array_key_exists( $new_index, $new_widgets[ $title ] ) ) {
                        $new_index ++;
                    }
                }
                $current_sidebars[ $import_sidebar ][] = $title . '-' . $new_index;
                if ( array_key_exists( $title, $new_widgets ) ) {
                    $new_widgets[ $title ][ $new_index ] = $widget_data[ $title ][ $index ];
                    $multiwidget                         = $new_widgets[ $title ]['_multiwidget'];
                    unset( $new_widgets[ $title ]['_multiwidget'] );
                    $new_widgets[ $title ]['_multiwidget'] = $multiwidget;
                } else {
                    $current_widget_data[ $new_index ] = $widget_data[ $title ][ $index ];
                    $current_multiwidget               = isset( $current_widget_data['_multiwidget'] ) ? $current_widget_data['_multiwidget'] : false;
                    $new_multiwidget                   = isset( $widget_data[ $title ]['_multiwidget'] ) ? $widget_data[ $title ]['_multiwidget'] : false;
                    $multiwidget                       = ( $current_multiwidget != $new_multiwidget ) ? $current_multiwidget : 1;
                    unset( $current_widget_data['_multiwidget'] );
                    $current_widget_data['_multiwidget'] = $multiwidget;
                    $new_widgets[ $title ]               = $current_widget_data;
                }

            endif;
        endforeach;
    endforeach;

    if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
        update_option( 'sidebars_widgets', $current_sidebars );

        foreach ( $new_widgets as $title => $content ) {
            update_option( 'widget_' . $title, $content );
        }

        return true;
    }

    return false;
}

function aht_get_new_widget_name( $widget_name, $widget_index ) {
    $current_sidebars = get_option( 'sidebars_widgets' );
    $all_widget_array = array();
    foreach ( $current_sidebars as $sidebar => $widgets ) {
        if ( ! empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
            foreach ( $widgets as $widget ) {
                $all_widget_array[] = $widget;
            }
        }
    }
    while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
        $widget_index ++;
    }
    $new_widget_name = $widget_name . '-' . $widget_index;

    return $new_widget_name;
}
if ( !function_exists('aht_demo_import'))
{
    function aht_demo_import()
    {
        ?>
        <div class="aht_message content" style="display:none;">
            <img src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/images/spinner.gif" alt="spinner">
            <h1 class="aht_message_title"><?php esc_html_e('Importing Demo Content...', 'foodfarm'); ?></h1>
            <p class="aht_message_text"><?php esc_html_e('Duration of demo content importing depends on your server speed.', 'foodfarm'); ?></p>
        </div>

        <div class="aht_message success" style="display:none;">
            <p class="aht_message_text"><?php echo wp_kses( sprintf(__('Congratulations and enjoy <a href="%s" target="_blank">your website</a> now!', 'foodfarm'), esc_url( home_url() )), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ); ?></p>
        </div>

        <form class="aht_importer" id="import_demo_data_form" action="?page=aht_demo_import" method="post">

            <div class="aht_importer_options">

                <div class="aht_importer_note">
                    <strong><?php esc_html_e('Before installing the demo content, please NOTE:', 'foodfarm'); ?></strong>
                    <p class="about-description"><?php echo esc_html__( 'If Demo not contains revolution slider. You need select slider when edit page in sidebar page. NOTE: Make sure to read.', 'foodfarm' ); ?><a href="http://demo.arrowpress.net/foodfarm-gui/#toc17" target="_blank" ><?php echo esc_html__(' our demo installation guide','foodfarm')?></a>  <?php echo esc_html__('before importing demo','foodfarm')?></p>
                <p class="about-description"><?php echo esc_html__( 'You need to install the first dummy content before importing home page.', 'foodfarm' ); ?></p>
                </div>
                <p>
                    <strong style="font-size:25px;margin-top:15px;"><?php esc_html_e('Choose a demo template to import:', 'foodfarm'); ?></strong>
                </p>
                <?php 
                    $demos = aht_demo_types();
                ?>
                <div class="aht_demo_import_choices">
                    <div class="title_base_dummy_content">
                        <h3>Import base dummy content</h3>
                    </div>
                    <div class="base_dummy_content">
                        <p>Start working with our template by installing base demo content. Then you will get the opportunity to install the Home Page from the provided below list.</p>
                        <img width="648" height="375" src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/images/dummy-content.jpg" />
                        <div class="aht_choice_radio_button">
                            <input type="radio" name="demo_template" value="dummy-content" checked="1"/>
                            <?php esc_html_e('Dummy Content (Required)', 'foodfarm'); ?>
                        </div>
                    </div>
                    <div class="title_base_dummy_content">
                        <h3>Import demo versions</h3>
                    </div>
                    <?php foreach ( $demos as $demo => $demo_details) : ?>
                    <label>
                        <img width="230" height="200" src="<?php echo esc_url($demo_details['img']); ?>" />
                        <div class="aht_choice_radio_button">
                            <input type="radio" name="demo_template" value="<?php echo esc_attr($demo); ?>"/>
                            <?php echo esc_html($demo_details['alt']); ?>
                        </div>
                    </label>
                    <?php endforeach;?>
                </div>
                <p class="aht_demo_button_align">
                    <input class="button-primary size_big" type="submit" value="Import Content" id="import_demo_data">
                </p>
            </div>

        </form>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('#import_demo_data_form').on('submit', function() {
                    jQuery("html, body").animate({
                        scrollTop: 0
                    }, {
                        duration: 300
                    });
                    jQuery('.aht_importer').slideUp(null, function(){
                        jQuery('.aht_message.content').slideDown();
                    });

                    // Importing Content
                    jQuery.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: jQuery(this).serialize()+'&action=aht_demo_import_content',
                        success: function(){

                            jQuery('.aht_message.content').slideUp();
                            jQuery('.aht_message.success').slideDown();

                        }
                    });
                    return false;
                });
            });
        </script>
        <?php
    }

    // Content Import
    function aht_demo_import_content() {
        
        $chosen_template = 'dummy-content';
        $demo_content = 'dummy-content';
        
        if(!empty($_POST['demo_template'])){
            $chosen_template = $_POST['demo_template'];
        }

        if($chosen_template == 'big-farm') {
            $demo_content = 'big-farm';
        }

        if($chosen_template == 'medium-farm') {
            $demo_content = 'medium-farm';
        }

        if($chosen_template == 'food-market') {
            $demo_content = 'food-market';
        }

        if($chosen_template == 'farm-services') {
            $demo_content = 'farm-services';
        }

        if($chosen_template == 'food-store') {
            $demo_content = 'food-store';
        }

        if($chosen_template == 'bakery-cafe') {
            $demo_content = 'bakery-cafe';
        }

        if($chosen_template == 'farm-fruits') {
            $demo_content = 'farm-fruits';
        }

        if($chosen_template == 'flower-farm') {
            $demo_content = 'flower-farm';
        }

        if($chosen_template == 'fresh-meat') {
            $demo_content = 'fresh-meat';
        }
        
        update_option('aht_chosen_template', $chosen_template);
        

        set_time_limit( 0 );

        if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
            define( 'WP_LOAD_IMPORTERS', true );
        }

        require_once( 'wordpress-importer/wordpress-importer.php' );

        $wp_import                    = new WP_Import();
        $wp_import->fetch_attachments = true;

        ob_start();
        $wp_import->import( plugin_dir_path( __FILE__ ) . '/data/'.$demo_content.'/content.xml' );
        ob_end_clean();

        global $wp_filesystem;

        if ( empty( $wp_filesystem ) ) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
            WP_Filesystem();
        }

        $locations = get_theme_mod( 'nav_menu_locations' );
        $menus = wp_get_nav_menus();

        if ($menus) {
            foreach ($menus as $menu) {
                if ($menu->name == 'Primary Menu') {
                    $locations['primary'] = $menu->term_id;
                }
                if ($menu->name == 'Bakery Menu') {
                    $locations['bakery_menu'] = $menu->term_id;
                }
                if ($menu->name == 'Vertical Menu') {
                    $locations['vertical_menu'] = $menu->term_id;
                }
            }
        }
        if (class_exists('WooCommerce')) {
            //resize product image for demo 1
            $catalog = array(
                'width' => '262',
                'height' => '262',
                'crop' => 1
            );
            $single = array(
                'width' => '600',
                'height' => '600',
                'crop' => 1
            );
            $thumbnail = array(
                'width' => '90',
                'height' => '90',
                'crop' => 1
            );
            add_image_size('shop_thumbnail', $thumbnail['width'], $thumbnail['height'], $thumbnail['crop']);
            add_image_size('shop_catalog', $catalog['width'], $catalog['height'], $catalog['crop']);
            add_image_size('shop_single', $single['width'], $single['height'], $single['crop']);
        }
        set_theme_mod( 'nav_menu_locations', $locations );

        update_option( 'show_on_front', 'page' );

        $chosen_template = 'dummy-content';

        $chosen_template = get_option('aht_chosen_template');

        // Main Content
        if($chosen_template == 'dummy-content') {
            /*Widgets*/
            $widgets_file = plugin_dir_path( __FILE__ ) . '/data/dummy-content/widget_data.json';
            if ( file_exists( $widgets_file ) ) {
                $encode_widgets_array = $wp_filesystem->get_contents( $widgets_file );
                aht_import_widgets( $encode_widgets_array );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }
        }

        // Big Farm
        if($chosen_template == 'big-farm') {
            //Theme Options    
            ob_start();
            include('data/big-farm/theme_options.php');
            $theme_options = ob_get_clean();

            $options = json_decode($theme_options, true);
            $redux = ReduxFrameworkInstances::get_instance('foodfarm_settings');
            $redux->set_options($options);
            foodfarm_save_theme_settings();
            //Front Page
            $front_page = get_page_by_title( 'Home 1' );
            if ( isset( $front_page->ID ) ) {
                update_option( 'page_on_front', $front_page->ID );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }

            if ( class_exists( 'RevSlider' ) ) {
                $main_slider = plugin_dir_path( __FILE__ ) . '/data/big-farm/big-farm.zip';

                if ( file_exists( $main_slider ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $main_slider );
                }
            }
        }

        // Medium Farm
        if($chosen_template == 'medium-farm') {
            //Theme Options    
            ob_start();
            include('data/medium-farm/theme_options.php');
            $theme_options = ob_get_clean();

            $options = json_decode($theme_options, true);
            $redux = ReduxFrameworkInstances::get_instance('foodfarm_settings');
            $redux->set_options($options);
            foodfarm_save_theme_settings();
            //Front Page
            $front_page = get_page_by_title( 'Home 2' );
            if ( isset( $front_page->ID ) ) {
                update_option( 'page_on_front', $front_page->ID );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }

            if ( class_exists( 'RevSlider' ) ) {
                $main_slider = plugin_dir_path( __FILE__ ) . '/data/medium-farm/medium-farm.zip';

                if ( file_exists( $main_slider ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $main_slider );
                }
            }
        }

        // Food Market
        if($chosen_template == 'food-market') {
            //Theme Options    
            ob_start();
            include('data/food-market/theme_options.php');
            $theme_options = ob_get_clean();

            $options = json_decode($theme_options, true);
            $redux = ReduxFrameworkInstances::get_instance('foodfarm_settings');
            $redux->set_options($options);
            foodfarm_save_theme_settings();
            //Front Page
            $front_page = get_page_by_title( 'Home 3' );
            if ( isset( $front_page->ID ) ) {
                update_option( 'page_on_front', $front_page->ID );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }

            if ( class_exists( 'RevSlider' ) ) {
                $main_slider = plugin_dir_path( __FILE__ ) . '/data/food-market/food-market.zip';

                if ( file_exists( $main_slider ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $main_slider );
                }
            }
        }

        // Farm Services
        if($chosen_template == 'farm-services') {
            //Theme Options    
            ob_start();
            include('data/farm-services/theme_options.php');
            $theme_options = ob_get_clean();

            $options = json_decode($theme_options, true);
            $redux = ReduxFrameworkInstances::get_instance('foodfarm_settings');
            $redux->set_options($options);
            foodfarm_save_theme_settings();
            //Front Page
            $front_page = get_page_by_title( 'Home 4' );
            if ( isset( $front_page->ID ) ) {
                update_option( 'page_on_front', $front_page->ID );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }

            if ( class_exists( 'RevSlider' ) ) {
                $main_slider = plugin_dir_path( __FILE__ ) . '/data/farm-services/farm-services.zip';

                if ( file_exists( $main_slider ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $main_slider );
                }
            }
        }

        // Food Store
        if($chosen_template == 'food-store') {
            //Theme Options    
            ob_start();
            include('data/food-store/theme_options.php');
            $theme_options = ob_get_clean();

            $options = json_decode($theme_options, true);
            $redux = ReduxFrameworkInstances::get_instance('foodfarm_settings');
            $redux->set_options($options);
            foodfarm_save_theme_settings();
            //Front Page
            $front_page = get_page_by_title( 'Home 5' );
            if ( isset( $front_page->ID ) ) {
                update_option( 'page_on_front', $front_page->ID );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }

            if ( class_exists( 'RevSlider' ) ) {
                $main_slider = plugin_dir_path( __FILE__ ) . '/data/food-store/food-store.zip';

                if ( file_exists( $main_slider ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $main_slider );
                }
            }
        }

        // Bakery Cafe
        if($chosen_template == 'bakery-cafe') {
            //Theme Options    
            ob_start();
            include('data/bakery-cafe/theme_options.php');
            $theme_options = ob_get_clean();

            $options = json_decode($theme_options, true);
            $redux = ReduxFrameworkInstances::get_instance('foodfarm_settings');
            $redux->set_options($options);
            foodfarm_save_theme_settings();
            //Front Page
            $front_page = get_page_by_title( 'Home 6' );
            if ( isset( $front_page->ID ) ) {
                update_option( 'page_on_front', $front_page->ID );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }

            if ( class_exists( 'RevSlider' ) ) {
                $main_slider = plugin_dir_path( __FILE__ ) . '/data/bakery-cafe/bakery-cafe.zip';

                if ( file_exists( $main_slider ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $main_slider );
                }
            }
        }

        // Farm Fruits
        if($chosen_template == 'farm-fruits') {
            //Theme Options    
            ob_start();
            include('data/farm-fruits/theme_options.php');
            $theme_options = ob_get_clean();

            $options = json_decode($theme_options, true);
            $redux = ReduxFrameworkInstances::get_instance('foodfarm_settings');
            $redux->set_options($options);
            foodfarm_save_theme_settings();
            //Front Page
            $front_page = get_page_by_title( 'Home 7' );
            if ( isset( $front_page->ID ) ) {
                update_option( 'page_on_front', $front_page->ID );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }

            if ( class_exists( 'RevSlider' ) ) {
                $main_slider = plugin_dir_path( __FILE__ ) . '/data/farm-fruits/farm-fruits.zip';

                if ( file_exists( $main_slider ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $main_slider );
                }
            }
        }

        // Flower Farm
        if($chosen_template == 'flower-farm') {
            //Theme Options    
            ob_start();
            include('data/flower-farm/theme_options.php');
            $theme_options = ob_get_clean();

            $options = json_decode($theme_options, true);
            $redux = ReduxFrameworkInstances::get_instance('foodfarm_settings');
            $redux->set_options($options);
            foodfarm_save_theme_settings();
            //Front Page
            $front_page = get_page_by_title( 'Home 8' );
            if ( isset( $front_page->ID ) ) {
                update_option( 'page_on_front', $front_page->ID );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }

            if ( class_exists( 'RevSlider' ) ) {
                $main_slider = plugin_dir_path( __FILE__ ) . '/data/flower-farm/flower-farm.zip';

                if ( file_exists( $main_slider ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $main_slider );
                }
            }
        }

        // Fresh Meat
        if($chosen_template == 'fresh-meat') {
            //Theme Options    
            ob_start();
            include('data/fresh-meat/theme_options.php');
            $theme_options = ob_get_clean();

            $options = json_decode($theme_options, true);
            $redux = ReduxFrameworkInstances::get_instance('foodfarm_settings');
            $redux->set_options($options);
            foodfarm_save_theme_settings();
            //Front Page
            $front_page = get_page_by_title( 'Home 9' );
            if ( isset( $front_page->ID ) ) {
                update_option( 'page_on_front', $front_page->ID );
            }

            $blog_page = get_page_by_title( 'Blog' );
            if ( isset( $blog_page->ID ) ) {
                update_option( 'page_for_posts', $blog_page->ID );
            }

            if ( class_exists( 'RevSlider' ) ) {
                $main_slider = plugin_dir_path( __FILE__ ) . '/data/fresh-meat/fresh-meat.zip';

                if ( file_exists( $main_slider ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $main_slider );
                }
            }
        }
        echo 'done';
        die();

    }

    add_action( 'wp_ajax_aht_demo_import_content', 'aht_demo_import_content' );

}