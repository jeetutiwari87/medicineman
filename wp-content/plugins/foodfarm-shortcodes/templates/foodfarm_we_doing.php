
<?php
$output = $type = $icon_fontawesome  = $title = $description  = $link = $el_class = '';
extract(shortcode_atts(array(
    'title' => '',
    'description' => '',
    'icon_type' => '',
    'icon_linecons' => '',
    'icon_fontawesome' => '',
    'icon_openiconic' => '',
    'icon_typicons' => '',
    'icon_pestrokefont' => '',
    'icon_foodfarm' => '',
    'icon_entypo' => '',
    'el_class' => '',
    'link' => '#'
                ), $atts));
$icon_class = "";
if (!empty($icon_foodfarm)) {
    $icon_class = $icon_foodfarm;
} elseif (!empty($icon_pestrokefont)) {
    $icon_class = $icon_pestrokefont;
} elseif (!empty($icon_fontawesome)) {
    $icon_class = $icon_fontawesome;
} elseif (!empty($icon_openiconic)) {
    $icon_class = $icon_openiconic;
} elseif (!empty($icon_typicons)) {
    $icon_class = $icon_typicons;
} elseif (!empty($icon_entypo)) {
    $icon_class = $icon_entypo;
} elseif (!empty($icon_linecons)) {
    $icon_class = $icon_linecons;
}
$style="";
if($icon_type == 'foodfarmfont') {
    wp_enqueue_style( 'foodfarmfont' );
} else {
    vc_icon_element_fonts_enqueue($icon_type);
}
$href = vc_build_link($link);
$href['url'] = $href['url'] !=''? $href['url'] : '#';
$el_class = foodfarm_shortcode_extract_class($el_class);
ob_start();
?>  
<?php 
    $output = '<div class="we-doing"';    
    $output .= '>';
    ?>
    <div class="we-doing-content">
        <div class="we_doing_icon">
            <i class="<?php echo $icon_class; ?>"></i> 
        </div>
        <a href="<?php echo $href['url']; ?>"><h4><?php echo $title; ?></h4></a>
        <p><?php echo $description;?></p>
    </div>
<?php
$output .= ob_get_clean();
$output .= '</div>' . foodfarm_shortcode_end_block_comment('foodfarm_we_doing') . "\n";

echo $output;


wp_reset_postdata(); ?>
