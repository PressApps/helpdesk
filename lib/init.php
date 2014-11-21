<?php
/**
 * Roots initial setup and constants
 */
function roots_setup() {
  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/roots-translations
  load_theme_textdomain('roots', get_template_directory() . '/lang');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus(array(
    'primary_navigation_left' => __('Primary Navigation Left', 'roots'),
    'primary_navigation_right' => __('Primary Navigation Right', 'roots'),
    'footer_navigation' => __('Footer Navigation', 'roots'),
  ));

  // Add post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  //add_theme_support('post-thumbnails');

  // Add post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio'));

  // Add HTML5 markup for captions
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', array('caption', 'comment-form', 'comment-list'));

  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style('/assets/css/editor-style.css');
}
add_action('after_setup_theme', 'roots_setup');

/**
 * Register sidebars
 */
function roots_widgets_init() {
  register_sidebar(array(
    'name'          => __('Primary', 'roots'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<div class="widget %1$s %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ));

  register_sidebar(array(
    'name'          => __('Home Page', 'roots'),
    'id'            => 'sidebar-home',
    'before_widget' => '<div class="widget %1$s %2$s'. pa_count_widgets( 'sidebar-home' ) .' half-gutter-col"><div class="widget-inner">',
    'after_widget'  => '</div></div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ));

}
add_action('widgets_init', 'roots_widgets_init');



/**
 * Count number of widgets in a sidebar
 */
function pa_count_widgets( $sidebar_id, $count = FALSE ) {
  // If loading from front page, consult $_wp_sidebars_widgets rather than options
  // to see if wp_convert_widget_settings() has made manipulations in memory.
  global $_wp_sidebars_widgets;
  if ( empty( $_wp_sidebars_widgets ) ) :
    $_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
  endif;

  $sidebars_widgets_count = $_wp_sidebars_widgets;

  if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) && count( $sidebars_widgets_count[ $sidebar_id ] ) > 0 ) :
    $widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
    $col = ceil(12 / $widget_count);
    $widget_classes = ' col-sm-' . $col;
    if ($count) {
      return $widget_count;
    } else {
      return $widget_classes;
    }
  endif;
}

/**
 * Add classes to custom Reduxs sidebar widgets
 */
/*
add_filter('redux_custom_widget_args', 'custom_sidebar_classes');

function custom_sidebar_classes($options) {
  global $helpdesk, $post;
  $home_sidebar = redux_post_meta( 'helpdesk', $post->ID, 'home_sidebar' );

  $options = array(
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
    'before_widget' => '<div class="widget %1$s %2$s half-gutter-col '. pa_count_widgets( $home_sidebar ) .'"><div class="widget-inner">',
    'after_widget'  => '</div></div>'
  );

    return $options;
}
*/
