<?php

/*
 * Global options
 * lib/metabox/meta-options/meta-options-global.php
 */
$options_global = get_option('custom_options_global');
?>
<?php
$args = array(
  'post_type' => 'product',
  'posts_per_page' => -1,
);
$equipment = new WP_Query( $args );
?>

<?php if ( $equipment->have_posts() ) : ?>
          <?php while ( $equipment->have_posts() ) : $equipment->the_post(); ?>
            <?php foreach ( get_the_category() as $category ) : ?>
              <input type="hidden" class="product_categories" value="<?php echo 'menu-' . str_replace(' ', '-', strtolower($category->name)); ?>" />
            <?php endforeach; ?>
          <?php endwhile; ?>
        <?php endif; ?>

<header class="navbar" role="banner">
  <div class="container">

    <div class="navbar-header">

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <?php if ( isset($options_global['_options_global_logo_main']) ) : ?>
        <a href="<?php echo esc_url(home_url('/')); ?>">
          <img class="logo logo--header" src="<?php echo $options_global['_options_global_logo_main']; ?>" alt="<?php bloginfo('name'); ?>">
        </a>
      <?php else : ?>
        <a class="logo__brand" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
      <?php endif; ?>

    </div>

    <nav class="collapse navbar-collapse" role="navigation">
      <?php if (has_nav_menu('primary_navigation')) : ?>
        <?php wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav')); ?>
      <?php endif; ?>
      <?php get_template_part('templates/modules/header', 'nav-equipment'); ?>
    </nav>
  </div>
</header>
