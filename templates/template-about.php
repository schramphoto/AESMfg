<?php
/*
Template Name: About
*/
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/modules/header', 'page'); ?>
  <?php get_template_part('templates/contents/content', 'about'); ?>
<?php endwhile; ?>
