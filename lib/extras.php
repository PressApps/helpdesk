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
    $output .= '.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav li > a:hover, .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus, .dropdown-menu > li > a:hover { color: ' . $helpdesk['navbar_link_color']['hover'] . '; }';
    $output .= 'section .box i, section .box h3, .article-count, .sidebar h3 { color: ' . $helpdesk['link_color']['regular'] . '; }';
  }

  if ($helpdesk['layout'] == '2') {
    $output .= ' @media (min-width: 768px) { .sidebar-primary .main { float: right; } }';
  }

  if ( ! empty( $output ) ) {
      echo '<style type="text/css" id="helpdesk-css">' . $output . '</style>';
  }

}

/**
 * Page breadcrumbs
 */
/*
function pa_breadcrumbs() {
  if (!is_home()) {
    echo '<ol class="navbar-text breadcrumb">';
    global $post;
    echo '<li><a href="' . get_home_url() . '">' . __('Home', 'roots') . '</a> </li>';
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

    } else {
      echo the_title('<li class="active">', '</li>');
    }
    echo '</ol>';  
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

  return '<i class="post-format ' . $post_icon . '"></i> ';

}

/**
 * Category icons
 */
/*
function pa_category_icon($id = '') {
  $icon_value = pa_category_icon_url($id);
  if ($icon_value != '') { 
      $icon = explode('|',$icon_value); 
  } else { 
      $icon = array('','');
  };
}


/**
 * Article voting
 */
if ($helpdesk['article_voting'] == 1 || $helpdesk['article_voting'] == 2) {
  add_action('init', 'pa_article_vote');
  add_action('wp_head','pa_vote_js');
}

function pa_article_voting($is_ajax = FALSE) {
  global $helpdesk, $post;        

  if ($helpdesk['article_voting'] == 0) {
    return;
  }

  //$votes_like = (int) get_post_meta($post->ID, '_votes_likes', true);
  //$votes_dislike = (int) get_post_meta($post->ID, '_votes_dislikes', true);
  $cookie_vote_count      = '';

  $like_icon = '<span class="icon icon-Yes"></span> ';
  $dislike_icon = '<span class="icon icon-Close"></span> ';

  if(isset($_COOKIE['vote_count'])){
      $cookie_vote_count = @unserialize(base64_decode($_COOKIE['vote_count']));
  }
  
  if(!is_array($cookie_vote_count) && isset($cookie_vote_count)){
      $cookie_vote_count = array();
  }
 
  echo (($is_ajax)?'':'<div class="vote section">');
                          
  if (is_user_logged_in() || $helpdesk['article_voting'] == 1) :
      
          if(is_user_logged_in())
              $vote_count = (array) get_user_meta(get_current_user_id(), 'vote_count', true);
          else
              $vote_count = $cookie_vote_count;
          
          if (!in_array( $post->ID, $vote_count )) :
                  echo '<h3>' . __('Was this article helpful to you?', 'pressapps') . '</h3>';
                  echo '<p><a class="like-btn" href="javascript:" post_id="'  . $post->ID . '">' . $like_icon .'</a>';
                  echo '<a class="dislike-btn" href="javascript:" post_id="' . $post->ID . '">' . $dislike_icon . '</a></p>';

          else :
                  // already voted
                  echo '<h3>' . __('Thank you for your feedback!', 'pressapps') . '</h3>';
          endif;
  
  else :
          // not logged in
          echo '<h3>' . __('Login to leave your feedback!', 'pressapps') . '</h3>';
  endif;
  
  echo (($is_ajax)?'':'</div>');

}

