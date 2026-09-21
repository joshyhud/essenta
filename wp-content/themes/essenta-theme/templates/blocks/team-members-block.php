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
  class="team-depts"
  id="<?php echo esc_attr($uid); ?>"
  data-team-depts>
  <div class="team-depts__content" data-team-content>
    <?php if ($team_block_subheading): ?>
      <p class="team-depts__subheading">
        <?php echo esc_html($team_block_subheading); ?>
      </p>
    <?php endif; ?>

    <?php if ($team_block_heading): ?>
      <h2 class="team-depts__heading">
        <?php echo esc_html($team_block_heading); ?>
      </h2>
    <?php endif; ?>

    <?php if ($team_block_content): ?>
      <div class="team-depts__content-text">
        <?php echo wp_kses_post(wpautop($team_block_content)); ?>
      </div>
    <?php endif; ?>
    <?php if ($team_block_primary_cta || $team_block_secondary_cta): ?>
      <div class="team-depts__ctas">
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
    <div class="team-depts__list">

      <section
        class="team-depts__section"
        data-dept-section>

        <?php while ($team_members->have_posts()): $team_members->the_post(); ?>
          <?php $member = get_post(); ?>

          <?php
          $member_id      = $member->ID;
          $name           = get_the_title($member_id);
          $role           = get_field('job_role', $member_id);
          $img            = get_the_post_thumbnail($member_id, 'medium');
          $member_content = get_post_field('post_content', $member_id);
          ?>

          <article class="team-card">

            <div class="team-card__user">

              <p class="team-card__name subheading">
                <?php echo esc_html($name); ?>
              </p>

              <?php if ($role): ?>
                <h3>
                  <?php echo esc_html($role); ?>
                </h3>
              <?php endif; ?>

              <?php if ($member_content): ?>
                <div class="member-content">
                  <?php echo wp_kses_post(wpautop($member_content)); ?>
                </div>
              <?php endif; ?>

            </div>

            <div class="team-card__image">
              <?php if ($img): ?>
                <?php echo $img; ?>
              <?php endif; ?>
            </div>

          </article>

        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>

      </section>

    </div>
  </div>
</section>