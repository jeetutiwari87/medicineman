<ul>
	<?php while (have_posts()) : the_post(); ?>
	<li class="blog-content press-media">
		<div class="blog-item">
			<div class="row">
				<?php if (has_post_thumbnail()): ?>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
					<div class="bg-hover">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail();?></a>
					</div>
				</div>
				<?php endif;?>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" >
					<div class="blog-post-info">
						<div class="blog-post-title">
							<div class="post-name">
								<a href="<?php echo get_post_meta(get_the_ID(),'media_press_link', true);?>" target="_blank"><?php the_title();?></a>
							</div>
							<div class="blog-info">
								<div class="info">
									<p><?php echo get_the_time('M'); ?> <?php echo get_the_time('d'); ?>, <?php echo get_the_time('Y'); ?> 
										<span>- <?php 
									$terms = wp_get_post_terms($post->ID, 'pressmedia_cat');
									$count = count($terms);
									if ( $count > 0 ) {
									    foreach ( $terms as $term ) {
									        echo $term->name;
									    }
									}
									 ?></span>
									</p>
								</div>
							</div>
						</div>
						<div class="blog_post_desc">
							<?php the_content();?>
							<div class="read-more">
								<a href="<?php echo esc_url(get_post_meta(get_the_ID(),'media_press_link', true));?>" target="_blank"><i class="fa fa-caret-right"></i><?php echo esc_html__('Read more', 'foodfarm');?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</li>
	<?php endwhile;?>
</ul>
<div class="col-md-12 col-sm-12 col-xs-12 text-left no-padding">
	<?php foodfarm_pagination(); ?> 
</div>