function pa_article_vote() {
    global $post;
    global $helpdesk;    

    if (is_user_logged_in()) {
        
        $vote_count = (array) get_user_meta(get_current_user_id(), 'vote_count', true);
        
        if (isset( $_GET['vote_like'] ) && $_GET['vote_like']>0) :
                
                $post_id = (int) $_GET['vote_like'];
                $the_post = get_post($post_id);
                
                if ($the_post && !in_array( $post_id, $vote_count )) :
                        $vote_count[] = $post_id;
                        update_user_meta(get_current_user_id(), 'vote_count', $vote_count);
                        $post_votes = (int) get_post_meta($post_id, '_votes_likes', true);
                        $post_votes++;
                        update_post_meta($post_id, '_votes_likes', $post_votes);
                        $post = get_post($post_id);
                        pa_article_voting(true);
                        die('');
                endif;
                
        elseif (isset( $_GET['vote_dislike'] ) && $_GET['vote_dislike']>0) :
                
                $post_id = (int) $_GET['vote_dislike'];
                $the_post = get_post($post_id);
                
                if ($the_post && !in_array( $post_id, $vote_count )) :
                        $vote_count[] = $post_id;
                        update_user_meta(get_current_user_id(), 'vote_count', $vote_count);
                        $post_votes = (int) get_post_meta($post_id, '_votes_dislikes', true);
                        $post_votes++;
                        update_post_meta($post_id, '_votes_dislikes', $post_votes);
                        $post = get_post($post_id);
                        pa_article_voting(true);
                        die('');
                        
                endif;
                
        endif;

    } elseif (!is_user_logged_in() && $helpdesk['article_voting'] == 1) {

        // ADD VOTING FOR NON LOGGED IN USERS USING COOKIE TO STOP REPEAT VOTING ON AN ARTICLE
        $vote_count = '';
        
        if(isset($_COOKIE['vote_count'])){
            $vote_count = @unserialize(base64_decode($_COOKIE['vote_count']));
        }
        
        if(!is_array($vote_count) && isset($vote_count)){
            $vote_count = array();
        }
        
        if (isset( $_GET['vote_like'] ) && $_GET['vote_like']>0) :
                
                $post_id = (int) $_GET['vote_like'];
                $the_post = get_post($post_id);
                
                if ($the_post && !in_array( $post_id, $vote_count )) :
                        $vote_count[] = $post_id;
                        $_COOKIE['vote_count']  = base64_encode(serialize($vote_count));
                        setcookie('vote_count', $_COOKIE['vote_count'] , time()+(10*365*24*60*60),'/');
                        $post_votes = (int) get_post_meta($post_id, '_votes_likes', true);
                        $post_votes++;
                        update_post_meta($post_id, '_votes_likes', $post_votes);
                        $post = get_post($post_id);
                        pa_article_voting(true);
                        die('');
                endif;
                
        elseif (isset( $_GET['vote_dislike'] ) && $_GET['vote_dislike']>0) :
                
                $post_id = (int) $_GET['vote_dislike'];
                $the_post = get_post($post_id);
                
                if ($the_post && !in_array( $post_id, $vote_count )) :
                        $vote_count[] = $post_id;
                        $_COOKIE['vote_count']  = base64_encode(serialize($vote_count));
                        setcookie('vote_count', $_COOKIE['vote_count'] , time()+(10*365*24*60*60),'/');
                        $post_votes = (int) get_post_meta($post_id, '_votes_dislikes', true);
                        $post_votes++;
                        update_post_meta($post_id, '_votes_dislikes', $post_votes);
                        $post = get_post($post_id);
                        pa_article_voting(true);
                        die('');
                        
                endif;
                
        endif;

    } elseif (!is_user_logged_in() && $helpdesk['article_voting'] == 2) {

        return;
        
    }
        
}

function pa_vote_js(){
    $paav = array(
        'base_url'  => esc_url(home_url()),
    );
    ?>
<script type="text/javascript">
    PAAV = <?php echo json_encode($paav); ?>;
</script>
    <?php
}

add_action('save_post','pa_reset_post_votes');

function pa_reset_post_votes($post_id){
    
    $reset_flag = get_post_meta($post_id,'reset_post_votes',TRUE);
    /**
     * In case the reset Button Is being Pressed in that case Reset
     * All the Votes and Delete that post meta as well
     */
    if(!empty($reset_flag)){
        delete_post_meta($post_id, '_votes_likes');
        delete_post_meta($post_id, '_votes_dislikes');
        delete_post_meta($post_id, 'reset_post_votes');
    }
}

add_action('init','pa_reset_all_post_votes');

