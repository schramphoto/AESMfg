<?php
/*
Template Name: Archive
*/
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/contents/content', 'archive'); ?>
<?php endwhile; ?>
