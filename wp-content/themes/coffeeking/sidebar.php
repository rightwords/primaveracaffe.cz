<?php
/**
 * The sidebar containing the main widget area
 *
 */

if ( coffeeking_is_wc('woocommerce') || coffeeking_is_wc('shop') || coffeeking_is_wc('product') ) : ?>
	<div class="col-lg-3 col-md-4 col-lg-pull-9 col-md-pull-8">
		<div id="content-sidebar" class="content-sidebar woocommerce-sidebar widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-wc' ); ?>
		</div>
	</div>
	</div></div>
<?php elseif ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div class="col-lg-3 col-md-4">
		<div id="content-sidebar" class="content-sidebar widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div>
	</div>
<?php endif; ?>
