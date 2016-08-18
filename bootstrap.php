<?php

	spl_autoload_register(function($className) {
		if (strpos($className, 'eviivo') === 0) {
			require_once( __DIR__ . '/src/' . str_replace('\\', '/', $className) . '.php');
		}
	});

	$eviivoWpPlugin = \eviivo\Plugin\Main::getInstance();
	$eviivoWpPlugin->init();
