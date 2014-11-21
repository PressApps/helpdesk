<?php
/**
 * Breadcrumbs
 */
function pa_breadcrumbs() {
  global $post, $cat;    
  
  if (!is_front_page()) { 

    echo '<ol class="navbar-text breadcrumb">';

    //Home Link
    echo '<li><a href="' . get_home_url() . '">' . __('Home', 'roots') . '</a> </li>';
    
    //Category
    if (is_category()) {
      echo '<li>' . get_category_parents($cat, true) . '</li>';
    } elseif ( is_single() ) {
      //Single Post 
      $terms = wp_get_post_terms( $post->ID , 'category');
      $visited = array();

      foreach($terms as $term) {
        echo '<li>' . get_category_parents($term->term_id, true,'', false, $visited ) . '</li>';
      } // End foreach

      echo the_title('<li class="active">', '</li>');

    } elseif (is_search()) {

      echo '<li>' . sprintf(__('Search Results for %s', 'roots'), get_search_query()) . '</li>';

    } elseif (is_404()) {

      echo '<li>' . __('Not Found', 'roots') . '</li>';

    } else {
      echo the_title('<li class="active">', '</li>');
    }
    echo '</ol>';  
  } 
}