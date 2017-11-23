<?php
function foodfarm_product_meta_data(){
    return array(
        // Custom Tab Title
        "custom_tab_title" => array(
            "name" => "custom_tab_title",
            "title" => esc_html__("Custom Tab Title", 'foodfarm'),
            "desc" => esc_html__("Input the custom tab title.", 'foodfarm'),
            "type" => "textfield"
        ),
        // Content Tab Content
        "custom_tab_content" => array(
            "name" => "custom_tab_content",
            "title" => esc_html__("Custom Tab Content", 'foodfarm'),
            "desc" => esc_html__("Input the custom tab content.", 'foodfarm'),
            "type" => "editor"
        )
    );
}

function foodfarm_show_product_meta_option() {
    $meta_box = foodfarm_product_meta_data();
    foodfarm_show_meta_box($meta_box);
}

function foodfarm_save_product_meta_option($post_id) {
    $meta_box = foodfarm_product_meta_data();
    return foodfarm_save_meta_data($post_id, $meta_box);
}

function foodfarm_add_product_metaboxes() {
    if (function_exists('add_meta_box')) {
        add_meta_box('view-meta-boxes', esc_html__('Layout Options', 'foodfarm'), 'foodfarm_show_product_meta_option', 'product', 'normal', 'low');
    }
}

add_action('add_meta_boxes', 'foodfarm_add_product_metaboxes');
add_action('save_post', 'foodfarm_save_product_meta_option');
function foodfarm_add_categorymeta_product_table() {
// Create Product Cat Meta
global $wpdb;
$type = 'product_cat';
$table_name = $wpdb->prefix . $type . 'meta';
$variable_name = $type . 'meta';
$wpdb->$variable_name = $table_name;

// Create Product Cat Meta Table
foodfarm_create_metadata_table($table_name, $type);
}
add_action( 'init', 'foodfarm_add_categorymeta_product_table' );
//Taxonomy
function foodfarm_default_product_tax_meta_data() {
    $foodfarm_sidebar_position = foodfarm_sidebar_position();
    $foodfarm_sidebars = foodfarm_sidebars();   
    $foodfarm_list_mode = foodfarm_product_type(); 
    return array(
        'filter_icon' => array(
            'name' => 'filter_icon',
            'title' => esc_html__('Filter Icon', 'foodfarm'),
            'desc' => esc_html__('Add icon class to this field', 'foodfarm'),
            'type' => 'text'
        ),        
        // Breadcrumbs
        'breadcrumbs' => array(
            'name' => 'breadcrumbs',
            'title' => esc_html__('Breadcrumbs', 'foodfarm'),
            'desc' => esc_html__('Hide breadcrumbs', 'foodfarm'),
            'type' => 'checkbox'
        ),
        'page_title' => array(
            'name' => 'page_title',
            'title' => esc_html__('Page Title', 'foodfarm'),
            'desc' => esc_html__('Hide Page Title', 'foodfarm'),
            'type' => 'checkbox'
        ),
        'show_header' => array(
            'name' => 'show_header',
            'title' => esc_html__('Header', 'foodfarm'),
            'desc' => esc_html__('Hide header', 'foodfarm'),
            'type' => 'checkbox'
        ),
        //  Show Footer
        'show_footer' => array(
            'name' => 'show_footer',
            'title' => esc_html__('Footer', 'foodfarm'),
            'desc' => esc_html__('Hide footer', 'foodfarm'),
            'type' => 'checkbox'
        ),
        //sidebar position
        'sidebar_position' => array(
            'name' => 'sidebar_position',
            'type' => 'select',
            'title' => esc_html__('Sidebar Position', 'foodfarm'),
            'options' => $foodfarm_sidebar_position,
            'default' => 'default'
        ),
        //sidebar
        'sidebar' => array(
            'name' => 'sidebar',
            'type' => 'select',
            'title' => esc_html__('Sidebar', 'foodfarm'),
            'options' => $foodfarm_sidebars,
            'default' => 'default'
        ),
        //sidebar
        'list_mode_product' => array(
            'name' => 'list_mode_product',
            'type' => 'select',
            'title' => esc_html__('List mode', 'foodfarm'),
            'options' => $foodfarm_list_mode,
            'default' => 'list-default'
        ),
        //column
        'category_cols' => array(
            'name' => 'category_cols',
            'type' => 'select',
            'title' => esc_html__('Product column', 'foodfarm'),
            'options' => foodfarm_product_columns(),
            'default' => '3'
        ),

    );
}

add_action( 'product_cat_add_form_fields', 'foodfarm_add_product_cat', 10, 2);
function foodfarm_add_product_cat() {
    $product_cat_meta_boxes = foodfarm_default_product_tax_meta_data();

    foodfarm_show_tax_add_meta_boxes($product_cat_meta_boxes);
}

add_action( 'product_cat_edit_form_fields', 'foodfarm_edit_product_cat', 10, 2);
function foodfarm_edit_product_cat($tag, $taxonomy) {
    $product_cat_meta_boxes = foodfarm_default_product_tax_meta_data();

    foodfarm_show_tax_edit_meta_boxes($tag, $taxonomy, $product_cat_meta_boxes);
}

add_action( 'created_term', 'foodfarm_save_product_cat', 10,3 );
add_action( 'edit_term', 'foodfarm_save_product_cat', 10,3 );

function foodfarm_save_product_cat($term_id, $tt_id, $taxonomy) {
    if (!$term_id) return;
    
    $product_cat_meta_boxes = foodfarm_default_product_tax_meta_data();
    return foodfarm_save_taxdata( $term_id, $tt_id, $taxonomy, $product_cat_meta_boxes );
}