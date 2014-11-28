<?php
/**
 * "I want to..." actions taxonomy
 */
add_action( 'init', 'create_actions_taxonomy', 0 );

function create_actions_taxonomy() {
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
    'menu_name'         => __( 'Actions' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'actions' ),
  );

  register_taxonomy( 'actions', array( 'post' ), $args );
}