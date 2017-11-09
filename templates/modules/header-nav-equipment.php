<?php
// Equipment query
//
// Let the plugin "Intuitive Custom Post Order"
// handle the ordering

$category_args = array(
  'parent'  => get_category_by_slug('products')->term_id
);
?>

<?php foreach ( get_categories( $category_args ) as $category ) : ?>
  <?php $args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'category_name' => $category->slug
  );
  $equipment = new WP_Query( $args );
  ?>
  <?php if ( wp_is_mobile() ) : ?>
    <?php if ( $equipment->have_posts() ) : ?>
      <div class="nav-equipment mobile-nav-equipment-<?php echo $category->slug; ?> mobile_offset">
        <div class="row flex-parent-mobile">
          <div class="nav-equipment__tabs">
            <ul class="nav nav-tabs" role="tablist">
              <?php while ( $equipment->have_posts() ) : $equipment->the_post(); ?>
                <li role="presentation presentation-mobile">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>
              <?php endwhile; ?>
              <?php wp_reset_query(); ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if ( $equipment->have_posts() ) : ?>
          <div class="nav-equipment__content">
            <div class="tab-content">
              <?php while ( $equipment->have_posts() ) : $equipment->the_post(); ?>
                <div id="<?php the_time('U'); ?>" class="tab-pane fade" role="tabpanel">
                  <div class="nav-equipment__left">
                    <div class="nav-equipment__content__image">
                      <?php if (meta ('_lesafety_products_image' )) : ?>
                        <img src="<?php echo meta( '_lesafety_products_image' ); ?>" />
                      <?php endif; ?>
                    </div>
                    <div class="nav-equipment__content__inner">
                      <div class="title">
                        <?php the_title(); ?>
                        <?php if ( meta('_magnum_equipment_nav_subtitle') ) : ?>
                          <em>(<?php echo meta('_magnum_equipment_nav_subtitle'); ?>)</em>
                        <?php endif; ?>
                      </div>
                      <div class="row">
                        <div class="col-attributes">
                          <?php
                          $nav_attributes = meta('_magnum_equipment_nav_attributes');
                          foreach ( (array) $nav_attributes as $key => $attribute ) :
                            $name = $value = '';
                            if ( isset( $attribute['name'] ) )
                              $name = $attribute['name'];
                            if ( isset( $attribute['value'] ) )
                              $value = $attribute['value'];
                            ?>
                            <div class="attribute">
                              <?php // echo trunc_string( meta( '_lesafety_products_description' ), 100 ); ?>
                              <?php echo meta( '_lesafety_products_description' ); ?>
                              <?php if ( $name ) : ?>
                                <div class="attribute__name">
                                  <?php echo $name; ?>
                                </div>
                              <?php endif; ?>
                              <?php if ( $value ) : ?>
                                <div class="attribute__value">
                                  <?php echo $value; ?>
                                </div>
                              <?php endif; ?>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      </div><!-- /.row -->
                    </div>
                  </div>
              <?php endwhile; ?>
              <?php wp_reset_query(); ?>
            </div>
          </div>
        <?php endif; ?>
  <?php else : ?>
    <?php if ( $equipment->have_posts() ) : ?>
      <div class="nav-equipment nav-equipment-<?php echo $category->slug; ?>">
        <div class="row flex-parent">
          <div class="nav-equipment__tabs">
            <ul class="nav nav-tabs" role="tablist">
              <?php while ( $equipment->have_posts() ) : $equipment->the_post(); ?>
                <li role="presentation">
                  <input type="hidden" id="image-<?php the_time('U'); ?>" class="header-images-links" value="<?php echo meta( '_lesafety_products_header_image' ); ?>" />
                  <?php if ( meta( '_lesafety_products_directlink' ) === 'on' ) : ?>
                    <a
                    href="<?php echo meta( '_lesafety_products_linkurl' ); ?>"
                    style="cursor: pointer;"
                    class="product_tabs_toggle"
                    data-background="<?php echo meta( '_lesafety_products_header_image' ); ?>">
                      <?php the_title(); ?>
                      <?php if (meta ('_lesafety_products_image' )) : ?>
                        <img src="<?php echo meta( '_lesafety_products_image' ); ?>" />
                      <?php endif; ?>
                    </a>
                  <?php else : ?>
                    <a
                    href="#<?php the_time('U'); ?>"
                    aria-controls="<?php the_time('U'); ?>"
                    role="tab" data-toggle="tab"
                    class="product_tabs_toggle"
                    data-background="<?php echo meta( '_lesafety_products_header_image' ); ?>">
                      <?php the_title(); ?>
                      <?php if (meta ('_lesafety_products_image' )) : ?>
                        <img src="<?php echo meta( '_lesafety_products_image' ); ?>" />
                      <?php endif; ?>
                    </a>
                  <?php endif; ?>
                </li>
              <?php endwhile; ?>
              <?php wp_reset_query(); ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if ( $equipment->have_posts() ) : ?>
          <div class="nav-equipment__content">
            <div class="tab-content">
              <?php while ( $equipment->have_posts() ) : $equipment->the_post(); ?>
                <div id="<?php the_time('U'); ?>" class="tab-pane fade" role="tabpanel">
                  <div class="nav-equipment__left">
                    <div class="nav-equipment__content__image">
                      <?php if (meta ('_lesafety_products_image' )) : ?>
                        <img src="<?php echo meta( '_lesafety_products_image' ); ?>" />
                      <?php endif; ?>
                    </div>
                    <div class="nav-equipment__content__inner">
                      <div class="title">
                        <?php the_title(); ?>
                        <?php if ( meta('_magnum_equipment_nav_subtitle') ) : ?>
                          <em>(<?php echo meta('_magnum_equipment_nav_subtitle'); ?>)</em>
                        <?php endif; ?>
                      </div>
                      <div class="row">
                        <div class="col-attributes">
                          <?php
                          $nav_attributes = meta('_magnum_equipment_nav_attributes');
                          foreach ( (array) $nav_attributes as $key => $attribute ) :
                            $name = $value = '';
                            if ( isset( $attribute['name'] ) )
                              $name = $attribute['name'];
                            if ( isset( $attribute['value'] ) )
                              $value = $attribute['value'];
                            ?>
                            <div class="attribute">
                              <?php // echo trunc_string( meta( '_lesafety_products_description' ), 100 ); ?>
                              <?php echo meta( '_lesafety_products_description' ); ?>
                              <?php if ( $name ) : ?>
                                <div class="attribute__name">
                                  <?php echo $name; ?>
                                </div>
                              <?php endif; ?>
                              <?php if ( $value ) : ?>
                                <div class="attribute__value">
                                  <?php echo $value; ?>
                                </div>
                              <?php endif; ?>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      </div><!-- /.row -->
                    </div>
                  </div>

                  <div class="nav-equipment__right">
                    <div class="links">
                      <div class="row">
                        <a class="nav-equipment__links nav-equipment__links--button_primary" href="<?php the_permalink(); ?>">
                          View Product Details
                        </a>
                        <?php $pdfs = new StdClass; ?>
                      <?php foreach( meta( '_lesafety_products_pdf' ) as $key => $pdf ) : ?>
                        <?php $pdfs->$key = new StdClass; ?>
                        <?php $pdfs->$key->name = $pdf['pdf-name']; ?>
                        <?php $pdfs->$key->link = $pdf['pdf-file']; ?>
                          <a href="<?php echo $pdfs->$key->link; ?>" target="_blank" class="nav-equipment__links nav-equipment__links--button_secondary"><?php echo $pdfs->$key->name; ?></a>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
              <?php wp_reset_query(); ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
<?php endforeach; ?>
