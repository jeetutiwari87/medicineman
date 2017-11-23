<?php

/**
 * Foodfarm Settings Options
 */
if (!class_exists('Framework_Foodfarm_Settings')) {

    class Framework_Foodfarm_Settings {

        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (true == Redux_Helpers::isTheme(__FILE__)) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }

        public function initSettings() {
            // Create the sections and fields
            $this->foodfarm_get_setting_sections();
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->foodfarm_get_setting_arguments());
        }
        // function dynamic_section($sections) {

        //     return $sections;
        // }
        public function foodfarm_get_setting_sections() {
            $page_layout = foodfarm_layouts();
            $sidebar_positions = foodfarm_sidebar_position();
            unset($page_layout['default']);
            unset($sidebar_positions['default']);
                $this->sections[] =  array(
                    'icon' => 'el-icon-dashboard',
                    'icon_class' => 'icon',
                    'title' => esc_html__('General', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => '1',
                            'type' => 'info',
                            'desc' => esc_html__('Layout default', 'foodfarm')
                        ),
                        array(
                            'id' => 'layout',
                            'type' => 'button_set',
                            'title' => esc_html__('Layout', 'foodfarm'),
                            'options' => $page_layout,
                            'default' => 'fullwidth'
                        ),
                        array(
                            'id' => 'sidebar-position',
                            'type' => 'button_set',
                            'title' => esc_html__('Sidebar Position', 'foodfarm'),
                            'options' => $sidebar_positions,
                            'default' => 'none'
                        ),
                        array(
                            'id' => 'sidebar',
                            'type' => 'select',
                            'title' => esc_html__('Select Sidebar', 'foodfarm'),
                            'required' => array('sidebar-position', 'equals', array(
                                    'left-sidebar',
                                    'right-sidebar'
                                )),
                            'data' => 'sidebars',
                            'default' => 'general-sidebar'
                        ),
                        array(
                            'id' => 'show-breadcrumbs',
                            'type' => 'switch',
                            'title' => esc_html__('Show Breadcrumbs', 'foodfarm'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'foodfarm'),
                            'off' => esc_html__('No', 'foodfarm'),
                        ),
                        array(
                            'id' => 'show-pagetitle',
                            'type' => 'switch',
                            'title' => esc_html__('Show Page Title', 'foodfarm'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'foodfarm'),
                            'off' => esc_html__('No', 'foodfarm'),
                        ),
                        array(
                            'id' => '1',
                            'type' => 'info',
                            'desc' => esc_html__('Logo, Icon', 'foodfarm')
                        ),
                        array(
                            'id' => 'logo',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo.png',
                                'height' => 60,
                                'wide' => 200
                            )
                        ),
                        array(
                            'id' => 'logo_4',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Home 4', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-4.png',
                                'height' => 60,
                                'wide' => 200
                            )
                        ),
                        array(
                            'id' => 'logo_5',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Home 5', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-5.png',
                            )
                        ),
                        array(
                            'id' => 'logo_6',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Home 6', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-6.png',
                            )
                        ),
                        array(
                            'id' => 'logo_7',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Home 7', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-7.png',
                            )
                        ), 
                        array(
                            'id' => 'logo_8',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Home 8', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-8.png',
                            )
                        ),   
                        array(
                            'id' => 'logo_9',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Home 9', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-9.png',
                            )
                        ),                                                                      
                        array(
                            'id' => 'favicon',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Favicon', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/favicon.ico'
                            )
                        ),
                        array(
                            'id' => '1',
                            'type' => 'info',
                            'desc' => esc_html__('404 Page', 'foodfarm')
                        ),
                        array(
                            'id' => '404-bg-image',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Background image', 'foodfarm'),
                            'desc' => esc_html__('Background image for 404 page', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/background_404.png',
                            )
                        ),
                        array(
                            'id' => 'logo-404',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo', 'foodfarm'),
                            'desc' => esc_html__('Logo for 404 page', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-404.png',
                                'height' => 56,
                                'wide' => 305
                            )
                        ),
                        array(
                            'id' => 'js-code',
                            'type' => 'ace_editor',
                            'title' => esc_html__('JS Code', 'foodfarm'),
                            'subtitle' => esc_html__('Paste your JS code here.', 'foodfarm'),
                            'mode' => 'javascript',
                            'theme' => 'chrome',
                            'default' => "jQuery(document).ready(function(){});"
                        )
                    )
                );
                $this->sections[] = array(
                    'icon' => 'el-icon-css',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Skin', 'foodfarm'),
                    'fields' => array(
                    )
                );
                $this->sections[] = array(
                    'icon_class' => 'icon',
                    'subsection' => true,
                    'title' => esc_html__('General', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => 'general-bg',
                            'type' => 'background',
                            'title' => esc_html__('General Background', 'foodfarm'),
                            'default' => array(
                                'background-color' => '#fff',
                                'background-image' => '',
                                'background-size' => 'inherit',
                                'background-repeat' => 'no-repeat',
                                'background-position' => 'center center',
                                'background-attachment' => 'inherit'
                            )
                        ),
                        array(
                            'id' => 'general-font',
                            'type' => 'typography',
                            'title' => esc_html__('General Font', 'foodfarm'),
                            'google' => true,
                            'subsets' => false,
                            'font-style' => false,
                            'text-align' => false,
                            'default' => array(
                                'color' => "#696969",
                                'google' => true,
                                'font-weight' => '400',
                                'font-family' => 'Lato',
                                'font-size' => '14px',
                                'line-height' => '24px'
                            ),
                        ),
                        array(
                            'id' => 'primary-color',
                            'type' => 'color',
                            'title' => esc_html__('Primary color', 'foodfarm'),
                            'default' => '#94c347',
                            'validate' => 'color',
                            'transparent' => false
                        ),
                        array(
                            'id' => 'highlight-color',
                            'type' => 'color',
                            'title' => esc_html__('Highlight color', 'foodfarm'),
                            'default' => '#85b635',
                            'validate' => 'color',
                            'description' => esc_html__('change links color when hover/active.', 'foodfarm'),
                            'transparent' => false
                        )
                    )
                );
                $this->sections[] =array(
                    'icon_class' => 'icon',
                    'subsection' => true,
                    'title' => esc_html__('Breadcrumbs', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => 'breadcrumbs-bg',
                            'type' => 'background',
                            'title' => esc_html__('Background', 'foodfarm'),
                            'background-color' => false,
                            'default' => array(
                                'background-color' => '#fff',
                                'background-image' => get_template_directory_uri() . '/images/bg-breadcrumb.jpg',
                                'background-size' => 'cover',
                                'background-repeat' => 'no-repeat',
                                'background-position' => 'center center',
                                'background-attachment' => 'inherit'
                            ),
                            'output' => array('.side-breadcrumb'),
                        ),
                    )
                );
                $this->sections[] =array(
                    'icon_class' => 'icon',
                    'subsection' => true,
                    'title' => esc_html__('Typography', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => 'h1-font',
                            'type' => 'typography',
                            'title' => esc_html__('H1 Font', 'foodfarm'),
                            'google' => true,
                            'subsets' => false,
                            'font-style' => false,
                            'text-align' => false,
                            'font-weight' => false,
                            'line-height' => false,
                            'default' => array(
                                'color' => "#696969",
                                'google' => true,
                                'font-family' => 'inherit',
                                'font-size' => '36px',
                            ),
                        ),
                        array(
                            'id' => 'h2-font',
                            'type' => 'typography',
                            'title' => esc_html__('H2 Font', 'foodfarm'),
                            'google' => true,
                            'subsets' => false,
                            'font-style' => false,
                            'text-align' => false,
                            'font-weight' => false,
                            'line-height' => false,
                            'default' => array(
                                'color' => "#696969",
                                'google' => true,
                                'font-family' => 'inherit',
                                'font-size' => '36px',
                            ),
                        ),
                        array(
                            'id' => 'h3-font',
                            'type' => 'typography',
                            'title' => esc_html__('H3 Font', 'foodfarm'),
                            'google' => true,
                            'subsets' => false,
                            'font-style' => false,
                            'text-align' => false,
                            'font-weight' => false,
                            'line-height' => false,
                            'default' => array(
                                'color' => "#696969",
                                'google' => true,
                                'font-family' => 'inherit',
                                'font-size' => '25px',
                            ),
                        ),
                        array(
                            'id' => 'h4-font',
                            'type' => 'typography',
                            'title' => esc_html__('H4 Font', 'foodfarm'),
                            'google' => true,
                            'subsets' => false,
                            'font-style' => false,
                            'text-align' => false,
                            'font-weight' => false,
                            'line-height' => false,
                            'default' => array(
                                'color' => "#696969",
                                'google' => true,
                                'font-family' => 'inherit',
                                'font-size' => '16px',
                            ),
                        ),
                        array(
                            'id' => 'h5-font',
                            'type' => 'typography',
                            'title' => esc_html__('H5 Font', 'foodfarm'),
                            'google' => true,
                            'subsets' => false,
                            'font-style' => false,
                            'text-align' => false,
                            'font-weight' => false,
                            'line-height' => false,
                            'default' => array(
                                'color' => "#696969",
                                'google' => true,
                                'font-family' => 'inherit',
                                'font-size' => '14px',
                            ),
                        ),
                        array(
                            'id' => 'h6-font',
                            'type' => 'typography',
                            'title' => esc_html__('H6 Font', 'foodfarm'),
                            'google' => true,
                            'subsets' => false,
                            'font-style' => false,
                            'text-align' => false,
                            'font-weight' => false,
                            'line-height' => false,
                            'default' => array(
                                'color' => "#696969",
                                'google' => true,
                                'font-family' => 'inherit',
                                'font-size' => '12px',
                            ),
                        ),
                    )
                );
                $this->sections[] =array(
                    'icon_class' => 'icon',
                    'subsection' => true,
                    'title' => esc_html__('Custom', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => 'custom-css-code',
                            'type' => 'ace_editor',
                            'title' => esc_html__('CSS', 'foodfarm'),
                            'subtitle' => esc_html__('Enter CSS code here.', 'foodfarm'),
                            'mode' => 'css',
                            'theme' => 'monokai',
                            'default' => ""
                        ),
                    )
                );
                $this->sections[] = $this->foodfarm_add_header_section_options();
                // Header Styling
                    $this->sections[] =array(
                        'icon_class' => 'icon',
                        'subsection' => true,
                        'title' => esc_html__('Header Style', 'foodfarm'),
                        'fields' => array(
                            array(
                                'id'       => 'logo_width',
                                'type'     => 'dimensions',
                                'units'    => array('em','px','%'),
                                'title'    => esc_html__('Set logo image width', 'foodfarm'),
                                'subtitle' => esc_html__('Allow users to set width for header logo image', 'foodfarm'),
                                'height'   => false,
                            ),                        
                            array(
                                'id'             => 'menu_spacing',
                                'type'           => 'spacing',
                                'mode'           => 'margin',
                                'units'          => array('px'),
                                'units_extended' => 'false',
                                'title'          => esc_html__('Set padding for menu items', 'foodfarm'),
                                'subtitle'       => esc_html__('Allow users to ajust menu item spacing', 'foodfarm'),
                                'required' => array('header-style', 'equals', array(
                                        '1','6','2'
                                    )),                              
                            ),                         
                            array(
                                'id' => 'header-style',
                                'type' => 'select',
                                'title' => esc_html__('Select header for styling', 'foodfarm'),
                                'options' => foodfarm_header_types(),
                                'default' => '1',
                            ),                         
                            array(
                                'id' => 'header-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header background color', 'foodfarm'),
                                'default' => '#3a3a3b',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '1','3'
                                )),                               
                            ),
                        // Header 1
                            array(
                                'id' => 'header-top-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header top background color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '1',
                                )),                            
                            ),
                            array(
                                'id' => 'header-top_color',
                                'type' => 'color',
                                'title' => esc_html__('Header top text color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '1',
                                )),                            
                            ),                         
                            array(
                                'id' => 'header-menu-color',
                                'type' => 'color',
                                'title' => esc_html__('Header menu color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '1',
                                )),                            
                            ),  
                            array(
                                'id' => 'header-menu-hcolor',
                                'type' => 'color',
                                'title' => esc_html__('Header menu hover color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '1',
                                )),  
                                'output' => array('.mega-menu li a:hover, .mega-menu li a:focus'),                          
                            ),                               
                            array(
                                'id' => 'header-nav-border_color',
                                'type' => 'color',
                                'title' => esc_html__('Header main navigation border color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '1',
                                )),                            
                            ),                          
                                                                          
                            
                        // End Header 1  
                        // Header 2
                            array(
                                'id' => 'header2-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header2 background color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '2'
                                )),                               
                            ),
                            array(
                                'id' => 'header2-menu-color',
                                'type' => 'color',
                                'title' => esc_html__('Header2 menu color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '2'
                                )),                               
                            ),                     
                            array(
                                'id' => 'header2-nav-border_color',
                                'type' => 'color',
                                'title' => esc_html__('Header2 main navigation border color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '2'
                                )),                               
                            ),  
                            array(
                                'id' => 'header2-top_color',
                                'type' => 'color',
                                'title' => esc_html__('Header2 top color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '2'
                                )),                               
                            ),                           
                                               
                        // End Header 2  
                        // Header 3
                            array(
                                'id' => 'header3-menu-color',
                                'type' => 'color',
                                'title' => esc_html__('Header3 menu and icon color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '3'
                                )),                               
                            ),                                           
                            array(
                                'id' => 'header3-border_color',
                                'type' => 'color',
                                'title' => esc_html__('Header3 icon border color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '3'
                                )),                               
                            ),                                           
                        // End Header 3  
                        // Header 4
                            array(
                                'id' => 'header4-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header4 main navigation background color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '4'
                                )),                               
                            ),  
                            array(
                                'id' => 'header4-top-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header top background color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '4'
                                )),                               
                            ), 
                            array(
                                'id' => 'header4-top-color',
                                'type' => 'color',
                                'title' => esc_html__('Header top text color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '4'
                                )),                               
                            ),                                                                      
                            array(
                                'id' => 'header4-menu-color',
                                'type' => 'color',
                                'title' => esc_html__('Header4 menu and icon color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '4'
                                )),                               
                            ),                                               
                                               
                        // End Header 4 
                        // Header 5
                            array(
                                'id' => 'header5-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header5 background color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '5'
                                )),                               
                            ),  
                            array(
                                'id' => 'header5-top-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header top background color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '5'
                                )),                               
                            ), 
                            array(
                                'id' => 'header5-top-color',
                                'type' => 'color',
                                'title' => esc_html__('Header top text color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '5'
                                )),                               
                            ),                                                                      
                            array(
                                'id' => 'header5-menu-color',
                                'type' => 'color',
                                'title' => esc_html__('Header5 menu and icon color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '5'
                                )),                               
                            ),                                               
                                               
                        // End Header 5  
                        // Header 6
                            array(
                                'id' => 'header6-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header6 background color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '6'
                                )),                               
                            ),   
                            array(
                                'id' => 'header6-top-color',
                                'type' => 'color',
                                'title' => esc_html__('Header top text color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '6'
                                )),                               
                            ),                                                                      
                            array(
                                'id' => 'header6-menu-color',
                                'type' => 'color',
                                'title' => esc_html__('Header6 menu and icon color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '6'
                                )),                               
                            ),                                               
                                               
                        // End Header6                                                                                                   
                            array(
                                'id' => 'header7-top-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header top background color', 'foodfarm'),
                                'default' => '#b7dc36',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '7',
                                )),                            
                            ),                        
                            array(
                                'id' => 'header7-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header background color', 'foodfarm'),
                                'default' => '#ffffff',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '7',
                                )),                            
                            ), 
                            array(
                                'id' => 'header7-top-text-color',
                                'type' => 'color',
                                'title' => esc_html__('Header top text color', 'foodfarm'),
                                'default' => '#ffffff',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '7',
                                )),                            
                            ),  
                            array(
                                'id' => 'header7-menu-text',
                                'type' => 'color',
                                'title' => esc_html__('Header menu color', 'foodfarm'),
                                'default' => '#777777',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '7','8',
                                )),                            
                            ),  
                        // Options for Header 8
                            array(
                                'id' => 'header8-top-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header top background color', 'foodfarm'),
                                'default' => '#faf8f5',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '8',
                                )),                            
                            ),                        
                            array(
                                'id' => 'header8-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header background color', 'foodfarm'),
                                'default' => '#ffffff',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '8',
                                )),                            
                            ), 
                            array(
                                'id' => 'header8-top-text-color',
                                'type' => 'color',
                                'title' => esc_html__('Header top text color', 'foodfarm'),
                                'default' => '#555555',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '8',
                                )),                            
                            ),  
                            array(
                                'id' => 'header8-text-phonenumber_color',
                                'type' => 'color',
                                'title' => esc_html__('Header contact text color', 'foodfarm'),
                                'default' => '#999999',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '8',
                                )),                            
                            ),                         
                            array(
                                'id' => 'header8-menu-hover',
                                'type' => 'color',
                                'title' => esc_html__('Header menu color on hover', 'foodfarm'),
                                'default' => '#222222',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '8',
                                )),                            
                            ),  
                            array(
                                'id' => 'header8-menu-border',
                                'type' => 'color',
                                'title' => esc_html__('Header Navigation Border', 'foodfarm'),
                                'default' => '#eeeeee',
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '8',
                                )),                            
                            ),
                        //Header 9
                            array(
                                'id' => 'header9-bg',
                                'type' => 'color',
                                'title' => esc_html__('Header9 background color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '9'
                                )),                               
                            ),  
                            array(
                                'id' => 'header9-menu-color',
                                'type' => 'color',
                                'title' => esc_html__('Header menu and icon color', 'foodfarm'),
                                'validate' => 'color',
                                'required' => array('header-style', 'equals', array(
                                        '9'
                                )),                               
                            ), 
                        //End option for header 9

                        )
                    );
                // End Header Styling
                $this->sections[] = array(
                    'icon' => 'el-icon-website',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Footer', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => 'footer-type',
                            'type' => 'image_select',
                            'title' => esc_html__('Footer Type', 'foodfarm'),
                            'subtitle' => esc_html__('Each page will have option for select footer type. Footer selection in each page will have higher priority than this general selection.','foodfarm'),                              
                            'options' => $this->foodfarm_footer_types(),
                            'default' => '1'
                        ),
                        array(
                            'id' => 'logo-footer-1',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Footer', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '1',
                                    '3'
                                )),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo_footer.png',
                            )
                        ),
                        array(
                            'id' => 'logo-footer-2',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Footer 2', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '2'
                                )),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-2.png',
                            )
                        ),
                        array(
                            'id' => 'logo-footer-3',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Footer 3', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '3'
                                )),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-3.png',
                            )
                        ),
                        array(
                            'id' => 'logo-footer-4',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Footer 4', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '4'
                                )),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-footer-4.png',
                            )
                        ),
                        array(
                            'id' => 'logo-footer-5',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Footer 5', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '5'
                                )),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-footer-5.png',
                            )
                        ),
                        array(
                            'id' => 'logo-footer-6',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Logo Footer 6', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '6'
                                )),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/logo-footer-6.png',
                            )
                        ),
                        array(
                            'id' => 'show-newsletter',
                            'type' => 'switch',
                            'title' => esc_html__('Show Newsletter', 'foodfarm'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'foodfarm'),
                            'off' => esc_html__('No', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                '9'
                                )),                             
                        ),                        
                        array(
                            'id' => "footer9-newletter_title",
                            'type' => 'text',
                            'title' => esc_html__('Newsletter title', 'foodfarm'),
                            'default' => esc_html__('Signup Newsletter', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '9'
                                )),                             
                        ),                        
                        array(
                            'id' => 'footer7_top_bg',
                            'type' => 'background',
                            'title' => esc_html__('Background footer top', 'foodfarm'),
                            'background-color' => false,
                            'default' => array(
                                'background-color' => '#fff',
                                'background-image' => get_template_directory_uri() . '/images/bg-footer-top.jpg',
                                'background-size' => 'cover',
                                'background-repeat' => 'no-repeat',
                                'background-position' => 'center center',
                                'background-attachment' => 'inherit'
                            ),
                            'required' => array('footer-type', 'equals', array(
                                    '7'
                                )),                            
                        ),                        
                        array(
                            'id' => "footer-copyright",
                            'type' => 'textarea',
                            'title' => esc_html__('Copyright', 'foodfarm'),
                            'default' => esc_html__('Coppyright &copy; 2017 ', 'foodfarm') . '<a href="#">' . esc_html__('Food', 'foodfarm') . '<span>' . esc_html__('farm', 'foodfarm') . '</span></a>' . '.' . '<br>' . esc_html__(' All Rights Reserved. Powered by ', 'foodfarm') . '<a target="_blank" href="http://arrowhitech.com">' . esc_html__('ArrowHitech', 'foodfarm') . '</a>',
                        ),
                        array(
                            'id' => "footer7-copyright",
                            'type' => 'textarea',
                            'title' => esc_html__('Copyright', 'foodfarm'),
                            'default' => esc_html__('© Food Farm 2017 All rights reserved', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '7'
                                )),                             
                        ),  
                        array(
                            'id' => "footer8-copyright",
                            'type' => 'textarea',
                            'title' => esc_html__('Copyright', 'foodfarm'),
                            'default' => esc_html__('© 2017 Arrowhitech Demo Store. All Rights Reserved.', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '8'
                                )),                             
                        ),                                               
                        array(
                            'id' => 'show-payment',
                            'type' => 'switch',
                            'title' => esc_html__('Show Payment', 'foodfarm'),
                            'default' => false,
                            'on' => esc_html__('Yes', 'foodfarm'),
                            'off' => esc_html__('No', 'foodfarm'),
                            'required' => array('footer-type', 'equals', array(
                                    '1',
                                    '2','3','4','5','6','9'
                                )),                             
                        ),
                        array(
                            'id' => 'link-paypal',
                            'type' => 'text',
                            'title' => esc_html__('Paypal', 'foodfarm'),
                            'required' => array('show-payment', 'equals', 1),
                            'placeholder' => esc_html__('http://', 'foodfarm')
                        ),
                        array(
                            'id' => 'link-visa',
                            'type' => 'text',
                            'title' => esc_html__('Visa', 'foodfarm'),
                            'required' => array('show-payment', 'equals', 1),
                            'placeholder' => esc_html__('http://', 'foodfarm')
                        ),
                        array(
                            'id' => 'link-mastercard',
                            'type' => 'text',
                            'title' => esc_html__('Master card', 'foodfarm'),
                            'required' => array('show-payment', 'equals', 1),
                            'placeholder' => esc_html__('http://', 'foodfarm')
                        ),
                        array(
                            'id' => 'link-discover',
                            'type' => 'text',
                            'title' => esc_html__('Discover', 'foodfarm'),
                            'required' => array('show-payment', 'equals', 1),
                            'placeholder' => esc_html__('http://', 'foodfarm')
                        ),
                        array(
                            'id' => 'link-amex',
                            'type' => 'text',
                            'title' => esc_html__('Amex', 'foodfarm'),
                            'required' => array('show-payment', 'equals', 1),
                            'placeholder' => esc_html__('http://', 'foodfarm')
                        ),
                    )
                );
                // Footer Styling
                    $this->sections[] =array(
                        'icon_class' => 'icon',
                        'subsection' => true,
                        'title' => esc_html__('Footer Style', 'foodfarm'),
                        'fields' => array(
                            array(
                                'id' => 'footer-style',
                                'type' => 'select',
                                'title' => esc_html__('Select footer for styling', 'foodfarm'),
                                'options' => foodfarm_footer_types(),
                                'default' => '1',
                            ),                        
                            array(
                                'id' => 'footer-bg',
                                'type' => 'color',
                                'title' => esc_html__('Footer background color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '1',
                                        '3'
                                    )),
                                'default' => '#3a3a3b',
                                'validate' => 'color',
                                'output' => array('.footer-top')
                            ),
                            array(
                                'id' => 'footer-left-bg',
                                'type' => 'background',
                                'title' => esc_html__('Left Footer Background', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '1',
                                        '2',
                                        '3',
                                    )),
                                'default' => array(
                                    'background-color' => '',
                                    'background-image' => get_template_directory_uri() . '/images/bg-footer.jpg',
                                    'background-size' => 'cover',
                                    'background-repeat' => 'no-repeat',
                                    'background-position' => 'center center',
                                    'background-attachment' => 'inherit'
                                )
                            ),                        
                            array(
                                'id' => 'footer-left-color',
                                'type' => 'color',
                                'title' => esc_html__('Left Footer text color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '1',
                                    )),
                                'validate' => 'color',
                            ),  
                            array(
                                'id' => 'footer-wtitle-color',
                                'type' => 'color',
                                'title' => esc_html__('Footer widget title color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '1','3','4','6','5'
                                    )),
                                'validate' => 'color',
                            ), 
                            array(
                                'id' => 'footer-link-color',
                                'type' => 'color',
                                'title' => esc_html__('Footer link color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '1','2','3','4','5','6'
                                    )),
                                'validate' => 'color',
                            ),
                            array(
                                'id' => 'footer-col-border',
                                'type' => 'color',
                                'title' => esc_html__('Footer columns border color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '1','3'
                                    )),
                                'validate' => 'color',
                            ),                                                                                               
                            array(
                                'id' => 'footer-bottom-bg',
                                'type' => 'color',
                                'title' => esc_html__('Bottom Footer background color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '1',
                                        '3'
                                    )),
                                'validate' => 'color',
                            ), 
                            array(
                                'id' => 'footer-bottom-text-color',
                                'type' => 'color',
                                'title' => esc_html__('Bottom Footer text color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '1','2','3','4','5'
                                    )),
                                'validate' => 'color',
                            ),                                                                       
                            array(
                                'id' => 'footer-bg-2',
                                'type' => 'color',
                                'title' => esc_html__('Footer 2 background color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '2',
                                    )),
                                'default' => '#f5f5f5',
                                'validate' => 'color',
                            ),
                            array(
                                'id' => 'footer2-bottom-bg',
                                'type' => 'color',
                                'title' => esc_html__('Bottom Footer background color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '2',
                                    )),
                                'validate' => 'color',
                            ),                         
                            array(
                                'id' => 'footer2-wtitle-color',
                                'type' => 'color',
                                'title' => esc_html__('Footer 2 widget title color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '2',
                                    )),
                                'validate' => 'color',
                            ),   
                            array(
                                'id' => 'footer2-left-color',
                                'type' => 'color',
                                'title' => esc_html__('Left Footer text color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '2',
                                    )),
                                'validate' => 'color',
                            ),   
                            array(
                                'id' => 'footer3-left-color',
                                'type' => 'color',
                                'title' => esc_html__('Left Footer text color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '3',
                                    )),
                                'validate' => 'color',
                            ),   
                            array(
                                'id' => 'footer-bg-4',
                                'type' => 'color',
                                'title' => esc_html__('Footer background color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '4','5'
                                    )),
                                'default' => '#f5f5f5',
                                'validate' => 'color',
                            ), 
                            array(
                                'id' => 'footer4-left-color',
                                'type' => 'color',
                                'title' => esc_html__('Left Footer text color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '4','5'
                                    )),
                                'validate' => 'color',
                            ),                                                 
                            array(
                                'id' => 'footer4-bottom-bg',
                                'type' => 'color',
                                'title' => esc_html__('Bottom Footer background color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '4','5'
                                    )),
                                'validate' => 'color',
                            ),                                                                                
                            array(
                                'id' => 'footer-bg-7',
                                'type' => 'color',
                                'title' => esc_html__('Footer background color 7', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '7',
                                    )),
                                'default' => '#fff',
                                'validate' => 'color',
                            ),                                               
                            array(
                                'id' => 'footer-text-7',
                                'type' => 'color',
                                'title' => esc_html__('Footer text color 7', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '7',
                                    )),
                                'default' => '#555',
                                'validate' => 'color',
                            ), 
                            array(
                                'id' => 'footer-title-7',
                                'type' => 'color',
                                'title' => esc_html__('Footer title color 7', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '7',
                                    )),
                                'default' => '#222',
                                'validate' => 'color',
                            ),  
                            array(
                                'id' => 'footer7_bottom_color',
                                'type' => 'color',
                                'title' => esc_html__('Footer Copyright text color 7', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '7',
                                    )),
                                'default' => '#999',
                                'validate' => 'color',
                            ),  
                            array(
                                'id' => 'footer-bg-8',
                                'type' => 'color',
                                'title' => esc_html__('Footer background color 8', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '8',
                                    )),
                                'default' => '#faf8f5',
                                'validate' => 'color',
                            ),  
                            array(
                                'id' => 'footer-text-8',
                                'type' => 'color',
                                'title' => esc_html__('Footer text color 8', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '8',
                                    )),
                                'default' => '#555',
                                'validate' => 'color',
                            ),                                                                                                             
                            array(
                                'id' => 'footer-bg-6',
                                'type' => 'background',
                                'title' => esc_html__('Footer 6 Background', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '6',
                                )),
                                'default' => array(
                                    'background-color' => '',
                                    'background-image' => get_template_directory_uri() . '/images/bg-footer-6.jpg',
                                    'background-size' => 'cover',
                                    'background-repeat' => 'no-repeat',
                                    'background-position' => 'center center',
                                    'background-attachment' => 'inherit'
                                )
                            ),
                            array(
                                'id' => 'footer6-bottom-text-color',
                                'type' => 'color',
                                'title' => esc_html__('Bottom Footer 6 text color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '6'
                                    )),
                                'validate' => 'color',
                            ),  
                            array(
                                'id' => 'footer6-left-color',
                                'type' => 'color',
                                'title' => esc_html__('Footer 6 contact info text color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '6'
                                    )),
                                'validate' => 'color',
                            ), 
                            //Footer 9
                            array(
                                'id' => 'footer9-newletter_bg',
                                'type' => 'color',
                                'title' => esc_html__('Footer 9 Background for Newsletter section', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '9',
                                )),
                                'validate' => 'color',
                            ),   
                            array(
                                'id' => 'footer9-newletter-color',
                                'type' => 'color',
                                'title' => esc_html__('Title color for Newsletter', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '9',
                                    )),
                                'validate' => 'color',
                            ),                                                      
                            array(
                                'id' => 'footer9-bg',
                                'type' => 'background',
                                'title' => esc_html__('Footer 9 Background', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '9',
                                )),
                                'default' => array(
                                    'background-color' => '',
                                    'background-image' => get_template_directory_uri() . '/images/bg-footer-9.jpg',
                                    'background-size' => 'cover',
                                    'background-repeat' => 'no-repeat',
                                    'background-position' => 'center center',
                                    'background-attachment' => 'inherit'
                                )
                            ), 
                            array(
                                'id' => 'footer9-title-color',
                                'type' => 'color',
                                'title' => esc_html__('Footer title color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '9',
                                    )),
                                'validate' => 'color',
                            ), 
                            array(
                                'id' => 'footer9-text-color',
                                'type' => 'color',
                                'title' => esc_html__('Footer text and link color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '9',
                                    )),
                                'validate' => 'color',
                            ),  
                            array(
                                'id' => 'footer9-social-color',
                                'type' => 'color',
                                'title' => esc_html__('Footer social link color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '9',
                                    )),
                                'validate' => 'color',
                            ),                                                                           
                            array(
                                'id' => 'footer9-bottom-color',
                                'type' => 'color',
                                'title' => esc_html__('Bottom footer text color', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '9',
                                    )),
                                'validate' => 'color',
                            ), 
                            array(
                                'id' => 'footer9-bottom-bg',
                                'type' => 'color',
                                'title' => esc_html__('Bottom footer background', 'foodfarm'),
                                'required' => array('footer-style', 'equals', array(
                                        '9',
                                    )),
                                'validate' => 'color',
                            ),                                                                                                     
                        )
                    );  
                // End Footer Styling
                $this->sections[] =array(
                    'icon' => 'el-icon-brush',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Countdown', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => "under_contr-day",
                            'type' => 'text',
                            'title' => esc_html__('Text display under day number', 'barber'),
                            'default' => esc_html__('Days', 'barber')
                        ),  
                        array(
                            'id' => "under_contr-hour",
                            'type' => 'text',
                            'title' => esc_html__('Text display under hour number', 'barber'),
                            'default' => esc_html__('Hours', 'barber')
                        ),
                        array(
                            'id' => "under_contr-min",
                            'type' => 'text',
                            'title' => esc_html__('Text display under minute number', 'barber'),
                            'default' => esc_html__('Minutes', 'barber')
                        ),
                        array(
                            'id' => "under_contr-sec",
                            'type' => 'text',
                            'title' => esc_html__('Text display under secs number', 'barber'),
                            'default' => esc_html__('Seconds', 'barber')
                        ),  
                    )
                );               
                $this->sections[] =array(
                    'icon' => 'el-icon-brush',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Blog & Single Blog', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => '1',
                            'type' => 'info',
                            'desc' => esc_html__('Blog layout default', 'foodfarm')
                        ),
                        array(
                            'id' => 'post-layout',
                            'type' => 'button_set',
                            'title' => esc_html__('Layout', 'foodfarm'),
                            'options' => $page_layout,
                            'default' => 'fullwidth'
                        ),
                        array(
                            'id' => 'post-sidebar-position',
                            'type' => 'button_set',
                            'title' => esc_html__('Sidebar Position', 'foodfarm'),
                            'options' => $sidebar_positions,
                            'default' => 'right-sidebar'
                        ),
                        array(
                            'id' => 'post-sidebar',
                            'type' => 'select',
                            'title' => esc_html__('Select Sidebar', 'foodfarm'),
                            'required' => array('post-sidebar-position', 'equals', array(
                                    'left-sidebar',
                                    'right-sidebar'
                                )),
                            'data' => 'sidebars',
                            'default' => 'blog-sidebar'
                        ),
                        array(
                            'id' => 'blog-title',
                            'type' => 'text',
                            'title' => esc_html__('Page Title', 'foodfarm'),
                            'default' => 'Blog'
                        ),
                        array(
                            'id' => 'blog-excerpt-length',
                            'type' => 'text',
                            'title' => esc_html__('Excerpt Length', 'foodfarm'),
                            'desc' => esc_html__('The number of words', 'foodfarm'),
                            'default' => '110',
                        ),
                    )
                );
                $this->sections[] =array(
                    'icon' => 'el-icon-file',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Gallery', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => '2',
                            'type' => 'info',
                            'desc' => esc_html__('Gallery Archive Page', 'foodfarm')
                        ),
                        array(
                            'id' => 'gallery-layout',
                            'type' => 'button_set',
                            'title' => esc_html__('Layout', 'foodfarm'),
                            'options' => $page_layout,
                            'default' => 'fullwidth'
                        ),
                        array(
                            'id' => 'gallery-sidebar-position',
                            'type' => 'button_set',
                            'title' => esc_html__('Sidebar Position', 'foodfarm'),
                            'options' => $sidebar_positions,
                            'default' => 'none'
                        ),
                        array(
                            'id' => 'gallery-sidebar',
                            'type' => 'select',
                            'title' => esc_html__('Select Sidebar', 'foodfarm'),
                            'required' => array('gallery-sidebar-position', 'equals', array(
                                    'left-sidebar',
                                    'right-sidebar'
                                )),
                            'data' => 'sidebars',
                            'default' => 'general-sidebar'
                        ),
                        array(
                            'id' => 'gallery-cols',
                            'type' => 'button_set',
                            'title' => esc_html__('Gallery Columns', 'foodfarm'),
                            'options' => foodfarm_gallery_columns(),
                            'default' => '3',
                        ),
                        array(
                            'id' => 'gallery-layout-version',
                            'type' => 'button_set',
                            'title' => esc_html__('Gallery page layout', 'foodfarm'),
                            'options' => foodfarm_page_gallery_layouts(),
                            'default' => 'grid'
                        ),
                        array(
                            'id' => 'gallery-archive-pagination-type',
                            'type' => 'button_set',
                            'title' => esc_html__('Pagination type', 'foodfarm'),
                            'options' => array('loadmore' => esc_html__('Load more', 'foodfarm'), 'nav' => esc_html__('Navigation', 'foodfarm')),
                            'default' => 'loadmore'
                        ),
                    )
                );
                $this->sections[] =array(
                    'icon' => 'el-icon-brush',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Recipes', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => '3',
                            'type' => 'info',
                            'desc' => esc_html__('Recipe list layout default', 'foodfarm')
                        ),
                        array(
                            'id' => 'recipe-layout',
                            'type' => 'button_set',
                            'title' => esc_html__('Layout', 'foodfarm'),
                            'options' => $page_layout,
                            'default' => 'fullwidth'
                        ),
                        array(
                            'id' => 'recipe-sidebar-position',
                            'type' => 'button_set',
                            'title' => esc_html__('Sidebar Position', 'foodfarm'),
                            'options' => $sidebar_positions,
                            'default' => 'right-sidebar'
                        ),
                        array(
                            'id' => 'recipe-sidebar',
                            'type' => 'select',
                            'title' => esc_html__('Select Sidebar', 'foodfarm'),
                            'required' => array('recipe-sidebar-position', 'equals', array(
                                    'left-sidebar',
                                    'right-sidebar'
                                )),
                            'data' => 'sidebars',
                            'default' => 'recipe-sidebar'
                        ),
                        array(
                            'id' => 'recipe-title',
                            'type' => 'text',
                            'title' => esc_html__('Page Title', 'foodfarm'),
                            'default' => 'Recipes'
                        ),
                        array(
                            'id'        => 'recipe_slug',
                            'type'      => 'text',
                            'title'     => esc_html__('Custom Slug', 'foodfarm'),
                            'subtitle'  => esc_html__('If you want your recipe post type to have a custom slug in the url, please enter it here.', 'foodfarm'),
                            'desc'      => esc_html__('You will still have to refresh your permalinks after saving this! 
            This is done by going to Settings > Permalinks and clicking save.', 'foodfarm'),
                            'validate'  => 'str_replace',
                            'str'       => array(
                                'search'        => ' ', 
                                'replacement'   => '-'
                            ),
                            'default'   => 'recipe',                    
                        ),  
                        array(
                            'id'        => 'recipe_cat_slug',
                            'type'      => 'text',
                            'title'     => esc_html__('Custom Slug for Recipe category', 'foodfarm'),
                            'subtitle'  => esc_html__('If you want your recipe category to have a custom slug in the url, please enter it here.', 'foodfarm'),
                            'desc'      => esc_html__('You will still have to refresh your permalinks after saving this! 
            This is done by going to Settings > Permalinks and clicking save.', 'foodfarm'),
                            'validate'  => 'str_replace',
                            'str'       => array(
                                'search'        => ' ', 
                                'replacement'   => '-'
                            ),
                            'default'   => 'recipe_cat',                    
                        ), 
                        array(
                            'id'        => 'recipe_tag_slug',
                            'type'      => 'text',
                            'title'     => esc_html__('Custom Slug for Recipe tag', 'foodfarm'),
                            'subtitle'  => esc_html__('If you want your recipe tag to have a custom slug in the url, please enter it here.', 'foodfarm'),
                            'desc'      => esc_html__('You will still have to refresh your permalinks after saving this! 
                This is done by going to Settings > Permalinks and clicking save.', 'foodfarm'),
                            'validate'  => 'str_replace',
                            'str'       => array(
                                'search'        => ' ', 
                                'replacement'   => '-'
                            ),
                            'default'   => 'recipe_tag',
                        ),                                                                       
                    )
                );
                $this->sections[] =array(
                    'icon' => 'el-icon-brush',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Press Media', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => '3',
                            'type' => 'info',
                            'desc' => esc_html__('Press media list layout default', 'foodfarm')
                        ),
                        array(
                            'id' => 'pressmedia-layout',
                            'type' => 'button_set',
                            'title' => esc_html__('Layout', 'foodfarm'),
                            'options' => $page_layout,
                            'default' => 'fullwidth'
                        ),
                        array(
                            'id' => 'press-sidebar-position',
                            'type' => 'button_set',
                            'title' => esc_html__('Sidebar Position', 'foodfarm'),
                            'options' => $sidebar_positions,
                            'default' => 'right-sidebar'
                        ),
                        array(
                            'id' => 'press-sidebar',
                            'type' => 'select',
                            'title' => esc_html__('Select Sidebar', 'foodfarm'),
                            'required' => array('press-sidebar-position', 'equals', array(
                                    'left-sidebar',
                                    'right-sidebar'
                                )),
                            'data' => 'sidebars',
                            'default' => 'press-media-sidebar'
                        ),
                        array(
                            'id' => 'press-media-title',
                            'type' => 'text',
                            'title' => esc_html__('Page Title', 'foodfarm'),
                            'default' => 'Press Media'
                        ),
                    )
                );
                if ( class_exists( 'Woocommerce' ) ) :
                    $this->sections[] =array(
                        'icon' => 'el-icon-shopping-cart',
                        'icon_class' => 'icon',
                        'title' => esc_html__('Shop', 'foodfarm'),
                        'fields' => array(
                            array(
                                'id' => '1',
                                'type' => 'info',
                                'desc' => esc_html__('Product listing', 'foodfarm')
                            ),
                            array(
                                'id' => 'shop-layout',
                                'type' => 'button_set',
                                'title' => esc_html__('Layout', 'foodfarm'),
                                'options' => $page_layout,
                                'default' => 'fullwidth'
                            ),
                            array(
                                'id' => 'shop-sidebar-position',
                                'type' => 'button_set',
                                'title' => esc_html__('Sidebar Position', 'foodfarm'),
                                'options' => $sidebar_positions,
                                'default' => 'right-sidebar'
                            ),
                            array(
                                'id' => 'shop-sidebar',
                                'type' => 'select',
                                'title' => esc_html__('Select Sidebar', 'foodfarm'),
                                'required' => array('shop-sidebar-position', 'equals', array(
                                        'left-sidebar',
                                        'right-sidebar'
                                    )),
                                'data' => 'sidebars',
                                'default' => 'shop-sidebar'
                            ),
                            array(
                                'id' => 'category-item',
                                'type' => 'text',
                                'title' => esc_html__('Products per Page', 'foodfarm'),
                                'desc' => esc_html__('Comma separated list of product counts.', 'foodfarm'),
                                'default' => '12,24,36'
                            ),
                            array(
                                'id' => 'product-cols',
                                'type' => 'button_set',
                                'title' => esc_html__('Product Columns', 'foodfarm'),
                                'options' => foodfarm_product_columns(),
                                'default' => '3',
                            ),
                            array(
                                'id' => 'product-cart',
                                'type' => 'switch',
                                'title' => esc_html__('Show Add to Cart button', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm')
                            ),                        
                            array(
                                'id' => 'product-quickview',
                                'type' => 'switch',
                                'title' => esc_html__('Show Quickview', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),
                            array(
                                'id' => 'product-wishlist',
                                'type' => 'switch',
                                'title' => esc_html__('Show Wishlist', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),
                            array(
                                'id' => 'product-compare',
                                'type' => 'switch',
                                'title' => esc_html__('Show Compare', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm')
                            ),
                            array(
                                'id' => 'product-hot',
                                'type' => 'switch',
                                'title' => esc_html__('Show "Hot" Label', 'foodfarm'),
                                'desc' => esc_html__('Will be show in the featured product.', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),
                            array(
                                'id' => 'product-news',
                                'type' => 'switch',
                                'title' => esc_html__('Show "New" Label', 'foodfarm'),
                                'desc' => esc_html__('Will be show in the recent product.', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),
                            array(
                                'id' => 'product-sale',
                                'type' => 'switch',
                                'title' => esc_html__('Show "Sale" Label', 'foodfarm'),
                                'desc' => esc_html__('Will be show in the special product.', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),
                            array(
                                'id' => 'product-sale-percent',
                                'type' => 'switch',
                                'title' => esc_html__('Show Sale Price Percentage', 'foodfarm'),
                                'default' => false,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),
                            array(
                                'id' => '1',
                                'type' => 'info',
                                'desc' => esc_html__('Single Product', 'foodfarm')
                            ),
                            array(
                                'id' => 'single-product-layout',
                                'type' => 'button_set',
                                'title' => esc_html__('Layout', 'foodfarm'),
                                'options' => $page_layout,
                                'default' => 'fullwidth'
                            ),
                            array(
                                'id' => 'single-product-sidebar-position',
                                'type' => 'button_set',
                                'title' => esc_html__('Sidebar Position', 'foodfarm'),
                                'options' => $sidebar_positions,
                                'default' => 'right-sidebar'
                            ),
                            array(
                                'id' => 'single-product-sidebar',
                                'type' => 'select',
                                'title' => esc_html__('Select Sidebar', 'foodfarm'),
                                'required' => array('single-product-sidebar-position', 'equals', array(
                                        'left-sidebar',
                                        'right-sidebar'
                                    )),
                                'data' => 'sidebars',
                                'default' => 'single-product-sidebar'
                            ),
                            array(
                                'id' => 'banner_product',
                                'type' => 'media',
                                'url' => true,
                                'readonly' => false,
                                'title' => esc_html__('Banner bottom', 'foodfarm'),
                                'default' => array(
                                    'url' => get_template_directory_uri() . '/images/promo/promo-banner-2.jpg',
                                )
                            ),
                            array(
                                'id' => 'product-related',
                                'type' => 'switch',
                                'title' => esc_html__('Show Related Products', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),
                            array(
                                'id' => 'product-mail-friend',
                                'type' => 'switch',
                                'title' => esc_html__('Show Mail to Friend', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),
                            array(
                                'id' => 'product-share',
                                'type' => 'switch',
                                'title' => esc_html__('Show Share Products', 'foodfarm'),
                                'default' => true,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),
                            array(
                                'id' => 'product-related-count',
                                'type' => 'text',
                                'required' => array('product-related', 'equals', true),
                                'title' => esc_html__('Related Products Count', 'foodfarm'),
                                'default' => '10'
                            ),
                            array(
                                'id' => 'product-zoom',
                                'type' => 'switch',
                                'title' => esc_html__('Disable product image zoom in mobile', 'foodfarm'),
                                'default' => false,
                                'on' => esc_html__('Yes', 'foodfarm'),
                                'off' => esc_html__('No', 'foodfarm'),
                            ),                            
                        )
                    );
                endif;
                $this->sections[] =array(
                    'icon' => 'el el-network',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Social', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => '1',
                            'type' => 'info',
                            'desc' => esc_html__('If the Url empty, the social icon will not display.', 'foodfarm')
                        ),
                        array(
                            'id' => 'social-twitter',
                            'type' => 'text',
                            'title' => esc_html__('Twitter', 'foodfarm'),
                            'default' => '#',
                            'placeholder' => esc_html__('http://', 'foodfarm')
                        ),
                        array(
                            'id' => 'social-instagram',
                            'type' => 'text',
                            'title' => esc_html__('Instagram', 'foodfarm'),
                            'default' => '#',
                            'placeholder' => esc_html__('http://', 'foodfarm')
                        ),
                        array(
                            'id' => 'social-facebook',
                            'type' => 'text',
                            'title' => esc_html__('Facebook', 'foodfarm'),
                            'default' => '#',
                            'placeholder' => esc_html__('http://', 'foodfarm')
                        ),
                        array(
                            'id' => 'social-google',
                            'type' => 'text',
                            'title' => esc_html__('Google Plus', 'foodfarm'),
                            'default' => '#',
                            'placeholder' => esc_html__('http://', 'foodfarm')
                        ),
                    )
                );
                $this->sections[] =array(
                    'icon' => 'el-icon-cog',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Coming Soon', 'foodfarm'),
                    'fields' => array(
                        array(
                            'id' => 'under-contr-mode',
                            'type' => 'switch',
                            'title' => esc_html__('Activate under construction mode', 'foodfarm'),
                            'default' => false,
                            'on' => esc_html__('Yes', 'foodfarm'),
                            'off' => esc_html__('No', 'foodfarm'),
                        ),
                        array(
                            'id' => 'under-bg-image',
                            'type' => 'media',
                            'url' => true,
                            'readonly' => false,
                            'title' => esc_html__('Background image', 'foodfarm'),
                            'desc' => esc_html__('Background image for coming soon page', 'foodfarm'),
                            'default' => array(
                                'url' => get_template_directory_uri() . '/images/coming-soon.jpg',
                            )
                        ),
                        array(
                            'id' => "under-contr-content",
                            'type' => 'editor',
                            'title' => esc_html__('Content', 'foodfarm'),
                            'default' => esc_html__('
                                    <h1>coming soon</h1>
                                    <h2>We are working <span>hard!</span></h2>
                                    <p>We work to improve our website and make it look fresher! But we"ll be back to soon</p>', 'foodfarm')
                        ),
                        array(
                            'id' => '1',
                            'type' => 'info',
                            'desc' => esc_html__('Countdown Timer', 'foodfarm')
                        ),
                        array(
                            'id' => 'under-display-countdown',
                            'type' => 'switch',
                            'title' => esc_html__('Display countdown timer', 'foodfarm'),
                            'default' => false,
                            'on' => esc_html__('Yes', 'foodfarm'),
                            'off' => esc_html__('No', 'foodfarm'),
                        ),
                        array(
                            'id' => "under-end-date",
                            'type' => 'date',
                            'title' => esc_html__('End date', 'foodfarm'),
                            'default' => '',
                            'required' => array('under-display-countdown', 'equals', true),
                        ),
                        array(
                            'id' => 'under-mail',
                            'type' => 'switch',
                            'title' => esc_html__('Display subcribe form', 'foodfarm'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'foodfarm'),
                            'off' => esc_html__('No', 'foodfarm'),
                        ),
                    )
                );
        }

        protected function foodfarm_add_header_section_options() {
            $header = array(
                'icon' => 'el-icon-website',
                'icon_class' => 'icon',
                'title' => esc_html__('Header', 'foodfarm'),
                'fields' => array(
                    array(
                        'id' => 'header-type',
                        'type' => 'image_select',
                        'title' => esc_html__('Header Type', 'foodfarm'),
                        'subtitle' => esc_html__('Each page will have option for select header type. Header selection in each page will have higher priority than this general selection.','foodfarm'),                        
                        'options' => $this->foodfarm_header_types(),
                        'default' => '1'
                    ),
                    array(
                        'id' => 'logo-header-sticky',
                        'type' => 'media',
                        'url' => true,
                        'readonly' => false,
                        'title' => esc_html__('Logo for sticky menu', 'foodfarm'),
                        'required' => array(
                                    array('header-sticky', 'equals', 1),
                                    array('header-type', 'equals', array(
                                    '1'
                                )),
                            ),
                        'default' => array(
                            'url' => get_template_directory_uri() . '/images/sticky-logo.png',
                            'height' => 56,
                            'wide' => 55
                        )
                    ),  
                    array(
                        'id' => 'header_search_type',
                        'type' => 'button_set',
                        'title' => esc_html__('Header search type', 'foodfarm'),
                        'options' => array(
                                "1" => esc_html__("Product (if Woocommerce enable)","foodfarm"),
                                "2" => esc_html__("Blog","foodfarm"),
                            ),
                        'default' => '1',                         
                    ),                                       
                    array(
                        'id' => '1',
                        'type' => 'info',
                        'desc' => esc_html__('Contacts', 'foodfarm')
                    ),
                    array(
                        'id' => 'header-slogan',
                        'type' => 'text',
                        'title' => esc_html__('Header Slogan', 'foodfarm'),
                        'required' => array(
                                    array('header-type', 'equals', array(
                                    '5'
                                )),
                            ),
                        'default' => esc_html__('Default welcome msg!', 'foodfarm'),
                    ),
                    array(
                        'id' => 'header7-top-text',
                        'type' => 'text',
                        'title' => esc_html__('Header Top Text', 'foodfarm'),
                        'required' => array(
                                    array('header-type', 'equals', array(
                                    '7'
                                )),
                            ),
                        'default' => esc_html__('Free Shipping on every Demestic order of $40 or more!', 'foodfarm'),
                    ),  

                    array(
                        'id' => 'header7-banner',
                        'type' => 'textarea',
                        'title' => esc_html__('Header Top Banner', 'foodfarm'),
                        'default' => wp_kses(__(
                            '<h5>New Daily Holiday Deals</h5>
                            <h6>24 Hours Only - Ends Midnight!</h6>
                            <h6 class="main_color">No Promo code Required</h6>','foodfarm'
                            ),                                
                            array(
                                'h5' => array(
                                    'class' => array(),
                                ),
                                'h6' => array(
                                    'class' => array(),
                                ),
                                'h4' => array(
                                    'class' => array(),
                                ), 
                                'h3' => array(
                                    'class' => array(),
                                ), 
                                'h2' => array(
                                    'class' => array(),
                                ),                                                        
                                'a' => array(
                                    'href' => array(),
                                    'title' => array(),
                                    'target' => array(),
                                ),
                                'i' => array(
                                    'class' => array(),
                                    'aria-hidden' => array(),
                                ),                                
                            )),
                        'required' => array(
                                    array('header-type', 'equals', array(
                                    '7'
                                )),
                            ),                                              
                    ),                                                        
                    array(
                        'id' => 'header-contact-phonenumber',
                        'type' => 'text',
                        'title' => esc_html__('Phone number', 'foodfarm'),
                        'default' => esc_html__('(+84)1234-5678', 'foodfarm'),  
                        'required' => array(
                                    array('header-type', 'equals', array(
                                    '1','2','3','4','5','6','7','8'
                                )),
                            ),                                             
                    ),
                    array(
                        'id' => 'header8-text-phonenumber',
                        'type' => 'text',
                        'title' => esc_html__('Header text before phone number', 'foodfarm'),
                        'required' => array(
                                    array('header-type', 'equals', array(
                                    '8'
                                )),
                            ),
                        'default' => esc_html__('Contact us 24/7: ', 'foodfarm'),
                    ),                      
                    array(
                        'id' => 'header-contact-email',
                        'type' => 'text',
                        'title' => esc_html__('Email', 'foodfarm'),
                        'default' => esc_html__('contact@yourdomain.com', 'foodfarm'),
                        'placeholder' => esc_html__('contact@yourdomain.com', 'foodfarm'),
                        'required' => array(
                                    array('header-type', 'equals', array(
                                    '1','2','3','4','5','6','7'
                                )),
                            ),                        
                    ),
                    array(
                        'id' => 'header-time',
                        'type' => 'text',
                        'title' => esc_html__('Time', 'foodfarm'),
                        'default' => esc_html__('Open Daily: 8.00 AM - 21.00 PM', 'foodfarm'),
                        'required' => array(
                                    array('header-type', 'equals', array(
                                    '6'
                                )),
                            ),
                        'placeholder' => esc_html__('Open Daily: 8.00 AM - 21.00 PM', 'foodfarm')
                    ),
                    
                    array(
                        'id' => 'header-accountlink',
                        'type' => 'switch',
                        'title' => esc_html__('Show Account Link', 'foodfarm'),
                        'default' => true,
                        'required' => array(
                                    array('header-type', 'equals', array(
                                    '1','8','9','3'
                                )),
                            ),                        
                    ),                                       
                    array(
                        'id' => 'header-minicart',
                        'type' => 'switch',
                        'title' => esc_html__('Show Mini Cart', 'foodfarm'),
                        'default' => true
                    ),
                    array(
                        'id' => 'header-search',
                        'type' => 'switch',
                        'title' => esc_html__('Show Search', 'foodfarm'),
                        'default' => true
                    ),
                    array(
                        'id' => 'enable_search_ajax',
                        'type' => 'switch',
                        'title' => esc_html__('Enable search ajax for product in header', 'foodfarm'),
                        'default' => false
                    ),                    
                    array(
                        'id' => 'header-sticky',
                        'type' => 'switch',
                        'title' => esc_html__('Enable sticky', 'foodfarm'),
                        'default' => true
                    ),   
                    array(
                        'id' => 'header-sticky-mobile',
                        'type' => 'switch',
                        'required' => array('header-sticky', 'equals', 1,),
                        'title' => esc_html__('Enable sticky on mobile ', 'foodfarm'),
                        'default' => true
                    ),                
                    array(
                        'id' => 'header-top',
                        'type' => 'switch',
                        'required' => array('header-type', 'equals', array(
                                '4',
                                '1',
                                '2',
                                '5',
                                '6',
                                '7'
                            )),
                        'title' => esc_html__('Show header top', 'foodfarm'),
                        'default' => true
                    ),                   
                    array(
                        'id' => 'verticle-menu',
                        'type' => 'switch',
                        'required' => array('header-type', 'equals', array(
                                '3',
                            )),
                        'title' => esc_html__('Show Verticle Menu', 'foodfarm'),
                        'default' => true
                    ),
                )
            );
            if (class_exists('WCML_CurrencySwitcher') && class_exists('Woocommerce')) {
                $header['fields'][] = array(
                    'id' => 'header-currency',
                    'type' => 'switch',
                    'title' => esc_html__('Show Currencies Switcher', 'foodfarm'),
                    'default' => true
                );
            }
            global $sitepress;
            if (defined('ICL_LANGUAGE_CODE') && isset($sitepress)) {
                $header['fields'][] = array(
                    'id' => 'header-language',
                    'type' => 'switch',
                    'title' => esc_html__('Show Languages Switcher', 'foodfarm'),
                    'default' => true
                );
            }
            return $header;
        }

        public function foodfarm_get_setting_arguments() {
            $theme = wp_get_theme();
            $args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'foodfarm_settings',
                'display_name' => esc_html__('Foodfarm', 'foodfarm'),
                'display_version' => foodfarm_version,
                'menu_type' => 'menu',
                'allow_sub_menu' => true,
                'menu_title' => esc_html__('Foodfarm', 'foodfarm'),
                'page_title' => esc_html__('Foodfarm', 'foodfarm'),
                'google_api_key' => '',
                'google_update_weekly' => false,
                'async_typography' => true,
                'admin_bar' => true,
                'admin_bar_icon' => 'dashicons-admin-generic',
                'admin_bar_priority' => 50,
                'global_variable' => '',
                'dev_mode' => false,
                'update_notice' => true,
                'customizer' => true,
                'page_priority' => null,
                'page_parent' => 'themes.php',
                'page_permissions' => 'manage_options',
                'menu_icon' => '',
                'last_tab' => '',
                'page_icon' => 'icon-themes',
                'page_slug' => '',
                'save_defaults' => true,
                'default_show' => false,
                'default_mark' => '',
                'show_import_export' => true,
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true,
                'output_tag' => true,
                'database' => '',
                'use_cdn' => true,
                // HINTS
                'hints' => array(
                    'icon' => 'el el-question-sign',
                    'icon_position' => 'right',
                    'icon_color' => 'lightgray',
                    'icon_size' => 'normal',
                    'tip_style' => array(
                        'color' => 'red',
                        'shadow' => true,
                        'rounded' => false,
                        'style' => '',
                    ),
                    'tip_position' => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect' => array(
                        'show' => array(
                            'effect' => 'slide',
                            'duration' => '500',
                            'event' => 'mouseover',
                        ),
                        'hide' => array(
                            'effect' => 'slide',
                            'duration' => '500',
                            'event' => 'click mouseleave',
                        ),
                    ),
                )
            );
            return $args;
        }

        protected function foodfarm_header_types() {
            return array(
                '1' => array('alt' => esc_html__('Header Type 1', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/headers/header-1.jpg'),
                '2' => array('alt' => esc_html__('Header Type 2', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/headers/header-2.jpg'),
                '3' => array('alt' => esc_html__('Header Type 3', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/headers/header-3.jpg'),
                '4' => array('alt' => esc_html__('Header Type 4', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/headers/header-4.jpg'),
                '5' => array('alt' => esc_html__('Header Type 5', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/headers/header-5.jpg'),
                '6' => array('alt' => esc_html__('Header Type 6', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/headers/header-6.jpg'),
                '7' => array('alt' => esc_html__('Header Type 7', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/headers/header-7.jpg'),
                '8' => array('alt' => esc_html__('Header Type 8', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/headers/header-8.jpg'),
                '9' => array('alt' => esc_html__('Header Type 8', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/headers/header-9.jpg'),
            );
        }

        protected function foodfarm_footer_types() {
            return array(
                '1' => array('alt' => esc_html__('Footer Type 1', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/footers/footer-1.jpg'),
                '2' => array('alt' => esc_html__('Footer Type 2', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/footers/footer-2.jpg'),
                '3' => array('alt' => esc_html__('Footer Type 3', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/footers/footer-3.jpg'),
                '4' => array('alt' => esc_html__('Footer Type 4', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/footers/footer-4.jpg'),
                '5' => array('alt' => esc_html__('Footer Type 5', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/footers/footer-5.jpg'),
                '6' => array('alt' => esc_html__('Footer Type 6', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/footers/footer-6.jpg'),
                '7' => array('alt' => esc_html__('Footer Type 6', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/footers/footer-7.jpg'),
                '8' => array('alt' => esc_html__('Footer Type 6', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/footers/footer-8.jpg'),
                '9' => array('alt' => esc_html__('Footer Type 6', 'foodfarm'), 'img' => get_template_directory_uri() . '/inc/admin/settings/footers/footer-9.jpg'),
            );
        }

    }

    
    function foodfarm_get_framework_settings() {
        global $foodfarmReduxSettings;
        $foodfarmReduxSettings = new Framework_Foodfarm_Settings();
        return $foodfarmReduxSettings;
    }
    foodfarm_get_framework_settings();
}