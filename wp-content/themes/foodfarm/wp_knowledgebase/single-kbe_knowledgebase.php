<?php
    get_header('knowledgebase');
    
    $kke_bred_class = '';
    // Classes For main content div
    if(KBE_SIDEBAR_HOME == 0) {
        $kbe_content_class = 'content-main main-sidebar';
    } elseif(KBE_SIDEBAR_HOME == 1) {
        $kbe_content_class = 'col-lg-9 col-md-12 col-sm-12 col-xs-12 main-sidebar f-right';
        $kke_bred_class =   ' col-lg-12 col-md-12 col-sm-12 col-xs-12';
    } elseif(KBE_SIDEBAR_HOME == 2) {
        $kbe_content_class = 'col-lg-9 col-md-12 col-sm-12 col-xs-12 main-sidebar';
        $kke_bred_class =   ' col-lg-12 col-md-12 col-sm-12 col-xs-12';
    }
    
    // Classes For sidebar div
    if(KBE_SIDEBAR_HOME == 0) {
        $kbe_sidebar_class = 'kbe_aside_none';
    } elseif(KBE_SIDEBAR_HOME == 1) {
        $kbe_sidebar_class = 'col-lg-3 col-md-12 col-sm-12 col-xs-12 right-sidebar';
    } elseif(KBE_SIDEBAR_HOME == 2) {
        $kbe_sidebar_class = 'col-lg-3 col-md-12 col-sm-12 col-xs-12 right-sidebar';
    }
?>
<div class="container">
   
    <div class="row">
                <!--Breadcrum-->
        <?php
            if(KBE_BREADCRUMBS_SETTING == 1){
        ?>
            <div class="kbe_breadcrum <?php echo esc_attr($kke_bred_class); ?>">
                <?php echo kbe_breadcrumbs(); ?>
            </div>
        <?php
            }
        ?>
        <!--/Breadcrum-->
            
        <!--content-->
        <div class="<?php echo esc_attr($kbe_content_class); ?>">
            <!--Content Body-->
            <div id="primary" class="content-area">
                <div class="blog single-post">
                    <div class="blog-content blog-ful" >
                        <!--search field-->
                        <?php
                            if(KBE_SEARCH_SETTING == 1){
                            ?>
                            <aside class="widget widget_search" id="search-3">
                                <h2 class="widget-title no-border title-search">
                                    <?php echo '<span>'.esc_html__('Search the ', 'foodfarm').'</span>'.esc_html__('Knowledge Base', 'foodfarm');?>
                                </h2>
                                <?php
                                foodfarm_kke_search_form();
                                    }
                                ?>
                            </aside>
                        
                        <!--/search field-->
                        <div class="blog-item">
                        <?php
                        while(have_posts()) :
                            the_post();

                            //  Never ever delete it !!!
                            kbe_set_post_views(get_the_ID());
                            ?>
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
                                            <a href="<?php echo esc_url(get_edit_user_link( $author_id )); ?>"><i class="fa fa-user"></i> <?php echo the_author_meta( 'nickname' , $author_id ); ?></a>
                                        </div>
                                        <div class="info cat-info">
                                            <?php echo get_the_term_list($post->ID,'kbe_taxonomy', '<i class="fa fa-briefcase"></i>', ',  ' ); ?>
                                        </div>
                                        <div class=" info"> 
                                            <i class="fa fa-comment"></i> <?php comments_popup_link(esc_html__('0 Comment', 'foodfarm'), esc_html__('1 Comment', 'foodfarm'), esc_html__('% Comments', 'foodfarm')); ?>
                                        </div>
                                        <div class="info tag-info">
                                            <?php echo get_the_term_list($post->ID,'kbe_tags', '<i class="fa fa-tag"></i>', ', ' ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (has_post_thumbnail()):?>
                                <div class="blog-img blog-single-img">                               
                                    <?php 
                                    $attachment_id = get_post_thumbnail_id();
                                    $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-recipe-single'); 
                                    ?>
                                    <a href="<?php the_permalink(); ?>"><img class="img-responsive" width="<?php echo esc_attr($attachment_grid['width']) ?>" height="<?php echo esc_attr($attachment_grid['height']) ?>" src="<?php echo esc_url($attachment_grid['src']) ?>" alt="<?php echo esc_attr($attachment_grid['alt']) ?>" /></a>
                                </div>
                            <?php endif;?>
                            <div class="blog-post-info blog-post-bg">
                                <div class="blog_post_desc">
                                    <?php the_content(); ?>                                    
                                </div>
                            </div>
                            <?php 
                                if(KBE_COMMENT_SETTING == 1){
                            ?>  
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="comments-area">
                                <?php comments_template('', true); ?>  
                                </div> 
                            </div>
                            <?php
                            }
                        endwhile;

                        //  Never ever delete it !!!
                        kbe_get_post_views(get_the_ID());
                        ?>
                        </div><!--/Blog item -->
                    </div>
                </div>
            </div>
            <!--/Content Body-->

        </div>
    	
        <!--aside-->
        <div class="kbe_aside <?php echo esc_attr($kbe_sidebar_class); ?>">
        <?php
            if((KBE_SIDEBAR_INNER == 2) || (KBE_SIDEBAR_INNER == 1)){
                dynamic_sidebar('kbe_cat_widget');
            }
        ?>
        </div>
        <!--/aside-->
    </div>    
</div>
<?php get_footer('knowledgebase'); ?>