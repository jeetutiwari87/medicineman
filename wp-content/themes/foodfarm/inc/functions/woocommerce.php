<?php
//remove action
if( class_exists( 'YITH_WCQV_Frontend' ) ){
$quick_view = YITH_WCQV_Frontend();
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_product_loop_tags', 5 );
remove_action('woocommerce_after_shop_loop_item', array( $quick_view, 'yith_add_quick_view_button' ), 15 );
}
remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products',20);
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display',10, 1 ); 
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10); 
remove_action( 'woocommerce_before_shop_loop_item_title_type2', 'woocommerce_template_loop_product_thumbnail', 10); 

//add action
add_action( 'woocommerce_before_shop_loop_item_title', 'foodfarm_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title_type2', 'foodfarm_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
//add action
add_action('woocommerce_related_after', 'woocommerce_output_related_products', 10);
add_action('woocommerce_related_after', 'foodfarm_banner_single_product', 20);
add_action('woocommerce_archive_description', 'wc_print_notices', 10);
add_action('woocommerce_before_shop_loop', 'foodfarm_view_count', 50);
add_action('woocommerce_before_shop_loop_item_title','foodfarm_quickview', 20);
add_action('woocommerce_after_shop_loop_item_title', 'foodfarm_woocommerce_single_excerpt', 40);
add_action('woocommerce_archive_description', 'foodfarm_product_loop_view_mode', 10);
add_action('init', 'woocommerce_clear_cart_url');
add_action('woocommerce_single_product_summary','foodfarm_single_info', 40);
add_action('woocommerce_shop_loop_item_title','foodfarm_wishlist_custom', 10);
add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 20);
add_action('woocommerce_shop_loop_item_title', 'foodfarm_compare_custom', 40);
add_action('woocommerce_shop_loop_item_title_type2','foodfarm_wishlist_custom', 10);
add_action('woocommerce_shop_loop_item_title_type2', 'foodfarm_compare_custom', 10);
add_action('woocommerce_shop_loop_item_title_type2', 'foodfarm_quickview', 10);
add_action('woocommerce_shop_loop_item_title_type2', 'woocommerce_template_loop_add_to_cart', 40);
add_action('woocommerce_list_shop_loop_custom','foodfarm_wishlist_custom', 10);
add_action('woocommerce_list_shop_loop_custom', 'woocommerce_template_loop_add_to_cart', 20);
add_action('woocommerce_list_shop_loop_custom', 'foodfarm_compare_custom', 40);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 5);
add_action( 'woocommerce_single_product_summary', 'foodfarm_stock_text_shop_page', 20 );
//add filter
add_filter('woocommerce_add_to_cart_fragments', 'foodfarm_woocommerce_header_add_to_cart_fragment');
add_filter('loop_shop_per_page', 'foodfarm_product_shop_per_page', 20);
// add_filter( 'woocommerce_billing_fields' , 'foodfarm_override_billing_fields' );
// add_filter( 'woocommerce_shipping_fields' , 'foodfarm_override_shipping_fields' );
add_filter('woocommerce_checkout_fields', 'foodfarm_custom_override_checkout_fields');
add_filter( 'woocommerce_product_add_to_cart_text' , 'foodfarm_woocommerce_product_add_to_cart_text' );
add_filter( 'wp_calculate_image_srcset', 'foodfarm_disable_srcset' );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 30 );
add_action( 'wp_print_scripts', 'foodfarm_remove_password_strength', 100 );
add_filter("woocommerce_checkout_fields", "foodfarm_order_fields");
add_filter("woocommerce_checkout_fields", "foodfarm_order_shipping_fields");
add_action( 'after_setup_theme', 'foodfarm_woocommerce_support' );
add_filter('woocommerce_product_get_rating_html', 'foodfarm_get_rating_html', 10, 2);
add_filter( 'woocommerce_default_address_fields' , 'foodfarm_override_default_address_fields' );

