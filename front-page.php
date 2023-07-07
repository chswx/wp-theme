<?php
get_header();
?>

<div id="currentwx">
    <h2>CURRENTLY</h2>
    <?php do_action('wxpress_observations'); ?>
</div>
<?php do_action('wxpress_alerts'); ?>
<?php do_action('chswx_updates'); ?>
<div id="forecast">
    <h2>Forecast</h2>
    <?php do_action('wxpress_forecast'); ?>
</div>
<?php
$blog_args = [
    'post_type' => 'post',
    'limit' => 1,
    'tax_query' => [[
        'taxonomy' => 'post_format',
        'field'    => 'slug',
        'terms'    => ['post-format-aside', 'post-format-status'],
        'operator' => 'NOT IN'
    ]]
];
$blog_query = new WP_Query($blog_args);
if ($blog_query->have_posts()) {
?>
    <div id="blog-intro">
        <h2>Charleston Weather Blog</h2>
        <p class="intro-text">Forecast explanations, atmospheric science, and other cool weather-related stuff for Charleston, SC</p>
        <?php
        $blog_query->the_post();
        get_template_part('template-parts/part', 'post'); ?>
        <a href="<?php echo get_post_type_archive_link('post') ?>">More Posts &raquo;</a>
    </div><?php
            wp_reset_postdata();
        }
            ?>
<?php
get_footer();
