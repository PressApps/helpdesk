<?php global $helpdesk; ?>
<div class="headline headline-default">
  <div class="container">
    <div class="row">
      <div class="col-md-7 hidden-xs hidden-sm">
        <?php pa_breadcrumbs(); ?>
      </div>
      <div class="col-md-5">
        <?php if ($helpdesk['headline_search']) { ?>
          <?php get_template_part('templates/search', 'form'); ?>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
