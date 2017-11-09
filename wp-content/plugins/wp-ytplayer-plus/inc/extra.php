<?php
/**
 * Created by mb.ideas.
 * User: pupunzi
 * Date: 19/11/16
 * Time: 15:07
 */

$price = 8;
$ytp_core = new ytp_mb_core("YTPL", $mbYTPlayer_license_key, $ytp_base);
$lic_domain = $ytp_core->get_lic_domain();
$ytp_xxx = isset($mbYTPlayer_license_key);

/**
 * Save Lic to file
 */
add_action('wp_ajax_mbytpplus_storeLic', 'ytp_storeLic');
function ytp_storeLic(){
    global $ytp_core;
    $ytp_core->storeLic();
}

/**
 * @param $hook
 */
add_action('admin_enqueue_scripts', 'mbytpplus_load_admin_script');
function mbytpplus_load_admin_script($hook)
{
    global $mbYTPlayer_version, $lic_domain, $ytp_xxx, $ytp_core;
    if ($hook != 'mb-ideas_page_wp-ytplayer-plus/wp-ytplayer-plus' && $hook != 'toplevel_page_mb-ideas-menu')
        return;

    $ytp_xxx = $ytp_core->validate_local_lic();

    wp_register_script('ytp_admin', plugins_url('/ytp_admin.js', __FILE__), array('jquery'), $mbYTPlayer_version, true, 1000);
    $data = array(
        "str_valid_key_needed" => __('A license key is needed', 'wpmbytplayer'),
        "str_license_not_valid" => __('Your license can\'t be verified', 'wpmbytplayer'),
        "str_server_error" => __('There\'s been a problem with the server:', 'wpmbytplayer'),
        "str_license_valid" => __('Your license is valid', 'wpmbytplayer'),
        "str_license_validating" => __('Validating your license', 'wpmbytplayer'),
        "str_email_sent" => __('An email has been sent. Follow the link', 'wpmbytplayer'),

        "lic_domain" => $lic_domain,
        "lic_theme" => get_template()
    );

    wp_localize_script('ytp_admin', 'ytpl_lic', $data);
    wp_enqueue_script('ytp_admin');
    wp_enqueue_style('ytp_admin_css', plugins_url('/mb_admin.css', __FILE__), null, MBYTPLAYER_PLUS_VERSION);
}

/**
 * define the shortcode function
 */
