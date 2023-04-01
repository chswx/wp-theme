<?php

include_once('vendor/autoload.php');

use Olifolkerd\Convertor\Convertor;

function register_stylesheet()
{
    wp_enqueue_style('chswx-css', get_template_directory_uri() . '/css/responsive-style.css', array(), chswx_get_css_sha(), 'all');
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
add_theme_support('automatic-feed-links');
add_theme_support('post-formats', [
    'aside',
    'status'
]);

// Manual override for site icons
remove_action('wp_head', 'wp_site_icon', 99);

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
    return ($post_type === 'post') && (is_home() || is_archive() || is_single());
}

function chswx_get_observation_data()
{
    $ob = json_decode(file_get_contents(WP_CONTENT_DIR . '/uploads/KCHS_ob.json'), true);
    return $ob['properties'];
}

function chswx_get_forecast_data()
{
    $fcst = json_decode(file_get_contents(WP_CONTENT_DIR . '/uploads/KCHS_fcst.json'), true);
    $fcst['updated'] = strtotime($fcst['updated']);
    return $fcst;
}

/**
 * Normalize observation data from the NWS API.
 * For now, make it look like the wunderground output until we can do a better job.
 * Prefixes: n_ = normalized, c_ = convertor
 */
function chswx_normalize_observation_data($ob)
{
    // Initialize our array of normalized observations.
    // Yeah, it's PHP, and you don't _have_ to, but no notices is nice
    $n_ob = array(
        'temp_f'            => '',
        'dewpoint_f'        => '',
        'pressure_in'       => '',
        'relative_humidity' => '',
        'wind_mph'          => '',
        'wind_dir'          => '',
        'wind_gust_mph'     => '',
        'feelslike_f'       => '',
        'heat_index_f'      => 'NA',
        'windchill_f'       => 'NA',
        'weather'           => '',
        'observation_epoch' => '',
    );

    // Set up individual elements to convert.
    // We will need to do null checks on anything initializing a new Convertor.
    // Otherwise, they will fail out as a fatal (sad! bad design! hiss!)
    $t = $ob['temperature']['value'];
    $tD = $ob['dewpoint']['value'];
    $hi = $ob['heatIndex']['value'];
    $wc = $ob['windChill']['value'];
    $windSpd = $ob['windSpeed']['value'];
    $windGust = $ob['windGust']['value'];

    // Start unit conversions...
    if (!is_null($t)) {
        $c_temp = new Convertor($t, 'c');
        $n_ob['temp_f'] = round($c_temp->to('f'));
    }

    if (!is_null($tD)) {
        $c_dpt = new Convertor($tD, 'c');
        $n_ob['dewpoint_f'] = round($c_dpt->to('f'));
    }

    if (!is_null($windSpd)) {
        $c_wind = new Convertor($windSpd, 'km h**-1');
        $n_ob['wind_mph'] = round($c_wind->to('mi h**-1')) . " mph";
    } else {
        $n_ob['wind_mph'] = "Not Available";
    }

    if (!is_null($windGust)) {
        $c_gust = new Convertor($windGust, 'km h**-1');
        $n_ob['wind_gust_mph'] = round($c_gust->to('mi h**-1')) . " mph";
    }

    // Take the value of the heat index if it is not null, otherwise use wind chill
    $feelslike = !is_null($hi) ? $hi : $wc;

    // Emulate the values of HI/WC for purposes of how the old API worked
    if (!is_null($hi)) {
        $n_ob['heat_index_f'] = $hi;
    }

    if (!is_null($wc)) {
        $n_ob['windchill_f'] = $wc;
    }

    $c_pres = $ob['barometricPressure']['value'] / 3386.389;

    // End unit conversions. Start appending values to the array...


    $n_ob['pressure_in'] = number_format(round($c_pres, 2), 2);
    $n_ob['relative_humidity'] = round($ob['relativeHumidity']['value']) . '%';
    $n_ob['wind_dir'] = chswx_get_wind_direction($ob['windDirection']['value']);
    $n_ob['observation_epoch'] = strtotime($ob['timestamp']);
    $n_ob['weather'] = $ob['textDescription'];

    if (!is_null($feelslike)) {
        $c_feelslike = new Convertor($feelslike, 'c');
        $n_ob['feelslike_f'] = round($c_feelslike->to('f'));
    } else {
        $n_ob['feelslike_f'] = $n_ob['temp_f'];
    }

    return $n_ob;
}

/**
 * PHP port of a solution found at https://stackoverflow.com/questions/7490660/converting-wind-direction-in-angles-to-text-words
 *
 * @param int $dir Angle of the compass
 *
 * @return string Textual wind direction
 */
function chswx_get_wind_direction($dir)
{
    if (!is_null($dir)) {
        $val = (int) ($dir / 22.5) + 0.5;
        $directions = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
        return $directions[($val % 16)];
    }

    return "";
}

function chswx_get_css_sha()
{
    if (file_exists(ABSPATH . 'css-hash')) {
        return file_get_contents(ABSPATH . 'css-hash');
    }

    return false;
}

/**
 * Grab patrons from a list and display on screen.
 */
function chswx_patron_shortcode($atts)
{
    return file_get_contents('/home/chswx/patrons.inc');
}
add_shortcode('patrons', 'chswx_patron_shortcode');

/**
 * Add micro.blog verification to the header
 *
 * @return void
 */
function chswx_micro_blog_verify()
{
    echo '<link href="https://micro.blog/chswx" rel="me" />';
}
add_action('wp_head', 'chswx_micro_blog_verify');
