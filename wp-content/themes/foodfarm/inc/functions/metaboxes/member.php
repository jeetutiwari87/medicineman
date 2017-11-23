<?php
function foodfarm_member_meta_data() {
    return array(
        array(
            "name" => "occupation",
            "title" => esc_html__("Occupation", 'foodfarm'),
            "type" => "textfield"
        ),
        array(
            "name" => "facebook",
            "title" => esc_html__("Facebook Link", 'foodfarm'),
            "type" => "textfield"
        ),
        array(
            "name" => "twitter",
            "title" => esc_html__("Twitter Link", 'foodfarm'),
            "type" => "textfield"
        ),
        array(
            "name" => "google",
            "title" => esc_html__("Google Link", 'foodfarm'),
            "type" => "textfield"
        ),        
    );
}
function foodfarm_view_member_meta_option() {
    $meta_box = foodfarm_member_meta_data();
    foodfarm_show_meta_box($meta_box);
}

function foodfarm_save_member_meta_option($post_id) {
    $meta_box = foodfarm_member_meta_data();
    return foodfarm_save_meta_data( $post_id, $meta_box );
}
function foodfarm_add_member_metaboxes() {
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'show-meta-boxes', esc_html__('Member Information', 'foodfarm'), 'foodfarm_view_member_meta_option', 'member', 'normal', 'low' );
    }
}
add_action('add_meta_boxes', 'foodfarm_add_member_metaboxes');
add_action('save_post', 'foodfarm_save_member_meta_option');