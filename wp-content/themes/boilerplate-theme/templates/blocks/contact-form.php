<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$contactTitle = get_sub_field('contact_heading');
$contactDescription = get_sub_field('contact_text');
$contactForm = get_sub_field('contact_form');
?>

<section class="contact-form">
  <div class="container">
    <div class="form-section">
      <div class="contact-half">
        <h2><?php echo $contactTitle; ?></h2>
        <?php echo $contactDescription; ?>
      </div>
      <div class="contact-half form">
        <h3>Get a quote today</h3>
        <?php echo $contactForm; ?>
      </div>
    </div>
  </div>
</section>