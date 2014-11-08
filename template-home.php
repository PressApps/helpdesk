<?php
/*
Template Name: Home
*/
?>

<?php

$meta = redux_post_meta( 'helpdesk', $post->ID );
$layout = $meta['home_sections']['Enabled'];

if ($layout): foreach ($layout as $key=>$value) {
 
    switch($key) {
 
        case 'header': get_template_part( 'templates/section', 'header' );
        break;
 
        case 'categories': get_template_part( 'templates/section', 'categories' );
        break;
 
        case 'content': get_template_part( 'templates/section', 'content' );
        break;
 
        case 'sidebar': get_template_part( 'templates/section', 'sidebar' );
        break;

        case 'contact': get_template_part( 'templates/section', 'contact' );    
        break;  
 
    }
 
}
 
endif;




