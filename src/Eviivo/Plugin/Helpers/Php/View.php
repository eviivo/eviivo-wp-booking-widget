<?php

	namespace eviivo\Plugin\Helpers\Php;

	/**
	 *  
	 */
	class View {

		/**
		 *
		 * @var array
		 */
		private $parameters = array();

		/**
		 * 
		 * @param array $parameters 
		 */
		public function __construct($parameters = array()) {

			$this->parameters = $parameters;
		}

		/**
		 * 
		 * @return array 
		 */
		public function getParameters() {
			return $this->parameters;
		}

		/**
		 * 
		 * @param string $parameters
		 * @return \eviivo\Plugin\Helpers\Php\View 
		 */
		public function setParameters($parameters) {
			$this->parameters = $parameters;
			return $this;
		}

		/**
		 * 
		 * @param string $parameter
		 * @param mixed $default
		 * @return mixed
		 */
		public function getParameter($parameter, $default = false) {

			return array_key_exists($parameter, $this->parameters) ? $this->parameters[$parameter] : $default;
		}

		/**
		 * 
		 * @param string $parameter
		 * @param mixed $value
		 * @return \eviivo\Plugin\Helpers\Php\View 
		 */
		public function setParameter($parameter, $value) {

			$this->parameters[$parameter] = $value;

			return $this;
		}

		/**
		 * 
		 * @param string $templatePath
		 * @return string
		 */
		public function render($templatePath) {

			extract($this->parameters);

			ob_start();
			require($templatePath);

			return ob_get_clean();
		}

	}
