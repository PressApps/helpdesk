<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php echo pa_post_format_icon(); ?> <?php the_title(); ?><a href="javascript:print();" class="icon icon-Printer" title="<?php _e('Print this Article', 'roots'); ?>"></a><a href="javascript:pdf();" class="icon icon-File-Download" title="<?php _e('Download as PDF', 'roots'); ?>"></a></h1>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
