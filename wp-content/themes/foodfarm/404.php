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
		        <div class="page-404 text-center" style="background: url(
             <?php
                if ($foodfarm_settings['404-bg-image'] && $foodfarm_settings['404-bg-image']['url']):
                    echo esc_url($foodfarm_settings['404-bg-image']['url']);
                else:
                    
                endif;
            ?>

        ); background-size: cover; background-position: bottom center;">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 col-sm-12 no-padding col-xs-12 page-404-container">
						<div class="logo">
							<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
								<?php
			                        if ($foodfarm_settings['logo-404'] && $foodfarm_settings['logo-404']['url']):
			                            echo '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['logo-404']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
			                        else:
			                            bloginfo('name');
			                        endif;
			                    ?>
							</a>
						</div>
						<div class="content-404">
							<div class="content-desc">
								<h1><?php echo esc_html__('404', 'foodfarm');?></h1>
								<h3><?php echo esc_html__('Sorry, this page is not available', 'foodfarm');?></h3>
								<p><?php echo esc_html__('The page you are looking for was moved, removed, renamed or might never existed.', 'foodfarm');?></p>
								<div class="button-404">
									<a class="btn btn-default button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__('Back to homepage', 'foodfarm');?></a>
									<a class="btn btn-default button btn-default-2 " href="<?php echo esc_url( get_permalink( get_page_by_title( 'Contact Us' ) ) ); ?>"><?php echo esc_html__('Contact Us', 'foodfarm');?></a>
								</div>
							</div>
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
