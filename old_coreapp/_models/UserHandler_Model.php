<?php

namespace CoreApp\Model;
use \CoreApp;
use \PDO;

	class UserHandler_Model extends CoreApp\DataModel {

		public function __construct() {
			parent::__construct();
			$this->AUTHDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>autchenticationDB"));
			$this->database->Restore();
			$this->MODULESDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>modulesDB"));
			$this->database->Restore();
			$this->USERDATADB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>dataDB"));
			$this->database->PDOClose();
		}

		public function getUserData() {
			$stmt = $this->USERDATADB->prepare("SELECT userData.firstname, userData.lastname, userData.shopname, userData.status FROM userData WHERE userData.uniquekey = :uniquekey");
			$stmt->execute(array(
				":uniquekey" => \CoreApp\Session::get("uniquekey")
			));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return($result[0]);
		}

		public function getModules() {
			$stmt = $this->MODULESDB->prepare("SELECT modules.name, modules.redirect, modules.lmodule, modules.icon FROM modules WHERE modules.uniquekey = :uniquekey");
			$stmt->execute(array(
				":uniquekey" => \CoreApp\Session::get("uniquekey")
			));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		public function getPageModules($viewid) {
			$stmt = $this->MODULESDB->prepare("SELECT viewid, module, little FROM pagemodules WHERE uniquekey = :uniquekey AND viewid = :viewid");
			$stmt->execute(array(
				":uniquekey" => \CoreApp\Session::get("uniquekey"),
				":viewid" => $viewid
			));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		public function userAllowedToModule($view) {
			$stmt = $this->MODULESDB->prepare("SELECT id FROM modules WHERE viewid = :viewid AND uniquekey = :uniquekey AND sitekey = :sitekey");
			$stmt->execute(array(
				":viewid" => $view,
				":uniquekey" => \CoreApp\Session::get("uniquekey"),
				":sitekey" => \CoreApp\Session::get("sitekey")
			));
			if($result = $stmt->fetchAll(PDO::FETCH_ASSOC)){
				return true;
			}
			return false;
		}

		public function userAllowedToAPIFunction($api, $function) {
			$stmt = $this->MODULESDB->prepare("SELECT id FROM apis WHERE uniquekey = :uniquekey AND api = :api AND function = :function");
			$stmt->execute(array(
				":uniquekey" => \CoreApp\Session::get("uniquekey"),
				":api" => $api,
				":function" => $function
			));
			if($result = $stmt->fetchAll(PDO::FETCH_ASSOC)){
				return true;
			}
			return false;
		}

		public function PageModulesPHP($pagemodules) {
			$c_p = count($pagemodules);
			$sitekey = \CoreApp\Session::get("sitekey");


			for($i=0; $i < $c_p; $i++) {
				$path = "_cms/$sitekey/modules/php/".$pagemodules[$i]["viewid"]."/".$pagemodules[$i]["module"].".php";
				$this->includePagemodulPHP($path);
			}
		}

		private function includePagemodulPHP($path) {
			include($path);
			return;
		}

		public function logout() {

			$devicekey = CoreApp\Session::get("devicekey");

			$stmt = $this->AUTHDB->prepare("DELETE FROM logged_users WHERE devicekey = :dk");
			$stmt->execute(array(
				":dk" => $devicekey
			));
			CoreApp\Session::destroy(CoreApp\AppConfig::getData("loginsessions"));
			return;
		}
	}
