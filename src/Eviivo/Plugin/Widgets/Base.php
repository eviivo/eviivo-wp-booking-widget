<?php

	namespace Eviivo\Plugin\Widgets;

	abstract class Base extends \WP_Widget {

		/**
		 *
		 * @var int
		 */
		static private $instances = 0;

		/**
		 *
		 * @var string
		 */
		protected $title = 'Widget';

		/**
		 *
		 * @var string
		 */
		protected $desciption = 'Widget description';

		/**
		 *  
		 */
		public function __construct() {

			++self::$instances;

			parent::__construct(
				str_replace('\\', '-', strtolower(get_called_class())), $this->title, array(
				'description' => $this->desciption,
				)
			);
		}

		/**
		 *
		 * @param array $instance
		 * @return void
		 */
		public function form($instance) {
			
		}

		/**
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 * @return array  
		 */
		public function update($new_instance, $old_instance) {

			return $new_instance;
		}

		/**
		 *
		 * @param array $args
		 * @param array $instance
		 * @return mixed
		 */
		final public function widget($args, $instance) {

			return $this->renderWidget($args, $instance);
		}

		/**
		 *  
		 * @param array $args
		 * @param array $instance
		 * @return mixed
		 */
		abstract public function renderWidget($args, $instance);

		/**
		 *
		 * @return string
		 */
		public function getTitle() {
			return $this->title;
		}

		/**
		 *
		 * @param string $title
		 * @return \TCWidget  
		 */
		public function setTitle($title) {
			$this->title = $title;
			return $this;
		}

		/**
		 *
		 * @return string
		 */
		public function getDesciption() {
			return $this->desciption;
		}

		/**
		 *
		 * @param string $desciption
		 * @return \TCWidget  
		 */
		public function setDesciption($desciption) {
			$this->desciption = $desciption;
			return $this;
		}

		/**
		 *
		 * @param string $name
		 * @return HtmlInput 
		 */
		public function getName($name) {

			return $this->fields[$name];
		}

	}
	