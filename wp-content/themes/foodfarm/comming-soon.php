<?php $foodfarm_settings = foodfarm_check_theme_options(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php if (!empty($foodfarm_settings['favicon'])): ?>
        <link rel="shortcut icon" href="<?php echo esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['favicon']['url'])); ?>" type="image/x-icon" />
    <?php endif; ?>
    <?php wp_head(); ?>
</head>
<body class="home">
<div id="primary" class="site-content">
    <div id="content" role="main">
    	<div class="page-coming-soon text-center has-overlay" style="background: url(
             <?php
                if ($foodfarm_settings['under-bg-image'] && $foodfarm_settings['under-bg-image']['url']):
                    echo esc_url($foodfarm_settings['under-bg-image']['url']);    
                endif;
            ?>
	        ); background-size: cover; background-position: 50% 50%;">
			<div class="container">
				<div class="row">  
					<div class="col-md-12 col-sm-12 col-xs-12 coming-soon-container">
						<div class="logo">
							<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
								<?php
			                        if ($foodfarm_settings['logo'] && $foodfarm_settings['logo']['url']):
			                            echo '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
			                        else:
			                            bloginfo('name');
			                        endif;
			                    ?>
							</a>
						</div>
						<div class="coming-soon">
							<div class="coming-sub">
								<?php if(isset($foodfarm_settings['under-contr-content']) && $foodfarm_settings['under-contr-content'] != ''):?>
									<?php echo wpautop(html_entity_decode($foodfarm_settings['under-contr-content']));?>

								<?php else:?>
									<h1><?php echo esc_html__('coming soon','foodfarm');?></h1>
									<h2><?php echo esc_html__('We are working ','foodfarm').'<span>'.esc_html__("hard!", "foodfarm").'</span>';?></h2>
									<p><?php echo esc_html__("We work to improve our website and make it look fresher! But we'll be back to soon",'foodfarm');?></p>
								<?php endif;?>								
							</div>
							<?php if($foodfarm_settings['under-display-countdown'] == 1):?>
								<?php if(isset($foodfarm_settings['under-end-date']) && $foodfarm_settings['under-end-date'] != ''):?>
								<div class="time-coundown">
									<div id="DateCountdown" data-date="<?php echo esc_attr($foodfarm_settings['under-end-date']);?> 00:00:00"></div>
								</div>
								<?php endif;?>
							<?php endif;?>
							<?php if($foodfarm_settings['under-mail'] == 1):?>
								<?php
									if( function_exists( 'mc4wp_show_form' ) ) {
									    mc4wp_show_form();
									}
								?>
							<?php endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div><!-- #content -->
</div><!-- #primary -->
</body>
<?php wp_footer(); ?>
</html>
