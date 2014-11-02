<?php
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more($more) {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

/**
 * Manage output of wp_title()
 */
function roots_wp_title($title) {
  if (is_feed()) {
    return $title;
  }

  $title .= get_bloginfo('name');

  return $title;
}
add_filter('wp_title', 'roots_wp_title', 10);

/**
 * Redirects search results from /?s=query to /search/query/, converts %20 to +
 *
 * @link http://txfx.net/wordpress-plugins/nice-search/
 * 
 * You can enable/disable this feature in functions.php (or lib/config.php if you're using Roots):
 * add_theme_support('soil-nice-search');
 */
function soil_nice_search_redirect() {
  global $wp_rewrite;
  if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks()) {
    return;
  }
  $search_base = $wp_rewrite->search_base;
  if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false) {
    wp_redirect(home_url("/{$search_base}/" . urlencode(get_query_var('s'))));
    exit();
  }
}
add_action('template_redirect', 'soil_nice_search_redirect');

/**
 * Hide sidebar on one column layout
 */
add_filter('roots/display_sidebar', 'pa_sidebars');

function pa_sidebars($sidebar) {
  global $helpdesk;

  if ($helpdesk['layout'] == '1') {
    return false;
  }
  return $sidebar;
}

/**
 * Output dynamic CSS at bottom of HEAD
 */
add_action('wp_head','pa_output_css');

function pa_output_css() {
  global $helpdesk;

  $output = '';

  if ($helpdesk['layout'] == '2') {
    $output .= '@media (min-width: 768px) { .sidebar-primary .main { float: right; } }';
  }

  if ( ! empty( $output ) ) {
      echo '<style type="text/css" title="helpdesk-css">' . $output . '</style>';
  }

}

