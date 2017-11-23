<?php 
$foodfarm_settings = foodfarm_check_theme_options();
$current_page = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
$classes = '';
switch ( $foodfarm_settings['gallery-cols'] ) {
    case '4':
        $classes = 'col-md-3 col-sm-4 col-xs-12';
        break;
    case '2':
        $classes = 'col-md-6 col-sm-6 col-xs-12';
        break;
    default:
        $classes = 'col-md-4 col-sm-4 col-xs-12 ';
        break;
}
?>
<div class="gallery-masonry">
			<div id="grid" class="grid gallery gallery-entries-wrap">
				<?php while (have_posts()) : the_post(); ?>
				<div class="grid-item <?php echo esc_attr($classes);?>">
					<div class="gallery-content bg-hover">
						<?php if (has_post_thumbnail()): ?>
						<div class="gallery-img">
							<?php the_post_thumbnail();?>
						</div>
						<?php endif;?>
						<div class="gallery-desc">
							<a rel="prettyPhoto[inline]" href="#inline_demo<?php the_id();?>"><?php the_title();?></a>
							<p>
								<?php  if(function_exists('foodfarm_getPostLikeLink')) {
                                        echo foodfarm_getPostLikeLink( get_the_ID() );
                                        }
                                ?>
							</p>
						</div>
					</div>
					<div id="inline_demo<?php the_id();?>" class="gallery-lightbox">
						<div class="lightbox-content">
							<?php if (has_post_thumbnail()): ?>
								<?php the_post_thumbnail();?>
							<?php endif;?>
							<div class="gallery-text">
								<a href="#"><?php the_title();?></a>
								<div class="gallery-info"><?php the_content();?></div>
                                <?php  if(function_exists('foodfarm_getPostLikeLink')): ?>
                                <p><?php echo foodfarm_getPostLikeLink( get_the_ID() ); ?></p>
                                <?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endwhile; ?>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<?php if($foodfarm_settings['gallery-archive-pagination-type'] == 'nav' ) :?>
					<?php foodfarm_pagination(); ?> 
				<?php endif;?>
				<?php if($foodfarm_settings['gallery-archive-pagination-type'] == 'loadmore' ) :?>
					<?php if ($wp_query->max_num_pages > 1) : ?>
					    <div class="load-more text-center">
					        <a data-paged="<?php echo esc_attr($current_page) ?>" data-totalpage="<?php echo esc_attr($wp_query->max_num_pages) ?>" id="gallery-loadmore" class="btn btn-default btn-icon"><?php echo esc_html__('View More', 'foodfarm') ?> <i class="fa fa-long-arrow-right"></i></a>
					    </div>
					<?php endif; ?>
				<?php endif;?>
			</div>
</div>
