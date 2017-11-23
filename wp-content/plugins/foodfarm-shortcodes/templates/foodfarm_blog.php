<?php
$output  = $number = $first_title = $description = $btn_text = $show_title= $last_title = $item_delay =  $el_class = '';
extract(shortcode_atts(array(
    'first_title' =>'Our',
    'last_title' => 'Blog',
    'icon_title' => 'icon_1',
    'description' => '',
    'number' => 4,
    'post_display_type' => '',
    'orderby' => 'date',
    'order' => 'desc',
    'cat' => '',
    'layout' => 'list',
    'sticky_type' => 'left',
    'btn_text' => '',
    'columns' => 4,
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
if ($post_display_type == 'featured') {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'meta_key' => 'special_box_check',
        'orderby' => $orderby,
        'order' => $order,
        'posts_per_page' => $number,
    );
}
else if ($post_display_type == 'most-viewed'){
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'meta_key' => 'post_views_count', 
        'orderby' => 'meta_value_num', 
        'order' => $order,
        'posts_per_page' => $number,
    );    
}
else {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order,
        'posts_per_page' => $number,
    );
}

if ($cat){
    $args['cat'] = $cat;
}
$blog_entry_col = '';

switch ( $columns ) {
    case '3':
        $blog_entry_col = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
        break;
    case '2':
        $blog_entry_col = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
        break;
    default:
        $blog_entry_col = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
        break;
}
if($sticky_type == 'right'){
    $layout_class = 'our-blog-2';
}elseif($layout == 'grid4'){
    $layout_class = ' our-blog-4';
}elseif($layout == 'grid5'){
    $layout_class = ' popular-recipes';
}elseif($layout == 'grid6'){
    $layout_class = ' our-blog-6';
}

