<?php get_header(); ?>
<div id="wrapper">
<?php if (is_home()) : ?>
<div class="blog-welcome">
    <h1 class="alternate">Charleston Weather Blog</h1>
    <p>Forecast explanations, atmospheric science, and other cool weather-related stuff for Charleston, SC</p>
</div>
<?php endif; ?>
<?php
if (have_posts()) :
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
</div>
<?php
get_footer();
