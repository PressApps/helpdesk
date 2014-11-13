<?php global $helpdesk; ?>
<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php echo pa_post_format_icon(); ?><?php the_title(); ?><?php if ($helpdesk['print']) { ?> <a href="javascript:print();" class="icon icon-Printer" title="<?php _e('Print this Article', 'roots'); ?>"></a><?php } ?></h1>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php get_template_part('templates/entry-meta'); ?>
    </footer>
    <?php pa_article_voting(); ?>
    <?php get_template_part('templates/related'); ?>
    <?php comments_template('/templates/comments.php'); ?>
    <?php get_template_part('templates/section', 'contact'); ?>
  </article>
<?php endwhile; ?>
