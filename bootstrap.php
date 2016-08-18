<?php

	spl_autoload_register(function($className) {
		if (strpos($className, 'Eviivo') === 0) {
			require_once( __DIR__ . '/src/' . str_replace('\\', '/', $className) . '.php');
		}
	});

	$eviivoWpPlugin = \Eviivo\Plugin\Main::getInstance();
	$eviivoWpPlugin->init();
	