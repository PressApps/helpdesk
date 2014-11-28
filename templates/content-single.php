<?php global $helpdesk; ?>
<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php echo pa_post_format_icon(); ?><?php the_title(); ?><?php if ($helpdesk['print']) { ?> <a href="javascript:print();" class="icon icon-Printer" title="<?php _e('Print this Article', 'roots'); ?>"></a><?php } ?></h1>
    </header>
    <div class="entry-content<?php echo pa_style_tag($post->ID); ?>">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php get_template_part('templates/entry-meta'); ?>
    </footer>

    <?php
    $layout = $helpdesk['single_modules']['Enabled'];

    if ($layout): foreach ($layout as $key=>$value) {
     
        switch($key) {
     
            case 'voting': get_template_part( 'templates/module', 'voting' );
            break;
     
            case 'related': get_template_part( 'templates/module', 'related' );
            break;
     
            case 'comments': comments_template( '/templates/comments.php' );
            break;

            case 'cta': get_template_part( 'templates/module', 'cta' );    
            break;  
     
        }
     
    }
     
    endif;
    ?>
  </article>
<?php endwhile; ?>
