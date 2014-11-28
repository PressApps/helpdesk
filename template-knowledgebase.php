<?php
/*
Template Name: Knowledge Base
*/
?>

<?php
global $post, $helpdesk, $meta;
$meta = redux_post_meta( 'helpdesk', get_the_ID() );

$kb_aticles_per_cat = $meta['kb_aticles_per_cat'];
$kb_categories = 'list';
if (isset($meta['kb_categories']) && $meta['kb_categories'] != '') {
    $kb_categories = implode(",", $meta['kb_categories']);
}
$kb_columns = 3;
$col_class = 4;
$i    = 0;
$row  = 0;

if (isset($meta['kb_columns']) && $meta['kb_columns'] != '') {
    $kb_columns = $meta['kb_columns'];
    if ($kb_columns == 2) {
        $col_class = 6;
    } elseif ($kb_columns == 4) {
        $col_class = 3;
    }
} 

$categories = get_categories(array(
    //'orderby'         => 'slug',
    //'order'           => 'ASC',
    'include'         => $kb_categories,
    'pad_counts'  => 1,
)); 

$categories = wp_list_filter($categories,array('parent'=>0));

foreach($categories as $category) { 
    
    
    $term_id        = array();
    $term_id[]      = $category->term_id;

    $cat_posts = get_posts(array(
        'numberposts'   => -1,
        'cat'  => $category->term_id,
    ));
    
    if(count($cat_posts)==0){
        continue;
    }

    if($i++%$kb_columns==0){
        $row++;
        ?>
        <div class="row kb-row<?php if ($row == 1) {echo ' kb-row-1';}?>">
        <?php
    }

    ?>
    <div class="col-sm-<?php echo $col_class; ?>">
        <h2>
            <a href="<?php echo get_category_link($category->term_id); ?>" title="<?php echo $category->name; ?>"><?php echo pa_category_icon_url($category->term_id, TRUE); ?><?php echo $category->name; ?>
            </a>
        </h2>
        <?php
        if(count($cat_posts)>0){
            ?>
            <ul>
            <?php 
            $j            = 1;
            $cat_post_num = $kb_aticles_per_cat; 
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
        <?php
        }
        ?>
        <a class="view-all" href="<?php echo get_category_link( $category->term_id ) ?>" ><?php echo pa_view_all_icon(); ?><?php _e('View all', 'pressapps'); ?> <?php echo $category->count;  ?> <?php _e('articles', 'pressapps'); ?></a>
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
