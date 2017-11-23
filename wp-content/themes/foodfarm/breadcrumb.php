<?php
$foodfarm_settings = foodfarm_check_theme_options();
$header_type = foodfarm_get_header_type();
$breadcrumbs = $foodfarm_settings['show-breadcrumbs'];
if(!foodfarm_get_meta_value('breadcrumbs',true)){
     $breadcrumbs = foodfarm_get_meta_value('breadcrumbs',true);
}
$page_title = $foodfarm_settings['show-pagetitle'];
if(!foodfarm_get_meta_value('page_title',true)){
     $page_title = foodfarm_get_meta_value('page_title',true);
}
if (( is_front_page() && is_home()) || is_front_page() ) {
    $breadcrumbs = false;
    $page_title = false;
}
?>
<?php if ($breadcrumbs || $page_title) : ?>
<div class="side-breadcrumb text-center <?php if($header_type == '2' || $header_type == '6'){echo 'side-breadcrumb2';}?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">  
                <?php if($page_title) :?>
                    <div class="page-title"><h1><?php foodfarm_page_title(); ?></h1></div>
                <?php endif;?>
                <?php if ($breadcrumbs) : ?>
                    <?php foodfarm_breadcrumbs(); ?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>