/**
 * 
 * Delete All the Likes/Dislikes by firing single Database Query
 * 
 * @global type $helpdesk
 * @global type $wpdb
 * @global type $reduxConfig
 * @return null
 */
function pa_reset_all_post_votes(){
    global $helpdesk,$wpdb,$reduxConfig;

    $reset_all_votes = $helpdesk['reset_all_votes'];
   
    if(empty($reset_all_votes))
        return NULL;
    
    $reduxConfig->ReduxFramework->set('reset_all_votes','');
    $qry = " DELETE FROM {$wpdb->postmeta} WHERE meta_key IN ('_votes_likes','_votes_dislikes') ";
    $wpdb->query($qry);
}

/* add vote columns to posts */
function pa_add_votes_columns($columns) {
    return array_merge( $columns, 
              array('likes' => '<a href="' . admin_url('edit.php?orderby=_votes_likes&order=desc') . '">' . __('Likes', 'pressapps') . '</a>', 'dislikes' => '<a href="' . admin_url('edit.php?orderby=_votes_dislikes&order=desc') . '">' . __('Dislikes', 'pressapps') . '</a>' ) );
}
add_filter('manage_posts_columns' , 'pa_add_votes_columns');

/* Add the Additional column Values for the Posts */
add_action( 'manage_posts_custom_column', 'pa_manage_post_columns', 10, 2 );

function pa_manage_post_columns( $column, $post_id ) {
    global $post;

    switch( $column ) {

        case 'likes' :

            $likes = get_post_meta( $post_id, '_votes_likes', true );
            if ( empty( $likes ) )
                echo '';
            else
                echo $likes;
            break;

        case 'dislikes' :

            $dislikes = get_post_meta( $post_id, '_votes_dislikes', true );
            if ( empty( $dislikes ) )
                echo '';
            else
                echo $dislikes;
            break;

        default :
            break;
    }
}

add_action( 'pre_get_posts', 'pa_order_by_votes' );

function pa_order_by_votes( $query ) {
    if( ! is_admin() )
        return;

    $orderby = $query->get( 'orderby');

    if( '_votes_likes' == $orderby ) {
        $query->set('meta_key','_votes_likes');
        $query->set('orderby','meta_value_num');
    }

    if( '_votes_dislikes' == $orderby ) {
        $query->set('meta_key','_votes_dislikes');
        $query->set('orderby','meta_value_num');
    }

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
    
  $prevposts = get_previous_posts_link('&larr; Previous');
  if($prevposts) { echo '<li>' . $prevposts  . '</li>'; }
  
  for($i = $start_page; $i  <= $end_page; $i++) {
    if($i == $paged) {
      echo '<li class="active"><a href="#">'.$i.'</a></li>';
    } else {
      echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
    }
  }
  echo '<li class="">';
  next_posts_link('Next &rarr;');
  echo '</li>';
  echo '</ul>'.$after."</nav>";
}

/**
 * Category icons
 */
add_action('admin_init', 'pa_init');
function pa_init() {
  $pa_taxonomies = array('category', 'action');;
  if (is_array($pa_taxonomies)) {
      foreach ($pa_taxonomies as $pa_taxonomy) {
          add_action($pa_taxonomy.'_add_form_fields', 'pa_add_texonomy_field');
      add_action($pa_taxonomy.'_edit_form_fields', 'pa_edit_texonomy_field');
      add_filter( 'manage_edit-' . $pa_taxonomy . '_columns', 'pa_taxonomy_columns' );
      add_filter( 'manage_' . $pa_taxonomy . '_custom_column', 'pa_taxonomy_column', 10, 3 );
      }
  }
}

// add image field in add form
function pa_add_texonomy_field() {
  if (get_bloginfo('version') >= 3.5)
    wp_enqueue_media();
  else {
    wp_enqueue_style('thickbox');
    wp_enqueue_script('thickbox');
  }
  
  echo '<div class="form-field">
    <label for="category_icon">' . __('Icon', 'pressapps') . '</label>';

        $html = '<input class="regular-text" id="category_icon" name="category_icon" type="hidden" value="" />';
        $html .= '<div id="preview_category_icon" class="button icon-picker" data-target="#category_icon"></div>';
      echo $html;
  echo '</div>';
}

