<?php

/*
  Plugin Name: Foodfarm Post Types
  Plugin URI:
  Description: Register Post Types for Foodfarm Theme.
  Version: 1.0.0
  Author: AHT
  Author URI:
 */

// don't load directly
if (!defined('ABSPATH'))
    die('-1');

class FoodfarmPostTypes {

    function __construct() {

        // Load text domain
        add_action('plugins_loaded', array($this, 'loadTextDomain'));

        // Register post types
        add_action('init', array($this, 'addGalleryPostType'));
        add_action('init', array($this, 'addPressmediaPostType'));
        add_action('init', array($this, 'addMemberPostType'));
        add_action('init', array($this, 'addTestimonialPostType'));
        add_action('init', array($this, 'addBlockPostType'));
         add_action('init', array($this, 'addListPostType'));
        add_action('init', array($this, 'addRecipePostType'));      
        add_filter('manage_gallery_posts_columns', array($this, 'addGallery_columns'));  
        add_action('manage_gallery_posts_custom_column', array($this, 'addGallery_columns_content'), 10, 2);      
    }
    // Register gallery post type
    function addGalleryPostType() {
        register_post_type(
            'gallery', array(
            'labels' => $this->getLabels(esc_html__('Gallery', 'foodfarm'), esc_html__('Gallery', 'foodfarm')),
            'exclude_from_search' => false,
            'has_archive' => true,
            'public' => true,
            'rewrite' => array('slug' => 'gallery'),
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'page-attributes'),
            'can_export' => true
                )
        );

