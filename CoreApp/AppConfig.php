<?php

	/**
	* Letsnet - AppConfig
	*
	* @author Letsnet <info@letsnet.hu>
	* @version 0.1
	* @category CoreApp Class
	* @uses CoreApp namespace
	*
	*/

	namespace CoreApp;

		class AppConfig {

			private static $appconfig_real_file = "App/config/appconfig_real.json";

			/**
			* The function returns to JSON object by default
			* @param bool $bool -> set this true to change the return type to PHP array
			*/

			public static function appConfigFile($bool) {
				if($bool) {
					return(json_decode(file_get_contents(self::$appconfig_real_file), TRUE));
				}
				else {
					return(json_decode(file_get_contents(self::$appconfig_real_file)));
				}
			}

			/**
			* Get data from the configuration json file by the arrowString structure
			* @param string $arrowString contains the object data (object=>object)
		    */

			public static function getData($arrowString) {

				$config = self::appConfigFile(FALSE);

				$a = self::arrowString($arrowString);
				$c_a = count($a);

				for($i = 0; $i < $c_a; $i++) {
					$config = $config->{$a[$i]};
				}

				return($config);

			}

			public static function arrowString($string) {
				$array = explode("=>", $string);
				return $array;
			}

			/* end AppConfig CLASS */
		}
