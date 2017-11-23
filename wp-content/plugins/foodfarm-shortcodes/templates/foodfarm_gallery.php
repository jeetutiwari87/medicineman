<?php
$output  = $number = $first_title = $last_title = $item_delay =  $el_class = '';
extract(shortcode_atts(array(
    'first_title' =>'Our',
    'last_title' => 'Gallery',
    'icon_title' => 'icon_1',
    'number' => 8,
    'cat' => '',
    'order' => 'desc',
    'layout' => 'layout_1',
    'columns' => 3,
    'show_viewmore' => '',
    'item_delay' => 'yes',
    'el_class' => ''
), $atts));
$args = array(
    'post_type' => 'gallery',
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page' => $number,
    'order' => $order,
    'orderby' => 'date',
);
$catArray = explode(',', $cat);
if ($cat){
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'gallery_cat',
            'field'    => 'term_id',
            'terms'    => $catArray,
        ),
    );
}
$gallery_entry_col = '';

switch ( $columns ) {
    case '3':
        $gallery_entry_col = 'col-md-4 col-sm-6 col-xs-12';
        break;
    case '2':
        $gallery_entry_col = 'col-sm-6 col-sm-6 col-xs-12';
        break;
    default:
        $gallery_entry_col = 'col-md-3 col-sm-6 col-xs-12';
        break;
}
query_posts($args);
global $wp_query;
$el_class = foodfarm_shortcode_extract_class( $el_class );
$output = '<div class="our-gallery ' . $el_class . '"';
$output .= '>';
ob_start();
?>
<?php 
    $count_item = 0.2; 
    $animation_delay = '';
    if($item_delay) {
        $animation_delay = ' data-sr="wait '. $count_item .'s"';  
    }
    $count_item += 0.2;
?>
<?php if (have_posts()) : ?>
        <div class="row">    
            <?php if($layout == 'layout_1') :?>
                <?php if($first_title || $last_title) :?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div <?php echo $animation_delay; ?> class="entry-title title-icon text-center <?php if($icon_title == 'icon_2'){echo 'title-icon2';} ?>">
                        <h2><?php echo $first_title;?> <span><?php echo $last_title;?></span></h2>
                        <div class="icon-title"><span class="icon-<?php if($icon_title == 'icon_1'){echo '8';}else{echo '7';}?>"></span></div>
                    </div>
                </div>
                <?php endif;?>
            <?php endif;?>
            <div class="col-md-12 col-sm-12 col-xs-12 gallery">
                <?php while ( have_posts() ) : the_post(); ?>
                <div class="<?php echo $gallery_entry_col;?> no-padding">
                    <div class="gallery-content bg-hover">
                        <?php 
                        $attachment_id = get_post_thumbnail_id();
                        $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-gallery-grid');  
                        $attachment_grid_2 = foodfarm_get_attachment($attachment_id, 'foodfarm-gallery-grid_2');  
                        ?>
                        <?php if($layout == 'layout_1') :?>
                        <div class="gallery-img">
                            <img class="img-responsive" width="<?php echo esc_attr($attachment_grid['width']) ?>" height="<?php echo esc_attr($attachment_grid['height']) ?>" src="<?php echo esc_url($attachment_grid['src']) ?>" alt="<?php echo esc_attr($attachment_grid['alt']) ?>" />
                        </div>
                        <?php endif;?>
                        <?php if($layout == 'layout_2') :?>
                        <div class="gallery-img layout_2">
                            <a href="#inline_demo<?php the_id();?>" rel="prettyPhoto[inline]"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid_2['width']) ?>" height="<?php echo esc_attr($attachment_grid_2['height']) ?>" src="<?php echo esc_url($attachment_grid_2['src']) ?>" alt="<?php echo esc_attr($attachment_grid_2['alt']) ?>" /></a>
                        </div>
                        <?php endif;?>
                        <div class="gallery-desc">
                            <a href="#inline_demo<?php the_id();?>" rel="prettyPhoto[inline]"><?php the_title(); ?></a>
                            <p>
                                <?php  if(function_exists('foodfarm_getPostLikeLink')) {
                                        echo foodfarm_getPostLikeLink( get_the_ID() );
                                        }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="gallery-lightbox" id="inline_demo<?php the_id();?>">
                        <div class="lightbox-content">
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail();?>
                            <?php endif;?>
                            <div class="gallery-text">
                                <a href="#"></a>
                                <div class="gallery-info"><?php the_content();?></div>
                                <?php  if(function_exists('foodfarm_getPostLikeLink')): ?>
                                    <p><?php echo foodfarm_getPostLikeLink( get_the_ID() ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile;?>
            </div>
            <?php if($layout == 'layout_2') :?>
            <div class="bg_gallery_2">
            <?php endif;?>
            <?php if($layout == 'layout_2') :?>
                <?php if($first_title || $last_title) :?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div <?php echo $animation_delay; ?> class="entry-title title-icon text-center <?php if($icon_title == 'icon_2'){echo 'title-icon2';} ?>">
                        <h2><?php echo $first_title;?> <span><?php echo $last_title;?></span></h2>
                        <div class="icon-title"><span class="icon-<?php if($icon_title == 'icon_1'){echo '8';}else{echo '7';}?>"></span></div>
                    </div>
                </div>
                <?php endif;?>
                <div class="gallery_description" <?php echo $animation_delay; ?>>
                    <?php 
                    echo wpb_js_remove_wpautop(do_shortcode($content), true);
                    ?>
                </div>
            <?php endif;?>    
                <?php if($show_viewmore) :?>
                <div <?php echo $animation_delay; ?> class="col-md-12 col-sm-12 col-xs-12">
                    <div class="load-more text-center">
                        <div id="load-gallery" class="load-gallery">
                            <a class="btn btn-default btn-no-radius" href="<?php echo get_post_type_archive_link('gallery'); ?>"><?php echo esc_html__('View More', 'foodfarm'); ?></a>
                        </div>
                    </div>
                </div>
                <?php endif;?>
            <?php if($layout == 'layout_2') :?>
            </div>
            <?php endif;?>    
        </div>
<?php endif;?>
<?php
    $output .= ob_get_clean();

    $output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_gallery' ) . "\n";

    echo $output;

wp_reset_query(); ?>