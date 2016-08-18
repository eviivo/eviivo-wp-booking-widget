<?php

	namespace eviivo\Plugin\Ajax;

	abstract class GenericRequest extends Request {

		/**
		 *
		 * @var string  
		 */
		public $type = 'text';

		/**
		 *  
		 */
		abstract public function process($args = array());

		/**
		 * 
		 * @param array $args
		 * @param \WordpressTheme\Ajax\Response $response 
		 */
		private function _process($args, Response $response) {
			$data = $this->process($args);
			$response->setType($this->type);
			$response->setData($data);
		}

		/**
		 * 
		 * @param array $args
		 * @param \WordpressTheme\Ajax\Response $response 
		 */
		public function doGet($args = array(), Response $response = null) {

			$this->_process($args, $response);
		}

		/**
		 * 
		 * @param array $args
		 * @param \WordpressTheme\Ajax\Response $response 
		 */
		public function doPost($args = array(), Response $response = null) {

			$this->_process($args, $response);
		}

	}
