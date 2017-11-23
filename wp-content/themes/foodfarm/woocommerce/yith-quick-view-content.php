<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

while ( have_posts() ) : the_post(); ?>

 <div class="product">

	<div itemscope id="product-<?php the_ID(); ?>" <?php post_class('product'); ?>>
		<div class="col-md-6 col-sm-6 col-xs-12">
		<?php do_action( 'yith_wcqv_product_image' ); ?>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12">
			<div class="summary-content">
				<?php do_action( 'yith_wcqv_product_summary' ); ?>
			</div>
		</div>

	</div>

</div>

<?php endwhile; // end of the loop.