function foodfarm_override_default_address_fields( $address_fields ) {
     $address_fields['postcode']['placeholder'] = esc_html__('Postcode / Zip *','foodfarm');

     return $address_fields;
}
function foodfarm_stock_text_shop_page() {
    global $product;
     $availability = $product->get_availability();
    if ( $product->is_in_stock() ) {
        echo '<div class="availability"><h4>'.esc_html__('Availability:', 'foodfarm').'</h4><p class="stock">' .$product->get_stock_quantity(). esc_html__( ' In Stock', 'foodfarm') . '</p></div>';
    }
    else{
         echo '<div class="availability"><h4>'.esc_html__('Availability:', 'foodfarm').'</h4><p class="stock">' . esc_html__( 'Out Stock', 'foodfarm') . '</p></div>';
    }
}
//show rating when not review
function foodfarm_template_loop_product_thumbnail() {
    global $product;
    ?>
    <a href="<?php the_permalink(); ?>">
        <?php echo  woocommerce_get_product_thumbnail(); ?>
    </a>
    <?php
}
function foodfarm_get_rating_html($rating_html, $rating) {
  if ( $rating > 0 ) {
    $title = sprintf( esc_html__( 'Rated %s out of 5', 'foodfarm' ), $rating );
  } else {
    $title = 'Not yet rated';
    $rating = 0;
  }

  $rating_html  = '<div class="star-rating" title="' . $title . '">';
  $rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . esc_html__( 'out of 5', 'foodfarm' ) . '</span>';
  $rating_html .= '</div>';

  return $rating_html;
}
function foodfarm_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
//Functions
function foodfarm_update_wishlist_count(){
    if( function_exists( 'YITH_WCWL' ) ){
        wp_send_json( YITH_WCWL()->count_products() );
    }
}
add_action( 'wp_ajax_update_wishlist_count', 'foodfarm_update_wishlist_count' );
add_action( 'wp_ajax_nopriv_update_wishlist_count', 'foodfarm_update_wishlist_count' );
function foodfarm_remove_password_strength() {
    if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
        wp_dequeue_script( 'wc-password-strength-meter' );
    }
}

