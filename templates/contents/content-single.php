<div class="single-page">
  <div class="single-page__column--left col-md-6">
    <div class="single-page__button">
      <?php $pdfs = new StdClass; ?>
      <?php foreach( meta( '_lesafety_products_pdf' ) as $key => $pdf ) : ?>
        <?php $pdfs->$key = new StdClass; ?>
        <?php $pdfs->$key->name = $pdf['pdf-name']; ?>
        <?php $pdfs->$key->link = $pdf['pdf-file']; ?>
        <a href="<?php echo $pdfs->$key->link; ?>" target="_blank" class="single-page__button--primary"><?php echo $pdfs->$key->name; ?></a>
      <?php endforeach; ?>
    </div>
    <?php if( meta( '_lesafety_products_image' ) ) : ?>
      <div class="single-page__image--primary">
        <a href="<?php echo meta( '_lesafety_products_image' ); ?>" rel="magnific"><img src="<?php echo meta( '_lesafety_products_image' ); ?>" /></a>
      </div>
    <?php endif; ?>
    <div class="single-page__image--gallery">
      <?php $gallery = new StdClass; ?>
      <?php foreach( meta( '_lesafety_products_gallery' ) as $key => $image ) : ?>
        <?php $gallery->$key->id = $image['gallery_id']; ?>
        <?php $gallery->$key->image = $image['gallery']; ?>
        <?php $img = wp_get_attachment_image_src( $gallery->$key->id, array(75,75) ); ?>
        <a href="<?php echo $gallery->$key->image; ?>" class="images" rel="magnific"><img class="square" src="<?php echo $img[0]; ?>" /></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="single-page__column--right col-md-6">
    <h1 class="single-page__title"><?php echo get_the_title(); ?></h1>
    <p class="single-page__description"><?php echo wpautop( meta( '_lesafety_products_description' ) ); ?></p>
    <label class="single-page__section">Key Features</label>
    <div class="single-page__section--block">
      <?php if ( meta( '_lesafety_products_features' ) ) : ?>
        <ul class="single-page__key_features">
          <?php foreach(meta( '_lesafety_products_features' ) as $feature) : ?>
            <li><?php echo $feature; ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else : ?>
        No Key Features to Display
      <?php endif; ?>
    </div>
    <label class="single-page__section">Spec Data</label>
    <div class="single-page__section--block">
      <?php $header_array = array(); ?>
      <?php $products = new StdClass; ?>
      <?php $header_printed = FALSE; ?>
      <?php $previous_header = NULL; ?>
      <?php if ( meta( '_lesafety_products_spec_data' ) ) : ?>
        <?php foreach( meta( '_lesafety_products_spec_data' ) as $key => $spec ) : ?>
          <?php $products->$key = new StdClass; ?>
          <?php $products->$key->header = $spec['header']; ?>
          <?php $products->$key->name = $spec['name']; ?>
          <?php $products->$key->value = $spec['value']; ?>
          <div class="single-page__section--container">
            <?php if ( $products->$key->header !== '' ) : ?>
              <?php if ( $previous_header !== $products->$key->header ) : ?>
                <span class="single-page__section--header"><?php echo $products->$key->header; ?></span>
                <br/>
              <?php endif; ?>
              <?php $previous_header = $products->$key->header; ?>
            <?php endif; ?>
            <span class="single-page__section--name"><?php echo $products->$key->name; ?></span>
            <span class="single-page__section--value"><?php echo $products->$key->value; ?></span>
            <br/>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        No Spec Data to Display
      <?php endif; ?>
    </div>
    <div class="single-page__button">
      <?php $url = urlencode('Pricing Request: ' . get_the_title()); ?>
      <a href="<?php echo esc_url(home_url('/contact-us/?pricing=' . $url)); ?>" class="single-page__button--secondary">Request Pricing</a>
    </div>
  </div>
</div>
