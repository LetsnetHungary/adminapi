<?

    namespace CoreApp;

    class DB {

        private static $config = [];
        protected static $PDO;

        public static function init($DB_NAME) {
            if(!self::$PDO) {
                self::$config = AppConfig::getData("database=>config");
                self::$config->DB_NAME = !empty($DB_NAME) ? $DB_NAME : self::$config->DB_NAME;
                $pdo = self::$config->DB_TYPE.":host=". self::$config->DB_HOST.";port=".self::$config->DB_PORT.";dbname=".self::$config->DB_NAME;
                self::$PDO = new \PDO($pdo, self::$config->DB_USER, self::$config->DB_PASSWORD);
                self::$PDO->exec("set names utf8");
                return self::$PDO;
            }
            return NULL;
        }

        public static function restore() {
            self::$PDO = NULL;
            self::$config = [];
        }

    }