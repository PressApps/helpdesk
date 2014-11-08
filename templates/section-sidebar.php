<?php global $helpdesk; ?>
<div class="section">
	<div class="">
		<div class="pre-footer">
			<?php
			$home_sidebar = redux_post_meta( 'helpdesk', $post->ID, 'home_sidebar' );
			if ($home_sidebar && $home_sidebar != '') {
				dynamic_sidebar($home_sidebar);
			} 
			?>
		</div>
	</div>
</div>