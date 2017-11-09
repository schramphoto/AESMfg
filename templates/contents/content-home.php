<!-- hero -->
<?php $image = wp_get_attachment_url( get_post_thumbnail_id( get_the_id() ) ) ?>
<div class="hero" style="background-image: url('<?php echo $image ?>');">
  <h1 class="hero__title"><?php the_title(); ?></h1>
  <!-- meta -->
  <h3 class="hero__subheading"><?php echo meta( '_lesafety_home_subtitle' ); ?></h3>
  <!-- meta -->
  <?php if ( !empty(meta( '_lesafety_home_button_title' ))) : ?>
    <a class="hero__link" href="<?php echo meta( '_lesafety_home_button_url' ); ?>"><?php echo meta( '_lesafety_home_button_title' ); ?></a>
  <?php endif; ?>
</div>
<!-- call to action -->
<div class="call_to_action">
  <?php $i = 1; ?>
  <?php $j = 1; ?>
  <div class="call_to_action__image call_to_action__image--left">
    <?php foreach ( meta('_lesafety_home_solutions_logos') as $logo ) : ?>
      <?php if ( $i % 2 === 0 ) : ?>
        <img src="<?php echo $logo; ?>" class="call_to_action__logo" />
      <?php endif; ?>
      <?php $i++; ?>
    <?php endforeach; ?>
  </div>
  <div class="call_to_action--content">
    <h2 class="call_to_action__title"><?php echo meta( '_lesafety_home_solutions' ); ?></h2>
    <h3 class="call_to_action__subheading"><?php echo meta( '_lesafety_home_solutions_subtitle' ); ?></h3>
    <?php foreach( meta('_lesafety_home_solutions_button') as $button ) : ?>
      <a class="call_to_action__link" href="<?php echo $button['link']; ?>"><?php echo $button['name']; ?></a>
    <?php endforeach; ?>
  </div>
  <div class="call_to_action__image call_to_action__image--right">
    <?php foreach ( meta('_lesafety_home_solutions_logos') as $logo ) : ?>
      <?php if ( $j % 2 === 1 ) : ?>
        <img src="<?php echo $logo; ?>" class="call_to_action__logo" />
      <?php endif; ?>
      <?php $j++; ?>
    <?php endforeach; ?>
  </div>
</div>
<!-- about block -->
<div class="home_about">
  <h2 class="home_about__title"><?php echo meta('_lesafety_home_about_title'); ?></h2>
  <h3 class="home_about__subheading"><?php echo meta('_lesafety_home_about_subtitle'); ?></h3>
  <div class="home_about__body">
    <?php the_content(); ?>
  </div>
  <a href="<?php echo meta('_lesafety_home_about_button_link'); ?>" class="home_about__link"><?php echo meta('_lesafety_home_about_button_title'); ?></a>
</div>
<!-- distributors -->
<div class="distributors">
  <!-- meta -->
  <h2 class="distributors__title"><?php echo meta('_lesafety_home_distributors_title'); ?></h2>
  <a class="distributors__link" href="<?php echo meta('_lesafety_home_distributors_button_link'); ?>"><?php echo meta('_lesafety_home_distributors_button_title'); ?></a>
</div>
