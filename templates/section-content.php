
<?php while (have_posts()) : the_post(); ?>
	<section>
  		<?php get_template_part('templates/content', 'page'); ?>
	</section>
<?php endwhile; ?>
