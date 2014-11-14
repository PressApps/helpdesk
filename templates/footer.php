<?php global $helpdesk; ?>
<footer class="content-info" role="contentinfo">
  <?php if ( is_active_sidebar('sidebar-footer') ) { ?>
    <div class="sidebar-footer">
      <div class="container">
        <div class="row">
          <?php dynamic_sidebar('sidebar-footer'); ?>
        </div>
      </div>
    </div>
  <?php } ?>
  <div class="footer-bottom">
    <div class="container">
      <div class="row">
    		<div class="col-md-6 copyright"><?php echo $helpdesk['footer_text']; ?></div>
        <div class="col-md-6">
          <?php
          if (has_nav_menu('footer_navigation')) :
            wp_nav_menu(array('theme_location' => 'footer_navigation', 'menu_class' => 'navbar-footer', 'depth' => 1));
          endif;
          ?>
          <?php
          $networks = get_social_links();
          $social = $helpdesk['footer_social'];
          if ( $social && ! is_null( $networks ) && count( $networks ) > 0 ) {
            echo '<div class="footer-social">';

            foreach ( $networks as $network ) {
              // Check if the social network URL has been defined
              if ( isset( $network['url'] ) && ! empty( $network['url'] ) && strlen( $network['url'] ) > 7 ) {
                echo '<a href="' . $network['url'] . '" title="' . $network['fullname'] . '"><i class="si-' . $network['icon'] . '"></i></a>';
              }
            }

            echo '</div>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</footer>

