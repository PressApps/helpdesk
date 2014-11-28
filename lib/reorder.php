<?php
/**
 * Article reorder
 */
global $pagenow, $helpdesk;

if(is_admin() && $helpdesk['reorder']) {

  if( $pagenow == 'edit.php') {
      if ( !isset($_GET['post_type'])  || 'post' == $_GET['post_type'] ) {
          add_filter( 'pre_get_posts', 'pa_order_reorder_list' );
          
      }
  } elseif( $pagenow == 'edit-tags.php' ) {
      if ( isset($_GET['taxonomy']) && ('category' == $_GET['taxonomy'] || 'actions' == $_GET['taxonomy']) ) {
          add_filter( 'get_terms_orderby', 'pa_order_reorder_taxonomies_list', 10, 2 );
      }
  } 

  add_action('wp_ajax_pa_order_update_posts', 'pa_order_save_order');
  add_action('wp_ajax_pa_order_update_taxonomies', 'pa_order_save_taxonomies_order');

}

if ($helpdesk['reorder']) {
  add_action( 'admin_enqueue_scripts', 'papc_reorder_scripts' );
}

if ( !is_admin() && $helpdesk['reorder'] ) {
  add_filter( 'get_terms_orderby', 'pa_reorder_front_end_tax' );
  add_action( 'pre_get_posts', 'pa_reorder_front_end_posts', 1 );
}

function papc_reorder_scripts() {

  global $pagenow;

  if( $pagenow == 'edit.php') {
      if ( !isset($_GET['post_type']) || 'post' == $_GET['post_type'] ) {
          wp_register_style('pressapps_order-admin-styles', get_template_directory_uri() . '/assets/css/reorder.css');
          wp_register_script('pressapps_order-update-order', get_template_directory_uri() . '/assets/js/vendor/order-posts.js');
          wp_enqueue_script('jquery-ui-sortable');
          wp_enqueue_script('pressapps_order-update-order');
          wp_enqueue_style('pressapps_order-admin-styles');         
      }
  } elseif( $pagenow == 'edit-tags.php' ) {
      if ( isset($_GET['taxonomy']) && ('category' == $_GET['taxonomy'] || 'actions' == $_GET['taxonomy']) ) {
          wp_register_style('pressapps_order-admin-styles', get_template_directory_uri() . '/assets/css/reorder.css');
          wp_register_script('pressapps_order-update-order', get_template_directory_uri() . '/assets/js/vendor/order-taxonomies.js');
          wp_enqueue_script('jquery-ui-sortable');
          wp_enqueue_script('pressapps_order-update-order');
          wp_enqueue_style('pressapps_order-admin-styles');
      }
  } 
}

function pa_order_reorder_taxonomies_list($orderby, $args) {
    $orderby = "t.term_group";
    return $orderby;
}

function pa_order_reorder_list($query) {
    $query->set('orderby', 'menu_order');
    $query->set('order', 'ASC');
    return $query;
}

function pa_order_save_order() {
    
    global $wpdb;
    
    $action             = $_POST['action']; 
    $posts_array        = $_POST['post'];
    $listing_counter    = 1;
    
    foreach ($posts_array as $post_id) {
        
        $wpdb->update( 
                    $wpdb->posts, 
                        array('menu_order'  => $listing_counter), 
                        array('ID'          => $post_id) 
                    );

        $listing_counter++;
    }
    
    die();
}

function pa_order_save_taxonomies_order() {
    global $wpdb;
    
    $action             = $_POST['action']; 
    $tags_array         = $_POST['tag'];
    $listing_counter    = 1;
    
    foreach ($tags_array as $tag_id) {
        
      $wpdb->update( 
        $wpdb->terms, 
            array('term_group'  => $listing_counter), 
            array('term_id'     => $tag_id) 
        );

      $listing_counter++;
    }
    
    die();
}

/* front end */
function pa_reorder_front_end_posts( $query ) {
    if ( is_admin() /* || !$query->is_main_query() */ )
        return;

    //if ( !isset($query->query_vars['post_type']) ) {
        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );       
        return;
    //}

}

function pa_reorder_front_end_tax($orderby) {

  $orderby = "t.term_group";
  
  return $orderby; 

}


