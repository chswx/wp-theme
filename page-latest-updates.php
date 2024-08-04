<?php
get_header();
?>
<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = [
    'post_type' => 'post',
    'posts_per_page' => 10,
    'paged' => $paged,
    'tax_query' => [
        [
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => ['post-format-aside', 'post-format-status']
        ]
    ]
];
$aside_query = new WP_Query($args);
$count = 0;
if ($aside_query->have_posts()) :
?>
    <div class="blog-welcome">
        <h1 class="alternate">Latest Updates</h1>
        <p>Breaking weather updates for the Charleston, SC metro area.</p>
    </div>
    <div class="latest-updates-archive">
        <?php while ($aside_query->have_posts()) :
            $aside_query->the_post(); ?>
            <?php if (is_new_day()) : ?><h2 class="date-header"><?php the_date(); ?></h2><?php endif; ?>
            <?php get_template_part('template-parts/part', 'aside'); ?>
        <?php
        endwhile;
        ?>
        <?php
        the_posts_navigation(
            array(
                'prev_text' => "&laquo; Older posts",
                'next_text' => "Newer posts &raquo;"
            )
        );
        wp_reset_query();
        ?>


    <?php
endif;
get_footer();
