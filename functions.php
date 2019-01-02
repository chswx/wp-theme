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
add_theme_support('post-thumbnails');

/**
 * WordPress' missing is_blog_page() function.  Determines if the currently viewed page is
 * one of the blog pages, including the blog home page, archive, category/tag, author, or single
 * post pages.
 *
 * @see https://gist.github.com/wesbos/1189639#gistcomment-2698304
 *
 * @return bool
 */
function is_blog_page()
{
    global $post;

    // Post type must be 'post'.
    $post_type = get_post_type($post);

    // Check all blog-related conditional tags, as well as the current post type, 
    // to determine if we're viewing a blog page.
    return ( $post_type === 'post' ) && ( is_home() || is_archive() || is_single() );
}
