<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$contact_left_image = get_sub_field('left_side_image');
$company_email = get_field('company_email', 'option');
$company_phone = get_field('company_phone', 'option');
$company_address = get_field('company_address', 'option');
$company_office_hours = get_field('office_hours', 'option');

$contactForm = get_sub_field('contact_form');
?>

<section class="contact-form">
  <div class="full-cta-subheader container">
    <div class="sub-header-wrapper dark">
      <div class="sub-header">Our Details</div>
      <div class="header-divider"></div>
      <i class="header-icon"></i>
    </div>
  </div>

  <div class="container">
    <div class="form-section">
      <div class="contact-half" style="background-image: url('<?php echo esc_url($contact_left_image['url']); ?>');">
        <div class="contact-overlay"></div>
        <div class="contact--inner">
          <div class="contact-item email">
            <span>Email</span>
            <a href="mailto:<?php echo esc_attr($company_email); ?>"><?php echo esc_html($company_email); ?></a>
          </div>
          <div class="contact-item phone">
            <span>Phone</span>
            <a href="tel:<?php echo esc_attr($company_phone); ?>"><?php echo esc_html($company_phone); ?></a>
          </div>
          <div class="contact-item address">
            <span>Address</span>
            <p><?php echo esc_html($company_address); ?></p>
          </div>
          <div class="contact-item office-hours">
            <span>Office Hours</span>
            <?php echo wp_kses_post($company_office_hours); ?>
          </div>
        </div>
      </div>
      <div class="contact-half form">
        <?php echo $contactForm; ?>
      </div>
    </div>
  </div>
</section>