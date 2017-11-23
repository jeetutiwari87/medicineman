<?php

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once foodfarm_plugins . '/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'foodfarm_theme_register_required_plugins');

function foodfarm_theme_register_required_plugins() {
    $remote_url = 'http://hn.arrowpress.net/plugins';
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        array(
            'name' => 'Redux Framework',
            'slug' => 'redux-framework',
            'required' => true
        ),
        // This is an example of how to include a plugin bundled with a theme.
        array(
            'name' => 'Revolution Slider', // The plugin name.
            'slug' => 'revslider', // The plugin slug (typically the folder name).
            'source' => $remote_url . '/revslider.zip', // The plugin source.
            'required' => true // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name' => 'WPBakery Visual Composer',
            'slug' => 'js_composer',
            'source' => $remote_url . '/js_composer.zip',
            'required' => true
        ),
        array(
            'name' => 'Ultimate Addons for Visual Composer',
            'slug' => 'Ultimate_VC_Addons',
            'source' => $remote_url . '/Ultimate_VC_Addons.zip',
            'required' => true
        ),
        array(
            'name' => 'ArrowPress Importer', // The plugin name.
            'slug' => 'arrowpress_importer', // The plugin slug (typically the folder name).
            'source' => $remote_url . '/foodfarm/arrowpress_importer.zip', // The plugin source.
            'required' => true // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name' => 'Foodfarm Post Types',
            'slug' => 'foodfarm-post-types',
            'source' => $remote_url . '/foodfarm/foodfarm-post-types.zip',
            'required' => true
        ),
        array(
            'name' => 'Foodfarm Shortcodes',
            'slug' => 'foodfarm-shortcodes',
            'source' => $remote_url . '/foodfarm/foodfarm-shortcodes.zip',
            'required' => true
        ),
        array(
            'name' => 'Foodfarm Instagram Feed',
            'slug' => 'foodfarm_instagram_feed',
            'source' => $remote_url . '/foodfarm/foodfarm_instagram_feed.zip',
            'required' => true
        ),
        array(
            'name' => 'Foodfarm Lastest Tweets',
            'slug' => 'foodfarm-latest-tweets',
            'source' => $remote_url . '/foodfarm/foodfarm-latest-tweets.zip',
            'required' => true
        ),
        array(
            'name' => 'Woocommerce',
            'slug' => 'woocommerce',
            'required' => true
        ),
        array(
            'name' => 'Yith Woocommerce Ajax Navigation',
            'slug' => 'yith-woocommerce-ajax-navigation',
            'required' => true
        ),
        array(
            'name' => 'Yith Woocommerce Brands Add On',
            'slug' => 'yith-woocommerce-brands-add-on',
            'required' => true
        ),
        array(
            'name' => 'Yith Woocommerce Compare',
            'slug' => 'yith-woocommerce-compare',
            'required' => true
        ),
        array(
            'name' => 'Yith Woocommerce Quick View',
            'slug' => 'yith-woocommerce-quick-view',
            'required' => true
        ),
        array(
            'name' => 'Yith Woocommerce Wishlist',
            'slug' => 'yith-woocommerce-wishlist',
            'required' => true
        ),
        array(
            'name' => 'Contact Form 7',
            'slug' => 'contact-form-7',
            'required' => true
        ),
        array(
            'name' => 'WP Knowledgebase',
            'slug' => 'wp-knowledgebase',
            'required' => true
        ),
        array(
            'name' => 'Dynamic Featured Image',
            'slug' => 'dynamic-featured-image',
            'required' => true
        ),
        array(
            'name' => 'Facebook Page Like Widget',
            'slug' => 'facebook-pagelike-widget',
            'required' => false
        ),
        array(
            'name' => 'MailChimp for WordPress',
            'slug' => 'mailchimp-for-wp',
            'required' => false
        ),
    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id' => 'foodfarm', // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '', // Default absolute path to bundled plugins.
        'menu' => 'install-required-plugins', // Menu slug.
        'parent_slug' => 'themes.php', // Parent menu slug.
        'capability' => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices' => true, // Show admin notices or not.
        'dismissable' => false, // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false, // Automatically activate plugins after installation or not.
        'message' => '', // Message to output right before the plugins table.
        'strings' => array(
            'page_title' => esc_html__('Install Required Plugins', 'foodfarm'),
            'menu_title' => esc_html__('Install Plugins', 'foodfarm'),
            'installing' => esc_html__('Installing Plugin: %s', 'foodfarm'), // %s = plugin name.
            'oops' => esc_html__('Something went wrong with the plugin API.', 'foodfarm'),
            'notice_can_install_required' => _n_noop(
                    'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'foodfarm'
            ), // %1$s = plugin name(s).
            'notice_can_install_recommended' => _n_noop(
                    'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'foodfarm'
            ), // %1$s = plugin name(s).
            'notice_cannot_install' => _n_noop(
                    'Sorry, but you do not have the correct permissions to install the %1$s plugin.', 'Sorry, but you do not have the correct permissions to install the %1$s plugins.', 'foodfarm'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update' => _n_noop(
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'foodfarm'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update_maybe' => _n_noop(
                    'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'foodfarm'
            ), // %1$s = plugin name(s).
            'notice_cannot_update' => _n_noop(
                    'Sorry, but you do not have the correct permissions to update the %1$s plugin.', 'Sorry, but you do not have the correct permissions to update the %1$s plugins.', 'foodfarm'
            ), // %1$s = plugin name(s).
            'notice_can_activate_required' => _n_noop(
                    'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'foodfarm'
            ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop(
                    'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'foodfarm'
            ), // %1$s = plugin name(s).
            'notice_cannot_activate' => _n_noop(
                    'Sorry, but you do not have the correct permissions to activate the %1$s plugin.', 'Sorry, but you do not have the correct permissions to activate the %1$s plugins.', 'foodfarm'
            ), // %1$s = plugin name(s).
            'install_link' => _n_noop(
                    'Begin installing plugin', 'Begin installing plugins', 'foodfarm'
            ),
            'update_link' => _n_noop(
                    'Begin updating plugin', 'Begin updating plugins', 'foodfarm'
            ),
            'activate_link' => _n_noop(
                    'Begin activating plugin', 'Begin activating plugins', 'foodfarm'
            ),
            'return' => esc_html__('Return to Required Plugins Installer', 'foodfarm'),
            'plugin_activated' => esc_html__('Plugin activated successfully.', 'foodfarm'),
            'activated_successfully' => esc_html__('The following plugin was activated successfully:', 'foodfarm'),
            'plugin_already_active' => esc_html__('No action taken. Plugin %1$s was already active.', 'foodfarm'), // %1$s = plugin name(s).
            'plugin_needs_higher_version' => esc_html__('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'foodfarm'), // %1$s = plugin name(s).
            'complete' => esc_html__('All plugins installed and activated successfully. %1$s', 'foodfarm'), // %s = dashboard link.
            'contact_admin' => esc_html__('Please contact the administrator of this site for help.', 'foodfarm'),
            'nag_type' => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        ),
    );

    tgmpa($plugins, $config);
}
