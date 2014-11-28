<?php get_template_part('templates/page', 'header'); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'roots'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php
global $helpdesk;
if (isset($helpdesk['kb_columns_category']) && $helpdesk['kb_columns_category'] != '') {
    $kb_columns = $helpdesk['kb_columns_category'];
    if ($kb_columns == 2) {
        $col_class = 6;
    } elseif ($kb_columns == 4) {
        $col_class = 3;
    }
} 

$kb_columns = 3;
$col_class = 4;
$i    = 0;

/**
 * Subcategories
 */
$sub_category_id = get_query_var('cat');

$subcat_args = array(
  //'orderby' => 'name',
  //'order' => 'ASC',
  'child_of' => $sub_category_id,
  'pad_counts'  => 1
);
$sub_categories = get_categories($subcat_args); 
$sub_categories = wp_list_filter($sub_categories,array('parent'=>$sub_category_id));
?>

<?php if ($sub_categories) { ?>
  <div class="category-sub">
  
    <?php foreach($sub_categories as $sub_category) {  ?>
      <?php if($i++%$kb_columns==0){ ?>
        <div class="row kb-row">
      <?php } ?>
      <div class="col-sm-<?php echo $col_class; ?>">
        <h4><a href="<?php echo get_category_link( $sub_category->term_id ) ?>"><?php echo pa_category_icon_url($sub_category->term_id, TRUE); ?><?php echo $sub_category->name ?></a></h4>
          <ul>
            <?php 
            $cat_posts = get_posts(array(
                'numberposts'   => -1,
                'cat'  => $sub_category->term_id,
            ));

            $j            = 1;
            $cat_post_num = 10; 
            foreach($cat_posts as $post){
              setup_postdata($post);
              ?>
              <li><a href="<?php the_permalink(); ?>"><?php echo pa_post_format_icon(); ?><?php the_title(); ?></a></li>
              <?php
              if($j++==$cat_post_num)
                  break;
            }
            ?>
          </ul>
          <a class="view-all" href="<?php echo get_category_link( $sub_category->term_id ) ?>" ><?php echo pa_view_all_icon(); ?><?php _e('View all', 'pressapps'); ?> <?php echo $sub_category->count;  ?> <?php _e('articles', 'pressapps'); ?></a>
        </div>

      <?php if($i%$kb_columns==0){ ?>
        </div>
      <?php } ?>

    <?php } ?>

    <?php if($i%$kb_columns!=0) { ?>
      </div>
    <?php } ?>

  </div> 
  
  <?php
  $current_cat = intval( get_query_var('cat') );
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $args=array(
    'category__in' => array($current_cat),
    'paged' => $paged,
    //'post_type' => 'post',
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

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('templates/content', get_post_format()); ?>
  <?php endwhile; ?>

  <?php if ($wp_query->max_num_pages > 1) : ?>
    <?php page_navi(); ?>
  <?php endif; ?>

<?php } ?>  
  

