<?php
/*
  Plugin Name: Foodfarm Latest Tweets
  Plugin URI:
  Description: Foodfarm Latest Tweets for Foodfarm blog theme.
  Version: 1.0.0
  Author: AHT
  Author URI:
 */

if (!defined('ABSPATH'))
    die('-1');

require_once dirname(__FILE__) . '/settings.php';
require_once dirname(__FILE__) . '/api/Abraham/TwitterOAuth/TwitterOAuth.php';
require_once dirname(__FILE__) . '/widget.php';

class FoodfarmLatestTweet {

    function __construct() {
        // Load text domain
        add_action('plugins_loaded', array($this, 'load_text_domain'));
    }
    
    // load plugin text domain
    public function load_text_domain() {
        load_plugin_textdomain('foodfarm', false, dirname(__FILE__) . '/languages/');
    }

    public function get_tweets($number_tweets) {
        # Define constants
        $options = get_option('foodfarm_latest_tweet');
        $username = $options['username'];
        $consumer_key = $options['consumer_key'];
        $consumer_secret = $options['consumer_secret'];
        $access_token = $options['access_token'];
        $access_token_secret = $options['access_token_secret'];
        if(empty($username) || empty($consumer_key) || empty($consumer_secret) 
                || empty($access_token) || empty($access_token_secret)) {
            return false;
        }
        # Create the connection
        $twitter = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
        # Migrate over to SSL/TLS
        $twitter->ssl_verifypeer = false;
        # Load the Tweets
        try {
            $tweets = $twitter->get('statuses/user_timeline', array('screen_name' => $username, 'exclude_replies' => 'true', 'include_rts' => 'false', 'count' => $number_tweets));
            # Example output
            //echo '<pre>';print_r($tweets);die();
            if (!empty($tweets)) {
                echo '<div class="latest-tweets"><ul>';
                foreach($tweets as $_tweet) {
                    $user = $_tweet->user;
                    $handle = $user->screen_name;
                    $id_str = $_tweet->id_str;
                    $link = esc_html( 'http://twitter.com/'.$handle.'/status/'.$id_str);
                    $date = DateTime::createFromFormat('D M d H:i:s O Y', $_tweet->created_at );
                    $output ='<li>';
                    $output .= '<div class="twitter-tweet"><i class="fa fa-twitter"></i><div class ="tweet-text">'. esc_attr($_tweet->text).'<p class="my-date">'.esc_attr($date->format('g:i A - j M Y')).'</p></div>';
                    $output .= '</div></li>';
                    echo $output;
                }
                echo '</ul></div>';
            }
        } catch (Exception $exc) {
            echo esc_html__('Something wrong, please check the connection or the api config!');
        }

        return null;
    }
}
