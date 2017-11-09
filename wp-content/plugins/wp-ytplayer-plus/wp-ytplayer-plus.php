<?php
/*
Plugin Name: mb.YTPlayer PLUS for background videos
Plugin URI: http://pupunzi.com/#mb.components/mb.YTPlayer/YTPlayer.html
Description: Play a Youtube video as background of your page. Go to <strong>mb.ideas > mb.YTPlayer plus</strong> to activate the background video option for your homepage. Or use the short code following the reference in the settings panel.
Author: Pupunzi (Matteo Bicocchi)
Version: 3.1.2
Author URI: http://pupunzi.com
Text Domain: wpmbytplayer
*/

define("MBYTPLAYER_PLUS_VERSION", "3.1.2");
define("MBYTPLAYER_PREFIX", "YTPL");

$ytppro = true;
$ytp_base = __FILE__;

register_activation_hook(__FILE__, 'mbytpplus_install');
function mbytpplus_install()
{
// add and update our default options upon activation
    add_option('mbYTPlayer_version', MBYTPLAYER_PLUS_VERSION);
    add_option('mbYTPlayer_is_active', 'true');

    add_option('mbYTPlayer_video_url', '');
    add_option('mbYTPlayer_show_controls', 'false');
    add_option('mbYTPlayer_show_videourl', 'false');
    add_option('mbYTPlayer_video_page', 'static');
    add_option('mbYTPlayer_audio_volume', '50');
    add_option('mbYTPlayer_mute', 'true');
    add_option('mbYTPlayer_start_at', '0');
    add_option('mbYTPlayer_stop_at', '0');
    add_option('mbYTPlayer_ratio', '16/9');
    add_option('mbYTPlayer_loop', 'true');
    add_option('mbYTPlayer_opacity', '10');
    add_option('mbYTPlayer_quality', 'default');
    add_option('mbYTPlayer_add_raster', 'false');
    add_option('mbYTPlayer_stop_on_blur', 'false');
    add_option('mbYTPlayer_track_ga', 'false');
    add_option('mbYTPlayer_realfullscreen', 'true');
    add_option('mbYTPlayer_fallbackimage', null);
    add_option('mbYTPlayer_custom_id', null);

    //license key
    add_option('mbYTPlayer_license_key', '');
}


$mbYTPlayer_version = MBYTPLAYER_PLUS_VERSION;
$mbYTPlayer_is_active = get_option('mbYTPlayer_is_active');
$mbYTPlayer_video_url = get_option('mbYTPlayer_video_url');
$mbYTPlayer_video_page = get_option('mbYTPlayer_video_page');
$mbYTPlayer_show_controls = get_option('mbYTPlayer_show_controls');
$mbYTPlayer_show_videourl = get_option('mbYTPlayer_show_videourl');
$mbYTPlayer_ratio = get_option('mbYTPlayer_ratio');
$mbYTPlayer_audio_volume = get_option('mbYTPlayer_audio_volume');
$mbYTPlayer_mute = get_option('mbYTPlayer_mute');
$mbYTPlayer_start_at = get_option('mbYTPlayer_start_at');
$mbYTPlayer_stop_at = get_option('mbYTPlayer_stop_at');
$mbYTPlayer_loop = get_option('mbYTPlayer_loop');
$mbYTPlayer_opacity = get_option('mbYTPlayer_opacity');
$mbYTPlayer_quality = get_option('mbYTPlayer_quality');
$mbYTPlayer_add_raster = get_option('mbYTPlayer_add_raster');
$mbYTPlayer_realfullscreen = get_option('mbYTPlayer_realfullscreen');
$mbYTPlayer_fallbackimage = get_option('mbYTPlayer_fallbackimage');
$mbYTPlayer_stop_on_blur = get_option('mbYTPlayer_stop_on_blur');
$mbYTPlayer_track_ga = get_option('mbYTPlayer_track_ga');
$mbYTPlayer_custom_id = get_option('mbYTPlayer_custom_id');

/**
 * license key
 */
$mbYTPlayer_license_key = get_option('mbYTPlayer_license_key');

/**
 * @Deprecated
 */
if(empty($mbYTPlayer_video_url)) {
    $mbYTPlayer_is_active = get_option('mbYTPlayer_Home_is_active');
    delete_option('mbYTPlayer_Home_is_active', false);
    $mbYTPlayer_video_url = get_option('mbYTPlayer_home_video_url');
    delete_option('mbYTPlayer_Home_is_active', '');
    $mbYTPlayer_video_page = get_option('mbYTPlayer_home_video_page');
    delete_option('mbYTPlayer_home_video_page', '');
}

/**
 * set up defaults if these fields are empty
 */
