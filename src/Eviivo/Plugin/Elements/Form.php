<?php

	namespace Eviivo\Plugin\Elements;

	use Eviivo\Plugin\Elements\Form\Input;
	use Eviivo\Plugin\Elements\Form\Checkbox;
	use Eviivo\Plugin\Elements\Form\Button;

	/**
	 *  
	 */
	class Form extends HtmlTag {

		/**
		 *
		 * @var string
		 */
		protected $tagName = 'form';

		/**
		 *
		 * @var array
		 */
		protected $elements = array();

		/**
		 *
		 * @var array
		 */
		private $elementNames = array();

		/**
		 *
		 * @var string
		 */
		private $message = '';

		/**
		 *
		 * @var int
		 */
		static protected $formCount = 0;

		/**
		 *
		 * @var int
		 */
		private $id = 0;

		/**
		 *  
		 */
		public function __construct() {

			$this->id = static::$formCount++;

			$this->setAttribute('action', '');
			$this->setAttribute('method', 'post');

			parent::__construct();
		}

		/**
		 * 
		 * @param string $url
		 * @return Form
		 */
		public function setAction($url) {

			return $this->setAttribute('action', $url);
		}

		/**
		 * 
		 * @param \Eviivo\Plugin\Elements\HtmlTag $node
		 * @return Form
		 */
		public function addChild(HtmlTag $node) {

			if ($node instanceof Input) {
				$baseName = $node->getName();
				$this->elements[$baseName] = $node;
				$this->computeElementName($node);
			}

			return parent::addChild($node);
		}

		/**
		 * 
		 * @return string
		 */
		public function getName() {
			$calledClass = get_called_class();
			return substr($calledClass, strrpos($calledClass, '\\') + 1) . '_' . $this->id;
		}

		/**
		 * 
		 * @param Input $element
		 * @return Input 
		 */
		protected function computeElementName(Input $element) {

			$baseName = $element->getName();
			$element->setName($this->getName() . '[' . $baseName . ']');
			$this->elementNames[$element->getName()] = $baseName;
			return $element;
		}

		/**
		 * 
		 * @return array
		 */
		public function getElements() {

			return $this->elements;
		}

		/**
		 * 
		 * @param string $name
		 * @return Input
		 */
		public function getElement($name) {

			$element = $this->elements[$name];
			$baseName = $element->getName();
			if (!isset($this->elementNames[$baseName])) {
				$this->computeElementName($element);
			}

			return $element;
		}

		/**
		 * 
		 * @param string $name
		 * @return boolean
		 */
		public function hasElement($name) {

			return array_key_exists($name, $this->elements);
		}

		/**
		 * 
		 * @param array $data
		 * @return boolean 
		 */
		public function isValid($data) {

			$isValid = true;

			$this->hydrateFromArray($data);
			$invalidFields = array();

			foreach ($this->elements as $name => $element) {
				if (!$element->isValid()) {
					$isValid = false;

					$label = $element->getLabel();
					$invalidFields [] = $label ? $label : $name;
				}
			}

			if (!$isValid) {
				$this->message = sprintf(__('Invalid data. Please correct the fallwoing fields: %s', 'eviivo-booking-widget'), implode(', ', $invalidFields));
			}

			return $isValid;
		}

		/**
		 * 
		 * @param string $name
		 * @return string
		 */
		private function getInputBaseName($name) {

			return substr($name, strlen($this->getName()) + 1, -1);
		}

		/**
		 * 
		 * @return array 
		 */
		public function getData() {

			$data = array();

			foreach ($this->elements as $element) {
				/* @var $element Input */
				if (!($element instanceof Button)) {
					$data[$this->getInputBaseName($element->getName())] = $element->getValue();
				}
			}

			return $data;
		}

		/**
		 * 
		 * @param array $data
		 * @return \Eviivo\Plugin\Elements\Form 
		 */
		public function hydrateFromArray($data) {

			foreach ($this->elements as $name => $element) {
				/* @var $element Input */
				if ($element instanceof Checkbox) {
					if (!isset($data[$name])) {
						$data[$name] = '';
					}
				}
			}

			foreach ($data as $name => $value) {
				if (!empty($this->elements[$name])) {
					$this->elements[$name]->setValue($value);
				}
			}

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

			$this->setAttribute('id', $this->getName());

			return parent::startTag();
		}

	}
	