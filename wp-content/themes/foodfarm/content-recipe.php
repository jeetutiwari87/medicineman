<div class="recipes-single">
    <div class="blog-content blog-ful">
        <div class="blog-item">
            <?php if (has_post_thumbnail()): ?>
                <div class="blog-img blog-single-img">
                    <?php 
                    $attachment_id = get_post_thumbnail_id();
                    $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-recipe-single'); 
                    ?>
                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid['width']) ?>" height="<?php echo esc_attr($attachment_grid['height']) ?>" src="<?php echo esc_url($attachment_grid['src']) ?>" alt="<?php echo esc_attr($attachment_grid['alt']) ?>" /></a>
                </div>
            <?php endif;?>
                <div class="blog-post-info blog-post-bg ">
                    <div class="blog-top-content">
                        <div class="blog-date">
                            <p class="date"><?php echo get_the_time('d'); ?></p>
                            <p class="month"><?php echo get_the_time('M'); ?></p>                           
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
                                    <i class="fa fa-comment"></i> <?php comments_popup_link(esc_html__('0 Comment', 'foodfarm'), esc_html__('1 Comment', 'foodfarm'), esc_html__('% Comments', 'foodfarm')); ?>
                                </div>
                                <div class="info tag-info">
                                    <?php echo get_the_term_list($post->ID,'recipe_tag', '<i class="fa fa-tag"></i>', ', ' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="blog_post_desc">
                        <div class="recipes-details-desc">
                            <div class="recipes-content-desc">
                                <div class="ingredients-container">
                                    <ul>
                                        <li class="recipes-prep">
                                            <div class="icon">
                                                <i class="pe-7s-stopwatch"></i>
                                            </div>
                                            <div class="name-time-recipes">
                                                <p class="name-recipes"><?php echo esc_html__('Prep','foodfarm')?></p>
                                                <p class="time-recipes"> <?php echo sprintf(esc_html('%s', 'foodfarm'), get_post_meta(get_the_ID(),'prep', true));?></p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="name-time-recipes">
                                                <p class="name-recipes"><?php echo esc_html__('Cook','foodfarm')?></p>
                                                <p class="time-recipes"> <?php echo sprintf(esc_html('%s', 'foodfarm'), get_post_meta(get_the_ID(),'cook', true));?></p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="name-time-recipes">
                                                <p class="name-recipes"><?php echo esc_html__('Ready In','foodfarm')?></p>
                                                <p class="time-recipes"><?php echo sprintf(esc_html('%s', 'foodfarm'), get_post_meta(get_the_ID(),'ready', true));?></p>
                                            </div>
                                        </li>
                                        <li class="recipes-servings">
                                <div class="icon">
                                                    <i class="pe-7s-graph"></i>
                                                </div>
                                            <div class="name-time-recipes">
                                                <p class="name-recipes"><?php echo sprintf(esc_html('%s', 'foodfarm'), get_post_meta(get_the_ID(),'serving', true)).esc_html__(' servings', 'foodfarm');?> </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                            </div>
                            <div class="title-desc">    
                                <h4><?php echo esc_html__('Ingredients', 'foodfarm')?></h4>
                            </div>
                        </div>
                        <div class="recipes-details-desc recipes-directions">
                            <div class="recipes-content-desc">
                                <?php echo wp_kses(get_post_meta(get_the_ID(),'ingre', true),array(
                                    'ul' => array(),
                                    'li' => array(),
                                    'div' => array('class'=> array()))
                                    );?>
                            </div>
                            <div class="title-desc">    
                                <h4><?php echo esc_html__('Directions', 'foodfarm')?> </h4>
                            </div>
                        </div>
                        <div class="recipes-details-desc recipes-pragraph">
                            <div class="recipes-content-desc">  
                                <div class="list-text-recipes">
                                    <?php 
                                        the_content();
                                    ?> 
                                </div>                            
                            </div>
                        </div>
                        <div class="recipes-details-desc recipes-footnote">
                            <div class="recipes-content-desc footnote">
                                <p class="footnote-title"><?php echo esc_html__('Footnotes', 'foodfarm');?></p>
                                <ul class="footnote-content">
                                    <li>
                                        <p class="footnote-title-small"><?php echo esc_html__("Cook's Note:", "foodfarm");?></p>
                                        <p class="desc"><?php echo get_post_meta(get_the_ID(),'cook_note', true);?>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="footnote-title-small"><?php echo esc_html__("Editor's Note:", "foodfarm");?></p>
                                        <p class="desc"><?php echo get_post_meta(get_the_ID(),'editor_note', true);?>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>	
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