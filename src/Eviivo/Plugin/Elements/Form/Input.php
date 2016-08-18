<?php

	namespace Eviivo\Plugin\Elements\Form;

	use Eviivo\Plugin\Elements\HtmlTag;

	/**
	 *  
	 */
	class Input extends HtmlTag {

		/**
		 *
		 * @var string
		 */
		protected $name = '';

		/**
		 *
		 * @var string
		 */
		protected $value = '';

		/**
		 *
		 * @var boolean
		 */
		protected $required = false;

		/**
		 *
		 * @var string
		 */
		protected $label = '';

		/**
		 *
		 * @var string
		 */
		private $message = '';

		/**
		 *
		 * @var boolean
		 */
		protected $hasError = false;

		/**
		 *
		 * @var boolean
		 */
		protected $includeLabel = false;

		/**
		 * 
		 * @param string $name 
		 */
		public function __construct($name = '', $label = '') {

			$this->setName($name);
			$this->setLabel($label);

			parent::__construct($name, $label);
		}

		/**
		 * 
		 * @return string 
		 */
		public function getName() {
			return $this->name;
		}

		/**
		 * 
		 * @param string $name
		 * @return \Eviivo\Plugin\Elements\Form\Input 
		 */
		public function setName($name) {

			$this->name = $name;
			$this->setAttribute('name', $name);

			return $this;
		}

		/**
		 * 
		 * @return string
		 */
		public function getValue() {
			return $this->value;
		}

		/**
		 * 
		 * @param string $value
		 * @return \Eviivo\Plugin\Elements\Form\Input 
		 */
		public function setValue($value) {
			$this->value = $value;
			return $this;
		}

		/**
		 * 
		 * @return boolean 
		 */
		public function getRequired() {
			return $this->required;
		}

		/**
		 * 
		 * @param boolean $required
		 * @return \Eviivo\Plugin\Elements\Form\Input 
		 */
		public function setRequired($required) {
			$this->required = $required;
			if ($required) {
				$this->setAttribute('required', 'required');
			} else {
				$this->removeAttribute('required');
			}

			return $this;
		}

		/**
		 * 
		 * @return boolean 
		 */
		public function isValid() {

			if ($this->required) {
				$value = $this->getValue();
				if (empty($value)) {
					$this->hasError = true;
					$this->message = sprintf(__('The %s field is mandatory', 'eviivo-booking-widget'), $this->getLabelOrName());

					return false;
				}
			}

			return true;
		}

		/**
		 * 
		 * @return string
		 */
		public function endTag() {

			$html = '';
			if ($this->hasError) {
				$html .= '<div class="eviivo-error-wrapper">' . $this->getMessage() . '</div>';
			}

			return parent::endTag() . $html;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getLabel() {
			return $this->label;
		}

		/**
		 * 
		 * @param string $label
		 * @return \Eviivo\Plugin\Elements\Form\Input 
		 */
		public function setLabel($label) {
			$this->label = $label;
			return $this;
		}

		/**
		 * 
		 * @return boolean
		 */
		public function hasMessage() {

			return !empty($this->message);
		}

		/**
		 * 
		 * @return stirng 
		 */
		public function getMessage() {
			return $this->message;
		}

		/**
		 * 
		 * @param stirng $message
		 * @return \Eviivo\Plugin\Admin\Pages\Base 
		 */
		public function setMessage($message) {
			$this->message = $message;
			return $this;
		}

		/**
		 * 
		 * @return string
		 */
		public function startTag() {

			if ($this->hasError) {
				$this->addClass('error');
			}

			$html = '';

			if ($this->getIncludeLabel()) {
				$html .= '<label for="">' . $this->getLabel() . ' </label>';
			}

			return $html . parent::startTag();
		}

		/**
		 * 
		 * @return string
		 */
		public function getLabelOrName() {

			return !empty($this->label) ? $this->label : $this->name;
		}

		/**
		 * 
		 * @param boolean $flag
		 * @return \Eviivo\Plugin\Elements\Form\Input 
		 */
		protected function setHasError($flag) {

			$this->hasError = $flag;

			return $this;
		}

		/**
		 * 
		 * @return boolean 
		 */
		public function getIncludeLabel() {
			return $this->includeLabel;
		}

		/**
		 * 
		 * @param boolean $includeLabel
		 * @return \Eviivo\Plugin\Elements\Form\Input 
		 */
		public function setIncludeLabel($includeLabel) {
			$this->includeLabel = $includeLabel;
			return $this;
		}

	}
	