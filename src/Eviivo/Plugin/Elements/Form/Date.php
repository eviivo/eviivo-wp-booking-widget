<?php

	namespace eviivo\Plugin\Elements\Form;

	/**
	 *  
	 */
	class Date extends Input {

		/**
		 * 
		 * @param string $name 
		 */
		public function __construct($name = '', $label = '') {

			$this->setTagName('input');
			$this->setShortTag(true);

			$this->setAttribute('type', 'text');
			$this->addClass('eviivo-datepicker');

			parent::__construct($name, $label);
		}

		/**
		 * 
		 * @param string $value
		 * @return \eviivo\Plugin\Elements\Form\Input 
		 */
		public function setValue($value) {

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

			$this->setAttribute('value', $date->format('Y-m-d'));

			return parent::setValue($value);
		}

	}
