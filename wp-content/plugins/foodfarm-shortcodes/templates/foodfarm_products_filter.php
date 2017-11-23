<?php
$output  = $number = $first_title = $show_title= $slug_name = $load_more = $btn_text = $product_style= $last_title  = $item_delay = $el_class = $exclude_cat = '';
extract(shortcode_atts(array(
    'first_title' =>'Our',
    'last_title' => 'Product',
    'category_parent' => 0,
    'exclude_cat' => '',
    'order' => 'desc',
    'show_filter' => 'yes',
    'show_title' => 'yes',
    'filter_layout' => '',
    'btn_text' => '',
    'slug_name'     => '',
    'load_more' => 'no',
    'product_style' => '',
    'number' => 8,
    'item_delay' => 'yes',
    'el_class' => ''
), $atts));
if ( get_query_var('paged') ) {
   $paged = get_query_var('paged');
} elseif ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}

$meta_query = WC()->query->get_meta_query();
$args = array(
    'paged' => $paged,
    'post_type' => 'product',
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page' => $number,
    'meta_query' => $meta_query,
    'order' => $order,
    'product_cat' => $slug_name
);

$dataAttr = ' data-isotope="1"';
if ( isset( $show_filter ) && $show_filter ) {
    $dataAttr .= ' data-filter="yes"';
}
$filter_class ='tab_filter_'.$filter_layout;
if ($category_parent || $exclude_cat){
    $catArray = explode(',', $category_parent);
    if($exclude_cat != '' && $category_parent){
        $exclude_cat_a = explode(',', $exclude_cat);
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $catArray,
            ),
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $exclude_cat_a,
                'operator' => 'NOT IN',
            ),        
        );
        $args['product_cat'] = $slug_name;        
    }elseif($exclude_cat!=''){
        $exclude_cat_a = explode(',', $exclude_cat);
        $args['tax_query'] = array(
            // 'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $exclude_cat_a,
                'operator' => 'NOT IN',
            ),        
        );
        $args['product_cat'] = $slug_name;        
    }else{
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $catArray,
            ),       
        );
        $args['product_cat'] = $slug_name;         
    }  
    
}
$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, array('per_page' => $number)));
if ($products->have_posts()) :
$taxonomy_names = get_object_taxonomies( 'product' );
if ( is_array( $taxonomy_names ) && count( $taxonomy_names ) > 0  && in_array( 'product_cat', $taxonomy_names ) ) {
    if($exclude_cat !=''){
        $exclude_cat_a = explode(',', $exclude_cat);
        $terms = get_terms( 'product_cat', array(
            'hierarchical'  => false,
            'hide_empty'        => true,
            'parent' => $category_parent, 
            'order' => 'random',
            'exclude' => $exclude_cat_a,
            ) );        
    }else{
        $terms = get_terms( 'product_cat', array(
            'hierarchical'  => false,
            'hide_empty'        => true,
            'parent' => $category_parent, 
            'order' => 'random',
            ) );        
    }
}
$el_class = foodfarm_shortcode_extract_class($el_class);
$output = '<div class="product-filter-isotope ' . $el_class . '"';        
$output .= '>';
ob_start();
?>
<?php 
    $count_item = 0.2; 
    $animation_delay = '';
    if($item_delay) {
        $animation_delay = ' data-sr="wait '. $count_item .'s"';  
    }
    $count_item += 0.2;
