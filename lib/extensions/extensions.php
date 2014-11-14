<?php

$redux_opt_name = "helpdesk";

if ( !function_exists( "redux_add_metaboxes" ) ):
    function redux_add_metaboxes($metaboxes) {

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
        'id' => 'demo-layout',
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
                'type'     => 'switch',
                'title'    => __( 'Reset Article Votes', 'redux-framework-demo' ),
                'desc'     => __( 'Reset votes on this article!.', 'redux-framework-demo' ),
                'default'  => '0'
            ),
        )
    );
  
    $metaboxes[] = array(
        'id' => 'post-reset',
        'title' => __('Article Options', 'pressapps'),
        'post_types' => array('post'),
        'position' => 'normal', // normal, advanced, side
        'priority' => 'core', // high, core, default, low
        'sections' => $boxPost
    );
   
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
                        'actions'     => 'Actions',
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
                'title'     => __( 'Title', 'shoestrap' ),
                'id'        => 'home_categories_title',
                'default'   => 'Browse Help Topics',
                'type'      => 'text'
            ),
            array(
                'id'        => 'home_categories',
                'title'     => __('Categories', 'redux-framework-demo'),
                'type'      => 'select',
                'data'      => 'categories',
                'multi'     => true,
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
            array(
                'title'     => __( 'Title', 'shoestrap' ),
                'id'        => 'home_actions_title',
                'default'   => 'I want to...',
                'type'      => 'text'
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