<?php

namespace CoreApp;

	class Router {

		public static function getRoutes($url) {
			$u = rtrim($url, '/');
			$u = explode('/', $u);

			$u[0] = isset($u[0]) ? $u[0] : "AuthTest";
			$u[1] = isset($u[1]) ? $u[1] : 0;
			$u[2] = isset($u[2]) ? $u[2] : 0;

			return $u;
		}

		public static function build($routes) {

			if(!$controller = self::setController($routes[0])) {
				return self::httperror();
			}

			self::performMethod($controller, $routes[1], $routes[2]);

			if(get_parent_class($controller) == "CoreApp\ViewController") {
				return self::renderView($controller);
			}

			return;
		}

		private static function setController($controller) {
			$vcontrollerdir = 'App/_controllers/ViewControllers/';
			$rcontrollerdir = 'App/_controllers/RequestControllers/';
			$vcontrollerfile = $vcontrollerdir.$controller.'.php';
			$rcontrollerfile = $rcontrollerdir.$controller.'.php';

			if(file_exists($vcontrollerfile)) {
				require($vcontrollerfile);
				return new $controller();
			}
			else if(file_exists($rcontrollerfile)) {
				require($rcontrollerfile);
				return new $controller();
			}
			return false;
		}

		private static function performMethod($controller, $method, $parameter) {
			if(method_exists($controller, $method)) {
				call_user_func(array($controller, $method), $parameter);
				return;
			}
			return false;
		}

		private static function renderView($controller) {
			return $controller->showView(false);
		}

		private static function httperror() {
			$errorcontroller = self::setController("Error");
			self::renderView($errorcontroller);
			return;
		}
	}
