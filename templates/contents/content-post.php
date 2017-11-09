<div class="single-post">
  <h2 class="single-post__header">News</h2>
  <h1 class="single-post__title">
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
  </h1>
  <?php $img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'full' ); ?>
  <div class="single-post__image">
    <img src="<?php echo $img[0]; ?>" />
  </div>
  <div class="single-post__content">
    <?php the_content(); ?>
  </div>
</div>
