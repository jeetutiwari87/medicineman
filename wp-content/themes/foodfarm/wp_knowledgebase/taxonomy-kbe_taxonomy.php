<?php
    get_header('knowledgebase');
    
    $kke_bred_class = '';
    $current_page = isset($_GET['pager']) && !empty($_GET['pager']) ? intval($_GET['pager']) : 1;
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
    
    // Query for Category
    $kbe_cat_slug = get_queried_object()->slug;
    $kbe_cat_name = get_queried_object()->name;
    
    $kbe_tax_post_args = array(
        'post_type' => KBE_POST_TYPE,
        'posts_per_page' => 999,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => KBE_POST_TAXONOMY,
                'field' => 'slug',
                'terms' => $kbe_cat_slug
            )
        )
    );
    $kbe_tax_post_qry = new WP_Query($kbe_tax_post_args);
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
        <div id="primary" class="content-area">
            <!--leftcol--> 
            <div class="knowledge-list">
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
                <!--<articles>-->
                <div class="padding-top-30">
                    

                    <ul>
                <?php
                    if($kbe_tax_post_qry->have_posts()) :
                        while($kbe_tax_post_qry->have_posts()) :
                            $kbe_tax_post_qry->the_post();
                ?>
                        <li class="blog-content recipes-list-container">
                            <div class="blog-item">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                        <?php if (has_post_thumbnail()): ?>
                                            <div class="img-recipes bg-hover">
                                                <?php
                                                $attachment_id = get_post_thumbnail_id();
                                                $attachment_grid = foodfarm_get_attachment($attachment_id, 'foodfarm-knowledge-list');
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
                                            <div class="blog_post_desc">
                                                <?php echo wpautop(kbe_short_content(280)); ?>
                                                <div class="read-more">
                                                    <a href="<?php the_permalink(); ?>"><i class="fa fa-caret-right"></i><?php echo esc_html__( 'Read more', 'foodfarm' )?> </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>

                            </div>
                        </li>
                <?php
                        endwhile;
                    endif;
                ?>
                    </ul>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-left no-padding">
                        <?php if ($wp_query->max_num_pages > 1) : ?>
                            <div class="load-more text-center">
                                <a data-paged="<?php echo esc_attr($current_page); ?>" href="<?php echo esc_url(foodfarm_get_current_url()); ?>" data-type="knowledgebase" data-totalpage="<?php echo esc_attr($kbe_tax_post_qry->max_num_pages); ?>" id="knowledgebase-loadmore" class="btn btn-default btn-icon"><?php echo esc_html__('View More', 'foodfarm') ?> <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <!--/leftcol-->
        </div>

    </div>
    <!--/content-->
    
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
<?php
    get_footer('knowledgebase');
?>