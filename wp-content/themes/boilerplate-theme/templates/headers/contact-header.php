<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$contact_subheading = get_sub_field('contact_header_subheading');
$contact_heading = get_sub_field('contact_header_heading');
$contact_text = get_sub_field('contact_header_text');
$contact_header_cta = get_sub_field('contact_header_cta');

$contact_form = get_sub_field('contact_header_form');

?>
<section class="contact-hero">
  <div class="container contact-header-section">
    <div class="contact-headers">
      <h1 class="subheading"><?php echo $contact_subheading; ?></h1>
      <h2><?php echo $contact_heading; ?></h2>
    </div>
    <div class="contact-content">
      <?php echo $contact_text; ?>
    </div>
  </div>

  <div class="container contact-hero-form">
    <div class="contact-channels">
      <p>Contact</p>
      <a href="mailto:<?php echo get_field('email_enquiries', 'option'); ?>"><?php echo get_field('email_enquiries', 'option'); ?></a>
      <a href="tel:<?php echo str_replace(' ', '', get_field('company_phone', 'option')); ?>"><?php echo get_field('company_phone', 'option'); ?></a>
      <?php if ($contact_header_cta): ?>
        <div class="contact-cta">
          <p>Know what you need?</p>
          <a href="<?php echo $contact_header_cta['url']; ?>" class="btn primary"><?php echo $contact_header_cta['title']; ?></a>
        </div>
      <?php endif; ?>
    </div>
    <div class="contact-form">
      <?php echo $contact_form; ?>
    </div>
  </div>

</section>