<?php

	/**
	 * Plugin Name: eviivo Booking Widget
	 * Plugin URI:  https://eviivo.com
	 * Description: eviivo booking form generator
	 * Version:     1.0
	 * Author:      eviivo
	 * Author URI:  https://eviivo.com
	 * Domain Path: /languages
	 * Text Domain: eviivo-booking-widget
	 */
	define('EVIIVO_BOOKING_WIDGET_PLUGIN_MIN_PHP_VER', '5.3');

	function eviivoBookingWidgetPluginBootstrap() {

		if (version_compare(PHP_VERSION, EVIIVO_BOOKING_WIDGET_PLUGIN_MIN_PHP_VER, '<')) {

			function eviivo_booking_widget_plugin_admin_notice() {
				echo '<div class="error"><p>';

				printf(__('eviivo Booking Widget plugin requires at least PHP %s. You have %s'), EVIIVO_BOOKING_WIDGET_PLUGIN_MIN_PHP_VER, phpversion());

				echo '</p></div>';
			}

			add_action('admin_notices', 'eviivo_booking_widget_plugin_admin_notice');
		} else {
			require(dirname(__FILE__) . '/bootstrap.php');
		}
	}

	eviivoBookingWidgetPluginBootstrap();