if (empty($mbYTPlayer_custom_id)) {
    $mbYTPlayer_custom_id = "YTPlayer_" . rand();
}
if (empty($mbYTPlayer_is_active)) {
    $mbYTPlayer_is_active = false;
}
if (empty($mbYTPlayer_show_controls)) {
    $mbYTPlayer_show_controls = "false";
}
if (empty($mbYTPlayer_show_videourl)) {
    $mbYTPlayer_show_videourl = "false";
}
if (empty($mbYTPlayer_ratio)) {
    $mbYTPlayer_ratio = "16/9";
}
if (empty($mbYTPlayer_audio_volume)) {
    $mbYTPlayer_audio_volume = "50";
}
if (empty($mbYTPlayer_mute)) {
    $mbYTPlayer_mute = "false";
}
if (empty($mbYTPlayer_start_at)) {
    $mbYTPlayer_start_at = 0;
}
if (empty($mbYTPlayer_stop_at)) {
    $mbYTPlayer_stop_at = 0;
}
if (empty($mbYTPlayer_loop)) {
    $mbYTPlayer_loop = "false";
}
if (empty($mbYTPlayer_opacity)) {
    $mbYTPlayer_opacity = "10";
}
if (empty($mbYTPlayer_quality)) {
    $mbYTPlayer_quality = "default";
}
if (empty($mbYTPlayer_add_raster)) {
    $mbYTPlayer_add_raster = "false";
}
if (empty($mbYTPlayer_track_ga)) {
    $mbYTPlayer_track_ga = "false";
}
if (empty($mbYTPlayer_stop_on_blur)) {
    $mbYTPlayer_stop_on_blur = "false";
}
if (empty($mbYTPlayer_realfullscreen)) {
    $mbYTPlayer_realfullscreen = "false";
}
if (empty($mbYTPlayer_fallbackimage)) {
    $mbYTPlayer_fallbackimage = null;
}
if (empty($mbYTPlayer_video_page)) {
    $mbYTPlayer_video_page = "static";
}

require_once("inc/mb_core.php");

register_activation_hook(__FILE__, 'mbytpplus_verify_lic');
function mbytpplus_verify_lic(){
    $y = new ytp_mb_core("YTPL", get_option('mbYTPlayer_license_key'), plugin_dir_path( __FILE__ ));
    $y->get_lic_from_server();
}

require_once('inc/extra.php');

if(!function_exists("is_edit_page")){

    function is_edit_page($new_edit = null){
        global $pagenow;
        //make sure we are on the backend
        if (!is_admin()) return false;

        if($new_edit == "edit")
            return in_array( $pagenow, array( 'post.php',  ) );
        elseif($new_edit == "new") //check for new post page
            return in_array( $pagenow, array( 'post-new.php' ) );
        else //check for either new or edit
            return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
    }
}

if($ytp_xxx && is_edit_page())
    require_once("inc/popup.php");

$link = "https://pupunzi.com/wpPlus/go-plus.php?locale=".get_locale()."&plugin_prefix=YTPL&plugin_version=".MBYTPLAYER_PLUS_VERSION."&lic_domain=".$lic_domain."&lic_theme=".get_template() . "&php=" . phpversion();

/**
 * ADD ADMIN NOTICE
 */

if (version_compare(phpversion(), '5.5.0', '>')) {
    require('inc/mb_notice/notice.php');
    $ytp_notice = new mb_notice('mbytpplus_1', plugin_basename( __FILE__ ));
    //$ytp_notice->reset_notice();

    $ytp_message = '<b>WP-YTPLAYER 3 PLUS</b>: <br> From this version some settings name have been changed; you should go to the settings page ad save your options again to be sure you don\'t loose them with the next update.';

    // if(!$mbYTPlayer_license_key)
    $ytp_notice->add_notice($ytp_message, 'success');

}


