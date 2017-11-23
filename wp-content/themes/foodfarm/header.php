<?php $foodfarm_settings = foodfarm_check_theme_options(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="google-site-verification" content="msv0f6cFnGXLCi8A4AOGAtwuKDhF0s3KjhWor-DD6jU" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) :?>
        <?php if (!empty($foodfarm_settings['favicon'])): ?>
            <link rel="shortcut icon" href="<?php echo esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['favicon']['url'])); ?>" type="image/x-icon" />
        <?php endif; ?>
    <?php endif;?>    

    <?php wp_head(); ?>
</head>
<?php
$foodfarm_sidebar_pos = foodfarm_get_sidebar_position();
$foodfarm_sidebar = foodfarm_get_sidebar();
$foodfarm_layout = foodfarm_get_layout();
$header_type = foodfarm_get_header_type();
$header_top = (isset($foodfarm_settings['header-top']) && $foodfarm_settings['header-top']) ? '':'hide-headertop';
?>
<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
        <?php if (foodfarm_get_meta_value('show_header', true)) : ?>
            <header id="masthead" class="site-header header-v<?php echo esc_attr($header_type); ?> <?php echo esc_attr($header_top); ?>">
                <?php get_template_part('headers/header_' . $header_type); ?>
            </header><!-- #masthead -->
        <?php endif; ?>
        <?php get_template_part('breadcrumb'); ?>
        <?php foodfarm_get_revolution_slider() ?>
        <div id="main" class="wrapper <?php if($foodfarm_layout == 'fullwidth'){echo 'boxed';}?>">