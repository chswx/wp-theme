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

function create_ios_app_icons($cb = "A00YePnb9k")
{
    $sizes = ["57x57", "60x60", "72x72",
              "76x76", "114x114", "120x120",
              "144x144", "152x152", "180x180"];

    $output = "";

    foreach ($sizes as $size) {
        $output .= '<link rel="apple-touch-icon" sizes="' . $size . '" href="' . get_stylesheet_directory_uri() . '/assets/icons/apple-touch-icon-' . $size . '.png?v=' . $cb . '">' . "\n";
    }

    return $output;
}
