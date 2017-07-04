<?php

namespace CoreApp\Model;
use \CoreApp;
use \PDO;

	class Analytics_Model extends CoreApp\Model {
		private $visitor;
		private $date;
		private $dbname;

		public function __construct() {
			parent::__construct();
	    $this->dbname = CoreApp\Appconfig::getVisitorsDB();
			$this->db->exec("use ".$this->dbname);

			$this->date = date("ym");

      $this->checkMonth();

			$this->visitor = new Visitor();
			$this->visitpage($this->visitor);
			$this->visitor = null;
		}

		public function visitpage() {
			$stmt = $this->db->prepare("SELECT * FROM banned WHERE ip = :ip");
			$stmt->execute(array(
					":ip" => $this->visitor->ip
				));

			if($result = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
				if($result[0]["bannedtime"] > time()) {
					echo "You have been banned from this site...";
          die();
				}
				else {
					if($result[0]["lastattempt"] > time() - 20) {
						// ATTEMPTS++
						if($result[0]["attempts"] >= 30) {
							$stmt = $this->db->prepare("UPDATE banned SET banned = :banned, bannedtime = :bannedtime WHERE ip = :ip");
							$stmt->execute(array(

									":banned" => 1,
									":bannedtime" => time() + 600,
									":ip" => $this->visitor->ip
								));
							$this->ban($this->visitor);
						}
						else {
            $stmt = $this->db->prepare("UPDATE banned SET lastattempt = :lastattempt, attempts = :attempts WHERE ip = :ip");
            $stmt->execute(array(

                    ":lastattempt" => time(),
                    ":attempts" => $result[0]["attempts"] + 1,
                    ":ip" => $this->visitor->ip
                ));
						}
					}
					else {
						$stmt = $this->db->prepare("UPDATE banned SET lastattempt = :lastattempt, attempts = :attempts WHERE ip = :ip");
						$stmt->execute(array(

								":lastattempt" => time(),
								":attempts" => 1,
								":ip" => $this->visitor->ip
							));
					}
				}
			}
			else {
				$stmt = $this->db->prepare("INSERT INTO banned (ip, lastattempt, attempts, banned, bannedtime) VALUES (:ip, :lastattempt, :attempts, :banned, :bannedtime)");
				$stmt->execute(array(

						":ip" => $this->visitor->ip,
						":lastattempt" => time(),
						":attempts" => 1,
						":banned" => 0,
						":bannedtime" => 0
					));
			}
			try {
				$stmt = $this->db->prepare("INSERT INTO `$this->date` (`ip`, `conntime`, `connday`, `url`, `useragent`) VALUES (:ip, :conntime, :connday, :url, :useragent)");
				$stmt->execute(array(

						":ip" => $this->visitor->ip,
						":conntime" => time(),
						":connday" => date("F j, Y, g:i a"),
						":url" => $this->visitor->url,
						":useragent" => $this->visitor->useragent
					));
			}
			catch(PDOException $ex) {

      }
			$stmt = "";
		}

		public function ban($v) {
				$stmt = $this->db->prepare("INSERT INTO bannedusers (`ip`, `bantime`, `banday`, `url`, `useragent`) VALUES (:ip, :conntime, :connday, :url, :useragent)");
				$stmt->execute(array(

						":ip" => $this->visitor->ip,
						":conntime" => time(),
						":connday" => date("F j, Y, g:i a"),
						":url" => $this->visitor->url,
						":useragent" => $this->visitor->useragent
					));
		}

    public function checkMonth() {
        $stmt = $this->db->prepare("SELECT id FROM `$this->date` LIMIT 1");
        $stmt->execute();
        if($result = $stmt->fetchAll())
        {
            return true;
        }
        else
        {
            \CoreApp\DB::monthTable($this->dbname, date("ym"));
            return false;
        }
    }
	}

	class Visitor {
		public $ip;
		public $url;
		public $useragent;

		public function __construct() {
			$this->ip = $_SERVER["REMOTE_ADDR"];
			$this->url = $_SERVER["REQUEST_URI"];
			$this->useragent = $_SERVER["HTTP_USER_AGENT"];
		}
	}
