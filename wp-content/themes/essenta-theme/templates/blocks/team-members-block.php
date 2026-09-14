<?php


if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

$tabs = [
  'management' => [
    'label'   => 'Management',
    'members' => get_sub_field('management_members') ?: [],
  ],
  'workshop' => [
    'label'   => 'Workshop',
    'members' => get_sub_field('workshop_members') ?: [],
  ],
  'sales' => [
    'label'   => 'Sales',
    'members' => get_sub_field('sales_members') ?: [],
  ],
];

// Optional: hide empty departments
$tabs = array_filter($tabs, fn($t) => !empty($t['members']));

if (empty($tabs)) {
  echo '<p>No team members selected.</p>';
  return;
}

$uid = 'team-depts-' . wp_unique_id();
?>

<section class="team-depts" id="<?php echo esc_attr($uid); ?>" data-team-depts>
  <aside class="team-depts__nav" aria-label="Departments">
    <div class="team-depts__nav-inner">
      <ul class="team-depts__nav-list">
        <?php $i = 0;
        foreach ($tabs as $key => $tab): ?>
          <li>
            <a
              class="team-depts__nav-link <?php echo $i === 0 ? 'is-active' : ''; ?>"
              href="#<?php echo esc_attr($uid . '-' . $key); ?>"
              data-dept-link="<?php echo esc_attr($key); ?>">
              <?php echo esc_html($tab['label']); ?>
            </a>
          </li>
        <?php $i++;
        endforeach; ?>
      </ul>
    </div>
  </aside>

  <div class="team-depts__content" data-team-content>
    <div class="team-depts__list">
      <?php foreach ($tabs as $key => $tab): ?>
        <section
          class="team-depts__section"
          id="<?php echo esc_attr($uid . '-' . $key); ?>"
          data-dept-section
          data-dept-key="<?php echo esc_attr($key); ?>">

          <?php foreach ($tab['members'] as $member): ?>
            <?php
            $member_id = is_object($member) ? $member->ID : (int) $member;
            $name      = get_the_title($member_id);
            $role      = get_field('job_role', $member_id); // optional
            $img       = get_the_post_thumbnail($member_id, 'medium');
            $member_content = get_post_field('post_content', $member_id);

            ?>
            <article class="team-card">
              <div class="team-card__user">
                <p class="team-card__name subheading"><?php echo esc_html($name); ?></p>
                <h3><?php echo esc_html($role); ?></h3>

                <div class="member-content">
                  <?php echo wp_kses_post(wpautop($member_content)); ?>
                </div>
              </div>

              <div class="team-card__image">
                <?php if ($img): ?>
                  <?php echo $img; ?>
                <?php endif; ?>
              </div>

            </article>
          <?php endforeach; ?>
        </section>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const teamDepts = document.querySelector('[data-team-depts]');
    if (!teamDepts) return;

    const navLinks = teamDepts.querySelectorAll('[data-dept-link]');
    const sections = teamDepts.querySelectorAll('[data-dept-section]');
    const content = teamDepts.querySelector('[data-team-content]');

    if (!navLinks.length || !sections.length || !content) return;

    // Smooth scrolling when clicking nav links
    navLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);

        if (targetSection) {
          const offsetTop = targetSection.offsetTop - content.offsetTop;
          content.scrollTo({
            top: offsetTop,
            behavior: 'smooth'
          });
        }
      });
    });

    // Update active nav link based on scroll position using Intersection Observer
    const observerOptions = {
      root: content,
      rootMargin: '-20% 0px -70% 0px',
      threshold: 0
    };

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const sectionKey = entry.target.getAttribute('data-dept-key');

          // Remove active class from all links
          navLinks.forEach(link => link.classList.remove('is-active'));

          // Add active class to current section's link
          const activeLink = teamDepts.querySelector(`[data-dept-link="${sectionKey}"]`);
          if (activeLink) {
            activeLink.classList.add('is-active');
          }
        }
      });
    }, observerOptions);

    // Observe all sections
    sections.forEach(section => {
      observer.observe(section);
    });
  });
</script>