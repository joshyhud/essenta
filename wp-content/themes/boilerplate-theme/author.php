<?php get_header(); ?>
    <section class="author-container">
        <div class="small-container">
            <?php if ( function_exists( 'acf' ) && function_exists( 'show_author_bio' )) {
                show_author_bio();
            } ?>
        </div>
    </section>
<?php get_footer(); ?>