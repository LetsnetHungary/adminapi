<?php

namespace CoreApp;

	class AppConfig {
			private static $server_appconfig_file = "App/_config/_server_appconfig.json";
			private static $development_appconfig_file = "App/_config/_development_appconfig.json";

			public static function appConfigFile($bool) {
				if($bool) {
					if(APPCONFIG == "development") {
						return(json_decode(file_get_contents(self::$development_appconfig_file), TRUE));
					}
					else if(APPCONFIG == "server") {
						return(json_decode(file_get_contents(self::$server_appconfig_file), TRUE));
					}
				}
				else {
					if(APPCONFIG == "development") {
						return(json_decode(file_get_contents(self::$development_appconfig_file)));
					}
					else if(APPCONFIG == "server") {
						return(json_decode(file_get_contents(self::$server_appconfig_file)));
					}
				}
			}

			public static function getData($arrowString) {
				$config = self::appConfigFile(FALSE);

				$a = arrowString($arrowString);
				$c_a = count($a);

				for($i = 0; $i < $c_a; $i++) {
					$config = $config->{$a[$i]};
				}
				return($config);
			}
	}
