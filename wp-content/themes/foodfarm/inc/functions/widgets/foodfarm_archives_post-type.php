<?php
add_action( 'widgets_init', create_function( '', 'register_widget( "Foodfarm_CustomType_Archive_Widget" );' ) );
class Foodfarm_CustomType_Archive_Widget extends WP_Widget
{
    function __construct() 
    {
        /* Widget settings. */
        $widget_ops = array("description" => "Display an archive listing of one specific custom-type.");

        /* Create the widget. */
        parent::__construct("wp-customtype-archive", "Custom Type Archive", $widget_ops);
    }

    function create_url($interval, $year, $month, $customtype)
    {
        global $wp_rewrite;
        $year_month_value = $year . $month;
        $year_month_path = $year . "/" . $month;
        if ($interval == "year") {
            $year_month_value = $year;
            $year_month_path = $year . "/00";
        }

        $url = home_url() . "/?m=" . $year_month_value . "&post_type=" . $customtype; 
        return ($url);
    }

    /**
     * Display widget.
     */
    function widget($args, $instance) 
    {
        extract($args);

        /* User-selected settings. */
        //$title = apply_filters('widget_title', $instance['title'] );
        $title = esc_attr($instance['title']);
    $customtype = esc_attr($instance['customtype']);
        $display_style = $instance['display_style'];
        $interval = $instance['interval'];
        $show_counts = intval($instance['show_counts']);
        echo $before_widget;
        /* Title of widget (before and after defined by themes). */
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        // TBD: could change to call sql statement that uses group by
        $myposts = get_posts("numberposts=-1&offset=0&post_type=" . $customtype . "&orderby=date&order=DESC");
        $previous_year_month_display = "";
        $previous_year_month_value = "";        
        $previous_year = "";
        $previous_month = "";
        $count = 0;

        $display_format = "F Y";
        $compare_format = "Ym";
        $select_str = esc_html__("Select Month", "foodfarm");
        if ($interval == "year") {
            $display_format = "Y";
            $compare_format = "Y";
            $select_str = esc_html__("Select Year", "foodfarm");
        }
        
        if ($display_style == "pulldown") {
            echo "<select name=\"wp-customtype-archive-dropdown\" onchange=\"document.location.href=this.options[this.selectedIndex].value;\">";
            echo " <option value=\"\">" . $select_str . "</option>";
        } else if ($display_style == "list") {
            echo "<ul class='widget_categories_list'>";
        }

        foreach($myposts as $post) {
            $post_date = strtotime($post->post_date);
            $current_year_month_display = date_i18n($display_format, $post_date);
            $current_year_month_value = date($compare_format, $post_date);
            $current_year = date("Y", $post_date);
            $current_month = date("m", $post_date);
            if ($previous_year_month_value != $current_year_month_value) {
                if ($count > 0) {
                    $url = $this->create_url($interval, $previous_year, $previous_month, $customtype);
                    if ($display_style == "pulldown") { 
                        echo " <option value=\"" . $url . "\"";
                        if ($_GET['m'] == $previous_year_month_value) {
                            echo " selected=\"selected\" ";
                        }
                        echo ">" . $previous_year_month_display;
                        if ($show_counts == 1) {
                            echo  " (" . $count . ")";
                        }
                        echo "</option>";
                    } else if ($display_style == "list") {
                        echo "<li><a href=\"". $url . "\">" . $previous_year_month_display . esc_html__(' Releases', 'foodfarm').
                        "</a>";
                        if ($show_counts == 1) {
                            echo  " (" . $count . ")";
                        }
                        echo "</li>";
                    } else {                        
                        echo "<a href=\"". $url . "\">" . $previous_year_month_display .esc_html__(' Releases', 'foodfarm'). "</a>";
                        if ($show_counts == 1) {
                            echo  " (" . $count . ")";
                        }
                        echo "<br/>";
                    }
                }
                $count = 0;
            }
            $count++;
            $previous_year_month_display = $current_year_month_display;
            $previous_year_month_value = $current_year_month_value;
            $previous_year = $current_year;
            $previous_month = $current_month;

        }
        if ($count > 0) {
            $url = $this->create_url($interval, $previous_year, $previous_month, $customtype);
            if ($display_style == "pulldown") { 
                echo " <option value=\"" . $url . "\">" . $previous_year_month_display;
                if ($show_counts == 1) {
                    echo " (" . $count . ")";
                }
                echo "</option>";
            } else if ($display_style == "list") { 
                echo "<li><a href=\"". $url . "\">" . $previous_year_month_display .esc_html__(' Releases', 'foodfarm'). "</a>";
                if ($show_counts == 1) {
                    echo " (" . $count . ")";
                }
                echo "</li>";
            } else {                        
                echo "<a href=\"". $url . "\">" . $previous_year_month_display .esc_html__(' Releases', 'foodfarm'). "</a>";
                if ($show_counts == 1) {
                    echo " (" . $count . ")";
                }
                echo "<br/>";
            }
        }
        if ($display_style == "pulldown") {
            echo "</select>";
        } else if ($display_style == "list") {
            echo "</ul>";
        }

        
        echo $after_widget;
    }

