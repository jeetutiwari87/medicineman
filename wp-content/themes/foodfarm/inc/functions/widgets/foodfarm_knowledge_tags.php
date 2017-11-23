<?php
/*===============
    KBE Tags Widget
 ===============*/
 
//========= Custom Knowledgebase Tags Widget
add_action( 'widgets_init', 'foodfarm_tags_widgets' );
function foodfarm_tags_widgets() {
    register_widget( 'foodfarm_Tags_Widget' );
}

//========= Custom Knowledgebase Tags Widget Body
class foodfarm_Tags_Widget extends WP_Widget {
    
    function __construct() {
        parent::__construct(
            'kbe_tags_widgets', // Base ID
            esc_html__( 'Knowledgebase Tags', 'foodfarm' ), // Name
            array( 'description' => esc_html__('WP Knowledgebase tags widget to show tags on the site', 'foodfarm'), 
                   'classname' => 'widget_tag_cloud' ),
            array( 'width' => 300, 'height' => 350, 'id_base' => 'kbe_tags_widgets' ) // Args
        );
    }
	
	//=======> How to display the widget on the screen.
    function widget($args, $widgetData) {
        extract($args);
        
        //=======> Our variables from the widget settings.
        $kbe_widget_tag_title = $widgetData['txtKbeTagsHeading'];
        $kbe_widget_tag_count = $widgetData['txtKbeTagsCount'];
        $kbe_widget_tag_style = $widgetData['txtKbeTagsStyle'];
        
        //=======> widget body
        echo $before_widget;
        echo '<div class="kbe_widget kbe_widget_article">';
        
                if($kbe_widget_tag_title){
                    //convert square brackets to angle brackets
                    $kbe_widget_tag_title = str_replace('[', '<', $kbe_widget_tag_title);
                    $kbe_widget_tag_title = str_replace(']', '>', $kbe_widget_tag_title);

                    //strip tags other than the allowed set
                    $kbe_widget_tag_title = strip_tags($kbe_widget_tag_title, '<a><blink><br><span>');                    
                    echo '<h3 class="widget-title">'.$kbe_widget_tag_title.'</h3>';
                }
        ?>
                <div class="widget_tag_cloud">
                <?php
                    $args = array(
                                'smallest'                  => 	12,
                                'largest'                   => 	30,
                                'unit'                      => 	'px',
                                'number'                    => 	$kbe_widget_tag_count,
                                'format'                    => 	$kbe_widget_tag_style,
                                'separator'                 => 	"\n",
                                'orderby'                   => 	'name',
                                'order'                     => 	'ASC',
                                'exclude'                   => 	null,
                                'include'                   => 	null,
                                'link'                      => 	'view',
                                'taxonomy'                  => 	KBE_POST_TAGS,
                                'echo'                      => 	true
                    );
						
                    wp_tag_cloud($args);
					
                    wp_reset_query();
		?>
                </div>
<?php      
        echo "</div>";
        echo $after_widget;
    }
    
    //Update the widget 
    function update($new_widgetData, $old_widgetData) {
        $widgetData = $old_widgetData;
		
        //Strip tags from title and name to remove HTML 
        $widgetData['txtKbeTagsHeading'] = $new_widgetData['txtKbeTagsHeading'];
        $widgetData['txtKbeTagsCount'] = $new_widgetData['txtKbeTagsCount'];
        $widgetData['txtKbeTagsStyle'] = $new_widgetData['txtKbeTagsStyle'];
		
        return $widgetData;
    }
    
    function form($widgetData) {
        //Set up some default widget settings.
        $defaults = array(
            'txtKbeTagsHeading' => esc_html__('Tag', 'foodfarm'), 
            'txtKbeTagsCount' => 6, 
            'txtKbeTagsStyle' => 'flat', 
            );
        $widgetData = wp_parse_args((array) $widgetData, $defaults);
?>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeTagsHeading'); ?>"><?php echo esc_html__('Tag Title:','foodfarm'); ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeTagsHeading'); ?>" name="<?php echo $this->get_field_name('txtKbeTagsHeading'); ?>" value="<?php echo $widgetData['txtKbeTagsHeading']; ?>" style="width:275px;" />
        </p>    
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeTagsCount'); ?>"><?php echo esc_html__('Tags Quantity:','foodfarm'); ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeTagsCount'); ?>" name="<?php echo $this->get_field_name('txtKbeTagsCount'); ?>" value="<?php echo $widgetData['txtKbeTagsCount']; ?>" style="width:275px;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeTagsStyle'); ?>"><?php echo esc_html__('Tags Style:','foodfarm'); ?></label>
            <select id="<?php echo $this->get_field_id('txtKbeTagsStyle'); ?>" name="<?php echo $this->get_field_name('txtKbeTagsStyle'); ?>">
                <option <?php selected($widgetData['txtKbeTagsStyle'], 'flat') ?> value="flat"><?php echo esc_html__('Flat','foodfarm'); ?></option>
                <option <?php selected($widgetData['txtKbeTagsStyle'], 'list') ?> value="list"><?php echo esc_html__('List','foodfarm'); ?></option>
            </select>
        </p>
<?php
    }
}
?>