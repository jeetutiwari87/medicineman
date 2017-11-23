<?php

add_action('redux/options/foodfarm_settings/saved', 'foodfarm_save_theme_settings', 10, 2);
add_action('redux/options/foodfarm_settings/import', 'foodfarm_save_theme_settings', 10, 2);
add_action('redux/options/foodfarm_settings/reset', 'foodfarm_save_theme_settings');
add_action('redux/options/foodfarm_settings/section/reset', 'foodfarm_save_theme_settings');

function foodfarm_config_value($value) {
    return isset($value) ? $value : 0;
}

//complie scss
function foodfarm_save_theme_settings() {
    global $foodfarm_settings;
    update_option('foodfarm_init_theme', '1');
    global $foodfarmReduxSettings;
    @ini_set('max_execution_time', '10000');
    @ini_set('memory_limit', '256M');
    $reduxFramework = $foodfarmReduxSettings->ReduxFramework;
    $template_dir = get_template_directory();

    // Compile SCSS Files
    if (!class_exists('scssc')) {
        require_once( foodfarm_admin . '/sassphp/scss.inc.php' );
    }

    // config skin file
    ob_start();
    include foodfarm_admin . '/sassphp/config_skin_scss.php';
    $_config_css = ob_get_clean();

    $filename = $template_dir . '/scss/config/_config_skin.scss';

    if (is_writable(dirname($filename)) == false) {
        @chmod(dirname($filename), 0755);
    }

    if (file_exists($filename)) {
        if (is_writable($filename) == false) {
            @chmod($filename, 0755);
        }
        @unlink($filename);
    }
    $reduxFramework->filesystem->execute('put_contents', $filename, array('content' => $_config_css));

    // skin css
    ob_start();

    $scss = new scssc();
    $scss->setImportPaths($template_dir . '/scss');
    $scss->setFormatter('scss_formatter');
    echo $scss->compile('@import "skin.scss"');

    if (isset($foodfarm_settings['custom-css-code']))
        echo $foodfarm_settings['custom-css-code'];

    $_config_css = ob_get_clean();

    $filename = $template_dir . '/css/config/skin.css';

    if (is_writable(dirname($filename)) == false) {
        @chmod(dirname($filename), 0755);
    }

    if (file_exists($filename)) {
        if (is_writable($filename) == false) {
            @chmod($filename, 0755);
        }
        @unlink($filename);
    }
    $reduxFramework->filesystem->execute('put_contents', $filename, array('content' => $_config_css));

    // // plugin css
    //     ob_start();

    //     $scss = new scssc();
    //     $scss->setImportPaths($template_dir . '/scss');
    //     $scss->setFormatter('scss_formatter');
    //     echo $scss->compile('@import "plugins.scss"');

    //     $_config_css = ob_get_clean();

    //     $filename = $template_dir . '/css/plugins.css';

    //     if (is_writable(dirname($filename)) == false) {
    //         @chmod(dirname($filename), 0755);
    //     }

    //     if (file_exists($filename)) {
    //         if (is_writable($filename) == false) {
    //             @chmod($filename, 0755);
    //         }
    //         @unlink($filename);
    //     }
    //     $reduxFramework->filesystem->execute('put_contents', $filename, array('content' => $_config_css));
    // if(is_rtl()){
    //     // theme rtl
    //     ob_start();

    //     $scss = new scssc();
    //     $scss->setImportPaths($template_dir . '/scss');
    //     $scss->setFormatter('scss_formatter');
    //     echo $scss->compile('@import "theme_rtl.scss"');

    //     $_config_css = ob_get_clean();

    //     $filename = $template_dir . '/css/theme_rtl.css';

    //     if (is_writable(dirname($filename)) == false) {
    //         @chmod(dirname($filename), 0755);
    //     }

    //     if (file_exists($filename)) {
    //         if (is_writable($filename) == false) {
    //             @chmod($filename, 0755);
    //         }
    //         @unlink($filename);
    //     }
    //     $reduxFramework->filesystem->execute('put_contents', $filename, array('content' => $_config_css));
    //     // Plugin rtl css
    //     ob_start();

    //     $scss = new scssc();
    //     $scss->setImportPaths($template_dir . '/scss');
    //     $scss->setFormatter('scss_formatter');
    //     echo $scss->compile('@import "plugins_rtl.scss"');

    //     $_config_css = ob_get_clean();

    //     $filename = $template_dir . '/css/plugins_rtl.css';

    //     if (is_writable(dirname($filename)) == false) {
    //         @chmod(dirname($filename), 0755);
    //     }

    //     if (file_exists($filename)) {
    //         if (is_writable($filename) == false) {
    //             @chmod($filename, 0755);
    //         }
    //         @unlink($filename);
    //     }
    //     $reduxFramework->filesystem->execute('put_contents', $filename, array('content' => $_config_css));
    // }

}