    /**
     * Called when widget control form is posted.
     */
    function update( $new_instance, $old_instance ) 
    {
        // global $post;
        if (!isset($new_instance['submit'])) {
            return false;
        }

        $instance = $old_instance;

        /* Strip tags (if needed) and update the widget settings. */
        $instance["title"] = strip_tags($new_instance["title"]);
        $instance["customtype"] = strip_tags($new_instance["customtype"]);
        $instance["display_style"] = strip_tags($new_instance["display_style"]);
        $instance["interval"] = strip_tags($new_instance["interval"]);
        $instance["show_counts"] = intval($new_instance["show_counts"]);
        return $instance;
    }


    /**
     * Display widget control form.
     *  Title:
     *  Custom Type: 
     *  Display Style:    Lines | (List) | Pulldown
     *  Group By:         (Month) | Year
     *  Show Post Counts: Yes | (No)
     */
    function form( $instance ) 
    {
        global $wpdb;
        /* Set up some default widget settings. */
        $defaults = array( "title" => "Archive", "customtype" => "", "display_style" => "list", "interval" => "month", "show_counts" => 1 );
        $instance = wp_parse_args( (array) $instance, $defaults ); 
        $title = esc_attr($instance['title']);
        $customtype = esc_attr($instance['customtype']);
        $display_style = $instance['display_style'];
        $interval = $instance['interval'];
        $show_counts = intval($instance['show_counts']);
        ?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'foodfarm' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p>
            <label for="<?php echo $this->get_field_id('customtype'); ?>">
                <strong><?php echo esc_html__('Product Type', 'foodfarm') ?>:</strong>
                <select class="widefat" id="<?php echo $this->get_field_id('customtype'); ?>" name="<?php echo $this->get_field_name('customtype'); ?>">
                    <option value="post"<?php echo ($instance['customtype'] == 'post')? ' selected="selected"' : '' ?>><?php echo esc_html__('post', 'foodfarm') ?></option>
                    <option value="gallery"<?php echo ($instance['customtype'] == 'gallery')? ' selected="selected"' : '' ?>><?php echo esc_html__('gallery', 'foodfarm') ?></option>
                    <option value="pressmedia"<?php echo ($instance['customtype'] == 'pressmedia')? ' selected="selected"' : '' ?>><?php echo esc_html__('pressmedia', 'foodfarm') ?></option>
                    <option value="recipe"<?php echo ($instance['customtype'] == 'recipe')? ' selected="selected"' : '' ?>><?php echo esc_html__('recipe', 'foodfarm') ?></option>
                </select>
            </label>
        </p>
        <?php

        // Display Style: Lines, List or Pulldown
        echo "<p>";
        echo "<label for=\"" . $this->get_field_id("display_style") . "\">Display Style:<br/>";        
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("display_style") . "\" value=\"lines\"";
        if ($display_style == "lines") {
            echo " checked=\"checked\" ";
        }
        echo "> Lines ";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("display_style") . "\" value=\"list\"";
        if ($display_style == "list") {
            echo " checked=\"checked\" ";
        }
        echo "> List ";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("display_style") . "\" value=\"pulldown\"";
        if ($display_style == "pulldown") {
            echo " checked=\"checked\" ";
        }
        echo "> Pulldown";
        echo "</label></p>";

        // Interval: Month or Year
        echo "<p>";
        echo "<label for=\"" . $this->get_field_id("interval") . "\">Group By:<br/>";        
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("interval") . "\" value=\"month\"";
        if ($interval != "year") {
            echo " checked=\"checked\" ";
        }
        echo "> Month ";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("interval") . "\" value=\"year\"";
        if ($interval == "year") {
            echo " checked=\"checked\" ";
        }
        echo "> Year";
        echo "</label></p>";

        // Show Counts: Yes or No
        echo "<p>";
        echo "<label for=\"" . $this->get_field_id("show_counts") . "\">Show Post Counts:<br/>";        
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("show_counts") . "\" value=\"1\"";
        if ($show_counts == 1) {
            echo " checked=\"checked\" ";
        }
        echo "> Yes ";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("show_counts") . "\" value=\"0\"";
        if ($show_counts != 0) {
            echo " checked=\"checked\" ";
        }
        echo "> No";
        echo "</label></p>";



        // Submit (hidden field)
        echo "<input type=\"hidden\" id=\"" . $this->get_field_id("submit") ."\" name=\"" . 
             $this->get_field_name("submit") . "\" value=\"1\" />";
    }

    /**
     * Register widget.
     */
    function register()
    {
        register_widget("Foodfarm_CustomType_Archive_Widget");
    }
    
}

?>
