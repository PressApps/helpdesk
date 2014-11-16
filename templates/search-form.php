<?php global $helpdesk; ?>
<?php if ( $helpdesk['headline_search'] == 2 ) { ?>
	<form class="navbar-form search-main" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
	  <div class="search-form-group">
	    <span class="icon-Magnifi-Glass2"></span>
	    <input type="search" value="<?php echo get_search_query(); ?>" id="autocomplete-ajax" class="searchajax search-query form-control" autocomplete="off" placeholder="<?php _e('Search Knowledge Base Articles', 'roots'); ?>" name="s" id="s">
	  </div>
	</form>
	<script> _url = '<?php echo home_url(); ?>';</script>
<?php } else { ?>
	<form class="navbar-form search-main" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
	  <div class="search-form-group">
	    <span class="icon-Magnifi-Glass2"></span>
	    <input type="search" value="<?php echo get_search_query(); ?>" class="form-control" placeholder="<?php _e('Search Knowledge Base Articles', 'roots'); ?>" name="s" id="autocomplete-ajax">
	  </div>
	</form>
<?php } ?>