<?php
/*
Plugin Name: Snapchat Snapcode Widget
Plugin URI: https://wordpress.org/plugins/pipdig-snapcode-widget/
Version: 1.1.1
Author: pipdig
Description: Add your Snapchat Snapcode to your site. Gain more followers!
Text Domain: pipdig-snapcode-widget
Author URI: https://www.pipdig.co/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
*/

if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

function pipdig_snapcode_widget_scripts() {

	// Don't bother if p3 active
	include_once (ABSPATH.'wp-admin/includes/plugin.php');
	if (is_plugin_active('p3/p3.php')) {
		return;
	}
	
	global $pagenow, $wp_customize;
	if ('widgets.php' === $pagenow || isset($wp_customize)) {
		wp_enqueue_media();
		wp_enqueue_script('pipdig-image-upload', trailingslashit(plugin_dir_url(__FILE__)) . 'image-upload.js', array('jquery'));
	}
}
add_action('admin_enqueue_scripts', 'pipdig_snapcode_widget_scripts');


class pipdig_snapcode_widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	function __construct() {

		$this->defaults = array(
			'title' => '',
			'snapcode' => '',
			'snapchat_account' => '',
		);

		$widget_ops = array(
			'classname' => 'pipdig_snapcode_widget',
			'description' => __('Display your Snapchat Snapcode.', 'pipdig-snapcode-widget'),
		);

		$control_ops = array(
			'id_base' => 'pipdig_snapcode_widget',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct('pipdig_snapcode_widget', 'pipdig - Snapchat Snapcode', $widget_ops, $control_ops);

	}

	// The widget content.
	function widget($args, $instance) {

		//* Merge with defaults
		$instance = wp_parse_args((array) $instance, $this->defaults);

		echo $args['before_widget'];

			if (! empty($instance['title']))
				echo $args['before_title'] . apply_filters('widget_title', $instance['title'], $instance, $this->id_base) . $args['after_title'];
			
			echo '<div style="text-align:center">';
			
			$link_open = $link_close = '';
			if (!empty($instance['snapchat_account'])) {
				$link_open = '<a href="'.esc_url('https://www.snapchat.com/add/'.trim($instance['snapchat_account'])).'" target="_blank" rel="nofollow">';
				$link_close = '</a>';
			}

			if (!empty($instance['snapcode'])) {
				echo $link_open.'<img src="'.esc_url($instance['snapcode']).'" alt="Snapchat" style="min-width: 1.3in; max-width: 1.7in; height: auto;"  />'.$link_close;
				if (!empty($instance['snapchat_account'])) {
					echo '<p>'.sprintf( __('Follow <b>%s</b> on Snapchat!', 'pipdig-snapcode-widget'), strip_tags($instance['snapchat_account']) ).'</p>';
				}
			}
			
			echo '</div>';
			
		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update($new_instance, $old_instance) {

		$new_instance['title'] = strip_tags($new_instance['title']);
		$new_instance['snapcode'] = strip_tags($new_instance['snapcode']);
		$new_instance['snapchat_account'] = strip_tags($new_instance['snapchat_account']);

		return $new_instance;

	}

	// The settings update form.
	function form($instance) {

		// Merge with defaults
		$instance = wp_parse_args((array) $instance, $this->defaults);

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset($instance['title'])) echo esc_attr($instance['title']); ?>" class="widefat" />
		</p>
		
		<p>1. Download your Snapcode PNG image from <a href="https://accounts.snapchat.com/accounts/snapcodes?type=png" target="_blank">this link</a>.</p>
		<p>2. Upload your Snapcode PNG image using the button below.</p>

		<p>
			<div class="pipdig-media-container">
				<div class="pipdig-media-inner">
					<?php $img_style = ($instance[ 'snapcode' ] != '') ? '' : 'display:none;'; ?>
					<img id="<?php echo $this->get_field_id('snapcode'); ?>-preview" src="<?php echo esc_attr($instance['snapcode']); ?>" style="margin:5px 0;padding:0;max-width:180px;height:auto;<?php echo $img_style; ?>" />
					<?php $no_img_style = ($instance[ 'snapcode' ] != '') ? 'style="display:none;"' : ''; ?>
				</div>
			
				<input type="text" id="<?php echo $this->get_field_id('snapcode'); ?>" name="<?php echo $this->get_field_name('snapcode'); ?>" value="<?php echo esc_attr($instance['snapcode']); ?>" class="pipdig-media-url" style="display: none" />

				<input type="button" value="<?php echo esc_attr(__('Select Image', 'pipdig-snapcode-widget')); ?>" class="button pipdig-media-upload" id="<?php echo $this->get_field_id('snapcode'); ?>-button" />
				<br class="clear">
			</div>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('snapchat_account'); ?>"><?php _e('Snapchat Account Name:', 'pipdig-snapcode-widget'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('snapchat_account'); ?>" name="<?php echo $this->get_field_name('snapchat_account'); ?>" value="<?php if (isset($instance['snapchat_account'])) echo esc_attr($instance['snapchat_account']); ?>" class="widefat" placeholder="<?php _e("For example:", 'pipdig-snapcode-widget'); ?> mileyxxcyrus" />
		</p>

		<?php

	}

}

function register_pipdig_snapchat_widget() { 
	register_widget('pipdig_snapcode_widget');
}
add_action('widgets_init', 'register_pipdig_snapchat_widget');


function pipdig_snapcode_widget_textdomain() {
	load_plugin_textdomain( 'pipdig-snapcode-widget', false, 'pipdig-snapcode-widget/languages' );
}
add_action( 'init', 'pipdig_snapcode_widget_textdomain' );