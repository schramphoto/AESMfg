<?php $args = array(
        'posts_per_page'    => -1,
        'post_type'         => 'team',
        'post_status'       => 'publish'
);
$posts_array = new WP_Query( $args ); ?>

<div class="default-page--body">
  <?php the_content(); ?>
  <?php if ( $posts_array->have_posts() ) : ?>
    <ul class="gridder">
      <?php while ( $posts_array->have_posts() ) : $posts_array->the_post() ?>
        <li class="gridder-list" data-griddercontent="#gridder-<?php the_id(); ?>">
          <?php the_post_thumbnail(); ?>
          <div class="banner"><span><?php the_title(); ?></span></div>
          <div class="overlay"></div>
        </li>
      <?php endwhile; ?>
      <?php wp_reset_query(); ?>
    </ul>
    <?php while ( $posts_array->have_posts() ) : $posts_array->the_post() ?>
      <div id="gridder-<?php the_id(); ?>" class="gridder-content">
        <p class="header"><?php echo get_the_title(); ?></p>
        <?php the_post_thumbnail(); ?>
        <?php the_content(); ?>
      </div>
    <?php endwhile; ?>
    <?php wp_reset_query(); ?>
  <?php endif; ?>
</div>
