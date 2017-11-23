<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $foodfarm_settings;
if(isset($foodfarm_settings['product-cart'])){
	if($foodfarm_settings['product-cart']){
		echo apply_filters( 'woocommerce_loop_add_to_cart_link',
			sprintf( '<div class="add-to-cart"><a rel="nofollow" title="'.$product->add_to_cart_text().'" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s"><span class="icon-6"></span></a></div>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $quantity ) ? $quantity : 1 ),
				esc_attr( $product->get_id() ),
				esc_attr( $product->get_sku() ),
				esc_attr( isset( $class ) ? $class : 'button' ),
				$product->add_to_cart_text()
			),
		$product );
	}
}else{
	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
		sprintf( '<div class="add-to-cart"><a rel="nofollow" title="'.$product->add_to_cart_text().'" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s"><span class="icon-6"></span></a></div>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $class ) ? $class : 'button' ),
			$product->add_to_cart_text()
		),
	$product );	
}
