<?php 

function disable_product_meta_box( $object, $box ) {
  global $post;
  
  wp_nonce_field( 'disable_meta_box', 'disable_meta_box_nonce' );

  
  $disable_product = get_post_meta( $post->ID, 'disable_zasilkovna_shipping', true );
  ?>
  
  <p><?php _e('Nastavit nulovou cenu pro Zásilkovnu','woocommerce-zasilkovna'); ?></p>
  <p><input type="checkbox" name="disable_zasilkovna_shipping" value="yes" <?php if( !empty($disable_product) && $disable_product == 'yes' ){ echo 'checked="checked"'; } ?> /></p>
  
<?php 

}