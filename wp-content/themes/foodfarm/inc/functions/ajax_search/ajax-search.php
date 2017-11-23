<?php
add_action("wp_ajax_woosearch_search", "foodfarm_search_ajax_data_search");
add_action("wp_ajax_nopriv_woosearch_search", "foodfarm_search_ajax_data_search");

function foodfarm_search_ajax_data_search(){

    $output = '';
    $data_view = '';
    $args = array();
    if(isset($_POST['raw_data'])){
        $args = array(
                    's'                 => $_POST['raw_data'],
                    'post_type'         => 'product',
            );
        if(isset($_POST['category'])){
            if($_POST['category']!='' && $_POST['category']!='all'){
                $args['tax_query']  =   array( 
                                            array(
                                                'taxonomy' => 'product_cat',
                                                'field'    => 'slug',
                                                'terms'    => $_POST['category'],
                                            )
                                        );
            }
        }
        if(isset($_POST['number'])){
             $args['posts_per_page'] = $_POST['number'];
        }else{
            $args['posts_per_page'] = 10;
        }
        $data_view = $_POST['raw_data'];
    }

    $search_data = new WP_Query($args);
    if($search_data->have_posts()):?>
        <ul class="product_list_widget">
        <?php 
        $search_number = 0;
        while ($search_data->have_posts()): $search_data->the_post();
            $search_number++;
        endwhile;?>
            <li class="search_result_info"><?php echo $search_number.esc_html__(' results found in ').'<span>"'.$_POST['raw_data'].'"</span>';
            ?></li>
        <?php while ($search_data->have_posts()): $search_data->the_post();
            $the_title = str_ireplace( $data_view ,"<span>".$data_view."</span>", get_the_title() );
            $product = wc_get_product(get_the_ID());?>
           <li class="item-product-grid">
                <div class="product-img">
                    <a href="<?php echo get_permalink();?>">
                        <?php echo $product->get_image();?>
                   </a>
                </div>
                <div class="product-content">
                    <h3 class="product_title">
                        <a href="<?php echo get_permalink();?>"> <?php echo wp_kses($the_title,foodfarm_allow_html());?></a>                        
                    </h3>
                <div class="price">
                    <?php echo $product->get_price_html();?>
                </div>
                <div class="widget_add_to_cart">
                    <?php echo woocommerce_template_loop_add_to_cart();?>
                </div>
                </div>
            </li>
        <?php  endwhile;?>
        </ul>
        <?php 
        wp_reset_postdata();
    endif;

    wp_die($output);
}
function foodfarm_recipe_search_ajax_data_search(){

    $output = '';
    $data_view = '';
    $args = array();

    if(isset($_POST['raw_data'])){
        $args = array(
                    's'                 => $_POST['raw_data'],
                    'post_type'         => 'recipe',
            );
        if(isset($_POST['category'])){
            if($_POST['category']!='' && $_POST['category']!='all'){
                $args['tax_query']  =   array( 
                                            array(
                                                'taxonomy' => 'product_cat',
                                                'field'    => 'slug',
                                                'terms'    => $_POST['category'],
                                            )
                                        );
            }
        }
        if(isset($_POST['number'])){
             $args['posts_per_page'] = $_POST['number'];
        }else{
            $args['posts_per_page'] = 10;
        }
        $data_view = $_POST['raw_data'];
    }

    $search_data = new WP_Query($args);
    if($search_data->have_posts()):
        $output .= '<ul>';
        while ($search_data->have_posts()): $search_data->the_post();
            $the_title = str_ireplace( $data_view ,"<span>".$data_view."</span>", get_the_title() );
            $output .= '<li>';
            $output .='<img class="recipe_search_img" src="'.get_the_post_thumbnail_url().'" alt=""/>';
            $output .='<a href="'.get_permalink().'"> '.$the_title.'</a>';
            $output .='</li>';
        endwhile;
        $output .= '</ul>';
        wp_reset_postdata();
    endif;

    wp_die($output);
}
add_action("wp_ajax_recipe_search", "foodfarm_recipe_search_ajax_data_search");
add_action("wp_ajax_nopriv_recipe_search", "foodfarm_recipe_search_ajax_data_search");