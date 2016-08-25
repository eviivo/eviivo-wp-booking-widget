<?php

	namespace eviivo\Plugin\Admin\Hooks;

	use eviivo\Plugin\Hooks\Base;
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
		static public function admin_enqueue_scripts($hook) {

			wp_enqueue_style('eviivo-booking-admin-css', Util::getPluginUrl('/assets/dist/css/admin.min.css', true));
			wp_enqueue_style('eviivo-booking-css', Util::getPluginUrl('/assets/dist/css/front.min.css', true));

			wp_enqueue_script('eviivo-booking-js', Util::getPluginUrl('/assets/dist/js/front.min.js', true), array(), null, true);
			wp_enqueue_script('eviivo-booking-admin-js', Util::getPluginUrl('/assets/dist/js/admin.min.js', true), array(), null, true);

			$bookingForm = new BookingForm();
			$previewNounce = wp_create_nonce('previewNounce');

			wp_localize_script('eviivo-booking-admin-js', 'eviivoConfig', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'previewNounce' => $previewNounce,
				'bookingForm' => $bookingForm->getData(),
				'bookingFormConfigScript' => Util::getAjaxLink('BookingFormHtml'),
				'genericErrorMessage' => __('Error generating the preview. Please try again later', 'eviivo-booking-widget'),
				'loadingMessage' => __('Loading', 'eviivo-booking-widget'),
				'addLabel' => __('Add', 'eviivo-booking-widget'),
				'cancelLabel' => __('Cancel', 'eviivo-booking-widget'),
				'updateLabel' => __('Update', 'eviivo-booking-widget'),
				'insertLabel' => __('Insert', 'eviivo-booking-widget'),
				'shortCodeTitle' => __('Embed eviivo booking form', 'eviivo-booking-widget'),
				'defaultWidgetName' => __('Booking Widget', 'eviivo-booking-widget'),
				'widgetEditTitle' => __('Widget edit %s', 'eviivo-booking-widget'),
				'shortcode' => BookingForm::WP_SHORT_CODE
			));
		}

	}
