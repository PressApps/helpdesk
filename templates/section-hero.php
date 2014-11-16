<?php global $helpdesk; ?>
<section class="section-hero">
  <div class="container">
    <?php the_title( '<h1 class="title">', '</h1>' ); ?>
    <?php if ($helpdesk['subtitle']) { ?>
      <h4 class="subtitle"><?php echo $helpdesk['subtitle']; ?></h4>
    <?php } ?>
    <?php if ($helpdesk['headline_search']) { ?>
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <?php get_template_part('templates/search', 'form'); ?>
        </div>
      </div>
    <?php } ?>
  </div>
</section>
