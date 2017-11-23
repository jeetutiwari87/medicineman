<?php
//remove wpml language selector style
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
//remove wpml currency-switcher style
add_action('wp_print_styles', 'foodfarm_dequeue_css_currency_switcher', 100);

function foodfarm_dequeue_css_currency_switcher() {
    wp_dequeue_style('currency-switcher');
}

//show currency switcher dropdown list
if ( ! function_exists( 'foodfarm_show_currencies_dropdown' ) ) {
    function foodfarm_show_currencies_dropdown() {
        global $foodfarm_settings;
        if (class_exists('WCML_CurrencySwitcher') && class_exists('Woocommerce') && $foodfarm_settings['header-currency']) {
            global $woocommerce_wpml;
            if (!isset($woocommerce_wpml->multi_currency_support)) {
                return;
            }
            $settings = $woocommerce_wpml->get_settings();
            $current_currency = $woocommerce_wpml->multi_currency_support->get_client_currency();
            $wc_currencies = get_woocommerce_currencies();
            $format_setting = isset($settings['wcml_curr_template']) && $settings['wcml_curr_template'] != '' ? $settings['wcml_curr_template'] : '%name% (%symbol%) - %code%';
            $currency_format = preg_replace(array('#%name%#', '#%symbol%#', '#%code%#'), array($wc_currencies[$current_currency], get_woocommerce_currency_symbol($current_currency), $current_currency), $format_setting);
            ?>
                <div class="currency_custom">
                    <a class="current-open" href="#" aria-expanded="false" aria-haspopup="true" data-toggle="dropdown">
                        <?php echo $currency_format;?>
                    </a>
                    <div class="dib header-currencies dropdown-menu">
                        <div id="currencyHolder">
                            <?php echo(do_shortcode('[currency_switcher switcher_style="list" orientation="vertical"]')); ?>
                        </div>
                    </div>
                </div>
            <?php
        }
    }
}
//show language switcher dropdown list
if ( ! function_exists( 'foodfarm_show_language_dropdown' ) ) {
function foodfarm_show_language_dropdown() {
    global $foodfarm_settings, $sitepress;
    if( !defined( 'ICL_LANGUAGE_CODE' ) && !isset( $sitepress )) {
        return false;
    }
    $languages = icl_get_languages('skip_missing=0&orderby=code');
    if (isset($foodfarm_settings['header-language']) && $foodfarm_settings['header-language']) {
        $language_text = __('Languages', 'foodfarm');
        if(defined('ICL_LANGUAGE_CODE')) {
            $language_text = ICL_LANGUAGE_CODE;
        }
        ?>
        <li class="languges-flags">
            <div class="header-languages">
                <?php do_action('icl_language_selector'); ?>
            </div>
        </li>
        <?php
    }
}
}
