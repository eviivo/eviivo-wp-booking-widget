<?php

	namespace Eviivo\Plugin\Ajax\Calls;

	use Eviivo\Plugin\Ajax\JsonRequest;
	use Eviivo\Plugin\Model\BookingForm;
	use Eviivo\Plugin\Admin\Pages\Forms\BookingFormConfig;

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
	