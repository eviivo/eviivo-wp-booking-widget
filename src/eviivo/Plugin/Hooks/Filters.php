<?php

	namespace eviivo\Plugin\Hooks;

	use eviivo\Plugin\Model\BookingForm;

	/**
	 *  
	 */
	class Filters extends Base {

		static public function script_loader_tag($tag, $handle, $src) {

			if ($handle === 'eviivo-booking-js') {
				if (BookingForm::getInstanceCount() === 0) {
					$tag = '';
				}
			}

			return $tag;
		}

	}
