<?php class Router {

	private const APP_PATH = __DIR__ . '/../../app/';
	private const BASE_PATH = __DIR__ . '/../../';

	/**
	 * Get request handler
	 *
	 * @param string $route
	 * @param string $path_to_include
	 * @return void
	 */
	public static function get($route, $path_to_include, $callback = null) {
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			self::route($route, $path_to_include);
		}
	}

	/**
	 * Post request handler
	 *
	 * @param string $route
	 * @param string $path_to_include
	 * @return void
	 */
	public static function post($route, $path_to_include) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			self::route($route, $path_to_include);
		}
	}

	/**
	 * Put request handler
	 *
	 * @param string $route
	 * @param string $path_to_include
	 * @return void
	 */
	public static function put($route, $path_to_include) {
		if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
			self::route($route, $path_to_include);
		}
	}

	/**
	 * Patch request handler
	 *
	 * @param string $route
	 * @param string $path_to_include
	 * @return void
	 */
	public static function patch($route, $path_to_include) {
		if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
			self::route($route, $path_to_include);
		}
	}

	/**
	 * Delete request handler
	 *
	 * @param string $route
	 * @param string $path_to_include
	 * @return void
	 */
	public static function delete($route, $path_to_include) {
		if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
			self::route($route, $path_to_include);
		}
	}

	/**
	 * Any type of request handler
	 *
	 * @param string $route
	 * @param string $path_to_include
	 * @return void
	 */
	public static function any($route, $path_to_include) {
		self::route($route, $path_to_include);
	}
	
	/**
	 * Route handler
	 *
	 * @param string $route
	 * @param string $path_to_include
	 * @return void
	 */
	public static function route($route, $path_to_include) {
		$callback = $path_to_include;
		if (!is_callable($callback)) {
			if (!strpos($path_to_include, '.php')) {
				$path_to_include .= '.php';
			}
		}
		if ($route == "/404") {
			include_once self::APP_PATH . "/views/404.php";
			exit();
		}
		$request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
		$request_url = rtrim($request_url, '/');
		$request_url = strtok($request_url, '?');
		$route_parts = explode('/', $route);
		$request_url_parts = explode('/', $request_url);
		array_shift($route_parts);
		array_shift($request_url_parts);
		if ($route_parts[0] == '' && count($request_url_parts) == 0) {
			// Callback function
			if (is_callable($callback)) {
				call_user_func_array($callback, []);
				exit();
			}
			include_once self::APP_PATH . "/$path_to_include";
			exit();
		}
		if (count($route_parts) != count($request_url_parts)) {
			return;
		}
		$parameters = [];
		for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
			$route_part = $route_parts[$__i__];
			if (preg_match("/^[$]/", $route_part)) {
				$route_part = ltrim($route_part, '$');
				array_push($parameters, $request_url_parts[$__i__]);
				$$route_part = $request_url_parts[$__i__];
			} else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
				return;
			}
		}
		// Callback function
		if (is_callable($callback)) {
			call_user_func_array($callback, $parameters);
			exit();
		}
		include_once self::APP_PATH . "/$path_to_include";
		exit();
	}

	/**
	 * Print output
	 *
	 * @param string $text
	 * @return void
	 */
	function out($text) {
		echo htmlspecialchars($text);
	}

	/**
	 * Set CSRF token
	 * @return void
	 */
	function set_csrf() {
		session_status() !== PHP_SESSION_ACTIVE ?: session_start();
		if (!isset($_SESSION["csrf"])) {
			$_SESSION["csrf"] = bin2hex(random_bytes(50));
		}
		echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
	}

	/**
	 * Check if CSRF token is valid
	 * @return bool
	 */
	function is_csrf_valid() {
		session_status() !== PHP_SESSION_ACTIVE ?: session_start();
		if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
			return false;
		}
		if ($_SESSION['csrf'] != $_POST['csrf']) {
			return false;
		}
		return true;
	}
}
