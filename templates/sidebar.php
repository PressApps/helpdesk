<?php
global $helpdesk;

if (is_page() && redux_post_meta( 'helpdesk', $post->ID, 'sidebar' ) && redux_post_meta( 'helpdesk', $post->ID, 'sidebar' ) != '') {
	dynamic_sidebar($helpdesk['sidebar']);
} else {
	dynamic_sidebar('sidebar-primary');
}
?>
