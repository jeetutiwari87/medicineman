<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'YITH_WCBR' ) ) {
	exit;
} // Exit if accessed directly

global $product;
?>

<?php 
  $thumbnail_id = get_woocommerce_term_meta($term->term_id, 'thumbnail_id');
 if( apply_filters( 'yith_wcbr_print_brand_description', true, $term ) ): ?>
<div class=" row brandBoxListing">
	<div class="col-md-3 term-logo">
		<?php
		 if ( $thumbnail_id ) {
					$image = wp_get_attachment_url( $thumbnail_id, 'full' );

					if( $image ){
						echo '<a href="'.get_term_link( $term ).'"><img src="'.$image.'" class="attachment-full size-full" alt="" style="width: 100%; height: auto;" width="300" height="300"></a>';
						//echo sprintf( '<a href="%s">%s</a>', get_term_link( $term ), $image );
					}
				}
		?>
	</div>
	<div class=" col-md-9 yith-wcbr-archive-header term-description">
		<?php
		if( ! empty( $term_description ) ){
			echo wpautop( $term_description );
		}
		?>
	</div>
	
</div>
<?php endif; ?>