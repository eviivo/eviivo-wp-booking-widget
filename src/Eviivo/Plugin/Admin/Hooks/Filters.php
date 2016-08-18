<?php

	namespace eviivo\Plugin\Admin\Hooks;

	use eviivo\Plugin\Hooks\Base;
	use eviivo\Plugin\Util;

	/**
	 *  
	 */
	class Filters extends Base {

		/**
		 * 
		 * @param array $pluginArray
		 * @return array
		 */
		static public function mce_external_plugins($pluginArray) {
			$pluginArray['eviivo_booking_form'] = Util::getPluginUrl('assets/dist/js/tinymce-booking-form.min.js');

			return $pluginArray;
		}

		/**
		 * 
		 * @param array $buttons 
		 * @return array
		 */
		static public function mce_buttons($buttons) {
			$buttons [] = 'eviivo_booking_form';
			return $buttons;
		}

	}
