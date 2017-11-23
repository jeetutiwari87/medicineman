<?php
function foodfarm_recipe_meta_data() {
    return array(
        array(
            "name" => "desc",
            "title" => esc_html__("Description", 'foodfarm'),
            "type" => "editor"
        ),
        array(
            "name" => "ingre",
            "title" => esc_html__("Ingredient", 'foodfarm'),
            "type" => "editor"
        ),
        array(
            "name" => "prep",
            "title" => esc_html__("Preparation time", 'foodfarm'),
            "type" => "textfield"
        ),
        array(
            "name" => "cook",
            "title" => esc_html__("Cook time", 'foodfarm'),
            "type" => "text"
        ),
        array(
            "name" => "ready",
            "title" => esc_html__("Ready time", 'foodfarm'),
            "type" => "text"
        ),
        array(
            "name" => "serving",
            "title" => esc_html__("Servings number", 'foodfarm'),
            "type" => "text"
        ),                        
    );
}
function foodfarm_recipe_note_meta_data() {
    return array(
        array(
            "name" => "cook_note",
            "title" => esc_html__("Cook's Note:", 'foodfarm'),
            "type" => "textarea"
        ),              
        array(
            "name" => "editor_note",
            "title" => esc_html__("Editor's Note:", 'foodfarm'),
            "type" => "textarea"
        ),        
    );
}
function foodfarm_view_recipe_meta_option() {
    $meta_box = foodfarm_recipe_meta_data();
    foodfarm_show_meta_box($meta_box);
}
function foodfarm_view_recipe_note_meta_option() {
    $meta_box2 = foodfarm_recipe_note_meta_data();
    foodfarm_show_meta_box($meta_box2);
}

function foodfarm_save_recipe_meta_option($post_id) {
    $meta_box = foodfarm_recipe_meta_data();
    return foodfarm_save_meta_data( $post_id, $meta_box );
}
function foodfarm_save_recipe_note_meta_option($post_id) {
    $meta_box2 = foodfarm_recipe_note_meta_data();
    return foodfarm_save_meta_data( $post_id, $meta_box2 );
}
function foodfarm_show_post_meta_option() {
    $meta_box =foodfarm_default_meta_data();
    foodfarm_show_meta_box($meta_box);
}

function foodfarm_save_default_meta_option($post_id) {
    $meta_box = foodfarm_default_meta_data();
    return foodfarm_save_meta_data($post_id, $meta_box);
}

function foodfarm_add_recipe_metaboxes() {
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'show-meta-boxes', esc_html__('Preparation Information', 'foodfarm'), 'foodfarm_view_recipe_meta_option', 'recipe', 'normal', 'high' );
        add_meta_box( 'view-meta-boxes', esc_html__('Footnotes', 'foodfarm'), 'foodfarm_view_recipe_note_meta_option', 'recipe', 'normal', 'high' );
        add_meta_box('display-meta-boxes', esc_html__('Layout Options', 'foodfarm'), 'foodfarm_show_post_meta_option', 'recipe', 'side', 'low');
    }
}
add_action('add_meta_boxes', 'foodfarm_add_recipe_metaboxes');
add_action('save_post', 'foodfarm_save_recipe_meta_option');
add_action('save_post', 'foodfarm_save_recipe_note_meta_option');
add_action('save_post', 'foodfarm_save_default_meta_option');
