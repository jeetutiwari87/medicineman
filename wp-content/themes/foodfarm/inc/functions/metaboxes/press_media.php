<?php
function foodfarm_pressmedia_meta_data() {
    return array(
        array(
            "name" => "media_press_link",
            "title" => esc_html__("Media Press Link", 'foodfarm'),
            "type" => "textfield"
        ),      
    );
}
function foodfarm_view_pressmedia_meta_option() {
    $meta_box = foodfarm_pressmedia_meta_data();
    foodfarm_show_meta_box($meta_box);
}

function foodfarm_save_pressmedia_meta_option($post_id) {
    $meta_box = foodfarm_pressmedia_meta_data();
    return foodfarm_save_meta_data( $post_id, $meta_box );
}
function foodfarm_add_pressmedia_metaboxes() {
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'show-meta-boxes', esc_html__('Press Media Information', 'foodfarm'), 'foodfarm_view_pressmedia_meta_option', 'pressmedia', 'normal', 'low' );
    }
}
add_action('add_meta_boxes', 'foodfarm_add_pressmedia_metaboxes');
add_action('save_post', 'foodfarm_save_pressmedia_meta_option');