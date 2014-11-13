<?php

$redux_opt_name = "helpdesk";

if ( !function_exists( "redux_add_metaboxes" ) ):
    function redux_add_metaboxes($metaboxes) {

    /*
    $boxSections[] = array(
        'title' => __('Home Settings', 'redux-framework-demo'),
        //'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'redux-framework-demo'),
        'icon' => 'el-icon-home',
        'fields' => array(  
            array(
                'id'=>'webFonts',
                'type' => 'media', 
                'title' => __('Web Fonts', 'redux-framework-demo'),
                'compiler' => 'true',
                'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'=> __('Basic media uploader with disabled URL input field.', 'redux-framework-demo'),
                'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                ),  
            array(
                 'id'=>'section-media-start',
                 'type' => 'section', 
                 'title' => __('Media Options', 'redux-framework-demo'),
                 'subtitle'=> __('With the "section" field you can create indent option sections.'),                            
                 'indent' => true // Indent all options below until the next 'section' option is set.
                 ),                                     
            array(
                'id'=>'media',
                'type' => 'media', 
                'url'=> true,
                'title' => __('Media w/ URL', 'redux-framework-demo'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'=> __('Basic media uploader with disabled URL input field.', 'redux-framework-demo'),
                'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                'default'=>array('url'=>'http://s.wordpress.org/style/images/codeispoetry.png'),
                ),
            array(
                 'id'=>'section-media-end',
                 'type' => 'section', 
                 'indent' => false // Indent all options below until the next 'section' option is set.
                 ),  
            array(
                'id'=>'media-nourl',
                'type' => 'media', 
                'title' => __('Media w/o URL', 'redux-framework-demo'),
                'desc'=> __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo'),
                'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                ),  
            array(
                'id'=>'media-nopreview',
                'type' => 'media', 
                'preview'=> false,
                'title' => __('Media No Preview', 'redux-framework-demo'),
                'desc'=> __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo'),
                'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                ),          
            array(
                'id' => 'gallery',
                'type' => 'gallery',
                'title' => __('Add/Edit Gallery', 'so-panels'),
                'subtitle' => __('Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'so-panels'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                ),
            array(
                'id'=>'slider1bDOVY23',
                'type' => 'slider', 
                'title' => __('JQuery UI Slider Example 1', 'redux-framework-demo'),
                'desc'=> __('JQuery UI slider description. Min: 1, max: 500, step: 3, default value: 45', 'redux-framework-demo'),
                "default"   => "45",
                "min"       => "1",
                "step"      => "3",
                "max"       => "500",
                ),  

            array(
                'id'=>'slider2bc',
                'type' => 'slider', 
                'title' => __('JQuery UI Slider Example 2 w/ Steps (5)', 'redux-framework-demo'),
                'desc'=> __('JQuery UI slider description. Min: 0, max: 300, step: 5, default value: 75', 'redux-framework-demo'),
                "default"   => "0",
                "min"       => "0",
                "step"      => "5",
                "max"       => "300",
                ),
                
            array(
                'id'=>'spinner1bcd',
                'type' => 'spinner', 
                'title' => __('JQuery UI Spinner Example 1', 'redux-framework-demo'),
                'desc'=> __('JQuery UI spinner description. Min:20, max: 100, step:20, default value: 40', 'redux-framework-demo'),
                "default"   => "40",
                "min"       => "20",
                "step"      => "20",
                "max"       => "100",
                ),
                
            array(
                'id'=>'switch-on',
                'type' => 'switch', 
                'title' => __('Switch On', 'redux-framework-demo'),
                'subtitle'=> __('Look, it\'s on!', 'redux-framework-demo'),
                "default"       => 1,
                ),  

            array(
                'id'=>'switch-off',
                'type' => 'switch', 
                'title' => __('Switch Off', 'redux-framework-demo'),
                'subtitle'=> __('Look, it\'s on!', 'redux-framework-demo'),
                "default"       => 0,
            ),  
        )
    );

    $boxSections[] = array(
        'title' => __('Home Layout', 'redux-framework-demo'),
        'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'redux-framework-demo'),
        'icon' => 'el-icon-home',
        'fields' => array(  
            array(
                "id" => "homepage_blocks",
                "type" => "sorter",
                "title" => "Homepage Layout Manager",
                "desc" => "Organize how you want the layout to appear on the homepage",
                "compiler"=>'true',
                'required' => array('layout','=','1'),       
                'options' => array(
                    "enabled" => array(
                        "placebo" => "placebo", //REQUIRED!
                        "highlights" => "Highlights",
                        "slider" => "Slider",
                        "staticpage" => "Static Page",
                        "services" => "Services"
                    ),
                    "disabled" => array(
                        "placebo" => "placebo", //REQUIRED!
                    ),
                ),
            ),        
            array(
                'id'=>'slides',
                'type' => 'slides',
                'title' => __('Slides Options', 'redux-framework-demo'),
                'subtitle'=> __('Unlimited slides with drag and drop sortings.', 'redux-framework-demo'),
                'desc' => __('This field will store all slides values into a multidimensional array to use into a foreach loop.', 'redux-framework-demo')
            ),        
            array(
                'id'=>'presets',
                'type' => 'image_select', 
                'presets' => true,
                'title' => __('Preset', 'redux-framework-demo'),
                'subtitle'=> __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo'),
                'default'       => 0,
                'desc'=> __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo'),
                'options' => array(
                                '1' => array('alt' => 'Preset 1', 'img' => ReduxFramework::$_url.'../sample/presets/preset1.png', 'presets'=>array('switch-on'=>1,'switch-off'=>1, 'switch-custom'=>1)),
                                '2' => array('alt' => 'Preset 2', 'img' => ReduxFramework::$_url.'../sample/presets/preset2.png', 'presets'=>'{"slider1":"1", "slider2":"0", "switch-on":"0"}'),
                ),
            ),                                                   
        ),
    );

    $metaboxes = array();

    $metaboxes[] = array(
        'id' => 'demo-layout',
        'title' => __('Cool Options', 'redux-framework-demo'),
        'post_types' => array('page','post', 'acme_product'),
        //'page_template' => array('page-test.php'),
        //'post_format' => array('image'),
        'position' => 'normal', // normal, advanced, side
        'priority' => 'high', // high, core, default, low
        //'sidebar' => false, // enable/disable the sidebar in the normal/advanced positions
        'sections' => $boxSections
    );
    */

    $boxLayout = array();
    $boxLayout[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'desc'      => __( 'Select main content and sidebar arrangement.', 'redux-framework-demo' ),
                'id'        => 'layout',
                'type'      => 'image_select',
                'customizer'=> array(),
                'options'   => array( 
                1           => ReduxFramework::$_url . 'assets/img/1c.png',
                2           => ReduxFramework::$_url . 'assets/img/2cl.png',
                3           => ReduxFramework::$_url . 'assets/img/2cr.png',
                )
            ),
            array(
                'id' => 'sidebar',
                'title' => __( 'Sidebar', 'fusion-framework' ),
                'desc' => 'Select custom sidebar, if left blank default "Primary" sidebar is used. You can create custom sidebars under Appearance > Widgets.',
                'type' => 'select',
                'required' => array('layout','>','1'),       
                'data' => 'sidebars',
                'default' => 'None',
            ),
        )
    );
  
    $metaboxes[] = array(
        'id' => 'demo-layout2',
        'post_types' => array('page'),
        'page_template' => array('template-custom.php'),
        'position' => 'side', // normal, advanced, side
        'priority' => 'high', // high, core, default, low
        'sections' => $boxLayout
    );

    $boxPost = array();
    $boxPost[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'id'       => 'reset_post_votes',
                'type'     => 'button_set',
                'title'    => __( 'Reset Article Votes', 'redux-framework-demo' ),
                'desc'     => __( 'Reset votes on this article!.', 'redux-framework-demo' ),
                'options'  => array(
                    '1' => 'Reset Votes'
                ),
                'multi'    => true,
                'default'  => '0'
            ),
        )
    );
  
    $metaboxes[] = array(
        'id' => 'demo-layout2',
        'title' => __('Article Options', 'pressapps'),
        'post_types' => array('post'),
        'position' => 'normal', // normal, advanced, side
        'priority' => 'core', // high, core, default, low
        'sections' => $boxPost
    );

    /*
    $page_options = array();
    $page_options[] = array(
        //'title'         => __('General Settings', 'redux-framework-demo'),
        'icon_class'    => 'icon-large',
        'icon'          => 'el-icon-home',
        'fields'        => array(
            array(
                'id' => 'sidebar55',
                'title' => __( 'Sidebar', 'fusion-framework' ),
                'desc' => 'Please select the sidebar you would like to display on this page. Note: You must first create the sidebar under Appearance > Widgets.',
                'type' => 'select',
                'data' => 'sidebars',
                'default' => 'None',
            ),
        ),
    );

    $metaboxes[] = array(
        'id'            => 'page-options',
        'title'         => __( 'Page Options', 'fusion-framework' ),
        'post_types'    => array( 'page', 'post', 'demo_metaboxes' ),
        //'page_template' => array('page-test.php'),
        //'post_format' => array('image'),
        'position'      => 'normal', // normal, advanced, side
        'priority'      => 'high', // high, core, default, low
        'sidebar'       => false, // enable/disable the sidebar in the normal/advanced positions
        'sections'      => $page_options,
    );
    */
   
      $knowledgebaseTemplate = array();
      $knowledgebaseTemplate[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'id'        => 'kb_categories',
                'type'      => 'select',
                'data'      => 'categories',
                'multi'     => true,
                'title'     => __('Categories', 'redux-framework-demo'),
                'desc'      => __('Select categories to display on Knowledge Base page template (If none selected all categories will be displayed).', 'redux-framework-demo'),
            ),
            array(
                'id' => 'kb_columns',
                'type' => 'select',
                'title' => __('Columns Per Page', 'pressapps' ), 
                'desc' => __('Select number of knowledge base columns displayed on page.', 'pressapps' ),
                'options' => array(
                    2 => '2 Columns',
                    3 => '3 Columns',
                    4 => '4 Columns',
                ),
                'default'   => '3', 
            ),
            array(
                'id' => 'kb_aticles_per_cat',
                'type' => 'select',
                'title' => __('Articles Per Category', 'pressapps' ), 
                'desc' => __('Select number of knowledge base articles displayed per category.', 'pressapps' ),
                'options' => array(
                    '3' => '3 Articles',
                    '4' => '4 Articles',
                    '5' => '5 Articles',
                    '6' => '6 Articles',
                    '7' => '7 Articles',
                    '8' => '8 Articles',
                    '10' => '10 Articles',
                    '12' => '12 Articles',
                    '14' => '14 Articles',
                    '18' => '18 Articles',
                    '20' => '20 Articles',
                    '30' => '30 Articles',
                ),
                'default'   => '7', 
            ),
            array(
                'id'        => '3rd_level_cat',
                'type'      => 'switch',
                'title'     => __('3rd level categories', 'redux-framework-demo'),
                'desc'  => __('Display 3rd level child categories.', 'redux-framework-demo'),
                'default'   => true,
            ),
        )
      );
      
      $metaboxes[] = array(
        'id' => 'kb-metabox',
        'title' => __('Knowledge Base Options', 'pressapps'),
        'post_types' => array('page'),
        'page_template' => array('template-knowledgebase.php'),
        'position' => 'normal', // normal, advanced, side
        'priority' => 'core', // high, core, default, low
        'sections' => $knowledgebaseTemplate
      );




      $homeTemplate = array();
      $homeTemplate[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'id'       => 'headline_bg',
                'type'     => 'background',
                'output'   => array( '.headline' ),
                'title'    => __( 'Body Background', 'redux-framework-demo' ),
                'subtitle' => __( 'Body background with image, color, etc.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'headline_padding',
                'type'     => 'spacing',
                'output'   => array( '.headline' ),
                'mode'     => 'padding',
                //'all'      => false,
                'right'         => false,
                'left'          => false,
                'units'         => 'px',
                'title'    => __( 'Padding', 'redux-framework-demo' ),
                'desc'     => __( 'Set page navigation top and bottom padding in px.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'headline_text',
                'type'     => 'color',
                'title'    => __( 'Text Color', 'redux-framework-demo' ),
                'subtitle' => __( 'Pick a page navigation text color.', 'redux-framework-demo' ),
                //'default'  => '#aaaaaa',
                'transparent' => false,
                'validate' => 'color',
                //'output'    => array(
                //    'color'            => '.breadcrumb, .breadcrumb a, .breadcrumb span, .breadcrumb > .active, .breadcrumb > li + li:before, .headline h1, .headline h4'
                //)
            ),
            array(
                'id'       => 'subtitle',
                'type'     => 'text',
                'title'    => __( 'Subtitle', 'redux-framework-demo' ),
                'desc'     => __( 'Enter page subtitle.', 'redux-framework-demo' ),
            ),
            array(
                'id' => 'headline_search',
                'type' => 'button_set',
                'title'       => __( 'Search', 'shoestrap' ),
                'desc'        => __( 'Display a search form in the headline.', 'shoestrap' ),
                'options'   => array(
                    '0' => 'Disabled',
                    '1' => 'WP Search',
                    '2' => 'Live Search',
                ),
            ),
            array(
                'id'       => 'home_sections',
                'type'     => 'sorter',
                'title'    => 'Layout Manager Advanced',
                'subtitle' => 'You can add multiple drop areas or columns.',
                'compiler' => 'true',
                'options'  => array(
                    'Enabled'  => array(
                        'header' => 'Header',
                        'categories'     => 'Categories',
                        'content' => 'Content',
                        'sidebar' => 'Sidebar',
                        'contact'   => 'Contact'
                    ),
                    'Disabled' => array(),
                ),
                'limits'   => array(
                    'Disabled' => 4,
                ),
            ),
            array(
                'id' => 'home_sidebar',
                'title' => __( 'Sidebar', 'fusion-framework' ),
                'desc' => 'Select custom sidebar, if left blank default "Primary" sidebar is used. You can create custom sidebars under Appearance > Widgets.',
                'type' => 'select',
                //'required' => array('layout','>','1'),       
                'data' => 'sidebars',
                'default' => 'None',
            ),
            array(
                'id'        => 'home_categories',
                'type'      => 'select',
                'data'      => 'categories',
                'multi'     => true,
                'title'     => __('Categories', 'redux-framework-demo'),
                'desc'      => __('Select categories to display on Knowledge Base page template (If none selected all categories will be displayed).', 'redux-framework-demo'),
            ),
            array(
                'id' => 'home_columns',
                'type' => 'select',
                'title' => __('Columns Per Page', 'pressapps' ), 
                'desc' => __('Select number of knowledge base columns displayed on page.', 'pressapps' ),
                'options' => array(
                    2 => '2 Columns',
                    3 => '3 Columns',
                    4 => '4 Columns',
                    6 => '6 Columns',
                ),
                'default'   => '4', 
            ),
            array(
                'id' => 'home_aticles_per_cat',
                'type' => 'select',
                'title' => __('Articles Per Category', 'pressapps' ), 
                'desc' => __('Select number of knowledge base articles displayed per category.', 'pressapps' ),
                'options' => array(
                    '3' => '3 Articles',
                    '4' => '4 Articles',
                    '5' => '5 Articles',
                    '6' => '6 Articles',
                    '7' => '7 Articles',
                    '8' => '8 Articles',
                    '10' => '10 Articles',
                    '12' => '12 Articles',
                    '14' => '14 Articles',
                    '18' => '18 Articles',
                    '20' => '20 Articles',
                    '30' => '30 Articles',
                ),
                'default'   => '7', 
            ),
            array(
                'id'        => '3rd_level_cat',
                'type'      => 'switch',
                'title'     => __('3rd level categories', 'redux-framework-demo'),
                'desc'  => __('Display 3rd level child categories.', 'redux-framework-demo'),
                'default'   => true,
            ),
        )
      );
      
      $metaboxes[] = array(
        'id' => 'home-metabox',
        'title' => __('Home Page Options', 'pressapps'),
        'post_types' => array('page'),
        'page_template' => array('template-home.php'),
        'position' => 'normal', // normal, advanced, side
        'priority' => 'core', // high, core, default, low
        'sections' => $homeTemplate
      );




    // Kind of overkill, but ahh well.  ;)
    //$metaboxes = apply_filters( 'your_custom_redux_metabox_filter_here', $metaboxes );

    return $metaboxes;
  }
  add_action('redux/metaboxes/'.$redux_opt_name.'/boxes', 'redux_add_metaboxes');
endif;

// The loader will load all of the extensions automatically based on your $redux_opt_name
require_once(dirname(__FILE__).'/loader.php');