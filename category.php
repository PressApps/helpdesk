<?php get_template_part('templates/page', 'header'); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'roots'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php
// Sub category
$sub_category_id = get_query_var('cat');

$subcat_args = array(
  'orderby' => 'name',
  'order' => 'ASC',
  'child_of' => $sub_category_id,
  'pad_counts'  => 1
);
$sub_categories = get_categories($subcat_args); 
$sub_categories = wp_list_filter($sub_categories,array('parent'=>$sub_category_id));

/**
 * Category posts
 */
if ($sub_categories) { ?>
  <ul class="sub-categories clearfix">
  <?php foreach($sub_categories as $sub_category) {  ?>
  <li><h4><a href="<?php echo get_category_link( $sub_category->term_id ) ?>"><?php echo pa_category_icon_url($sub_category->term_id, TRUE); ?> <?php echo $sub_category->name ?></a>
      <?php //if (of_get_option('st_hp_subcat_counts') == '1') {
        echo '<span class="cat-count">(' . $sub_category->count.')</span>';  
      //} 


        ?>
      </h4></li>

              <ul class="category-posts">
              <?php 

              $cat_posts = get_posts(array(
                  'numberposts'   => -1,
                  'cat'  => $sub_category->term_id,
              ));

              $j            = 1;
              $cat_post_num = 10; //$kb_aticles_per_cat; 
              foreach($cat_posts as $post){
                  setup_postdata($post);
                  ?>
                  <li><a href="<?php the_permalink(); ?>"><?php echo pa_post_format_icon(); ?> <?php the_title(); ?></a></li>
              <?php
              if($j++==$cat_post_num)
                  break;
              }
              ?>
              </ul>


  <?php } ?>
  </ul>

        <?php
      $current_cat = intval( get_query_var('cat') );
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      $args=array(
        'category__in' => array($current_cat),
        'paged' => $paged,
        'post_type' => 'post',
        'post_status' => 'publish',
        'ignore_sticky_posts'=> 1
      );
      query_posts($args);
      ?>

      <?php if ( have_posts() ) : ?>

        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('templates/content', get_post_format()); ?>
        <?php endwhile; ?>

        <?php if ($wp_query->max_num_pages > 1) : ?>
          <?php page_navi(); ?>
        <?php endif; ?>


      <?php endif; ?>




<?php } else { ?>
<!-- #/sub-cats --> 



  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('templates/content', get_post_format()); ?>
  <?php endwhile; ?>

  <?php if ($wp_query->max_num_pages > 1) : ?>
    <?php page_navi(); ?>
  <?php endif; ?>



<?php } ?>  
  

