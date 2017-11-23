<?php
$foodfarm_settings = foodfarm_check_theme_options();
?>
<div class="footer-top">
	<div class="container">
            <?php
			if (is_active_sidebar('footer-newsletter')) {
			?> 
            <div class="newsletter-footer">
			  	<div class="newsletter-title widget-title-border text-white">
			       <h4><?php echo esc_html__('Subscribe to us!', 'foodfarm') ?></h4>
			       <p><?php echo esc_html__('Enter Your email address for our mailing list to keep yourself update.','foodfarm');?></p>
			  	</div>
			  	<?php dynamic_sidebar('footer-newsletter'); ?> 
			</div>  
			<?php
			}
			?>	 
	</div>
</div>	  	
<div class="footer-center">
		<div class="container">
			 <div class="row">
			 	<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="footer-container row">
						<?php
					        $cols = 0;
					        for ($i = 1; $i <= 4; $i++) {
					            if (is_active_sidebar('footer7-' . $i))
					                $cols++;
					        }
				        ?>
							<?php
					        if ($cols) :
					            $col_class = array();
					            switch ($cols) {
					                case 1:
					                    $col_class[1] = 'col-sm-12';
					                    break;
					                case 2:
					                    $col_class[1] = 'col-sm-6 col-xs-12 col-md-6';
					                    $col_class[2] = 'col-sm-6 col-xs-12 col-md-6';
					                    break;
					                case 3:
					                    $col_class[1] = 'col-xs-12 col-sm-4 col-md-4';
					                    $col_class[2] = 'col-xs-12 col-sm-4 col-md-4';
					                    $col_class[3] = 'col-xs-12 col-sm-4 col-md-4';
					                    break;
					                case 4:
					                    $col_class[1] = 'col-xs-12 col-sm-12 col-md-3';
					                    $col_class[2] = 'col-xs-12 col-sm-12 col-md-3';
					                    $col_class[3] = 'col-xs-12 col-sm-12 col-md-3';
					                    $col_class[4] = 'col-xs-12 col-sm-12 col-md-3';
					                    break;
					            }
					            ?>
							<div class="footer-menu-list">
								<?php
			                    $cols = 1;
			                    for ($i = 1; $i <= 4; $i++) {
			                        if (is_active_sidebar('footer7-' . $i)) {
			                            ?>
			                            <div class="<?php echo esc_attr($col_class[$cols++]) ?>">
			                                <?php dynamic_sidebar('footer7-' . $i); ?>
			                            </div>
			                            <?php
			                        }
			                    }
			                    ?>
							</div>							
							<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container">
			<div class="row">
				<?php if ($foodfarm_settings['footer7-copyright']) : ?>
				<div class="copy-right text-center">
					<address><?php echo force_balance_tags($foodfarm_settings['footer7-copyright']); ?></address>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>