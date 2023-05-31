<?php
// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace Devwael\CartNotificationsWc\I18n;

use Devwael\CartNotificationsWc\WP_Integration;

/**
 * Load plugin text domain
 *
 * @package Devwael\CartNotificationsWc\I18n
 */
class Languages implements WP_Integration {

	public function load_text_domain(): void {
		$languages_dir_rel_path = dirname( \plugin_basename( __FILE__ ), 3 )
		                          . '/languages';
		/**
		 * Load plugin text domain
		 */
		\load_plugin_textdomain( 'cart-notifications-wc', false,
			$languages_dir_rel_path );
	}

	/**
	 * Attach the class functions to WordPress hooks
	 *
	 * @return void
	 */
	public function load_hooks(): void {
		\add_action( 'plugins_loaded', [ $this, 'load_text_domain' ] );
	}
}