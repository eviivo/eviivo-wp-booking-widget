<?php

	namespace eviivo\Plugin\Elements\Form;

	/**
	 *  
	 */
	class Checkbox extends Input {

		/**
		 * 
		 * @param string $name 
		 */
		public function __construct($name = '', $label = '') {

			$this->setTagName('input');
			$this->setShortTag(true);

			$this->setAttribute('type', 'checkbox');

			parent::__construct($name, $label);
		}

		/**
		 * 
		 * @param string $value
		 * @return \eviivo\Plugin\Elements\Form\Input 
		 */
		public function setValue($value) {

			if($value == $this->getAttribute('value', '')) {
				$this->setAttribute('checked', 'checked');
			} else {
				$this->removeAttribute('checked');
			}

			return parent::setValue($value);
		}

	}