add_shortcode('mbYTPlayer', 'mbytpplus_shortcode');
add_filter('widget_text', 'do_shortcode');
function mbytpplus_shortcode($atts)
{
    global $ytp_xxx, $mbYTPlayer_is_active;

    STATIC $i = 1;
    $elId = "body";
    $style = "";
    extract(shortcode_atts(array(
        'url' => '',
        'fallback_image' => null,
        'custom_id' => null,
        'showcontrols' => '',
        'printurl' => '',
        'mute' => '',
        'ratio' => '',
        'loop' => '',
        'opacity' => '',
        'quality' => '',
        'addraster' => '',
        'isinline' => '',
        'playerwidth' => '',
        'playerheight' => '',
        'autoplay' => '',
        'gaTrack' => '',
        'stopmovieonblur' => '',
        'realfullscreen' => 'true',
        'elementselector' => null,
        'startat' => '',
        'stopat' => '',
        'volume' => ''
    ), $atts));

    if (empty($url) || ((is_home() || is_front_page()) && !empty($mbYTPlayer_home_video_url) && empty($isInline)))
        return false;

    if (empty($custom_id)) {
        $custom_id = null;
    }
    if (empty($fallback_image)) {
        $fallback_image = null;
    }
    if (empty($startat)) {
        $startat = 0;
    }
    if (empty($stopat)) {
        $stopat = 0;
    }
    if (empty($isinline)) {
        $isinline = "false";
    }
    if (empty($elementselector)) {
        $elementselector = null;
    }
    if (empty($ratio)) {
        $ratio = "auto";
    }
    if (empty($showcontrols)) {
        $showcontrols = "true";
    }
    if (empty($printurl)) {
        $printurl = "true";
    }
    if (empty($opacity)) {
        $opacity = "1";
    }
    if (empty($mute)) {
        $mute = "false";
    }
    if (empty($loop)) {
        $loop = "false";
    }
    if (empty($quality)) {
        $quality = "default";
    }
    if (empty($addraster)) {
        $addraster = "false";
    };
    if (empty($stopmovieonblur)) {
        $stopmovieonblur = "false";
    };
    if (empty($gaTrack)) {
        $gaTrack = "false";
    };
    if (empty($realfullscreen)) {
        $realfullscreen = "true";
    };
    if (empty($autoplay)) {
        $autoplay = "false";
    };
    if (empty($volume)) {
        $volume = "50";
    };
    if ($isinline == "true") {
        if (empty($playerwidth)) {
            $playerwidth = "300";
        };
        if (empty($playerheight)) {
            $playerheight = "220";
        };

        $unitWidth = strrpos($playerwidth, "%") ? "" : "px";
        $unitHeight = strrpos($playerheight, "%") ? "" : "px";

        $startat = $startat > 0 ? $startat : 1;

        $elId = "self";
        $style = " style=\"width:" . $playerwidth . $unitWidth . "; height:" . $playerheight . $unitHeight . "; position:relative\"";
    };

    if ($elementselector != null) {
        $elId = $elementselector;
    }

    if ($opacity > 1)
        $opacity = $opacity / 10;

    /**
     * If multiple URL are inserted than choose one randomly
     * */
    $vids = explode(',', $url);
    $n = rand(0, count($vids) - 1);
    $mbYTPlayer_home_video_url_revised = $vids[$n];

    $player_id = $custom_id ? $custom_id : 'playerVideo' . $i;

    $mbYTPlayer_player_shortcode = "";

    if($mbYTPlayer_is_active)
        $mbYTPlayer_player_shortcode = $ytp_xxx
            ? '<div id="' . $player_id . '" ' . $style . ' class="mbYTPMovie' . ($isinline ? " inline_YTPlayer" : "") . '" data-property="{videoURL:\'' . $mbYTPlayer_home_video_url_revised . '\', mobileFallbackImage:\''. $fallback_image .'\', opacity:' . $opacity . ', autoPlay:' . $autoplay . ', containment:\'' . $elId . '\', startAt:' . $startat . ', stopAt:' . $stopat . ', mute:' . $mute . ', vol:' . $volume . ', optimizeDisplay:true, showControls:' . $showcontrols . ', printUrl:' . $printurl . ', loop:' . $loop . ', addRaster:' . $addraster . ', quality:\'' . $quality . '\', realfullscreen:' . $realfullscreen . ', ratio:\'' . $ratio . '\', gaTrack:' . $gaTrack . ', stopMovieOnBlur:' . $stopmovieonblur . '}"></div>'
            : '<div class="ytp_alert">' . __('<h3>[YTPlayer short code]</h3><p>You need a <b>license key</b> to display a <b>YTPlayer video</b> using the shortcode.<br> Go to the <b>YTPlayer settings page</b> to get your license</p>', 'wpmbytplayer') . '</div>';

    $i++; //increment static variable for unique player IDs

    return $mbYTPlayer_player_shortcode;
}

/**
 * Water-mark
 */
add_action('wp_head', 'mbytpplus_custom_js');
function mbytpplus_custom_js() {
    global $ytp_xxx;

    if ($ytp_xxx)
        return;

    if (!wp_script_is('jquery', 'done')) {
        wp_enqueue_script('jquery');
    }
    $script = 'jQuery(function(){
      var class_name = null;
     setInterval (function(){
          var ytp_videos = jQuery(".YTPOverlay");
          ytp_videos.each(function(){
          var ytp_video = jQuery(this);
          jQuery("[class*=ytp_wm_]", ytp_video).remove();
          class_name = "ytp_wm_" + Math.floor(Math.random()*100000);
          var $wm = jQuery("<img/>").attr("src","data:image/png;base64,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");
          ytp_wm = jQuery("<div/>").addClass(class_name).html($wm);
          $wm.attr("style", "filter:none!important;-webkit-transform:none!important;transform:none!important;padding:0!important;margin:0!important;height:100%!important; width:auto!important;display:block!important;visibility:visible!important;top:0!important;right:0!important;opacity:1!important;position:absolute!important;margin:auto!important;z-index:10000!important;")
          ytp_wm.attr("style", "filter:none!important;-webkit-transform:none!important;transform:none!important;padding:0!important;margin:0!important;display:block!important;position:absolute!important;top:0!important;bottom:0!important;right:0!important;margin:auto!important;z-index:10000!important;width:100%!important;height:100%!important;max-height:450px!important;");
          ytp_video.prepend(ytp_wm);
          })
        }, 5000);
  });';
    echo "<script>".$script."</script>";
}
