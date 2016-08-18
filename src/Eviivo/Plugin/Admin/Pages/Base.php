<?php

	namespace Eviivo\Plugin\Admin\Pages;

	use Eviivo\Plugin\Helpers\Php\View;
	use Eviivo\Plugin\Util;

	/**
	 *  
	 */
	abstract class Base {

		/**
		 *
		 * @var stirng  
		 */
		protected $message = '';

		/**
		 *
		 * @var string
		 */
		protected $messageType = 'success';

		/**
		 *  
		 */
		public function __construct() {
			
		}

		/**
		 * 
		 * @return \Eviivo\Plugin\Admin\Pages\Base 
		 */
		public function register() {
			$instance = $this;
			add_action('admin_menu', function() use ($instance) {

				ob_start();
				add_menu_page(
					$instance->getTitle(), $instance->getMenuTitle(), $instance->getCapability(), $instance->getSlug(), array($instance, 'callback'), $instance->getIcon(), $instance->getPosition()
				);
			});

			return $this;
		}

		/**
		 * @return string
		 */
		abstract public function getTitle();

		/**
		 * @return string
		 */
		abstract public function getSlug();

		/**
		 * @return string
		 */
		public function getMenuTitle() {

			return $this->getTitle();
		}

		/**
		 * @return string
		 */
		public function getCapability() {

			return 'manage_options';
		}

		/**
		 * @return string
		 */
		public function callback() {

			$this->init();

			if (!empty($_POST)) {
				$this->post();
			}
			$view = $this->render();

			echo $view->render($this->getViewFilePath());
		}

		/**
		 * 
		 * @return string
		 */
		public function getViewFilePath() {
			
			$calledClass = get_called_class();
			$className = substr($calledClass, strrpos($calledClass, '\\') + 1);
			
			return Util::getViewPath('admin/' . strtolower($className));
		}

		/**
		 * 
		 * @return string 
		 */
		public function getIcon() {

			return 'dashicons-location-alt';
		}

		/**
		 * 
		 * @return int 
		 */
		public function getPosition() {

			return 100;
		}

		/**
		 * 
		 * @return View 
		 */
		public function render() {

			return new View();
		}

		/**
		 *  
		 */
		public function post() {
			
		}

		/**
		 *  
		 */
		public function init() {
			
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
		public function setMessage($message, $type = 'success') {
			$this->message = $message;
			$this->messageType = $type;
			return $this;
		}

		/**
		 * 
		 * @return string 
		 */
		public function getMessageType() {
			return $this->messageType;
		}

		/**
		 * 
		 * @param string $messageType
		 * @return \Eviivo\Plugin\Admin\Pages\Base 
		 */
		public function setMessageType($messageType) {
			$this->messageType = $messageType;
			return $this;
		}

	}
	