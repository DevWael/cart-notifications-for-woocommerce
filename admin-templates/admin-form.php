<?php
// -*- coding: utf-8 -*-

declare( strict_types=1 );

defined( '\ABSPATH' ) || exit;

$saved_settings = \get_option( 'cart_notifications_wc_options' );
?>
    <div class="wrap">
        <h1><?php
			echo \esc_html( \get_admin_page_title() ) ?></h1>
        <form method="post" action="<?php
		echo \esc_url( \admin_url( 'admin-post.php' ) ) ?>">
            <table class="form-table">
                <tr>
                    <th scope="row"><?php
						\esc_html_e( 'Layout', 'cart-notifications-wc' ) ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php
									\esc_html_e( 'Layout', 'cart-notifications-wc' ) ?></span></legend>
                            <label><input type="radio" name="layout" value="default"
									<?php
									echo isset( $saved_settings['style'] )
										? \checked( $saved_settings['style'], 'default', false ) : '';
									?>><?php
								\esc_html_e( 'Default', 'cart-notifications-wc' ) ?></label><br>
                            <label><input type="radio" name="layout" value="modern"
									<?php
									echo isset( $saved_settings['style'] )
										? \checked( $saved_settings['style'], 'modern', false ) : '';
									?>><?php
								\esc_html_e( 'Modern', 'cart-notifications-wc' ) ?></label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php
						\esc_html_e( 'Position', 'cart-notifications-wc' ) ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php
									\esc_html_e( 'Position', 'cart-notifications-wc' ) ?></span></legend>
                            <label><input type="radio" name="position" value="top-right"
									<?php
									echo isset( $saved_settings['position'] )
										? \checked( $saved_settings['position'], 'top-right', false ) : '';
									?>><?php
								\esc_html_e( 'Top', 'cart-notifications-wc' ) ?></label><br>
                            <label><input type="radio" name="position" value="bottom-right"
									<?php
									echo isset( $saved_settings['position'] )
										? \checked( $saved_settings['position'], 'bottom-right', false ) : '';
									?>><?php
								\esc_html_e( 'Bottom', 'cart-notifications-wc' ) ?></label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="close-after"><?php
							\esc_html_e( 'Close after (Seconds)', 'cart-notifications-wc' ) ?></label></th>
                    <td>
                        <legend class="screen-reader-text"><span><?php
								\esc_html_e( 'Close after (Seconds)', 'cart-notifications-wc' ) ?></span></legend>
                        <input name="close_after" type="number" id="close-after" required value="<?php
						echo isset( $saved_settings['close_after'] ) ? \esc_attr( $saved_settings['close_after'] )
							: 5;
						?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="conditions"><?php
							\esc_html_e( 'Display conditions', 'cart-notifications-wc' ) ?></label></th>
                    <td>
                        <select name="conditions[]" multiple id="conditions" required>
                            <option value="none"<?php
							echo isset( $saved_settings['display_on'] ) && is_array( $saved_settings['display_on'] )
							     && in_array( 'none', $saved_settings['display_on'] ) ? 'selected' : '';
							?>><?php
								\esc_html_e( 'None', 'cart-notifications-wc' ) ?></option>
                            <option value="all" <?php
							echo isset( $saved_settings['display_on'] ) && is_array( $saved_settings['display_on'] )
							     && in_array( 'all', $saved_settings['display_on'] ) ? 'selected' : '';
							?>><?php
								\esc_html_e( 'All pages', 'cart-notifications-wc' ) ?></option>
                            <option value="shop_archive"
								<?php
								echo isset( $saved_settings['display_on'] )
								     && is_array( $saved_settings['display_on'] )
								     && in_array( 'shop_archive', $saved_settings['display_on'] ) ? 'selected' : '';
								?>
                            ><?php
								\esc_html_e( 'Shop archive', 'cart-notifications-wc' ) ?></option>
                            <option value="shop_archive_cats"<?php
							echo isset( $saved_settings['display_on'] ) && is_array( $saved_settings['display_on'] )
							     && in_array( 'shop_archive_cats', $saved_settings['display_on'] ) ? 'selected'
								: '';
							?>><?php
								\esc_html_e( 'Shop Archive Categories', 'cart-notifications-wc' ) ?></option>
                            <option value="shop_archive_tags"<?php
							echo isset( $saved_settings['display_on'] ) && is_array( $saved_settings['display_on'] )
							     && in_array( 'shop_archive_tags', $saved_settings['display_on'] ) ? 'selected'
								: '';
							?>><?php
								\esc_html_e( 'Shop Archive Tags', 'cart-notifications-wc' ) ?></option>
                            <option value="shop_archive_attrs"<?php
							echo isset( $saved_settings['display_on'] ) && is_array( $saved_settings['display_on'] )
							     && in_array( 'shop_archive_attrs', $saved_settings['display_on'] ) ? 'selected'
								: '';
							?>><?php
								\esc_html_e( 'Shop Archive Product Attributes', 'cart-notifications-wc' ) ?></option>
                            <option value="single_product"<?php
							echo isset( $saved_settings['display_on'] ) && is_array( $saved_settings['display_on'] )
							     && in_array( 'single_product', $saved_settings['display_on'] ) ? 'selected' : '';
							?>><?php
								\esc_html_e( 'Single Products', 'cart-notifications-wc' ) ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="action" value="cart_notifications_wc_save_options">
			<?php
			\wp_nonce_field();
			\submit_button();
			?>
        </form>
    </div>
<?php