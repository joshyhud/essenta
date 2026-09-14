<?php get_header(); ?>

<?php
get_template_part('templates/components/blog-header', 'single');
?>

<div class="container contained">
    <?php the_content(); ?>
</div>

<section class="blog-meta">
    <div class="container contained">
        <div class="social-share">
            <h3>Share this post</h3>
            <div class="social-icons">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="social-icon facebook">
                    <i class="icon-facebook"></i>
                </a>
                <a href="https://www.instagram.com/" target="_blank" rel="noopener" class="social-icon instagram">
                    <i class="icon-instagram"></i>
                </a>
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&description=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="social-icon pinterest">
                    <i class="icon-pinterest"></i>
                </a>
            </div>
        </div>

        <div class="post-categories">
            <h3>Categories</h3>
            <?php
            $categories = get_the_category();
            if (!empty($categories)) :
                echo '<div class="category-list">';
                foreach ($categories as $category) :
                    echo '<a class="pill" href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                endforeach;
                echo '</div>';
            endif;
            ?>
        </div>
    </div>
</section>

<section class="articles-block">
    <div class="container">
        <div class="section-header">
            <p class="eyebrow">Related Posts</p>
            <h2 class="heading">More from our Blog</h2>
        </div>

        <div class="articles-content">
            <?php
            $current_post_id = get_the_ID();
            $current_categories = get_the_category($current_post_id);

            if (!empty($current_categories)) {
                $category_ids = array();
                foreach ($current_categories as $category) {
                    $category_ids[] = $category->term_id;
                }

                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 6,
                    'post__not_in' => array($current_post_id),
                    'category__in' => $category_ids,
                );
                $articles_query = new WP_Query($args);
            } else {
                $articles_query = null;
            }

            if ($articles_query && $articles_query->have_posts()) : ?>
                <div class="articles-wrapper">
                    <div class="articles-list">
                        <?php $index = 0;
                        while ($articles_query->have_posts()) : $articles_query->the_post(); ?>
                            <div class="article-item" data-index="<?php echo $index; ?>">
                                <a href="<?php the_permalink(); ?>" class="btn">
                                    <div class="article-date"><?php echo get_the_date('d-m-Y'); ?></div>
                                    <div class="article-category pill">
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) {
                                            echo esc_html($categories[0]->name);
                                        }
                                        ?>
                                    </div>
                                    <h3 class="article-title"><?php the_title(); ?></h3>
                                    <p class="btn primary">Read Article</p>
                                </a>
                            </div>
                            <?php $index++; ?>
                        <?php endwhile; ?>
                        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="articles-cta btn primary">View All Articles</a>
                    </div>

                    <div class="articles-image">
                        <?php
                        // Reset query to show first post image by default
                        $articles_query->rewind_posts();
                        $image_index = 0;
                        while ($articles_query->have_posts()) : $articles_query->the_post();
                        ?>
                            <div class="article-image-item <?php echo $image_index === 0 ? 'active' : ''; ?>" data-index="<?php echo $image_index; ?>">
                                <?php
                                $thumbnail_id = get_post_thumbnail_id();
                                $thumb_url = wp_get_attachment_image_src($thumbnail_id, 'full');
                                if (!$thumb_url) : ?>
                                    <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
                                <?php else : ?>
                                    <?php the_post_thumbnail('full', ['loading' => 'lazy']); ?>
                                <?php endif; ?>
                            </div>
                            <?php $image_index++; ?>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Mobile Slider Wrapper -->
                <div class="articles-mobile-slider">
                    <div class="articles-slider">
                        <?php
                        // Reset query for mobile slider
                        $articles_query->rewind_posts();
                        while ($articles_query->have_posts()) : $articles_query->the_post();
                        ?>
                            <div class="article-slide">
                                <div class="slide-header">
                                    <div class="article-date"><?php echo get_the_date('d-m-Y'); ?></div>
                                    <h3 class="article-title"><?php the_title(); ?></h3>
                                </div>

                                <div class="slide-image">
                                    <?php
                                    $thumbnail_id = get_post_thumbnail_id();
                                    $thumb_url = wp_get_attachment_image_src($thumbnail_id, 'full');
                                    if ($thumb_url) : ?>
                                        <?php the_post_thumbnail('full', array('loading' => 'lazy')); ?>
                                    <?php else : ?>
                                        <img loading="lazy" src="<?php echo esc_url(get_template_directory_uri() . '/src/images/product-placeholder.png'); ?>" alt="Placeholder Image">
                                    <?php endif; ?>
                                </div>

                                <div class="slide-footer">
                                    <div class="article-category pill">
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) {
                                            echo esc_html($categories[0]->name);
                                        }
                                        ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="btn primary">Read Article</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="slider-nav articles">
                        <div class="slider-dots"></div>
                        <div class="slider-arrows">
                            <button class="slick-prev"></button>
                            <button class="slick-next"></button>
                        </div>
                    </div>
                </div>

                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php esc_html_e('No related articles found.', 'boilerplate-theme'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>


<?php get_footer(); ?>