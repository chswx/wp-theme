<?php

function register_stylesheet()
{
    wp_enqueue_style('chswx-css', get_template_directory_uri() . '/css/responsive-style.css', false, 'all');
}
add_action('wp_enqueue_scripts', 'register_stylesheet');

function maybe_register_jquery()
{
    if (is_front_page()) {
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'maybe_register_jquery');

add_theme_support('title-tag');
add_theme_support('wp-block-styles');
add_theme_support('responsive-embeds');
