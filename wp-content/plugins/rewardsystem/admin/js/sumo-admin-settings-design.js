jQuery(function ($) {
//    $('.wrapper h2').attr('data-section_close', 'no');
//    $('.wrapper h2').addClass('open');
//    jQuery('.rs_section_wrapper h2').nextUntil('h2').css('display','none');                            
    jQuery(document).on('click', '.rs_section_wrapper h2', function () {
//        var is_section_close = jQuery(this).attr('data-section_close');
//        
//        if (is_section_close === 'yes') {
//            $(this).attr('data-section_close', 'no');
//            $(this).removeClass('close').addClass('open');
//        } else {
//            $(this).attr('data-section_close', 'yes');
//            $(this).addClass('close').removeClass('open');
//        }
//        $(this).next('.panel').toggle();        
        jQuery(this).nextUntil('h2').toggle();        

    });
    jQuery(document).on('click', '.rs_membership_compatible_wrapper h2', function () {
        
        jQuery(this).nextUntil('h2').toggle();        

    });
     jQuery(document).on('click', '.rs_subscription_compatible_wrapper h2', function () {
        
        jQuery(this).nextUntil('h2').toggle();        

    });
    jQuery(document).on('click', '.rs_coupon_compatible_wrapper h2', function () {
        
        jQuery(this).nextUntil('h2').toggle();        

    });
});
