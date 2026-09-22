<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$offices = new WP_Query(array(
    'post_type' => 'office',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
));

$office_locations = array();

foreach ($offices->posts as $office) :
    $location = get_field('office_location', $office->ID);

    if (!is_array($location) || !isset($location['lat'], $location['lng'])) {
        continue;
    }

    $location_type = get_field('office_or_delivery_partner', $office->ID);

    $office_locations[] = array(
        'title' => get_the_title($office),
        'country' => isset($location['country']) ? $location['country'] : '',
        'url' => get_permalink($office),
        'address' => isset($location['address']) ? $location['address'] : '',
        'phone' => get_field('office_phone', $office->ID),
        'email' => get_field('office_email', $office->ID),
        'description' => get_the_excerpt($office),
        'image' => get_the_post_thumbnail_url($office->ID, 'large'),
        'type' => $location_type === 'partner' ? 'partner' : 'office',
        'lat' => (float) $location['lat'],
        'lng' => (float) $location['lng'],
    );
endforeach;
$offices = $offices->posts;

if ($office_locations) {
    $google_maps_api_key = apply_filters(
        'essenta_google_maps_api_key',
        'AIzaSyAW0GJXmU70GtFN4p8eyw1ujFSSXhlJRY8'
    );

    wp_enqueue_script(
        'essenta-google-maps',
        add_query_arg(
            array(
                'key' => $google_maps_api_key,
                'callback' => 'essentaInitOfficeMaps',
                'loading' => 'async',
            ),
            'https://maps.googleapis.com/maps/api/js'
        ),
        array('main-script'),
        null,
        true
    );
}
?>

<section class="office-locations">
    <div class="container">
        <?php if ($office_locations) : ?>
            <div class="office-locations__stage">
                <div class="office-locations__map js-office-locations-map" aria-label="Office and delivery partner locations map">
                    <?php foreach ($office_locations as $location_index => $office_location) : ?>
                        <div
                            class="office-locations__marker"
                            data-location-index="<?php echo esc_attr($location_index); ?>"
                            data-location-type="<?php echo esc_attr($office_location['type']); ?>"
                            data-title="<?php echo esc_attr($office_location['title']); ?>"
                            data-lat="<?php echo esc_attr($office_location['lat']); ?>"
                            data-lng="<?php echo esc_attr($office_location['lng']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="office-locations__intro">
                    <p class="office-locations__eyebrow">By the numbers</p>
                    <h2>Global reach, personal commitment</h2>
                    <div class="office-locations__controls" aria-label="Choose an office">
                        <?php foreach ($office_locations as $location_index => $office_location) : ?>
                            <?php if ($office_location['type'] !== 'office') continue; ?>
                            <button
                                class="office-locations__toggle"
                                type="button"
                                data-location-index="<?php echo esc_attr($location_index); ?>"
                                aria-controls="office-location-<?php echo esc_attr($location_index); ?>"
                                aria-expanded="false">
                                <span><?php echo esc_html($office_location['title']); ?></span>
                                <span class="office-locations__toggle-icon" aria-hidden="true"></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="office-locations__legend" aria-label="Map legend">
                    <div><span class="office-locations__legend-role" aria-hidden="true"></span>Roles delivered</div>
                    <div><span class="office-locations__legend-office" aria-hidden="true"></span>Office locations</div>
                </div>

                <aside class="office-locations__drawer" aria-hidden="true" aria-label="Office details">
                    <button class="office-locations__close" type="button" aria-label="Close office details"></button>
                    <?php foreach ($office_locations as $location_index => $office_location) : ?>
                        <?php if ($office_location['type'] !== 'office') continue; ?>
                        <article
                            class="office-locations__details"
                            id="office-location-<?php echo esc_attr($location_index); ?>"
                            data-location-index="<?php echo esc_attr($location_index); ?>"
                            hidden>
                            <p class="office-locations__eyebrow">Our office</p>
                            <h2>
                                <?php echo esc_html($office_location['title']); ?><?php echo $office_location['country'] ? ', ' . esc_html($office_location['country']) : ''; ?>
                            </h2>
                            <?php if ($office_location['image']) : ?>
                                <img class="office-locations__image" src="<?php echo esc_url($office_location['image']); ?>" alt="<?php echo esc_attr($office_location['title']); ?> office">
                            <?php endif; ?>
                            <div class="office-locations__contact">
                                <?php if ($office_location['address']) : ?>
                                    <div class="office-locations__contact-row">
                                        <span class="office-locations__contact-icon office-locations__contact-icon--pin" aria-hidden="true"></span>
                                        <address><?php echo esc_html($office_location['address']); ?></address>
                                    </div>
                                <?php endif; ?>
                                <?php if ($office_location['phone']) : ?>
                                    <div class="office-locations__contact-row">
                                        <span class="office-locations__contact-icon office-locations__contact-icon--phone" aria-hidden="true"></span>
                                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $office_location['phone'])); ?>"><?php echo esc_html($office_location['phone']); ?></a>
                                    </div>
                                <?php endif; ?>
                                <?php if ($office_location['email']) : ?>
                                    <div class="office-locations__contact-row">
                                        <span class="office-locations__contact-icon office-locations__contact-icon--email" aria-hidden="true"></span>
                                        <a href="mailto:<?php echo esc_attr($office_location['email']); ?>"><?php echo esc_html($office_location['email']); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <a class="btn cta-link" href="<?php echo esc_url($office_location['url']); ?>">
                                View office
                            </a>
                        </article>
                    <?php endforeach; ?>
                </aside>
            </div>
        <?php else : ?>
            <p class="office-locations__empty">No office locations are currently available.</p>
        <?php endif; ?>
    </div>
</section>