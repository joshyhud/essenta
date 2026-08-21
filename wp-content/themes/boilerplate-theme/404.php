<?php get_header();


$errorHeading = get_field('404_message', 'option');
$errorImage = get_field('404_image', 'option');

?>
<section class="fourZerofour" style="background-image: url('<?php echo esc_url($errorImage['url']); ?>');">
	<div class="overlay"></div>

	<div class="container">
		<div class="container--inner">
			<h1 class="heading">404</h1>
			<div class="error-message">
				<h2 class="content"><?php echo esc_html($errorHeading); ?></h2>
				<a href="/" class="btn primary dark">Back to homepage</a>
			</div>
		</div>
	</div>

</section>
<?php get_footer(); ?>