?>
<?php if($product_style =='product_type_2' || $product_style =='product_type_3' ):?>
    <?php if($product_style =='product_type_3'  ):?>
        <div class="our-products product_type_2 product_type_3">
    <?php else:?>
        <div class="our-products product_type_2">
    <?php endif;?>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if ( isset( $show_title ) && $show_title ) :?>
                    <?php if($first_title || $last_title) :?>
                    <div <?php echo $animation_delay; ?> class="entry-title text-center">
                        <h2><?php echo esc_html($first_title);?> <span><?php echo esc_html($last_title);?></span></h2>
                    </div>
                    <?php endif;?>
                <?php endif;?>
                <?php if ($show_filter && is_array( $terms ) && count( $terms ) > 0 ) : ?>
                <div class="tabs-fillter <?php echo esc_attr($filter_class);?>">
                    <?php if($filter_layout =='5'):?>
                        <ul class="nav nav-tabs btn-filter">
                            <li><a class="button active" data-filter="*">
                                <i class="icon-icon1-18"></i>
                                <p><?php echo esc_html__('All Products','foodfarm'); ?></p></a>
                            </li>
                            <?php foreach ( $terms as $term ) : ?>
                                <?php 
                                $filter_icon = get_metadata('product_cat', $term->term_id, 'filter_icon', true);
                                ?>                                    
                            <li><a class="button" data-filter=".<?php echo esc_attr($term->slug); ?>">
                                <i class="<?php echo esc_html($filter_icon);?>"></i>
                                <p><?php echo esc_html($term->name); ?></p>
                            </a></li>
                            <?php endforeach; ?>
                        </ul>                            
                    <?php else:?>
                        <ul class="nav nav-tabs btn-filter">
                            <li><a class="button active" data-filter="*"><?php echo esc_html__('All','foodfarm'); ?></a></li>
                            <?php foreach ( $terms as $term ) : ?>
                            <li><a class="button" data-filter=".<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></a></li>
                            <?php endforeach; ?>
                        </ul>                           
                    <?php endif;?>                
                </div>
                <?php endif;?>
                <div class="our-products-content row tab-sorts">
                    <div class="product-grid product-entries-wrap isotope">
                        <?php while ($products->have_posts()) : $products->the_post(); ?>
                            <?php wc_get_template_part( 'content', 'product-filter2' ); ?>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php if($load_more == 'yes' && $products->max_num_pages > 1) :?>
                    <div class="load-more product-loadmore text-center col-md-12">
                        <a class="btn btn-primary" data-paged="<?php echo esc_html($paged); ?>" data-totalpage="<?php echo esc_html($products->max_num_pages); ?>" id="product-loadmore"> 
                        <?php if($btn_text !=''){
                            echo esc_html($btn_text);
                            }else{
                                echo esc_html__('Load More','foodfarm');
                            }
                        ?>
                            
                        </a>
                    </div>    
                <?php endif;?> 
            </div>
        </div>
    </div>    
<?php else:?>
    <div class="our-products <?php if($filter_layout =='5'){echo 'product_filter_5';}?>">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php if ( isset( $show_filter ) && $show_filter ) :?>
                        <?php if($first_title || $last_title) :?>
                        <div <?php echo $animation_delay; ?> class="entry-title text-center">
                            <h2><?php echo esc_html($first_title);?> <span><?php echo esc_html($last_title);?></span></h2>
                        </div>
                        <?php endif;?>
                    <?php endif;?>
                    <?php if ($show_filter && is_array( $terms ) && count( $terms ) > 0 ) : ?>
                    <div class="tabs-fillter <?php echo esc_attr($filter_class);?>">
                        <?php if($filter_layout =='5'):?>
                            <ul class="nav nav-tabs btn-filter">
                                <li><a class="button active" data-filter="*">
                                    <i class="icon-icon1-18"></i>
                                    <p><?php echo esc_html__('All Products','foodfarm'); ?></p></a>
                                </li>
                                <?php foreach ( $terms as $term ) : ?>
                                    <?php 
                                    $filter_icon = get_metadata('product_cat', $term->term_id, 'filter_icon', true);
                                    ?>                                    
                                <li><a class="button" data-filter=".<?php echo esc_attr($term->slug); ?>">
                                    <i class="<?php echo esc_html($filter_icon);?>"></i>
                                    <p><?php echo esc_html($term->name); ?></p>
                                </a></li>
                                <?php endforeach; ?>
                            </ul>                            

                        <?php else:?>
                            <ul class="nav nav-tabs btn-filter">
                                <li><a class="button active" data-filter="*"><?php echo esc_html__('Show all','foodfarm'); ?></a></li>
                                <?php foreach ( $terms as $term ) : ?>
                                <li><a class="button" data-filter=".<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></a></li>
                                <?php endforeach; ?>
                            </ul>                            
                        <?php endif;?>
                    </div>
                    <?php endif;?>
                    <div class="our-products-content row">
                        <div class="product-grid product-entries-wrap isotope ">
                            <?php while ($products->have_posts()) : $products->the_post(); ?>
                                <?php wc_get_template_part( 'content', 'product-filter' ); ?>
                            <?php endwhile; ?>
                        </div>
                    </div> 
                    <?php if($load_more == 'yes'  && $products->max_num_pages > 1) :?>
                        <div class="load-more product-loadmore text-center col-md-12">
                            <a class="btn btn-primary" data-paged="<?php echo esc_html($paged); ?>" data-totalpage="<?php echo esc_html($products->max_num_pages); ?>" id="product-loadmore"> 
                            <?php if($btn_text !=''){
                                echo esc_html($btn_text);
                                }else{
                                    echo esc_html__('Load More','foodfarm');
                                }
                            ?>                            
                            </a>
                        </div>    
                    <?php endif;?>                                              
                </div>
            </div>
    </div>
<?php endif;?>

<?php
endif;
    $output .= ob_get_clean();

    $output .= '</div>' . foodfarm_shortcode_end_block_comment( 'foodfarm_products_filter' ) . "\n";

    echo $output;

wp_reset_postdata();