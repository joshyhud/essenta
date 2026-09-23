<?php


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$current_service_id = is_singular('service') ? get_queried_object_id() : 0;

// Show three alternative services on service pages and all services elsewhere.
$service_query_args = array(
    'post_type' => 'service',
    'posts_per_page' => $current_service_id ? 3 : -1,
    'orderby' => 'menu_order title',
    'order' => 'ASC',
);

if ($current_service_id) {
    $service_query_args['post__not_in'] = array($current_service_id);
}

$services_query = new WP_Query($service_query_args);

if (!$services_query->have_posts()) {
    return;
}

$services = array();

// Convert each service post into the data used by the accordion and image panel.
while ($services_query->have_posts()) {
    $services_query->the_post();

    $service_types = get_the_terms(get_the_ID(), 'service_type');
    $service_type_names = $service_types && !is_wp_error($service_types)
        ? wp_list_pluck($service_types, 'name')
        : array('Service');

    $services[] = array(
        'title' => get_the_title(),
        'type' => implode(', ', $service_type_names),
        'excerpt' => get_the_excerpt(),
        'url' => get_permalink(),
        'image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
    );
}

wp_reset_postdata();
$first_service = $services[0];

?>

<section class="services-block">
    <div class="container">
        <!-- The image starts with the first service and updates when another item opens. -->
        <div class="services-block__image-wrap">
            <?php if ($first_service['image']) : ?>
                <img class="services-block__image" src="<?php echo esc_url($first_service['image']); ?>" alt="<?php echo esc_attr($first_service['title']); ?>">
            <?php endif; ?>
        </div>

        <div class="services-block__accordion">
            <!-- Each service type is the accordion summary; the panel contains the service details and CTA. -->
            <?php foreach ($services as $index => $service) : ?>
                <details
                    class="services-block__item"
                    data-image="<?php echo esc_url($service['image']); ?>"
                    data-alt="<?php echo esc_attr($service['title']); ?>"
                    <?php echo $index === 0 ? ' open' : ''; ?> name="service-item">
                    <summary><?php echo esc_html($service['type']); ?></summary>
                    <div class="services-block__content">
                        <p class="eyebrow"> Our <?php echo esc_html($service['type']); ?> Services</p>
                        <h3><?php echo esc_html($service['title']); ?></h3>
                        <?php if ($service['excerpt']) : ?>
                            <p><?php echo wp_kses_post($service['excerpt']); ?></p>
                        <?php endif; ?>
                        <a class="btn primary" href="<?php echo esc_url($service['url']); ?>">View service</a>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
    jQuery(function($) {
        $('.services-block').each(function() {
            var $block = $(this);
            var $image = $block.find('.services-block__image');

            // Keep one service open at a time and sync the featured image with it.
            $block.on('toggle', '.services-block__item', function() {
                var $item = $(this);

                if (!$item.prop('open')) {
                    return;
                }

                $block.find('.services-block__item').not(this).prop('open', false);

                if ($image.length && $item.data('image')) {
                    $image.attr('src', $item.data('image')).attr('alt', $item.data('alt'));
                }
            });
        });
    });
</script>