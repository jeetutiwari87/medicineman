<div class="blog single-post">
    <div class="blog-content blog-ful">
        <div class="blog-item">
            <?php if (has_post_thumbnail()): ?>
                <div class="blog-img blog-single-img">
                    <?php 
                    $attachment_id = get_post_thumbnail_id();
                    $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-blog-list'); 
                    ?>
                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid['width']) ?>" height="<?php echo esc_attr($attachment_grid['height']) ?>" src="<?php echo esc_url($attachment_grid['src']) ?>" alt="<?php echo esc_attr($attachment_grid['alt']) ?>" /></a>
                </div>
            <?php endif;?>
            <?php if($post->post_content != ""): ?>
                <div class="blog-post-info blog-post-bg ">
                    <div class="blog-top-content">
                        <div class="blog-date">
                            <p class="date"><?php echo get_the_time('d'); ?></p>
                            <p class="month"><?php echo get_the_time('M'); ?></p>                           
                        </div> 
                        <div class="blog-post-title">
                            <div class="post-name">
                                <?php the_title(); ?>
                            </div>
                            <div class="blog-info float-left">
                                <?php $author_id= $post->post_author;?>
                                <div class="info">
                                    <a href="<?php echo esc_url(get_edit_user_link( $author_id )); ?>"><i class="fa fa-user"></i> <?php echo the_author_meta( 'nickname' , $author_id ); ?></a>
                                </div>
                                <div class="info">
                                    <?php echo get_the_term_list($post->ID,'category', '<i class="fa fa-briefcase"></i>', ', ' ); ?>
                                </div>
                                <div class=" info"> 
                                    <i class="fa fa-comment"></i> <?php comments_popup_link(esc_html__('0 Comment', 'foodfarm'), esc_html__('1 Comment', 'foodfarm'), esc_html__('% Comments', 'foodfarm')); ?>
                                </div>
                                <div class="info">
                                    <?php echo get_the_tag_list('<a><i class="fa fa-tag"></i>',', ','</a>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="blog_post_desc">
                        <?php 
                        the_content();
      					 ?>
                    </div>
                </div>	
            <?php endif;?>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 share-links ">
        <div class="addthis_sharing_toolbox">
            <p class="small-text"><?php echo esc_html__( 'Share', 'foodfarm' )?></p>
            <div class="f-social">
                <ul>

                    <li><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li>
                        <a href="https://plus.google.com/share?url=<?php echo urlencode(get_the_permalink()); ?>" target="_blank">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    <li><a href="https://twitter.com/share?url=<?php echo urlencode(get_the_permalink()); ?>&amp;text=<?php echo urlencode(get_the_title()); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="http://www.linkedin.com/shareArticle?url=<?php echo urlencode(get_the_permalink()); ?>&amp;title=<?php echo urlencode(get_the_title()); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
        <?php comments_template('', true); ?>  
    </div>
</div>
