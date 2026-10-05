<?php
// Blog Header
$post_id = get_the_ID();
$categories = get_the_category($post_id);
$expertise_terms = get_the_terms($post_id, 'post-expertise');
$author = get_field('post_author', $post_id);
$summary = get_field('post_header_summary', $post_id);

$reading_time = (int) get_post_meta($post_id, '_yoast_wpseo_estimated-reading-time-minutes', true);
if (!$reading_time) {
  $reading_time = max(1, (int) ceil(str_word_count(wp_strip_all_tags(get_post_field('post_content', $post_id))) / 200));
}

$posts_page_id = (int) get_option('page_for_posts');
$permalink = get_permalink($post_id);
$title = get_the_title($post_id);
?>

<section class="blog-header">
  <div class="container">
    <div class="blog-header-split">
      <div class="blog-header-title-content">
        <nav class="blog-header-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'essenta-theme'); ?>">
          <?php if ($posts_page_id) : ?>
            <a href="<?php echo esc_url(get_permalink($posts_page_id)); ?>"><?php echo esc_html(get_the_title($posts_page_id)); ?></a>
            <span class="blog-header-breadcrumbs-separator" aria-hidden="true">›</span>
          <?php endif; ?>
          <span aria-current="page"><?php echo esc_html($title); ?></span>
        </nav>

        <div class="blog-header-heading">
          <?php if ($categories) : ?>
            <p class="eyebrow blog-header-category"><?php echo esc_html($categories[0]->name); ?></p>
          <?php endif; ?>

          <h1 class="blog-header-title"><?php echo esc_html($title); ?></h1>
        </div>

        <?php if ($expertise_terms && !is_wp_error($expertise_terms)) : ?>
          <ul class="blog-header-tags">
            <?php foreach ($expertise_terms as $expertise) : ?>
              <li class="blog-header-tag"><?php echo esc_html($expertise->name); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <div class="blog-header-byline">
          <div class="blog-header-author">
            <?php if ($author instanceof WP_Post && has_post_thumbnail($author)) : ?>
              <div class="blog-header-author-image">
                <?php echo get_the_post_thumbnail($author, 'thumbnail', array('alt' => esc_attr(get_the_title($author)))); ?>
              </div>
            <?php endif; ?>
            <div class="blog-header-author-details">
              <?php if ($author instanceof WP_Post) : ?>
                <?php $author_role = get_field('job_role', $author->ID); ?>
                <p class="blog-header-author-name"><?php echo esc_html(get_the_title($author)); ?></p>
                <?php if ($author_role) : ?>
                  <p class="blog-header-author-role"><?php echo esc_html($author_role); ?></p>
                <?php endif; ?>
              <?php endif; ?>
              <p class="blog-header-meta">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('F j, Y')); ?></time>
                |
                <?php echo esc_html(sprintf(__('%d min', 'essenta-theme'), $reading_time)); ?>
              </p>
            </div>
          </div>

          <?php
          $author_linkedin = $author instanceof WP_Post ? get_field('team_member_linkedin', $author->ID) : null;
          $author_email = $author instanceof WP_Post ? get_field('team_member_email', $author->ID) : null;
          $author_email_url = $author_email['url'] ?? '';
          $author_email_address = preg_replace('#^(https?://|mailto:)#i', '', $author_email_url);

          if (is_email($author_email_address)) {
            $author_email_url = 'mailto:' . $author_email_address;
          }
          ?>
          <div class="blog-header-share">
            <?php if (!empty($author_linkedin['url'])) : ?>
              <a class="blog-header-share-button" href="<?php echo esc_url($author_linkedin['url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr(sprintf(__('%s on LinkedIn', 'essenta-theme'), get_the_title($author))); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/dist/images/share-linkedin.svg'); ?>" width="16" height="16" alt="">
              </a>
            <?php endif; ?>
            <?php if ($author_email_url) : ?>
              <a class="blog-header-share-button" href="<?php echo esc_url($author_email_url); ?>" aria-label="<?php echo esc_attr(sprintf(__('Email %s', 'essenta-theme'), get_the_title($author))); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/dist/images/share-email.svg'); ?>" width="16" height="16" alt="">
              </a>
            <?php endif; ?>
            <div class="blog-header-share-copy">
              <button class="blog-header-share-button" type="button" data-copy-link="<?php echo esc_url($permalink); ?>" aria-label="<?php esc_attr_e('Copy link', 'essenta-theme'); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/dist/images/share-link.svg'); ?>" width="16" height="16" alt="">
              </button>
              <span class="blog-header-share-popover" role="status" aria-live="polite" hidden><?php esc_html_e('URL copied', 'essenta-theme'); ?></span>
            </div>
          </div>
        </div>
      </div>

      <?php if ($summary) : ?>
        <div class="blog-header-summary">
          <p class="eyebrow"><?php esc_html_e('Executive Summary', 'essenta-theme'); ?></p>
          <div class="blog-header-summary-content">
            <?php echo wp_kses_post($summary); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
  document.querySelectorAll('.blog-header [data-copy-link]').forEach(function(button) {
    var popover = button.parentElement.querySelector('.blog-header-share-popover');
    var timer;

    button.addEventListener('click', function() {
      if (!navigator.clipboard) return;
      navigator.clipboard.writeText(button.dataset.copyLink).then(function() {
        button.classList.add('is-copied');
        popover.hidden = false;
        clearTimeout(timer);
        timer = setTimeout(function() {
          button.classList.remove('is-copied');
          popover.hidden = true;
        }, 2000);
      });
    });
  });
</script>