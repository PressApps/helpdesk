<?php global $helpdesk; ?>
<div class="section">
	<div class="row">
		<div class="sidebar-home">
			<?php
			/*
			$home_sidebar = redux_post_meta( 'helpdesk', $post->ID, 'home_sidebar' );
			if ($home_sidebar && $home_sidebar != '') {
				dynamic_sidebar($home_sidebar);
			} 
			*/
			dynamic_sidebar('sidebar-home');
			?>
		</div>
	</div>
</div>