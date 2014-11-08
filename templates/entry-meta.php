<?php global $helpdesk; ?>
<div class="meta">
	<?php if ($helpdesk['article_meta']['1']) { ?>
		<time class="updated" datetime="<?php echo get_the_time('c'); ?>"><?php echo __('Last Updated:', 'roots'); ?> <?php echo get_the_date(); ?></time>
	<?php } ?>
	<?php if ($helpdesk['article_meta']['2']) { ?>
		<p class="byline author vcard"><?php echo __('By', 'roots'); ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a></p>
	<?php } ?>
	<?php if ($helpdesk['article_meta']['3']) { ?>
		<?php echo get_the_category_list(', '); ?>
	<?php } ?>
</div>
<?php if ($helpdesk['article_meta']['4'] && the_tags()) { ?>
	<p class="tags"><?php the_tags('<span class="icon-Tag"></span> ',' ',''); ?></p>
<?php } ?>	

