<?php global $helpdesk; ?>
<section class="section-sidebar">
	<div class="container">
		<div class="row half-gutter-row">
			<?php 
			//$home_sidebar = redux_post_meta( 'helpdesk', $post->ID, 'home_sidebar' );
			//if ($home_sidebar && $home_sidebar != '') {
			//	dynamic_sidebar($home_sidebar);
			//} 
			dynamic_sidebar('sidebar-home');
			?>
		</div>
	</div>
</section>