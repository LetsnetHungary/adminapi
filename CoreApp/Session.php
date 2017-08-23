<?

	namespace CoreApp;
	
		class Session {
			public static function init() {
				@session_start();
			}

			public static function set($key, $value) {
				$_SESSION[$key] = $value;
			}

			public static function get($key) {
				if (isset($_SESSION[$key])) {
				return $_SESSION[$key];
				}
				else {
						return false;
				}
			}

			public static function destroy($a) {
				if(is_object($a)) {
					foreach ($a as $key => $value) {
						unset($_SESSION[$value]);
					}
				}
				else if(is_array($a)) {
					$c_a = count($a);
					for($i=0; $i<$c_a; $i++) {
						unset($_SESSION[$a[$i]]);
					}
				}
				else {
					unset($_SESSION[$a]);
				}
			}

			public static function isSessionSet($a) {
				if(is_array($a)) {
					$c_a = count($a);
					for($i = 0; $i < $c_a; $i++) {
						if(isset($_SESSION[$a[$i]]) && !empty($_SESSION[$a[$i]])) {
							continue;
						}
						else {
							return false;
						}
					}
				}
				else if(is_object($a)) {
					foreach ($a as $key => $value) {
						if(isset($_SESSION[$value]) && !empty($_SESSION[$value])) {
							continue;
						}
						else {
							return false;
						}
					}
				}
				else {
					if(!isset($_SESSION[$a])) {
						return false;
					}
				}
				return true;
			}

			public static function setJSONArray($array) {
				if(is_object($array)) {
					foreach ($array as $key => $value) {
						self::set($key, $value);
					}
				}
			}

			public static function setArray($arr1, $arr2) {
				$c_a = count($arr1);
				for($i = 0; $i < $c_a; $i++) {
					$_SESSION[$arr1[$i]] = $arr2[$i];
				}
			}

		}
