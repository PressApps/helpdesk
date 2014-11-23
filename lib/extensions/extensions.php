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
        'id' => 'layout',
        'post_types' => array('page'),
        'position' => 'side', // normal, advanced, side
        'priority' => 'high', // high, core, default, low
        'sections' => $boxLayout
    );

    $boxStyle = array();
    $boxStyle[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'id'       => 'style_ol',
                'type'     => 'switch',
                'title'    => __( 'Ordered List', 'redux-framework-demo' ),
                'desc'     => __( 'Style ordered list.', 'redux-framework-demo' ),
                'default'  => '0'
            ),
        )
    );
  
    $metaboxes[] = array(
        'id' => 'post-style',
        'title' => __('Style Options', 'pressapps'),
        'post_types' => array('post', 'page'),
        'position' => 'normal', // normal, advanced, side
        'priority' => 'core', // high, core, default, low
        'sections' => $boxStyle
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
                'desc'     => __( 'Reset votes on this article!', 'redux-framework-demo' ),
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

    $homeSorter = array();
    $homeSorter[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'id'       => 'home_sections',
                'type'     => 'sorter',
                'subtitle' => 'You can add multiple drop areas or columns.',
                'compiler' => 'true',
                'options'  => array(
                    'Enabled'  => array(
                        'hero' => 'Hero',
                        'boxes' => 'Boxes',
                        'categories'     => 'Categories',
                        'actions'     => 'Actions',
                        'content' => 'Content',
                        'sidebar' => 'Sidebar',
                        'cta'   => 'CTA'
                    ),
                    'Disabled' => array(),
                ),
            ),
        )
    );
      
    $metaboxes[] = array(
        'id' => 'home-layout-metabox',
        'title' => __('Page Layout Options', 'pressapps'),
        'post_types' => array('page'),
        'page_template' => array('template-home.php'),
        'position' => 'normal', // normal, advanced, side
        'priority' => 'core', // high, core, default, low
        'sections' => $homeSorter
    );

    $homeTemplate = array();
    $homeTemplate[] = array(
        'title' => __('Hero Section', 'redux-framework-demo'),
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-minus',
        'fields' => array(
            array(
                'id'       => 'hero_bg',
                'type'     => 'background',
                'output'   => array( '.section-hero' ),
                'title'    => __( 'Background', 'redux-framework-demo' ),
                'default'  => array(
                    'background-color' => '#f3f3f3',
                )
            ),
            array(
                'id'       => 'hero_padding',
                'type'     => 'spacing',
                'output'   => array( '.section-hero' ),
                'mode'     => 'padding',
                'right'         => false,
                'left'          => false,
                'units'         => 'px',
                'title'    => __( 'Padding', 'redux-framework-demo' ),
                'default'  => array(
                    'padding-top'    => '100px',
                    'padding-bottom' => '100px',
                )
            ),
            array(
                'id'       => 'hero_text',
                'type'     => 'color',
                'title'    => __( 'Text Color', 'redux-framework-demo' ),
                'default'  => '#aaaaaa',
                'transparent' => false,
                'validate' => 'color',
                'output'    => array(
                    'color' => '.section-hero h1, .section-hero h4'
                )
            ),
            array(
                'id'       => 'subtitle',
                'type'     => 'text',
                'title'    => __( 'Subtitle', 'redux-framework-demo' ),
            ),
            array(
                'id' => 'headline_search',
                'type' => 'button_set',
                'title'       => __( 'Search', 'shoestrap' ),
                'desc'        => __( 'Display a search form in the hero section.', 'shoestrap' ),
                'options'   => array(
                    '0' => 'Disabled',
                    '1' => 'WP Search',
                    '2' => 'Live Search',
                ),
            ),
        )
    );

    $homeTemplate[] = array(
        'title' => __('Boxes Section', 'redux-framework-demo'),
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-minus',
        'fields' => array(
            array(
                'title'     => __( 'Title', 'shoestrap' ),
                'id'        => 'boxes_title',
                'default'   => '',
                'type'      => 'text'
            ),
            array(
                'id' => 'number_of_boxes',
                'type' => 'button_set',
                'title'       => __( 'Number of Boxes', 'shoestrap' ),
                'options'   => array(
                    2 => '2 Boxes',
                    3 => '3 Boxes',
                    4 => '4 Boxes',
                ),
                'default' => '3',
            ),
            array(
                'id'       => 'section-box1',
                'type'     => 'section',
                'title'    => __( 'Box 1', 'redux-framework-demo' ),
                'indent'   => true, 
            ),
            array(
                'id'=>'box_1_icon',
                'type' => 'icon_select', 
                'title' => __('Icon', 'redux-framework-demo'),
                'default'     => '',
                'enqueue' => false, 
                'enqueue_frontend' => false, 
                'stylesheet' => get_template_directory_uri() . '/assets/css/icons.min.css', 
                'prefix' => '', 
                'selector' => 'icon-', 
                'height' => 200 
            ),
            array(
                'title'     => __( 'Title', 'shoestrap' ),
                'id'        => 'box_1_title',
                'default'   => '',
                'type'      => 'text'
            ),
            array(
                'id'=>'box_1_text',
                'type' => 'textarea',
                'title' => __('Text', 'redux-framework-demo'), 
                'default' => '',
            ),
            array(
                'title'     => __( 'Url', 'shoestrap' ),
                'id'        => 'box_1_url',
                'default'   => '',
                'type'      => 'text',
                'validate'  => 'url',
            ),
            array(
                'id'       => 'section-box2',
                'type'     => 'section',
                'title'    => __( 'Box 2', 'redux-framework-demo' ),
                'indent'   => true, 
            ),
            array(
                'id'=>'box_2_icon',
                'type' => 'icon_select', 
                'title' => __('Icon', 'redux-framework-demo'),
                'default'     => '',
                'enqueue' => false, 
                'enqueue_frontend' => false, 
                'stylesheet' => get_template_directory_uri() . '/assets/css/icons.min.css', 
                'prefix' => '', 
                'selector' => 'icon-', 
                'height' => 200 
            ),
            array(
                'title'     => __( 'Title', 'shoestrap' ),
                'id'        => 'box_2_title',
                'default'   => '',
                'type'      => 'text'
            ),
            array(
                'id'=>'box_2_text',
                'type' => 'textarea',
                'title' => __('Text', 'redux-framework-demo'), 
                'default' => '',
            ),
            array(
                'title'     => __( 'Url', 'shoestrap' ),
                'id'        => 'box_2_url',
                'default'   => '',
                'type'      => 'text',
                'validate'  => 'url',
            ),
            array(
                'id'       => 'section-box3',
                'type'     => 'section',
                'title'    => __( 'Box 3', 'redux-framework-demo' ),
                'indent'   => true, 
                'required' => array( 'number_of_boxes', 'equals', array( '3', '4' ) ),
            ),
            array(
                'id'=>'box_3_icon',
                'type' => 'icon_select', 
                'title' => __('Icon', 'redux-framework-demo'),
                'default'     => '',
                'enqueue' => false, 
                'enqueue_frontend' => false, 
                'stylesheet' => get_template_directory_uri() . '/assets/css/icons.min.css', 
                'prefix' => '', 
                'selector' => 'icon-', 
                'height' => 200,
                'required' => array( 'number_of_boxes', 'equals', array( '3', '4' ) ),
            ),
            array(
                'title'     => __( 'Title', 'shoestrap' ),
                'id'        => 'box_3_title',
                'default'   => '',
                'type'      => 'text',
                'required' => array( 'number_of_boxes', 'equals', array( '3', '4' ) ),
            ),
            array(
                'id'=>'box_3_text',
                'type' => 'textarea',
                'title' => __('Text', 'redux-framework-demo'), 
                'default' => '',
                'required' => array( 'number_of_boxes', 'equals', array( '3', '4' ) ),
            ),
            array(
                'title'     => __( 'Url', 'shoestrap' ),
                'id'        => 'box_3_url',
                'default'   => '',
                'type'      => 'text',
                'validate'  => 'url',
                'required' => array( 'number_of_boxes', 'equals', array( '3', '4' ) ),
            ),
            array(
                'id'       => 'section-box4',
                'type'     => 'section',
                'title'    => __( 'Box 4', 'redux-framework-demo' ),
                'indent'   => true, 
                'required' => array( 'number_of_boxes', 'equals', '4' ),
            ),
            array(
                'id'=>'box_4_icon',
                'type' => 'icon_select', 
                'title' => __('Icon', 'redux-framework-demo'),
                'default'     => '',
                'enqueue' => false, 
                'enqueue_frontend' => false, 
                'stylesheet' => get_template_directory_uri() . '/assets/css/icons.min.css', 
                'prefix' => '', 
                'selector' => 'icon-', 
                'height' => 200,
                'required' => array( 'number_of_boxes', 'equals', '4' ),
            ),
            array(
                'title'     => __( 'Title', 'shoestrap' ),
                'id'        => 'box_4_title',
                'default'   => '',
                'type'      => 'text',
                'required' => array( 'number_of_boxes', 'equals', '4' ),
            ),
            array(
                'id'=>'box_4_text',
                'type' => 'textarea',
                'title' => __('Text', 'redux-framework-demo'), 
                'default' => '',
                'required' => array( 'number_of_boxes', 'equals', '4' ),
            ),
            array(
                'title'     => __( 'Url', 'shoestrap' ),
                'id'        => 'box_4_url',
                'default'   => '',
                'type'      => 'text',
                'validate'  => 'url',
                'required' => array( 'number_of_boxes', 'equals', '4' ),
            ),
        )
    );

    $homeTemplate[] = array(
        'title' => __('Actions Section', 'redux-framework-demo'),
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-minus',
        'fields' => array(
            array(
                'title'     => __( 'Title', 'shoestrap' ),
                'id'        => 'section_actions_title',
                'default'   => 'I want to...',
                'type'      => 'text'
            ),
            array(
                'id'        => 'section_actions_include',
                'title'     => __('Actions', 'redux-framework-demo'),
                'type'      => 'select',
                'data'      => 'terms',
                'args'      => array('taxonomies'=>'action'),
                'multi'     => true,
                'desc'      => __('Select actions to display in section (If none selected all actions will be displayed).', 'redux-framework-demo'),
            ),
            array(
                'id' => 'section_actions_columns',
                'type' => 'select',
                'title' => __('Columns', 'pressapps' ), 
                'desc' => __('Select number columns to display in section.', 'pressapps' ),
                'options' => array(
                    2 => '2 Columns',
                    3 => '3 Columns',
                    4 => '4 Columns',
                    6 => '6 Columns',
                ),
                'default'   => 3, 
            ),
        )
    );

    $homeTemplate[] = array(
        'title' => __('Categories Section', 'redux-framework-demo'),
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-minus',
        'fields' => array(
            array(
                'title'     => __( 'Title', 'shoestrap' ),
                'id'        => 'section_categories_title',
                'default'   => 'Browse Help Topics',
                'type'      => 'text'
            ),
            array(
                'id'        => 'section_categories_include',
                'title'     => __('Categories', 'redux-framework-demo'),
                'type'      => 'select',
                'data'      => 'categories',
                'multi'     => true,
                'desc'      => __('Select categories to display in section (If none selected all categories will be displayed).', 'redux-framework-demo'),
            ),
            array(
                'id' => 'section_categories_columns',
                'type' => 'select',
                'title' => __('Columns', 'pressapps' ), 
                'desc' => __('Select number columns to display in section.', 'pressapps' ),
                'options' => array(
                    2 => '2 Columns',
                    3 => '3 Columns',
                    4 => '4 Columns',
                    6 => '6 Columns',
                ),
                'default'   => 3, 
            ),
        )
    );

    $homeTemplate[] = array(
        'title' => __('CTA Section', 'redux-framework-demo'),
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-minus',
        'fields' => array(
            array(
                'id'       => 'call_to_action',
                'type'     => 'editor',
                'title'    => __( 'Call To Action Content', 'redux-framework-demo' ),
            ),
        )
    );

    $metaboxes[] = array(
        'id' => 'home-metabox',
        'title' => __('Sections Options', 'pressapps'),
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
