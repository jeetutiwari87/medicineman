
<?php while (have_posts()) : the_post(); ?>
    <div class="blog-content blog-ful">
        <div class="blog-item">
            <?php if (has_post_thumbnail()): ?>
                <div class="blog-img">
                <?php 
                    $attachment_id = get_post_thumbnail_id();
                    $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-list'); 
                    ?>
                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid['width']) ?>" height="<?php echo esc_attr($attachment_grid['height']) ?>" src="<?php echo esc_url($attachment_grid['src']) ?>" alt="<?php echo esc_attr($attachment_grid['alt']) ?>" /></a>
                </div>
            <?php endif;?>
                <div class="blog-post-info blog-post-bg ">
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
                            <div class="info">
                                <?php echo get_the_term_list($post->ID,'category', '<i class="fa fa-briefcase"></i>', ',  ' ); ?>
                            </div>
                            <div class=" info"> 
                                <i class="fa fa-comments"></i> <?php comments_popup_link(esc_html__('0 Comment', 'foodfarm'), esc_html__('1 Comment', 'foodfarm'), esc_html__('% Comments', 'foodfarm')); ?>
                            </div>
                            <div class="info">
                                <?php echo get_the_tag_list('<i class="fa fa-tag"></i>',', ',''); ?>
                            </div>
                        </div>
                    </div>
                    <div class="blog_post_desc">
                        <?php 
                        $foodfarm_settings = foodfarm_check_theme_options();
                        if ($foodfarm_settings['blog-excerpt-length'] != "") : ?>                            
                            <?php 
                                $content = foodfarm_get_excerpt($foodfarm_settings['blog-excerpt-length']);
                                echo wpautop( $content ); 
                            ?>
                        <?php else:?>
                            <?php
                            echo '<div class="entry-content">';
                            the_content();
                            wp_link_pages( array(
                                'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'foodfarm' ) . '</span>',
                                'after'       => '</div>',
                                'link_before' => '<span>',
                                'link_after'  => '</span>',
                                'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'foodfarm' ) . ' </span>%',
                                'separator'   => '<span class="screen-reader-text">, </span>',
                            ) );
                            echo '</div>';
                            ?>
                        <?php endif; ?>
                        
                    </div>
                </div>	
        </div>
    </div>
<?php endwhile; ?>
<div class="text-center"><?php foodfarm_pagination(); ?> </div>
