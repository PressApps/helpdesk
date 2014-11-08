<?php

global $post, $helpdesk, $meta;
$meta = redux_post_meta( 'helpdesk', get_the_ID() );

//$show_3rd_level_cat = $meta['3rd_level_cat'];
$kb_aticles_per_cat = $meta['home_aticles_per_cat'];
$kb_categories = 'list';
if (isset($meta['home_categories']) && $meta['home_categories'] != '') {
    $kb_categories = implode(",", $meta['home_categories']);
}
$kb_columns = 3;
$col_class = 4;

if (isset($meta['home_columns']) && $meta['home_columns'] != '') {
    $kb_columns = $meta['home_columns'];
    if ($kb_columns == 2) {
        $col_class = 6;
    } elseif ($kb_columns == 4) {
        $col_class = 3;
    } elseif ($kb_columns == 6) {
        $col_class = 2;
    }
} 

$i    = 0;
$skip = TRUE;


$categories = get_categories(array(
    'orderby'         => 'slug',
    'order'           => 'ASC',
    //'hierarchical'    => true,
    //'parent'          => 0,
    //'hide_empty'      => false,
    'include'         => $kb_categories,
    'pad_counts'  => 1,
)); 

$categories = wp_list_filter($categories,array('parent'=>0));

?>

<section class="category-list">
	
<?php
foreach($categories as $category) { 
    
    
    $term_id        = array();
    $sub_categories = NULL;
    $subcategories  = NULL;
    $term_id[]      = $category->term_id;


    if($i++%$kb_columns==0 && $skip){
        ?>
        <div class="row half-gutter-row knowledge-base-row">
        <?php
    }
    $skip = TRUE;
    ?>
    <div class="col-sm-<?php echo $col_class; ?> half-gutter-col">
	    <a href="<?php echo get_category_link($category->term_id); ?>" title="<?php echo $category->name; ?>" class="category">
	    	<?php echo pa_category_icon_url($category->term_id, TRUE); ?>
	        <h2><?php echo $category->name; ?></h2>
	        <p class="article-desc"><?php echo $category->description; ?></p>
	        <p class="article-count"> <?php _e('View all', 'pressapps'); ?> <?php echo $category->count;  ?> <?php _e('articles', 'pressapps'); ?></p>
	    </a>
	</div>
    <?php		
    
    if($i%$kb_columns==0){
        ?>
        </div>
        <?php
    }
   
}
if($i%$kb_columns!=0){
        echo "</div>";
    }
wp_reset_query();

?>

</section>