<?php global $helpdesk; ?>
<footer class="content-info" role="contentinfo">
  <div class="container">
    <div class="footer-bottom">
      <div class="row">
    		<div class="col-md-6 copyright pull-left"><?php echo $helpdesk['footer_text']; ?></div>
  		<?php
  		if (has_nav_menu('footer_navigation')) :
  		  wp_nav_menu(array('theme_location' => 'footer_navigation', 'menu_class' => 'col-md-6 navbar-footer', 'depth' => 1));
  		endif;
  		?>
      </div>
    </div>
    <div class="row">
      <?php //dynamic_sidebar('sidebar-footer'); ?>
    </div>
  </div>
</footer>
