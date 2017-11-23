<?php
$foodfarm_settings = foodfarm_check_theme_options();
?>
	  	
<div class="footer-center">
		<div class="container">
			 <div class="row">
			 	<div class="col-md-12 col-sm-12 col-xs-12">
		            <?php
					if (is_active_sidebar('footer-newsletter') && isset($foodfarm_settings['show-newsletter']) && $foodfarm_settings['show-newsletter']) {
					?> 
					<div class="newsletter-wrap">
			            <div class="newsletter-footer">
			            	<?php if(isset($foodfarm_settings['footer9-newletter_title']) && $foodfarm_settings['footer9-newletter_title'] !=''):?>
			            	<div class="newsletter-title widget-title-border text-white">
						       <h4><?php echo esc_html($foodfarm_settings['footer9-newletter_title']); ?></h4>
						  	</div>
						  	<?php endif;?>
						  	<?php dynamic_sidebar('footer-newsletter'); ?> 
						</div>  
					</div>
					<?php
					}
					?>	 		 	
					<div class="footer-container row">
						<?php
					        $cols = 0;
					        for ($i = 1; $i <= 4; $i++) {
					            if (is_active_sidebar('footer9-' . $i))
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
			                        if (is_active_sidebar('footer9-' . $i)) {
			                            ?>
			                            <div class="<?php echo esc_attr($col_class[$cols++]) ?>">
			                            	<?php if($i != 1): ?>
			                            		<div class="footer_pad_left">
			                            	<?php endif;?>
			                                <?php dynamic_sidebar('footer9-' . $i); ?>
			                            	<?php if($i !=1): ?>
			                            		</div>
			                            	<?php endif;?>			                                
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
				<?php if ($foodfarm_settings['footer-copyright']) : ?>
				<div class="col-md-6 col-sm-6 col-xs-12 copy-right">
					<address><?php echo force_balance_tags($foodfarm_settings['footer-copyright']); ?></address>
				</div>
				<?php endif;?>
				<?php if ($foodfarm_settings['show-payment']) : ?>
				<div class="col-md-6 col-sm-6 col-xs-12 f-float payment_footer">
					<div class="payment">
						<ul>
							<?php if (!empty($foodfarm_settings['link-visa'])): ?>
								<li><a href="<?php echo esc_url($foodfarm_settings['link-visa']); ?>"><i class="fa fa-cc-visa"></i></a></li>
							<?php endif; ?>
							<?php if (!empty($foodfarm_settings['link-paypal'])): ?>
								<li><a href="<?php echo esc_url($foodfarm_settings['link-paypal']); ?>"><i class="fa fa-cc-paypal"></i></a></li>
							<?php endif; ?>
							<?php if (!empty($foodfarm_settings['link-mastercard'])): ?>
								<li><a href="<?php echo esc_url($foodfarm_settings['link-mastercard']); ?>"><i class="fa fa-cc-mastercard"></i></a></li>
							<?php endif; ?>
							<?php if (!empty($foodfarm_settings['link-discover'])): ?>
								<li><a href="<?php echo esc_url($foodfarm_settings['link-discover']); ?>"><i class="fa fa-cc-discover"></i></a></li>
							<?php endif; ?>
							<?php if (!empty($foodfarm_settings['link-amex'])): ?>
								<li><a href="<?php echo esc_url($foodfarm_settings['link-amex']); ?>"><i class="fa fa-cc-amex"></i></a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>