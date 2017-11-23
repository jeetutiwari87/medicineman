<?php
$foodfarm_settings = foodfarm_check_theme_options();
?>
<div class="footer-top">
	<div class="container">
			<div class="left_logo_footer">
				<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
				<?php
	            if ($foodfarm_settings['logo-footer-6'] && $foodfarm_settings['logo-footer-6']['url']):
	                echo '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo-footer-6']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
	            else:
	                bloginfo('name');
	            endif;
	            ?>		
	       		</a>
            </div>	
            <?php
			if (is_active_sidebar('footer-newsletter')) {
			?> 
            <div class="newsletter-footer">
			  	<div class="newsletter-title widget-title-border text-white">
			       <h4><?php echo esc_html__('Signup Newsletter', 'foodfarm') ?></h4>
			  	</div>
			  	<?php dynamic_sidebar('footer-newsletter'); ?> 
			</div>  
			<div class="dib footer-social hidden-xs hidden-sm">
                    <ul>
                    	<?php if (!empty($foodfarm_settings['social-facebook'])): ?>
                            <li><a href="<?php echo esc_url($foodfarm_settings['social-facebook']) ?>" data-toggle="tooltip" title="facebook"><i class="fa fa-facebook"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($foodfarm_settings['social-twitter'])): ?>
                            <li><a href="<?php echo esc_url($foodfarm_settings['social-twitter']) ?>" data-toggle="tooltip" title="twitter"><i class="fa fa-twitter"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($foodfarm_settings['social-google'])): ?>
                            <li><a href="<?php echo esc_url($foodfarm_settings['social-google']) ?>" data-toggle="tooltip" title="google plus"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($foodfarm_settings['social-instagram'])): ?>
                            <li><a href="<?php echo esc_url($foodfarm_settings['social-instagram']) ?>" data-toggle="tooltip" title="instagram"><i class="fa fa-instagram"></i></a></li>
                        <?php endif; ?>
                    </ul>
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
					            if (is_active_sidebar('footer-bakery-' . $i))
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
					                    $col_class[2] = 'col-xs-12 col-sm-4 col-md-3';
					                    $col_class[3] = 'col-xs-12 col-sm-4 col-md-3';
					                    $col_class[4] = 'col-xs-12 col-sm-4 col-md-3';
					                    break;
					            }
					            ?>
							<div class="footer-menu-list">
								<?php
			                    $cols = 1;
			                    for ($i = 1; $i <= 4; $i++) {
			                        if (is_active_sidebar('footer-bakery-' . $i)) {
			                            ?>
			                            <div class="<?php echo esc_attr($col_class[$cols++]) ?>">
			                                <?php dynamic_sidebar('footer-bakery-' . $i); ?>
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