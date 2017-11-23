<?php
$output  = $number = $first_title = $last_title = $description = $sale = $item_delay =  $el_class = '';
extract(shortcode_atts(array(
    'first_title' =>'',
    'last_title' => '',
    'icon_title' => 'icon_1',
    'description' => '',
    'number' => 8,
    'sale'=>'30',
    'post_display_type' => '',
    'columns' => 2,
    'el_class' => ''
), $atts));
$today_menu_entry_col = '';

switch ( $columns ) {
    case '3':
        $today_menu_entry_col = 'col-lg-4 col-md-6 col-sm-12 col-xs-12';
        break;
    case '2':
        $today_menu_entry_col = 'col-lg-6 col-sm-12 col-md-6 col-xs-12';
        break;
    default:
        $today_menu_entry_col = 'col-lg-3 col-md-6 col-sm-12 col-xs-12';
        break;
}
$args = array(
    'post_type' => 'productlist',
);
query_posts($args);
global $wp_query;
$output = '<div class="today-menu-container"';        
$output .= '>';
ob_start();
$count_item = 0.2; 
    $animation_delay = '';
    if($item_delay) {
        $animation_delay = ' data-sr="wait '. $count_item .'s"';  
    }
    $count_item += 0.2;
$blog = new WP_Query($args);
?>
<div class="row"> 
    <?php if($first_title || $last_title) :?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div <?php echo $animation_delay; ?> class="entry-title title-icon text-center <?php if($icon_title == 'icon_2'){echo 'title-icon2';} ?>">
                <h2 class="text-white"><?php echo esc_html($first_title);?> <span><?php echo esc_html($last_title);?></span></h2>
                <div class="icon-title"><span class="icon-<?php if($icon_title == 'icon_1'){echo '8';}else{echo '7';}?>"></span></div>
                <p class="align_center text-white"><?php echo wp_kses( $description, array('br' => array()));?></p>
            </div>
        </div>
    <?php endif;?>
    <div class="col-md-12 col-sm-12 col-xs-12">
     <div class="today-menu">
        <?php while ($blog->have_posts()) : $blog->the_post(); ?>
         <div class="<?php echo $today_menu_entry_col;?> item-grid">
            <div class="today-menu-item">
                <div class="img-item hover-link">
                    <a class="bg-hover" href="<?php echo get_post_meta(get_the_ID(),'link_product',true);?>">
                        <?php if(has_post_thumbnail()):
                            $attachment_img = foodfarm_get_attachment(get_post_thumbnail_id());
                        ?>
                            <img width="<?php echo esc_attr($attachment_img['width']) ?>" height="<?php echo esc_attr($attachment_img['height']) ?>" src="<?php echo esc_url($attachment_img['src']) ?>" alt="<?php echo esc_attr($attachment_img['alt']) ?>" />
                        <?php endif;?>
                    </a>
                </div>
                <div class="today-menu-info">
                    <div class="today-item-info">
                        <a href="<?php echo get_post_meta(get_the_ID(),'link_product',true);?>"><h4><?php the_title(); ?></h4></a>
                        <div class="title-item-line"></div>
                        <div class="price_info">
                             <?php echo get_post_meta(get_the_ID(),'price_product',true);?>
                        </div>
                         <div class="menu-desc"><?php the_content();?></div>
                         
                    </div>
                </div>
            </div>        
        </div>
         <?php endwhile;?>
        <div class="reduce-cost">
            <div class="text-reduce">
                 <?php echo esc_html__('Sale','foodfarm');?>
            </div>
            <div class="price-reduce"><?php echo $sale; ?>
                <div class="text-unit"><?php echo '%</br>off';?></div>
            </div>

        </div>
   </div>
   </div>
</div>
   
<?php
    $output .= ob_get_clean();

    $output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_today_menu' ) . "\n";

    echo $output;

wp_reset_query(); ?>