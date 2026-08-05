<?php

$step_section_heading = get_sub_field('step_section_header');

$steps = get_sub_field('steps_sections');
?>

<?php if (!empty($steps) && is_array($steps)) : ?>

  <div class="step-section-wrapper" data-step-count="<?php echo count($steps); ?>">
    <section class="step-section">

      <div class="step-section-left">
        <h3 class="step-section-heading"><?php echo esc_html($step_section_heading); ?></h3>

        <div class="step-section-steps">
          <?php foreach ($steps as $index => $step) : ?>
            <details class="step-section-step" name="step-section-item" data-step-index="<?php echo esc_attr($index); ?>" <?php echo 0 === $index ? 'open' : ''; ?>>
              <summary class="step-section-count">
                <div class="step-section-icon">
                  <img loading="lazy" src="<?php echo esc_url($step['step_section_icon']['url']); ?>" alt="<?php echo esc_attr($step['step_section_icon']['alt']); ?>">
                </div>
                Step <?php echo sprintf('%02d', $index + 1); ?>
              </summary>
              <div class="step-section-step-content">
                <h5 class="step-section-title"><?php echo esc_html($step['step_section_header']); ?></h5>
                <p class="step-section-description"><?php echo wp_kses_post($step['step_section_content']); ?></p>
              </div>
            </details>
            <div class="step-section-divider col-2"></div>
          <?php endforeach; ?>
        </div>

      </div>

      <div class="step-section-right">
        <?php foreach ($steps as $index => $step) : ?>
          <div class="step-section-image <?php echo 0 === $index ? 'is-active' : ''; ?>" data-step-index="<?php echo esc_attr($index); ?>" aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
            <img loading="lazy" src="<?php echo esc_url($step['step_section_image']['url']); ?>" alt="<?php echo esc_attr($step['step_section_image']['alt']); ?>">
          </div>
        <?php endforeach; ?>
      </div>

    </section>
  </div>
<?php endif; ?>