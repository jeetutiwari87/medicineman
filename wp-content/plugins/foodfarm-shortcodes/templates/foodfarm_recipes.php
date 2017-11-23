<?php
$output  = $number = $first_title = $show_title = $last_title = $description  = $item_delay =  $el_class = '';
extract(shortcode_atts(array(
    'first_title' =>'Recipes',
    'last_title' => 'of The Day',
    'icon_title' => 'icon_1',
    'description' => '',
    'number' => 4,
    'post_display_type' => '',
    'order' => 'desc',
    'cat' => '',
    'layout' => 'slide',
    'columns' => 4,
    'show_viewmore' => '',
    'item_delay' => 'yes',
    'show_title' => '',
    'icon_list' => 'default',
    'icon_type' => '',
    'icon_linecons' => '',
    'icon_fontawesome' => '',
    'icon_openiconic' => '',
    'icon_typicons' => '',
    'icon_pestrokefont' => '',
    'icon_foodfarm' => '',
    'icon_entypo' => '',    
    'el_class' => ''
), $atts));
$icon_class = "";
if (!empty($icon_foodfarm)) {
    $icon_class = $icon_foodfarm;
} elseif (!empty($icon_pestrokefont)) {
    $icon_class = $icon_pestrokefont;
} elseif (!empty($icon_fontawesome)) {
    $icon_class = $icon_fontawesome;
} elseif (!empty($icon_openiconic)) {
    $icon_class = $icon_openiconic;
} elseif (!empty($icon_typicons)) {
    $icon_class = $icon_typicons;
} elseif (!empty($icon_entypo)) {
    $icon_class = $icon_entypo;
} elseif (!empty($icon_linecons)) {
    $icon_class = $icon_linecons;
}
$style="";
if($icon_type == 'foodfarmfont') {
    wp_enqueue_style( 'foodfarmfont' );
} else {
    vc_icon_element_fonts_enqueue($icon_type);
}
if ($post_display_type == 'most-viewed'){
    $args = array(
        'post_type' => 'recipe',
        'post_status' => 'publish',
        'meta_key' => 'post_views_count', 
        'orderby' => 'meta_value_num', 
        'order' => $order,
        'posts_per_page' => $number,
    );    
}
else {
    $args = array(
        'post_type' => 'recipe',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => $number,
        'order' => $order,
        'orderby' => 'date',
    );
}

$catArray = explode(',', $cat);
if ($cat){
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'recipe_cat',
            'field'    => 'term_id',
            'terms'    => $catArray,
        ),
    );
}
$recipes_entry_col = '';

switch ( $columns ) {
    case '3':
        $recipes_entry_col = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';
        break;
    case '2':
        $recipes_entry_col = 'col-lg-6 col-sm-6 col-sm-6 col-xs-12';
        break;
    default:
        $recipes_entry_col = 'col-lg-3 col-md-6 col-sm-6 col-xs-12';
        break;
}
if($layout == 'slide'){
    $layout_class = 'recipes';
}
elseif($layout == 'grid2'){
     $layout_class = 'popular-recipes';
}
else{
    $layout_class = 'best-recipes';
}
query_posts($args);
global $wp_query;
$slide_id = 'recipes_' . wp_rand();
$output = '<div class="'.$layout_class.'"';        
$output .= '>';
ob_start();
?>
<?php 
    $count = 1;
    $count_item = 0.2; 
    $animation_delay = '';
    if($item_delay) {
        $animation_delay = ' data-sr="wait '. $count_item .'s"';  
    }
    $count_item += 0.2;
