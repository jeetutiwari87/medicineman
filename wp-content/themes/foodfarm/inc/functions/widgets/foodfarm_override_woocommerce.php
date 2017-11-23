<?php
add_action( 'widgets_init', 'foodfarm_reset_filter_override_woocommerce_widgets', 15 );
function foodfarm_reset_filter_override_woocommerce_widgets() {
    if ( class_exists( 'YITH_WCAN_Reset_Navigation_Widget' ) ) {
        unregister_widget( 'YITH_WCAN_Reset_Navigation_Widget' );
        include get_template_directory() . '/woocommerce/classes/class.yith-wcan-reset-navigation-widget.php';
        register_widget( 'foodfarm_reset_filter_YITH_WCAN_Reset_Navigation_Widget' );
    }
}  