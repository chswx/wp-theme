<?php
include('vendor/autoload.php');
use Rarst\WordPress\DateTime\WpDateTime;
use Rarst\WordPress\DateTime\WpDateTimeZone;

$data = json_decode(file_get_contents(WP_CONTENT_DIR . '/uploads/KCHS.json'), true);

// Current conditions
if(isset($data['current_observation'])) {
    $ob = $data['current_observation'];
    $temperature = $ob['temp_f'] . "&deg;";

    // Feels like (heat index/wind chill)
    $feels_like_temp = $ob['feelslike_f'] . "&deg;";
    // v3.0.3: Set a default feels like type for sanity's sake
    $feels_like_type = "";
    // v3.0.3: WU API does not empty out the heat index/wind chill values,
    // just uses NA when they don't apply...so check against that instead
    if($ob['heat_index_f'] != "NA") {
        $feels_like_type = 'hi';
    }
    elseif($ob['windchill_f'] != "NA") {
        $feels_like_type = 'wc';
    }
    $display_feels_like = $feels_like_temp != $temperature;

    // Temperature color
    if($temperature < 28)
    {
        $tempcolor = "frigid";
    }
    else if ($temperature > 28 && $temperature < 50)
    {
        $tempcolor = "cold";
    }
    else if ($temperature >= 50 && $temperature < 70)
    {
        $tempcolor = "moderate";
    }
    else if ($temperature >= 70 && $temperature < 83)
    {
        $tempcolor = "warm";
    }
    else if ($temperature >= 83 && $temperature < 95)
    {
        $tempcolor = "verywarm";
    }
    else
    {
        $tempcolor = "hot";
    }

    // Sensible weather
    $sky = $ob['weather'];

    // Winds
    if($ob['wind_mph'] == 0) {
        $wind = "Calm";
    }
    else {
        $wind = $ob['wind_dir'] . " " . $ob['wind_mph'];
    }

    if($ob['wind_gust_mph'] > 0) {
        $wind .= " (gust {$ob['wind_gust_mph']})";
    }

    // Other statistics
    $dewpoint = $ob['dewpoint_f'] . "&deg;F";
    $rh = $ob['relative_humidity'];
    $pressure = $ob['pressure_in'] . " in";
    $obdate = new WpDateTime();
    $obdate->setTimestamp($ob['observation_epoch']);
    $obdate->setTimezone = WpDateTimeZone::getWpTimezone();
}
?>
<?php get_header(); ?>
<script>
jQuery(document).ready(function($) {
    $('.alert').click(function(event) {
        var toggleID = '#' + event.currentTarget.id.toString();
        $(toggleID + ' ul').toggle();
    });
});
</script>
    <div id="currentwx">
        <h2>CURRENTLY</h2>
    <?php
if(isset($data['current_observation'])) { 
    ?>
    <div id="temp" class="<?php echo $tempcolor?>"><?php echo $temperature?></div>
    <?php if ($display_feels_like): ?>
    <div id="feels-like">Feels Like <span class="<?php echo $feels_like_type?>"><?php echo $feels_like_temp?></span></div>
    <?php endif; ?>
    <div id="sky"><?php echo $sky;?></div>
    <ul id="others">
        <li><span class="title">Wind</span> <?php echo $wind?></li>
        <li><span class="title">Pressure</span> <?php echo $pressure;?></li>
        <li><span class="title">Dewpoint</span> <?php echo $dewpoint;?></li>
        <li><span class="title">Humidity</span> <?php echo $rh;?></li>
    </ul>
    <div class="updated-time">last updated at <?php echo $obdate->formatTime(); ?> on <?php echo $obdate->formatDate(); ?></div>
<?php } else { ?>
    <div class="fail">Temporarily Unavailable</div>
<?php } ?>
</div>
<?php if (!empty($data['alerts'])) 
{
?>
<div id="advisories">
    <h2>Alerts</h2>
    <ul>
    <?php foreach($data['alerts'] as $alert)
    {
        // try to filter out bad advisories
        $current_time = time();
        
        if($alert['phenomena'] == "TO")
        {
            $advisory_class = "tor";
        }
        else if($alert['phenomena'] == "SV")
        {
            $advisory_class = "svr";
        }
        else if($alert['phenomena'] == 'FL' || $alert['phenomena'] == 'FF')
        {
            $advisory_class = "ffw";
        }
        else
        {
            $advisory_class = "normal";
        }

        $alert_timing_text = '';

        if($alert['date_epoch'] > time()) {
            $alert_timing_text = "from {$alert['date']} ";
        }

        $alert_timing_text .= " until {$alert['expires']}";

        echo "<li class=\"alert vtec-phen-{$alert['phenomena']} vtec-sig-{$alert['significance']}\" id=\"{$alert['phenomena']}-{$alert['significance']}-{$alert['date_epoch']}\"><span class=\"alert-name\">" . $alert['description'] . "</span> <span class=\"alert-timing\">$alert_timing_text</span>";
        echo "<ul><li>" . str_replace("\n",'<br />',trim($alert['message'])) . "</li></ul></li>";
    }
    ?>
</div>
<?php } ?>
<div id="forecast">
    <h2>Forecast</h2>
    <div class="updated-time">Forecast for Charleston updated at <?php echo $data['forecast']['txt_forecast']['date']?></div>
    <ul>
        <?php 
            if(isset($data['forecast']['txt_forecast']))
            {
                foreach($data['forecast']['txt_forecast']['forecastday'] as $forecast)
                {
                    ?><li><span class="day"><?php echo $forecast['title']?></span> <span class="forecast_text"><?php echo $forecast['fcttext']?></span></li>
                    <?php
                }
            }
            else
            {
                ?><div class="fail">Forecast temporarily unavailable</div>
            <?php }
        ?>
    </ul>
</div>
<?php
get_footer();
