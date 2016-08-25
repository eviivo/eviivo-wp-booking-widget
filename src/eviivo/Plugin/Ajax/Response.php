<?php

	namespace eviivo\Plugin\Ajax;

	/**
	 *  
	 */
	class Response {

		/**
		 *
		 * @var string
		 */
		private $type = 'json';

		/**
		 *
		 * @var mixed
		 */
		private $data = null;

		/**
		 *
		 * @var string
		 */
		private $status = '200 OK';

		/**
		 * 
		 * @param string $type 
		 */
		public function __construct($type = 'json') {

			$this->setType($type);
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
		 * @param string $type
		 * @return \WordpressTheme\Ajax\Response 
		 */
		public function setType($type) {

			$this->type = $type;
			switch ($this->type) {
				case 'json':
					$this->data = array();
					break;
				case 'text':
					$this->data = '';
					break;
			}

			return $this;
		}

		/**
		 * 
		 * @return \WordpressTheme\Ajax\Response 
		 */
		public function send() {

			header('HTTP/1.0 ' . $this->status);

			switch ($this->type) {
				case 'json':
					echo json_encode($this->data);
					break;
				case 'text':
					echo $this->data;
					break;
			}

			return $this;
		}

		/**
		 * 
		 * @param mixed $data
		 * @return \WordpressTheme\Ajax\Response 
		 */
		public function setData($data) {

			$this->data = $data;
			return $this;
		}

		/**
		 * 
		 * @return string
		 */
		public function getStatus() {
			return $this->status;
		}

		/**
		 * 
		 * @param string $status
		 * @return \eviivo\Plugin\Ajax\Response 
		 */
		public function setStatus($status) {
			$this->status = $status;
			return $this;
		}

	}
