<?php get_header(); ?>
<div id="wrapper">
<?php
if (have_posts()) :
    the_post(); ?>
        <header class="entry-header">
            <h1><?php the_title(); ?></h1>
            <div class="meta"><?php the_author_posts_link(); ?> / <?php the_date(); ?></div>
        </header>
        <?php
        the_content();
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>
    <?php
endif;
?>
</div>
<?php
get_footer();
