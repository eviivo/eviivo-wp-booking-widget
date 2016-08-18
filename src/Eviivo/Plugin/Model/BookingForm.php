<?php

	namespace eviivo\Plugin\Model;

	use eviivo\Plugin\Admin\Pages\Main;
	use eviivo\Plugin\Helpers\Php\View;
	use eviivo\Plugin\Util;

	/**
	 *  
	 */
	class BookingForm {

		const WP_OPTION_NAME = 'eviivo_booking_form';
		const WP_SHORT_CODE = 'eviivo-booking-form';

		/**
		 *
		 * @var int
		 */
		static private $instancesCount = 0;

		/**
		 *
		 * @var array  
		 */
		static private $cachedLabels = array();

		/**
		 *
		 * @var int
		 */
		private $id = 0;

		/**
		 *
		 * @var string
		 */
		private $layout = 'portrait';

		/**
		 *
		 * @var string
		 */
		private $hotelReference = '';

		/**
		 * @var string
		 */
		private $languageCode = 'en_US';

		/**
		 *
		 * @var string
		 */
		private $theme = 'light';

		/**
		 *
		 * @var string
		 */
		private $checkInDateType = 'relative';

		/**
		 *
		 * @var string
		 */
		private $checkOutDateType = 'relative_to_checkin_date';

		/**
		 *
		 * @var int
		 */
		private $checkInDateRelativeDays = 0;

		/**
		 *
		 * @var int
		 */
		private $checkOutDateRelativeDays = 1;

		/**
		 *
		 * @var type  
		 */
		private $labelCheckIn = '';

		/**
		 *
		 * @var type  
		 */
		private $labelCheckOut = '';

		/**
		 *
		 * @var type  
		 */
		private $labelRooms = '';

		/**
		 *
		 * @var type  
		 */
		private $labelRoom = '';

		/**
		 *
		 * @var type  
		 */
		private $labelAdults = '';

		/**
		 *
		 * @var type  
		 */
		private $labelChildren = '';

		/**
		 *
		 * @var string
		 */
		private $labelShowPrices = '';

		/**
		 *
		 * @var \DateTime
		 */
		private $absoluteCheckInDate;

		/**
		 *
		 * @var \DateTime
		 */
		private $absoluteCheckOutDate;

		/**
		 *
		 * @var int
		 */
		private $checkInDateRelativeDay = 0;

		/**
		 *
		 * @var int
		 */
		private $checkOutDateRelativeDay = 0;

		/**
		 *
		 * @var int
		 */
		private $roomCount = 50;

		/**
		 *
		 * @var int
		 */
		private $adultCount = 5;

		/**
		 *
		 * @var int
		 */
		private $childCount = 5;

		/**
		 *
		 * @var int
		 */
		private $defaultRoomCount = 1;

		/**
		 *
		 * @var int
		 */
		private $defaultAdultCount = 2;

		/**
		 *
		 * @var int
		 */
		private $defaultChildCount = 0;

		/**
		 *
		 * @var boolean
		 */
		private $includeFormTag = true;

		/**
		 *
		 * @var boolean
		 */
		private $excludeJavascript = false;

		/**
		 *
		 * @var boolean
		 */
		private $excludeCss = false;

		/**
		 *
		 * @var string
		 */
		private $textColor = '#444444';

		/**
		 *
		 * @var string
		 */
		private $backgroundColor = '#FFFFFF';

		/**
		 *
		 * @var string
		 */
		private $buttonTextColor = '#FFFFFF';

		/**
		 *
		 * @var string
		 */
		private $buttonBackgroundColor = '#333333';

		/**
		 *
		 * @var int
		 */
		private $fontSize = 14;

		/**
		 *
		 * @var int
		 */
		private $buttonFontSize = 14;

		/**
		 *
		 * @var boolean
		 */
		private $hasTransparentBackground = false;

		/**
		 * 
		 * @param array $options 
		 */
		public function __construct($options = array()) {

			$this->absoluteCheckInDate = new \DateTime();
			$this->absoluteCheckOutDate = new \DateTime();

			$defaultOptions = get_option(static::WP_OPTION_NAME, array());
			$optionsToParse = array_merge($defaultOptions, $options);

			$this->id = static::$instancesCount;
			++static::$instancesCount;

			$this->hydrateFromArray($optionsToParse);
		}

		/**
		 * 
		 * @return string 
		 */
		public function getLayout() {
			return $this->layout;
		}

		/**
		 * 
		 * @param string $layout
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setLayout($layout) {
			$this->layout = $layout;
			return $this;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getHtml() {
			global $locale;

			$oldLocale = $locale;
			Util::changeLanguage($this->getLanguageCode());
			static::reloadLabels();

			$view = new View(array(
				'bookingForm' => $this
			));
			$html = $view->render(Util::getViewPath('booking-form'));


			Util::changeLanguage($oldLocale);

			static::reloadLabels();

			return $html;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getHotelReference() {
			return $this->hotelReference;
		}

		/**
		 * 
		 * @param string $hotelReference
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setHotelReference($hotelReference) {
			$this->hotelReference = $hotelReference;
			return $this;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getErrorMessage() {
			if (empty($this->hotelReference)) {

				$adminPage = new Main();

				return sprintf(__('Please fill in the Hotel Refrence field in the <a href="%s">Setup</a> tab.', 'eviivo-booking-widget'), menu_page_url($adminPage->getSlug(), false) . '#eviivo-booking-form-generator-setup');
			}

			return '';
		}

		/**
		 * 
		 * @return boolean 
		 */
		public function isValid() {

			if (empty($this->hotelReference)) {
				return false;
			}

			return true;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getLanguageCode() {
			return $this->languageCode;
		}

		/**
		 * 
		 * @param string $languageCode
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setLanguageCode($languageCode) {
			$this->languageCode = $languageCode;
			return $this;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getTheme() {
			return $this->theme;
		}

		/**
		 * 
		 * @param string $theme
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setTheme($theme) {
			$this->theme = $theme;
			return $this;
		}

		/**
		 * 
		 * @return array
		 */
		public function getData() {

			$columns = array(
				'hotelReference',
				'languageCode',
				'layout',
				'theme',
				'checkInDateType',
				'checkInDateRelativeDays',
				'checkOutDateType',
				'checkOutDateRelativeDays',
				'absoluteCheckInDate',
				'absoluteCheckOutDate',
				'checkInDateRelativeDay',
				'checkOutDateRelativeDay',
				'roomCount',
				'adultCount',
				'childCount',
				'defaultRoomCount',
				'defaultAdultCount',
				'defaultChildCount',
				'excludeJavascript',
				'excludeCss',
				'textColor',
				'backgroundColor',
				'fontSize',
				'buttonTextColor',
				'buttonBackgroundColor',
				'buttonFontSize',
				'hasTransparentBackground',
			);

			foreach (array_keys(static::getLabels()) as $name) {
				$columns [] = $name;
			}

			$data = array();
			foreach ($columns as $column) {
				$getter = 'get' . ucfirst($column);
				$value = $this->$getter();

				if ($value instanceof \DateTime) {
					$data[$column] = $value->getTimestamp();
				} else {
					$data[$column] = $value;
				}
			}

			return $data;
		}

		/**
		 * 
		 * @return string
		 */
		public function getUniquId() {

			return 'eviivo-booking-form-' . $this->getId();
		}

		/**
		 * 
		 * @return int
		 */
		public function getId() {

			return $this->id;
		}

		/**
		 * 
		 * @param string $shortCode
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function hydrateFromShortCode($shortCode) {

			$data = array();
			$attributeSegments = explode(' ', substr($shortCode, strlen('[' . static::WP_SHORT_CODE), -1));
			foreach ($attributeSegments as $segment) {
				if ($segment) {
					$segment = explode('=', $segment);
					$name = array_shift($segment);
					$value = substr(implode('=', $segment), 1, -1);
					if ($name) {
						$data[$name] = $value;
					}
				}
			}

			$this->hydrateFromArray($data);

			return $this;
		}

		/**
		 * 
		 * @param array $data
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function hydrateFromArray($data) {

			foreach ($data as $property => $value) {
				$method = 'set' . ucfirst($property);

				if (method_exists($this, $method)) {
					if (isset($this->$property) && $this->$property instanceof \DateTime) {
						$date = new \DateTime();

						if (!empty($value)) {
							if (is_numeric($value)) {
								$date->setTimestamp($value);
							} elseif (is_string($value)) {
								$date = \DateTime::createFromFormat('Y-m-d', $value);
							} elseif ($value instanceof \DateTime) {
								$date = $value;
							}
						}

						$this->$method($date);
					} else {
						$this->$method($value);
					}
				}
			}

			return $this;
		}

		/**
		 * 
		 * @return array 
		 */
		public function getCalendarI18n() {

			return array(
				'previousMonth' => __('Previous Month', 'eviivo-booking-widget'),
				'nextMonth' => __('Next Month', 'eviivo-booking-widget'),
				'months' => array(
					__('January', 'eviivo-booking-widget'),
					__('February', 'eviivo-booking-widget'),
					__('March', 'eviivo-booking-widget'),
					__('April', 'eviivo-booking-widget'),
					__('May', 'eviivo-booking-widget'),
					__('June', 'eviivo-booking-widget'),
					__('July', 'eviivo-booking-widget'),
					__('August', 'eviivo-booking-widget'),
					__('September', 'eviivo-booking-widget'),
					__('October', 'eviivo-booking-widget'),
					__('November', 'eviivo-booking-widget'),
					__('December', 'eviivo-booking-widget')
				),
				'weekdays' => array(
					__('Sunday', 'eviivo-booking-widget'),
					__('Monday', 'eviivo-booking-widget'),
					__('Tuesday', 'eviivo-booking-widget'),
					__('Wednesday', 'eviivo-booking-widget'),
					__('Thursday', 'eviivo-booking-widget'),
					__('Friday', 'eviivo-booking-widget'),
					__('Saturday', 'eviivo-booking-widget')
				),
				'weekdaysShort' => array(
					__('Sun', 'eviivo-booking-widget'),
					__('Mon', 'eviivo-booking-widget'),
					__('Tue', 'eviivo-booking-widget'),
					__('Wed', 'eviivo-booking-widget'),
					__('Thu', 'eviivo-booking-widget'),
					__('Fri', 'eviivo-booking-widget'),
					__('Sat', 'eviivo-booking-widget')
				)
			);
		}

		/**
		 * 
		 * @return string 
		 */
		public function getCheckInDateType() {
			return $this->checkInDateType;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getCheckOutDateType() {
			return $this->checkOutDateType;
		}

		/**
		 * 
		 * @param string $checkInDateType
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setCheckInDateType($checkInDateType) {
			$this->checkInDateType = $checkInDateType;
			return $this;
		}

		/**
		 * 
		 * @param string $checkOutDateType
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setCheckOutDateType($checkOutDateType) {
			$this->checkOutDateType = $checkOutDateType;
			return $this;
		}

		/**
		 * 
		 * @return int 
		 */
		public function getCheckInDateRelativeDays() {
			return $this->checkInDateRelativeDays;
		}

		/**
		 * 
		 * @return int 
		 */
		public function getCheckOutDateRelativeDays() {
			return $this->checkOutDateRelativeDays;
		}

		/**
		 * 
		 * @param type $checkInDateRelativeDays
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setCheckInDateRelativeDays($checkInDateRelativeDays) {
			$this->checkInDateRelativeDays = $checkInDateRelativeDays;
			return $this;
		}

		/**
		 * 
		 * @param int $checkOutDateRelativeDays
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setCheckOutDateRelativeDays($checkOutDateRelativeDays) {
			$this->checkOutDateRelativeDays = $checkOutDateRelativeDays;
			return $this;
		}

		/**
		 * 
		 * @param string $type
		 * @param int $offset
		 * @param int $absolute
		 * @return int 
		 */
		private function getCheckDate($type, $offset, $absolute = 0, $dayOfWeek = 0) {
			switch ($type) {
				case 'relative':
					return strtotime('+' . ($offset ? $offset : '0') . ' days');
				case 'absolute':
					return !empty($absolute) ? $absolute->getTimestamp() : 0;
				case 'relative_to_day_of_week':
					$days = array(
						'monday',
						'tuesday',
						'wednesday',
						'thursday',
						'friday',
						'saturday',
						'sunday'
					);
					return strtotime('next ' . (!empty($days[$dayOfWeek]) ? $days[$dayOfWeek] : 'monday'), time());
			}
		}

		/**
		 * 
		 * @return int
		 */
		public function getCheckInDate() {

			return $this->getCheckDate($this->getCheckInDateType(), $this->getCheckInDateRelativeDays(), $this->getAbsoluteCheckInDate(), $this->getCheckInDateRelativeDay());
		}

		/**
		 * 
		 * @return int
		 */
		public function getCheckOutDate() {

			return $this->getCheckDate($this->getCheckOutDateType(), $this->getCheckOutDateRelativeDays(), $this->getAbsoluteCheckOutDate(), $this->getCheckOutDateRelativeDay());
		}

		/**
		 * 
		 * @param boolean $getRaw
		 * @return string
		 */
		public function getLabelCheckIn($getRaw = true) {

			return $this->getFormLabel('labelCheckIn', $getRaw);
		}

		/**
		 * 
		 * @param boolean $getRaw
		 * @return string
		 */
		public function getLabelCheckOut($getRaw = true) {

			return $this->getFormLabel('labelCheckOut', $getRaw);
		}

		/**
		 * 
		 * @param boolean $getRaw
		 * @return string
		 */
		public function getLabelRooms($getRaw = true) {

			return $this->getFormLabel('labelRooms', $getRaw);
		}

		/**
		 * 
		 * @param boolean $getRaw
		 * @return string
		 */
		public function getLabelRoom($getRaw = true) {

			return $this->getFormLabel('labelRoom', $getRaw);
		}

		/**
		 * 
		 * @param boolean $getRaw
		 * @return string
		 */
		public function getLabelAdults($getRaw = true) {

			return $this->getFormLabel('labelAdults', $getRaw);
		}

		/**
		 * 
		 * @param boolean $getRaw
		 * @return string
		 */
		public function getLabelChildren($getRaw = true) {

			return $this->getFormLabel('labelChildren', $getRaw);
		}

		/**
		 * 
		 * @param string $labelCheckin
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setLabelCheckin($labelCheckin) {
			$this->labelCheckIn = $labelCheckin;
			return $this;
		}

		/**
		 * 
		 * @param string $labelCheckOut
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setLabelCheckOut($labelCheckOut) {
			$this->labelCheckOut = $labelCheckOut;
			return $this;
		}

		/**
		 * 
		 * @param string $labelRooms
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setLabelRooms($labelRooms) {
			$this->labelRooms = $labelRooms;
			return $this;
		}

		/**
		 * 
		 * @param string $labelRoom
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setLabelRoom($labelRoom) {
			$this->labelRoom = $labelRoom;
			return $this;
		}

		/**
		 * 
		 * @param string $labelAdults
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setLabelAdults($labelAdults) {
			$this->labelAdults = $labelAdults;
			return $this;
		}

		/**
		 * 
		 * @param string $labelChildren
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setLabelChildren($labelChildren) {
			$this->labelChildren = $labelChildren;
			return $this;
		}

		/**
		 * 
		 * @return array
		 */
		static public function getLabels() {

			if (empty(static::$cachedLabels)) {
				static::reloadLabels();
			}

			return static::$cachedLabels;
		}

		/**
		 *  
		 */
		static public function reloadLabels() {
			static::$cachedLabels = array(
				'labelCheckIn' => __('Check In', 'eviivo-booking-widget'),
				'labelCheckOut' => __('Check Out', 'eviivo-booking-widget'),
				'labelRooms' => __('Rooms', 'eviivo-booking-widget'),
				'labelRoom' => __('Room #%d', 'eviivo-booking-widget'),
				'labelAdults' => __('Adults', 'eviivo-booking-widget'),
				'labelChildren' => __('Children', 'eviivo-booking-widget'),
				'labelShowPrices' => __('Show Prices', 'eviivo-booking-widget'),
			);
		}

		/**
		 * 
		 * @param boolean $getRaw
		 * @return string 
		 */
		public function getLabelShowPrices($getRaw = true) {

			return $this->getFormLabel('labelShowPrices', $getRaw);
		}

		/**
		 * 
		 * @param string $labelShowPrices
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setLabelShowPrices($labelShowPrices) {
			$this->labelShowPrices = $labelShowPrices;
			return $this;
		}

		/**
		 * 
		 * @param string $name
		 * @param boolean $getRaw
		 * @return string 
		 */
		private function getFormLabel($name, $getRaw) {
			if ($getRaw) {
				return $this->$name;
			} else {
				$labels = static::getLabels();
				return !empty($this->$name) ? $this->$name : $labels[$name];
			}
		}

		/**
		 * 
		 * @return \DateTime 
		 */
		public function getAbsoluteCheckInDate() {
			return $this->absoluteCheckInDate;
		}

		/**
		 * 
		 * @return \DateTime 
		 */
		public function getAbsoluteCheckOutDate() {
			return $this->absoluteCheckOutDate;
		}

		/**
		 * 
		 * @param \DateTime $absoluteCheckInDate
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setAbsoluteCheckInDate(\DateTime $absoluteCheckInDate) {
			$this->absoluteCheckInDate = $absoluteCheckInDate;
			return $this;
		}

		/**
		 * 
		 * @param \DateTime $absoluteCheckOutDate
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setAbsoluteCheckOutDate(\DateTime $absoluteCheckOutDate) {
			$this->absoluteCheckOutDate = $absoluteCheckOutDate;
			return $this;
		}

		/**
		 * 
		 * @return int 
		 */
		public function getCheckInDateRelativeDay() {
			return $this->checkInDateRelativeDay;
		}

		/**
		 * 
		 * @return int 
		 */
		public function getCheckOutDateRelativeDay() {
			return $this->checkOutDateRelativeDay;
		}

		/**
		 * 
		 * @param int $checkInDateRelativeDay
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setCheckInDateRelativeDay($checkInDateRelativeDay) {
			$this->checkInDateRelativeDay = $checkInDateRelativeDay;
			return $this;
		}

		/**
		 * 
		 * @param int $checkOutDateRelativeDay
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setCheckOutDateRelativeDay($checkOutDateRelativeDay) {
			$this->checkOutDateRelativeDay = $checkOutDateRelativeDay;
			return $this;
		}

		/**
		 * 
		 * @return int
		 */
		public function getRoomCount() {
			return $this->roomCount;
		}

		/**
		 * 
		 * @return int
		 */
		public function getAdultCount() {
			return $this->adultCount;
		}

		/**
		 * 
		 * @return int
		 */
		public function getChildCount() {
			return $this->childCount;
		}

		/**
		 * 
		 * @return int
		 */
		public function getDefaultRoomCount() {
			return $this->defaultRoomCount;
		}

		/**
		 * 
		 * @return int
		 */
		public function getDefaultAdultCount() {
			return $this->defaultAdultCount;
		}

		/**
		 * 
		 * @return int
		 */
		public function getDefaultChildCount() {
			return $this->defaultChildCount;
		}

		/**
		 * 
		 * @param int $roomsCount
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setRoomCount($roomsCount) {
			$this->roomCount = $roomsCount;
			return $this;
		}

		/**
		 * 
		 * @param int $adultsCount
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setAdultCount($adultsCount) {
			$this->adultCount = $adultsCount;
			return $this;
		}

		/**
		 * 
		 * @param int $childrenCount
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setChildCount($childrenCount) {
			$this->childCount = $childrenCount;
			return $this;
		}

		/**
		 * 
		 * @param int $defaultRoomsCount
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setDefaultRoomCount($defaultRoomsCount) {
			$this->defaultRoomCount = $defaultRoomsCount;
			return $this;
		}

		/**
		 * 
		 * @param int $defaultAdultsCount
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setDefaultAdultCount($defaultAdultsCount) {
			$this->defaultAdultCount = $defaultAdultsCount;
			return $this;
		}

		/**
		 * 
		 * @param int $defaultChildrenCount
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setDefaultChildCount($defaultChildrenCount) {
			$this->defaultChildCount = $defaultChildrenCount;
			return $this;
		}

		/**
		 * 
		 * @return int
		 */
		static public function getInstanceCount() {

			return static::$instancesCount;
		}

		/**
		 * 
		 * @return boolean 
		 */
		public function getIncludeFormTag() {
			return $this->includeFormTag;
		}

		/**
		 * 
		 * @param boolean $includeFormTag
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setIncludeFormTag($includeFormTag) {
			$this->includeFormTag = $includeFormTag;
			return $this;
		}

		/**
		 * 
		 * @return boolean 
		 */
		public function getExcludeJavascript() {
			return $this->excludeJavascript;
		}

		/**
		 * 
		 * @param boolean $excludeJavascript
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setExcludeJavascript($excludeJavascript) {
			$this->excludeJavascript = $excludeJavascript;
			return $this;
		}

		/**
		 * 
		 * @return boolean 
		 */
		public function getExcludeCss() {
			return $this->excludeCss;
		}

		/**
		 * 
		 * @param boolean $excludeCss
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setExcludeCss($excludeCss) {
			$this->excludeCss = $excludeCss;
			return $this;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getTextColor() {
			return $this->textColor;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getBackgroundColor() {
			return $this->backgroundColor;
		}

		/**
		 * 
		 * @param string $textColor
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setTextColor($textColor) {
			$this->textColor = $textColor;
			return $this;
		}

		/**
		 * 
		 * @param string $backgroundColor
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setBackgroundColor($backgroundColor) {
			$this->backgroundColor = $backgroundColor;
			return $this;
		}

		/**
		 * 
		 * @return int 
		 */
		public function getFontSize() {
			return $this->fontSize;
		}

		/**
		 * 
		 * @param int $fontSize
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setFontSize($fontSize) {
			$this->fontSize = $fontSize;
			return $this;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getButtonTextColor() {
			return $this->buttonTextColor;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getButtonBackgroundColor() {
			return $this->buttonBackgroundColor;
		}

		/**
		 * 
		 * @return int 
		 */
		public function getButtonFontSize() {
			return $this->buttonFontSize;
		}

		/**
		 * 
		 * @param string $buttonTextColor
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setButtonTextColor($buttonTextColor) {
			$this->buttonTextColor = $buttonTextColor;
			return $this;
		}

		/**
		 * 
		 * @param string $buttonBackgroundColor
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setButtonBackgroundColor($buttonBackgroundColor) {
			$this->buttonBackgroundColor = $buttonBackgroundColor;
			return $this;
		}

		/**
		 * 
		 * @param int $buttonFontSize
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setButtonFontSize($buttonFontSize) {
			$this->buttonFontSize = $buttonFontSize;
			return $this;
		}

		/**
		 * 
		 * @return boolean
		 */
		public function getHasTransparentBackground() {
			return $this->hasTransparentBackground;
		}

		/**
		 * 
		 * @param boolean $hasTransparentBackground
		 * @return \eviivo\Plugin\Model\BookingForm 
		 */
		public function setHasTransparentBackground($hasTransparentBackground) {
			$this->hasTransparentBackground = $hasTransparentBackground;
			return $this;
		}

	}
