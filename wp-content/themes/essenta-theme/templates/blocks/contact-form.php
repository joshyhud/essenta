<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$contactTitle = get_sub_field('contact_heading');
$contactDescription = get_sub_field('contact_text');
$contactForm = get_sub_field('contact_form');
$defaultOpeningHours = get_field('opening_hours', 'option');
$contactOffices = new WP_Query(array(
  'post_type' => 'office',
  'post_status' => 'publish',
  'posts_per_page' => 2,
  'orderby' => 'title',
  'order' => 'ASC',
  'meta_query' => array(
    array(
      'key' => 'office_or_delivery_partner',
      'value' => 'office',
    ),
  ),
));
?>

<section class="contact-form">
  <div class="container">
    <div class="contact-form__layout">
      <div class="contact-form__main">
        <div class="contact-form__intro">
          <?php if ($contactTitle) : ?>
            <h4><?php echo esc_html($contactTitle); ?></h4>
          <?php endif; ?>
          <?php echo wp_kses_post($contactDescription); ?>
        </div>

        <div class="contact-form__form">
          <?php echo do_shortcode($contactForm); ?>
        </div>
      </div>

      <?php if ($contactOffices->have_posts()) : ?>
        <aside class="contact-form__offices" aria-label="<?php esc_attr_e('Essenta offices', 'essenta-theme'); ?>">
          <?php while ($contactOffices->have_posts()) : $contactOffices->the_post(); ?>
            <?php
            $officeId = get_the_ID();
            $officeLocation = get_field('office_location', $officeId);
            $officeAddress = is_array($officeLocation) ? ($officeLocation['address'] ?? '') : '';
            $officeCountry = is_array($officeLocation) ? ($officeLocation['country'] ?? '') : '';
            $officeCountry = str_replace(array('United Kingdom', 'United States'), array('UK', 'USA'), $officeCountry);
            $officePhone = get_field('office_phone', $officeId);
            $officeEmail = get_field('office_email', $officeId);
            $officeOpeningHours = get_field('office_opening_hours', $officeId) ?: $defaultOpeningHours;
            ?>
            <article class="contact-form__office-card">
              <h5>
                <?php the_title(); ?><?php echo $officeCountry ? ', ' . esc_html($officeCountry) : ''; ?>
              </h5>

              <div class="contact-form__office-details">
                <?php if ($officeAddress) : ?>
                  <div class="contact-form__office-row">
                    <div class="contact-form__icon">
                      <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/pin.svg'); ?>" alt="">
                    </div>
                    <address><a href="<?php echo esc_url('https://www.google.com/maps/search/?api=1&query=' . rawurlencode($officeAddress)); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($officeAddress); ?></a></address>
                  </div>
                <?php endif; ?>

                <?php if ($officePhone) : ?>
                  <div class="contact-form__office-row">
                    <div class="contact-form__icon">
                      <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/phone-dark.svg'); ?>" alt="">
                    </div>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $officePhone)); ?>"><?php echo esc_html($officePhone); ?></a>
                  </div>
                <?php endif; ?>

                <?php if ($officeEmail) : ?>
                  <div class="contact-form__office-row">
                    <div class="contact-form__icon">
                      <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/mail-dark.svg'); ?>" alt="">
                    </div>
                    <a href="mailto:<?php echo esc_attr(antispambot($officeEmail)); ?>"><?php echo esc_html(antispambot($officeEmail)); ?></a>
                  </div>
                <?php endif; ?>

                <?php if ($officeOpeningHours) : ?>
                  <div class="contact-form__office-row contact-form__office-row--hours">
                    <div class="contact-form__icon">
                      <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/clock-dark.svg'); ?>" alt="">
                    </div>
                    <?php echo wp_kses_post($officeOpeningHours); ?>
                  </div>
                <?php endif; ?>
              </div>
            </article>
          <?php endwhile; ?>
        </aside>
        <?php wp_reset_postdata(); ?>
      <?php endif; ?>
    </div>
  </div>
</section>