// add image field in edit form
function pa_edit_texonomy_field($taxonomy) {
  if (get_bloginfo('version') >= 3.5)
    wp_enqueue_media();
  else {
    wp_enqueue_style('thickbox');
    wp_enqueue_script('thickbox');
  }
  
  if (pa_category_icon_url( $taxonomy->term_id, NULL, TRUE ) == '') 
    $image_text = "";
  else
    $image_text = pa_category_icon_url( $taxonomy->term_id, NULL, TRUE );

    $value = $image_text;
        if ($value != '') { $preview = explode('|',$value); } else { $preview = array('','');};
        $html = '<input class="regular-text" id="category_icon" name="category_icon" type="hidden" value="'.$value.'"  />';
        $html .= '<div id="preview_category_icon" class="button icon-picker '.$preview[0].' '.$preview[1].'" data-target="#category_icon"></div>';
    echo '<tr class="form-field">
      <th scope="row" valign="top"><label for="category_icon">' . __('Icon', 'pressapps') . '</label></th>';
    echo '<td>' . $html . '<td>';
    echo '</tr>';
}

// save our taxonomy image while edit or save term
add_action('edit_term','pa_save_category_icon');
add_action('create_term','pa_save_category_icon');
function pa_save_category_icon($term_id) {
    if(isset($_POST['category_icon']))
        update_option('pa_category_icon'.$term_id, $_POST['category_icon']);
}

// get attachment ID by image url
function pa_get_attachment_id_by_url($image_src) {
    global $wpdb;
    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid = '$image_src'";
    $id = $wpdb->get_var($query);
    return (!empty($id)) ? $id : NULL;
}

// get taxonomy image url for the given term_id (Place holder image by default)
function pa_category_icon_url($term_id = NULL, $explode_icon = FALSE) {
  if (!$term_id) {
    if (is_category())
      $term_id = get_query_var('cat');
    elseif (is_tax()) {
      $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
      $term_id = $current_term->term_id;
    }
  }
  
    $category_icon_url = get_option('pa_category_icon'.$term_id);
    if(!empty($category_icon_url)) {
      $attachment_id = pa_get_attachment_id_by_url($category_icon_url);
      if(!empty($attachment_id)) {
        $category_icon_url = wp_get_attachment_image_src($attachment_id, $size);
        $category_icon_url = $category_icon_url[0];
      }
  }

  if ($explode_icon) {
    $icon_css = explode('|', $category_icon_url);
    return '<i class="' . $icon_css[0] . ' ' . $icon_css[1] . '"></i>';
  } else {
    return $category_icon_url;
  }
}

function pa_quick_edit_custom_box($column_name, $screen, $name) {
  if ($column_name == 'thumb') 
    echo '<fieldset>
    <div class="thumb inline-edit-col">
      <label>
        <span class="title">Icon</span>
        <span class="input-text-wrap">
              <input class="regular-text" id="category_icon" name="category_icon" type="hidden" value="" />
              <div id="preview_category_icon" class="button icon-picker" data-target="#category_icon"></div>
        </span>
      </label>
    </div>
  </fieldset>';
}

/**
 * Thumbnail column added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
function pa_taxonomy_columns( $columns ) {
  $new_columns = array();
  $new_columns['cb'] = $columns['cb'];
  $new_columns['thumb'] = __('Icon', 'pressapps');

  unset( $columns['cb'] );

  return array_merge( $new_columns, $columns );
}

/**
 * Thumbnail column value added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
function pa_taxonomy_column( $columns, $column, $id ) {
  if ( $column == 'thumb' ) {

    $value = pa_category_icon_url($id, NULL, TRUE);

        if ($value != '') { $preview = explode('|',$value); } else { $preview = array('','');};
    $columns = '<i class="' .$preview[0].' '.$preview[1]. '"></i>';
  }
  return $columns;
}


// style the image in category list
if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 ) {
  add_action('quick_edit_custom_box', 'pa_quick_edit_custom_box', 10, 3);
}


/*
 * Live Search
 */
