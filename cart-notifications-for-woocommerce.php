<?php

# -*- coding: utf-8 -*-

declare( strict_types=1 );

/**
 * Cart Notifications for WooCommerce
 *
 * @package           Devwael\CartNotificationsWc
 * @author            Ahmad Wael
 * @copyright         2023 Ahmad Wael
 * @license           GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Cart Notifications for WooCommerce
 * Plugin URI: https://www.bbioon.com
 * Description: WordPress plugin that display notification when the customer add product to woocommerce cart.
 * Version: 1.0.0
 * Author: Ahmad Wael
 * Author URI: https://www.bbioon.com
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cart-notifications-wc
 * Domain Path: /languages
 */

namespace Devwael\CartNotificationsWc;

/**
 * Check if loaded inside a WordPress environment.
 */
defined( '\ABSPATH' ) || exit;

/**
 * Load composer packages
 */
$autoLoad = \plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
if ( ! class_exists( Main::class ) && is_readable( $autoLoad ) ) {
	//check if the Main plugin class is loaded
	/** @noinspection PhpIncludeInspection */
	require_once $autoLoad;
}

/**
 * Create instance from the main plugin class
 */
class_exists( Main::class ) && Main::instance();

\register_activation_hook( __FILE__, static function () {
	\update_option( 'cart_notifications_wc_options', [
		'style'       => 'default',
		'position'    => 'top-right',
		'close_after' => 3,
		'display_on'  => 'all',
	] );
} );