function foodfarm_order_fields($fields) {
    $order = array(
        "billing_country",
        "billing_state",
        "billing_first_name", 
        "billing_last_name", 
        "billing_company", 
        "billing_address_1", 
        "billing_address_2",
        "billing_city",   
        "billing_postcode",       
        "billing_email", 
        "billing_phone"
    );
    foreach($order as $field)
    {
        $ordered_fields[$field] = $fields["billing"][$field];
    }

    $fields["billing"] = $ordered_fields;
    return $fields;

}
function foodfarm_order_shipping_fields($fields) {
    $order = array(
        "shipping_country",
        "shipping_state",
        "shipping_first_name", 
        "shipping_last_name", 
        "shipping_company", 
        "shipping_address_1",
        "shipping_address_2",
        "shipping_city",        
        "shipping_postcode",
        "shipping_phone",       
        "shipping_email", 
        
    );
    foreach($order as $field)
    {
        $ordered_fields[$field] = $fields["shipping"][$field];
    }

    $fields["shipping"] = $ordered_fields;
    return $fields;

}
function foodfarm_banner_single_product(){
global $foodfarm_settings;   
if ($foodfarm_settings['banner_product'] && $foodfarm_settings['banner_product']['url']):
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="img-banner">
        <?php
            echo '<img class="" src="' . esc_url(str_replace(array('http:', 'https:'), '', $foodfarm_settings['banner_product']['url'])) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" />';
        ?>
    </div>
</div>
<?php
endif;
}
function foodfarm_disable_srcset( $sources ) {
return false;
}
function foodfarm_product_loop_view_mode() {
    global $foodfarm_settings, $wp_query;
    $cat = $wp_query->get_queried_object();
    if(isset($cat->term_id)){
    $woo_cat = $cat->term_id;
    }else{
        $woo_cat = '';
    }
    $product_list_mode = get_metadata('product_cat', $woo_cat, 'list_mode_product', true);
    ?>
    <div class="col-md-6 col-sm-2 col-xs-3 no-padding">
        <div class="viewmode-toggle">
            <?php if($product_list_mode != 'only-list') : ?>
            <a href="#" id="grid_mode" title="<?php echo esc_html__('Grid View', 'foodfarm') ?>"><i class="fa fa-th-large"></i></a>
            <?php endif;?>
            <?php if($product_list_mode != 'only-grid') : ?>
            <a href="#" id="list_mode" title="<?php echo esc_html__('List View', 'foodfarm') ?>"><i class="fa fa-list"></i></a>
            <?php endif;?>
        </div>
    </div>   
    <?php
}
function foodfarm_view_count(){
    global $wp_query, $foodfarm_settings;

    if ($foodfarm_settings['category-item']) {
        $per_page = explode(',', $foodfarm_settings['category-item']);
    } else {
        $per_page = explode(',', '12,24,36');
    }
    $page_count = foodfarm_product_shop_per_page();
    ?>
    <form class="woocommerce-viewing result-count" method="get">
        <label><?php echo esc_html__('Show', 'foodfarm') ?> </label>
        <select name="count" class="count">
            <?php foreach ( $per_page as $count ) : ?>
                <option value="<?php echo esc_attr( $count ); ?>" <?php selected( $page_count, $count ); ?>><?php echo esc_html( $count ); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="paged" value=""/>
        <?php
        // Keep query string vars intact
        foreach ( $_GET as $key => $val ) {
            if ( 'count' === $key || 'submit' === $key || 'paged' === $key ) {
                continue;
            }
            if ( is_array( $val ) ) {
                foreach( $val as $innerVal ) {
                    echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
                }
            } else {
                echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
            }
        }
        ?>
    </form>
    <?php
}
function foodfarm_woocommerce_product_add_to_cart_text() {
    global $product;
    
    $product_type = $product->get_type();
    
    switch ( $product_type ) {
        case 'external':
            return esc_html__( 'Buy product', 'foodfarm' );
        break;
        case 'grouped':
            return esc_html__( 'View products', 'foodfarm' );
        break;
        case 'simple':
            return esc_html__( 'Add to cart', 'foodfarm' );
        break;
        case 'variable':
            return esc_html__( 'Select options', 'foodfarm' );
        break;
        default:
            return esc_html__( 'Read more', 'foodfarm' );
    }
    
}
function foodfarm_quickview(){
    global $product, $foodfarm_settings;
    ?>
    <?php 
        if($foodfarm_settings['product-quickview']){
            printf('<div class="quick-view" data-toggle="tooltip" title="'.esc_html__("Quick view","foodfarm").'"><a onclick="" href="#" class="yith-wcqv-button add_to_wishlist" data-product_id="%d" title="%s"><i class="fa fa-plus"></i></a></div>', $product->get_id(), esc_html__('Quick View', 'foodfarm'), esc_html__('Quick View', 'foodfarm'));
        }
    ?>
    <?php
}
function foodfarm_wishlist_custom(){
    global $foodfarm_settings;
?>
<div class="add-to">
        <?php    
        if (class_exists('YITH_WCWL') && $foodfarm_settings['product-wishlist']) {
            echo do_shortcode('[yith_wcwl_add_to_wishlist]');
        }
        ?>
</div>
<?php
}
function foodfarm_single_info(){
    global $product, $foodfarm_settings;
    $compare = (get_option('yith_woocompare_compare_button_in_products_list') == 'yes');
    ?>
    <div class="add-to">
            <?php    
            if (class_exists('YITH_WCWL') && $foodfarm_settings['product-wishlist']) {
                echo do_shortcode('[yith_wcwl_add_to_wishlist]');
            }
            ?>
        <?php
        if ($compare && $foodfarm_settings['product-compare']){
                printf('<div class="add-to-compare"><a title="'.esc_html__("compare","foodfarm").'" onclick="" data-toggle="tooltip" href="%s" class="%s" data-product_id="%d"><i class="fa fa-random"></i></a></div>', foodfarm_add_compare_action($product->get_id()), 'add_to_compare compare button', $product->get_id(), esc_html__('Compare', 'foodfarm'));
            }
        ?>
    </div>
    <?php
}

