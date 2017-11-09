<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

// Uninstall all the mbYTPlayer settings
delete_option('mbYTPlayer_version');
delete_option('mbYTPlayer_is_active');
delete_option('mbYTPlayer_donate');
delete_option('mbYTPlayer_video_url');
delete_option('mbYTPlayer_show_controls');
delete_option('mbYTPlayer_show_videourl');
delete_option('mbYTPlayer_audio_volume');
delete_option('mbYTPlayer_mute');
delete_option('mbYTPlayer_start_at');
delete_option('mbYTPlayer_stop_at');
delete_option('mbYTPlayer_ratio');
delete_option('mbYTPlayer_loop');
delete_option('mbYTPlayer_opacity');
delete_option('mbYTPlayer_quality');
delete_option('mbYTPlayer_add_raster');
delete_option('mbYTPlayer_track_ga');
delete_option('mbYTPlayer_stop_on_blur');
delete_option('mbYTPlayer_track_ga');
delete_option('mbYTPlayer_realfullscreen');
delete_option('mbYTPlayer_video_page');
delete_option('mbYTPlayer_fallbackimage');

delete_option('mbYTPlayer_Home_is_active');
delete_option('mbYTPlayer_home_video_url');
delete_option('mbYTPlayer_home_video_page');

delete_option('mbYTPlayer_license_key');
