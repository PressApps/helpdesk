<?php
global $helpdesk;

/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more($more) {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

/**
 * Custom excerpt lenghth
 */
function pa_excerpt($excerpt_length = 55, $echo = true) {
         
  $text = '';
  global $post;
  $text = ($post->post_excerpt) ? $post->post_excerpt : get_the_content('');
  $text = strip_shortcodes( $text );
  $text = apply_filters('the_content', $text);
  $text = str_replace(']]>', ']]&gt;', $text);
  $text = strip_tags($text);
       
  $excerpt_more = ' ' . '';
  $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
  if ( count($words) > $excerpt_length ) {
    array_pop($words);
    $text = implode(' ', $words);
    $text = $text . $excerpt_more;
  } else {
    $text = implode(' ', $words);
  }
  if($echo)
    echo apply_filters('the_content', $text);
  else
    return $text;
}
 
function get_pa_excerpt($excerpt_length = 55, $echo = false) {
 return pa_excerpt($excerpt_length, $id, $echo);
}

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
function pa_nice_search_redirect() {
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

if ($helpdesk['nice_search']) {
  add_action('template_redirect', 'pa_nice_search_redirect');
}

/**
 * Hide sidebar on one column layout
 */
add_filter('roots/display_sidebar', 'pa_sidebars');

function pa_sidebars($sidebar) {
  global $helpdesk;

  if (is_category() && $helpdesk['layout_category'] == '1') {
    return false;
  } elseif (is_singular('post') && $helpdesk['layout_single'] == '1') {
    return false;
  } elseif ($helpdesk['layout'] == '1') {
    return false;
  }
  return $sidebar;
}

/**
 * Left sidebar
 */
function pa_left_sidebar($sidebar = FALSE) {
  global $helpdesk;

  if (is_category() && $helpdesk['layout_category'] == '2') {
    return true;
  } elseif (is_singular('post') && $helpdesk['layout_single'] == '2') {
    return true;
  } elseif ($helpdesk['layout'] == '2') {
    return true;
  }
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

/**
 * Post format icons 
 */
function pa_post_format_icon($post_id = '') {
  global $helpdesk;

  if (!$helpdesk['icons_post_format']) {
    return;
  }

  switch(get_post_format($post_id)){
      case 'gallery':
          $post_icon = 'icon-Picture';
          break;
      case 'link':
          $post_icon = 'icon-File';
          break;
      case 'quote':
          $post_icon = 'icon-File';
          break;
      case 'status':
          $post_icon = 'icon-File';
          break;
      case 'video':
          $post_icon = 'icon-Video-4';
          break;
      case 'audio':
          $post_icon = 'icon-Music-Note2';
          break;
      case 'chat':
          $post_icon = 'icon-File';
          break;
      case 'image':
          $post_icon = 'icon-Photo';
          break;
      default:
          $post_icon = 'icon-File';
          break;
  }

  return '<span class="icon-wrap"><i class="' . $post_icon . '"></i></span>';
}

function pa_view_all_icon() {
  global $helpdesk;
  if (!$helpdesk['icons_post_format']) {
    return;
  }
  return '<span class="icon-wrap"><i class="icon-Files"></i></span>';
}

/**
 * Pagination
 */
function page_navi($before = '', $after = '') {
  global $wpdb, $wp_query;
  $request = $wp_query->request;
  $posts_per_page = intval(get_query_var('posts_per_page'));
  $paged = intval(get_query_var('paged'));
  $numposts = $wp_query->found_posts;
  $max_page = $wp_query->max_num_pages;
  if ( $numposts <= $posts_per_page ) { return; }
  if(empty($paged) || $paged == 0) {
    $paged = 1;
  }
  $pages_to_show = 7;
  $pages_to_show_minus_1 = $pages_to_show-1;
  $half_page_start = floor($pages_to_show_minus_1/2);
  $half_page_end = ceil($pages_to_show_minus_1/2);
  $start_page = $paged - $half_page_start;
  if($start_page <= 0) {
    $start_page = 1;
  }
  $end_page = $paged + $half_page_end;
  if(($end_page - $start_page) != $pages_to_show_minus_1) {
    $end_page = $start_page + $pages_to_show_minus_1;
  }
  if($end_page > $max_page) {
    $start_page = $max_page - $pages_to_show_minus_1;
    $end_page = $max_page;
  }
  if($start_page <= 0) {
    $start_page = 1;
  }
    
  echo $before.'<nav class="text-center"><ul class="pagination pagination-sm">'."";
    
  $prevposts = get_previous_posts_link('&larr;');
  if($prevposts) { echo '<li>' . $prevposts  . '</li>'; }
  
  for($i = $start_page; $i  <= $end_page; $i++) {
    if($i == $paged) {
      echo '<li class="active"><a href="#">'.$i.'</a></li>';
    } else {
      echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
    }
  }
  echo '<li class="">';
  next_posts_link('&rarr;');
  echo '</li>';
  echo '</ul>'.$after."</nav>";
}

/**
 * Styled elements
 */
function pa_style_tag($id) {
  $meta = redux_post_meta( 'helpdesk', $id );
  $class = '';
  if ($meta['style_ol']) {
    $class .= ' style-ol';
  }
  return $class;
}