add_action('wp_ajax_search_title', 'pa_live_search');  // hook for login users
add_action('wp_ajax_nopriv_search_title', 'pa_live_search'); // hook for not login users

function pa_live_search() {
    global $wpdb, $helpdesk;
    
    $post_status  = 'publish';
    $search_term  = "%".$_REQUEST['query']."%";

    //if ($helpdesk['search_post_types']) {
    //  $post_type = "'" . implode("','", $helpdesk['search_post_types']) . "'";
    //} else {
      $post_type = "'post'";
    //}

    if ($helpdesk['live_search_in'] == '2') {
      $sql_query = $wpdb->prepare( "SELECT ID, post_title, post_type, post_content as post_content, post_name from $wpdb->posts where post_status = %s and post_type in ( $post_type )and (post_title like %s or post_content like %s)", $post_status, $search_term, $search_term );
    } else {
      $sql_query = $wpdb->prepare( "SELECT ID, post_title, post_type, post_content as post_content, post_name from $wpdb->posts where post_status = %s and post_type in ( $post_type )and post_title like %s", $post_status, $search_term );
    }
  
  $results = $wpdb->get_results($sql_query);
  
  $search_json = array( "query" => "Unit", "suggestions" => array() );   // create a json array
  
  foreach ( $results as $result ) {

    $link = get_permalink( $result->ID ); // get post url
    $icon = pa_post_format_icon($result->ID);

    $search_json["suggestions"][] = array(
                      "value" => $result->post_title,
                      "data"  => array( "content" => $result->post_content, "url" => $link ),
                      "icon" => $icon,
                    );
  }
  echo json_encode($search_json); // convert array to joson string
  die();
}


/**
 * Pupular posts
 */

// Setup activation
add_action('after_switch_theme', 'PA_Popular_System::install');

// Class for installation and uninstallation
class PA_Popular_System{
  public static function actions() {
    // Check for token
    if ( ! wp_verify_nonce( $_POST['token'], 'pamp_token' ) ) die();
    $track = new PAMP_track( intval( $_POST['id'] ) );
  }
  
  public static function install() {
    PAMP_setup::install();
  }
  
  public static function javascript() {
    global $wp_query;
    wp_reset_query();
    wp_print_scripts('jquery');
    $token = wp_create_nonce( 'pamp_token' );
    if ( ! is_front_page() && ( is_page() || is_single() ) ) {
      echo '<!-- Popular Articles --><script type="text/javascript">/* <![CDATA[ */ jQuery.post("' . admin_url('admin-ajax.php') . '", { action: "pamp_update", id: ' . $wp_query->post->ID . ', token: "' . $token . '" }); /* ]]> */</script><!-- /Popular Articles -->';
    }
  }
}

// Use ajax for tracking popular posts
add_action( 'wp_head', 'PA_Popular_System::javascript' );
add_action( 'wp_ajax_pamp_update', 'PA_Popular_System::actions' );
// Comment out to stop logging stats for admin and logged in users
add_action( 'wp_ajax_nopriv_pamp_update', 'PA_Popular_System::actions' );

function pa_get_popular( $args = array() ) {
  global $wpdb;
  
  // Default arguments
  $limit = 5;
  $post_type = array( 'post' );
  $range = 'all_time';
  
  if ( isset( $args['limit'] ) ) {
    $limit = $args['limit'];
  }
  
  if ( isset( $args['post_type'] ) ) {
    if ( is_array( $args['post_type'] ) ) {
      $post_type = $args['post_type'];
    } else {
      $post_type = array( $args['post_type'] );
    }
  }
  
  if ( isset( $args['range'] ) ) {
    $range = $args['range'];
  }
  
  switch( $range ) {
    CASE 'all_time':
      $order = "ORDER BY all_time_stats DESC";
      break;
    CASE 'monthly':
      $order = "ORDER BY 30_day_stats DESC";
      break;
    CASE 'weekly':
      $order = "ORDER BY 7_day_stats DESC";
      break;
    CASE 'daily':
      $order = "ORDER BY 1_day_stats DESC";
      break;
    DEFAULT:
      $order = "ORDER BY all_time_stats DESC";
      break;
  }

  $holder = implode( ',', array_fill( 0, count( $post_type ), '%s') );
  
  $sql = "
    SELECT
      p.*
    FROM
      {$wpdb->prefix}most_popular mp
      INNER JOIN {$wpdb->prefix}posts p ON mp.post_id = p.ID
    WHERE
      p.post_type IN ( $holder ) AND
      p.post_status = 'publish'
    {$order}
    LIMIT %d
  ";

  $result = $wpdb->get_results( $wpdb->prepare( $sql, array_merge( $post_type, array( $limit ) ) ), OBJECT );
  
  if ( ! $result) {
    return array();
  }
  
  return $result;
}