        register_taxonomy(
            'gallery_cat', 'gallery', array(
            'hierarchical' => true,
            'show_in_nav_menus' => true,
            'labels' => $this->getTaxonomyLabels(esc_html__('Gallery Category', 'foodfarm'), esc_html__('Gallery Categories', 'foodfarm')),
            'query_var' => true,
            'rewrite' => true,
            'show_admin_column' => true,
            )
        );
    }
    function get_the_image( $post_id = false ) {
        
        $post_id    = (int) $post_id;
        $cache_key  = "featured_image_post_id-{$post_id}-_thumbnail";
        $cache      = wp_cache_get( $cache_key, null );
        
        if ( !is_array( $cache ) )
            $cache = array();
    
        if ( !array_key_exists( $cache_key, $cache ) ) {
            if ( empty( $cache) || !is_string( $cache ) ) {
                $output = '';
                    
                if ( has_post_thumbnail( $post_id ) ) {
                    $image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), array( 36, 32 ) );
                    
                    if ( is_array( $image_array ) && is_string( $image_array[0] ) )
                        $output = $image_array[0];
                }
                
                if ( empty( $output ) ) {
                    // $output = plugins_url( 'images/default.png', __FILE__ );
                    // $output = apply_filters( 'featured_image_column_default_image', $output );
                }
                
                $output = esc_url( $output );
                $cache[$cache_key] = $output;
                
                wp_cache_set( $cache_key, $cache, null, 60 * 60 * 24 /* 24 hours */ );
            }
        }
        return isset( $cache[$cache_key] ) ? $cache[$cache_key] : $output;
    } 
    function addGallery_columns($defaults) {  

        if ( !is_array( $defaults ) )
            $defaults = array();       
        $new = array();      
        foreach( $defaults as $key => $title ) {
            if ( $key == 'title' ) 
                $new['featured_image'] = 'Image';
            
            $new[$key] = $title;
        }
        
        return $new;         
    }        
    // SHOW THE FEATURED IMAGE
    function addGallery_columns_content($column_name, $post_id) {
        if ( 'featured_image' != $column_name )
                    return;         
                
                $image_src = self::get_the_image( $post_id );
                            
                if ( empty( $image_src ) ) {
                    echo "&nbsp;"; // This helps prevent issues with empty cells
                    return;
                }
                
                echo '<img alt="' . esc_attr( get_the_title() ) . '" src="' . esc_url( $image_src ) . '" />';
    }      
    // Register pressmedia post type
    function addPressmediaPostType() {
        register_post_type(
            'pressmedia', array(
            'labels' => $this->getLabels(esc_html__('Press Media', 'foodfarm'), esc_html__('Press Media', 'foodfarm')),
            'exclude_from_search' => false,
            'has_archive' => true,
            'public' => true,
            'rewrite' => array('slug' => 'pressmedia'),
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'page-attributes'),
            'can_export' => true
                )
        );

        register_taxonomy(
            'pressmedia_cat', 'pressmedia', array(
            'hierarchical' => true,
            'show_in_nav_menus' => true,
            'labels' => $this->getTaxonomyLabels(esc_html__('Press Media Category', 'foodfarm'), esc_html__('Press Media Categories', 'foodfarm')),
            'query_var' => true,
            'rewrite' => true
                )
        );
    }

    // Register testimonial post type
    function addTestimonialPostType() {
        register_post_type(
            'testimonial', array(
            'labels' => $this->getLabels(esc_html__('Testimonial', 'foodfarm'), esc_html__('Testimonials', 'foodfarm')),
            'exclude_from_search' => true,
            'has_archive' => false,
            'publicly_queryable'  => false,
            'public' => true,
            'rewrite' => array('slug' => 'testimonial'),
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'page-attributes'),
            'can_export' => true
                )
        );
    }
    // Register static block post type
    function addBlockPostType() {
        register_post_type(
            'block', array(
            'labels' => $this->getLabels(esc_html__('Static Block', 'foodfarm'), esc_html__('Static Block', 'foodfarm')),
            'exclude_from_search' => true,
            'has_archive' => false,
            'publicly_queryable'  => false,
            'public' => true,
            'rewrite' => array('slug' => 'block'),
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'page-attributes'),
            'can_export' => true
                )
        );
    }
    //Register product order post type
    function addListPostType() {
        register_post_type(
            'productlist', array(
            'labels' => $this->getLabels(esc_html__('Menu Today', 'foodfarm'), esc_html__('Menu Today', 'foodfarm')),
            'exclude_from_search' => true,
            'has_archive' => false,
            'publicly_queryable'  => false,
            'public' => true,
            'rewrite' => array('slug' => 'productlist'),
            'supports' => array('title', 'price', 'editor', 'thumbnail', 'comments', 'page-attributes'),
            'can_export' => true
                )
        );
    }
    // Register member post type
    function addMemberPostType() {
        register_post_type(
            'member', array(
            'labels' => $this->getLabels(esc_html__('Member', 'foodfarm'), esc_html__('Members', 'foodfarm')),
            'exclude_from_search' => true,
            'has_archive' => false,
            'publicly_queryable'  => false,
            'public' => true,
            'rewrite' => array('slug' => 'member'),
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'page-attributes'),
            'can_export' => true
                )
        );
        register_taxonomy(
            'member_cat', 'member', array(
            'hierarchical' => true,
            'show_in_nav_menus' => true,
            'labels' => $this->getTaxonomyLabels(esc_html__('Member Category', 'foodfarm'), esc_html__('Member Categories', 'foodfarm')),
            'query_var' => true,
            'rewrite' => true
                )
        );
    }
    // Register Recipe post type
    function addRecipePostType() {
        global $foodfarm_settings;
        if(isset($foodfarm_settings['recipe_slug'])){
            $recipe_slug = $foodfarm_settings['recipe_slug'];
        }
        else {$recipe_slug = "recipe"; }
        if(isset($foodfarm_settings['recipe_cat_slug'])){
            $recipe_cat_slug = $foodfarm_settings['recipe_cat_slug'];
        }
        else {$recipe_cat_slug = "recipe_cat"; }        
        if(isset($foodfarm_settings['recipe_tag_slug'])){
            $recipe_tag_slug = $foodfarm_settings['recipe_tag_slug'];
        }
        else {$recipe_tag_slug = "recipe_tag"; }         
        register_post_type(
            'recipe', array(
            'labels' => $this->getLabels(esc_html__('Recipes', 'foodfarm'), esc_html__('Recipes', 'foodfarm')),
            'exclude_from_search' => false,
            'has_archive' => $recipe_slug,
            'public' => true,
            'rewrite' => array('slug' => $recipe_slug),
            'supports' => array('title', 'editor', 'thumbnail', 'comments', 'page-attributes'),
            'can_export' => true
            )
        );
        register_taxonomy(
            'recipe_cat', 'recipe', array(
            'hierarchical' => true,
            'show_in_nav_menus' => true,
            'labels' => $this->getTaxonomyLabels(esc_html__('Recipe Category', 'foodfarm'), esc_html__('Recipe Categories', 'foodfarm')),
            'query_var' => true,
            'rewrite' => array('slug' => $recipe_cat_slug),
                )
        );
        register_taxonomy('recipe_tag','recipe',array(
            'hierarchical' => false,
            'labels' => $this->getTaxonomyLabels(esc_html__('Recipe Tags', 'foodfarm'), esc_html__('Recipe Tags', 'foodfarm')),
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => $recipe_tag_slug),
          ));
    }
    // load plugin text domain
    function loadTextDomain() {
        load_plugin_textdomain('foodfarm', false, dirname(__FILE__) . '/languages/');
    }

    // Get content type labels
    function getLabels($singular_name, $name, $title = FALSE) {
        if (!$title)
            $title = $name;

        return array(
            "name" => $title,
            "singular_name" => $singular_name,
            "add_new" => esc_html__("Add New", 'foodfarm'),
            "add_new_item" => sprintf(esc_html__("Add New %s", 'foodfarm'), $singular_name),
            "edit_item" => sprintf(esc_html__("Edit %s", 'foodfarm'), $singular_name),
            "new_item" => sprintf(esc_html__("New %s", 'foodfarm'), $singular_name),
            "view_item" => sprintf(esc_html__("View %s", 'foodfarm'), $singular_name),
            "search_items" => sprintf(esc_html__("Search %s", 'foodfarm'), $name),
            "not_found" => sprintf(esc_html__("No %s found", 'foodfarm'), $name),
            "not_found_in_trash" => sprintf(esc_html__("No %s found in Trash", 'foodfarm'), $name),
            "parent_item_colon" => ""
        );
    }

    // Get content type taxonomy labels
    function getTaxonomyLabels($singular_name, $name) {
        return array(
            "name" => $name,
            "singular_name" => $singular_name,
            "search_items" => sprintf(esc_html__("Search %s", 'foodfarm'), $name),
            "all_items" => sprintf(esc_html__("All %s", 'foodfarm'), $name),
            "parent_item" => sprintf(esc_html__("Parent %s", 'foodfarm'), $singular_name),
            "parent_item_colon" => sprintf(esc_html__("Parent %s:", 'foodfarm'), $singular_name),
            "edit_item" => sprintf(esc_html__("Edit %", 'foodfarm'), $singular_name),
            "update_item" => sprintf(esc_html__("Update %s", 'foodfarm'), $singular_name),
            "add_new_item" => sprintf(esc_html__("Add New %s", 'foodfarm'), $singular_name),
            "new_item_name" => sprintf(esc_html__("New %s Name", 'foodfarm'), $singular_name),
            "menu_name" => $name,
        );
    }

}

new FoodfarmPostTypes();
