<?php
/*
  Plugin Name: Foodfarm Instagram Feed
  Plugin URI:
  Description: Foodfarm Instagram Feed for Foodfarm Theme.
  Version: 1.0.0
  Author: AHT
  Author URI:
 */

// Block direct requests
if ( !defined('ABSPATH') )
    die('-1');
    
require_once dirname(__FILE__) . '/settings.php';    

/**
 * Adds foodfarm_instagram_feed widget.
 */
class foodfarm_instagram_feed extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'foodfarm_instagram_feed', // Base ID
            __('Foodfarm Instagram Feed', 'foodfarm'), // Name
            array( 'description' => __( 'Foodfarm Instagram Feed', 'foodfarm' ), ) // Args
        );
        add_shortcode('foodfarm_instagram_feed', array($this, 'foodfarm_shortcode_instagram'));
    }
    function loadJs() {
        wp_enqueue_script('foodfarm_instagram', plugin_dir_url(__FILE__) . '/js/instagramfeed.js', array(), false, false);
    }
    // load plugin text domain
    function loadTextDomain() {
        load_plugin_textdomain('foodfarm', false, dirname(__FILE__) . '/languages/');
    }
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $options = get_option('foodfarm_instagram');
        $access_token = $options['access_token'];
        $user_id = $options['user_id'];

        extract( $args );
        $tag = ( ! empty( $instance['tag'] ) ) ? strip_tags( $instance['tag'] ) : '';
        $title = apply_filters( 'widget_title', $instance['title'] );
        $i=0;
        echo $before_widget;
        ?>

        <?php if ($access_token != '' && $user_id != ''): ?>
            <?php
            $url = 'https://api.instagram.com/v1/users/' . $user_id . '/media/recent/?access_token=' . $access_token;
            $all_result = $this->process_url($url);

            $decoded_results = json_decode($all_result, true);
        ?>
            <?php echo $args['before_title'] . $title . $args['after_title']; ?>
            <div class="instagram-container">
                <?php if (count($decoded_results) && isset($decoded_results['data'])) : ?>
                    <?php if($instance['number'] <=9):?>
                  
                            <ul class="footer-gallery">
                              <?php if($tag != ""):?>
                                    <?php foreach (array_slice($decoded_results['data'], 0) as $value): ?>
                                      <?php if( isset($value['tags'][0])):?>
                                        <?php if (in_array($tag, $value['tags'])):?>
                                        <?php  $i ++;?>
                                          <?php if($i <= $instance['number']):?>
                                            <li>
                                                <a title="<?php echo $value['caption']['text'] ?>" target="_blank" href="<?php echo $value['link'] ?>">
                                                  <img width="85" height="85" src="<?php echo $value['images']['thumbnail']['url'] ?>" alt="<?php echo $value['caption']['text'] ?>" />
                                                </a>
                                            </li>
                                          <?php endif;?>
                                        <?php endif;?>
                                      <?php endif;?>
                                    <?php endforeach; ?>                           
                              <?php else:?>                             
                                <?php foreach (array_slice($decoded_results['data'], 0, $instance['number']) as $value): ?>
                                  <li>
                                      <a title="<?php echo $value['caption']['text'] ?>" target="_blank" href="<?php echo $value['link'] ?>">
                                        <img width="80" height="80" src="<?php echo $value['images']['thumbnail']['url'] ?>" alt="<?php echo $value['caption']['text'] ?>" />
                                      </a>
                                  </li>
                                <?php endforeach; ?>  
                               <?php endif;?>                              
                            </ul>
                    <?php else:?>
                                <ul class="footer-gallery">
                                  <?php foreach (array_slice($decoded_results['data'], 0, 8) as $value): ?>
                                    <li>
                                        <a title="<?php echo $value['caption']['text'] ?>" target="_blank" href="<?php echo $value['link'] ?>">
                                          <img width="80" height="80" src="<?php echo $value['images']['thumbnail']['url'] ?>" alt="<?php echo $value['caption']['text'] ?>" />
                                        </a>
                                    </li>
                                  <?php endforeach; ?>
                                </ul>
                    <?php endif;?>
                        
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php
        echo $after_widget;
    }
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $defaults = array( 
            'title' => 'Instagram', 
            'number' => 9,
            'tag' =>"",
            );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" type="text" name="<?php echo $this->get_field_name('title'); ?>'" value="<?php echo $instance['title']; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of photos to display (Less than or equal to 9):'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" type="text" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tag'); ?>">Hashtag:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('tag'); ?>" type="text" name="<?php echo $this->get_field_name('tag'); ?>'" value="<?php echo $instance['tag']; ?>" />
        </p>       
       
        <?php 
    }
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['tag'] = ( ! empty( $new_instance['tag'] ) ) ? strip_tags( $new_instance['tag'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
        return $instance;
    }
    function foodfarm_shortcode_instagram($atts, $content = null) {
        $options = get_option('foodfarm_instagram');
        $access_token = $options['access_token'];
        $user_id = $options['user_id'];

        $limit = 20;
        $output = $title = $el_class = '';
        $per_page = 12;
        extract(shortcode_atts(array(
            'per_page' => '',
            'show_text' => 'yes',
            'name_account' => '',
            'el_class' => ''
        ), $atts));

        $el_class = foodfarm_shortcode_extract_class($el_class);
        $output = '<div class="foodfarm-animation ' . $el_class . '"';
        $output .= '>';
        ob_start();
        ?>
        <?php echo $output; ?>
        <?php if ($access_token != '' && $user_id != ''): ?>
            <?php
            $url = 'https://api.instagram.com/v1/users/' . $user_id . '/media/recent/?access_token=' . $access_token;
            $link_url = 'https://instagram.com/' . $user_id;
            $all_result = $this->process_url($url);

            $decoded_results = json_decode($all_result, true);
            ?>
            <div class="instagram-container instagram-gallery">
                <?php if (count($decoded_results) && isset($decoded_results['data'])) : ?>
                      <ul class="footer-gallery">
                        <?php foreach (array_slice($decoded_results['data'], 0, $per_page) as $value): ?>
                          <li>
                              <a title="<?php echo $value['caption']['text'] ?>" target="_blank" href="<?php echo $value['link'] ?>">
                                <img width="190" height="190" src="<?php echo $value['images']['standard_resolution']['url'] ?>" alt="<?php echo $value['caption']['text'] ?>" />
                              </a>
                          </li>
                        <?php endforeach; ?>                                
                      </ul>
                  <?php if($show_text) :?>
                  <div class="banner_text_overlay">
                    <?php echo esc_html__( 'We are', 'foodfarm' );?> <a target="_blank" href="https://www.instagram.com/<?php echo $name_account;?>">@<?php echo $name_account;?></a><br> <?php echo esc_html__( 'with 100%', 'foodfarm' );?>
                    <?php if (count($decoded_results) && isset($decoded_results['data'])) : ?>
                          <?php foreach (array_slice($decoded_results['data'], 0, $per_page) as $value): ?>
                              <span><?php echo $value['caption']['text'] ?><span>
                          <?php endforeach; ?> 
                    <?php endif; ?>
                  </div>
                  <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row">
                <?php echo __('Instagram Plugin error: Plugin not fully configured', 'foodfarm') ?>
            </div>
        <?php endif; ?>
            
        </div>
        <?php
        return ob_get_clean();
    }
    function process_url($url) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2
        ));

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
} // class My_Widget

add_action( 'widgets_init', function(){
     register_widget( 'foodfarm_instagram_feed' );
}); 
?>