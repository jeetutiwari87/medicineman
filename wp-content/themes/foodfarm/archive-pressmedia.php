<?php get_header(); ?>	
<?php if($foodfarm_layout == 'fullwidth') :?>
<div class="container">
	<div class="row">	
	<?php endif;?>	
		<div class="<?php if (($foodfarm_sidebar_pos == 'left-sidebar' || $foodfarm_sidebar_pos == 'right-sidebar') && $foodfarm_sidebar && is_active_sidebar($foodfarm_sidebar)) echo 'col-lg-9 col-md-12 col-sm-12 col-xs-12 main-sidebar'; else echo 'content-main'; ?> <?php if ($foodfarm_sidebar_pos == 'left-sidebar' && is_active_sidebar($foodfarm_sidebar)){echo 'f-right';}?>">	
			<div id="primary" class="content-area">
	             <?php if (have_posts()): ?>    
	             		<?php get_template_part('content', 'press-media'); ?> 
	             <?php else: ?> 
	                 <?php get_template_part('content', 'none'); ?>
	             <?php endif; ?>
			</div><!-- #primary -->
		</div>
	<?php get_sidebar() ?>
<?php if($foodfarm_layout == 'fullwidth') :?>
	</div>
</div>
<?php endif;?>
<?php get_footer(); ?>