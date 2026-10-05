<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$text_or_image = get_sub_field('text_or_image');

$left_right = get_sub_field('media_position');

$background_colour = get_sub_field('background_colour');
$background_value = sanitize_title($background_colour['label']);


$image = get_sub_field('media_image');

$media_sub_header = get_sub_field('media_sub_header');
$media_heading = get_sub_field('media_heading');
$media_content = get_sub_field('media_content');

$media_cta = get_sub_field('media_cta');
$media_cta_secondary = get_sub_field('media_cta_secondary');

$media_text_content = get_sub_field('media_text_content');

?>

<section class="media-text-block <?php echo esc_attr($background_value); ?>">
  <div class="media-text-content container <?php echo esc_attr($left_right); ?> <?php echo esc_attr($text_or_image); ?>">
    <?php if ($text_or_image === 'text'): ?>
      <div class="media-text">
        <?php echo $media_text_content; ?>
      </div>
    <?php else: ?>
      <div class="media-image">
        <?php if (!empty($image)): ?>
          <img loading="lazy" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <div class="media-text">
      <div class="media-text-inner">
        <p class="eyebrow"><?php echo esc_html($media_sub_header); ?></p>
        <h2><?php echo esc_html($media_heading); ?></h2>
        <?php echo wp_kses_post($media_content); ?>
        <div class="media-ctas">
          <?php if ($media_cta): ?>
            <?php if ($background_value != 'navy-blue') { ?>
              <a href="<?php echo esc_url($media_cta['url']); ?>" class="btn primary"><?php echo esc_html($media_cta['title']); ?></a>
            <?php } else { ?>
              <a href="<?php echo esc_url($media_cta['url']); ?>" class="btn primary--light"><?php echo esc_html($media_cta['title']); ?></a>
            <?php } ?>
          <?php endif; ?>
          <?php if ($media_cta_secondary): ?>
            <?php if ($background_value != 'navy-blue') { ?>
              <a href="<?php echo esc_url($media_cta_secondary['url']); ?>" class="btn secondary"><?php echo esc_html($media_cta_secondary['title']); ?></a>
            <?php } else { ?>
              <a href="<?php echo esc_url($media_cta_secondary['url']); ?>" class="btn secondary--light"><?php echo esc_html($media_cta_secondary['title']); ?></a>
            <?php } ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
</section>