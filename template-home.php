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
 
        case 'hero': get_template_part( 'templates/section', 'hero' );
        break;
 
        case 'boxes': get_template_part( 'templates/section', 'boxes' );
        break;

        case 'actions': get_template_part( 'templates/section', 'actions' );
        break;
 
        case 'categories': get_template_part( 'templates/section', 'categories' );
        break;
 
        case 'content': get_template_part( 'templates/section', 'content' );
        break;
 
        case 'sidebar': get_template_part( 'templates/section', 'sidebar' );
        break;

        case 'cta': get_template_part( 'templates/section', 'cta' );    
        break;  
 
    }
 
}
 
endif;




