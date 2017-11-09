<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="format-detection" content="telephone=no">

  <?php
  /**
   * Global options
   * lib/metabox/meta-options/meta-options-global.php
   */
  $options_global = get_option('custom_options_global');
  ?>

  <?php if ( $options_global['_options_global_environment'] == 'production' && isset($options_global['_options_global_google_analytics']) ) { // Google Analytics
    echo $options_global['_options_global_google_analytics'];
  } ?>

  <?php wp_head(); ?>
</head>
