<?php

$scrolling_section_subtitle = get_sub_field('scrolling_section_subtitle');
$scrolling_section_header = get_sub_field('scrolling_section_header');

$scrolling_content_sections = get_sub_field('scrolling_content_sections');

?>

<div class="scrolling-contents-wrapper">
  <section class="scrolling-contents">

    <div class="scrolling-content-header">
      <?php if ($scrolling_section_subtitle) : ?>
        <div class="scrolling-content-subtitle "><?php echo esc_html($scrolling_section_subtitle); ?></div>
      <?php endif; ?>
      <?php if ($scrolling_section_header) : ?>
        <h2 class="scrolling-content-title"><?php echo esc_html($scrolling_section_header); ?></h2>
      <?php endif; ?>
    </div>

    <div class="scrolling-content-body">
      <div class="scrolling-content-track">
        <?php if ($scrolling_content_sections) : ?>
          <?php foreach ($scrolling_content_sections as $index => $section) :
            $subheader = $section['section_slide_title'];
            $image = $section['section_slide_image'];
            $image_icon = $section['section_slide_icon'];
          ?>
            <div class="scrolling-content" style="background-image: url('<?php echo esc_url($image['url']); ?>');">
              <?php if ($image) : ?>
                <div class="section-slide-overlay"></div>
              <?php endif; ?>
              <?php if ($subheader) : ?>
                <div class="sub-header-wrapper">
                  <div class="sub-header"><?php echo esc_html($subheader); ?></div>
                  <div class="header-divider"></div>
                  <?php if ($image_icon) : ?>
                    <div class="header-icon">
                      <img loading="lazy" src="<?php echo esc_url($image_icon['url']); ?>" alt="<?php echo esc_attr($image_icon['alt']); ?>" />
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </section>
</div>