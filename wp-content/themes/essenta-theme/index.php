<?php get_header(); ?>

<?php if (is_home() || is_archive()) : ?>
	<?php echo get_template_part('templates/archive', 'blog'); ?>
<?php else : ?>
	<main class="content">
		<?php
		$header_part = get_template_part('header-page-builder');
		?>
		<?php
		$page_part = get_template_part('page-builder');

		?>
	</main>
<?php endif; ?>
<?php get_footer(); ?>