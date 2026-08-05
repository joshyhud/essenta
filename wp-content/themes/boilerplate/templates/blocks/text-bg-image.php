<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$backgroundImage = get_sub_field('background_image');
$heading = get_sub_field('card_title');
$text = get_sub_field('card_text');
$button = get_sub_field('cta_link');
?>

<section class="text-background-image" style="background-image: url('<?php echo $backgroundImage['url']; ?>')">
  <div class="container">
    <div class="text-bg-image-card">
      <h2 class="heading"><?php echo $heading; ?></h2>
      <?php echo $text; ?>
      <?php if ($button): ?>
        <a href="<?php echo $button['url']; ?>" class="btn tertiary-filled"><?php echo $button['title']; ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>