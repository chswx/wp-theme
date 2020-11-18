<?php get_header(); ?>
<article class="full-post">
    <?php
    while (have_posts()) :
        the_post(); ?>
        <header class="entry-header">
            <h1><?php the_title(); ?></h1>
            <div class="meta"><?php the_author_posts_link(); ?> / <?php the_date(); ?> at <span class="time"><?php the_time(); ?></span></div>
        </header>
        <?php
        the_content(); ?>
        <footer class="entry-footer <?php if (has_tag()) : ?>tagged<?php endif; ?>">
            <div class="meta">
                <?php
                the_tags();
                ?>
            </div>
            <?php
            the_post_navigation(); ?>
            </div>
            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            /*
        if (comments_open() || get_comments_number()) {
            comments_template();
        }*/
            ?>
        <?php
    endwhile;
        ?>
</article>
<?php
get_footer();
