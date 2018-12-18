<?php

function register_stylesheet()
{
    wp_enqueue_style('chswx-css', get_template_directory_uri() . '/css/responsive-style.css', false, 'all');
}
add_action('wp_enqueue_scripts', 'register_stylesheet');

add_theme_support('title-tag');
