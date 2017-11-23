<?php get_header(); ?>  
<?php if($foodfarm_layout == 'fullwidth') :?>
<div class="container">
    <div class="row">   
    <?php endif;?>  
    <div class="<?php if (($foodfarm_sidebar_pos == 'left-sidebar' || $foodfarm_sidebar_pos == 'right-sidebar') && $foodfarm_sidebar && is_active_sidebar($foodfarm_sidebar)) echo 'col-lg-9 col-md-12 col-sm-12 col-xs-12 main-sidebar'; else echo 'col-md-12 col-sm-12 col-xs-12 content-primary'; ?>
        <?php if ($foodfarm_sidebar_pos == 'left-sidebar' && is_active_sidebar($foodfarm_sidebar)){echo 'f-right';}?>">       
        <div id="primary" class="content-area">
            <?php if ( have_posts() ) :?>  
                <div class="search-content">
                    <?php while ( have_posts() ) : the_post(); ?>
                            <?php get_template_part('content', 'search-result'); ?>
                    <?php endwhile; ?>  
                </div>   
                <div class="text-center"><?php foodfarm_pagination(); ?> </div>                                 
            <?php else: ?> 
                <article id="post-0" class="post no-results not-found">
                    <div class="container">
                        <header class="entry-header">
                            <h1 class="entry-title"><?php echo esc_html__('Nothing Found', 'foodfarm'); ?></h1>
                        </header>
                        <div class="row">
                            <div class="entry-content">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <p><?php echo esc_html__('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'foodfarm'); ?></p>
                                    <div class="widget widget_search">
                                    <?php get_search_form(); ?>
                                    </div>
                                </div>
                            </div><!-- .entry-content -->
                        </div>
                    </div>
                </article><!-- #post-0 -->
            <?php endif; ?>
        </div>
    </div>
    <?php get_sidebar() ?>
<?php if($foodfarm_layout == 'fullwidth') :?>
    </div>
</div>
<?php endif;?>    
<?php get_footer(); ?>