<?php

	namespace Eviivo\Plugin;

	use Eviivo\Plugin\Ajax\Request;
	use Eviivo\Plugin\Widgets\Base;

	/**
	 *  
	 */
	class Main {

		/**
		 *
		 * @var Main
		 */
		static private $instance;

		/**
		 *
		 * @var array
		 */
		private $adminPages = array();

		/**
		 *  
		 */
		private function __construct() {
			
		}

		/**
		 * 
		 * @return Main
		 */
		static public function getInstance() {

			if (empty(static::$instance)) {
				$className = __CLASS__;
				static::$instance = new $className();
			}

			return static::$instance;
		}

		/**
		 *  
		 */
		public function init() {

			$this->registerHooks();
			$this->registerAjax();
			$this->registerShortcodes();
			$this->registerWidgets();

			if (is_admin()) {
				$this->registerAdminMenus();
			}
		}

		/**
		 *  
		 */
		private function registerAdminMenus() {

			$baseFolder = __DIR__ . '/Admin/Pages';
			$pages = scandir($baseFolder);
			foreach ($pages as $page) {
				if ($page !== '.' && $page !== '..' && $page !== 'Base.php' && is_file($baseFolder . '/' . $page)) {
					$filenameSegments = explode('.', $page);
					array_pop($filenameSegments);
					$className = implode('.', $filenameSegments);
					$fullClassName = '\\Eviivo\\Plugin\\Admin\\Pages\\' . $className;
					$this->adminPages[$className] = new $fullClassName();
					$this->adminPages[$className]->register();
				}
			}
		}

		/**
		 * 
		 * @param string $className
		 * @param string $hookRegisterFunction 
		 */
		private function registerHook($className, $hookRegisterFunction) {

			$baseMethods = get_class_methods('\\Eviivo\\Plugin\\Hooks\\Base');

			foreach (get_class_methods($className) as $filter) {
				if (!in_array($filter, $baseMethods)) {
					$hookData = $className::getHookName($filter);
					call_user_func($hookRegisterFunction, $hookData['name'], $hookData['callback'], $hookData['priority'], $hookData['argumentsCount']);
				}
			}
		}

		/**
		 *  
		 */
		private function registerHooks() {
			$this->registerHook('\\Eviivo\\Plugin\\Hooks\\Filters', 'add_filter');
			$this->registerHook('\\Eviivo\\Plugin\\Hooks\\Actions', 'add_action');

			if (is_admin()) {
				$this->registerHook('\\Eviivo\\Plugin\\Admin\\Hooks\\Filters', 'add_filter');
				$this->registerHook('\\Eviivo\\Plugin\\Admin\\Hooks\\Actions', 'add_action');
			}
		}

		/**
		 *  
		 */
		private function registerAjax() {
			//Register ajax
			if (defined('DOING_AJAX')) {

				$basePath = dirname(__FILE__);
				$dir = __DIR__ . '/Ajax/Calls';
				if (file_exists($dir)) {
					foreach (scandir($dir) as $file) {
						$path = $dir . '/' . $file;
						if ($file != '.' && $file != '..' && is_file($path)) {
							$className = '\\Eviivo\\Plugin\\Ajax\\Calls\\' . substr($file, 0, -4);
							new $className();
						}
					}
				}
			}
		}

		/**
		 *  
		 */
		private function registerShortcodes() {
			$dir = Util::getPluginPath() . '/shortcodes';
			if (file_exists($dir)) {
				foreach (scandir($dir) as $file) {
					if ($file != '.' && $file != '..' && is_file($dir . '/' . $file)) {
						$path = $dir . '/' . $file;
						add_shortcode(substr($file, 0, strpos($file, '.')), function($attributes = array()) use ($path) {

							if (is_array($attributes)) {
								extract($attributes);
							}

							ob_start();
							require($path);

							return ob_get_clean();
						});
					}
				}
			}
		}

		/**
		 *  
		 */
		private function registerWidgets() {

			add_filter('widgets_init', function() {
				$widgetsBasePath = __DIR__ . '/Widgets';
				if (file_exists($widgetsBasePath)) {
					$widgets = scandir($widgetsBasePath);
					foreach ($widgets as $widgetFileName) {
						if ($widgetFileName != '.' && $widgetFileName != '..') {
							if ($widgetFileName != 'Base.php' && is_file($widgetsBasePath . '/' . $widgetFileName)) {
								$widgetName = substr('\\Eviivo\\Plugin\\Widgets\\Base', 0, strrpos('\\Eviivo\\Plugin\\Widgets\\Base', '\\')) . '\\' . substr($widgetFileName, 0, strrpos($widgetFileName, '.'));
								register_widget($widgetName);
							}
						}
					}
				}
			});
		}

	}
	