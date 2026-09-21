<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$insights_subheading = get_sub_field('insights_subheading');
$insights_heading = get_sub_field('insights_heading');
$insights_cta = get_sub_field('insights_cta');

$insights = new WP_Query(array(
    'post_type' => 'post',
    'posts_per_page' => 5,
    'orderby' => 'date',
    'order' => 'DESC'
));

?>

<section class="insights-block">
    <div class="container">
        <?php if ($insights_subheading) : ?>
            <p class="insights-subheading eyebrow"><?php echo esc_html($insights_subheading); ?></p>
        <?php endif; ?>

        <?php if ($insights_heading) : ?>
            <h2 class="insights-heading"><?php echo esc_html($insights_heading); ?></h2>
        <?php endif; ?>

        <?php if ($insights_cta) : ?>
            <a href="<?php echo esc_url($insights_cta['url']); ?>" class="insights-cta btn primary"><?php echo esc_html($insights_cta['title']); ?></a>
        <?php endif; ?>
    </div>

    <div class="insights-posts">
        <?php if ($insights->have_posts()) : ?>
            <div class="insights-posts-wrapper">
                <?php while ($insights->have_posts()) : $insights->the_post(); ?>
                    <a href="<?php the_permalink(); ?>">
                        <div class="insights-post">
                            <div class="insights-post-thumbnail">
                                <div class="insight-tags">
                                    <?php
                                    $post_cats = get_the_terms(get_the_ID(), 'category');
                                    if ($post_cats) {
                                        foreach ($post_cats as $tag) {
                                            echo '<span class="pill outline">' . esc_html($tag->name) . '</span>';
                                        }
                                    }
                                    ?>
                                </div>
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php endif; ?>
                            </div>
                            <div class="insights-post-content">
                                <h4 class="insights-post-title"><?php the_title(); ?></h4>
                                <div class="insights-post-excerpt"><?php the_excerpt(); ?></div>
                                <div class="insights-post-readmore btn cta-link">
                                    Read more
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
    <div class="insights-posts-navigation prev"></div>
    <div class="insights-posts-navigation next"></div>
</section>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.insights-posts-wrapper').slick({
            variableWidth: true,
            variableHeight: true,
            slidesToShow: 3.2,
            slidesToScroll: 1,
            arrows: true,
            infinite: false,
            autoplay: false,
            autoplaySpeed: 2000,
            prevArrow: $('.insights-posts-navigation.prev'),
            nextArrow: $('.insights-posts-navigation.next'),

            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        variableWidth: false,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });

    });
</script>