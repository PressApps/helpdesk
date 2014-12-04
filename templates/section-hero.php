<?php
global $post, $helpdesk, $meta;
$meta = redux_post_meta( 'helpdesk', get_the_ID() );

$top_searches         = $meta['top_searches'];
$top_searches_title   = $meta['top_searches_title'];
$top_searches_period  = $meta['top_searches_period'];
$top_searches_terms   = $meta['top_searches_terms'];
?>
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
          <?php if ($top_searches) {
            echo '<p class="top-searches text-center">' . $top_searches_title . ' ';
            pa_popular_searches( $top_searches_period, $top_searches_terms );
            echo '</p>';
          }
          ?>
        </div>
      </div>
    <?php } ?>
  </div>
</section>
