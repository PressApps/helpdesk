<?php

global $post, $helpdesk, $meta;
$meta = redux_post_meta( 'helpdesk', get_the_ID() );


$title = $meta['section_actions_title'];
$section_actions_include = 'list';
$section_actions_columns = 3;
$col_class = 4;
$i    = 0;

if (isset($meta['section_actions_include']) && $meta['section_actions_include'] != '') {
    $section_actions_include = implode(",", $meta['section_actions_include']);
}
if (isset($meta['section_actions_columns']) && $meta['section_actions_columns'] != '') {
    $section_actions_columns = $meta['section_actions_columns'];
    if ($section_actions_columns == 2) {
        $col_class = 6;
    } elseif ($section_actions_columns == 4) {
        $col_class = 3;
    } elseif ($section_actions_columns == 6) {
        $col_class = 2;
    }
} 

$actions = get_categories(array(
    //'orderby'         => 'slug',
    //'order'           => 'ASC',
    'taxonomy'        => 'actions',
    'include'         => $section_actions_include,
    'pad_counts'      => 1,
)); 

$actions = wp_list_filter($actions,array('parent'=>0));

?>

<section class="section-actions">
	<div class="container">
        <?php
        if ($title) {
            echo '<h2 class="section-title">' . $title . '</h2>';
        }
        foreach($actions as $action) { 
            
            $term_id        = array();
            $term_id[]      = $action->term_id;

            if($i++%$section_actions_columns==0){
                ?>
                <div class="row half-gutter-row box-row">
                <?php
            }
            ?>
            <div class="col-sm-<?php echo $col_class; ?> half-gutter-col">
        	    <a href="<?php echo get_term_link($action); ?>" title="<?php echo $action->name; ?>" class="box">
        	    	<?php echo pa_category_icon_url($action->term_id, TRUE); ?>
        	        <h3><?php echo $action->name; ?></h3>
        	        <p><?php echo $action->description; ?></p>
        	        <p><?php _e('View all', 'pressapps'); ?> <?php echo $action->count;  ?> <?php _e('articles', 'pressapps'); ?></p>
        	    </a>
        	</div>
            <?php		
            
            if($i%$section_actions_columns==0){
                ?>
                </div>
                <?php
            }
           
        }
        if($i%$section_actions_columns!=0){
                echo "</div>";
            }
        wp_reset_query();

        ?>
    </div>
</section>