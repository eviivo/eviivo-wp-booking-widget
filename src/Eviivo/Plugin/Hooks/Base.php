<?php

	namespace Eviivo\Plugin\Hooks;

	/**
	 *  
	 */
	abstract class Base {

		/**
		 *
		 * @var array
		 */
		static protected $mapping = array();

		/**
		 * 
		 * @param string $methodName
		 * @return string
		 */
		static public function getHookName($methodName) {
	
			return isset(static::$mapping[$methodName]) ? static::$mapping[$methodName] : array(
				'name' => $methodName,
				'priority' => 10,
				'argumentsCount' => 10,
				'callback' => get_called_class() . '::' . $methodName
			);
		}
		
	}
	