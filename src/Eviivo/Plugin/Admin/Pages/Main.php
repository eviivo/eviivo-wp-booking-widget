<?php

	namespace eviivo\Plugin\Admin\Pages;

	use eviivo\Plugin\Admin\Pages\Forms\BookingFormConfig;
	use eviivo\Plugin\Helpers\Php\View;
	use eviivo\Plugin\Admin\Pages\Base;
	use eviivo\Plugin\Model\BookingForm;

	/**
	 *  
	 */
	class Main extends Base {

		/**
		 *
		 * @var BookingFormConfig
		 */
		protected $form;
		
		/**
		 *  
		 */
		public function init() {
			$this->form = new BookingFormConfig();
			$this->form->setAction(menu_page_url($this->getSlug(), false));
		}

		/**
		 * 
		 * @return View 
		 */
		public function render() {

			if (!empty($_GET['eviivo_saved'])) {
				$this->setMessage(__('Information saved.', 'eviivo-booking-widget'));
			}

			return new View(array(
				'adminPage' => $this,
				'bookingForm' => $this->form->getBookingFormModel(),
				'form' => $this->form
			));
		}

		/**
		 * 
		 * @return string 
		 */
		public function getSlug() {

			return 'eviivo-booking-widget';
		}

		/**
		 * 
		 * @return string 
		 */
		public function getTitle() {

			return 'eviivo booking widget';
		}

		/**
		 *  
		 */
		public function post() {

			if ($this->form->isValid($_POST[$this->form->getName()])) {
				$data = $this->form->getData();
				update_option(BookingForm::WP_OPTION_NAME, $data, false);
				wp_redirect(menu_page_url($this->getSlug(), false) . '&eviivo_saved=1');
				exit();
			} else {
				$this->setMessage($this->form->getMessage(), 'error');
			}

			parent::post();
		}

	}
