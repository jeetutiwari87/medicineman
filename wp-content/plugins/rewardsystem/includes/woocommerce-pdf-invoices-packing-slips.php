<?php 

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSWCPdfPackingslips')) {

    class RSWCPdfPackingslips {
        
        public static function init() {
                add_action('wpo_wcpdf_after_order_details' , array(__CLASS__ , 'display_earned_redeemed_message') , 10 ,2 );
        }
        
        public static function display_earned_redeemed_message( $template , $order ) {
            //Getting Order details
            $order_object = rs_get_order_obj($order);
            $order_id = $order_object['order_id'];
            
            //Getting Earned/Redeeming messages in PDF
            $earned_redeemed_message = RSModulesTab::get_earned_redeemed_points_message($order_id);
            $replacemsgforearnedpoints = "<h3>".array_keys($earned_redeemed_message)[0] . "</h3>" ;
            $replacemsgforredeempoints = "<h3>".array_values($earned_redeemed_message)[0] . "</h3>" ; 
            
            //Displaying Earned/Redeeming messages in PDF
            if (get_option('rs_enable_msg_for_earned_points') == 'yes') {
                     if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
                         echo $replacemsgforearnedpoints."<br>";
                         echo $replacemsgforredeempoints;
                     } else {
                         echo $replacemsgforearnedpoints;
                     }
            } else {
                 if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
                     echo $replacemsgforredeempoints;
                 }

            }
        } 
    }
}


RSWCPdfPackingslips::init();