<?php
$output = $number = $items_desktop = $items_tablets = $items_mobile = $el_class = '';
extract(shortcode_atts(array(
    'number' => 3,
    'layout' => 'grid',
    'cat' => '',
    'items_desktop' => 4,
    'items_tablets' => 2,
    'items_mobile' => 1,
    'el_class' => ''
), $atts));
$args = array(
    'post_type' => 'member',
    'posts_per_page' => $number,
);
$catArray = explode(',', $cat);
if ($cat){
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'member_cat',
            'field'    => 'term_id',
            'terms'    => $catArray,
        ),
    );
}
$posts = new WP_Query($args);
$items_desktop_no= 12/$items_desktop;
$items_tablets_no = 12/$items_tablets;
$items_mobile_no = 12/$items_mobile;
$title = foodfarm_shortcode_js_remove_wpautop($content, true);
if ($posts->have_posts()) {
    $el_class = foodfarm_shortcode_extract_class( $el_class );

    $output = '<div class="our-team ' . $el_class . '"';
    $output .= '>';

    ob_start();
    ?>
        <div class="row">
            <?php if($title !="") :?>
                <div class="entry-title title-icon text-center">
                    <?php echo foodfarm_shortcode_js_remove_wpautop($content, true); ?>
                    <div class="icon-title"><span class="icon-8"></span></div>
                </div>
            <?php endif;?>
            <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                <?php if($layout == 'grid') :?>
                    <?php $featuredImage = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>  
                    <div class="col-md-<?php echo esc_attr($items_desktop_no) ?> col-sm-<?php echo esc_attr($items_tablets_no) ?> col-xs-<?php echo esc_attr($items_mobile_no) ?>">
                        <div class="profile-content">
                            <div class="profile-top bg-hover2">
                                <div class="profile-image">
                                    <img src="<?php echo esc_url($featuredImage); ?>" alt="member">
                                </div>
                                <?php 
                                    $fb = get_post_meta(get_the_ID(),'facebook', true);
                                    $twitter = get_post_meta(get_the_ID(),'twitter', true);
                                    $google = get_post_meta(get_the_ID(),'google', true);
                                ?>
                                <?php if($fb!='' || $twitter!="" || $google!=""):?>
                                <div class="link-network">
                                    <ul class="social-url">
                                        <?php if($fb!=''):?>
                                            <li><a href="<?php echo esc_url($fb);?>" data-toggle="tooltip" title="" data-original-title="facebook"><i class="fa fa-facebook"></i></a></li>
                                        <?php endif;?>
                                        <?php if($twitter!=''):?>
                                            <li><a href="<?php echo esc_url($twitter);?>" data-toggle="tooltip" title="" data-original-title="twitter"><i class="fa fa-twitter"></i></a></li>
                                        <?php endif;?>
                                        <?php if($google!=''):?>
                                            <li><a href="<?php echo esc_url($google);?>" data-toggle="tooltip" title="" data-original-title="google"><i class="fa fa-google-plus"></i></a></li>
                                        <?php endif;?>

                                    </ul>
                                </div>
                                <?php endif;?>  
                            </div>
                            <div class="profile-bottom ">
                                <div class="profile-desc">
                                    <a href="#" class="profile-name"><?php print the_title();?></a>
                                    <p><?php echo esc_html(get_post_meta(get_the_ID(),'occupation', true));?></p>
                                    <?php the_content();?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else :?>
                    <div class="member-image member-service">
                        <?php 
                        if( class_exists('Dynamic_Featured_Image') ):
                        global $dynamic_featured_image;
                        global $post;
                        $featured_images = $dynamic_featured_image->get_featured_images( $post->ID );

                            if ( $featured_images ): ?>
                                    <?php foreach( $featured_images as $images ): ?>
                                       <div class="member-slider_item">
											<div class="img-member">
												<img src="<?php echo $images['full'] ?>" alt="">
											</div>
											<div class="box-members">
												<span class="button_member"></span>
												<div class="box-member">
													<h4><?php print the_title();?></h4>
													<p><?php echo esc_html(get_post_meta(get_the_ID(),'occupation', true));?></p>
												</div>
											</div>
                                        </div>
                                    <?php endforeach; ?>
                            <?php
                            endif;
                        endif;
                        ?>
                    </div>
                <?php endif;?>    

            <?php endwhile; ?>
        </div>        
    
    <?php
    $output .= ob_get_clean();

    $output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_our_team' ) . "\n";

    echo $output;
}

wp_reset_postdata();