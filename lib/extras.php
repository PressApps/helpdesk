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
    $output .= '.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav li > a:hover, .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus, .dropdown-menu > li > a:hover { color: ' . $helpdesk['navbar_link_color']['hover'] . '; }';
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

/**
 * Post format icons 
 */
function pa_post_format_icon() {
    if (get_post_format() == 'video') {
        return '<span class="icon-Video-4"></span>';
    } elseif (get_post_format() == 'image') {
        return '<span class="icon-Photo"></span>';
    } elseif (get_post_format() == 'gallery') {
        return '<span class="icon-Photos"></span>';
    } elseif (get_post_format() == 'audio') {
        return '<span class="icon-Music-Note2"></span>';
    } else {
        return '<span class="icon-File"></span>';
    }
}

/**
 * Article voting
 */
function pado_docs_votes($is_ajax = FALSE) {

        global $pado_options;        
        global $post;
        $votes_like = (int) get_post_meta($post->ID, '_votes_likes', true);
        $votes_dislike = (int) get_post_meta($post->ID, '_votes_dislikes', true);
        $voted_like             = sprintf(_n('%s person found this helpful', '%s people found this helpful', $votes_like, 'pressapps'), $votes_like);
        $voted_dislike  = sprintf(_n('%s person did not find this helpful', '%s people did not find this helpful', $votes_dislike, 'pressapps'), $votes_dislike);
        //$vote_like_link                 = __("I found this helpful", 'pressapps');
        //$vote_dislike_link      = __("I did not find this helpful", 'pressapps');
        $cookie_vote_count      = '';

        //if ($pado_options['icon'] == 'thumbs') {
            $like_icon = '<span class="icon icon-Yes"></span> ';
            $dislike_icon = '<span class="icon icon-Close"></span> ';
        //} else {
        //    $like_icon = 'icon-Smile';
        //    $dislike_icon = 'icon-Depression';
        //}

        if(isset($_COOKIE['vote_count'])){
            $cookie_vote_count = @unserialize(base64_decode($_COOKIE['vote_count']));
        }
        
        if(!is_array($cookie_vote_count) && isset($cookie_vote_count)){
            $cookie_vote_count = array();
        }
       
        echo (($is_ajax)?'':'<div class="pado-votes">');
                                
        if (is_user_logged_in() || $pado_options['voting'] == 1) :
            
                if(is_user_logged_in())
                    $vote_count = (array) get_user_meta(get_current_user_id(), 'vote_count', true);
                else
                    $vote_count = $cookie_vote_count;
                
                if (!in_array( $post->ID, $vote_count )) :
                        echo '<h3>' . __('Was this article helpful to you?', 'pressapps') . '</h3>';
                        echo '<p><a class="pado-like-btn" href="javascript:" post_id="'  . $post->ID . '">' . $like_icon .'</a>';
                        echo '<a class="pado-dislike-btn" href="javascript:" post_id="' . $post->ID . '">' . $dislike_icon . '</a></p>';

                else :
                        // already voted
                        echo '<h3>' . __('Thank you for your feedback!', 'pressapps') . '</h3>';
                        //echo '<p data-toggle="tooltip" title="' . $voted_like . '" class="pado-likes">' . $like_icon .'<span class="count">' . $votes_like . '</span></p> ';
                        //echo '<p data-toggle="tooltip" title="' . $voted_dislike . '" class="pado-dislikes">' . $dislike_icon . '<span class="count">' . $votes_dislike . '</span></p> ';
                endif;
        
        else :
                // not logged in
                echo '<h3>' . __('Was this article helpful to you?', 'pressapps') . '</h3>';
                echo '<p data-toggle="tooltip" title="' . $voted_like . '" class="pado-likes">' . $like_icon .'<span class="count">' . $votes_like . '</span></p> ';
                echo '<p data-toggle="tooltip" title="' . $voted_dislike . '" class="pado-dislikes">' . $dislike_icon . '<span class="count">' . $votes_dislike . '</span></p> ';
        endif;
        
        echo (($is_ajax)?'':'</div>');

}

function pado_docs_vote() {
    global $post;
    global $pado_options;    

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
                        pado_docs_votes(true);
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
                        pado_docs_votes(true);
                        die('');
                        
                endif;
                
        endif;

    } elseif (!is_user_logged_in() && $pado_options['voting'] == 1) {

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
                        pado_docs_votes(true);
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
                        pado_docs_votes(true);
                        die('');
                        
                endif;
                
        endif;

    } elseif (!is_user_logged_in() && $pado_options['voting'] == 2) {

        return;
        
    }
        
}

add_action('init', 'pado_docs_vote');

add_action('wp_head','pado_common_js');

function pado_common_js(){
    $pado = array(
        'base_url'  => esc_url(home_url()),
    );
    ?>
<script type="text/javascript">
    PADO = <?php echo json_encode($pado); ?>;
</script>
    <?php
}


