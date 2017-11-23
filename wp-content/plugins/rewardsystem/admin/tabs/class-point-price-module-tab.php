<?php
/*
 * Support Tab Setting
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('RSPointPriceModule')) {

    class RSPointPriceModule {

        public static function init() {

            add_action('admin_init', array(__CLASS__, 'reward_system_default_settings'), 999);

            add_filter('woocommerce_rs_settings_tabs_array', array(__CLASS__, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

            add_action('woocommerce_rs_settings_tabs_rewardsystem_point_price_module', array(__CLASS__, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

            add_action('woocommerce_update_options_rewardsystem_modules_rewardsystem_point_price_module', array(__CLASS__, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

            add_action('woocommerce_admin_field_rs_enable_disable_point_price_module', array(__CLASS__, 'rs_function_to_enable_disable_point_price_module'));

            add_action('woocommerce_admin_field_selected_products_point', array(__CLASS__, 'rs_select_products_to_update_point_price'));

            add_action('admin_head', array(__CLASS__, 'rs_add_update_chosen_reward_system'));

            add_action('woocommerce_admin_field_button_point_price', array(__CLASS__, 'rs_save_button_for_update_point_price'));

            add_action('wp_ajax_previousproductpointpricevalue', array(__CLASS__, 'get_ajax_request_for_previous_product_point_price'));

            add_action('wp_ajax_nopriv_previousproductpointpricevalue', array(__CLASS__, 'get_ajax_request_for_previous_product_point_price'));

            add_action('wp_ajax_rssplitajaxoptimizationforpointprice', array(__CLASS__, 'process_chunk_ajax_request_in_rewardsystem_point_price'));

            add_action('woocommerce_admin_field_rs_include_products_for_point_pricing', array(__CLASS__, 'rs_include_products_for_point_pricing'));

            add_action('woocommerce_admin_field_rs_exclude_products_for_point_pricing', array(__CLASS__, 'rs_exclude_products_for_point_pricing'));

            add_action('fp_action_to_reset_module_settings_rewardsystem_point_price_module', array(__CLASS__, 'rs_function_to_point_price_module'));
            
            add_action('woocommerce_admin_field_rs_hide_bulk_update_for_point_price_start', array(__CLASS__, 'rs_hide_bulk_update_for_point_price_start'));

            add_action('woocommerce_admin_field_rs_hide_bulk_update_for_point_price_end', array(__CLASS__, 'rs_hide_bulk_update_for_point_price_end'));
        }

        /*
         * Function to Define Name of the Tab
         */

        public static function reward_system_tab_setting($setting_tabs) {
            if (!is_array($setting_tabs))
                $setting_tabs = (array) $setting_tabs;
            $setting_tabs['rewardsystem_point_price_module'] = __('Points Price Module', 'rewardsystem');
            return $setting_tabs;
        }

        /*
         * Function label settings to Member Level Tab
         */

        public static function reward_system_admin_fields() {
            global $woocommerce;
            $categorylist = fp_rs_get_product_category();
            return apply_filters('woocommerce_rewardsystem_point_price_module', array(
                array(
                    'type' => 'rs_modulecheck_start',
                ),
                array(
                    'name' => __('Points Pricing Module', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_activate_point_price_module'
                ),
                array(
                    'type' => 'rs_enable_disable_point_price_module',
                ),
                array('type' => 'sectionend', 'id' => '_rs_activate_point_price_module'),
                array(
                    'type' => 'rs_modulecheck_end',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Point Priced Products Global Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_global_Point_price'
                ),
                array(
                    'name' => __('Point Pricing', 'rewardsystem'),
                    'id' => 'rs_enable_product_category_level_for_points_price',
                    'class' => 'rs_enable_product_category_level_for_points_price',
                    'std' => 'no',
                    'default' => 'no',
                    'type' => 'radio',
                    'newids' => 'rs_enable_product_category_level_for_points_price',
                    'options' => array(
                        'no' => __('Quick Setup (Global Level Settings will be enabled)', 'rewardsystem'),
                        'yes' => __('Advanced Setup (Global,Category and Product Level wil be enabled)', 'rewardsystem'),
                    ),
                    'desc_tip' => true,
                    'desc' => __('Quick Setup - Global Level will be enabled<br>Advanced Setup - Global,Category and Product Level wil be enabled', 'rewardsystem')
                ),
                array(
                    'name' => __('Point Pricing is applicable for', 'rewardsystem'),
                    'id' => 'rs_point_pricing_global_level_applicable_for',
                    'std' => '1',
                    'class' => 'rs_point_pricing_global_level_applicable_for',
                    'default' => '1',
                    'newids' => 'rs_point_pricing_global_level_applicable_for',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('All Product(s)', 'rewardsystem'),
                        '2' => __('Include Product(s)', 'rewardsystem'),
                        '3' => __('Exclude Product(s)', 'rewardsystem'),
                        '4' => __('All Categories', 'rewardsystem'),
                        '5' => __('Include Categories', 'rewardsystem'),
                        '6' => __('Exclude Categories', 'rewardsystem'),
                    ),
                ),
                array(
                    'type' => 'rs_include_products_for_point_pricing',
                ),
                array(
                    'type' => 'rs_exclude_products_for_point_pricing',
                ),
                array(
                    'name' => __('Include Categories', 'rewardsystem'),
                    'id' => 'rs_include_particular_categories_for_point_pricing',
                    'css' => 'min-width:350px;',
                    'std' => '',
                    'class' => 'rs_include_particular_categories_for_point_pricing',
                    'default' => '',
                    'newids' => 'rs_include_particular_categories_for_point_pricing',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Exclude Categories', 'rewardsystem'),
                    'id' => 'rs_exclude_particular_categories_for_point_pricing',
                    'css' => 'min-width:350px;',
                    'std' => '',
                    'class' => 'rs_exclude_particular_categories_for_point_pricing',
                    'default' => '',
                    'newids' => 'rs_exclude_particular_categories_for_point_pricing',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Point Pricing', 'rewardsystem'),
                    'id' => 'rs_enable_disable_point_priceing',
                    'default' => '1',
                    'std' => '1',
                    'newids' => 'rs_enable_disable_point_priceing',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Point Priced Product Identifer Label', 'rewardsystem'),
                    'id' => 'rs_label_for_point_value',
                    'default' => '/Pt',
                    'std' => '/Pt',
                    'newids' => 'rs_label_for_point_value',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Point Priced Product Identifer Label Display Position', 'rewardsystem'),
                    'id' => 'rs_sufix_prefix_point_price_label',
                    'default' => '1',
                    'std' => '1',
                    'newids' => 'rs_sufix_prefix_point_price_label',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Before', 'rewardsystem'),
                        '2' => __('After', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Point Pricing Global Level Settings', 'rewardsystem'),
                    'id' => 'rs_local_enable_disable_point_price_for_product',
                    'std' => '2',
                    'default' => '2',
                    'desc_tip' => true,
                    'newids' => 'rs_local_enable_disable_point_price_for_product',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Point Price Type', 'rewardsystem'),
                    'id' => 'rs_global_point_price_type',
                    'std' => '2',
                    'default' => '2',
                    'desc_tip' => true,
                    'newids' => 'rs_global_point_price_type',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Fixed', 'rewardsystem'),
                        '2' => __('Based On Conversion', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Pricing in Point(s)', 'rewardsystem'),
                    'id' => 'rs_local_price_points_for_product',
                    'class' => 'rs_local_price_points_for_product',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_price_points_for_product',
                ),
                array('type' => 'sectionend', 'id' => '_rs_global_Point_price'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_hide_bulk_update_for_point_price_start',
                ),
                array(
                    'type' => 'rs_wrapper_start',
                ),
                array(
                    'name' => __('Point Pricing Bulk Update Settings', 'rewardsystem'),
                    'type' => 'title',
                    'id' => '_rs_update_point_priceing'
                ),
                array(
                    'name' => __('Product/Category Selection', 'rewardsystem'),
                    'id' => 'rs_which_point_precing_product_selection',
                    'std' => '1',
                    'class' => 'rs_which_point_precing_product_selection',
                    'default' => '1',
                    'newids' => 'rs_which_point_precing_product_selection',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('All Products', 'rewardsystem'),
                        '2' => __('Selected Products', 'rewardsystem'),
                        '3' => __('All Categories', 'rewardsystem'),
                        '4' => __('Selected Categories', 'rewardsystem'),
                    ),
                ),
                array(
                    'type' => 'selected_products_point',
                ),
                array(
                    'name' => __('Select Particular Categories', 'rewardsystem'),
                    'id' => 'rs_select_particular_categories_for_point_price',
                    'css' => 'min-width:350px;',
                    'std' => '1',
                    'class' => 'rs_select_particular_categories_for_point_price',
                    'default' => '1',
                    'newids' => 'rs_select_particular_categories_for_point_price',
                    'type' => 'multiselect',
                    'options' => $categorylist,
                ),
                array(
                    'name' => __('Enable Point Pricing', 'rewardsystem'),
                    'id' => 'rs_local_enable_disable_point_price',
                    'std' => '2',
                    'default' => '2',
                    'newids' => 'rs_local_enable_disable_point_price',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Enable', 'rewardsystem'),
                        '2' => __('Disable', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Pricing Type', 'rewardsystem'),
                    'id' => 'rs_local_point_pricing_type',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'desc' => __('Enable will Turn On Points Price for Product Purchase and Product Settings will be considered if it is available. '
                            . 'Disable will Turn Off Points Price for Product Purchase and Product Settings will be considered if it is available.', 'rewardsystem'),
                    'newids' => 'rs_local_point_pricing_type',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Currency & Point Price', 'rewardsystem'),
                        '2' => __('Only Point Price', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('Points Prices Type ', 'rewardsystem'),
                    'id' => 'rs_local_point_price_type',
                    'std' => '1',
                    'default' => '1',
                    'desc_tip' => true,
                    'desc' => __('Enable will Turn On Points Price for Product Purchase and Product Settings will be considered if it is available. '
                            . 'Disable will Turn Off Points Price for Product Purchase and Product Settings will be considered if it is available.', 'rewardsystem'),
                    'newids' => 'rs_local_point_price_type',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('By Fixed', 'rewardsystem'),
                        '2' => __('Based On Conversion', 'rewardsystem'),
                    ),
                ),
                array(
                    'name' => __('By Fixed Points', 'rewardsystem'),
                    'desc' => __('Please Enter Price Points', 'rewardsystem'),
                    'id' => 'rs_local_price_points',
                    'class' => 'show_if_price_enable_in_update',
                    'std' => '',
                    'default' => '',
                    'type' => 'text',
                    'newids' => 'rs_local_price_points',
                    'placeholder' => '',
                    'desc' => __('When left empty, Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                            . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Global Settings will be ignored.', 'rewardsystem'),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Test Button', 'rewardsystem'),
                    'desc' => __('This is for testing button', 'rewardsystem'),
                    'id' => 'rs_sumo_point_price_button',
                    'std' => '',
                    'default' => '',
                    'type' => 'button_point_price',
                    'desc_tip' => true,
                    'newids' => 'rs_sumo_point_price_button',
                ),
                array('type' => 'sectionend', 'id' => '_rs_update_point_priceing'),
                array(
                    'type' => 'rs_wrapper_end',
                ),
                array(
                    'type' => 'rs_hide_bulk_update_for_point_price_end',
                ),
            ));
        }

        /**
         * Registering Custom Field Admin Settings of SUMO Reward Points in woocommerce admin fields funtion
         */
        public static function reward_system_register_admin_settings() {
            woocommerce_admin_fields(RSPointPriceModule::reward_system_admin_fields());
        }

        /**
         * Update the Settings on Save Changes may happen in SUMO Reward Points
         */
        public static function reward_system_update_settings() {
            woocommerce_update_options(RSPointPriceModule::reward_system_admin_fields());
            if (isset($_POST['rs_select_particular_products'])) {
                update_option('rs_select_particular_products', $_POST['rs_select_particular_products']);
            } else {
                update_option('rs_select_particular_products', '');
            }
            if (isset($_POST['rs_select_particular_products_for_point_price'])) {
                update_option('rs_select_particular_products_for_point_price', $_POST['rs_select_particular_products_for_point_price']);
            } else {
                update_option('rs_select_particular_products_for_point_price', '');
            }
            if (isset($_POST['rs_point_price_module_checkbox'])) {
                update_option('rs_point_price_activated', $_POST['rs_point_price_module_checkbox']);
            } else {
                update_option('rs_point_price_activated', 'no');
            }
            if (isset($_POST['rs_include_products_for_point_pricing'])) {
                update_option('rs_include_products_for_point_pricing', $_POST['rs_include_products_for_point_pricing']);
            } else {
                update_option('rs_include_products_for_point_pricing', '');
            }
            if (isset($_POST['rs_include_products_for_point_pricing'])) {
                update_option('rs_exclude_products_for_point_pricing', $_POST['rs_exclude_products_for_point_pricing']);
            } else {
                update_option('rs_exclude_products_for_point_pricing', '');
            }
        }

        /**
         * Initialize the Default Settings by looping this function
         */
        public static function reward_system_default_settings() {
            global $woocommerce;
            foreach (RSPointPriceModule::reward_system_admin_fields() as $setting)
                if (isset($setting['newids']) && isset($setting['std'])) {
                    add_option($setting['newids'], $setting['std']);
                }
        }

        public static function rs_function_to_point_price_module() {
            $settings = RSPointPriceModule::reward_system_admin_fields();
            RSTabManagement::rs_function_to_reset_setting($settings);
        }

        public static function rs_function_to_enable_disable_point_price_module() {
            $get_option_value = get_option('rs_point_price_activated');
            $name_of_checkbox = 'rs_point_price_module_checkbox';
            RSModulesTab::rs_common_function_to_get_checkbox($get_option_value, $name_of_checkbox);
        }

        public static function rs_hide_bulk_update_for_point_price_start() {
            ?>
            <div class="rs_hide_bulk_update_for_point_price_start">
                <?php
            }

            public static function rs_hide_bulk_update_for_point_price_end() {
                ?>
            </div>
            <?php
        }

        public static function rs_select_products_to_update_point_price() {
            $field_id = "rs_select_particular_products_for_point_price";
            $field_label = "Select Particular Products";
            $getproducts = get_option('rs_select_particular_products_for_point_price');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        public static function rs_add_update_chosen_reward_system() {
            global $woocommerce;
            if (isset($_GET['page'])) {
                if ($_GET['page'] == 'rewardsystem_callback') {
                    if (isset($_GET['tab'])) {
                        echo rs_common_ajax_function_to_select_products('rs_select_particular_products');
                        echo rs_common_ajax_function_to_select_products('rs_select_particular_social_products');
                        echo rs_common_ajax_function_to_select_products('rs_select_particular_products_for_point_price');
                        if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                            echo rs_common_chosen_function('#rs_select_particular_categories_for_point_price');
                            echo rs_common_chosen_function('#rs_select_particular_products');
                            echo rs_common_chosen_function('#rs_select_particular_social_products');
                            echo rs_common_chosen_function('#rs_select_particular_categories');
                            echo rs_common_chosen_function('#rs_include_particular_categories_for_product_purchase');
                            echo rs_common_chosen_function('#rs_exclude_particular_categories_for_product_purchase');
                            echo rs_common_chosen_function('#rs_include_particular_categories_for_point_pricing');
                            echo rs_common_chosen_function('#rs_exclude_particular_categories_for_point_pricing');
                        } else {
                            echo rs_common_select_function('#rs_select_particular_categories_for_point_price');
                            echo rs_common_select_function('#rs_select_particular_categories');
                            echo rs_common_select_function('#rs_include_particular_categories_for_product_purchase');
                            echo rs_common_select_function('#rs_exclude_particular_categories_for_product_purchase');
                            echo rs_common_select_function('#rs_include_particular_categories_for_point_pricing');
                            echo rs_common_select_function('#rs_exclude_particular_categories_for_point_pricing');
                        }
                    }
                }
            }
        }

        public static function rs_save_button_for_update_point_price() {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">                    
                </th>
                <td class="forminp forminp-select">
                    <input type="submit" class="rs_sumo_point_price_button button-primary" value="Save and Update"/>
                    <img class="gif_rs_sumo_point_price_button" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/admin/images/update.gif" style="width:32px;height:32px;position:absolute"/>          
                    <div class='rs_sumo_point_price_button' style='margin-bottom:10px; margin-top:10px; color:green;'></div>
                </td>
            </tr>
            <?php
        }

        public function get_ajax_request_for_previous_product_point_price() {
            global $woocommerce;
            global $post;
            if (isset($_POST['proceedanyway'])) {
                if ($_POST['proceedanyway'] == '1') {
                    if ($_POST['whichproduct'] == '1') {
                        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                        $products = get_posts($args);
                        echo json_encode($products);
                    } elseif ($_POST['whichproduct'] == '2') {
                        if (!is_array($_POST['selectedproducts'])) {
                            $_POST['selectedproducts'] = explode(',', $_POST['selectedproducts']);
                        }
                        if (is_array($_POST['selectedproducts'])) {

                            foreach ($_POST['selectedproducts']as $particularpost) {
                                $checkprod = rs_get_product_object($particularpost);
                                if (is_object($checkprod) && ($checkprod->is_type('simple') || ($checkprod->is_type('subscription')) || $checkprod->is_type('booking') || $checkprod->is_type('lottery'))) {
                                    if ($_POST['enabledisablepoints'] == '1') {
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_enable_point_price', 'yes');
                                    } else {
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_enable_point_price', 'no');
                                    }
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_point_price_type', $_POST['pointpricetype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem__points', $_POST['pricepoints']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_enable_point_price_type', $_POST['pointpricingtype']);
                                } else {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_enable_reward_points_price', $_POST['enabledisablepoints']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_enable_reward_points_price_type', $_POST['pointpricetype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_enable_reward_points_pricing_type', $_POST['pointpricingtype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, 'price_points', $_POST['pricepoints']);
                                }
                            }
                        }
                        echo json_encode("success");
                    } elseif ($_POST['whichproduct'] == '3') {
                        $allcategories = get_terms('product_cat');
                        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                        $products = get_posts($args);
                        foreach ($products as $product) {
                            $checkproducts = rs_get_product_object($product);
                            if ((float) $woocommerce->version >= (float) '3.0') {
                                $id = $checkproducts->get_id();
                            } else {
                                $id = $checkproducts->id;
                            }
                            if (is_object($checkproducts) && ($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking') || $checkproducts->is_type('lottery'))) {
                                $term = get_the_terms($product, 'product_cat');
                                if (is_array($term)) {
                                    foreach ($allcategories as $mycategory) {
                                        if ($_POST['enabledisablepoints'] == '1') {
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_point_price_category', 'yes');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'yes');
                                        } else {
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_point_price_category', 'no');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'no');
                                        }
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_point_price_type', $_POST['pointpricetype']);
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price_type', $_POST['pointpricingtype']);

                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem__points', $_POST['pricepoints']);



                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'point_price_category_type', $_POST['pointpricetype']);

                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_points_price', $_POST['pricepoints']);
                                    }
                                }
                            } elseif (is_object($checkproducts) && (rs_check_variable_product_type($checkproducts) || ($checkproducts->is_type('variable-subscription')))) {
                                if (is_array($checkproducts->get_available_variations())) {
                                    foreach ($checkproducts->get_available_variations() as $getvariation) {
                                        $term = get_the_terms($id, 'product_cat');
                                        if (is_array($term)) {
                                            foreach ($allcategories as $mycategory) {
                                                if ($_POST['enabledisablepoints'] == '1') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_point_price_category', 'yes');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '1');
                                                } else {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_point_price_category', 'no');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '2');
                                                }

                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price_type', $_POST['pointpricetype']);


                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], 'price_points', $_POST['pricepoints']);


                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'point_price_category_type', $_POST['pointpricetype']);

                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_points_price', $_POST['pricepoints']);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        echo json_encode("success");
                    } else {
                        $mycategorylist = $_POST['selectedcategories'];
                        $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                        $products = get_posts($args);
                        foreach ($products as $product) {
                            $checkproducts = rs_get_product_object($product);
                            if ((float) $woocommerce->version >= (float) '3.0') {
                                $id = $checkproducts->get_id();
                            } else {
                                $id = $checkproducts->id;
                            }
                            if (is_object($checkproducts) && ($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking') || $checkproducts->is_type('lottery'))) {
                                if (is_array($mycategorylist)) {
                                    foreach ($mycategorylist as $eachlist) {
                                        $term = get_the_terms($product, 'product_cat');
                                        if (is_array($term)) {
                                            foreach ($term as $termidlist) {
                                                if ($eachlist == $termidlist->term_id) {
                                                    if ($_POST['enabledisablepoints'] == '1') {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_point_price_category', 'yes');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'yes');
                                                    } else {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_point_price_category', 'no');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'no');
                                                    }

                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_point_price_type', $_POST['pointpricetype']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price_type', $_POST['pointpricingtype']);

                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem__points', $_POST['pricepoints']);

                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'point_price_category_type', $_POST['pointpricingtype']);


                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_points_price', $_POST['pricepoints']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'point_price_category_type', $_POST['pointpricetype']);
                                                }
                                            }
                                        }
                                    }
                                }
                            } elseif (is_object($checkproducts) && (rs_check_variable_product_type($checkproducts) || ($checkproducts->is_type('variable-subscription')))) {
                                $mycategorylist = $_POST['selectedcategories'];
                                if (is_array($checkproducts->get_available_variations())) {
                                    foreach ($checkproducts->get_available_variations() as $getvariation) {
                                        if (is_array($mycategorylist)) {
                                            foreach ($mycategorylist as $eachlist) {
                                                $term = get_the_terms($id, 'product_cat');
                                                if (is_array($term)) {
                                                    foreach ($term as $termidlist) {
                                                        if ($eachlist == $termidlist->term_id) {
                                                            if ($_POST['enabledisablepoints'] == '1') {
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_point_price_category', 'yes');
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '1');
                                                            } else {
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_point_price_category', 'no');
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '2');
                                                            }

                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price_type', $_POST['pointpricetype']);

                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], 'price_points', $_POST['pricepoints']);
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'point_price_category_type', $_POST['pointpricetype']);
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_pricing_type', $_POST['pointpricingtype']);


                                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'point_price_category_type', $_POST['pointpricingtype']);

                                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_points_price', $_POST['pricepoints']);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        echo json_encode("success");
                    }
                }

                if ($_POST['proceedanyway'] == '0') {
                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    foreach ($products as $product) {
                        $checkproducts = rs_get_product_object($product);
                        if (is_object($checkproducts) && ($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking'))) {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'no');
                        } elseif (is_object($checkproducts) && (rs_check_variable_product_type($checkproducts) || ($checkproducts->is_type('variable-subscription')))) {
                            if (is_array($checkproducts->get_available_variations())) {
                                foreach ($checkproducts->get_available_variations() as $getvariation) {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '2');
                                }
                            }
                        }
                    }
                    echo json_encode("success");
                }
                exit();
            }
        }

        public static function process_chunk_ajax_request_in_rewardsystem_point_price() {
            if (isset($_POST['ids'])) {
                $products = $_POST['ids'];
                foreach ($products as $product) {
                    $checkproduct = rs_get_product_object($product);
                    if (is_object($checkproduct) && ($checkproduct->is_type('simple') || ($checkproduct->is_type('subscription')) || $checkproduct->is_type('booking') || $checkproduct->is_type('lottery'))) {
                        if ($_POST['enabledisablepoints'] == '1') {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'yes');
                        } else {
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'no');
                        }
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price_type', $_POST['pointpricingtype']);

                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_point_price_type', $_POST['pointpricetype']);
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem__points', $_POST['pricepoints']);
                    } else {
                        if (is_object($checkproduct) && (rs_check_variable_product_type($checkproduct) || ($checkproduct->is_type('variable-subscription')))) {
                            if (is_array($checkproduct->get_available_variations())) {
                                foreach ($checkproduct->get_available_variations() as $getvariation) {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', $_POST['enabledisablepoints']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_pricing_type', $_POST['pointpricingtype']);

                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], 'price_points', $_POST['pricepoints']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price_type', $_POST['pointpricetype']);
                                }
                            }
                        }
                    }
                }
            }

            exit();
        }

        public static function rs_include_products_for_point_pricing() {
            $field_id = "rs_include_products_for_point_pricing";
            $field_label = "Include Product(s)";
            $getproducts = get_option('rs_include_products_for_point_pricing');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

        public static function rs_exclude_products_for_point_pricing() {
            $field_id = "rs_exclude_products_for_point_pricing";
            $field_label = "Exclude Product(s)";
            $getproducts = get_option('rs_exclude_products_for_point_pricing');
            echo rs_function_to_add_field_for_product_select($field_id, $field_label, $getproducts);
        }

    }

    RSPointPriceModule::init();
}