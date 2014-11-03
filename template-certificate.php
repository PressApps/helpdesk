<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
<?php wp_head(); ?>
</head>
<body <?php body_class('single-certificate'); ?>>
<?php $ID=PA_Core::getRewriteRule('certificate'); ?>
<?php $certificate=PA_Course::getCertificate(pa_decode($ID), pa_decode($ID, true)); ?>
<?php if(isset($certificate['user'])) { ?>
	<div class="certificate-wrap">
		<div class="certificate-text">
			<?php echo $certificate['content']; ?>		
		</div>
	</div>
	<?php if($certificate['user']==get_current_user_id()) { ?>
	<a href="#" class="print-button"><?php _e('Print Certificate', 'pressapps'); ?></a>
	<?php } ?>
<?php } else { ?>
<div class="certificate-error">
	<h1><?php _e('Certificate not found', 'pressapps'); ?>.</h1>
</div>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>