<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

$post_id = get_the_ID();
$author_id = (int) get_the_author_meta('ID');
$featured_image_url = get_the_post_thumbnail_url($post_id, 'largest');
$featured_image_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true);

if (!$featured_image_alt) {
	$featured_image_alt = get_the_title($post_id);
}
?>

<section class="blog-header<?php echo $featured_image_url ? '' : ' no-image'; ?>">
	<?php if ($featured_image_url) : ?>
		<div class="image-media">
			<div class="overlay"></div>
			<img loading="lazy" src="<?php echo esc_url($featured_image_url); ?>" alt="<?php echo esc_attr($featured_image_alt); ?>">
		</div>
	<?php endif; ?>

	<div class="container blog-header-content">
		<div class="blog-header-inner">
			<h1><?php the_title(); ?></h1>

			<div class="blog-header-meta">
				<div class="author-avatar">
					<?php echo get_avatar($author_id, 48); ?>
					<span class="author-name"><?php echo esc_html(get_the_author()); ?></span>
				</div>

				<time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
					<?php echo esc_html(get_the_date('M j Y')); ?>
				</time>
			</div>
		</div>
	</div>
</section>