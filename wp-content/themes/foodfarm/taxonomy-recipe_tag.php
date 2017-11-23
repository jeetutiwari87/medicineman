<?php get_header(); ?>
<?php
$main_class =                   "";
if ($foodfarm_sidebar_pos == 'left-sidebar' && is_active_sidebar($foodfarm_sidebar)){
	$main_class .= 'f-right';
}
?>
<div class="recipes-list">
	<?php if($foodfarm_layout == 'fullwidth') :?>
	<div class="container">
		<div class="row">	
		<?php endif;?>		
			<div class="<?php 
				if (($foodfarm_sidebar_pos == 'left-sidebar' || $foodfarm_sidebar_pos == 'right-sidebar') && $foodfarm_sidebar && is_active_sidebar($foodfarm_sidebar)) echo 'col-lg-9 col-md-12 col-sm-12 col-xs-12 main-sidebar'; else echo 'content-main'; ?> <?php echo esc_attr($main_class); ?>">		
				<div id="primary" class="content-area">
		             <?php if (have_posts()): ?>                        
		                 <?php get_template_part('content', 'archive-recipe'); ?>
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
</div>
<?php get_footer(); ?>