else{
    $layout_class = '';
}
if($layout == 'grid'){
    $grid_class = 'bg-none';
}
else{
    $grid_class = '';
}  
query_posts($args);
$slide_id = 'blog_' . wp_rand();
$output = '<div class="our-blog '.$layout_class.' '.$grid_class.'"';    
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
            <?php if($show_title):?>  
                <?php if($first_title || $last_title) :?>
                <div class="col-xs-12 <?php if($sticky_type == 'right'){echo 'hidden-lg hidden-md';}?>">
                    <div class="entry-title title-icon text-center <?php if($icon_title == 'icon_2'){echo 'title-icon2';} ?>" <?php echo $animation_delay; ?>>
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
            <?php if($layout == 'list') :?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php if(is_sticky() && $sticky_type != 'list-default') :?>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if($sticky_type == 'right'){echo 'blog-style-2';}?>">
                                <div <?php if($sticky_type == 'left') {echo $animation_delay;} ?> class="blog-content <?php echo $sticky_type == 'left' ? 'blog-ful' : '' ?>">
                                    <div class="blog-item">
                                        <?php 
                                        $attachment_id = get_post_thumbnail_id();
                                        $attachment_sticky_1 = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-sticky-1'); 
                                        $attachment_sticky_2 = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-sticky-2'); 
                                        ?>
                                        <?php if (has_post_thumbnail()): ?>
                                        <div class="blog-img <?php if($sticky_type == 'right'){echo 'has-overlay';}?>">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php if($sticky_type == 'left') :?>
                                                <img class="img-responsive" width="<?php echo esc_attr($attachment_sticky_1['width']) ?>" height="<?php echo esc_attr($attachment_sticky_1['height']) ?>" src="<?php echo esc_url($attachment_sticky_1['src']) ?>" alt="blog-img" />
                                                <?php else :?>
                                                <img class="img-responsive" width="<?php echo esc_attr($attachment_sticky_2['width']) ?>" height="<?php echo esc_attr($attachment_sticky_2['height']) ?>" src="<?php echo esc_url($attachment_sticky_2['src']) ?>" alt="blog-img" />    
                                                <?php endif;?>
                                            </a>
                                        </div>
                                        <?php endif;?>
                                        <div class="blog-post-info">
                                            <?php if($sticky_type == 'left') :?>
                                            <div class="blog-date">
                                                <p class="date"><?php echo get_the_time('d'); ?></p>
                                                <p class="month"><?php echo get_the_time('M'); ?></p>
                                            </div>
                                            <?php endif;?>
                                            <div class="blog-post-title">
                                                <div class="post-name">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                                </div>
                                                <div class="post-info"><?php echo get_the_category_list(', '); ?></div>
                                            </div>
                                            <div class="blog_post_desc">
                                                <?php 
                                                        echo wp_trim_words(get_the_excerpt(), 40,"");
                                                    ?>    
                                            </div>
                                            <div class="blog-info">
                                                <?php if($sticky_type == 'left') :?>
                                                <div class="blog-comment"> 
                                                    <?php comments_popup_link('<i class="fa fa-comment"></i>'.esc_html__('', 'foodfarm'), '<i class="fa fa-comment"></i> '.esc_html__('1', 'foodfarm'), '<i class="fa fa-comment"></i> '.esc_html__('%', 'foodfarm')); ?> 
                                                </div>
                                                <?php endif;?>
                                                <div class="read-more">
                                                    <a href="<?php the_permalink(); ?>" class="<?php if($sticky_type == 'right'){echo 'btn btn-default btn-icon';}?>">
                                                        <?php if($sticky_type == 'right'){echo '<i class="fa fa-long-arrow-right"></i>';}else{echo '<i class="fa fa-arrow-right"></i>';}?>
                                                        <?php if($sticky_type == 'right'){echo esc_html__('View more','foodfarm');}else{echo esc_html__('Read more','foodfarm');} ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>        
                    <?php endif;?>
                <?php endwhile;?>
                <?php if($sticky_type != 'list-default') :?>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if($sticky_type == 'right'){echo 'our-blog-left';}?>"> 
                <?php endif;?>
                <?php if($layout== 'list' && $sticky_type == 'right') :?>
                    <?php if($first_title || $last_title) :?>
                    <div <?php echo $animation_delay; ?> class="entry-title title-icon hidden-sm hidden-xs <?php if($icon_title == 'icon_2'){echo 'title-icon2';} ?>">
                        <h2><?php echo esc_html($first_title);?> <span><?php echo esc_html($last_title);?></span></h2>
                        <div class="icon-title"><span class="icon-<?php if($icon_title == 'icon_1'){echo '8';}else{echo '7';}?>"></span></div>
                    </div>
                    <?php endif;?>
                <?php endif;?>   
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php if(!is_sticky() || $sticky_type == 'list-default') :?>
                        <?php if($sticky_type == 'list-default') :?>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 blog-list-default"> 
                        <?php endif;?>
                        <div <?php echo $animation_delay; ?> class="blog-content blog-list">
                            <div class="blog-item">
                                <?php 
                                $attachment_id = get_post_thumbnail_id();
                                $attachment_list = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-small'); 
                                ?>
                                <?php if (has_post_thumbnail()): ?>
                                <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_list['width']) ?>" height="<?php echo esc_attr($attachment_list['height']) ?>" src="<?php echo esc_url($attachment_list['src']) ?>" alt="blog-img" /></a>
                                </div>
                                <?php endif;?>
                                <div class="blog-post-info">
                                    <div class="blog-post-title">
                                        <div class="post-name">
                                            <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                        </div>
                                        <div class="post-info"><?php echo get_the_category_list(', '); ?></div>
                                    </div>
                                    <div class="blog_post_desc">
                                        <p>
                                            <?php 
                                                echo wp_trim_words(get_the_excerpt(), 40,"");
                                            ?>  
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if($sticky_type == 'list-default') :?>
                        </div> 
                        <?php endif;?>
                    <?php endif;?>
                <?php endwhile;?>
                <?php if($sticky_type != 'list-default') :?>
                </div>
                <?php endif;?>
            <?php endif;?>   
            <?php if($layout == 'grid') :?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="<?php echo $blog_entry_col;?>">
                        <div class="blog-content blog-ful">
                            <div class="blog-item">
                                <?php 
                                $attachment_id = get_post_thumbnail_id();
                                $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-grid'); 
                                ?>
                                <?php if (has_post_thumbnail()): ?>
                                <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid['width']) ?>" height="<?php echo esc_attr($attachment_grid['height']) ?>" src="<?php echo esc_url($attachment_grid['src']) ?>" alt="blog-grid-img" /></a>
                                </div>
                                <?php endif;?>
                                <div class="blog-post-info">
                                    <div class="blog-date">
                                        <p class="date"><?php echo get_the_time('d'); ?></p>
                                        <p class="month"><?php echo get_the_time('M'); ?></p>
                                    </div>
                                    <div class="blog-post-title">
                                        <div class="post-name">
                                            <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                        </div>
                                    </div>
                                    <div class="blog_post_desc">
                                        <?php 
                                            echo wp_trim_words(get_the_excerpt(), 30,"");
                                        ?>  
                                    </div>
                                    <div class="blog-info">
                                        <div class="blog-comment"> 
                                            <?php comments_popup_link('<i class="fa fa-comment"></i>'.esc_html__('', 'foodfarm'), '<i class="fa fa-comment"></i> '.esc_html__('1', 'foodfarm'), '<i class="fa fa-comment"></i> '.esc_html__('%', 'foodfarm')); ?>  
                                        </div>
                                        <div class="read-more">
                                            <a href="<?php the_permalink(); ?>"><i class="fa fa-arrow-right"></i> &nbsp;<?php echo esc_html__('Read more','foodfarm'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile;?>
            <?php endif;?>
            <?php if($layout == 'grid2') :?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="<?php echo $blog_entry_col;?>">
                        <div class="blog-content blog-ful-3">
                            <div class="blog-item">
                                <?php 
                                $attachment_id = get_post_thumbnail_id();
                                $attachment_grid_2 = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-grid-2'); 
                                ?>
                                <?php if (has_post_thumbnail()): ?>
                                <div class="blog-img has-overlay">
                                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid_2['width']) ?>" height="<?php echo esc_attr($attachment_grid_2['height']) ?>" src="<?php echo esc_url($attachment_grid_2['src']) ?>" alt="blog-grid-img" /></a>
                                    <div class="blog-date">
                                        <p class="date"><?php echo get_the_time('d'); ?></p>
                                        <p class="month"><?php echo get_the_time('M'); ?></p>
                                    </div>
                                </div>
                                <?php endif;?>
                                <div class="blog-post-info">
                                    <div class="blog-post-title">
                                        <div class="post-name">
                                            <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                        </div>
                                        <div class="post-info"><?php echo get_the_category_list(', '); ?></div>
                                    </div>
                                    <div class="blog_post_desc">
                                        <?php 
                                            echo wp_trim_words(get_the_excerpt(), 30,"");
                                        ?>  
                                    </div>
                                    <div class="blog-info">
                                        <div class="blog-comment"> 
                                            <?php comments_popup_link('<i class="fa fa-comment"></i>'.esc_html__('', 'foodfarm'), '<i class="fa fa-comment"></i> '.esc_html__('1', 'foodfarm'), '<i class="fa fa-comment"></i> '.esc_html__('%', 'foodfarm')); ?>  
                                        </div>
                                        <div class="read-more">
                                            <a href="<?php the_permalink(); ?>"><i class="fa fa-arrow-right"></i> &nbsp;<?php echo esc_html__('Read more','foodfarm'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile;?>
            <?php endif;?> 
            <?php if($layout == 'grid3') :?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="<?php echo $blog_entry_col;?>">
                        <div class="blog-content blog-grid-style3">
                            <div class="blog-item">
                                <?php 
                                $attachment_id = get_post_thumbnail_id();
                                $attachment_grid_3 = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-grid-3'); 
                                ?>
                                <?php if (has_post_thumbnail()): ?>
                                <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid_3['width']) ?>" height="<?php echo esc_attr($attachment_grid_3['height']) ?>" src="<?php echo esc_url($attachment_grid_3['src']) ?>" alt="blog-grid-img" /></a>
                                   
                                </div>
                                <?php endif;?>
                                <div class="blog-post-info">
                                     <div class="blog-info">
                                        <div class="info author-name">
                                            <a href="<?php echo esc_url(get_edit_user_link( )); ?>"><i class="fa fa-user"></i> <?php echo esc_html__('posted by', 'foodfarm');?> <span><?php echo get_the_author(); ?></span></a>
                                        </div>
                                        <div class="info"> 
                                            <?php comments_popup_link('<i class="fa fa-comments-o"></i>'.esc_html__('0', 'foodfarm'), '<i class="fa fa-comments-o"></i> '.esc_html__('1', 'foodfarm'), '<i class="fa fa-comments-o"></i> '.esc_html__('%', 'foodfarm')); ?>  
                                        </div>
                                        <div class="info blog-time">
                                            <p><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo get_the_date('d M'); ?></p>
                                        </div>
                                    </div>
                                    <div class="blog-post-title">
                                        <div class="post-name">
                                            <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                        </div>
                                    </div>
                                    <div class="blog_post_desc">
                                        <?php 
                                            echo wp_trim_words(get_the_excerpt(), 30,"");
                                        ?>  
                                    </div>
                                    <div class="read-more">
                                        <a href="<?php the_permalink(); ?>">
                                        <?php if($btn_text != ''){
                                                echo esc_html($btn_text);
                                                }else{
                                                echo esc_html__('Read more','foodfarm');
                                                } ?>  
                                        </a>
                                    </div>
                                </div>     
                            </div>
                        </div>
                    </div>
                <?php endwhile;?>
            <?php endif;?> 
            <?php if($layout == 'grid4') :?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="<?php echo $blog_entry_col;?>">
                        <div class="blog-content blog-grid-style4 blog-grid-style3">
                            <div class="blog-item">
                                <?php 
                                $attachment_id = get_post_thumbnail_id();
                                $attachment_grid_3 = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-grid-4'); 
                                ?>
                                <?php if (has_post_thumbnail()): ?>
                                <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid_3['width']) ?>" height="<?php echo esc_attr($attachment_grid_3['height']) ?>" src="<?php echo esc_url($attachment_grid_3['src']) ?>" alt="blog-grid-img" /></a>
                                   
                                </div>
                                <?php endif;?>
                                <div class="blog-post-info">
                                    <div class="blog-post-title">
                                        <div class="post-name">
                                            <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                        </div>
                                    </div>                                
                                    <div class="blog-info">
                                        <div class="info blog-time">
                                            <p><a href="<?php the_permalink(); ?>"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo get_the_date('F d, Y'); ?></a></p>
                                        </div>                                     
                                        <div class="info"> 
                                            <?php comments_popup_link('<i class="fa fa-comments" aria-hidden="true"></i>'.esc_html__('0 Comment', 'foodfarm'), '<i class="fa fa-comments"></i> '.esc_html__('1 Comment', 'foodfarm'), '<i class="fa fa-comments"></i> '.esc_html__('% Comments', 'foodfarm')); ?>  
                                        </div>
                                    </div>

                                    <div class="blog_post_desc">
                                        <?php 
                                            echo wp_trim_words(get_the_excerpt(), 30,"");
                                        ?>  
                                    </div>
                                    <div class="read-more">
                                        <a href="<?php the_permalink(); ?>">
                                        <?php if($btn_text != ''){
                                                echo esc_html($btn_text);
                                                }else{
                                                echo esc_html__('Read more','foodfarm');
                                                } ?>  
                                        </a>
                                    </div>
                                </div>     
                            </div>
                        </div>
                    </div>
                <?php endwhile;?>
            <?php endif;?> 
            <?php if($layout == 'grid5') :?>
                <?php 
                global $wp_query;
                while ( have_posts() ) : the_post(); 
                $index = $wp_query->current_post + 1;?>

                    <div class="<?php echo $blog_entry_col;?> item-grid">
                        <div class="blog-content blog-ful-2">
                            <div class="blog-item">
                                <?php 
                                $attachment_id = get_post_thumbnail_id();
                                $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-grid-4'); 
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
                                        <?php 
                                            echo wp_trim_words(get_the_excerpt(), 30,"");
                                        ?>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                <?php endwhile;?>
                    <div class="col-md-12 col-sm-12 col-xs-12" <?php echo $animation_delay; ?>>
                        <div class="load-more text-center">
                            <div id="load-recipes" class="load-recipes">
                                <a class="btn btn-default btn-default-2" href="<?php echo get_post_type_archive_link('post'); ?>">
                                        <?php if($btn_text != ''){
                                        echo esc_html($btn_text);
                                        }else{
                                        echo esc_html__('Read more','foodfarm');
                                        } ?>                                    
                                </a>
                            </div>
                        </div>
                    </div>                
            <?php endif;?> 
            <?php if($layout =='grid6'):?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="<?php echo $blog_entry_col;?>">
                        <div class="blog-content blog-ful-3 blog_grid6">
                            <div class="blog-item">
                                <?php 
                                $attachment_id = get_post_thumbnail_id();
                                $attachment_grid_2 = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-grid-6'); 
                                ?>
                                <?php if (has_post_thumbnail()): ?>
                                <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid_2['width']) ?>" height="<?php echo esc_attr($attachment_grid_2['height']) ?>" src="<?php echo esc_url($attachment_grid_2['src']) ?>" alt="blog-grid-img" /></a>
                                </div>
                                <?php endif;?>
                                <div class="blog-post-info">
                                    <div class="blog-dateinfo">
                                        <div class="post-date">
                                            <p class="date"><?php echo get_the_time('d'); ?></p>
                                            <p class="month"><?php echo get_the_time('M'); ?></p>
                                        </div>                            
                                        <div class="blog-comment"> 
                                            <?php comments_popup_link('<i class="fa fa-comments-o"></i>'.esc_html__('', 'foodfarm'), '<i class="fa fa-comments-o"></i> '.esc_html__('1', 'foodfarm'), '<i class="fa fa-comments-o"></i> '.esc_html__('%', 'foodfarm')); ?>  
                                        </div>                              
                                    </div>                                
                                    <div class="blog-post-title">
                                        <div class="post-name">
                                            <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                        </div>
                                        <div class="blog_post_desc">
                                            <?php 
                                                echo wp_trim_words(get_the_excerpt(), 10,"");
                                            ?>  
                                        </div>
                                        <div class="post-bottom">
                                            <div class="author">
                                                <a href="<?php echo esc_url(get_edit_user_link( )); ?>"><i class="fa fa-user"></i> <?php echo get_the_author(); ?></a>                                                  
                                            </div>   
                                            <div class="tag">
                                                <i class="fa fa-tags"></i><?php echo get_the_tag_list('',', '); ?>
                                            </div>                                           
                                        </div>                                        
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile;?>
            <?php endif;?>                              
        </div>
<?php endif;?>
<?php
    $output .= ob_get_clean();

    $output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_blog' ) . "\n";

    echo $output;

wp_reset_query(); ?>