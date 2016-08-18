<?php

	namespace eviivo\Plugin\Ajax\Calls;

	use eviivo\Plugin\Ajax\JsonRequest;
	use eviivo\Plugin\Model\BookingForm;
	use eviivo\Plugin\Admin\Pages\Forms\BookingFormConfig;

	class PreviewBookingForm extends JsonRequest {

		/**
		 * 
		 * @param array $args
		 * @return array
		 */
		public function process($args = array()) {
			
			if (!current_user_can('manage_options')) {
				$this->notAllowed();
				return false;
			}

			$bookingFormConfig = new BookingFormConfig();

			if ($bookingFormConfig->isValid($_POST)) {

				$bookingForm = new BookingForm();
				$bookingForm->hydrateFromArray($bookingFormConfig->getData());

				return array(
					'form' => $bookingForm->getHtml()
				);
			} else {
				return array(
					'form' => $bookingFormConfig->getMessage()
				);
			}
		}

	}
