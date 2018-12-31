<article class="partial-post">
    <header class="entry-header">
            <h1><a href="<?php echo get_the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
            <div class="meta"><?php the_author_posts_link(); ?> / <?php the_date(); ?> at <span class="time"><?php the_time(); ?></span></div>
        </header>
        <?php the_content('Read more &raquo;'); ?>
</article>
