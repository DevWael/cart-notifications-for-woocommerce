<?php
// -*- coding: utf-8 -*-

declare( strict_types=1 );

namespace Devwael\CartNotificationsWc\Frontend\Assets;

use Devwael\CartNotificationsWc\WP_Integration;

class Assets_Loader implements WP_Integration, Assets {

	/**
	 * The unique identifier of this plugin.
	 */
	private const PLUGIN_NAME = 'cart-notifications-wc';

	/**
	 * The current version of the plugin.
	 */
	private const PLUGIN_VERSION = '1.0.0';

	/**
	 * plugin options.
	 * @var mixed|null
	 */
	private $options;

	public function __construct() {
		$this->options = $this->plugin_options();
	}

	/**
	 * Load the plugin css.
	 */
	public function load_css(): void {
		if ( ! $this->should_display() ) {
			return; // disable the styles if we aren't displaying the notification.
		}
		$handle = self::PLUGIN_NAME . '-style';
		$src    = $this->plugin_resources_dir_url() . 'css/style.css';
		\wp_enqueue_style( $handle, $src, [], self::PLUGIN_VERSION );
		\do_action( 'wp_users_table_load_css_assets' );
	}

	/**
	 * Load the plugin javascript.
	 */
	public function load_js(): void {
		if ( ! $this->should_display() ) {
			return; // disable the javascript if we aren't displaying the notification.
		}
		$handle = self::PLUGIN_NAME . '-script';
		$src    = $this->plugin_resources_dir_url() . 'js/main.js';
		\wp_enqueue_script(
			$handle,
			$src,
			[ 'jquery' ],
			self::PLUGIN_VERSION,
			true
		);

		/**
		 * Add the object that will be used for the ajax and for localizing
		 * frontend components.
		 */
		\wp_localize_script( $handle, 'wcNotificationObject', [
			'i18n'           => [
				'title'             => \esc_html__( 'Added To Cart', 'cart-notifications-wc' ),
				'cart_button_label' => \esc_html__( 'View Cart', 'cart-notifications-wc' ),
				'price_label'       => \esc_html__( 'Price: ', 'cart-notifications-wc' ),
			],
			'template'       => isset( $this->options['style'] ) && $this->options['style'] === 'modern'
				? $this->notification_template_modern()
				: $this->notification_template_default(),
			'options'        => $this->options,
			'should_display' => $this->should_display(),
			'cart_url'       => \esc_url( \wc_get_cart_url() ),
		] );
	}

	/**
	 * default notification template.
	 */
	public function notification_template_default(): string {
		return \apply_filters( 'cart_notifications_wc_notification_template_default', '
		<div class="product_cart_notification style_default">
			<header>
				<span class="title">{{title}}</span>
				<span class="close">x</span> 
			</header>
			<div class="middle">
				<div class="product_cart_notification__image">
					<a href="{{product_link}}" title="{{product_name}}">
						<img src="{{product_image_url}}" alt="{{product_name}}">
					</a>	
				</div>
				<div class="product_cart_notification__content">
					<div class="product_cart_notification__content__title">
						<a href="{{product_link}}" title="{{product_name}}">{{product_name}}</a>
					</div>
					<div class="product_cart_notification__content__price">
						<strong>{{price}}</strong> {{product_price}}
					</div>
				</div>
			</div>
			<div class="product_cart_notification__content__button">
				<a href="{{cart_url}}">{{cart_button}}</a>
			</div>
		</div>
		' );
	}

	/**
	 * modern notification template.
	 */
	public function notification_template_modern(): string {
		return \apply_filters( 'cart_notifications_wc_notification_template_modern', '
		<div class="product_cart_notification style_modern" style="background-image: url({{product_image_url}})">
			<header>
				<span class="title">{{title}}</span>
				<span class="close">x</span> 
			</header>
			<div class="middle">
				<div class="product_cart_notification__content">
					<div class="product_cart_notification__content__title">
						<a href="{{product_link}}" title="{{product_name}}">{{product_name}}</a>
					</div>
					<div class="product_cart_notification__content__price">
						<strong>{{price}}</strong> {{product_price}}
					</div>
				</div>
			</div>
			<div class="product_cart_notification__content__button">
				<a href="{{cart_url}}">{{cart_button}}</a>
			</div>
		</div>
		' );
	}

	/**
	 * Prepare the resources URL to the resources plugin directory.
	 *
	 * @return string
	 */
	private function plugin_resources_dir_url(): string {
		return \apply_filters( 'cart_notifications_wc_resources_dir_url',
			\plugin_dir_url( dirname( __FILE__, 3 ) ) . 'resources/' );
	}

	/**
	 * Get the plugin options.
	 * @return mixed|null
	 */
	private function plugin_options(): array {
		return \apply_filters( 'cart_notifications_wc_options', \get_option( 'cart_notifications_wc_options', [
			'style'       => 'default',
			'position'    => 'top-right',
			'close_after' => 3,
			'display_on'  => 'all',
		] ) );
	}

	/**
	 * Check if the notification should be displayed based on settings.
	 *
	 * @return bool
	 */
	private function should_display(): bool {
		$should_display = false;
		if ( ! is_array( $this->options['display_on'] ) ) {
			return \apply_filters( 'cart_notifications_wc_should_display', $should_display );
		}
		if ( in_array( 'none', $this->options['display_on'], true ) ) {
			return \apply_filters( 'cart_notifications_wc_should_display', $should_display );
		}
		if ( in_array( 'all', $this->options['display_on'], true ) ) {
			$should_display = true;
		} else {
			if ( \is_product() ) {
				if ( in_array( 'single_product', $this->options['display_on'], true ) ) {
					$should_display = true;
				}
			} elseif ( \is_shop() ) {
				if ( in_array( 'shop_archive', $this->options['display_on'], true ) ) {
					$should_display = true;
				}
			} elseif ( \is_product_category() ) {
				if ( in_array( 'shop_archive_cats', $this->options['display_on'], true ) ) {
					$should_display = true;
				}
			} elseif ( \is_product_tag() ) {
				if ( in_array( 'shop_archive_tags', $this->options['display_on'], true ) ) {
					$should_display = true;
				}
			} elseif ( \is_product_taxonomy() ) {
				if ( in_array( 'shop_archive_attrs', $this->options['display_on'], true ) ) {
					$should_display = true;
				}
			}
		}

		return \apply_filters( 'cart_notifications_wc_should_display', $should_display );
	}

	public function template_wrapper(): void {
		$position = $this->options['position'];
		echo \apply_filters( 'cart_notifications_wc_template_wrapper',
			'<div class="product_cart_notification_wrapper ' . esc_attr( $position ) . '"></div>' );
	}

	public function load_hooks(): void {
		\add_action( 'wp_enqueue_scripts', [ $this, 'load_css' ] );
		\add_action( 'wp_enqueue_scripts', [ $this, 'load_js' ] );
		\add_action( 'wp_footer', [ $this, 'template_wrapper' ] );
	}
}