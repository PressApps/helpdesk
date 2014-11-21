<?php
/**
 * PressApps includes
 *
 * The $pa_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 */

define('OPT_NAME', 'helpdesk');

/**
 * Include addons
 */
/*
$path = dirname( __FILE__ ) . '/lib/addons/';
$addons = scandir( $path, 1 );      
foreach($addons as $addon_file) {
  if ( !is_file($path . $addon_file) ) {
    continue; 
  } 
  $addon_path = $path . $addon_file;
  require_once $addon_path;
}
unset($addon_file, $addon_path);
*/
/**
 * Core files
 */
$pa_includes = array(
  'lib/extensions/extensions.php',      // Metaboxes
  'lib/options.php',         // Theme Options
  'lib/utils.php',           // Utility functions
  'lib/init.php',            // Initial theme setup and constants
  'lib/widgets.php',         // Widgets
  'lib/wrapper.php',         // Theme wrapper class
  'lib/sidebar.php',         // Sidebar class
  'lib/config.php',          // Configuration
  'lib/titles.php',          // Page titles
  'lib/nav.php',             // Custom nav modifications
  'lib/dependencies.php',    // Install deoendency plugins
  'lib/scripts.php',         // Scripts and stylesheets
  'lib/extras.php',          // Custom functions
  'lib/reorder.php',          // Custom
  'lib/post-types.php',          // Custom
  'lib/popular.php',          // Custom
  'lib/live-search.php',          // Custom
  'lib/category-icons.php',          // Custom
  'lib/votes.php',          // Custom
  'lib/breadcrumbs.php',          // Custom
  'lib/dynamic-css.php',          // Custom
);

foreach ($pa_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'roots'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

