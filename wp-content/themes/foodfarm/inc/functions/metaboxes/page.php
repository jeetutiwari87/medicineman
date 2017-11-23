<?php
function foodfarm_page_meta_data() {
    $other_fonts =  array(
                        'default' => esc_html__( 'default', 'foodfarm' ),
                        'Poppins'  => 'Poppins',
                        'Hind'  => 'Hind',
                        'Roboto'  => 'Roboto',                      
                        'Palanquin'  => 'Palanquin',
                        'Open Sans'  => 'Open Sans',             
                        'Oxygen'  => 'Oxygen',                         
                    );    
    return array(
        array(
            "name" => "main_color",
            "title" => esc_html__("Main Color", 'foodfarm'),
            "type" => "color"
        ),
        array(
            "name" => "h_color",
            "title" => esc_html__("Highlight Color", 'foodfarm'),
            "type" => "color"
        ),      
    );
}
function foodfarm_view_page_meta_option() {
    $meta_box = foodfarm_page_meta_data();
    foodfarm_show_meta_box($meta_box);
}
function foodfarm_save_page2_meta_option($post_id) {
    $meta_box = foodfarm_page_meta_data();
    return foodfarm_save_meta_data($post_id, $meta_box);
}
function foodfarm_show_page_meta_option() {
    $meta_box = foodfarm_default_meta_data();
    foodfarm_show_meta_box($meta_box);
}

function foodfarm_save_page_meta_option($post_id) {
    $meta_box = foodfarm_default_meta_data();
    return foodfarm_save_meta_data($post_id, $meta_box);
}

function foodfarm_add_page_metaboxes() {
    if (function_exists('add_meta_box')) {
        add_meta_box('show-meta-boxes', esc_html__('Skin Color', 'foodfarm'), 'foodfarm_view_page_meta_option', 'page', 'normal', 'low');        
        add_meta_box('view-meta-boxes', esc_html__('Layout Options', 'foodfarm'), 'foodfarm_show_page_meta_option', 'page', 'side', 'low');
    }
}

add_action('add_meta_boxes', 'foodfarm_add_page_metaboxes');
add_action('save_post', 'foodfarm_save_page_meta_option');
add_action('save_post', 'foodfarm_save_page2_meta_option');
function foodfarm_default_post_tax_meta_data() {
    $foodfarm_sidebar_position = foodfarm_sidebar_position();
    $foodfarm_sidebars = foodfarm_sidebars();
    $foodfarm_header_layout = foodfarm_header_types();
    return array(
        // Breadcrumbs
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
    );
}
//category taxonomy
function foodfarm_add_categorymeta_table() {
// Create Product Cat Meta
global $wpdb;
$type = 'category';
$table_name = $wpdb->prefix . $type . 'meta';
$variable_name = $type . 'meta';
$wpdb->$variable_name = $table_name;

// Create Category Meta Table
foodfarm_create_metadata_table($table_name, $type);
}
add_action( 'init', 'foodfarm_add_categorymeta_table' );

// category meta
add_action( 'category_add_form_fields', 'foodfarm_add_category', 10, 2);
function foodfarm_add_category() {
    $category_meta_boxes = foodfarm_default_post_tax_meta_data();
    foodfarm_show_tax_add_meta_boxes($category_meta_boxes);
}

add_action( 'category_edit_form_fields', 'foodfarm_edit_category', 10, 2);
function foodfarm_edit_category($tag, $taxonomy) {
    $category_meta_boxes = foodfarm_default_post_tax_meta_data();
    foodfarm_show_tax_edit_meta_boxes($tag, $taxonomy, $category_meta_boxes);
}

add_action( 'created_term', 'foodfarm_save_category', 10,3 );
add_action( 'edit_term', 'foodfarm_save_category', 10,3 );
function foodfarm_save_category($term_id, $tt_id, $taxonomy) {
    if (!$term_id) return;
    
    $category_meta_boxes = foodfarm_default_post_tax_meta_data();
    return foodfarm_save_taxdata( $term_id, $tt_id, $taxonomy, $category_meta_boxes );
}

// Featured Post
//ADD THE META BOX
add_action( 'add_meta_boxes', 'foodfarm_add_featured_slide' );
function foodfarm_add_featured_slide(){
    //POST TYPES TO HAVE THE CUSTOM META BOX 
    $ctptypes = array( 'post' );
    foreach ( $ctptypes as $ctptype ) {
        add_meta_box( 'featured-slide', 'Featured Post', 'foodfarm_featured_slide_func', $ctptype, 'side', 'high' );
    }
}
//DEFINE THE META BOX
function foodfarm_featured_slide_func( $post ){
    $values = get_post_custom( $post->ID );
    $check = isset( $values['special_box_check'] ) ? esc_attr( $values['special_box_check'][0] ) : '';
    wp_nonce_field( 'my_featured_slide_nonce', 'featured_slide_nonce' );
    ?>
    <p>
        <input type="checkbox" name="special_box_check" id="special_box_check" <?php checked( $check, 'on' ); ?> />
        <label for="special_box_check">Feature</label>
    </p>
    <?php 
}
//SAVE THE META BOX DATA WITH THE POST
add_action( 'save_post', 'foodfarm_featured_slide_save' );
function foodfarm_featured_slide_save( $post_id ){
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if( !isset( $_POST['featured_slide_nonce'] ) || !wp_verify_nonce( $_POST['featured_slide_nonce'], 'my_featured_slide_nonce' ) ) return;
    if( !current_user_can( 'edit_post' ) ) return;
    $allowed = array( 
        'a' => array( 
            'href' => array() 
        )
    );
    // IF CHECKED SAVE THE CUSTOM META
    if ( isset( $_POST['special_box_check'] ) && $_POST['special_box_check'] ) {
        add_post_meta( $post_id, 'special_box_check', 'on', true );
    }
    // IF UNCHECKED DELETE THE CUSTOM META
    else {
        delete_post_meta( $post_id, 'special_box_check' );
    }
}

// function to display number of posts.
function foodfarm_get_post_views($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return '0';
    }
    return $count;
}
 