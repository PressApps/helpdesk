<?php
/**
 * Article voting
 */
global $helpdesk;

$voting_enabled = FALSE;
if (array_key_exists('voting' ,$helpdesk['single_modules']['Enabled'])) {
 $voting_enabled = TRUE;
}

if ($voting_enabled) {
  add_action('init', 'pa_article_vote');
  add_action('wp_head','pa_vote_js');
}
function pa_article_voting($is_ajax = FALSE) {
  global $helpdesk, $post;        

  $cookie_vote_count      = '';
  $like_icon = '<span class="icon icon-Yes"></span> ';
  $dislike_icon = '<span class="icon icon-Close"></span> ';

  if(isset($_COOKIE['vote_count'])){
      $cookie_vote_count = @unserialize(base64_decode($_COOKIE['vote_count']));
  }
  
  if(!is_array($cookie_vote_count) && isset($cookie_vote_count)){
      $cookie_vote_count = array();
  }
 
  echo (($is_ajax)?'':'<div class="vote module">');
                          
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
    global $post, $helpdesk;

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
