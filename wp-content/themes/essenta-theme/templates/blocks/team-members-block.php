<?php

if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

$team_block_subheading = get_sub_field('team_block_subheading');
$team_block_heading = get_sub_field('team_block_heading');
$team_block_content = get_sub_field('team_block_content');
$team_block_primary_cta = get_sub_field('team_block_primary_cta');
$team_block_secondary_cta = get_sub_field('team_block_secondary_cta');


$team_member_location = get_sub_field('team_member_location');

$team_members = [];

if ($team_member_location) {

  // Handle ACF taxonomy field returning either a term object or term ID.
  $team_members = new WP_Query([
    'post_type'      => 'team_member',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'tax_query'      => [
      [
        'taxonomy' => 'team_location',
        'field'    => 'term_id',
        'terms'     => $team_member_location,
      ],
    ],
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
  ]);
}

if (!$team_members || !$team_members->have_posts()) {
  echo '<p>No team members selected.</p>';
  return;
}

$uid = 'team-members-' . wp_unique_id();

?>

<section
  class="team-members-block"
  id="<?php echo esc_attr($uid); ?>">
  <div class="container">
    <div class="team-members-block__content">
      <?php if ($team_block_subheading): ?>
        <p class="eyebrow"><?php echo esc_html($team_block_subheading); ?></p>
      <?php endif; ?>

      <?php if ($team_block_heading): ?>
        <h2 class="heading"><?php echo esc_html($team_block_heading); ?></h2>
      <?php endif; ?>

      <?php if ($team_block_content): ?>
        <div class="content-text">
          <?php echo wp_kses_post(wpautop($team_block_content)); ?>
        </div>
      <?php endif; ?>

      <?php if ($team_members->post_count > 1): ?>
        <div class="team-members-block__nav">
          <button type="button" class="team-members-block__prev" aria-label="<?php esc_attr_e('Previous', 'essenta-theme'); ?>"></button>
          <button type="button" class="team-members-block__next" aria-label="<?php esc_attr_e('Next', 'essenta-theme'); ?>"></button>
        </div>
      <?php endif; ?>

      <?php if ($team_block_primary_cta || $team_block_secondary_cta): ?>
        <div class="team-members-block__ctas">
          <?php if ($team_block_primary_cta): ?>
            <a href="<?php echo esc_url($team_block_primary_cta['url']); ?>" class="btn primary">
              <?php echo esc_html($team_block_primary_cta['title']); ?>
            </a>
          <?php endif; ?>

          <?php if ($team_block_secondary_cta): ?>
            <a href="<?php echo esc_url($team_block_secondary_cta['url']); ?>" class="btn secondary">
              <?php echo esc_html($team_block_secondary_cta['title']); ?>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="team-members-block__slider-wrapper">
      <div class="team-members-slider">
        <?php while ($team_members->have_posts()): $team_members->the_post(); ?>
          <?php $member = get_post(); ?>

          <?php
          $member_id       = $member->ID;
          $name            = get_the_title($member_id);
          $role            = get_field('job_role', $member_id);
          $img             = get_the_post_thumbnail($member_id, 'full');
          $departments     = get_the_terms($member_id, 'department');
          $locations       = get_the_terms($member_id, 'team_location');
          ?>

          <article class="team-card">
            <div class="team-card__image">
              <?php if ($img): ?>
                <?php echo $img; ?>
              <?php endif; ?>
            </div>

            <div class="team-card__body">
              <p class="team-card__name"><?php echo esc_html($name); ?></p>

              <?php if ($role): ?>
                <p class="team-card__role"><?php echo esc_html($role); ?></p>
              <?php endif; ?>

              <div class="team-card__meta">
                <?php if (!empty($departments) && !is_wp_error($departments)): ?>
                  <p class="team-card__department"><?php echo esc_html(implode(', ', wp_list_pluck($departments, 'name'))); ?></p>
                <?php endif; ?>

                <?php if (!empty($locations) && !is_wp_error($locations)): ?>
                  <?php
                  $location_names = array_map(
                    function ($location) {
                      return trim(explode(',', $location->name, 2)[0]);
                    },
                    $locations
                  );
                  ?>
                  <p class="team-card__location">
                    <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/pin.svg'); ?>" alt="">
                    <span><?php echo $location_names[0]; ?></span>
                  </p>
                <?php endif; ?>
              </div>

              <a href="<?php echo esc_url(get_permalink($member_id)); ?>" class="btn cta-link">
                <?php esc_html_e('View Profile', 'essenta-theme'); ?>
              </a>
            </div>
          </article>

        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
      </div>

    </div>
  </div>
</section>

<script>
  jQuery(document).ready(function($) {
    $('#<?php echo esc_js($uid); ?> .team-members-slider').slick({
      slidesToShow: 3.5,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      infinite: false,
      adaptiveHeight: false,
      prevArrow: $('#<?php echo esc_js($uid); ?> .team-members-block__prev'),
      nextArrow: $('#<?php echo esc_js($uid); ?> .team-members-block__next'),
      responsive: [{
        breakpoint: 1200,
        settings: {
          slidesToShow: 2.5
        }
      }, {
        breakpoint: 700,
        settings: {
          slidesToShow: 1.35
        }
      }]
    });
  });
</script>