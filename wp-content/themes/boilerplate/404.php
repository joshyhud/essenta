<?php get_header(); ?>
<section class="fourZerofour">
	<div class="small-container">
		<div class="fourContent">
			<h1 class="coastal-title">Page not found</h1>
		</div>
		<div class="fourImage">
			<img loading="lazy" src="<?php echo get_field('404_image', 'option')['url']; ?>" alt="<?php echo get_field('404_image', 'option')['alt']; ?>" class="img-responsive" />
		</div>
		<div class="fourCta">
			<h4 class="coastal-subtitle">Don’t worry, we’ll get you where you need to be</h4>
			<a href="/" class="btn primary cta">Take me back to the homepage</a>
		</div>
</section>
<?php get_footer(); ?>