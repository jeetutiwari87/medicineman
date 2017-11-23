<?php
$output = $title = $title_url = $position = $icon_position = $title_font_size = $title_align = $title_color = $view_background = $icon_color = $select_icon = $layout = $icon_type  = $image_icon = $background_image = $icon_image = $animation_type = $el_class =$btn_text= $icon_size = '';
extract(shortcode_atts(array(
    'title' => '',
    'title_url' => '',
    'title_align' => 'left',
    'position' => 'left',
    'title_color' => '',
    'title_font_size' => '',
    'icon_type' => '',
    'select_icon' => 'icon',
    'layout' => '',
    'icon_linecons' => '',
    'icon_size'     => '',
    'icon_fontawesome' => '',
    'btn_text'  => '',
    'image_icon'    => '',
    'icon_openiconic' => '',
    'icon_typicons' => '',
    'icon_pestrokefont' => '',
    'icon_foodfarm' => '',
    'icon_entypo' => '',
    'icon_color' => '',
    'background_image' => '',
    'view_background' => false,
    'el_class' => ''
), $atts));
$icon_style ='';
if($icon_size != ''){
    $icon_style .='style = "font-size:'.esc_html($icon_size).'";';
}
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
if(!empty($bgImage) && $view_background == true){
    $class.=' icon-bg';
}
    $el_class = foodfarm_shortcode_extract_class( $el_class );

    $output = '<div class="wpb_content_element clearfix' . $el_class . '"';        
    $output .= '>';
    $featuredImage = wp_get_attachment_url($icon_image);
    $image_icon_url = wp_get_attachment_url($image_icon);
    $bgImage = wp_get_attachment_url($background_image);
    ob_start();
    $href = vc_build_link( $title_url );
    ?>
    <?php if($layout=='layout_2'):?>
        <div class="our-services services-content service_style_2
                    <?php if($position=='right'){
                        echo 'text-align-right';
                    }elseif($position=='center'){
                        echo 'text-center';
                    }else{
                        echo 'text-left';
                    }
                    ?>" >

            <div class="services-list">
                <div class="services-icon">
                    <?php if($select_icon == 'image'):?>
                        <?php if($href['url'] !=''):?>
                            <a href="<?php echo $href['url'];?>">
                        <?php endif;?>
                        <img src="<?php echo esc_url($image_icon_url);?>" alt="image_icon">                        
                        <?php if($href['url'] !=''):?>
                            </a>
                        <?php endif;?>
                    <?php else:?>
                        <i class="<?php echo esc_attr($icon_class); ?>"  
                            <?php if($icon_color != '' || $icon_size!=''):?>
                                style="color: <?php echo $icon_color;?>; font-size: <?php echo $icon_size;?>px"
                            <?php endif;?>
                        ></i>  
                    <?php endif;?> 
                </div>            
                <h4 class="services-title 
                        <?php if($title_align=='right'){
                            echo 'text-align-right';
                        }elseif($title_align=='center'){
                            echo 'text-center';
                        }else{
                            echo 'text-left';
                        }
                        ?> " ><a href="<?php echo $href['url'];?>"
                        <?php 
                        if(!empty($title_color) || !empty($title_font_size)){
                            $style = ' style="';
                            if(!empty($title_color)){
                                $style.='color:'.$title_color.';';
                            }if(!empty($title_font_size)){
                                $style.='font-size:'.$title_font_size;
                            }
                            $style .= '"';
                            echo $style;
                        }
                        ?>><?php echo $title;?></a>
                </h4>
                <div class="services-part">
                    <div class="list-primary">
                        <?php 
                        echo wpb_js_remove_wpautop(do_shortcode($content), true);
                        ?>
                    </div>
                    <div class="ser_content">
                        <?php if($href['url'] !=''):?>
                            <a href="<?php echo $href['url'];?>">
                        <?php endif;?>
                            <?php if($btn_text !=''):?>
                                <?php echo esc_html($btn_text);?><i class="fa fa-angle-right" aria-hidden="true"></i>
                            <?php else:?>
                                <?php echo esc_html__('Read More','foodfarm');?><i class="fa fa-angle-right" aria-hidden="true"></i>
                            <?php endif;?>
                        <?php if($href['url'] !=''):?>
                            </a>
                        <?php endif;?>                        
                    </div>
                </div>
            </div>
        </div>    
    <?php elseif($layout=='layout_3'):?>  
        <div class="our-services services-content service_style_3
                    <?php if($position=='right'){
                        echo 'text-align-right';
                    }elseif($position=='center'){
                        echo 'text-center';
                    }else{
                        echo 'text-left';
                    }
                    ?>" >

            <div class="services-list">
                <div class="services-icon">
                    <?php if($select_icon == 'image'):?>
                        <?php if($href['url'] !=''):?>
                            <a href="<?php echo $href['url'];?>">
                        <?php endif;?>
                        <img src="<?php echo esc_url($image_icon_url);?>" alt="image_icon">                        
                        <?php if($href['url'] !=''):?>
                            </a>
                        <?php endif;?>
                    <?php else:?>
                        <i class="<?php echo esc_attr($icon_class); ?>"  
                            <?php if($icon_color != '' || $icon_size!=''):?>
                                style="color: <?php echo $icon_color;?>; font-size: <?php echo $icon_size;?>px"
                            <?php endif;?>
                        ></i>  
                    <?php endif;?> 
                </div>     
                <div class="service_body">     
                    <h4 class="services-title 
                            <?php if($title_align=='right'){
                                echo 'text-align-right';
                            }elseif($title_align=='center'){
                                echo 'text-center';
                            }else{
                                echo 'text-left';
                            }
                            ?> " ><a href="<?php echo $href['url'];?>"
                            <?php 
                            if(!empty($title_color) || !empty($title_font_size)){
                                $style = ' style="';
                                if(!empty($title_color)){
                                    $style.='color:'.$title_color.';';
                                }if(!empty($title_font_size)){
                                    $style.='font-size:'.$title_font_size;
                                }
                                $style .= '"';
                                echo $style;
                            }
                            ?>><?php echo $title;?></a>
                    </h4>
                    <div class="services-part">
                        <div class="list-primary">
                            <?php 
                            echo wpb_js_remove_wpautop(do_shortcode($content), true);
                            ?>
                        </div>
                    </div>
                </div>  
            </div>
        </div>        
    <?php else:?>
        <div class="our-services services-content" >

            <div class="services-list">
                <h4 class="services-title 
                        <?php if($title_align=='right'){
                            echo 'text-align-right';
                        }elseif($title_align=='center'){
                            echo 'text-center';
                        }else{
                            echo 'text-left';
                        }
                        ?> " style="background: rgba(0, 0, 0, 0) 
        url(<?php echo $bgImage;?>) no-repeat scroll left top; padding-top:30px;"><a href="<?php echo $href['url'];?>"
                        <?php 
                        if(!empty($title_color) || !empty($title_font_size)){
                            $style = 'style="';
                            if(!empty($title_color)){
                                $style.='color:'.$title_color.';';
                            }if(!empty($title_font_size)){
                                $style.='font-size:'.$title_font_size;
                            }
                            $style .= '"';
                            echo $style;
                        }
                        ?>><?php echo $title;?></a>
                </h4>
                <div class="services-part">
                    <div class="services-icon">
                        <?php if($select_icon == 'image'):?>
                            <?php if($href['url'] !=''):?>
                                <a href="<?php echo $href['url'];?>">
                            <?php endif;?>
                            <img src="<?php echo esc_url($image_icon_url);?>" alt="image_icon">                        
                            <?php if($href['url'] !=''):?>
                                </a>
                            <?php endif;?>
                        <?php else:?>
                            <i class="<?php echo esc_attr($icon_class); ?>"  
                            <?php if($icon_color != '' || $icon_size!=''):?>
                                style="color: <?php echo $icon_color;?>; font-size: <?php echo $icon_size;?>px"
                            <?php endif;?>
                            ></i>  
                        <?php endif;?> 
                    </div>
                    <div class="list-primary">
                        <?php 
                        echo wpb_js_remove_wpautop(do_shortcode($content), true);
                        ?>
                    </div>
                </div>
            </div>
        </div> 
    <?php endif;?>   
    <?php
    $output .= ob_get_clean();
    $output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_services' );
    echo $output;
    wp_reset_postdata();