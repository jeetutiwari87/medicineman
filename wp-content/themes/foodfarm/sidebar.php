<?php
    $foodfarm_settings = foodfarm_check_theme_options();
    $foodfarm_sidebar_pos = foodfarm_get_sidebar_position();
    $foodfarm_sidebar = foodfarm_get_sidebar();
?>

<?php if (($foodfarm_sidebar_pos == 'left-sidebar' || $foodfarm_sidebar_pos == 'right-sidebar')) : ?>
	<?php if(get_post_type() == 'recipe' || get_post_type() == 'pressmedia'):?>
		<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 <?php if($foodfarm_sidebar_pos == 'left-sidebar'){echo 'left-sidebar';}?> <?php if($foodfarm_sidebar_pos == 'right-sidebar'){echo 'right-sidebar';}?>">
	<?php else:?>
    	<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 <?php if($foodfarm_sidebar_pos == 'left-sidebar'){echo 'left-sidebar';}?> <?php if($foodfarm_sidebar_pos == 'right-sidebar'){echo 'right-sidebar';}?>"><!-- main sidebar -->
    <?php endif;?>
        <?php dynamic_sidebar($foodfarm_sidebar); ?>
    </div>
<?php endif; ?>





