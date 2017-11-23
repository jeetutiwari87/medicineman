<?php
require_once(foodfarm_functions . '/vc_functions.php');
require_once(foodfarm_functions . '/sidebars.php');
require_once(foodfarm_functions . '/layout.php');
require_once(foodfarm_functions . '/menus.php');
require_once(foodfarm_functions . '/gallery_like_count.php');
if (class_exists('Woocommerce')) {
    require_once(foodfarm_functions . '/woocommerce.php');
    require_once(foodfarm_functions . '/widgets/foodfarm_product.php');
    require_once(foodfarm_functions . '/widgets/foodfarm_override_woocommerce.php');
}
require_once(foodfarm_functions . '/wpml.php');
require_once(foodfarm_functions . '/widgets/foodfarm_archives_post-type.php');
require_once(foodfarm_functions . '/widgets/foodfarm_recent_posts.php');
if( function_exists('is_plugin_active') && is_plugin_active( 'foodfarm-post-types/functions.php' ) ) {
    require_once(foodfarm_functions . '/widgets/foodfarm_recipe_categories.php');
    require_once(foodfarm_functions . '/widgets/foodfarm_recent_recipes.php');
}
require_once(foodfarm_functions . '/ajax_search/ajax-search.php');
if( function_exists('is_plugin_active') && is_plugin_active( 'wp-knowledgebase/wp-knowledgebase.php' ) ) {
    require_once(foodfarm_functions . '/widgets/foodfarm_knowledge_catogories.php');
    require_once(foodfarm_functions . '/widgets/foodfarm_knowledge_tags.php');
    require_once(foodfarm_functions . '/widgets/foodfarm_knowledge_articles.php');
    require_once(foodfarm_functions . '/widgets/foodfarm_knowledge_search.php');
    remove_filter('template_include', 'kbe_template_chooser');
    add_filter('template_include', 'foodfarm_kbe_template_chooser');
    function foodfarm_kbe_template_chooser($template){

        $template_path = apply_filters( 'kbe_template_path', 'wp_knowledgebase/' );

        $find = array();
        $file = '';

        if ( is_single() && get_post_type() == 'kbe_knowledgebase' ) {
            $file   = 'single-kbe_knowledgebase.php';
            $find[] = $file;
            $find[] = $template_path . $file;
        } elseif ( is_tax('kbe_taxonomy') || is_tax( 'kbe_tags') ) {
            $term   = get_queried_object();

            if ( is_tax( 'kbe_taxonomy' ) || is_tax( 'kbe_tags' ) ) {
                    $file = 'taxonomy-' . $term->taxonomy . '.php';
            } else {
                    $file = 'archive.php';
            }

            $find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
            $find[] = $template_path . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
            $find[] = 'taxonomy-' . $term->taxonomy . '.php';
            $find[] = $template_path . 'taxonomy-' . $term->taxonomy . '.php';
            $find[] = $file;
            $find[] = $template_path . $file;
        } elseif ( is_post_type_archive( 'kbe_knowledgebase' )) {
            $file   = 'kbe_knowledgebase.php';
            $find[] = $file;
            $find[] = $template_path . $file;
        }

        if ( $file ) {
            $template = locate_template( array_unique( $find ) ) ;
            if ( ! $template ) {
                $template = trailingslashit( dirname(__FILE__) ) . 'template/' . $file;
            }
        }

          return $template;
    }
}
add_filter( 'widget_text', 'do_shortcode', 11);
//search filter
if ( !is_admin() ) {
    function foodfarm_searchfilter($query) {
        if ($query->is_search && !is_admin() && $query->get( 'post_type' ) != 'kbe_knowledgebase' && $query->get( 'post_type' ) != 'product') {
        $query->set('post_type',array('post','recipe'));
        }
        return $query;
    }
    add_filter('pre_get_posts','foodfarm_searchfilter');
}
//back to top
add_action( 'wp_footer', 'foodfarm_back_to_top' );
function foodfarm_back_to_top() {
echo '<a class="scroll-to-top"><i class="fa fa-angle-up"></i></a>';
}
function foodfarm_add_slug_body_class( $classes ) {
    global $post,$foodfarm_settings;
    $header_type = foodfarm_get_header_type();
    $header_fixed_class = '';
    if($header_type == '2' || $header_type == '6'){
        $classes[] = 'fixed-header';
    }
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    if(isset($foodfarm_settings['product-zoom']) && $foodfarm_settings['product-zoom']){
        $classes[] = ' disable_zoom_image ';
    }
    return $classes;
}
add_filter( 'body_class', 'foodfarm_add_slug_body_class' );
function foodfarm_get_revolution_slider() {
    global $foodfarm_settings;
    $header_type = foodfarm_get_header_type();
    $show_slider = get_post_meta(get_the_ID(),'show_slider', true);
    $slider_home = get_post_meta(get_the_ID(),'select_slider', true);
    $output = '';
    ob_start();
    ?>
    <?php if($show_slider) :?>
        <div class="main-slider">
            <?php echo do_shortcode( '[rev_slider alias=' . $slider_home . ']' ); ?>
        </div>
    <?php endif;?>
    <?php
    $output .= ob_get_clean();
    echo $output;
}
function foodfarm_set_post_views($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
function foodfarm_get_attachment( $attachment_id, $size = 'full' ) {
    if (!$attachment_id)
        return false;
    $attachment = get_post( $attachment_id );
    $image = wp_get_attachment_image_src($attachment_id, $size);

    if (!$attachment)
        return false;

    return array(
        'alt' => esc_attr(get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true )),
        'caption' => esc_attr($attachment->post_excerpt),
        'description' => force_balance_tags($attachment->post_content),
        'href' => get_permalink( $attachment->ID ),
        'src' => esc_url($image[0]),
        'title' => esc_attr($attachment->post_title),
        'width' => esc_attr($image[1]),
        'height' => esc_attr($image[2])
    );
}
function foodfarm_pagination($max_num_pages = null) {
    global $wp_query, $wp_rewrite;

    $max_num_pages = ($max_num_pages) ? $max_num_pages : $wp_query->max_num_pages;

    // Don't print empty markup if there's only one page.
    if ($max_num_pages < 2) {
        return;
    }

    $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
    $pagenum_link = html_entity_decode(get_pagenum_link());
    $query_args = array();
    $url_parts = explode('?', $pagenum_link);

    if (isset($url_parts[1])) {
        wp_parse_str($url_parts[1], $query_args);
    }

    $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
    $pagenum_link = trailingslashit($pagenum_link) . '%_%';

    $format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged') : '?paged=%#%';

    // Set up paginated links.
    $links = paginate_links(array(
        'base' => $pagenum_link,
        'format' => $format,
        'total' => $max_num_pages,
        'current' => $paged,
        'end_size' => 1,
        'mid_size' => 1,
        'prev_next' => True,
        'prev_text' => '<i class="fa fa-long-arrow-left"></i>',
        'next_text' => '<i class="fa fa-long-arrow-right"></i>',
        'type' => 'list'
            ));

    if ($links) :
        ?>
        <nav class="pagination ">
            <?php echo $links ?>        
        </nav>
        <?php
    endif;
}
function foodfarm_get_excerpt($limit = 45) {

    if (!$limit) {
        $limit = 45;
    }

    $allowed_html =array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'ul' => array(),
        'li'  => array(),
        'ol'  => array(),
        'iframe' => array(
            'src' => true,
            'width' => true,
            'height' => true,
            'align' => true,
            'class' => true,
            'name' => true,
            'id' => true,
            'frameborder' => true,
            'seamless' => true,
            'srcdoc' => true,
            'sandbox' => true,
            'allowfullscreen' => true
        ),
        'blockquote'  => array(),
        'embed' => array(
                'width' => array(),
                'height' => array(),
                ),
        'br' => array(),
        'img' => array(
            'alt' => array(),
            'src' => array(),
            'width' => array(),
            'height' =>array(), 
            'id' => array(),
            'style' => array(),
            'class' => array(),
            ),
        'audio' => array(
            'src' => true,
            'width' => true,
            'height' => true,
            'align' => true,
            'class' => true,
            'name' => true,
            'id' => true,
            'preload' => true,
            'style' => true,
            'controls' => true,
        ),
        'source' => array(
            'src' => true,
            'width' => true,
            'height' => true,
            'align' => true,
            'class' => true,
            'name' => true,
            'id' => true,
            'type' => true,
        ),
        'p'  => array(
            'style' => true,
            'class' => true,
            'id' => true,),
        'em' => array(),
        'strong' => array(),
    );

    if (has_excerpt()) {
        $content =  wp_kses(strip_shortcodes(get_the_excerpt()), $allowed_html) ;
    } else {
        $content = get_the_content( );
        $content = apply_filters( 'the_content', $content );
        $content = str_replace( ']]>', ']]&gt;', $content );
        $content =  wp_kses(strip_shortcodes($content), $allowed_html) ;
    }

    $content = explode(' ', $content, $limit);

    if (count($content) >= $limit) {
        array_pop($content);
            $content = implode(" ",$content).'<a href="'.get_the_permalink().'" class="blog-readmore"><i class="fa fa-caret-right"></i>&nbsp;'.esc_html__(' Read More', 'foodfarm').'</a>';
    } else {
        $content = implode(" ",$content);
    }

    return $content;
}
function foodfarm_latest_tweets_date( $created_at ){
   $date = DateTime::createFromFormat('D M d H:i:s O Y', $created_at ); 
    return sprintf( '%s ' . esc_html__( 'ago', 'foodfarm' ), human_time_diff( $date->format('U') ) );
}
function foodfarm_comment_nav() {
    if (get_comment_pages_count() > 1 && get_option('page_comments')) :
        ?>
        <nav class="navigation comment-navigation" role="navigation">
            <div class="comment-nav-links">
        <?php
        if ($prev_link = get_previous_comments_link(__('Older', 'foodfarm'))) :
            printf('<div class="comment-nav-previous">%s</div>', $prev_link);
        endif;

        if ($next_link = get_next_comments_link(__('Newer', 'foodfarm'))) :
            printf('<div class="comment-nav-next">%s</div>', $next_link);
        endif;
        ?>
            </div>
        </nav>
        <?php
    endif;
}
function foodfarm_comment_body_template($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo esc_html($tag) ?> <?php comment_class(empty($args['has_children']) ? 'profile-content ' : 'parent profile-content' ) ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
            <div class="comment-author vcard profile-top">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>    
            </div>

            <div class="profile-bottom">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation"><?php echo esc_html__('Your comment is awaiting moderation.', 'foodfarm'); ?></em>
                    <br />
                <?php endif; ?>

                <div class="profile-name"><?php printf(esc_html__('%s','foodfarm'), get_comment_author_link()); ?></div>
                <div class="date-cmt">
                    <?php
                    printf(esc_html__('%1$s', 'foodfarm'), get_comment_date());
                    ?><?php edit_comment_link(esc_html__('(Edit)', 'foodfarm'), '  ', '');
                    ?>
                </div>

                <div class="comment-content profile-desc">
                    <?php comment_text(); ?>
                </div>
                
                <div class="links-info float-right">
                    <div class="info">
                        <?php  
                            if(function_exists('foodfarm_getPostLikeLink')) {
                                echo foodfarm_getPostLikeLink( get_comment_ID(), 'comment' );
                            }
                        ?>
                    </div>
                    <?php if($depth<$args['max_depth']): ?>
                    <div class="info">
                        <?php comment_reply_link(array_merge($args, array('reply_text'=>'<i class="fa fa-reply"></i>'.esc_html__('Reply', 'foodfarm'),'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ('div' != $args['style']) : ?>
                    </div>
                <?php endif; ?>
            </div>
                <?php

}
add_filter('comment_reply_link', 'foodfarm_reply_link_class');
function foodfarm_reply_link_class($class){
    $class = str_replace("class='comment-reply-link", "class='", $class);
    return $class;
}

    add_action( 'comment_form', 'foodfarm_comment_submit' );
    function foodfarm_comment_submit( $post_id ) {
        if (get_post_type() !== 'product'){
            echo '<div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="comment-submit">
                            <button type="submit" class="submit btn btn-default btn-icon" > <i class="fa fa-long-arrow-right"></i> '.esc_html__('Post Comment', 'foodfarm').'   
                            </button>    
                        </div>
                    </div>';
            }
    }


add_filter('latest_tweets_render_date', 'foodfarm_latest_tweets_date', 10 , 1 );
function foodfarm_latest_tweets_hook( $html, $date, $link, array $tweet ){
    $output ='';
    $output .= '<div class="twitter-tweet"><i class="fa fa-twitter"></i><div class="tweet-text">'.$html.'<p class="my-date">'.$date.'</p></div>';
    
    $output .= ' </div>';
    return $output;
    // echo "<pre>";
    // print_r($tweet);
    // exit;
}
add_filter('latest_tweets_render_tweet', 'foodfarm_latest_tweets_hook', 10, 4 );
//allow html in widget title
function foodfarm_change_widget_title($title)
{
    //convert square brackets to angle brackets
    $title = str_replace('[', '<', $title);
    $title = str_replace(']', '>', $title);

    //strip tags other than the allowed set
    $title = strip_tags($title, '<a><blink><br><span>');
    return $title;
}
add_filter('widget_title', 'foodfarm_change_widget_title');
function foodfarm_custom_excerpt_length( $length ) {
    return 50;
}
add_filter( 'excerpt_length', 'foodfarm_custom_excerpt_length', 999 );
function foodfarm_kke_search_form(){
    ?>
    <div id="live-search" class="searchform">  
        <form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">
            <input type="text" placeholder="<?php echo esc_html__("Enter the keywords", "foodfarm") ?>" value="<?php echo esc_html__("Enter the keywords", "foodfarm") ?>" name="s" id="s" />
            <!--<ul id="kbe_search_dropdown"></ul>-->
            <button type="submit" title="Search" class="button"><?php echo esc_html__('Search', 'foodfarm') ?> <i class="fa fa-search"></i>
            </button>
            <input type="hidden" name="post_type" value="kbe_knowledgebase" />
        </form>
    </div>
    <?php
}
function foodfarm_maintenance_mode(){
    global $foodfarm_settings;
    if(isset($foodfarm_settings['under-contr-mode']) && $foodfarm_settings['under-contr-mode'] ==1){
        if(!current_user_can('edit_themes') || !is_user_logged_in()){
            wp_die(get_template_part('comming-soon'));
        }
    }
}
add_action('get_header', 'foodfarm_maintenance_mode');

add_filter('wp_list_categories', 'foodfarm_cat_count_span');
function foodfarm_cat_count_span($links) {
    $links = str_replace('</a> (', ' (', $links);
    $links = str_replace(')', ')</a>', $links);
    return $links;
}
function foodfarm_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}
add_filter( 'comment_form_fields', 'foodfarm_move_comment_field_to_bottom' );


//add default knowledgebase option settings
function foodfarm_add_default_knowledgebase_options() {
    $settings = array();
    $settings['kbe_plugin_slug'] = 'knowledgebase';
    $settings['kbe_article_qty'] = 5;
    $settings['kbe_search_setting'] = 1;
    $settings['kbe_breadcrumbs_setting'] = 0;
    $settings['kbe_sidebar_home'] = 2;
    $settings['kbe_sidebar_inner'] = 2;
    $settings['kbe_comments_setting'] = 0;
    $settings['kbe_bgcolor'] = '#dd9933';
    add_option('kbe_settings', $settings);
}
add_action( 'after_setup_theme', 'foodfarm_add_default_knowledgebase_options' );
function foodfarm_allow_html(){
    return array(
        'form'=>array(
            'role' => array(),
            'method'=> array(),
            'class'=> array(),
            'action'=>array(),
            'id'=>array(),
            ),
        'input' => array(
            'type' => array(),
            'name'=> array(),
            'class'=> array(),
            'title'=>array(),
            'id'=>array(), 
            'value'=> array(), 
            'placeholder'=>array(), 
            'autocomplete' => array(),
            'data-number' => array(),
            'data-keypress' => array(),                        
            ),
        'button' => array(
            'type' => array(),
            'name'=> array(),
            'class'=> array(),
            'title'=>array(),
            'id'=>array(),                            
            ),                        
        'div'=>array(
            'class'=> array(),
            ),
        'h4'=>array(
            'class'=> array(),
            ),
        'a'=>array(
            'class'=> array(),
            'href'=>array(),
            'onclick' => array(),
            'aria-expanded' => array(),
            'aria-haspopup' => array(),
            'data-toggle' => array(),
            ),
        'i' => array(
            'class'=> array(),
        ),
        'p' => array(
            'class'=> array(),
        ), 
        'span' => array(
            'class'=> array(),
            'onclick' => array(),
            'style' => array(),
        ), 
        'strong' => array(
            'class'=> array(),
        ),  
        'ul' => array(
            'class'=> array(),
        ),  
        'li' => array(
            'class'=> array(),
        ), 
        'del' => array(),
        'ins' => array(),

    );
}

function brandlistcustome() {
	 
  global $wpdb;
        $args = array(
			'hide_empty' => false
        );
		echo '<style>';
		echo '.brandss > li {
			 float: left;
			padding: 20px;
			text-align: center;
			  height: 230px;
			width: 25%;
		}';
		echo '</style>';
        $brands = get_terms('yith_product_brand', $args);
		echo '<ul class="brandss">';
        foreach ($brands as $_brand) {
		$term = get_term( $_brand->term_id, 'yith_product_brand' );//for example uncategorized category
		 
		echo '<li>';
            $thumbnail_id = get_woocommerce_term_meta($_brand->term_id, 'thumbnail_id');
            $_brand->thumbnail_id = $thumbnail_id;
            $_brand->brand_url = get_term_link($_brand->slug, 'yith_product_brand');
            $image = wp_get_attachment_image_src($thumbnail_id, 'full');
            if ($image) {
                echo sprintf('<div class="brands-content"><div class="brands-img"><a title="' . $_brand->name . '" href="%s"><img src="%s" alt="%s"  width= "153"/></a></div></div>', get_term_link($_brand), $image[0], $image[1], $image[2], $_brand->name);
            } else {
                echo sprintf('<div class="brands-content"><div class="brands-img"><a title="' . $_brand->name . '" href="%s"><img src="%s" alt="%s"   width= "153"/></a></div></div>', get_term_link($_brand), wc_placeholder_img_src(), $_brand->name);
            }
			echo '<p>'.$_brand->name.' ('.$term->count.')<p>';
			echo '</li>';
        }
		
	echo '</ul>';
}
add_shortcode('brandlist', 'brandlistcustome');

function homepagesliderfun() {
if ( is_user_logged_in() ) {
    echo do_shortcode( '[rev_slider alias="for-login-user"]' ); //  'Welcome, registered user!';
} else {
	echo do_shortcode( '[rev_slider alias="for-visitor-user"]' ); // echo 'Welcome, visitor!';
}
  
}
add_shortcode('homepageslider', 'homepagesliderfun');
?>
