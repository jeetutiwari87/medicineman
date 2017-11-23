<?php 
$config = foodfarm_check_theme_options();
?>

//SKIN
//General
$general_bg_color: <?php echo $config['general-bg']['background-color'] ?>;
$general_bg_image: url('<?php echo $config['general-bg']['background-image'] ?>');
$general_bg_repeat: <?php echo $config['general-bg']['background-repeat'] ?>;
$general_bg_position: <?php echo $config['general-bg']['background-position'] ?>;
$general_bg_size: <?php echo $config['general-bg']['background-size'] ?>;
$general_bg_attachment: <?php echo $config['general-bg']['background-attachment'] ?>;
$general_font_family: <?php echo $config['general-font']['font-family'] ?>;
$general_font_weight: <?php echo $config['general-font']['font-weight'] ?>;
$general_font_size: <?php echo $config['general-font']['font-size'] ?>;
$general_font_color: <?php echo $config['general-font']['color'] ?>;
$general_line_height: <?php echo $config['general-font']['line-height'] ?>;
$primary_color: <?php echo $config['primary-color'] ?>;
$highlight_color: <?php echo $config['highlight-color'] ?>;
//Header
$header_bg: <?php echo $config['header-bg'] ?>;
$header7_top_bg : <?php echo esc_attr($config['header7-top-bg']) ?>;
$header7_top_text_color : <?php echo esc_attr($config['header7-top-text-color']) ?>;
$header7_bg : <?php echo esc_attr($config['header7-bg']) ?>;
$header7_menu_text : <?php echo esc_attr($config['header7-menu-text']) ?>;
//Footer
$footer_bg: <?php echo $config['footer-bg'] ?>;
$footer_bg_2: <?php echo $config['footer-bg-2'] ?>;
$footer_bg_color: <?php echo $config['footer-bg'] ? $config['footer-bg'] : '#333333' ?>;
$footer_bg_image: url('<?php echo $config['footer-left-bg']['background-image'] ?>');
$footer_bg_repeat: <?php echo $config['footer-left-bg']['background-repeat'] ?>;
$footer_bg_position: <?php echo $config['footer-left-bg']['background-position'] ?>;
$footer_bg_size: <?php echo $config['footer-left-bg']['background-size'] ?>;
$footer_bg_attachment: <?php echo $config['footer-left-bg']['background-attachment'] ?>;
$footer_bg_image_6: url('<?php echo $config['footer-bg-6']['background-image'] ?>');
$footer_bg_repeat_6: <?php echo $config['footer-bg-6']['background-repeat'] ?>;
$footer_bg_position_6: <?php echo $config['footer-bg-6']['background-position'] ?>;
$footer_bg_size_6: <?php echo $config['footer-bg-6']['background-size'] ?>;
$footer_bg_attachment_6: <?php echo $config['footer-bg-6']['background-attachment'] ?>;
$footer_bg_7: <?php echo $config['footer-bg-7'] ?>;
$footer_text_7: <?php echo $config['footer-text-7'] ?>;
$footer_title_7: <?php echo $config['footer-title-7'] ?>;
$footer7_bottom_color : <?php echo $config['footer7_bottom_color'] ?>;
//Breadcrumbs 1
$breadcrumb_bg_image: url('<?php echo esc_url(str_replace(array('http:', 'https:'), '', $config['breadcrumbs-bg']['background-image'])) ?>');
$breadcrumb_bg_repeat: <?php echo $config['breadcrumbs-bg']['background-repeat'] ?>;
$breadcrumb_bg_position: <?php echo $config['breadcrumbs-bg']['background-position'] ? $config['breadcrumbs-bg']['background-position'] : 'left top' ?>;
$breadcrumb_bg_size: <?php echo $config['breadcrumbs-bg']['background-size'] ?>;
$breadcrumb_bg_attachment: <?php echo $config['breadcrumbs-bg']['background-attachment'] ?>;
//
$footer7_top_bg_image : url('<?php echo esc_url(str_replace(array('http:', 'https:'), '', $config['footer7_top_bg']['background-image'])) ?>');
$footer7_top_bg_repeat: <?php echo $config['footer7_top_bg']['background-repeat'] ?>;
$footer7_top_bg_position: <?php echo $config['footer7_top_bg']['background-position'] ? $config['breadcrumbs-bg']['background-position'] : 'left top' ?>;
$footer7_top_bg_size: <?php echo $config['footer7_top_bg']['background-size'] ?>;
$footer7_top_bg_attachment: <?php echo $config['footer7_top_bg']['background-attachment'] ?>;
//Typography
$h1_font_family: <?php echo $config['h1-font']['font-family'] ?>;
$h1_font_size: <?php echo $config['h1-font']['font-size'] ?>;
$h1_font_color: <?php echo $config['h1-font']['color'] ?>;
$h2_font_family: <?php echo $config['h2-font']['font-family'] ?>;
$h2_font_size: <?php echo $config['h2-font']['font-size'] ?>;
$h2_font_color: <?php echo $config['h2-font']['color'] ?>;
$h3_font_family: <?php echo $config['h3-font']['font-family'] ?>;
$h3_font_size: <?php echo $config['h3-font']['font-size'] ?>;
$h3_font_color: <?php echo $config['h3-font']['color'] ?>;
$h4_font_family: <?php echo $config['h4-font']['font-family'] ?>;
$h4_font_size: <?php echo $config['h4-font']['font-size'] ?>;
$h4_font_color: <?php echo $config['h4-font']['color'] ?>;
$h5_font_family: <?php echo $config['h5-font']['font-family'] ?>;
$h5_font_size: <?php echo $config['h5-font']['font-size'] ?>;
$h5_font_color: <?php echo $config['h5-font']['color'] ?>;
$h6_font_family: <?php echo $config['h6-font']['font-family'] ?>;
$h6_font_size: <?php echo $config['h6-font']['font-size'] ?>;
$h6_font_color: <?php echo $config['h6-font']['color'] ?>;