add_filter('plugin_action_links', 'mbytpplus_action_links', 10, 2);
function mbytpplus_action_links($links, $file)
{
    if ($file == plugin_basename(__FILE__)) {
        // The anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wp-ytplayer-plus/wp-ytplayer-plus.php">Settings</a>';
        // Add the link to the list
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_action('wp_enqueue_scripts', 'mbytpplus_init');
function mbytpplus_init()
{
    global $mbYTPlayer_version;

    if (!is_admin()) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('mb.YTPlayer', plugins_url('/js/jquery.mb.YTPlayer.min.js', __FILE__), array('jquery'), $mbYTPlayer_version, true, 1000);
        wp_enqueue_style('mb.YTPlayer_css', plugins_url('/css/mb.YTPlayer.css', __FILE__), array(), $mbYTPlayer_version, 'screen');
    }
}

add_action('plugins_loaded', 'mbytpplus_localize');
function mbytpplus_localize()
{
    load_plugin_textdomain( 'wpmbytplayer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

// scripts to load in the footer
add_action('wp_footer', 'mbytpplus_player_foot', 20);
function mbytpplus_player_foot()
{
    global $mbYTPlayer_video_url, $mbYTPlayer_fallbackimage, $mbYTPlayer_show_controls, $mbYTPlayer_ratio, $mbYTPlayer_show_videourl, $mbYTPlayer_start_at, $mbYTPlayer_stop_at, $mbYTPlayer_mute, $mbYTPlayer_loop, $mbYTPlayer_opacity, $mbYTPlayer_quality, $mbYTPlayer_add_raster, $mbYTPlayer_track_ga, $mbYTPlayer_realfullscreen, $mbYTPlayer_stop_on_blur, $mbYTPlayer_video_page, $mbYTPlayer_is_active, $mbYTPlayer_audio_volume, $mbYTPlayer_css_plus, $mbYTPlayer_custom_id;
    $mbYTPlayer_css_plus = "";
    echo '
    
	<!-- START mbYTPlayer -->
	' . $mbYTPlayer_css_plus . '
	<script type="text/javascript">

    function onYouTubePlayerAPIReady() {
    	if(ytp.YTAPIReady)
		    return;
	    ytp.YTAPIReady=true;
	    jQuery(document).trigger("YTAPIReady");
    }

    jQuery.mbYTPlayer.rasterImg ="' . plugins_url('/images/', __FILE__) . 'raster.png";
	  jQuery.mbYTPlayer.rasterImgRetina ="' . plugins_url('/images/', __FILE__) . 'raster@2x.png";

	  jQuery(function(){
        jQuery(".mbYTPMovie").YTPlayer()
	  });

	</script>
	<!-- END mbYTPlayer -->
	
	';

    $canShowMovie = is_front_page() && !is_home();
    if ($mbYTPlayer_video_page == "blogindex")
        $canShowMovie = is_home(); // the blog index page;
    else if ($mbYTPlayer_video_page == "both")
        $canShowMovie = is_front_page() || is_home();
    else if ($mbYTPlayer_video_page == "all")
        $canShowMovie = true; // on all pages;

    if ($canShowMovie && $mbYTPlayer_is_active) {

        if (empty($mbYTPlayer_video_url))
            return false;

        if($mbYTPlayer_opacity > 1)
            $mbYTPlayer_opacity = $mbYTPlayer_opacity/10;

        $vids = explode(',', $mbYTPlayer_video_url);
        $n = rand(0, count($vids) - 1);
        $mbYTPlayer_video_url_revised = $vids[$n];

        $mbYTPlayer_start_at = $mbYTPlayer_start_at > 0 ? $mbYTPlayer_start_at : 1;

        $player_id = $mbYTPlayer_custom_id ? $mbYTPlayer_custom_id : "bgndVideo_home";

        $mbYTPlayer_player_homevideo =
            '<div id=\"' . $player_id . '\" data-property=\"{videoURL:\'' . $mbYTPlayer_video_url_revised . '\', mobileFallbackImage:\'' . $mbYTPlayer_fallbackimage . '\', opacity:' . $mbYTPlayer_opacity . ', autoPlay:true, containment:\'body\', startAt:' . $mbYTPlayer_start_at . ', stopAt:' . $mbYTPlayer_stop_at . ', mute:' . $mbYTPlayer_mute . ', vol:' . $mbYTPlayer_audio_volume . ', optimizeDisplay:true, showControls:' . $mbYTPlayer_show_controls . ', printUrl:' . $mbYTPlayer_show_videourl . ', loop:' . $mbYTPlayer_loop . ', addRaster:' . $mbYTPlayer_add_raster . ', quality:\'' . $mbYTPlayer_quality . '\', ratio:\'' . $mbYTPlayer_ratio . '\', realfullscreen:' . $mbYTPlayer_realfullscreen . ', gaTrack:' . $mbYTPlayer_track_ga . ', stopMovieOnBlur:' . $mbYTPlayer_stop_on_blur . '}\"></div>';
        echo '
        
	<!-- START - mbYTPlayer video -->
	<script type="text/javascript">

	  jQuery(function(){
	      var homevideo = "' . $mbYTPlayer_player_homevideo . '";
	      jQuery("body").prepend(homevideo);
	      jQuery("#'.$player_id.'").YTPlayer();
    });

	</script>
	<!-- END - mbYTPlayer video -->
	
  ';
    }
};

/**
 * Add root menu
 */
require("inc/mb-admin-menu.php");

add_action('admin_menu', 'mbytpplus_add_option_page');
function mbytpplus_add_option_page()
{
    add_submenu_page('mb-ideas-menu', 'YTPlayerPlus', 'YTPlayerPlus', 'manage_options', __FILE__, 'mbytpplus_options_page');
}

add_action('admin_init', 'mbytpplus_register_settings');
function mbytpplus_register_settings()
{
    //register YTPlayer settings
    register_setting('YTPlayer-settings-group', 'mbYTPlayer_version');

    register_setting('YTPlayer-settings-group', 'mbYTPlayer_is_active');
    register_setting('YTPlayer-settings-group', 'mbYTPlayer_video_url');
    register_setting('YTPlayer-settings-group', 'mbYTPlayer_video_page');

    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_show_controls');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_show_videourl');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_start_at');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_stop_at');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_audio_volume');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_mute');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_ratio');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_loop');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_opacity');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_quality');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_add_raster');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_track_ga');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_realfullscreen');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_fallbackimage');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_stop_on_blur');
    register_setting('YTPlayer-PLUS-group', 'mbYTPlayer_custom_id');

    register_setting('YTPlayer-license-group', 'mbYTPlayer_license_key');
}

