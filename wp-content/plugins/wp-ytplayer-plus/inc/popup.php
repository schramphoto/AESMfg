<?php

$ytpl_popup_id = "ytplayer-form";
// Only add ytplayer icon above posts and pages
add_action( 'admin_head', 'add_ytplayer_button' );
function add_ytplayer_button() {
    add_action( 'media_buttons', 'add_ytplayer_icon' );
    add_action( 'admin_footer', 'add_ytplayer_popup' );
}

// Add button above editor if not editing ytplayer
function add_ytplayer_icon() {
    global $ytpl_popup_id;
    echo '<style>
	#add-ytplayer .dashicons {
		color: #888;
		margin: 0 4px 0 0;
		vertical-align: text-top;
		height: 18px;
        width: 18px;

		background-image: url(/wp-content/plugins/wp-ytplayer-plus/images/ytplayerbutton.svg);
		background-repeat: no-repeat;
}
	#add-ytplayer {
		padding-left: 0.4em;
	}

	</style>
	<a id="add-ytplayer" class="button" title="' . __("Add YTPlayer", 'wpmbytplayer' ) . '" href="#" onclick="show_ytplayer_editor();">
		<div class="dashicons"></div>' . __("Add YTPlayer", "wpmbytplayer") . '</a>';
}

class mbytpplus_shortcode_replace
{
    function __construct() {
        if ( get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', array(&$this, 'add_mbytpplus_tinymce_plugin'));
        }
        add_filter('tiny_mce_before_init', array( &$this, 'add_mbytpplus_TinyMCE_css' ) );
    }
    //include the tinymce javascript plugin
    function add_mbytpplus_tinymce_plugin($plugin_array) {
        $plugin_array['wpytplayer'] =   plugins_url('ytp_short_code.js?_=' . MBYTPLAYER_PLUS_VERSION, __FILE__);
        return $plugin_array;
    }
    //include the css file to style the graphic that replaces the shortcode
    function add_mbytpplus_TinyMCE_css($in)
    {
        $in['content_css'] .= ",". plugins_url('ytp_short_code.css', __FILE__);;
        return $in;
    }
}
add_action("init", create_function('', 'new mbytpplus_shortcode_replace();'));

$custom_player_id = "YTPlayer_" . rand();

