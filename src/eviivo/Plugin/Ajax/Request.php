<?php

	namespace eviivo\Plugin\Ajax;

	abstract class Request {

		const AJAX_ACTION_PREFIX = 'eviivo_';

		/**
		 *
		 * @var Response;  
		 */
		private $response = null;

		/**
		 *  
		 */
		public function __construct() {
			
			add_action('wp_ajax_nopriv_' . self::AJAX_ACTION_PREFIX . $this->getId(), array($this, 'call'));
			add_action('wp_ajax_' . self::AJAX_ACTION_PREFIX . $this->getId(), array($this, 'call'));
			$this->response = new Response();
		}

		/**
		 *  
		 */
		public function call() {

			if (strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
				$this->doGet($_GET, $this->response);
			} else {
				$this->doPost($_POST, $this->response);
			}

			$this->response->send();
			exit();
		}

		/**
		 * 
		 * @return string 
		 */
		public function getId() {

			$className = explode('\\', get_called_class());
			return array_pop($className);
		}

		/**
		 *  
		 */
		abstract public function doGet($args = array(), Response $response = null);

		/**
		 *  
		 */
		abstract public function doPost($args = array(), Response $response = null);

		/**
		 * 
		 * @return Response 
		 */
		public function getResponse() {
			return $this->response;
		}

		/**
		 * 
		 * @param \eviivo\Plugin\Ajax\Response $response
		 * @return \eviivo\Plugin\Ajax\Request 
		 */
		public function setResponse(Response $response) {
			$this->response = $response;
			return $this;
		}

		/**
		 * 
		 * @param string $message
		 * @return \eviivo\Plugin\Ajax\Request 
		 */
		public function notAllowed($message = '401 Unauthorized') {
			$this->response->setStatus($message);

			return $this;
		}

		/**
		 * 
		 * @param string $message
		 * @return \eviivo\Plugin\Ajax\Request 
		 */
		public function error($message = '500 Unauthorized') {
			$this->response->setStatus($message);

			return $this;
		}

	}
