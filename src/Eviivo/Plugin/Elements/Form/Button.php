<?php

	namespace Eviivo\Plugin\Elements\Form;

	/**
	 *  
	 */
	class Button extends Input {

		/**
		 *
		 * @var string
		 */
		protected $type = 'submit';

		/**
		 *
		 * @var string
		 */
		protected $label = 'Submit';

		/**
		 * 
		 * @param string $name
		 * @param string $label 
		 */
		public function __construct($name = '', $label = '') {

			$this->setTagName('button');
			$this->setAttribute('type', $this->type);

			parent::__construct($name, $label);
		}

		/**
		 * 
		 * @return string 
		 */
		public function render() {

			return $this->startTag() . $this->getLabel() . $this->endTag();
		}

		/**
		 * 
		 * @return string 
		 */
		public function getType() {
			return $this->type;
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
		 * @param string $type
		 * @return \Eviivo\Plugin\Elements\Form\Button 
		 */
		public function setType($type) {


			$this->setAttribute('type', $type);
			$this->type = $type;
			return $this;
		}

		/**
		 * 
		 * @param string $label
		 * @return \Eviivo\Plugin\Elements\Form\Button 
		 */
		public function setLabel($label) {
			$this->label = $label;
			return $this;
		}

	}
	