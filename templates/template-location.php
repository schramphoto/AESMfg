<?php
/*
Template Name: Location
*/
global $locations;
$locations = get_option( 'bnsf_locations' );
?>
<?php $args = array(
  'posts_per_page'    => -1,
  'post_type'         => 'location',
  'post_status'       => 'publish'
);
$region_var = 'all';
$no_retailers = false;
if ( ( $_POST ) ) {
  $response = lesafety_search_filter();
  $args = $response['arg'];
  $region_var = $response['region'];
}
$posts_array = new WP_Query( $args );
if ( !$posts_array->have_posts() ) {
  if ( $region_var !== 'all' ) {
    $no_retailers = true;
  }
  $args = array(
    'posts_per_page'    => -1,
    'post_type'         => 'location',
    'post_status'       => 'publish'
  );
  $posts_array = new WP_Query( $args );
}
$options_global = get_option('custom_options_global');
?>

<div class="distributors_archive">
<!-- Header map -->
  <div class="distributors_archive__header">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/map.jpg" />
    <?php if ( is_array( $locations ) ) : foreach( $locations as $key => $location ) : ?>
      <div class="distributors_archive__pins" style="top: <?php echo $location['y']; ?>; left: <?php echo $location['x']; ?>;" data-id="<?php echo $key; ?>"></div>
    <?php endforeach; endif; ?>
  </div>
  <h1 class="distributors_archive__header--text"><?php echo $options_global['_options_global_location_header']; ?></h1>
<!-- Search / contact -->
  <div class="distributors_archive__searchtext">
    <?php if ( $options_global['_options_global_location_contact_allow'] == 'yes' ) : ?>
      If there is no dealer in your general region, please <a class="distributors_archive__searchtext--link" href="<?php echo $options_global['_options_global_location_contact']; ?>">contact us.</a>
    <?php endif; ?>
  </div>
  <div class="distributors_archive__search">
    <form id="distributors_archive__find_distributors" name="distributors_archive__find_distributors" method="POST">
      <select id="select_dropdown" name="region" class="form-control">
        <option <?php if($region_var === 'all'){echo 'selected="selected"';} ?> value="all">All Regions</option>
        <!-- Add Canada code here -->
        <optgroup label="Northeast">
          <option <?php if($region_var === 'newengland'){echo 'selected="selected"';} ?> value="newengland">New England</option>
          <option <?php if($region_var === 'middleatlantic'){echo 'selected="selected"';} ?> value="middleatlantic">Mid-Atlantic</option>
        </optgroup>
        <optgroup label="Midwest">
          <option <?php if($region_var === 'eastnorthcentral'){echo 'selected="selected"';} ?> value="eastnorthcentral">East North Central</option>
          <option <?php if($region_var === 'westnorthcentral'){echo 'selected="selected"';} ?> value="westnorthcentral">West North Central</option>
        </optgroup>
        <optgroup label="South">
          <option <?php if($region_var === 'southatlantic'){echo 'selected="selected"';} ?> value="southatlantic">South Atlantic</option>
          <option <?php if($region_var === 'eastsouthcentral'){echo 'selected="selected"';} ?> value="eastsouthcentral">East South Central</option>
          <option <?php if($region_var === 'westsouthcentral'){echo 'selected="selected"';} ?> value="westsouthcentral">West South Central</option>
        </optgroup>
        <optgroup label="West">
          <option <?php if($region_var === 'mountain'){echo 'selected="selected"';} ?> value="mountain">Mountain</option>
          <option <?php if($region_var === 'pacific'){echo 'selected="selected"';} ?> value="pacific">Pacific</option>
        </optgroup>
      </select>
      <input type="text" id="zipcode" name="zip" placeholder="Zip Code"/>
    </form>
    <?php if($no_retailers){echo '<div class="distributors_archive__searchtext">' . $options_global['_options_global_location_message'] . '</div>';} ?>
  </div>
<!-- List Locations -->
  <div class="container">
    <div class="distributors_archive__stores">
      <?php if ( $posts_array->have_posts() ) : ?>
        <?php while ( $posts_array->have_posts() ) : $posts_array->the_post() ?>
          <div class="distributors_archive__locations">
            <?php the_title(); ?>
            <br/>
            <span class="distributors_archive__locations--light"><?php echo meta( '_lesafety_locations_street_address' ); ?></span>
            <br/>
            <span class="distributors_archive__locations--light"><?php echo meta( '_lesafety_locations_city' ) . ', ' . strtoupper( meta( '_lesafety_locations_state' ) ); ?></span>
            <br/>
            <span class="distributors_archive__locations--light distributors_archive__locations--zipcode"><?php echo meta( '_lesafety_locations_zipcode' ); ?></span>
            <br/>
            <span class="distributors_archive__locations--phone_number"><?php echo format_phone(meta( '_lesafety_locations_phone_number' )); ?></span>
            <br/>
            <a class="distributors_archive__locations--light distributors_archive__locations--link" href="<?php echo meta('_lesafety_locations_website'); ?>">Visit Site</a>
          </div>
        <?php endwhile; ?>
        <?php wp_reset_query(); ?>
      <?php endif; ?>
    </div>
  </div>
</div>
