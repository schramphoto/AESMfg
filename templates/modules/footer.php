<?php
$options_contact = get_option('custom_options_contact');
?>
<footer class="footer" role="contentinfo">
  <div class="container">
    <div class="row">
      <div class="footer__social">
        <?php if ( isset($options_contact['_options_contact_facebook']) ) : ?>
          <a href="<?php echo $options_contact['_options_contact_facebook']; ?>"><span class="icon-social icon-facebook"></span></a>
        <?php endif; ?>
        <?php if ( isset($options_contact['_options_contact_googleplus']) ) : ?>
          <a href="<?php echo $options_contact['_options_contact_googleplus']; ?>"><span class="icon-social icon-google-plus"></span></a>
        <?php endif; ?>
        <?php if ( isset($options_contact['_options_contact_linkedin']) ) : ?>
          <a href="<?php echo $options_contact['_options_contact_linkedin']; ?>"><span class="icon-social icon-linkedin"></span></a>
        <?php endif; ?>
        <?php if ( isset($options_contact['_options_contact_pinterest']) ) : ?>
          <a href="<?php echo $options_contact['_options_contact_pinterest']; ?>"><span class="icon-social icon-pinterest"></span></a>
        <?php endif; ?>
        <?php if ( isset($options_contact['_options_contact_twitter']) ) : ?>
          <a href="<?php echo $options_contact['_options_contact_twitter']; ?>"><span class="icon-social icon-twitter"></span></a>
        <?php endif; ?>
        <?php if ( isset($options_contact['_options_contact_youtube']) ) : ?>
          <a href="<?php echo $options_contact['_options_contact_youtube']; ?>"><span class="icon-social icon-youtube"></span></a>
        <?php endif; ?>
      </div>
      <div class="footer__middle">
        <a class="footer__links" href="<?php echo site_url('categories/news' ); ?>">News</a> | <a class="footer__links" href="<?php echo site_url('contact-us' ); ?>">Contact</a>
      </div>
      <div class="footer__credits">
        Copyright <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved.<br/>
        <a class="footer__links" href="https://liftedlogic.com/" target="_blank">Web Design in Kansas City</a> by <a class="footer__links" href="https://liftedlogic.com/" target="_blank">Lifted Logic</a>
      </div>
    </div>
  </div>
</footer>
