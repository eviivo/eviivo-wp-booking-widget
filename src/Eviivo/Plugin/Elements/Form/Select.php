<?php

	namespace eviivo\Plugin\Elements\Form;

	/**
	 *  
	 */
	class Select extends Input {

		/**
		 *
		 * @var array
		 */
		protected $options = array();

		/**
		 * 
		 * @param string $name 
		 */
		public function __construct($name = '', $label = '') {

			$this->setTagName('select');
			$this->setShortTag(false);

			parent::__construct($name, $label);
		}

		/**
		 * 
		 * @return string 
		 */
		public function render() {
			$html = $this->startTag();
			$currentValue = $this->getValue();

			foreach ($this->options as $value => $label) {
				$html .= '<option ';

				if ($value == $currentValue) {
					$html .= 'selected="selected" ';
				}

				$html .= 'value="' . esc_attr($value) . '">' . $label . '</option>';
			}

			$html .= $this->endTag();

			return $html;
		}

		/**
		 * 
		 * @return array
		 */
		public function getOptions() {
			return $this->options;
		}

		/**
		 * 
		 * @param array $options
		 * @return \eviivo\Plugin\Elements\Form\Select 
		 */
		public function setOptions($options) {
			$this->options = $options;
			return $this;
		}

		/**
		 * 
		 * @return boolean 
		 */
		public function isValid() {

			if (!array_key_exists($this->value, $this->options)) {
				$this->setHasError(true);
				$this->setMessage(sprintf(__('Please select on of the avaible options', 'eviivo-booking-widget'), $this->getLabelOrName()));

				return false;
			}

			return parent::isValid();
		}

	}
