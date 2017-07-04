<?php

namespace CoreApp;

	class App {

		public function __construct($url) {
			$this->routes = Router::getRoutes($url);
		}

		public function build() {
			Router::build($this->routes);
		}
	}
