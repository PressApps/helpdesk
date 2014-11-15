<?php
global $post, $helpdesk, $meta;
$meta = redux_post_meta( 'helpdesk', get_the_ID() );

$title = $meta['boxes_title'];
$boxes_columns = 3;
$col_class = '4';
$i = 0;

if (isset($meta['number_of_boxes']) && $meta['number_of_boxes'] != '') {
    $boxes_columns = $meta['number_of_boxes'];
    if ($boxes_columns == 2) {
        $col_class = '6';
    } elseif ($boxes_columns == 4) {
        $col_class = '3';
    } elseif ($boxes_columns == 3) {
        $col_class = '4';
    }
} 


function get_page_boxes() {
    global $post, $helpdesk, $meta;
    $meta = redux_post_meta( 'helpdesk', get_the_ID() );

    $boxes   = array();
    $boxes[] = array( 'icon' => $meta['box_1_icon'], 'title' => $meta['box_1_title'], 'text' => $meta['box_1_text'], 'url' => $meta['box_1_url'] );
    $boxes[] = array( 'icon' => $meta['box_2_icon'], 'title' => $meta['box_2_title'], 'text' => $meta['box_2_text'], 'url' => $meta['box_2_url'] );
    $boxes[] = array( 'icon' => $meta['box_3_icon'], 'title' => $meta['box_3_title'], 'text' => $meta['box_3_text'], 'url' => $meta['box_3_url'] );
    $boxes[] = array( 'icon' => $meta['box_4_icon'], 'title' => $meta['box_4_title'], 'text' => $meta['box_4_text'], 'url' => $meta['box_4_url'] );
    return $boxes;
}

$boxes = get_page_boxes();
?>

<section class="section-boxes">
    <div class="container">
        
        <?php
        if ($title) {
            echo '<h2 class="section-title">' . $title . '</h2>';
        }

        echo '<div class="row half-gutter-row">';

        foreach ( $boxes as $box ) {
            if ( isset( $box['title'] ) && $box['title'] != '' ) {

                if(++$i > $boxes_columns) break;

                echo '<div class="col-sm-' . $col_class . ' half-gutter-col">';
                echo '<a href="' . $box['url'] . '" title="' . $box['title'] . '" class="box">';
                echo '<i class="' . $box['icon'] . '"></i>';
                echo '<h3>' . $box['title'] . '</h3>';
                echo '<p>' . $box['text'] . '</p>';
                echo '</a>';
                echo '</div>';
            }
        }

        echo '</div>';
        ?>

    </div>
</section>