<?php

// Minimal Header Template
if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$minimal_title = get_sub_field('minimal_page_title');
$minimal_subtitle = get_sub_field('minimal_page_description');
$minimal_content = get_sub_field('minimal_content');

$minimal_cta = get_sub_field('minimal_cta');
?>

<section class="minimal-header">
  <div class="container minimal-header-content">
    <h1 class="subheading"><?php echo esc_html($minimal_title); ?></h1>
    <h2><?php echo esc_html($minimal_subtitle); ?></h2>
    <div class="minimal-content"><?php echo $minimal_content; ?></div>
    <?php if ($minimal_cta) : ?>
      <a class="btn primary" href="<?php echo esc_url($minimal_cta['url']); ?>">
        <?php echo esc_html($minimal_cta['title']); ?>
      </a>
    <?php endif; ?>
  </div>
</section>