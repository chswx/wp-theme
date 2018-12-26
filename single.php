<?php get_header(); ?>
<div id="wrapper">
<?php
if (have_posts()) :
    the_post(); ?>
        <header class="entry-header">
            <h1><?php the_title(); ?></h1>
            <div class="meta"><?php the_author_link(); ?> / <?php the_date(); ?></div>
        </header>
        <?php the_content(); ?>
    <?php
endif;
?>
</div>
<?php
get_footer();
