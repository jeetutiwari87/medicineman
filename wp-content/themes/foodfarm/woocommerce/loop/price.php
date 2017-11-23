<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
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

global $product;
global $post; 
$is_vari =  get_field('is_product_for_variations'); 
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<div class="price">

	<?php if($is_vari == 'Yes'){ 
	$p =	$product->get_price() ; 
	$pr  = $p/3.5;
	 
	echo '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>'.number_format((float)$pr, 2, '.', '').' PER GRAM</span>';  
	}else{echo $price_html;} ?></div>
<?php endif; ?>
<?php 
 global $product;
	$type = $product->get_attribute( 'types' );
	$thc = $product->get_attribute( 'thc' );
	$grade = $product->get_attribute( 'grade' );
	$cbd = $product->get_attribute( 'cbd' );
 
?>
<div class="extraAtt">
<div class="custom-types"><div class="type-title">TYPE</div><div class="type-text">
	<?php  if($type != ''){ echo  $type;   }else{ echo '-';  } ?>
 </div></div>
	


<div class="custom-thc"><div class="thc-title">THC</div><div class="thc-text">
<?php  if($thc != ''){ echo  $thc;   }else{ echo '-';  } ?>
</div></div>
<div class="custom-cbd"><div class="cbd-title">CBD</div><div class="cbd-text">
<?php  if($cbd != ''){ echo  $cbd;   }else{ echo '-';  } ?>
</div></div>
<div class="custom-grade"><div class="grade-title">GRADE</div><div class="grade-text">
<?php  if($grade != ''){ echo  $grade;   }else{ echo '-';  } ?>
</div></div>
</div>
