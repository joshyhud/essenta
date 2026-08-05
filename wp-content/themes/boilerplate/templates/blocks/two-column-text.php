<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$title = get_sub_field('section_title');
$leftContent = get_sub_field('left_section_text');
$rightContent = get_sub_field('right_text_section');
$rightCTA = get_sub_field('right_cta');
$leftCTA = get_sub_field('left_cta');
?>

<section class="two-column-text">
  <div class="container">
    <h2 class="heading"><?php echo $title; ?></h2>
    <div class="content-section">
      <div class="content-half">
        <?php echo $leftContent; ?>
        <?php if ($leftCTA) : ?>
          <a href="<?php echo $leftCTA['url']; ?>" class="btn tertiary-outlined"><?php echo $leftCTA['title']; ?></a>
        <?php endif; ?>
      </div>
      <div class="content-half">
        <?php echo $rightContent; ?>
        <?php if ($rightCTA) : ?>
          <a href="<?php echo $rightCTA['url']; ?>" class="btn tertiary-filled"><?php echo $rightCTA['title']; ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>