<?php
/*===============
    KBE Category Widget
 ===============*/
 
//========= Custom Knowledgebase Category Widget
add_action( 'widgets_init', 'foodfarm_category_widgets' );
function foodfarm_category_widgets() {
    register_widget( 'foodfarm_Cat_Widget' );
}

//========= Custom Knowledgebase Category Widget Body
class foodfarm_Cat_Widget extends WP_Widget {
	
    function __construct() {
        parent::__construct(
            'kbe_category_widget', // Base ID
            esc_html__( 'Knowledgebase Category', 'foodfarm' ), // Name
            array( 'description' => esc_html__('WP Knowledgebase category widget to show categories on the site', 'foodfarm'), 
                    'classname' => 'foodfarm' ), // Args
            array( 'width' => 300, 'height' => 350, 'id_base' => 'kbe_category_widget' )
        );
    }
	
     //=======> How to display the widget on the screen.
    function widget($args, $widgetData) {
        extract($args);
		
        //Our variables from the widget settings.
        $kbe_widget_cat_title = $widgetData['txtKbeCatHeading'];
        $kbe_widget_cat_count = $widgetData['txtKbeCatCount'];
		
        //=======> widget body
        echo $before_widget;
        echo '<div class="kbe_widgets">';
        
            if ($kbe_widget_cat_title){
                //convert square brackets to angle brackets
                $kbe_widget_cat_title = str_replace('[', '<', $kbe_widget_cat_title);
                $kbe_widget_cat_title = str_replace(']', '>', $kbe_widget_cat_title);

                //strip tags other than the allowed set
                $kbe_widget_cat_title = strip_tags($kbe_widget_cat_title, '<a><blink><br><span>');
                echo '<h3 class="widget-title">'.$kbe_widget_cat_title.'</h3>';
            }
			
            $kbe_cat_args = array(
                'number' 	=>  $kbe_widget_cat_count,
                'taxonomy'	=>  'kbe_taxonomy',
                'orderby'   =>  'terms_order',
                'order'     =>  'ASC',
                'show_count'=>  1,
                'hierarchical' => 1,
                'title_li'     => ''
                
            );
			
            $kbe_cats = get_categories($kbe_cat_args);
            echo '<ul class="widget_categories_list">';
            wp_list_categories( $kbe_cat_args );
            echo '</ul>';
        
        echo "</div>";
        echo $after_widget;
    }
	
    //Update the widget 
    function update($new_widgetData, $old_widgetData) {
        $widgetData = $old_widgetData;
		
        //Strip tags from title and name to remove HTML 
        $widgetData['txtKbeCatHeading'] = $new_widgetData['txtKbeCatHeading'];
        $widgetData['txtKbeCatCount'] = $new_widgetData['txtKbeCatCount'];
		
        return $widgetData;
    }
    function form($widgetData) {
        
        $defaults = array(
            'txtKbeCatHeading' => esc_html__('Categories', 'foodfarm'), 
            'txtKbeCatCount' => '', 
        );
        $widgetData = wp_parse_args((array) $widgetData, $defaults);
?>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeCatHeading'); ?>"><?php echo esc_html__('Category Title:','foodfarm') ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeCatHeading'); ?>" name="<?php echo $this->get_field_name('txtKbeCatHeading'); ?>" value="<?php echo $widgetData['txtKbeCatHeading']; ?>" style="width:275px;" />
        </p>    
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeCatCount'); ?>"><?php echo esc_html__('Catgory Quantity:','foodfarm'); ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeCatCount'); ?>" name="<?php echo $this->get_field_name('txtKbeCatCount'); ?>" value="<?php echo $widgetData['txtKbeCatCount']; ?>" style="width:275px;" />
        </p>
<?php
    }
}
?>