<?php
$output = $number = $animation_type = $slug = $slide = $blockquote = $text_color = $title_color= $align = $pag_style = $el_class = '';
extract(shortcode_atts(array(
    'first_title' =>'',
    'last_title' => '',
    'title_color' => '',
    'icon_color' => '',
    'text_color' => '',
    'slug'      => '',
    'layout' =>'slide',
    'number' => 3,
    'block_align' => 'center',
    'show_name' => 'yes',
    'item_delay' => 'yes',
    'el_class' => ''
), $atts));


if($slug !=''){
    $slug_arr =  explode(',', $slug);
    $args = array(
        'post_type' => 'testimonial',
        'posts_per_page' => $number,
        'post_name__in' => $slug_arr,
    );  
}else{
    $args = array(
        'post_type' => 'testimonial',
        'posts_per_page' => $number,
    );     
}
$style = "";
$style_2 = "";
$style_3 = "";
$align_class = '';
if($block_align == 'right'){
    $align_class = 'text-align-right';
}elseif($block_align == 'center'){
    $align_class = 'text-center';
}else{
    $align_class = 'text-left';
}
$testimonial = new WP_Query($args);
if ($testimonial->have_posts()) {
    $el_class = foodfarm_shortcode_extract_class( $el_class );

    $slide_id = 'testimonial_' . wp_rand();
    $output = '<div class="testimonials-client wpb_content_element ' .$layout.' '.$el_class. '"';
    if ($animation_type){
        $output .= ' data-appear-animation="'.$animation_type.'"';
    }
        
    $output .= '>';
    ob_start();
    $count_item = 0.2; 
    $animation_delay = '';
    if($item_delay) {
        $animation_delay = ' data-sr="wait '. $count_item .'s"';  
    }
    $count_item += 0.2;
    ?>
    <?php if($first_title || $last_title) :?>
        <div class="entry-title title-icon <?php echo $align_class;?>" <?php echo $animation_delay; ?>>
            <h2 
            <?php 
            if(!empty($title_color)){
                $style_2 .= 'style="';
                if(!empty($title_color)){
                    $style_2.='color:'.$title_color.';';
                }
                $style_2 .= '"';
                echo $style_2;
            }
            ?>><?php echo esc_html($first_title);?> <span><?php echo esc_html($last_title);?></span>
            </h2>
        </div>
    <?php endif;?>
    <div class="row">
        <?php if($layout == 'slide') :?>    
            <div id="container">
              <div class="owl-carousel" id="<?php echo $slide_id; ?>">
                <?php while ($testimonial->have_posts()) : $testimonial->the_post(); ?>
                <div data-dot="1" class="<?php echo $align_class;?>">
                    <i class="fa fa-quote-left" aria-hidden="true" 
                    <?php 
                    if(!empty($icon_color)){
                        $style_3 .= 'style="';
                        if(!empty($icon_color)){
                            $style_3.='color:'.$icon_color.';';
                        }
                        $style_3 .= '"';
                        echo $style_3;
                    }
                    ?>></i>    
                    <?php
                        $content = apply_filters( 'the_content', get_the_content() );
                        $content = strip_tags($content,'<p><br>');
                    ?>
                    <div class="testimonial_content" 
                    <?php 
                    if(!empty($text_color)){
                        $style = 'style="';
                        if(!empty($text_color)){
                            $style.='color:'.$text_color.';';
                        }
                        $style .= '"';
                        echo $style;
                    }
                    ?>>
                    <?php echo $content;?></div>
                    <?php if($show_name) :?>
                        <p class="name" 
                        <?php 
                        if(!empty($text_color)){
                            $style = 'style="';
                            if(!empty($text_color)){
                                $style.='color:'.$text_color.';';
                            }
                            $style .= '"';
                            echo $style;
                        }
                        ?>> <?php echo the_title();?></p>
                    <?php endif;?>
                </div>
                <?php endwhile;?>
              </div>
              <div class="dotsCont <?php echo $align_class;?>">
                <?php while ($testimonial->have_posts()) : $testimonial->the_post(); ?>
                <div>
                    <?php the_post_thumbnail();?>
                </div>
                <?php endwhile;?>
              </div>
            </div>
        <?php endif;?>
    </div>    

    <?php if($layout == 'slide2') :?>
        <div id="container-2" class="testimonial-3">
            <div class="owl-carousel" id="<?php echo $slide_id; ?>">
                <?php while ($testimonial->have_posts()) : $testimonial->the_post(); ?>
                <div data-dot="1">
                    <div class="img-testimonial">
                     <?php the_post_thumbnail();?>
                    </div>
                    <div class="testimonial-container"> 
                        <?php
                            $content = apply_filters( 'the_content', get_the_content() );
                            $content = strip_tags($content,'<p><br>');
                        ?>
                        <div class="testimonial_content" 
                        <?php 
                        if(!empty($text_color)){
                            $style = 'style="';
                            if(!empty($text_color)){
                                $style.='color:'.$text_color.';';
                            }
                            $style .= '"';
                            echo $style;
                        }
                        ?>>
                        <?php echo $content;?>
                         <?php if($show_name) :?>
                            <div class="name" 
                            <?php 
                            if(!empty($text_color)){
                                $style = 'style="';
                                if(!empty($text_color)){
                                    $style.='color:'.$text_color.';';
                                }
                                $style .= '"';
                                echo $style;
                            }
                            ?>> <?php echo the_title();?></div>
                        <?php endif;?>
                        </div>
                    </div>
                </div>
                <?php endwhile;?>
            </div>
            <div class="dotsCont <?php echo $align_class;?>">
                <?php while ($testimonial->have_posts()) : $testimonial->the_post(); ?>
                <div>
                     <?php if($show_name) :?>
                        <p class="name" 
                        <?php 
                        if(!empty($text_color)){
                            $style = 'style="';
                            if(!empty($text_color)){
                                $style.='color:'.$text_color.';';
                            }
                            $style .= '"';
                            echo $style;
                        }
                        ?>> <?php echo the_title();?></p>
                    <?php endif;?>
                </div>
                <?php endwhile;?>
            </div>
        </div>
    <?php elseif($layout == 'slide3'):?>  
            <div id="container">
              <div class="owl-carousel" id="<?php echo $slide_id; ?>">
                <?php while ($testimonial->have_posts()) : $testimonial->the_post(); ?>
                <?php $featuredImage = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
                <div data-dot="1" class="<?php echo $align_class;?>">
                    <?php
                        $content = apply_filters( 'the_content', get_the_content() );
                        $content = strip_tags($content,'<p><br>');
                    ?>
                    <?php 
                    $attachment_id = get_post_thumbnail_id();
                    $attachment_list = foodfarm_get_attachment($attachment_id, 'foodfarm-member');
                    ?>
                   <div class="testimonial-profile">
                        <img class="testimonial-img testimonial-img-3" width="<?php echo esc_attr($attachment_list['width']) ?>" height="<?php echo esc_attr($attachment_list['height']) ?>" src="<?php echo esc_url($attachment_list['src']) ?>" alt="testimonial-img" />
                    </div>
                    <div class="testimonial_content testimonial-style3" 
                    <?php 
                    if(!empty($text_color)){
                        if(!empty($text_color)){
                            $style=' style="color:'.$text_color.';';
                        }
                        $style .= '"';
                        echo $style;
                    }
                    ?>>
                    
                    <?php echo $content;?>
                    <?php if($show_name) :?>
                        <p class="name" 
                        <?php 
                        if(!empty($text_color)){
                            if(!empty($text_color)){
                                $style=' style="color:'.$text_color.';';
                            }
                            $style .= '"';
                            echo $style;
                        }
                        ?>> <?php echo the_title();?></p></div>
                    <?php endif;?>
                </div>
                <?php endwhile;?>
              </div>
              <div class="dotsCont style3 <?php echo $align_class;?>">
              </div>
            </div>        
    <?php elseif($layout == 'slide4'):?>
        <div class="testimonial_style_4">
          <div class="owl-carousel" id="<?php echo $slide_id; ?>">
            <?php while ($testimonial->have_posts()) : $testimonial->the_post(); ?>
            <div data-dot="1" class="<?php echo $align_class;?>">
                <div class="tes_image">
                    <?php the_post_thumbnail();?>
                </div>
                <?php if($show_name) :?>
                    <p class="name" 
                        <?php 
                        if(!empty($title_color)){
                            $style_2 = 'style="';
                            if(!empty($title_color)){
                                $style_2.='color:'.$title_color.';';
                            }
                            $style_2 .= '"';
                            echo $style_2;
                        }
                        ?>> <?php echo the_title();?></p>
                <?php endif;?>        
                <?php if(get_post_meta(get_the_ID(),'role', true) != ''){
                            echo '<p class="tes_role">'.esc_html(get_post_meta(get_the_ID(),'role', true)).'</p>';
                        }
                ?>         
                <?php
                    $content = apply_filters( 'the_content', get_the_content() );
                    $content = strip_tags($content,'<p><br>');
                ?>
                <div class="testimonial_content" 
                <?php 
                if(!empty($text_color)){
                    $style = 'style="';
                    if(!empty($text_color)){
                        $style.='color:'.$text_color.';';
                    }
                    $style .= '"';
                    echo $style;
                }
                ?>>
                <?php echo $content;?></div>

            </div>
            <?php endwhile;?>
          </div>
        </div>        
    <?php elseif($layout == 'slide5'):?>
            <div class="testimonial_style_5">
              <div class="slider-nav">
                <?php while ($testimonial->have_posts()) : $testimonial->the_post(); ?>
                <div>
                    <?php the_post_thumbnail('foodfarm-testimonial');?>
                </div>
                <?php endwhile;?>
              </div>           
              <div class="slider-for">
                <?php while ($testimonial->have_posts()) : $testimonial->the_post(); ?>
                <div class="<?php echo esc_attr($align_class);?>">    
                    <?php
                        $content = apply_filters( 'the_content', get_the_content() );
                        $content = strip_tags($content,'<p><br>');
                    ?>
                    <div class="testimonial_content" 
                    <?php 
                    if(!empty($text_color)){
                        $style = 'style="';
                        if(!empty($text_color)){
                            $style.='color:'.$text_color.';';
                        }
                        $style .= '"';
                        echo $style;
                    }
                    ?>>
                    <?php echo $content;?></div>
                    <?php if($show_name) :?>
                        <p class="name"> <?php echo the_title();?></p>
                    <?php endif;?>
                </div>
                <?php endwhile;?>
              </div> 
            </div> 
            <script type="text/javascript">
                jQuery(function ($) {
                    jQuery('.slider-for').slick({
                      slidesToShow: 1,
                      slidesToScroll: 1,
                      arrows: false,
                      asNavFor: '.slider-nav',
                      <?php if(is_rtl()) :?>
                      rtl: true,
                      <?php endif;?>
                    });
                    jQuery('.slider-nav').slick({
                      slidesToShow: 3,
                      slidesToScroll: 1,
                      asNavFor: '.slider-for',
                      dots: false,
                      centerMode: true,
                      focusOnSelect: true,
                      vertical: true,
                      verticalSwiping: true,
                        responsive: [
                            {
                              breakpoint: 768,
                              settings: {
                                vertical: false,
                                verticalSwiping: false,
                                centerMode: true,
                              }
                            },
                            {
                              breakpoint: 376,
                              settings: {
                                slidesToShow: 2,
                                 vertical: false,
                                verticalSwiping: false,
                                centerMode: true,
                              }
                            },                            
                          ]                      
                    });
                });
            </script>     
    <?php endif;?>
<?php if($layout != 'slide5'):?>
<script type="text/javascript">
    jQuery(function ($) {
        var owl = $("#<?php echo esc_js($slide_id); ?>");
        owl.owlCarousel({
        <?php if (is_rtl()) :?>
            rtl:true,
        <?php endif;?>
        <?php if($layout == 'slide4'):?>
            nav: true,
            navText: [
              "Prev",
              "Next"
              ],            
        <?php endif;?>
        items:1,
        loop:true,
        margin:10,
        smartSpeed:450,
        dotsContainer: '.dotsCont'
        }); //end: owl
    });
</script>
<?php endif;?>
    <?php
    $output .= ob_get_clean();

    $output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_testimonial' ) . "\n";

    echo $output;
}

wp_reset_postdata();