?>
<?php if (have_posts()) : ?>
        <div class="row"> 
            <?php if($layout == 'slide') :?>
                <?php if ( isset( $show_title ) && $show_title ) :?>
                    <?php if($first_title || $last_title) :?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div <?php echo $animation_delay; ?> class="entry-title title-icon text-center <?php if($layout == 'slide'){echo 'text-white';}?> <?php if($icon_title == 'icon_2'){echo 'title-icon2';} ?> ">
                            <h2><span><?php echo $first_title;?></span> <?php echo $last_title;?></h2>
                            <?php if($icon_list != 'none'): ?>
                                <?php if($icon_list != 'font_family'):?>
                                    <div class="icon-title"><span class="icon-<?php if($icon_title == 'icon_1'){echo '8';}else{echo '7';}?>"></span></div>
                                <?php else:?>
                                    <div class="icon-title font_family_type"><span class="<?php echo esc_attr($icon_class); ?>"></span></div>
                                <?php endif;?>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php endif;?>
                <?php endif;?>
            <?php else :?>
                <?php if($first_title || $last_title) :?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div <?php echo $animation_delay; ?> class="entry-title title-icon text-center <?php if($layout == 'slide'){echo 'text-white';}?> <?php if($icon_title == 'icon_2'){echo 'title-icon2';} ?> ">
                        <h2><?php echo esc_html($first_title);?> <span><?php echo esc_html($last_title);?></span></h2>
                        <?php if($icon_list != 'none'): ?>
                            <?php if($icon_list != 'font_family'):?>
                                <div class="icon-title"><span class="icon-<?php if($icon_title == 'icon_1'){echo '8';}else{echo '7';}?>"></span></div>
                            <?php else:?>
                                <div class="icon-title font_family_type"><span class="<?php echo esc_attr($icon_class); ?>"></span></div>
                            <?php endif;?>
                        <?php endif;?>
                        <p class="align_center"><?php echo wp_kses( $description, array('br' => array()));?></p>
                    </div>
                </div>
                <?php endif;?>
            <?php endif;?>   
            <div class="recipe-body"> 
                <?php if($layout == 'slide') :?>
                <div id="<?php echo $slide_id; ?>" class="owl-carousel">
                <?php endif;?>
                    <?php while ( have_posts() ) : the_post(); $count++;
                        $index = $wp_query->current_post + 1;   ?>
                        <?php if($layout == 'slide') :?>
                        <div <?php echo $animation_delay; ?> class="item">
                            <?php 
                            $attachment_id = get_post_thumbnail_id();
                            $attachment_carousel = foodfarm_get_attachment($attachment_id, 'foodfarm-recipes-carousel'); 
                            ?>
                            <div class="col-md-6 col-sm-12 col-xs-12 style-img-recipes">
                                <div class="img-recipes">
                                    <img class="img-responsive" width="<?php echo esc_attr($attachment_carousel['width']) ?>" height="<?php echo esc_attr($attachment_carousel['height']) ?>" src="<?php echo esc_url($attachment_carousel['src']) ?>" alt="<?php echo esc_attr($attachment_carousel['alt']) ?>" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="blog-content recipes-content">
                                    <div class="blog-item">
                                        <div class="blog-post-info">
                                            <div class="blog-date">
                                                <p class="date"><?php echo get_the_time('d'); ?></p>
                                                <p class="month"><?php echo get_the_time('M'); ?></p>
                                            </div>
                                            <div class="blog-post-title">
                                                <div class="post-name">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                                </div>
                                                <div class="post-info"><?php if ($post_display_type == 'most-viewed'){echo esc_html__('Popular Recipes','foodfarm');} ?></div>
                                            </div>
                                            <div class="blog_post_desc">
                                                <?php echo wpautop(get_post_meta(get_the_ID(),'desc', true),false);?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php elseif($layout == 'grid2'):?>
                        <div class="<?php echo $recipes_entry_col;?> item-grid">
                            <div class="blog-content blog-ful-2">
                                <div class="blog-item">
                                    <?php 
                                    $attachment_id = get_post_thumbnail_id();
                                    $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-recipe-grid-2'); 
                                    ?>
                                    <div class="blog-img">
                                        <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid['width']) ?>" height="<?php echo esc_attr($attachment_grid['height']) ?>" src="<?php echo esc_url($attachment_grid['src']) ?>" alt="<?php echo esc_attr($attachment_grid['alt']) ?>" /></a>
                                    </div>
                                    <div class="blog-post-info">
                                        <div class="number-blog"><?php echo '0'.$index.'.'; ?></div>
                                        <div class="post-info-content">
                                            <div class="blog-post-title">
                                                <div class="post-name">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                                </div>
                                            </div>
                                            <div class="blog_post_desc">
                                                <?php echo wpautop(get_post_meta(get_the_ID(),'desc', true),false);?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else :?>
                        <div class="<?php echo $recipes_entry_col;?>">
                            <div <?php echo $animation_delay; ?> class="blog-content blog-ful">
                                <div class="blog-item">
                                    <?php 
                                    $attachment_id = get_post_thumbnail_id();
                                    $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-recipes-grid'); 
                                    ?>
                                    <div class="blog-img">
                                        <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid['width']) ?>" height="<?php echo esc_attr($attachment_grid['height']) ?>" src="<?php echo esc_url($attachment_grid['src']) ?>" alt="<?php echo esc_attr($attachment_grid['alt']) ?>" /></a>
                                    </div>
                                    <div class="blog-post-info">
                                        <div class="blog-post-title">
                                            <div class="post-name">
                                                <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                            </div>
                                        </div>
                                        <div class="blog_post_desc">
                                            <?php echo wpautop(get_post_meta(get_the_ID(),'desc', true),false);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>
                    <?php endwhile;?>
                <?php if($layout == 'slide') :?>
                    </div>
                <?php endif;?>
                 <?php if($layout == 'grid2') :?>
                    <?php if($show_viewmore) :?>
                    <div class="col-md-12 col-sm-12 col-xs-12" <?php echo $animation_delay; ?>>
                        <div class="load-more text-center">
                            <div id="load-recipes" class="load-recipes">
                                <a class="btn btn-default btn-default-2" href="<?php echo get_post_type_archive_link('recipe'); ?>"><?php echo esc_html__('More Recipes', 'foodfarm'); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                <?php endif;?>
                <?php if($layout == 'grid') :?>
                    <?php if($show_viewmore) :?>
                    <div class="col-md-12 col-sm-12 col-xs-12" <?php echo $animation_delay; ?>>
                        <div class="load-more text-center">
                            <div id="load-recipes" class="load-recipes">
                                <a class="btn btn-default btn-icon" href="<?php echo get_post_type_archive_link('recipe'); ?>"><?php echo esc_html__('View More', 'foodfarm'); ?> <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                <?php endif;?>
            </div>

        </div>
    <?php if($layout == 'slide') :?>
        <script type="text/javascript">
            jQuery(function ($) {
                var owl = $("#<?php echo esc_js($slide_id); ?>");
                owl.owlCarousel({
                    <?php if (is_rtl()) :?>
                    rtl:true,
                    <?php endif;?>
                    dots:false,
                    loop:false,
                    margin:10,
                    nav:true,
                    navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                    items: 1,
                }); //end: owl
            });
        </script>
    <?php endif;?>
<?php endif;?>
<?php
    $output .= ob_get_clean();

    $output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_recipes' ) . "\n";

    echo $output;

wp_reset_query(); ?>