// Displays the lightbox popup to insert a YTPlayer shortcode to a post/page
function add_ytplayer_popup() {
    global $custom_player_id;
    ?>
    <div id="ytplayer-form" style="display: none;">
    <style>

        #ytplayer-form {
            position: fixed;
            width: 100%;
            min-width: 500px;
            height: 100%;
            top:0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            background: rgba(0,0,0,0.7);
            z-index: 10000;
            box-sizing: border-box;
            overflow: hidden;
        }

        #ytplayer-form header {
            position: absolute;
            background: #0073aa;
            color: #FFFFFF;
            height: 50px;
            box-sizing: border-box;
            margin: 0;
            top: 0;
            width: 100%;
            padding: 10px;
            box-shadow: 1px 4px 8px 0px rgba(0,0,0,0.3);
            z-index: 1000;
        }

        #ytplayer-form header h2 {
            color: #ffffff;
            margin: 0;
            line-height: 40px;
        }

        #ytplayer-form #editor {
            position: absolute;
            width: 50%;
            min-width: 700px;
            height: 90%;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            background: #FFFFFF;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            box-sizing: border-box;
        }

        #ytplayer-form #editor form {
            position: absolute;
            width: 100%;
            top: 50px;
            left: 0;
            height: calc(100% - 55px);
            overflow: auto;
            padding: 10px;
            box-sizing: border-box;
        }

        #ytplayer-form fieldset {
            font-size: 16px;
            border: none;
            font-family: inherit;
            font-family: Helvetica Neue, Arial, Helvetica, sans-serif;
        }

        #ytplayer-form fieldset span.label {
            display: inline-block;
            width: 45%;
            font-size: 100%;
            font-weight: 400;
            vertical-align: top;
        }

        #ytplayer-form fieldset div {
            margin: 0;
            padding: 9px!important;
            display: block;
            font-size: 16px;
            border-bottom: 1px dotted #cccccc;
        }

        #ytplayer-form input, textarea, select {
            font-size: 100%;
        }

        #ytplayer-form input[type=text], textarea {
            width: 54%;
        }

        #ytplayer-form .sub-set {
            background: #f3f3f3;
        }

        #ytplayer-form .media-modal-close .media-modal-icon:before {
            color: #FFFFFF;
        }

        #ytplayer-form .actions {
            text-align: right;
            padding: 10px;
            background: rgba(158, 158, 158, 0.19);
        }

        .help-inline {
            font-size: 16px;
            font-weight: 300;
            display: block;
            color: #999;
            padding-left: 0;
            margin: 5px 0;
        }

        .help-inline.inline {
            display: inline-block;
            font-weight: 400;
            padding-left: 10px;
        }

        #inlinePlayer, #controlBox{
            display: none;
            background: #fff;
            padding: 5px;
        }

    </style>

    <div id="editor">
        <header>
            <h2><?php _e('mb.YTPlayer short-code editor', 'wpmbytplayer'); ?></h2>
            <button onclick="hide_ytplayer_editor()" type="button" class="button-link media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close panel</span></span></button>
        </header>
        <form action="#" >
            <div class="actions">
                <input type="submit" value="Insert shortcode" class="button-primary"/>
            </div>

            <fieldset>
                <div>
                    <span class="label"><?php _e('Video url', 'wpmbytplayer'); ?> <span style="color:red">*</span>: </span>
                    <textarea type="text" name="url" ></textarea>
                    <span class="help-inline"><?php _e('YouTube video URLs (comma separated)', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Custom ID', 'wpmbytplayer'); ?>:</span>
                    <input type="text" name="custom_id" value="<?php echo $custom_player_id ?>">
                    <span class="help-inline"><?php _e('Set a custom ID (must be unique) you can refer to with the <a href="https://github.com/pupunzi/jquery.mb.YTPlayer/wiki#external-methods" target="_blank">API</a>', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Fallback image url', 'wpmbytplayer'); ?>:</span>
                    <input type="text" name="fallback_image" value="">
                    <span class="help-inline"><?php _e('Fallback background image url for mobiles', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Opacity', 'wpmbytplayer'); ?>:</span>

                    <input type="text" name="opacity" value="10" style="width: 60px">
                    <span class="help-inline"><?php _e('YouTube video opacity', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Quality', 'wpmbytplayer'); ?>:</span>
                    <select name="quality">
                        <option value="default" selected><?php _e('auto detect', 'wpmbytplayer'); ?></option>
                        <option value="small"><?php _e('small', 'wpmbytplayer'); ?></option>
                        <option value="medium"><?php _e('medium', 'wpmbytplayer'); ?></option>
                        <option value="large"><?php _e('large', 'wpmbytplayer'); ?></option>
                        <option value="hd720"><?php _e('hd720', 'wpmbytplayer'); ?></option>
                        <option value="hd1080"><?php _e('hd1080', 'wpmbytplayer'); ?></option>
                        <option value="highres"><?php _e('highres', 'wpmbytplayer'); ?></option>
                    </select>
                    <span class="help-inline"><?php _e('YouTube video quality', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Aspect ratio', 'wpmbytplayer'); ?>:</span>
                    <select name="ratio">
                        <option value="auto" selected="selected"><?php _e('auto detect', 'wpmbytplayer'); ?></option>
                        <option value="4/3"><?php _e('4/3', 'wpmbytplayer'); ?></option>
                        <option value="16/9"><?php _e('16/9', 'wpmbytplayer'); ?></option>
                    </select>
                    <span class="help-inline"><?php _e('YouTube video aspect ratio'); ?>.</span>
                    <span class="help-inline"> <?php _e('If "auto" the plug in will try to get it from Youtube', 'wpmbytplayer'); ?>.</span>
                </div>

                <div id="elementSelector">
                    <span class="label"><?php _e('Element selector', 'wpmbytplayer'); ?>:</span>
                    <input type="text" name="elementselector" value="" onchange="isElement()"/>
                    <span class="help-inline"><?php _e('If you want the player into a specific element set the ID or the CSS class of it here (by default is the BODY of the page)', 'wpmbytplayer'); ?></span>
                </div>

                <div id="inlinePlayer-checkbox">
                    <span class="label"><?php _e('Is inline', 'wpmbytplayer'); ?>: </span>
                    <input type="checkbox" name="isinline" value="true" onchange="isInline()" />
                    <span class="help-inline"><?php _e('Show the player inline', 'wpmbytplayer'); ?></span>
                </div>
                <div class="sub-set" id="inlinePlayer" style="display: none">
                    <span class="label"><?php _e('Player width', 'wpmbytplayer'); ?> *: </span>
                    <input type="text" name="playerwidth"  style="width: 60px" onblur="suggestedHeight()"/> px
                    <span class="help-inline"><?php _e('Set the width of the inline player', 'wpmbytplayer'); ?></span>
                    <span class="label"><?php _e('Aspect ratio', 'wpmbytplayer'); ?>:</span>
                    <select name="inLine_ratio" style="width: 60px" onchange="suggestedHeight()">
                        <option value="4/3"><?php _e('4/3', 'wpmbytplayer'); ?></option>
                        <option value="16/9"><?php _e('16/9', 'wpmbytplayer'); ?></option>
                    </select>
                    <span class="help-inline"><?php _e('To get the suggested height for the player', 'wpmbytplayer'); ?></span>
                    <span class="label"><?php _e('Player height', 'wpmbytplayer'); ?> *: </span>
                    <input type="text" name="playerheight"  style="width: 60px" /> px
                    <span class="help-inline"><?php _e('Set the height of the inline player', 'wpmbytplayer'); ?></span>
                    <span class="help-inline">* Add % to the unit if the width is set as percentage.</span>
                </div>

                <div>
                    <span class="label"><?php _e('Show controls', 'wpmbytplayer'); ?>:</span>
                    <input type="checkbox" name="showcontrols" value="true" onchange="showControlBox()"/>
                    <span class="help-inline"><?php _e('show controls for this player', 'wpmbytplayer'); ?></span>
                </div>
                <div class="sub-set"  id="controlBox" style="display: none">
                    <span class="label"><?php _e('Full screen', 'wpmbytplayer'); ?>:</span>
                    <input type="radio" name="realfullscreen" value="true" checked/>
                    <span class="help-inline inline"><?php _e('Full screen containment is the screen', 'wpmbytplayer'); ?></span>

                    <span class="label"></span>
                    <input type="radio" name="realfullscreen" value="false"/>
                    <span class="help-inline inline" ><?php _e('Full screen containment is the browser window', 'wpmbytplayer'); ?></span>
                    <br>
                    <br>
                    <span class="label"><?php _e('Show YouTube® link', 'wpmbytplayer'); ?></span>
                    <input type="checkbox" name="printurl" value="true" checked/>
                    <span class="help-inline"><?php _e('Show the link to the original YouTube® video', 'wpmbytplayer'); ?>.</span>
                </div>

                <div>
                    <span class="label"><?php _e('Autoplay', 'wpmbytplayer'); ?>: </span>
                    <input type="checkbox" name="autoplay" value="true" checked/>
                    <span class="help-inline"><?php _e('The player starts on page load', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Start at', 'wpmbytplayer'); ?>: </span>
                    <input type="text" name="startat"  style="width: 60px" /> sec.
                    <span class="help-inline"><?php _e('Set the seconds you want the player starts at', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Stop at', 'wpmbytplayer'); ?>: </span>
                    <input type="text" name="stopat"  style="width: 60px" /> sec.
                    <span class="help-inline"><?php _e('Set the seconds you want the player stops at', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Audio volume', 'wpmbytplayer'); ?>:</span>
                    <input type="text" name="volume" value="50" style="width: 60px"/>
                    <span class="help-inline"><?php _e('Set the audio volume (from 0 to 100)', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Mute video', 'wpmbytplayer'); ?>:</span>
                    <input type="checkbox" name="mute" value="true"/>
                    <span class="help-inline"><?php _e('Mute the audio of the video', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Loop video', 'wpmbytplayer'); ?>:</span>
                    <input type="checkbox" name="loop" value="true"/>
                    <span class="help-inline"><?php _e('Loop the video once ended', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Add raster', 'wpmbytplayer'); ?>:</span>
                    <input type="checkbox" name="addraster" value="true"/>
                    <span class="help-inline"><?php _e('Add a raster effect', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Pause on window blur', 'wpmbytplayer'); ?>:</span>
                    <input type="checkbox" name="stopmovieonblur" value="true"/>
                    <span class="help-inline"><?php _e('Pause the player on window blur', 'wpmbytplayer'); ?></span>
                </div>

                <div>
                    <span class="label"><?php _e('Add Google Analytics', 'wpmbytplayer'); ?>:</span>
                    <input type="checkbox" name="gaTrack" value="true"/>
                    <span class="help-inline"><?php _e('Add the event "play" on Google Analytics track', 'wpmbytplayer'); ?></span>
                </div>

            </fieldset>

            <div class="actions">
                <input type="submit" value="Insert shortcode" class="button-primary"/>
            </div>
        </form>
    </div>
    </div>

<?php }
