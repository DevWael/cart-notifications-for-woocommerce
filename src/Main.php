<?php

// -*- coding: utf-8 -*-

declare( strict_types=1 );

namespace Devwael\CartNotificationsWc;

use Devwael\CartNotificationsWc\admin\Admin_Page;
use Devwael\CartNotificationsWc\admin\Options_Page;
use Devwael\CartNotificationsWc\Frontend\Assets\Assets;
use Devwael\CartNotificationsWc\Frontend\Assets\Assets_Loader;
use Devwael\CartNotificationsWc\Frontend\WC\WC;
use Devwael\CartNotificationsWc\Frontend\WC\WC_Integration;
use Devwael\CartNotificationsWc\I18n\Languages;

/**
 * The plugin main class that responsible for loading all plugin logic.
 *
 * @package Devwael\CartNotificationsWc
 */
final class Main {

	/**
	 * Unique instance of the Main class.
	 *
	 * @var Main|null
	 */
	private static ?Main $instance = null;

	private Options_Page $admin_page;
	private Assets $scripts;
	private WC $woocommerce_integration;
	private Languages $languages;

	/**
	 * Main constructor.
	 */
	private function __construct(
		Options_Page $admin_page,
		Assets $scripts,
		WC $woocommerce_integration,
		Languages $languages
	) {
		$this->admin_page              = $admin_page;
		$this->scripts                 = $scripts;
		$this->woocommerce_integration = $woocommerce_integration;
		$this->languages               = $languages;
	}

	/**
	 * Load class singleton instance.
	 *
	 * @param Options_Page|null $admin_page              instance of Admin_Page object.
	 * @param Assets|null       $scripts                 instance of Assets_Loader object.
	 * @param WC|null           $woocommerce_integration instance of WC_Integration object.
	 * @param Languages|null    $languages               instance of Languages object.
	 *
	 * @return Main singleton instance
	 */
	public static function instance(
		Options_Page $admin_page = null,
		Assets $scripts = null,
		WC $woocommerce_integration = null,
		Languages $languages = null
	): self {
		if ( null === self::$instance ) {
			// new instance of Admin_Page object
			$admin_page_object = $admin_page ?? new Admin_Page();
			// new instance of Admin_Page object
			$assets_loader_object = $scripts ?? new Assets_Loader();
			// new instance of WC_Integration
			$wc_integration_object = $woocommerce_integration ?? new WC_Integration();
			// new instance of Languages object
			$languages_object = $languages ?? new Languages();
			self::$instance   = new self(
				$admin_page_object,
				$assets_loader_object,
				$wc_integration_object,
				$languages_object
			);
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initialize the logic
	 */
	public function init(): void {
		if ( \wp_installing() ) {
			return; //prevent loading when we are installing WordPress
		}

		/**
		 * Load all admin side logic
		 */
		if ( \is_admin() ) {
			$this->admin_page->load_hooks(); //load all admin page actions
		}

		$this->scripts->load_hooks(); //load all scripts actions
		$this->woocommerce_integration->load_hooks(); //load all woocommerce integrations actions
		$this->languages->load_hooks(); //load languages text domain action
	}
}