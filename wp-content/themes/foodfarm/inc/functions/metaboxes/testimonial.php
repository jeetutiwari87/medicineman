<?php
function foodfarm_testimonial_meta_data() {
    return array(
        array(
            "name" => "role",
            "title" => esc_html__("Role", 'foodfarm'),
            "type" => "textfield"
        ),       
    );
}
function foodfarm_view_testimonial_meta_option() {
    $meta_box = foodfarm_testimonial_meta_data();
    foodfarm_show_meta_box($meta_box);
}

function foodfarm_save_testimonial_meta_option($post_id) {
    $meta_box = foodfarm_testimonial_meta_data();
    return foodfarm_save_meta_data( $post_id, $meta_box );
}
function foodfarm_add_testimonial_metaboxes() {
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'show-meta-boxes', esc_html__('More Information', 'foodfarm'), 'foodfarm_view_testimonial_meta_option', 'testimonial', 'normal', 'low' );
    }
}
add_action('add_meta_boxes', 'foodfarm_add_testimonial_metaboxes');
add_action('save_post', 'foodfarm_save_testimonial_meta_option');