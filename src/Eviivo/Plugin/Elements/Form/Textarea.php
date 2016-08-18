<?php

	namespace Eviivo\Plugin\Elements\Form;

	/**
	 *  
	 */
	class Textarea extends Input {

		/**
		 * 
		 * @param string $name 
		 */
		public function __construct($name = '', $label = '') {

			$this->setTagName('textarea');
			$this->setShortTag(false);
			
			parent::__construct($name, $label);
		}

		/**
		 * 
		 * @param string $value
		 * @return \Eviivo\Plugin\Elements\Form\Input 
		 */
		public function setValue($value) {

			$this->setInnerHtml($value);

			return parent::setValue($value);
		}

	}
	