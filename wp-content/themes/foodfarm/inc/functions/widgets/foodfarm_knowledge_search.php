<?php
/*===============
    KBE Search Articles Widget
 ===============*/
 
//========= Custom Knowledgebase Search Widget
add_action( 'widgets_init', 'foodfarm_search_widgets' );
function foodfarm_search_widgets() {
    register_widget( 'foodfarm_Search_Widget' );
}

//========= Custom Knowledgebase Search Widget Body
class foodfarm_Search_Widget extends WP_Widget {
    
    //=======> Widget setup
    function __construct() {
        parent::__construct(
            'kbe_search_widget', // Base ID
            esc_html__( 'Knowledgebase Search', 'foodfarm' ), // Name
            array( 'description' => esc_html__('WP Knowledgebase search widget', 'foodfarm'), 
                  'classname' => 'widget_search' ),
            array( 'width' => 300, 'height' => 350, 'id_base' => 'kbe_search_widget' ) // Args
        );
    }
        
  //=======> How to display the widget on the screen.
    function widget($args, $widgetData) {
        extract($args);
        
        //=======> Our variables from the widget settings.
        $kbe_widget_search_title = $widgetData['txtKbeSearchHeading'];
        
        //=======> widget body
        echo $before_widget;
        echo '<div class="kbe_widgets">';
        
        if($kbe_widget_search_title){
            //convert square brackets to angle brackets
            $kbe_widget_search_title = str_replace('[', '<', $kbe_widget_search_title);
            $kbe_widget_search_title = str_replace(']', '>', $kbe_widget_search_title);

            //strip tags other than the allowed set
            $kbe_widget_search_title = strip_tags($kbe_widget_search_title, '<a><blink><br><span>');            
            echo '<h3 class="widget-title">'.$kbe_widget_search_title.'</h3>';
        }
    ?>
        <form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">
            <input type="text" onfocus="if (this.value == '<?php echo esc_html__("Search Articles...", "foodfarm") ?>') {this.value = '';}" onblur="if (this.value == '')  {this.value = '<?php echo esc_html__("Search Articles...", "foodfarm") ?>';}" value="<?php echo esc_html__("Search Articles...", "foodfarm") ?>" name="s" id="s" placeholder="Enter the keywords"/>
            <input type="hidden" name="post_type" value="kbe_knowledgebase" />
        </form>
    <?php
        echo "</div>";
        echo $after_widget;
    }
    
    //Update the widget 
    function update($new_widgetData, $old_widgetData) {
        $widgetData = $old_widgetData;
        //Strip tags from title and name to remove HTML 
        $widgetData['txtKbeSearchHeading'] = $new_widgetData['txtKbeSearchHeading'];
        return $widgetData;
    }
    
    function form($widgetData) {
        //Set up some default widget settings.
        $defaults = array(
            'txtKbeSearchHeading' => '', 
        );
        $widgetData = wp_parse_args((array) $widgetData, $defaults);

?>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeSearchHeading'); ?>"><?php echo esc_html__('Search Title:','foodfarm'); ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeSearchHeading'); ?>" name="<?php echo $this->get_field_name('txtKbeSearchHeading'); ?>" value="<?php echo $widgetData['txtKbeSearchHeading']; ?>" style="width:275px;" />
        </p>
<?php
    }
}
?>