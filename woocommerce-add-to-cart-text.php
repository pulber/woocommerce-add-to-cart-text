<?php
/**
 * Plugin Name: WooCommerce Add To Cart Text
 * Plugin URI: http://pulber.com/woocommerce/woocommerce-add-to-cart-text/
 * Description: Change add to cart button text.
 * Author: Eugene Pulber
 * Author URI: http://pulber.com/
 * Version: 1.0.0
 */

if (!defined( 'ABSPATH' )) {
  exit; // Exit if accessed directly.
}

if (!class_exists( 'WooCommerce_Add_To_Cart_Text' )) {

  final class WooCommerce_Add_To_Cart_Text
  {

    public static function hooks() {
      if (!defined( 'WC_VERSION' ) || version_compare( WC_VERSION, '3.0.0', '<' )) {
        return;
      }

      add_filter( 'woocommerce_product_settings', __CLASS__ . '::product_settings' );
      add_filter( 'woocommerce_product_single_add_to_cart_text', __CLASS__ . '::product_single_add_to_cart_text', 10, 2 );
    }

    public static function product_settings($settings) {
      $insert_after_key = array_search( 'woocommerce_enable_ajax_add_to_cart', array_column( $settings, 'id' ) );

      array_splice( $settings, $insert_after_key + 1, 0, array(
        array(
          'id'          => 'woocommerce_add_to_cart_text',
          'type'        => 'text',
          'title'       => __( 'Add To Cart Text', 'woocommerce' ),
          'desc'        => __( 'Change add to cart button text.', 'woocommerce' ),
          'css'         => 'width: 250px;',
          'placeholder' => __( 'Add to cart', 'woocommerce' ),
          'desc_tip'    => true,
          'autoload'    => false
        )
      ) );

      return $settings;
    }

    public static function product_single_add_to_cart_text($add_to_cart_text, $product) {
      if ($custom_add_to_cart_text = get_option( 'woocommerce_add_to_cart_text' )) {
        $add_to_cart_text = $custom_add_to_cart_text;
      }

      if ($product->is_type( 'external' ) && $product->get_button_text() !== '') {
        $add_to_cart_text = $product->get_button_text();
      }

      return $add_to_cart_text;
    }

  }

  add_action( 'woocommerce_init', 'WooCommerce_Add_To_Cart_Text::hooks' );

}
