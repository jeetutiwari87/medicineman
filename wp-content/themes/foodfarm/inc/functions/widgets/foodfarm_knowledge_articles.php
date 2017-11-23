<?php
/*===============
    KBE Articles Widget
 ===============*/
 
//========= Custom Knowledgebase Article Widget
add_action( 'widgets_init', 'foodfarm_article_widgets' );
function foodfarm_article_widgets() {
    register_widget( 'foodfarm_Article_Widget' );
}

//========= Custom Knowledgebase Article Widget Body
class foodfarm_Article_Widget extends WP_Widget {
    
    //=======> Widget setup
    function __construct() {
        parent::__construct(
            'kbe_article_widgets', // Base ID
            esc_html__( 'Knowledgebase Article', 'foodfarm' ), // Name
            array( 'description' => esc_html__('WP Knowledgebase article widget to show articles on the site', 'foodfarm'), 
                    'classname' => 'widget_post_blog' ), // Args
            array( 'width' => 300, 'height' => 300, 'id_base' => 'kbe_article_widgets' )
        );
    }
    
    //=======> How to display the widget on the screen.
    function widget($args, $widgetData) {
        extract($args);
        
        //=======> Our variables from the widget settings.
        $kbe_widget_article_title = $widgetData['txtKbeArticleHeading'];
        $kbe_widget_article_count = $widgetData['txtKbeArticleCount'];
        $kbe_widget_article_order = $widgetData['txtKbeArticleOrder'];
        $kbe_widget_article_orderby = $widgetData['txtKbeArticleOrderBy'];

        //=======> widget body
        echo $before_widget;
        echo '<div class="kbe_widgets">';
        
                if($kbe_widget_article_title){
                    //convert square brackets to angle brackets
                    $kbe_widget_article_title = str_replace('[', '<', $kbe_widget_article_title);
                    $kbe_widget_article_title = str_replace(']', '>', $kbe_widget_article_title);

                    //strip tags other than the allowed set
                    $kbe_widget_article_title = strip_tags($kbe_widget_article_title, '<a><blink><br><span>');
                    echo '<h3 class="widget-title">'.$kbe_widget_article_title.'</h3>';
                }
                
                if($kbe_widget_article_orderby == 'popularity'){
                    $kbe_widget_article_args = array( 
                        'posts_per_page' => $kbe_widget_article_count, 
                        'post_type'  => 'kbe_knowledgebase',
                        'orderby' => 'meta_value_num',
                        'order'	=>	$kbe_widget_article_order,
                        'meta_key' => 'kbe_post_views_count'
                    );
                }
                else{
                    $kbe_widget_article_args = array(
                        'post_type' => 'kbe_knowledgebase',
                        'posts_per_page' => $kbe_widget_article_count,
                        'order' => $kbe_widget_article_order,
                        'orderby' => $kbe_widget_article_orderby
                   );
                }
                
                $kbe_widget_articles = new WP_Query($kbe_widget_article_args);
                if($kbe_widget_articles->have_posts()) :
            ?>
                <ul class="blog-content">
            <?php
                    while($kbe_widget_articles->have_posts()) :
                        $kbe_widget_articles->the_post();
            ?>
                        <li class="blog-item">
                            <?php if (has_post_thumbnail()): ?>
                                <?php $blogImages = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()) ); ?>
                                <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>" title="<?php esc_attr(the_title_attribute()) ?>"><img width="82" height="82" alt="" src="<?php echo esc_url($blogImages[0]); ?>"></a>                     
                                </div>
                            <?php endif;?>          
                            <div class="blog-post-info">
                                <div class="blog-post-title">
                                    <div class="post-name">
                                        <a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
                                    </div>
                                    <div class="blog-info"> 
                                        <div class="info">
                                            <p><?php echo get_the_date('d M, Y'); ?></p>
                                        </div>                  
                                        <div class="info info-comment">
                                            <i class="fa fa-comments-o"></i> <?php comments_popup_link(esc_html__('0 comment', 'foodfarm'), esc_html__('1 comment', 'foodfarm'), esc_html__('% comments', 'foodfarm')); ?>                
                                        </div>              
                                    </div>   
                                </div>                   
                            </div>
                        </li>
            <?php
                    endwhile;
            ?>
                </ul>
            <?php
                endif;
                
                wp_reset_query();
                
        echo "</div>";
        echo $after_widget;
    }
    
    //Update the widget 
    function update($new_widgetData, $old_widgetData) {
        $widgetData = $old_widgetData;
		
        //Strip tags from title and name to remove HTML 
        $widgetData['txtKbeArticleHeading'] = $new_widgetData['txtKbeArticleHeading'];
        $widgetData['txtKbeArticleCount'] = $new_widgetData['txtKbeArticleCount'];
        $widgetData['txtKbeArticleOrder'] = $new_widgetData['txtKbeArticleOrder'];
        $widgetData['txtKbeArticleOrderBy'] = $new_widgetData['txtKbeArticleOrderBy'];
		
        return $widgetData;
    }
    
    function form($widgetData) {
        //Set up some default widget settings.
        $defaults = array(
            'txtKbeArticleHeading' => '', 
            'txtKbeArticleCount' => '', 
            'txtKbeArticleOrder' => 'ASC', 
            'txtKbeArticleOrderBy' => 'name',
        );
        $widgetData = wp_parse_args((array) $widgetData, $defaults);
?>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeArticleHeading'); ?>"><?php echo esc_html__('Article Title:','foodfarm'); ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeArticleHeading'); ?>" name="<?php echo $this->get_field_name('txtKbeArticleHeading'); ?>" value="<?php echo $widgetData['txtKbeArticleHeading']; ?>" style="width:275px;" />
        </p>    
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeArticleCount'); ?>"><?php echo esc_html__('Articles Quantity:','foodfarm') ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeArticleCount'); ?>" name="<?php echo $this->get_field_name('txtKbeArticleCount'); ?>" value="<?php echo $widgetData['txtKbeArticleCount']; ?>" style="width:275px;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeArticleOrder'); ?>"><?php echo esc_html__('Articles Order:','foodfarm') ?></label>
            <select id="<?php echo $this->get_field_id('txtKbeArticleOrder'); ?>" name="<?php echo $this->get_field_name('txtKbeArticleOrder'); ?>">
                <option <?php selected($widgetData['txtKbeArticleOrder'], 'ASC') ?> value="ASC"><?php echo esc_html__('ASC','foodfarm'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrder'], 'DESC') ?> value="DESC"><?php echo esc_html__('DESC','foodfarm'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeArticleOrderBy'); ?>"><?php echo esc_html__('Articles Order by:','foodfarm') ?></label>
            <select id="<?php echo $this->get_field_id('txtKbeArticleOrderBy'); ?>" name="<?php echo $this->get_field_name('txtKbeArticleOrderBy'); ?>">
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'name') ?> value="name"><?php echo esc_html__('By Name','foodfarm'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'date') ?> value="date"><?php echo esc_html__('By Date','foodfarm'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'rand') ?> value="rand"><?php echo esc_html__('By Random','foodfarm'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'popularity') ?> value="popularity"><?php echo esc_html__('By Popularity','foodfarm'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'comment_count') ?> value="comment_count"><?php echo esc_html__('By Comments','foodfarm') ?></option>
            </select>
        </p>
<?php
    }
}
?>