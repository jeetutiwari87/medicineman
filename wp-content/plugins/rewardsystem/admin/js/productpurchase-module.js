jQuery(function ($) {

    var wc_sumo_product_purchase = {
        init: function () {
            //Cart custom message show/hide
            $(document).ready(this.hide_cart_custom_message);
            $(document.body).on('change', '#rs_show_hide_custom_msg_for_points_cart', this.hide_cart_custom_message);
            //Checkout custom message show/hide
            $(document).ready(this.hide_checkout_custom_message);
            $(document.body).on('change', '#rs_show_hide_custom_msg_for_points_checkout', this.hide_checkout_custom_message);
            //Thank you custom message show/hide
            $(document).ready(this.hide_thankyou_custom_message);
            $(document.body).on('change', '#rs_show_hide_custom_msg_for_points_thankyou', this.hide_thankyou_custom_message);
            
        },
   
        hide_cart_custom_message : function () {
            if ( jQuery ( '#rs_show_hide_custom_msg_for_points_cart' ).val () == '1' ) {
                jQuery ( '#rs_custom_message_for_points_cart' ).closest ( 'tr' ).show () ;
            } else {
                jQuery ( '#rs_custom_message_for_points_cart' ).closest ( 'tr' ).hide () ;
            }
        },
         hide_checkout_custom_message : function () {
            if ( jQuery ( '#rs_show_hide_custom_msg_for_points_checkout' ).val () == '1' ) {
                jQuery ( '#rs_custom_message_for_points_checkout' ).closest ( 'tr' ).show () ;
            } else {
                jQuery ( '#rs_custom_message_for_points_checkout' ).closest ( 'tr' ).hide () ;
            }
        },
         hide_thankyou_custom_message : function () {
            if ( jQuery ( '#rs_show_hide_custom_msg_for_points_thankyou' ).val () == '1' ) {
                jQuery ( '#rs_custom_message_for_points_thankyou' ).closest ( 'tr' ).show () ;
            } else {
                jQuery ( '#rs_custom_message_for_points_thankyou' ).closest ( 'tr' ).hide () ;
            }
        }
    };

    wc_sumo_product_purchase.init();
});