add_action('wp_ajax_mbytppro_activate', 'mbytppro_activate');
function mbytppro_activate()
{
    $activate = $_POST["activate"] ? 1 : 0;
    update_option('mbYTPlayer_is_active', $activate);
    echo json_encode(array("resp"=>$activate));
}

function mbytpplus_options_page()
{ // Output the options page
    global $price, $lic_domain, $link, $ytp_xxx, $ytp_core,$mbYTPlayer_custom_id;
    $lic = $ytp_core->readLic();
    ?>
    <div class="wrap">
    <a href="http://pupunzi.com"><img style=" width: 350px" src="<?php echo plugins_url('images/logo.png', __FILE__); ?>" alt="Made by Pupunzi"/></a>
    <h2><?php _e('mb.YTPlayer <strong>PLUS</strong>', 'wpmbytplayer'); ?></h2>
    <img style=" width: 150px; position: absolute; right: 0; top: 0; z-index: 100" src="<?php echo plugins_url('images/YTPL.svg', __FILE__); ?>" alt="mb.YTPlayer icon"/>
    <?php
    $mbYTPlayer_key = esc_attr(get_option('mbYTPlayer_license_key'));
    ?>

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
License form box
---------------------------—---------------------------—---------------------------—---------------------------— -->
    <div id="getLic" class="box box-success" style="display: <?php echo empty($mbYTPlayer_key) || !$ytp_xxx ? 'block' : 'none' ?>">
        <h3><?php _e('Get your <strong>Plus</strong> license to activate all the <strong>mb.YTPlayer</strong> features!', 'wpmbytplayer'); ?></h3>
        <?php _e('You need a <b>license key</b> to remove the <b>watermark</b> from the video and to enable the <b>mb.YTPlayer shortcode editor</b> for any page of your site.', 'wpmbytplayer'); ?>

        <form id="YTP-license-form" method="post" action="options.php" style="margin-top: 20px">
            <?php settings_fields('YTPlayer-license-group'); ?>
            <?php do_settings_sections('YTPlayer-license-group'); ?>

            <a target="_blank" href="<?php echo $link ?>" class="getKey">
                <span><?php printf(__('Get your Key For <b>%s EUR</b> Only', 'wpmbytplayer'), $price) ?></span>
            </a>
            <hr>
            <label for="mbYTPlayer_license_key"><?php echo _e('<strong>Have a key?</strong> Paste it here:', 'wpmbytplayer') ?></label><br>
            <input type="text" id="mbYTPlayer_license_key" name="mbYTPlayer_license_key" value="<?php echo $mbYTPlayer_key ?>" style="width:100%; max-width: 450px; padding: 10px; font-size: 200%" placeholder="<?php _e('Your license key', 'wpmbytplayer'); ?>"/>
            <br>
            <div id="invalid_lic" style="display: <?php echo (!empty($mbYTPlayer_key) && !$ytp_xxx ? "block" : "none") ?>; color: darkred">
                <p class="invalid">
                    <?php printf(__('This license seems not valid.<br>The license domain is <strong id="invalid_lic_domain">%s</strong> while your domain is <strong>%s</strong>.<br>Try to validate it again or change the associated domain.', 'wpthumbgallery'), ($lic["lic_domain"] ? $lic["lic_domain"] : "null" ), $lic_domain) ?>
                </p>
                <a href="javascript:void(0)" onclick="jQuery(this).fadeOut(); change_domain('<?php echo $mbYTPlayer_key ?>', '<?php echo $lic_domain ?>')"><?php echo _e("change associated domain", "wpthumbgallery") ?></a><br>
                <span class="message" style="display: none"></span>
            </div>
            <br>

            <div id="license-save-bar">
                <span class="message" style="display: none"></span>
                <input  type="submit" value="<?php !empty($mbYTPlayer_key) && !$ytp_xxx ? _e('Validate', 'wpmbytplayer') : _e('Activate', 'wpmbytplayer') ?>" class="button right""></div>
            <br style="clear: both">
        </form>
    </div>

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
 Default settings box
 ---------------------------—---------------------------—---------------------------—---------------------------— -->
    <form class="optForm" id="optionsForm" method="post" action="options.php">
        <h3><?php _e('Background video settings', 'wpmbytplayer'); ?></h3>
        <?php settings_fields('YTPlayer-settings-group'); ?>
        <?php do_settings_sections('YTPlayer-settings-group'); ?>

        <table class="form-table">

            <tr valign="top">
                <th scope="row"><?php _e('activate the background video', 'wpmbytplayer'); ?></th>
                <td>
                    <div class="onoffswitch">
                        <input class="onoffswitch-checkbox" type="checkbox" id="mbYTPlayer_is_active"
                               name="mbYTPlayer_is_active" value="true" <?php if (get_option('mbYTPlayer_is_active')) {
                            echo ' checked="checked"';
                        } ?>/> <label class="onoffswitch-label" for="mbYTPlayer_is_active"></label>
                    </div>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"> <?php _e('The Youtube video url is:', 'wpmbytplayer'); ?></th>
                <td>
                    <?php
                    $ytpl_video_url = get_option('mbYTPlayer_video_url');
                    $vids = explode(',', $ytpl_video_url);
                    $n = count($vids);
                    $n = $n > 2 ? 2 : $n;
                    $w = (480/$n) - ($n>1 ? (3*$n) : 0);
                    $h = 315/$n;
                    foreach ($vids as $vurl) {
                        $YouTubeCheck = preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $vurl, $matches);
                        if ($YouTubeCheck) {
                            $ytvideoId = $matches[0];
                            ?>
                        <iframe width="<?php echo $w ?>" height="<?php echo $h ?>" style="display: inline-block" src="https://www.youtube.com/embed/<?php echo $ytvideoId ?>?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe><?php
                        }
                    }?>
                    <textarea name="mbYTPlayer_video_url" style="width:100%" value="<?php echo esc_attr(get_option('mbYTPlayer_video_url')); ?>"><?php echo esc_attr(get_option('mbYTPlayer_video_url')); ?></textarea>
                    <p><?php _e('Copy and paste here the URL of the Youtube video you want as your homepage background. If you add more then one URL comma separated it will be chosen one randomly each time you reach the page', 'wpmbytplayer'); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('The page where to show the background video is:', 'wpmbytplayer'); ?></th>
                <td>
                    <input type="radio" name="mbYTPlayer_video_page" value="static" <?php if (get_option('mbYTPlayer_video_page') == "static" || get_option('mbYTPlayer_video_page') == "") { echo ' checked';} ?> /> <?php _e('Static Homepage', 'wpmbytplayer'); ?><br>
                    <input type="radio" name="mbYTPlayer_video_page" value="blogindex" <?php if (get_option('mbYTPlayer_video_page') == "blogindex") { echo ' checked';} ?>/> <?php _e('Blog index Homepage', 'wpmbytplayer'); ?> <br>
                    <input type="radio" name="mbYTPlayer_video_page" value="both" <?php if (get_option('mbYTPlayer_video_page') == "both") {  echo ' checked';} ?>/><?php _e('Both', 'wpmbytplayer'); ?>  <br>
                    <input type="radio" name="mbYTPlayer_video_page" value="all" <?php if (get_option('mbYTPlayer_video_page') == "all") {  echo ' checked';} ?>/><?php _e('All pages', 'wpmbytplayer'); ?>  <br>
                    <p><?php _e('Choose on which page you want the background video to be shown. If you check "All" you\'ll not be able to insert a page background video using the short-code', 'wpmbytplayer'); ?></p>
                </td>
            </tr>

        </table>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save options') ?>"/>
        </p>

    </form>

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
  PLUS settings box
  ---------------------------—---------------------------—---------------------------—---------------------------— -->
    <form class="optForm" id="PROForm" method="post" action="options.php" style="margin-top: 20px; opacity: <?php echo empty($mbYTPlayer_key) || !$ytp_xxx ? '0.6' : '1' ?>"">
    <h3><?php _e('Advanced settings', 'wpmbytplayer'); ?></h3>

    <?php settings_fields('YTPlayer-PLUS-group'); ?>
    <?php do_settings_sections('YTPlayer-PLUS-group'); ?>

    <p style="display:  <?php echo empty($mbYTPlayer_key) || !$ytp_xxx ? 'block' : 'none' ?>"> <?php _e('Activate the <strong>PLUS</strong> license to get all the features.', 'wpmbytplayer'); ?> <a href="<?php echo $link ?>" target="_blank"><?php echo _e("Get it now","wpmbytplayer")?></a> </p>

    <table class="form-table">

        <tr valign="top">
            <th scope="row"><?php _e('Custom ID:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="text" name="mbYTPlayer_custom_id" style="width: 50%" value="<?php echo $mbYTPlayer_custom_id; ?>"/>
                <p><?php _e('Set a custom ID (must be unique) you can refer to with the <a href="https://github.com/pupunzi/jquery.mb.YTPlayer/wiki#external-methods" target="_blank">API</a>', 'wpmbytplayer'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Fallback image url:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="text" name="mbYTPlayer_fallbackimage" style="width: 100%" value="<?php echo esc_attr(get_option('mbYTPlayer_fallbackimage') ); ?>"/>
                <p><?php _e('Set the background image url to be used on mobile devices', 'wpmbytplayer'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Set the opacity:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="text" name="mbYTPlayer_opacity" style="width:10%"value="<?php echo esc_attr(get_option('mbYTPlayer_opacity') ); ?>"/>
                <p><?php _e('Set the opacity of the background video (from 0 to 10)', 'wpmbytplayer'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Set the quality:', 'wpmbytplayer'); ?></th>
            <td>
                <select name="mbYTPlayer_quality">
                    <option value="default" <?php if (get_option('mbYTPlayer_quality') == "default") {echo ' selected';} ?> ><?php _e('default', 'wpmbytplayer'); ?></option>
                    <option value="small" <?php if (get_option('mbYTPlayer_quality') == "small") {echo ' selected';} ?> ><?php _e('small', 'wpmbytplayer'); ?></option>
                    <option value="medium" <?php if (get_option('mbYTPlayer_quality') == "medium") {echo ' selected';} ?> ><?php _e('medium', 'wpmbytplayer'); ?></option>
                    <option value="large" <?php if (get_option('mbYTPlayer_quality') == "large") {echo ' selected';} ?> ><?php _e('large', 'wpmbytplayer'); ?></option>
                    <option value="hd720" <?php if (get_option('mbYTPlayer_quality') == "hd720") {echo ' selected';} ?> ><?php _e('hd720', 'wpmbytplayer'); ?></option>
                    <option value="hd1080" <?php if (get_option('mbYTPlayer_quality') == "hd1080") {echo ' selected';} ?> ><?php _e('hd1080', 'wpmbytplayer'); ?></option>
                    <option value="highres" <?php if (get_option('mbYTPlayer_quality') == "highres") {echo ' selected';} ?> ><?php _e('highres', 'wpmbytplayer'); ?></option>
                </select>

                <p><?php _e('Set the quality of the background video ("default" YouTube selects the appropriate playback quality)', 'wpmbytplayer'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Set the aspect ratio:', 'wpmbytplayer'); ?></th>
            <td>
                <select name="mbYTPlayer_ratio">
                    <option value="auto" <?php if (get_option('mbYTPlayer_ratio') == "auto") {echo ' selected';} ?> ><?php _e('auto', 'wpmbytplayer'); ?></option>
                    <option value="4/3" <?php if (get_option('mbYTPlayer_ratio') == "4/3") {echo ' selected';} ?> ><?php _e('4/3', 'wpmbytplayer'); ?></option>
                    <option value="16/9" <?php if (get_option('mbYTPlayer_ratio') == "16/9") {echo ' selected';} ?>><?php _e('16/9', 'wpmbytplayer'); ?></option>
                </select>

                <p><?php _e('Set the aspect-ratio of the background video. If "auto" the plug in will try to retrieve the aspect ratio from Youtube. If you have problems on viewing the background video try setting this manually.', 'wpmbytplayer'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('The video should start at:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="text" name="mbYTPlayer_start_at" style="width:10%" value="<?php echo esc_attr(get_option('mbYTPlayer_start_at')); ?>"/>
                <p><?php _e('Set the seconds the video should start at', 'wpmbytplayer'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('The video should stop at:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="text" name="mbYTPlayer_stop_at" style="width:10%"value="<?php echo esc_attr(get_option('mbYTPlayer_stop_at')); ?>"/>
                <p><?php _e('Set the seconds the video should stop at', 'wpmbytplayer'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Show the control bar:', 'wpmbytplayer'); ?></th>
            <td>
                <input id="mbYTPlayer_show_controls" onclick="videoUrlControl()" type="checkbox" name="mbYTPlayer_show_controls" value="true" <?php if (get_option('mbYTPlayer_show_controls') == "true") {echo ' checked="checked"';} ?>/>
                <label for="mbYTPlayer_show_controls"><?php _e('Check to show controls at the bottom of the page', 'wpmbytplayer'); ?></label>
                <div id="videourl" style="display: none; margin-top: 10px">
                    <input id="mbYTPlayer_show_videourl" type="checkbox" name="mbYTPlayer_show_videourl" value="true" <?php if (get_option('mbYTPlayer_show_videourl') == "true") {echo ' checked="checked"';} ?>/>
                    <label for="mbYTPlayer_show_videourl"><?php _e('Check to show the link to the original YouTube® video', 'wpmbytplayer'); ?></label>
                </div>
                <script>
                    function videoUrlControl() {
                        if (jQuery("#mbYTPlayer_show_controls").is(":checked")) {
                            jQuery("#videourl").show();
                        } else {
                            jQuery("#mbYTPlayer_show_videourl").attr("checked", false).val(false);
                            jQuery("#videourl").hide();
                        }
                    }
                    videoUrlControl();
                </script>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('The full screen behavior is:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="radio" id="mbYTPlayer_realfullscreen" name="mbYTPlayer_realfullscreen" value="true" <?php if (get_option('mbYTPlayer_realfullscreen') == "true") {echo ' checked="checked"';} ?>/>
                <label for="mbYTPlayer_realfullscreen"><?php _e('Full screen containment is the screen', 'wpmbytplayer'); ?></label>

                <div style=" margin-top: 10px">
                    <input type="radio" id="mbYTPlayer_browserfullscreen" name="mbYTPlayer_realfullscreen"
                           value="false" <?php if (get_option('mbYTPlayer_realfullscreen') == "false") {
                        echo ' checked="checked"';
                    } ?>/>
                    <label
                        for="mbYTPlayer_realfullscreen"><?php _e('Full screen containment is the browser window', 'wpmbytplayer'); ?></label>
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Set the audio volume:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="text" name="mbYTPlayer_audio_volume"
                       value="<?php echo esc_attr(get_option('mbYTPlayer_audio_volume')) ?>" style="width:10%"/>

                <p><?php _e('Set the volume for the video (from 0 to 100)', 'wpmbytplayer'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Mute the video:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="checkbox" id="mbYTPlayer_mute" name="mbYTPlayer_mute"
                       value="true" <?php if (get_option('mbYTPlayer_mute') == "true") {
                    echo ' checked="checked"';
                } ?>/>
                <label
                    for="mbYTPlayer_mute"><?php _e('Check to mute the audio of the video', 'wpmbytplayer'); ?></label>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('The video should loop:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="checkbox" id="mbYTPlayer_loop" name="mbYTPlayer_loop"
                       value="true" <?php if (get_option('mbYTPlayer_loop') == "true") {
                    echo ' checked="checked"';
                } ?>/>
                <label for="mbYTPlayer_loop"><?php _e('Check to loop the video once ended', 'wpmbytplayer'); ?></label>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Add the raster image:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="checkbox" id="mbYTPlayer_add_raster" name="mbYTPlayer_add_raster"
                       value="true" <?php if (get_option('mbYTPlayer_add_raster') == "true") {
                    echo ' checked="checked"';
                } ?>/>
                <label
                    for="mbYTPlayer_add_raster"><?php _e('Check to add a raster effect to the video', 'wpmbytplayer'); ?></label>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Track the video views on Google Analytics', 'wpmbytplayer'); ?></th>
            <td>
                <input type="checkbox" id="mbYTPlayer_track_ga" name="mbYTPlayer_track_ga"
                       value="true" <?php if (get_option('mbYTPlayer_track_ga') == "true") {
                    echo ' checked="checked"';
                } ?>/>
                <label
                    for="mbYTPlayer_track_ga"><?php _e('Check to track this video on Google Analytics if played', 'wpmbytplayer'); ?></label>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e('Pause the player on window blur:', 'wpmbytplayer'); ?></th>
            <td>
                <input type="checkbox" id="mbYTPlayer_stop_on_blur" name="mbYTPlayer_stop_on_blur"
                       value="true" <?php if (get_option('mbYTPlayer_stop_on_blur') == "true") {
                    echo ' checked="checked"';
                } ?>/>
                <label
                    for="mbYTPlayer_stop_on_blur"><?php _e('Check to pause the player once the window blur', 'wpmbytplayer'); ?></label>
            </td>
        </tr>

    </table>
    <p class="submit"><input type="submit" class="button<?php echo empty($mbYTPlayer_key) || !$ytp_xxx ? '' : '-primary' ?>" value="<?php _e('Save Advanced options') ?>" <?php echo !$ytp_xxx ? "disabled" : "" ?> /></p>

    <span class="message" style="display: none"></span>
    </form>
    <script>
        jQuery(function () {

            var activate = jQuery("#mbYTPlayer_is_active");
            activate.on("change", function () {
                var val = this.checked ? 1 : 0;

                jQuery.ajax({
                    type    : "post",
                    dataType: "json",
                    url     : ajaxurl,
                    data    : {action: "mbytppro_activate", activate: val},
                    success : function (resp) {}
                })

            })

        });

        jQuery('#PROForm').submit( function () {
            var msg_box = jQuery(".message", this);
            show_message(msg_box, "Saving advanced options", 3000, "warning" );
            var b =  jQuery(this).serialize();
            jQuery.post( 'options.php', b ).error(
                function() {
                    show_message(msg_box, "Error saving options", 3000, "error" );
                }).success( function() {
                    show_message(msg_box, "Options have been saved successfully", 3000, "success", function(){
                        jQuery("#optionsForm").submit();
                    } );
                });
            return false;
        });

    </script>

    </div>

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
  Right column
  ---------------------------—---------------------------—---------------------------—---------------------------— -->
    <div class="rightCol">
        <!-- ---------------------------—---------------------------—---------------------------—---------------------------
        License info box
        ---------------------------—---------------------------—---------------------------—---------------------------— -->
        <div id="validLic" class="box box-success"
             style="display: <?php echo !empty($mbYTPlayer_key) && $ytp_xxx ? 'block' : 'none' ?>">
            <h3><?php _e('Your license:', 'wpmbytplayer'); ?></h3>
            <?php _e('This copy of <strong>mb.YTPlayer Plus</strong> is registered.', 'wpmbytplayer'); ?>

            <?php

            if($mbYTPlayer_key){
                $registered_to = $lic["user_mail"];
                $lic_domain = $lic["lic_domain"];
                $lic_theme = $lic["lic_theme"];
            }

            ?>
            <div>
                <strong>REGISTERED TO</strong>: <span id="registered_to"><?php echo $registered_to ?></span><br>
                <?php if($lic["lic_type"] == "DEV") {?>
                    <strong>FOR THE THEME</strong>: <span id="lic_theme"><?php echo $lic_theme ?></span>
                <?php } else { ?>
                    <strong>FOR THE DOMAIN</strong>: <span id="lic_domain"><?php echo $lic_domain ?></span>
                <?php } ?>
                <br>
                <strong>KEY</strong>: <span id="lic_key" class="<?php echo (!empty($mbYTPlayer_key) && !$ytp_xxx) ? "invalid" : "valid" ?>"><?php echo $mbYTPlayer_key ?>
            </div>
        </div>

        <!-- ---------------------------—---------------------------—---------------------------—---------------------------
        ADVs box
        ---------------------------—---------------------------—---------------------------—---------------------------— -->
        <div id="ADVs" class="box"></div>

        <!-- ---------------------------—---------------------------—---------------------------—---------------------------
        Info box
        ---------------------------—---------------------------—---------------------------—---------------------------— -->
        <div class="box">
            <h3><?php _e('Thanks for purchasing <b>mb.YTPlayer Plus</b>!', 'wpmbytplayer'); ?></h3>
            <p>
                <?php printf(__('You\'re using mb.YTPlayer v. <b>%s</b>', 'wpmbytplayer'), MBYTPLAYER_PLUS_VERSION); ?>
                <br><?php _e('by', 'wpmbytplayer'); ?> <a href="http://pupunzi.com">mb.ideas (Pupunzi)</a>
            </p>
            <hr>
            <p>
                <?php _e('Don’t forget to follow me on twitter', 'wpmbytplayer'); ?>: <a href="https://twitter.com/pupunzi">@pupunzi</a><br>

                <?php _e('Visit Open lab site', 'wpmbytplayer'); ?>: <a href="http://open-lab.com">http://open-lab.com</a><br>

                <?php _e('Visit my site', 'wpmbytplayer'); ?>: <a href="http://pupunzi.com">http://pupunzi.com</a><br>
                <?php _e('Visit my blog', 'wpmbytplayer'); ?>: <a href="http://pupunzi.open-lab.com">http://pupunzi.open-lab.com</a><br><br>
                <?php _e('Need support', 'wpmbytplayer'); ?>? <a href="http://pupunzi.open-lab.com/wordpress-plug-in-support/">http://pupunzi.open-lab.com/support</a><br>
            <hr>
            <!-- Begin MailChimp Signup Form -->
            <form action="http://pupunzi.us6.list-manage2.com/subscribe/post?u=4346dc9633&amp;id=91a005172f"
                  method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"
                  target="_blank" novalidate>
                <label for="mce-EMAIL"><?php _e('Subscribe to my mailing list <br>to stay in touch', 'wpmbytplayer'); ?>:</label>
                <br>
                <br>
                <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL"
                       placeholder="<?php _e('your email address', 'wpmbytplayer'); ?>" required>
                <input type="submit" value="<?php _e('Subscribe', 'wpmbytplayer'); ?>" name="subscribe"
                       id="mc-embedded-subscribe" class="button">
            </form>
            <!--End mc_embed_signup-->
            <hr>

            <!--SHARE-->

            <div id="share" style="margin-top: 10px; min-height: 80px">
                <a href="https://twitter.com/share" class="twitter-share-button"
                   data-url="http://wordpress.org/extend/plugins/wpmbytplayer/"
                   data-text="I'm using the mb.YTPlayer WP plugin for background videos" data-via="pupunzi"
                   data-hashtags="HTML5,wordpress,plugin">Tweet</a>
                <script>!function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, "script", "twitter-wjs");</script>
                <div id="fb-root"></div>
                <script>(function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//connect.facebook.net/it_IT/all.js#xfbml=1";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                <div style="margin-top: 10px" class="fb-like"
                     data-href="http://wordpress.org/extend/plugins/wpmbytplayer/" data-send="false"
                     data-layout="button_count" data-width="450" data-show-faces="true" data-font="arial"></div>
            </div>
        </div>

    </div>
    <script>

        // Add ADVs
        jQuery.ajax({
            type    : "post",
            dataType: "html",
            url     : "https://pupunzi.com/wpPlus/advs.php",
            data    : {plugin: "YTPL"},
            success : function (resp) {
                jQuery("#ADVs").html(resp);
            }
        })

    </script>

<?php
}

/**
 * Auto update
 */
require 'inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://pupunzi.com/wpPlus/wp-plugins/YTPL/YTPL.json',
    __FILE__,
    'wp-ytplayer-plus'
);
