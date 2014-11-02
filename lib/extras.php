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
                          
  if ($helpdesk['navbar_link_color']) {
    $output .= '.navbar-default .navbar-nav > li > a, .dropdown-menu > li > a { color: ' . $helpdesk['navbar_link_color']['regular'] . '; }';
    $output .= '.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav li > a:hover,
    .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus, .dropdown-menu > li > a:hover { color: ' . $helpdesk['navbar_link_color']['hover'] . '; }';
  

  }

  if ($helpdesk['layout'] == '2') {
    $output .= '@media (min-width: 768px) { .sidebar-primary .main { float: right; } }';
  }

  if ( ! empty( $output ) ) {
      echo '<style type="text/css" title="helpdesk-css">' . $output . '</style>';
  }

}

/**
 * Page breadcrumbs
 */
function pa_breadcrumbs() {
  if (!is_home()) {
    echo '<ol class="navbar-text breadcrumb">';
    global $post;
    echo '<li><a href="' . get_home_url() . '"><span class="icon-Home"></span></a> </li>';
    if ( is_page() && $post->post_parent ) {
      // get the parent page breadcrumb
      $parent_title = get_the_title($post->post_parent);
      if ( $parent_title != the_title(' ', ' ', false) ) {
        echo '<li>><a href=' . get_permalink($post->post_parent) . '' . 'title=' . $parent_title . '>' . $parent_title . '</a></li>';
      }
    } else {
      // first, display the blog page link, since that's a global parent, but only if it's set to be different than the home page
      if ( get_option('page_for_posts') ) {
        // defines the blog page if it's set
        $blog_page_uri = get_permalink( get_option( 'page_for_posts' ) );
        echo '<li><a href="' . $blog_page_uri . '">News</a></li>';
      }
      // this is a post, so get the category, if it exists
      $category = get_the_category();
      if ($category) {
        foreach($category as $category) {
        echo '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
        }
      }
    }
  }
  if (is_singular()) {
    echo the_title('<li class="active">', '</li>');
  }
  echo '</ol>';
}

/**
 * Add custom favicon to head
 */
function pa_add_favicon(){ 
  global $helpdesk;
  ?>
  <!-- Custom Favicons -->
  <link rel="shortcut icon" href="<?php echo $helpdesk['favicon']['url']; ?>"/>
  <?php }
add_action('wp_head','pa_add_favicon');
