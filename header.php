<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-title" content="CHS Weather">
    <meta name="application-name" content="Charleston Weather">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri() ?>/assets/favicon.png">
    <link rel="apple-touch-icon" type="image/png" href="<?php echo get_stylesheet_directory_uri() ?>/assets/chswx-retro-2023.png">
    <?php

    wp_head();
    ?>
</head>

<body <?php body_class(); ?>>
    <h1 class="main-title">
        <span class="city" title="Charleston Weather">
            <a href="/"><object type="image/svg+xml" data="<?php echo get_stylesheet_directory_uri() ?>/assets/wordmark-oneline.svg" class="chswx-logo">Charleston Weather</object></a>
        </span>
    </h1>
    <?php do_action('wxpress_critical_alerts'); ?>
    <div id="wrapper">
        <?php if (is_blog_page() && !is_home()) : ?>
            <h2 class="alternate"><a href="<?php echo get_post_type_archive_link('post') ?>">Blog</a></h2>
        <?php endif; ?>