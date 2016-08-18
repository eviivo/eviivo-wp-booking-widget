<?php

	namespace eviivo\Plugin\Hooks;

	use eviivo\Plugin\Util;
	use eviivo\Plugin\Model\BookingForm;

	/**
	 *  
	 */
	class Actions extends Base {

		/**
		 * 
		 * @param string $hook 
		 */
		static public function wp_enqueue_scripts() {

			$bookingForm = new BookingForm();

			if (!$bookingForm->getExcludeCss()) {
				wp_enqueue_style('eviivo-booking-css', Util::getPluginUrl('/assets/dist/css/front.min.css', true));
			}

			if (!$bookingForm->getExcludeJavascript()) {
				wp_enqueue_script('eviivo-booking-js', Util::getPluginUrl('/assets/dist/js/front.min.js', true), array(), null, true);
			}
		}

		/**
		 *  
		 */
		static public function plugins_loaded() {

			load_plugin_textdomain('eviivo-booking-widget', false, basename(Util::getPluginPath()) . '/languages/');
		}

	}