function foodfarm_compare_custom(){
    global $product, $foodfarm_settings;
    $compare = (get_option('yith_woocompare_compare_button_in_products_list') == 'yes');
    ?>
    <?php
    if ($compare && $foodfarm_settings['product-compare']){
            printf('<div class="add-to"><a title="'.esc_html__("compare","foodfarm").'" onclick="" data-toggle="tooltip" href="%s" class="%s" data-product_id="%d"><i class="fa fa-random"></i></a></div>', foodfarm_add_compare_action($product->get_id()), 'add_to_compare compare button', $product->get_id(), esc_html__('Compare', 'foodfarm'));
        }
    ?>
    <?php
}   
function foodfarm_compare_toplink(){
    global $product;
    $compare = (get_option('yith_woocompare_compare_button_in_products_list') == 'yes');
    ?>
    <?php
    if ($compare){
            printf('<a title="'.esc_html__("compare","foodfarm").'" target="_blank" href="%s">'.esc_html__('Compare', 'foodfarm').'</a>', foodfarm_view_table_url());
        }
    ?>
    <?php
}
function foodfarm_add_compare_action($product_id) {
    $action = 'yith-woocompare-add-product';
    $url_args = array('action' => $action, 'id' => $product_id);
    return wp_nonce_url(add_query_arg($url_args), $action);
}
function foodfarm_view_table_url( $product_id = false ) {
    $action = 'yith-woocompare-view-table';
    $url_args = array(
        'action'    => $action,
        'iframe'    => true
    );
    return apply_filters( 'yith_woocompare_view_table_url', esc_url_raw( add_query_arg( $url_args, site_url() ) ), $product_id );
}
function foodfarm_woocommerce_single_excerpt() {
    global $post;

    if ( ! $post->post_excerpt ) {
        return;
    }
    ?>
    <div class="desc">
        <?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
    </div>
    <?php
}
//update cart items on minicart
function foodfarm_woocommerce_header_add_to_cart_fragment($fragments) {
    $_cartQty = WC()->cart->cart_contents_count;
    $_cartTotal = WC()->cart->get_cart_total();
    $fragments['.mini-cart .number-product'] = '<p class="number-product">' . $_cartQty . '</p>';
    $fragments['header .mini-cart + .woocommerce-Price-amount'] = '' . $_cartTotal . '';
    return $fragments;
}

