<?php
/*
	Plugin Name: Canada Post (BASIC) WooCommerce Extension
	Plugin URI: https://www.xadapter.com/product/woocommerce-canada-post-shipping-plugin-with-print-label/
	Description: The ultimate Canada Post WooCommerce Shipping plugin. Real time shipping rates.
	Version: 1.3.1
	Author: XAdapter
        Text Domain: wf-shipping-canada-post
	Author URI: www.xadapter.com/
*/

//Dev version : 1.6.3
if( !defined('WF_CANADAPOST_ID') ){
	define("WF_CANADAPOST_ID", "wf_shipping_canada_post");
}

function wf_canadapost_activation_check(){
	if ( is_plugin_active('canada-post-woocommerce-shipping/canada-post-woocommerce-shipping.php') ){
        deactivate_plugins( basename( __FILE__ ) );
		wp_die("Is everything fine? You already have the Premium version installed in your website. For any issues, kindly raise a ticket via <a target='_blank' href='//support.xadapter.com/'>support.xadapter.com</a>", "", array('back_link' => 1 ));
	}
}
register_activation_hook( __FILE__, 'wf_canadapost_activation_check' );

/**
 * Check if WooCommerce is active
 */
if (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {
	/**
	 * WC_USPS class
	 */
	if( !class_exists('wf_canada_post_woocommerce_shipping_setup') ){
		class wf_canada_post_woocommerce_shipping_setup {
			/**
			 * Constructor
			 */
			public function __construct() {
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
				add_action( 'init', array( $this, 'init' ) );
				add_action( 'woocommerce_shipping_init', array( $this, 'shipping_init' ) );
				add_filter( 'woocommerce_shipping_methods', array( $this, 'add_method' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			}

			public function init(){
				if ( ! class_exists( 'wf_order' ) ) {
					include_once 'includes/class-wf-legacy.php';
				}		
			}

			public function activation_check() {
				if ( ! function_exists( 'simplexml_load_string' ) ) {
			        deactivate_plugins( basename( __FILE__ ) );
			        wp_die( "Sorry, but you cannot run this plugin, it requires the SimpleXML library installed on your server/hosting to function." );
				}
			}

			/**
			 * Plugin page links
			 */
			public function plugin_action_links( $links ) {
				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=wf_shipping_canada_post' ) . '">' . __( 'Settings', 'wf-shipping-canada-post' ) . '</a>',

					'<a href="https://www.xadapter.com/product/woocommerce-canada-post-shipping-plugin-with-print-label/" target="_blank">' . __( 'Premium Upgrade', 'wf-shipping-canada-post' ) . '</a>',

					'<a href="https://wordpress.org/support/plugin/canada-post-woocommerce-shipping" target="_blank">' . __( 'Support', 'wf-shipping-canada-post' ) . '</a>',
				);
				return array_merge( $plugin_links, $links );
			}

			/**
			 * Load gateway class
			 */
			public function shipping_init() {
				include_once( 'includes/class-wf-shipping-canada-post.php' );
			}

			/**
			 * Add method to WC
			 */
			public function add_method( $methods ) {
				$methods[] = 'wf_shipping_canada_post';
				return $methods;
			}

			/**
			 * Enqueue scripts
			 */
			public function scripts() {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
		}
	}

	new wf_canada_post_woocommerce_shipping_setup();

	if (!function_exists('wf_convert_rate')){
		function wf_convert_rate($actual_rate, $conversion_rate,$convert_to_base = true){
			if($convert_to_base){
				return round( $actual_rate * $conversion_rate, absint( get_option( 'woocommerce_price_num_decimals' ) ) );
			}
			else{
				return round( $actual_rate / $conversion_rate, absint( get_option( 'woocommerce_price_num_decimals' ) ) );
			}
		}
	}
}