class PAMP_setup {
  public static function install() {
    // Create table
    global $wpdb;
    $table = $wpdb->prefix . "most_popular";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
      $sql = "CREATE TABLE $table (
            id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            post_id BIGINT NOT NULL,
            last_updated DATETIME NOT NULL,
            1_day_stats MEDIUMINT NOT NULL,
            7_day_stats MEDIUMINT NOT NULL,
            30_day_stats MEDIUMINT NOT NULL,
            all_time_stats BIGINT NOT NULL,
            raw_stats text NOT NULL);
          ";
      $wpdb->query($sql);
    }
  }
}

class PAMP_track {
  private $post_id = NULL;
  
  public function __construct( $post_id ) {
    $this->post_id = $post_id;
    
    // Action to update stats
    $this->update_stats();
  }
  
  private function update_stats() {
    global $wpdb;
    
    if ( $this->post_id ) {
      // Get the existing raw stats
      $raw_stats = $wpdb->get_var( $wpdb->prepare( "SELECT raw_stats FROM {$wpdb->prefix}most_popular WHERE post_id = '%d'", array( $this->post_id ) ) );
      $date = gmdate('Y-m-d');
      
      if ( $raw_stats ) {
        $raw_stats = unserialize( $raw_stats );
      } else {
        // Create a entry for this post
        $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}most_popular (post_id, last_updated, 1_day_stats, 7_day_stats, 30_day_stats, all_time_stats, raw_stats) VALUES ('%d', NOW(), '0', '0', '0', '0', '')", array( $this->post_id ) ) );
      }
      
      $count_1 = $this->calculate_1_day_stats( $raw_stats, $date );
      $count_7 = $this->calculate_7_day_stats( $raw_stats, $date );
      $count_30 = $this->calculate_30_day_stats( $raw_stats, $date );
      
      if ( isset( $row_stats ) && count( $raw_stats ) >= 30 ) {
        array_shift( $raw_stats );
        $raw_stats[$date] = 1;
      } else {
        if ( ! isset( $raw_stats[$date] ) ) {
          $raw_stats[$date] = 1;
        } else {
          $raw_stats[$date]++;
        }
      } 
      
      // Update our table with new figures
      $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}most_popular SET 1_day_stats = '{$count_1}', 7_day_stats = '{$count_7}', 30_day_stats = '{$count_30}', all_time_stats = all_time_stats + 1, raw_stats = '%s' WHERE post_id = '%d'", array( serialize( $raw_stats ), $this->post_id ) ) );
    }
  }
  
  private function calculate_1_day_stats( $existing_stats, $date ) {
    if ( $existing_stats ) {
      if ( isset( $existing_stats[$date] ) ) {
        return $existing_stats[$date] + 1;
      }
    }
    return 1;
  }
  
  private function calculate_7_day_stats( $existing_stats, $date ) {
    if ( $existing_stats ) {
      $extra_to_add = 0;
      if ( isset( $existing_stats[$date] ) ) {
        $extra_to_add = $existing_stats[$date];
      }
      $total = 0;
      for ( $i = 1; $i < 7; $i++ ) {
        $old_date = date('Y-m-d', strtotime( "-{$i} days" ) );
        if ( isset( $existing_stats[$old_date] ) ) {
          $total += $existing_stats[$old_date];
        }
      }
      return $total + $extra_to_add + 1;
    }
    return 1;
  }
  
  private function calculate_30_day_stats( $existing_stats, $date ) {
    if ( $existing_stats ) {
      $extra_to_add = 0;
      if ( isset( $existing_stats[$date] ) ) {
        $extra_to_add = $existing_stats[$date];
      }
      $total = 0;
      for ( $i = 1; $i < 30; $i++ ) {
        $old_date = date('Y-m-d', strtotime( "-{$i} days" ) );
        if ( isset( $existing_stats[$old_date] ) ) {
          $total += $existing_stats[$old_date];
        }
      }
      return $total + $extra_to_add + 1;
    }
    return 1;
  }
}

