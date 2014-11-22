<?php
global $post, $helpdesk;
$related_articles = $helpdesk['related_articles'];
$orig_post = $post;
$related = '';

if ($related_articles == 1) {

  $related = wp_get_post_tags($post->ID);
  if ($related) {
    $tag_ids = array();
    foreach($related as $individual_tag) $tag_ids[] = $individual_tag->term_id;
    $args=array(
      'tag__in' => $tag_ids,
      'post__not_in' => array($post->ID),
      'posts_per_page'=> 2,
      'ignore_sticky_posts'=>1
    );
  }

} elseif ($related_articles == 2) {

  $related = get_the_category($post->ID);
  if ($related) {
    $category_ids = array();
    foreach($related as $individual_category) $category_ids[] = $individual_category->term_id;
    $args=array(
      'category__in' => $category_ids,
      'post__not_in' => array($post->ID),
      'posts_per_page'=> 2,
      'ignore_sticky_posts'=>1
    );
  }
}

if ($related) {
  $related_query = new wp_query( $args );
  if( $related_query->have_posts() ) {
    echo '<div class="related module">';
    echo '<h2>' . __('Related Articles', 'pressapps') . '</h2>';
    echo '<div class="row">';
    while( $related_query->have_posts() ) {
      $related_query->the_post();
      get_template_part('templates/content', 'related');
    }
    echo '</div>';
    echo '</div>';
  }
}

$post = $orig_post;
wp_reset_query();


