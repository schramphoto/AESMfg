<div class="post-roll">
  <h2 class="post-roll__title"><?php the_title(); ?></h2>
  <a class="post-roll__link" href="<?php the_permalink(); ?>">View post</a>
  <?php $img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'full' ); ?>
  <div class="post-roll__image">
    <img src="<?php echo $img[0]; ?>" />
  </div>
</div>
