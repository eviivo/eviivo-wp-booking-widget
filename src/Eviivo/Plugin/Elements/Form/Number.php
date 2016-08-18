<?php

	namespace Eviivo\Plugin\Elements\Form;

	/**
	 *  
	 */
	class Number extends Input {

		/**
		 * 
		 * @param string $name 
		 */
		public function __construct($name = '', $label = '') {

			$this->setTagName('input');
			$this->setShortTag(true);

			$this->setAttribute('type', 'number');

			parent::__construct($name, $label);
		}

		/**
		 * 
		 * @param string $value
		 * @return \Eviivo\Plugin\Elements\Form\Input 
		 */
		public function setValue($value) {

			$this->setAttribute('value', $value);

			return parent::setValue($value);
		}

	}
	