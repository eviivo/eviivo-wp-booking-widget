<?php

	namespace eviivo\Plugin;

	use eviivo\Plugin\Ajax\Request;
	use eviivo\Plugin\Helpers\Php\View;

	/**
	 *  
	 */
	class Util {

		/**
		 *
		 * @var array
		 */
		static private $config = null;

		/**
		 * 
		 * @return string
		 */
		static public function getPluginPath() {

			return realpath(__DIR__ . '/../../..');
		}

		/**
		 * 
		 * @param string $file
		 * @param boolean $includeFileTimeAsParameter
		 * @return strong
		 */
		static public function getPluginUrl($file = '', $includeFileTimeAsParameter = false) {

			$url = plugins_url($file, static::getPluginPath() . '/eviivo-booking-widget.php');

			if ($includeFileTimeAsParameter && $file) {
				$url .= '?cache=' . md5(filemtime(static::getPluginPath()) . '/' . $file);
			}

			return $url;
		}

		/**
		 *
		 * @return string
		 */
		static public function getAjaxPath() {

			return site_url() . '/wp-admin/admin-ajax.php?action=' . Request::AJAX_ACTION_PREFIX;
		}

		/**
		 *
		 * @param string $ajaxId
		 * @param array $params [optional]
		 * @return string
		 */
		static public function getAjaxLink($ajaxId, $params = array()) {

			$args = array();
			foreach ($params as $id => $value) {
				$args [] = urlencode($id) . '=' . urlencode($value);
			}

			return self::getAjaxPath() . $ajaxId . ( $args ? '&' . implode('&', $args) : '' );
		}

		/**
		 * 
		 * @param string $view
		 * @return string
		 */
		static public function getViewPath($view) {

			return Util::getPluginPath() . '/views/' . $view . '.php';
		}

		/**
		 * 
		 * @param string $message
		 * @param string $messageType
		 * @return string
		 */
		static public function renderMessage($message, $messageType = 'success') {

			$view = new View(array(
				'message' => $message,
				'messageType' => $messageType,
			));

			return $view->render(static::getViewPath('admin/message'));
		}

		/**
		 * 
		 * @global string $locale
		 * @global array $l10n
		 * @param string $languageCode 
		 */
		static public function changeLanguage($languageCode) {
			global $locale;
			$locale = $languageCode;

			global $l10n;
			if (isset($l10n['eviivo-booking-widget'])) {
				unset($l10n['eviivo-booking-widget']);
			}

			load_plugin_textdomain('eviivo-booking-widget', false, basename(static::getPluginPath()) . '/languages/');
		}

		/**
		 * 
		 * @param string $key
		 * @param mixed $default
		 * @return mixed
		 */
		static public function getConfig($key, $default = false) {
			if (static::$config === null) {
				static::$config = require(static::getPluginPath() . '/config/common.php');
			}

			return array_key_exists($key, static::$config) ? static::$config[$key] : $default;
		}

	}
