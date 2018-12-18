<?php get_header(); ?>
<div id="wrapper">
<h1>Blog</h1>
<?php
if(have_posts()) :
    while(have_posts()): the_post(); ?>
        <h1><?php the_title(); ?></h1>
    <?php endwhile;
endif;
?>
</div>
<?php
get_footer();
