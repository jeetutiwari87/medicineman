<?php 
add_action('widgets_init', 'foodfarm_product_load_widgets');
function foodfarm_product_load_widgets()
{
    register_widget('Foodfarm_Product_Widget');
}

class Foodfarm_Product_Widget extends WP_Widget {
	function __construct(){
        parent::__construct (
	      'foodfarm_product_widget', 
	      'Custom Foodfarm Product', 
	      array(
	      	'classname' => 'woocommerce widget_products',
	        'description' => 'Foodfarm Product Widget' 
	      )
	    );
    }
    function form($instance){
    	$defaults = array(
    	 'title' => esc_html__('Product', 'foodfarm'),
    	 'number' => 3, 
    	 'show' => 'recent', 
		);
        $instance = wp_parse_args((array) $instance, $defaults); ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <strong><?php echo esc_html__('Title', 'foodfarm') ?>:</strong>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset($instance['title'])) echo $instance['title']; ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>">
                <strong><?php echo esc_html__('Number of posts to show', 'foodfarm') ?>:</strong>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php if (isset($instance['number'])) echo $instance['number']; ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show'); ?>">
                <strong><?php echo esc_html__('Product Type', 'foodfarm') ?>:</strong>
                <select class="widefat" id="<?php echo $this->get_field_id('show'); ?>" name="<?php echo $this->get_field_name('show'); ?>">
                    <option value="recent"<?php echo ($instance['show'] == 'recent')? ' selected="selected"' : '' ?>><?php echo esc_html__('Recent', 'foodfarm') ?></option>
                    <option value="related"<?php echo ($instance['show'] == 'related')? ' selected="selected"' : '' ?>><?php echo esc_html__('Related', 'foodfarm') ?></option>
                    <option value="featured"<?php echo ($instance['show'] == 'featured')? ' selected="selected"' : '' ?>><?php echo esc_html__('Featured', 'foodfarm') ?></option>
                    <option value="on_sale"<?php echo ($instance['show'] == 'on_sale')? ' selected="selected"' : '' ?>><?php echo esc_html__('Special', 'foodfarm') ?></option>
                    <option value="top_rated"<?php echo ($instance['show'] == 'top_rated')? ' selected="selected"' : '' ?>><?php echo esc_html__('Top Rate', 'foodfarm') ?></option>
                    <option value="best_selling"<?php echo ($instance['show'] == 'best_selling')? ' selected="selected"' : '' ?>><?php echo esc_html__('Best Sellers', 'foodfarm') ?>
                    </option>
                </select>
            </label>
        </p>
        <?php
    }
    function update($new_instance, $old_instance){
    	$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = $new_instance['number'];
        $instance['show'] = $new_instance['show'];

        return $instance;
    }
    function widget($args, $instance){
    	extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $number = $instance['number'];
        $show = $instance['show'];

        global $woocommerce, $woocommerce_loop;
        $meta_query[] = array(
		    'key' => '_featured',
		    'value' => 'yes'
		);

		if ($show == 'recent') {
		    $meta_query = WC()->query->get_meta_query();
		}
		if ($show == 'featured') {
		    $meta_query = array(
		        array(
		            'key' => '_visibility',
		            'value' => array('catalog', 'visible'),
		            'compare' => 'IN'
		        ),
		        array(
		            'key' => '_featured',
		            'value' => 'yes'
		        )
		    );
		}
		if ($show == 'top_rated') {
		    add_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses'));
		    $meta_query = WC()->query->get_meta_query();
		}

		$args = array(
		    'post_type' => 'product',
		    'post_status' => 'publish',
		    'ignore_sticky_posts' => 1,
		    'posts_per_page' => $number,
		    'orderby' => 'DESC',
		    'order' => 'date',
		    'meta_query' => $meta_query
		);

		if ($show == "on_sale") {
		    $product_ids_on_sale = woocommerce_get_product_ids_on_sale();
		    $meta_query = array();
		    $meta_query[] = $woocommerce->query->visibility_meta_query();
		    $meta_query[] = $woocommerce->query->stock_status_meta_query();
		    $args['meta_query'] = $meta_query;
		    $args['post__in'] = $product_ids_on_sale;
		}
		if ($show == "best_selling") {
		    $args['meta_key'] = 'total_sales';
		    $args['orderby'] = 'meta_value_num';
		    $args['meta_query'] = array(
		        array(
		            'key' => '_visibility',
		            'value' => array('catalog', 'visible'),
		            'compare' => 'IN'
		        )
		    );
		}
		if ($show == 'related') {
			global $product;
			$related = $product->get_related( $number );
			$args['no_found_rows'] = 1;
			$args['post__in']             = $related;
			$args['post__not_in']         = array( $product->get_id() );
		}
		$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, array('per_page' => $number, 'orderby' => 'date', 'order' => 'desc')));
		if ($products->have_posts()) :

            echo $before_widget;
            ?>	
            <?php if($title) :?>
            <h3 class="widget-title"><?php echo $title;?></h3>      
			<?php endif;?>
                <ul>
		            <?php while ($products->have_posts()) : $products->the_post(); ?>
		         
                        <?php wc_get_template_part( 'content', 'widget-product' ); ?>
                        
		            <?php endwhile; ?>
                </ul>
    	<?php echo $after_widget;

        endif;
        wp_reset_postdata();	

    }
}
?>