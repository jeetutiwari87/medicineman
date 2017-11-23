<?php
function foodfarm_productlist_meta_data() {
    return array(
        array(
            "name" => "price_product",
            "title" => esc_html__("Price", 'foodfarm'),
            "type" => "textfield"
        ),  
         array(
            "name" => "link_product",
            "title" => esc_html__("Link To Product", 'foodfarm'),
            "type" => "textfield"
        ),     
    );
}
function foodfarm_view_productlist_meta_option() {
    $meta_box = foodfarm_productlist_meta_data();
    foodfarm_show_meta_box($meta_box);
}

function foodfarm_save_productlist_meta_option($post_id) {
    $meta_box = foodfarm_productlist_meta_data();
    return foodfarm_save_meta_data( $post_id, $meta_box );
}
function foodfarm_add_productlist_metaboxes() {
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'show-meta-boxes', esc_html__('Todays Menu Information', 'foodfarm'), 'foodfarm_view_productlist_meta_option', 'productlist', 'normal', 'low' );
    }
}
add_action('add_meta_boxes', 'foodfarm_add_productlist_metaboxes');
add_action('save_post', 'foodfarm_save_productlist_meta_option');