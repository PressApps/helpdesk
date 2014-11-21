<?php
/**
 * Output dynamic CSS at bottom of HEAD
 */
add_action('wp_head','pa_output_css');

function pa_output_css() {
  global $helpdesk;

  $output = '';
                          
  if ($helpdesk['navbar_link_color']) {
    $output .= '.navbar-default .navbar-nav > li > a, .dropdown-menu > li > a { color: ' . $helpdesk['navbar_link_color']['regular'] . '; }';
    $output .= '.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav li > a:hover, .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus, .dropdown-menu > li > a:hover { color: ' . $helpdesk['navbar_link_color']['hover'] . '; }';
    $output .= 'section .box i, section .box h3, .article-count, .sidebar h3 { color: ' . $helpdesk['link_color']['regular'] . '; }';
  }

  if (pa_left_sidebar()) {
    $output .= ' @media (min-width: 768px) { .sidebar-primary .main { float: right; } }';
  }

  if ($helpdesk['icons_category'] && $helpdesk['icons_post_format']) {
    $output .= '.kb-row .icon-wrap {min-width: 40px;text-align: center;margin-right: 0;}';
  } else {
    $output .= '.kb-row .icon-wrap {margin-left: 1px;margin-right: 10px;}';
  }

  $output .= $helpdesk['custom_css'];

  if ( ! empty( $output ) ) {
      echo '<style type="text/css" id="helpdesk-css">' . $output . '</style>';
  }

}
