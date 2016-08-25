<?php

	namespace eviivo\Plugin\Elements\Form;

	/**
	 *  
	 */
	class Text extends Input {

		/**
		 * 
		 * @param string $name 
		 */
		public function __construct($name = '', $label = '') {

			$this->setTagName('input');
			$this->setShortTag(true);

			$this->setAttribute('type', 'text');

			parent::__construct($name, $label);
		}

		/**
		 * 
		 * @param string $value
		 * @return \eviivo\Plugin\Elements\Form\Input 
		 */
		public function setValue($value) {

			$this->setAttribute('value', $value);

			return parent::setValue($value);
		}

	}
