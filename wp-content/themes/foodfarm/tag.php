<?php get_header(); ?>	
<?php if($foodfarm_layout == 'fullwidth') :?>
<?php 
	$foodfarm_sidebar_pos = $foodfarm_settings['post-sidebar-position'];
	$foodfarm_sidebar = $foodfarm_settings['post-sidebar'];
?>
<div class="container">
	<div class="row">	
	<?php endif;?>		
		<div class="<?php 
		if (($foodfarm_sidebar_pos == 'left-sidebar' || $foodfarm_sidebar_pos == 'right-sidebar') 
			&& $foodfarm_sidebar && is_active_sidebar($foodfarm_sidebar)) 
				echo 'col-lg-9 col-md-9 col-sm-12 col-xs-12 main-sidebar'; 
			else 
				echo 'col-lg-12 col-md-12 col-sm-12 col-xs-12 content-main'; ?>
			<?php if ($foodfarm_sidebar_pos == 'left-sidebar' && is_active_sidebar($foodfarm_sidebar)){echo 'f-right';}?>">		
			<div id="primary" class="content-area">
	             <?php if (have_posts()): ?>                        
	                 <?php get_template_part('content', 'blog-list'); ?>
	             <?php else: ?> 
	                 <?php get_template_part('content', 'none'); ?>
	             <?php endif; ?>
			</div><!-- #primary -->
		</div>
	<?php if (($foodfarm_sidebar_pos == 'left-sidebar' || $foodfarm_sidebar_pos == 'right-sidebar')) : ?>
	    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 <?php if($foodfarm_sidebar_pos == 'left-sidebar'){echo 'left-sidebar';}?> <?php if($foodfarm_sidebar_pos == 'right-sidebar'){echo 'right-sidebar';}?>"><!-- main sidebar -->
	        <?php dynamic_sidebar($foodfarm_sidebar); ?>
	    </div>
	<?php endif; ?>
	<?php if($foodfarm_layout == 'fullwidth') :?>
	</div>
</div>
<?php endif;?>
<?php get_footer(); ?>