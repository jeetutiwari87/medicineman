<?php

/*
  Plugin Name: Foodfarm Shortcodes
  Plugin URI:
  Description: Shortcodes for Foodfarm Theme.
  Version: 2.0.0
  Author: AHT
  Author URI:
 */

// don't load directly
if (!defined('ABSPATH'))
    die('-1');

define('FOODFARM_SHORTCODES_URL', plugin_dir_url(__FILE__));
define('FOODFARM_SHORTCODES_PATH', dirname(__FILE__) . '/shortcodes/');
define('FOODFARM_SHORTCODES_LIB', dirname(__FILE__) . '/lib/');
define('FOODFARM_SHORTCODES_TEMPLATES', dirname(__FILE__) . '/templates/');

class FoodfarmShortcodesClass {

    private $shortcodes = array("foodfarm_instagram_feed", "foodfarm_services", "foodfarm_testimonial", "foodfarm_static_block", "foodfarm_container","foodfarm_our_team", "foodfarm_gallery", "foodfarm_recipes", "foodfarm_blog", "foodfarm_we_doing" , "foodfarm_today_menu","foodfarm_slider_wrap");

    private $woo_shortcodes = array("foodfarm_toprate_product", "foodfarm_bestseller_product", "foodfarm_featured_product", "foodfarm_recent_product", "foodfarm_sale_product", "foodfarm_products_category", "foodfarm_products_filter",'foodfarm_product_categories','foodfarm_product_list');
    function __construct() {

        // Load text domain
        add_action('plugins_loaded', array($this, 'loadTextDomain'));
        // Init plugins
        add_action('init', array($this, 'initPlugin'));

        $this->addShortcodes();
        add_filter('the_content', array($this, 'formatShortcodes'));
        add_filter('widget_text', array($this, 'formatShortcodes'));
        add_action('vc_base_register_front_css',  array($this,'foodfarm_iconpicker_base_register_css'));
        add_action('vc_base_register_admin_css', array($this,'foodfarm_iconpicker_base_register_css'));
        add_action('vc_backend_editor_enqueue_js_css', array($this,'foodfarm_iconpicker_editor_jscss'));
        add_action('vc_frontend_editor_enqueue_js_css', array($this,'foodfarm_iconpicker_editor_jscss'));
        add_action('wp_enqueue_scripts', array($this,'foodfarm_add_foodfarm_font_style'));
    }

    // Init plugins
    function initPlugin() {
        $this->addTinyMCEButtons();
    }

    // load plugin text domain
    function loadTextDomain() {
        load_plugin_textdomain('foodfarm', false, dirname(__FILE__) . '/languages/');
    }

    // Add buttons to tinyMCE
    function addTinyMCEButtons() {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;

        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_buttons', array(&$this, 'registerTinyMCEButtons'));
        }
    }

    function registerTinyMCEButtons($buttons) {
        array_push($buttons, "foodfarm_shortcodes_button");
        return $buttons;
    }

    // Add shortcodes
    function addShortcodes() {
        require_once(FOODFARM_SHORTCODES_LIB . 'functions.php');
        foreach ($this->shortcodes as $shortcode) {
            require_once(FOODFARM_SHORTCODES_PATH . $shortcode . '.php');
        }
        //if (  function_exists('is_plugin_active') && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            foreach ($this->woo_shortcodes as $woo_shortcode) {
                require_once(FOODFARM_SHORTCODES_PATH . $woo_shortcode . '.php');
            }
            //if (function_exists('is_plugin_active') && is_plugin_active( 'yith-woocommerce-brands-add-on/init.php' )) {
                require_once(FOODFARM_SHORTCODES_PATH . 'foodfarm_brands' . '.php');
            //}
        //}
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    // Format shortcodes content
    function formatShortcodes($content) {
        $block = join("|", $this->shortcodes);
        // opening tag
        $content = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);
        // closing tag
        $content = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)/", "[/$2]", $content);

        return $content;
    }
    function foodfarm_iconpicker_base_register_css() {
        wp_register_style('pestrokefont', FOODFARM_SHORTCODES_URL  . 'assets/css/pe-icon-7-stroke.css', false, '1.0', 'screen');
        wp_register_style('foodfarmfont', FOODFARM_SHORTCODES_URL  . 'assets/css/style.css', false, '1.0', 'screen');
    }

    function foodfarm_iconpicker_editor_jscss() {
        wp_enqueue_style('pestrokefont');
        wp_enqueue_style('foodfarmfont');
    }
    function foodfarm_add_foodfarm_font_style() {
        wp_enqueue_style('foodfarmfont');
    }

}

// Finally initialize code
new FoodfarmShortcodesClass();
