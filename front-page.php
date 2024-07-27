<?php
get_header();
?>

<div id="currentwx">
    <h2>Currently</h2>
    <?php do_action('wxpress_observations'); ?>
</div>
<?php do_action('wxpress_alerts'); ?>
<?php do_action('chswx_updates'); ?>
<?php
$blog_args = [
    'post_type' => 'post',
    'limit' => 1,
    'category_name' => 'Forecasts',
    'tax_query' => [
        [
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => ['post-format-aside', 'post-format-status'],
            'operator' => 'NOT IN'
        ]
    ],
    'date_query' => [
        [
            'after' => '-18 hours'
        ]
    ]
];
$blog_query = new WP_Query($blog_args);
if ($blog_query->have_posts()) {
?>
    <div id="blog-intro">
        <h2>Charleston Weather Discussion</h2>
        <p class="intro-text">The latest forecast and discussion for the Charleston, SC metro area</p>
        <?php
        $blog_query->the_post();
        get_template_part('template-parts/part', 'post'); ?>
    </div><?php
            wp_reset_postdata();
        }
            ?>
<div id="forecast">
    <h2>Charleston Area <abbr title="National Weather Service">NWS</abbr> Forecast</h2>
    <?php do_action('wxpress_forecast'); ?>
</div>
<?php
get_footer();
