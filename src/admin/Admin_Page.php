<?php
// -*- coding: utf-8 -*-

declare( strict_types=1 );

namespace Devwael\CartNotificationsWc\admin;

use Devwael\CartNotificationsWc\WP_Integration;

/*
 * Admin page
 */
class Admin_Page implements WP_Integration, Options_Page {

	/**
	 * Add options page to the admin menu
	 */
	public function options_page() {
		\add_options_page(
			\esc_html__( 'Cart Notifications', 'cart-notifications-wc' ),     // Page title
			\esc_html__( 'Cart Notifications', 'cart-notifications-wc' ),     // Menu title
			'manage_options',         // Capability required to access the page
			'cart-notifications-wc',     // Menu slug
			[ $this, 'render' ] // Callback function to render the page
		);
	}

	/**
	 * Render the options page
	 */
	public static function render(): void {
		include \plugin_dir_path( \dirname( __FILE__, 2 ) ) . 'admin-templates/admin-form.php';
	}

    /**
     * verify the wp nonce
     */
	private function check_nonce(): void {
		if ( ! isset( $_POST['_wpnonce'] ) || ! \wp_verify_nonce( $_POST['_wpnonce'] ) ) {
			\wp_die( \esc_html__( 'Sorry, your nonce did not verify.', 'cart-notifications-wc' ) );
		}
	}

    /**
     * Check if the current user has the required permissions
     */
	private function check_permissions(): void {
		if ( ! \current_user_can( 'manage_options' ) ) {
			\wp_die( \esc_html__( 'Sorry, you are not allowed to access this page.', 'cart-notifications-wc' ) );
		}
	}

    /**
     * Save the options page form data
     */
	private function options_data(): array {
		$layout      = isset( $_POST['layout'] ) ? \sanitize_text_field( $_POST['layout'] ) : 'default';
		$position    = isset( $_POST['position'] ) ? \sanitize_text_field( $_POST['position'] ) : 'top-right';
		$close_after = isset( $_POST['close_after'] ) ? (int) \sanitize_text_field( $_POST['close_after'] ) : 5;
		if ( isset( $_POST['conditions'] ) ) {
			if ( is_array( $_POST['conditions'] ) ) {
				$conditions = array_map( '\sanitize_text_field', $_POST['conditions'] );
			} else {
				$conditions = [ \sanitize_text_field( $_POST['conditions'] ) ];
			}
		} else {
			$conditions = [ 'all' ];
		}

		return \apply_filters( 'cart_notifications_wc_options_before_save', [
			'style'       => $layout,
			'position'    => $position,
			'close_after' => $close_after,
			'display_on'  => $conditions,
		] );
	}

	public function process_save_options(): void {
		// verify nonce
		$this->check_nonce();
		// check permissions
		$this->check_permissions();
		// save form data
		\update_option( 'cart_notifications_wc_options', $this->options_data() );
		\wp_safe_redirect( \admin_url( 'options-general.php?page=cart-notifications-wc&status=success' ) );
		exit;
	}

	public function admin_notices(): void {
		if ( isset( $_GET['status'], $_GET['page'] ) && $_GET['status'] === 'success'
		     && $_GET['page'] === 'cart-notifications-wc'
		) {
			?>
            <div class="notice notice-success is-dismissible">
                <p><?php
					\esc_html_e( 'Settings saved successfully.', 'cart-notifications-wc' ) ?></p>
            </div>
			<?php
		}
	}

	public function load_hooks(): void {
		\add_action( 'admin_menu', [ $this, 'options_page' ] );
		\add_action( 'admin_post_cart_notifications_wc_save_options', [ $this, 'process_save_options' ] );
		\add_action( 'admin_notices', [ $this, 'admin_notices' ] );
	}
}