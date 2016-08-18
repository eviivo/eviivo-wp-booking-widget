<?php

	namespace Eviivo\Plugin\Ajax\Calls;

	use Eviivo\Plugin\Ajax\JsonRequest;
	use Eviivo\Plugin\Admin\Pages\Forms\BookingFormConfig;
	use Eviivo\Plugin\Admin\Pages\Main;

	class BookingFormHtml extends JsonRequest {

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
			$bookingFormConfig->includePreviewTab(true);

			$html = $bookingFormConfig->render();

			$adminPage = new Main();
			$html .= '<div class="info">' . sprintf(__('You can change all the defaults from the plugin <a target="_blank" href="%s">page</a>', 'eviivo-booking-widget'), get_admin_url() . 'admin.php?page=' . $adminPage->getSlug()) . '</div>';

			return array(
				'form' => $html
			);
		}

	}
	