/**
 * Return an array of the social links the user has entered.
 * This is simply a helper function for other functions.
 */
function get_social_links() {
  global $helpdesk;
  // An array of the available networks
  $networks   = array();

  // Started on the new stuff, not done yet.
  $networks[] = array( 'url' => $helpdesk['dribbble_link'],     'icon' => 'dribbble',   'fullname' => 'Dribbble' );
  $networks[] = array( 'url' => $helpdesk['facebook_link'],     'icon' => 'facebook',   'fullname' => 'Facebook' );
  $networks[] = array( 'url' => $helpdesk['flickr_link'],       'icon' => 'flickr',     'fullname' => 'Flickr' );
  $networks[] = array( 'url' => $helpdesk['github_link'],       'icon' => 'github',     'fullname' => 'GitHub' );
  $networks[] = array( 'url' => $helpdesk['google_plus_link'],  'icon' => 'googleplus', 'fullname' => 'Google+' );
  $networks[] = array( 'url' => $helpdesk['email_link'],    'icon' => 'mail',  'fullname' => 'Email' );
  $networks[] = array( 'url' => $helpdesk['linkedin_link'],     'icon' => 'linkedin',   'fullname' => 'LinkedIn' );
  $networks[] = array( 'url' => $helpdesk['pinterest_link'],    'icon' => 'pinterest',  'fullname' => 'Pinterest' );
  $networks[] = array( 'url' => $helpdesk['picassa_link'],       'icon' => 'picassa',     'fullname' => 'Picassa' );
  $networks[] = array( 'url' => $helpdesk['rss_link'],          'icon' => 'feed',        'fullname' => 'RSS' );
  $networks[] = array( 'url' => $helpdesk['skype_link'],        'icon' => 'skype',      'fullname' => 'Skype' );
  $networks[] = array( 'url' => $helpdesk['soundcloud_link'],   'icon' => 'soundcloud', 'fullname' => 'SoundCloud' );
  $networks[] = array( 'url' => $helpdesk['stackoverflow_link'],   'icon' => 'stackoverflow', 'fullname' => 'Stack Overflow' );
  $networks[] = array( 'url' => $helpdesk['wordpress_link'],       'icon' => 'wordpress',     'fullname' => 'WordPress' );
  $networks[] = array( 'url' => $helpdesk['twitter_link'],      'icon' => 'twitter',    'fullname' => 'Twitter' );
  $networks[] = array( 'url' => $helpdesk['vimeo_link'],        'icon' => 'vimeo',      'fullname' => 'Vimeo' );
  $networks[] = array( 'url' => $helpdesk['youtube_link'],      'icon' => 'youtube',    'fullname' => 'YouTube' );

  return $networks;
}

/**
 * "I want to..." action taxonomy
 */
add_action( 'init', 'create_action_taxonomy', 0 );

function create_action_taxonomy() {
  $labels = array(
    'name'              => _x( 'Actions', 'taxonomy general name' ),
    'singular_name'     => _x( 'Action', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Actions' ),
    'all_items'         => __( 'All Actions' ),
    'parent_item'       => __( 'Parent Action' ),
    'parent_item_colon' => __( 'Parent Action:' ),
    'edit_item'         => __( 'Edit Action' ),
    'update_item'       => __( 'Update Action' ),
    'add_new_item'      => __( 'Add New Action' ),
    'new_item_name'     => __( 'New Action Name' ),
    'menu_name'         => __( 'Action' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'action' ),
  );

  register_taxonomy( 'action', array( 'post' ), $args );
}

