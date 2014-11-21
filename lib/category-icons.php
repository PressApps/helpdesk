<?php
/**
 * Category icons
 */
add_action('admin_init', 'pa_init');
function pa_init() {
  $pa_taxonomies = array('category', 'actions');;
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
  global $helpdesk;

  if (!$helpdesk['icons_category']) {
    return;
  }

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

  if ($category_icon_url) {
    if ($explode_icon ) {
      $icon_css = explode('|', $category_icon_url);
      if ($icon_css[1] != 'icon-blank') {
        return '<span class="icon-wrap"><i class="icon-light ' . $icon_css[1] . '"></i></span>';
      }
    } else {
      return $category_icon_url;
    }
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
