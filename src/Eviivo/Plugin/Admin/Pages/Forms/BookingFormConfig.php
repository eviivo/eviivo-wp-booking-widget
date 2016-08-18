<?php

	namespace eviivo\Plugin\Admin\Pages\Forms;

	use eviivo\Plugin\Elements\Form;
	use eviivo\Plugin\Elements\Form\Checkbox;
	use eviivo\Plugin\Elements\Form\Text;
	use eviivo\Plugin\Elements\Form\Color;
	use eviivo\Plugin\Elements\Form\Number;
	use eviivo\Plugin\Elements\Form\Date;
	use eviivo\Plugin\Elements\Form\Select;
	use eviivo\Plugin\Elements\Form\Button;
	use eviivo\Plugin\Model\BookingForm;
	use eviivo\Plugin\Model\BookingForm as BookingForModel;
	use eviivo\Plugin\Helpers\Php\View;
	use eviivo\Plugin\Util;

	/**
	 *  
	 */
	class BookingFormConfig extends Form {

		/**
		 *
		 * @var BookingForModel
		 */
		protected $bookingFormModel;

		/**
		 *
		 * @var boolean
		 */
		protected $includePreviewTab = false;

		/**
		 *  
		 */
		public function __construct($options = array()) {
			$this->addClass('eviivo-booking-form-config');

			$this->addSetupFields();
			$this->addLabelFields();
			$this->addCheckInOutDatesFields();
			$this->addRoomCountFields();
			$this->addMiscFields();
			$this->addColorFields();
			$this->addFontSizeFields();

			$submit = new Button('submit', __('Save', 'eviivo-booking-widget'));
			$submit->addClass('eviivo-btn-primary');
			$this->addChild($submit);

			parent::__construct();

			$this->setBookingFormModel(new BookingForModel($options));
		}

		/**
		 *  
		 */
		private function addSetupFields() {
			$hotelReference = new Text('hotelReference', __('Property short name', 'eviivo-booking-widget'));
			$hotelReference->setRequired(true);
			$this->addChild($hotelReference);

			$languageCode = new Select('languageCode', __('Language', 'eviivo-booking-widget'));
			$languageCode->setOptions(Util::getConfig('languages', array()));
			$this->addChild($languageCode);
			
			$layout = new Select('layout', __('Layout', 'eviivo-booking-widget'));
			$layout->setOptions(array(
				'portrait' => __('Portrait', 'eviivo-booking-widget'),
				'landscape' => __('Landscape', 'eviivo-booking-widget')
			));
			$this->addChild($layout);
		}
		
		/**
		 *  
		 */
		private function addCheckInOutDatesFields() {

			$dateTypes = array(
				'absolute' => __('Absolute', 'eviivo-booking-widget'),
				'relative' => __('Relative to today', 'eviivo-booking-widget'),
				'relative_to_day_of_week' => __('Relative to day of week', 'eviivo-booking-widget'),
			);

			$daysOfTheWeek = array(
				__('Monday', 'eviivo-booking-widget'),
				__('Tuesday', 'eviivo-booking-widget'),
				__('Wednesday', 'eviivo-booking-widget'),
				__('Thursday', 'eviivo-booking-widget'),
				__('Friday', 'eviivo-booking-widget'),
				__('Saturday', 'eviivo-booking-widget'),
				__('Sunday', 'eviivo-booking-widget')
			);

			$checkInDateType = new Select('checkInDateType', __('Check In Date Type', 'eviivo-booking-widget'));
			$checkInDateType->setOptions($dateTypes);
			$this->addChild($checkInDateType);

			$checkInDateRelativeDays = new Number('checkInDateRelativeDays', __('when to accept bookings from', 'eviivo-booking-widget'));
			$this->addChild($checkInDateRelativeDays);

			$absoluteCheckInDate = new Date('absoluteCheckInDate', __('Check In date', 'eviivo-booking-widget'));
			$this->addChild($absoluteCheckInDate);

			$checkInDateRelativeDay = new Select('checkInDateRelativeDay', __('Check In Relative Day', 'eviivo-booking-widget'));
			$checkInDateRelativeDay->setOptions($daysOfTheWeek);
			$this->addChild($checkInDateRelativeDay);


			$checkOutDateType = new Select('checkOutDateType', __('Check Out Date Type', 'eviivo-booking-widget'));
			$dateTypes['relative_to_checkin_date'] = __('Relative to check in date', 'eviivo-booking-widget');
			$checkOutDateType->setOptions($dateTypes);
			$this->addChild($checkOutDateType);

			$checkOutDateRelativeDays = new Number('checkOutDateRelativeDays', __('When to accept bookings from', 'eviivo-booking-widget'));
			$this->addChild($checkOutDateRelativeDays);

			$absoluteCheckOutDate = new Date('absoluteCheckOutDate', __('Check Out date', 'eviivo-booking-widget'));
			$this->addChild($absoluteCheckOutDate);

			$checkOutDateRelativeDay = new Select('checkOutDateRelativeDay', __('Check Out Relative Day', 'eviivo-booking-widget'));
			$checkOutDateRelativeDay->setOptions($daysOfTheWeek);
			$this->addChild($checkOutDateRelativeDay);
		}

		/**
		 *  
		 */
		private function addLabelFields() {

			foreach (BookingForm::getLabels() as $name => $label) {
				$field = new Text($name, $label);
				$field->setAttribute('placeholder', $label);
				$this->addChild($field);
			}
		}

		/**
		 *  
		 */
		private function addRoomCountFields() {
			$roomCount = new Number('roomCount', __('How many rooms does the property have?', 'eviivo-booking-widget'));
			$this->addChild($roomCount);

			$adultCount = new Number('adultCount', __('Maximum number of adults per room', 'eviivo-booking-widget'));
			$this->addChild($adultCount);

			$childCount = new Number('childCount', __('Maximum number of children per room', 'eviivo-booking-widget'));
			$this->addChild($childCount);

			$defaultRoomCount = new Number('defaultRoomCount', __('Default room selection', 'eviivo-booking-widget'));
			$this->addChild($defaultRoomCount);

			$defaultAdultCount = new Number('defaultAdultCount', __('Default number of adults per room', 'eviivo-booking-widget'));
			$this->addChild($defaultAdultCount);

			$defaultChildCount = new Number('defaultChildCount', __('Default number of children per room', 'eviivo-booking-widget'));
			$this->addChild($defaultChildCount);
		}

		/**
		 * 
		 * @return BookingForModel 
		 */
		public function getBookingFormModel() {
			return $this->bookingFormModel;
		}

		/**
		 * 
		 * @param BookingForModel $bookingFormModel
		 * @return \eviivo\Plugin\Admin\Pages\Forms\BookingFormConfig 
		 */
		public function setBookingFormModel(BookingForModel $bookingFormModel) {

			$this->bookingFormModel = $bookingFormModel;
			$this->hydrateFromArray($bookingFormModel->getData());

			return $this;
		}

		/**
		 * 
		 * @return string 
		 */
		public function render() {

			$view = new View(array(
				'form' => $this,
				'bookingForm' => $this->bookingFormModel,
				'includePreviewTab' => $this->includePreviewTab
			));

			return $view->render(Util::getViewPath('admin/booking-form-config'));
		}

		/**
		 * 
		 * @param boolean $flag
		 * @return \eviivo\Plugin\Admin\Pages\Forms\BookingFormConfig 
		 */
		public function includePreviewTab($flag) {
			$this->includePreviewTab = (boolean) $flag;

			return $this;
		}

		/**
		 *  
		 */
		private function addMiscFields() {
			$excludeJavascript = new Checkbox('excludeJavascript', __('Exclude javascript', 'eviivo-booking-widget'));
			$excludeJavascript->setAttribute('value', '1');
			$this->addChild($excludeJavascript);

			$excludeCss = new Checkbox('excludeCss', __('Exclude CSS', 'eviivo-booking-widget'));
			$excludeCss->setAttribute('value', '1');
			$this->addChild($excludeCss);
			
			$theme = new Select('theme', __('Theme', 'eviivo-booking-widget'));
			$theme->setOptions(array(
				'light' => __('Light', 'eviivo-booking-widget'),
				'dark' => __('Dark', 'eviivo-booking-widget'),
				'custom' => __('Custom', 'eviivo-booking-widget'),
				'none' => __('None', 'eviivo-booking-widget')
			));
			$this->addChild($theme);
		}

		/**
		 *  
		 */
		private function addColorFields() {
			
			$textColor = new Color('textColor', __('Text color', 'eviivo-booking-widget'));
			$this->addChild($textColor);

			$backgroundColor = new Color('backgroundColor', __('Background color', 'eviivo-booking-widget'));
			$this->addChild($backgroundColor);

			$buttonTextColor = new Color('buttonTextColor', __('Submit Button Text color', 'eviivo-booking-widget'));
			$this->addChild($buttonTextColor);

			$buttonBackgroundColor = new Color('buttonBackgroundColor', __('Submit Button Background color', 'eviivo-booking-widget'));
			$this->addChild($buttonBackgroundColor);

			$hasTransparentBackground = new Checkbox('hasTransparentBackground', __('Has transparent background', 'eviivo-booking-widget'));
			$hasTransparentBackground->setValue('1');
			$this->addChild($hasTransparentBackground);
		}

		/**
		 *  
		 */
		private function addFontSizeFields() {
			
			$fontSize = new Number('fontSize', __('Font size', 'eviivo-booking-widget'));
			$this->addChild($fontSize);

			$buttonFontSize = new Number('buttonFontSize', __('Submit Button Font size', 'eviivo-booking-widget'));
			$this->addChild($buttonFontSize);
		}

	}
