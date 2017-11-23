<div class="recipes-list">
    <ul>
    	<?php while (have_posts()) : the_post(); ?>
    		<li class="blog-content recipes-list-container">
                <div class="blog-item">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="img-recipes bg-hover">
                                    <?php 
                                    $attachment_id = get_post_thumbnail_id();
                                    $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-recipe-list'); 
                                    ?>
                                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid['width']) ?>" height="<?php echo esc_attr($attachment_grid['height']) ?>" src="<?php echo esc_url($attachment_grid['src']) ?>" alt="<?php echo esc_attr($attachment_grid['alt']) ?>" /></a>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div class="blog-post-info blog-post-bg text-style-bg ">
                                <div class="blog-date">
                                    <p class="date"><?php echo get_the_time('d'); ?></p>
                                    <p class="month"><?php echo get_the_time('M'); ?></p>
                                    <a href="<?php the_permalink(); ?>"></a>                               
                                </div> 
                                <div class="blog-post-title">
                                    <div class="post-name">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                    <div class="blog-info float-left">
                                        <?php $author_id= $post->post_author;?>
                                        <div class="info">
                                            <a href="<?php echo esc_url(get_edit_user_link( $author_id )); ?>"><i class="fa fa-user"></i> <?php the_author_meta( 'nickname' , $author_id ); ?></a>
                                        </div>
                                        <div class="info cat-info">
                                            <?php echo get_the_term_list($post->ID,'recipe_cat', '<i class="fa fa-briefcase"></i>', ',  ' ); ?>
                                        </div>
                                        <div class=" info"> 
                                            <i class="fa fa-comments-o"></i> <?php comments_popup_link(esc_html__('0 Comment', 'foodfarm'), esc_html__('1 Comment', 'foodfarm'), esc_html__('% Comments', 'foodfarm')); ?>
                                        </div>
                                        <div class="info tag-info">
                                            <?php echo get_the_term_list($post->ID,'recipe_tag', '<i class="fa fa-tag"></i>', ', ' ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="blog_post_desc">
                                    <?php echo wpautop(get_post_meta(get_the_ID(),'desc', true),false);?>
                                    <div class="read-more">
                                        <a href="<?php the_permalink(); ?>"><i class="fa fa-caret-right"></i><?php echo esc_html__( 'Read more', 'foodfarm' )?> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    	    </li>
    	<?php endwhile; ?>
       
    </ul>

    <div class="text-left">
        <?php foodfarm_pagination(); ?> 
    </div>
</div>