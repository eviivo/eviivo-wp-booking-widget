<?php

	namespace eviivo\Plugin\Elements;

	class HtmlTag {

		/**
		 *
		 * @var string
		 */
		protected $tagName = 'div';

		/**
		 *
		 * @var array
		 */
		protected $attributes = array();

		/**
		 *
		 * @var string
		 */
		protected $innerHtml = '';

		/**
		 *
		 * @var array
		 */
		protected $children = array();

		/**
		 *
		 * @var boolean
		 */
		protected $shortTag = false;

		/**
		 *
		 * @var array  
		 */
		protected $cssClasses = array();

		/**
		 *  
		 */
		public function __construct() {
			
		}

		/**
		 * 
		 * @return string 
		 */
		public function startTag() {

			$html = '<' . $this->tagName . (!empty($this->attributes) ? ' ' . $this->getAttributesHtml() : '');
			if ($this->shortTag) {
				$html .= '/';
			}
			$html .= '>';

			return $html;
		}

		/**
		 * 
		 * @return string 
		 */
		public function endTag() {

			if (!$this->shortTag) {
				return '</' . $this->tagName . '>';
			}

			return '';
		}

		/**
		 * 
		 * @return string 
		 */
		public function render() {
			$html = $this->startTag();
			$html .= $this->getInnerHtml();
			foreach ($this->children as $child) {
				$html .= $child->render();
			}

			$html .= $this->endTag();

			return $html;
		}

		/**
		 * 
		 * @param string $name
		 * @param string $value
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function setAttribute($name, $value) {

			$this->attributes[$name] = $value;

			return $this;
		}

		/**
		 * 
		 * @param string $name
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function removeAttribute($name) {

			if (array_key_exists($name, $this->attributes)) {
				unset($this->attributes[$name]);
			}

			return $this;
		}

		/**
		 * 
		 * @return string
		 */
		public function getAttributesHtml() {
			$html = array();

			$this->attributes['class'] = implode(' ', array_keys($this->cssClasses));

			foreach ($this->attributes as $name => $value) {
				$html [] = $name . '="' . esc_attr($value) . '"';
			}

			return implode(' ', $html);
		}

		/**
		 * 
		 * @return string
		 */
		public function getTagName() {
			return $this->tagName;
		}

		/**
		 * 
		 * @return array
		 */
		public function getAttributes() {
			return $this->attributes;
		}

		/**
		 * 
		 * @param string $name
		 * @param string $default
		 * @return string
		 */
		public function getAttribute($name, $default = '') {

			return array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
		}

		/**
		 * 
		 * @return string
		 */
		public function getInnerHtml() {
			return $this->innerHtml;
		}

		/**
		 * 
		 * @param string $tagName
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function setTagName($tagName) {
			$this->tagName = $tagName;
			return $this;
		}

		/**
		 * 
		 * @param array $attributes
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function setAttributes($attributes) {
			$this->attributes = $attributes;
			return $this;
		}

		/**
		 * 
		 * @param string $innerHtml
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function setInnerHtml($innerHtml) {
			$this->innerHtml = $innerHtml;
			return $this;
		}

		/**
		 * 
		 * @param \eviivo\Plugin\Elements\HtmlTag $node
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function addChild(HtmlTag $node) {

			$this->children [] = $node;
			return $this;
		}

		/**
		 * 
		 * @return array
		 */
		public function getChildren() {

			return $this->children;
		}

		/**
		 * 
		 * @param \eviivo\Plugin\Elements\HtmlTag $node
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function removeChild(HtmlTag $node) {

			foreach ($this->children as $index => $child) {
				if ($child === $node) {
					array_splice($this->children, $index, 1);
					break;
				}
			}

			return $this;
		}

		/**
		 * 
		 * @return boolean 
		 */
		public function getShortTag() {
			return $this->shortTag;
		}

		/**
		 * 
		 * @param boolean $shortTag
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function setShortTag($shortTag) {
			$this->shortTag = $shortTag;
			return $this;
		}

		/**
		 * 
		 * @param string $className
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function addClass($className) {

			$this->cssClasses[$className] = true;
			return $this;
		}

		/**
		 * 
		 * @param string $className
		 * @return boolean
		 */
		public function hasClass($className) {

			return !empty($this->cssClasses[$className]);
		}

		/**
		 * 
		 * @param string $className
		 * @return \eviivo\Plugin\Elements\HtmlTag 
		 */
		public function removeClass($className) {

			if (isset($this->cssClasses[$className])) {
				unset($this->cssClasses[$className]);
			}

			return $this;
		}

	}