// check for empty-cart get param to clear the cart
function woocommerce_clear_cart_url() {
    global $woocommerce;
    if (isset($_GET['empty-cart'])) {
        $woocommerce->cart->empty_cart();
    }
}
function foodfarm_product_shop_per_page() {
    global $foodfarm_settings;
    parse_str($_SERVER['QUERY_STRING'], $params);

    // replace it with theme option
    if ($foodfarm_settings['category-item']) {
        $per_page = explode(',', $foodfarm_settings['category-item']);
    } else {
        $per_page = explode(',', '12,24,36');
    }

    $item_count = !empty($params['count']) ? $params['count'] : $per_page[0];

    return $item_count;
}
function foodfarm_override_billing_fields( $fields ) {
  $fields['billing_first_name'] = array(
        'label' => esc_html__('First Name','foodfarm'),
        'placeholder' => _x('First Name *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['billing_last_name'] = array(
        'label' => esc_html__('Last Name','foodfarm'),
        'placeholder' => _x('Last Name *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['billing_company'] = array(
        'label' => esc_html__('Company name','foodfarm'),
        'placeholder' => _x('Company Name', 'placeholder', 'foodfarm'),
        'required' => false,
    );
  $fields['billing_email'] = array(
        'label' => esc_html__('Email','foodfarm'),
        'placeholder' => _x('E-mail *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['billing_phone'] = array(
        'label' => esc_html__('Phone','foodfarm'),
        'placeholder' => _x('Phone *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['billing_address_1'] = array(
        'label' => esc_html__('Address','foodfarm'),
        'placeholder' => _x('Address', 'placeholder', 'foodfarm'),
        'required' => false,
    );
  $fields['billing_address_2'] = array(
        'label' => esc_html__('Apartment, suite, unit etc. (optional)','foodfarm'),
        'placeholder' => _x('Apartment, suite, unit etc. (optional)', 'placeholder', 'foodfarm'),
        'required' => false,
    );
  $fields['billing_postcode'] = array(
        'label' => esc_html__('Postcode / Zip','foodfarm'),
        'placeholder' => _x('Postcode / Zip *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['billing_city'] = array(
        'label' => esc_html__('Town / City','foodfarm'),
        'placeholder' => _x('Town / City *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['billing_phone'] = array(
        'label' => esc_html__('Phone','foodfarm'),
        'placeholder' => _x('Phone *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['billing']['billing_state'] = array(
        'label' => esc_html__('State / County','foodfarm'),
        'placeholder' => _x('State / County', 'placeholder', 'foodfarm'),
        'required' => false,
    );
  return $fields;
}

function foodfarm_override_shipping_fields( $fields ) {
  $fields['shipping_first_name'] = array(
        'label' => esc_html__('First Name','foodfarm'),
        'placeholder' => _x('First Name *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['shipping_last_name'] = array(
        'label' => esc_html__('Last Name','foodfarm'),
        'placeholder' => _x('Last Name *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['shipping_company'] = array(
        'label' => esc_html__('Company name','foodfarm'),
        'placeholder' => _x('Company Name', 'placeholder', 'foodfarm'),
        'required' => false,
    );
  $fields['shipping_email'] = array(
        'label' => esc_html__('Email','foodfarm'),
        'placeholder' => _x('E-mail *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['shipping_phone'] = array(
        'label' => esc_html__('Phone','foodfarm'),
        'placeholder' => _x('Phone *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['shipping_address_1'] = array(
        'label' => esc_html__('Address','foodfarm'),
        'placeholder' => _x('Address *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['shipping_address_2'] = array(
        'label' => esc_html__('Apartment, suite, unit etc. (optional)','foodfarm'),
        'placeholder' => _x('Apartment, suite, unit etc. (optional)', 'placeholder', 'foodfarm'),
        'required' => false,
    );
  $fields['shipping_postcode'] = array(
        'label' => esc_html__('Postcode / Zip','foodfarm'),
        'placeholder' => _x('Postcode / Zip *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['shipping_city'] = array(
        'label' => esc_html__('Town / City','foodfarm'),
        'placeholder' => _x('Town / City *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
  $fields['shipping_phone'] = array(
        'label' => esc_html__('Phone','foodfarm'),
        'placeholder' => _x('Phone *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['shipping_state'] = array(
        'label' => esc_html__('State / County ','foodfarm'),
        'placeholder' => _x('State / County ', 'placeholder', 'foodfarm'),
        'required' => false,
    );
  return $fields;
}
function foodfarm_custom_override_checkout_fields($fields) {

    $fields['billing']['billing_first_name'] = array(
        'label' => esc_html__('First Name','foodfarm'),
        'placeholder' => _x('First Name *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['billing']['billing_last_name'] = array(
        'label' => esc_html__('Last Name','foodfarm'),
        'placeholder' => _x('Last Name *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['billing']['billing_company'] = array(
        'label' => '',
        'placeholder' => _x('Company Name', 'placeholder', 'foodfarm'),
        'required' => false,
        'class'     => array('form-row-wide'),
    );
    $fields['billing']['billing_address_1'] = array(
        'label' => '',
        'placeholder' => _x('Address', 'placeholder', 'foodfarm'),
        'required' => false,
        'class'     => array('form-row-wide'),
    );
    $fields['billing']['billing_address_2'] = array(
        'label' => '',
        'placeholder' => _x('Enter Your Apartment', 'placeholder', 'foodfarm'),
        'required' => false,
    );
    $fields['billing']['billing_city'] = array(
        'label' => esc_html__('City','foodfarm'),
        'placeholder' => _x('City *', 'placeholder', 'foodfarm'),
        'required' => true,
    );

    $fields['billing']['billing_email'] = array(
        'label' => esc_html__('Email Address','foodfarm'),
        'placeholder' => _x('E-mail *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['billing']['billing_phone'] = array(
        'label' => esc_html__('Phone','foodfarm'),
        'placeholder' => _x('Phone *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['billing']['billing_state'] = array(
        'label' => esc_html__('State / County','foodfarm'),
        'placeholder' => _x('State / County', 'placeholder', 'foodfarm'),
        'required' => false,
    );
    $fields['shipping']['shipping_phone'] = array(
        'label' => esc_html__('Phone','foodfarm'),
        'placeholder'   => _x('Phone Number *', 'placeholder', 'foodfarm'),
        'required'  => true,
     );
    $fields['shipping']['shipping_first_name'] = array(
        'label' => esc_html__('First Name','foodfarm'),
        'placeholder' => _x('First Name *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['shipping']['shipping_last_name'] = array(
        'label' => esc_html__('Last Name','foodfarm'),
        'placeholder' => _x('Last Name *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['shipping']['shipping_company'] = array(
        'label' => esc_html__('Company Name','foodfarm'),
        'placeholder' => _x('Company Name', 'placeholder', 'foodfarm'),
        'required' => false,
        'class'     => array('form-row-wide'),
    );
    $fields['shipping']['shipping_city'] = array(
        'label' => esc_html__('City','foodfarm'),
        'placeholder' => _x('City *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['shipping']['shipping_state'] = array(
        'label' => esc_html__('Enter State/Country','foodfarm'),
        'placeholder' => _x('Enter State/Country *', 'placeholder', 'foodfarm'),
        'required' => false,
    );
    $fields['shipping']['shipping_email'] = array(
        'label' => esc_html__('Email Address','foodfarm'),
        'placeholder' => _x('E-mail *', 'placeholder', 'foodfarm'),
        'required' => true,
    );
    $fields['shipping']['shipping_address_1'] = array(
        'label' => esc_html__('Adress','foodfarm'),
        'placeholder' => _x('Address *', 'placeholder', 'foodfarm'),
        'required' => true,
        'class'     => array('form-row-wide'),
    );
    $fields['order']['order_comments'] = array(
        'label' => esc_html__('Order notes','foodfarm'),
        'placeholder' => _x('Order Notes', 'placeholder', 'foodfarm'),
        'required' => false,
        'type' => 'textarea',
        'class'     => array('form-row-wide'),
    );
    

    return $fields;
}
//change text sort by
add_filter( 'gettext', 'foodfarm_sort_change', 20, 3 );
function foodfarm_sort_change( $translated_text, $text, $domain ) {

    if ( is_woocommerce() ) {

        switch ( $translated_text ) {
            case 'Sort by popularity' :

                $translated_text = esc_html__( 'Popularity', 'foodfarm' );
                break;
            case 'Sort by average rating' :

                $translated_text = esc_html__( 'Average rating', 'foodfarm' );
                break;    
            case 'Sort by newness' :

                $translated_text = esc_html__( 'Newest', 'foodfarm' );
                break;
            case 'Sort by price: low to high' :

                $translated_text = esc_html__( 'Low to high', 'foodfarm' );
                break;    
            case 'Sort by price: high to low' :

                $translated_text = esc_html__( 'High to low', 'foodfarm' );
                break;    
        }

    }

    return $translated_text;
} 
