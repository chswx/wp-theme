<?php get_header(); ?>
<?php
if (have_posts()) :
    the_archive_title( '<h1 class="page-title">', '</h1>' );
    the_archive_description( '<div class="archive-description">', '</div>' );

    while (have_posts()) :
        the_post();
        get_template_part('template-parts/part', 'post');
    endwhile;
    the_posts_navigation(
        array(
            'prev_text' => "&laquo; Older posts",
            'next_text' => "Newer posts &raquo;"
        )
    );
endif;
?>
<?php
get_footer();
