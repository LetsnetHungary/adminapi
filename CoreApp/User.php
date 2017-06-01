<?php

namespace CoreApp;

	class User {

		public static function LoggedUser() {
			return Session::get("logged_user");
		}

		public static function UniqueKey() {
			return Session::get("uniquekey");
		}

		public static function DeviceKey() {
			return Session::get("devicekey");
		}

		public static function Allow() {
			return Session::get("allow");
		}

		public static function SiteKey() {
			return Session::get("webpage");
		}
	}
