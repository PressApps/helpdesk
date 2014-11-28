<?php
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
