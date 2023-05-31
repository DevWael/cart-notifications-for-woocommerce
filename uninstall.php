<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// delete saved plugin options
delete_option( 'cart_notifications_wc_options' );