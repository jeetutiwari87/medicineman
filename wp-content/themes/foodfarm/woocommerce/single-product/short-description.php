<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

if ( ! $post->post_excerpt ) {
	return;
}

?>
<div class="extraAtt">
<?php 
 global $product;
	$type = $product->get_attribute( 'types' );
	$thc = $product->get_attribute( 'thc' );
	$grade = $product->get_attribute( 'grade' );
	$cbd = $product->get_attribute( 'cbd' );
 
?>
	<div class="p-strains-details">
	<?php  if($type != ''){ ?>
		<div class="p-strains-type hybrid indica "><?php echo $type; ?></div>
	<?php } ?>
		<?php  if($thc != ''){ ?>
		<div class="p-thc-details"><div class="p-thc-title hybrid-text indica-text ">THC: </div><div class="p-thc-val" style="margin-left: 3px;"> <?php echo $thc; ?></div></div>
	<?php } ?>
		<?php  if($cbd != ''){ ?>
		<div class="p-cbd-details"><div class="p-cbd-title hybrid-text indica-text ">CBD: </div><div class="p-cbd-val" style="margin-left: 3px;"><?php echo $cbd; ?> </div></div>
	<?php } ?>
		<?php  if($type != ''){ ?>
		<div class="p-grade-details"><div class="p-grade-title hybrid-text indica-text ">GRADE: </div><div class="p-grade-val" style="margin-left: 3px;"> <?php echo $grade; ?> </div></div>
	<?php } ?>
		
	</div>
	
<?php 
	$effects = $product->get_attribute( 'effects' );
	$flavors = $product->get_attribute( 'flavors' );
	$medical = $product->get_attribute( 'medical' );
 
 
?>
	<?php  if($effects != ''){ ?>
	<div class="p-strains-details">
		<div class="p-common-details"><div class="p-common-title hybrid-text indica-text ">EFFECTS: </div><div class="p-common-val" style="margin-left: 3px;"> &nbsp;<?php echo $effects; ?></div></div>
	</div>
	<?php } ?>
	
	<?php  if($flavors != ''){ ?>
	 <div class="p-strains-details">
		<div class="p-common-details"><div class="p-common-title hybrid-text indica-text ">FLAVORS: </div><div class="p-common-val" style="margin-left: 3px;"> <?php echo $flavors; ?></div></div>
	</div>
	<?php } ?>
	<?php  if($medical != ''){ ?>
	<div class="p-strains-details">
		<div class="p-common-details"><div class="p-common-title hybrid-text indica-text ">MEDICAL: </div><div class="p-common-val" style="margin-left: 3px;"> <?php echo $medical; ?></div></div>
	</div>
	<?php } ?>
	
	
</div>
<div itemprop="description" class="description">
		<h4><?php echo esc_html__('Quick description', 'foodfarm');?>:</h4>
	<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
</div>

