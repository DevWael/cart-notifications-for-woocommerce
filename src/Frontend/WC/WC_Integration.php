<?php
// -*- coding: utf-8 -*-

declare( strict_types=1 );

namespace Devwael\CartNotificationsWc\Frontend\WC;

use Devwael\CartNotificationsWc\WP_Integration;

class WC_Integration implements WP_Integration, WC {

	public function cart_fragments( $fragments ) {
		if ( isset( $_POST['product_id'] ) && is_numeric( $_POST['product_id'] ) ) {
			$product_id = sanitize_text_field( $_POST['product_id'] );
			$product_id = absint( apply_filters( 'woocommerce_add_to_cart_product_id', $product_id ) );
			// get product
			$product = wc_get_product( $product_id );
			if ( $product ) {
				$product_name      = $product->get_name();
				$product_price     = $product->get_price();
				$product_image_url = wp_get_attachment_image_src( $product->get_image_id(),
					'full' )[0] ?? wc_placeholder_img_src('full');

				$fragments['product_notification_widget'] = [
					'product_name'       => esc_html( $product_name ),
					'product_url'        => esc_url( $product->get_permalink() ),
					'product_price'      => esc_html( $product_price ),
					'product_price_html' => $product->get_price_html(),
					'product_image_url'  => esc_url( $product_image_url ),
				];
			}
		}

		return $fragments;
	}

	public function load_hooks(): void {
		\add_filter( 'woocommerce_add_to_cart_fragments',
			[ $this, 'cart_fragments' ] );
	}
}