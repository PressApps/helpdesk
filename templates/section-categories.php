<?php

global $post, $helpdesk, $meta;
$meta = redux_post_meta( 'helpdesk', get_the_ID() );

$title = $meta['section_categories_title'];
if (isset($meta['section_categories']) && $meta['section_categories'] != '') {
    $section_categories = implode(",", $meta['section_categories']);
}
if (isset($meta['section_columns']) && $meta['section_columns'] != '') {
    $section_columns = $meta['section_columns'];
    if ($section_columns == 2) {
        $col_class = 6;
    } elseif ($section_columns == 4) {
        $col_class = 3;
    } elseif ($section_columns == 6) {
        $col_class = 2;
    }
} 
$section_categories = 'list';
$section_columns = 3;
$col_class = 4;
$i    = 0;

$categories = get_categories(array(
    'orderby'         => 'slug',
    'order'           => 'ASC',
    'include'         => $section_categories,
    'pad_counts'  => 1,
)); 

$categories = wp_list_filter($categories,array('parent'=>0));
?>

<section class="section-categories">
    <div class="container">
        <?php
        if ($title) {
            echo '<h2 class="section-title">' . $title . '</h2>';
        }
        foreach($categories as $category) { 
            
            $term_id        = array();
            $term_id[]      = $category->term_id;

            if($i++%$section_columns==0){
                ?>
                <div class="row half-gutter-row box-row">
                <?php
            }
            ?>
            <div class="col-sm-<?php echo $col_class; ?> half-gutter-col">
        	    <a href="<?php echo get_category_link($category->term_id); ?>" title="<?php echo $category->name; ?>" class="box">
        	    	<?php echo pa_category_icon_url($category->term_id, TRUE); ?>
        	        <h3><?php echo $category->name; ?></h3>
        	        <p><?php echo $category->description; ?></p>
        	        <p><?php _e('View all', 'pressapps'); ?> <?php echo $category->count;  ?> <?php _e('articles', 'pressapps'); ?></p>
        	    </a>
        	</div>
            <?php		
            
            if($i%$section_columns==0){
                ?>
                </div>
                <?php
            }
           
        }
        if($i%$section_columns!=0){
                echo "</div>";
            }
        wp_reset_query();

        ?>
    </div>
</section>