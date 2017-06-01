<?php

namespace CoreApp;
use \PDO;

	class Database extends PDO {

	    protected $PDO;
	    private $config;

		public function __construct() {
			$this->config = AppConfig::getData("database=>basic");
			return;
		}

		public function PDOConnection($db) {
			if($this->PDO == null)
			{
				$this->config->DB_NAME = !empty($db) ? $db : $this->config->DB_NAME;
				$pdo = $this->config->DB_TYPE.":host=". $this->config->DB_HOST.";port=.".$this->config->DB_PORT.";dbname=".$this->config->DB_NAME;

				try {
					$this->PDO = new \PDO($pdo, $this->config->DB_USER, $this->config->DB_PASS);
					$this->PDO->exec("set names utf8");
				}
				catch(Exception $ex) {
					echo "Cannot connect to the db: ";
					print_r($ex);
				}

			}
      return $this->PDO;
		}

		public function Restore() {
			$this->PDO = null;
		}

		public function PDOClose() {
			$this->config = null;
			$this->PDO = null;
			return null;
    }

	}
