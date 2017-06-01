<?php

namespace CoreApp;

	class ServerHandler {

		public static function curlEnding() {
			if($_SERVER["SERVER_NAME"] == "mvc" OR $_SERVER["SERVER_NAME"] == "letsnet") {
				return ":8080";
			}
			else if($_SERVER["SERVER_NAME"] == "letsnet.hu") {
				return ".hu";
			}
			else {
				return "";
			}
		}

	}
