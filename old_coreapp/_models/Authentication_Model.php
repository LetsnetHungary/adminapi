<?php
namespace CoreApp\Model;

  use \CoreApp;
  use \PDO;

  class Authentication_Model extends CoreApp\DataModel {

      public function __construct() {
        parent::__construct();
        $this->PDO = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>autchenticationDB"));
        $this->database->PDOClose();
      }

      public function checkIfUserLoggedIn() {
        if(CoreApp\Session::isSessionSet(CoreApp\AppConfig::getData("loginsessions"))){
          $lu = CoreApp\Session::get("logged_user");
          $dk = CoreApp\Session::get("devicekey");
          $lq = $this->PDO->prepare("SELECT users.id FROM users INNER JOIN logged_users ON (users.uniquekey = logged_users.uniquekey) WHERE users.email = :email and logged_users.devicekey = :devicekey");
          $lq->execute(array(
            ":email" => $lu,
            ":devicekey" => $dk
          ));
          if($r = $lq->fetchAll(PDO::FETCH_ASSOC)) {
            unset($lq);
            return true;
          }
        }
        CoreApp\Session::destroy(CoreApp\AppConfig::getData("loginsessions"));
        return false;
      }

      public function loginAttemptUser(CoreApp\AttemptUser $a) {
        if(!$this->checkIfUserLoggedIn()) {
          $l = $this->PDO->prepare("SELECT users.password, users.uniquekey, users.allow, users.sitekey FROM users INNER JOIN devices ON (users.uniquekey = devices.uniquekey) WHERE users.email = :email AND devices.devicekey = 'ALL' OR users.email = :email AND devices.devicekey = :devicekey");
          $l->execute(array(
            ":email" => $a->e,
            ":devicekey" => $a->devicekey
          ));
          if($d = $l->fetchAll(PDO::FETCH_ASSOC)) {
            if(password_verify($a->p, $d[0]["password"])) {
              //logged_in
              $a->uniquekey = $d[0]["uniquekey"];
              $a->allow = $d[0]["allow"];
              $a->sitekey = $d[0]["sitekey"];

              $this->insertToLoggedTable($a);

              $sessionvalues = array(
                $a->e,
                $a->uniquekey,
                $a->devicekey,
                $a->allow,
                $a->sitekey
              );

              CoreApp\Session::setArray(CoreApp\AppConfig::getData("loginsessions", true), $sessionvalues);
              return true;
            }
          }
        }

        $l = $this->PDO->prepare("INSERT INTO wrong_try (email, devicekey, POST, time, date) VALUES (:email, :devicekey, :POST, :time, :date)");
        $l->execute(array(
          ":email" => $a->e,
          ":devicekey" => $a->devicekey,
          ":POST" => json_encode($_POST),
          ":time" => time(),
          ":date" => date("Y-m-d H:i:s")
        ));

        $l = null;
        return false;
      }

      private function insertToLoggedTable(CoreApp\AttemptUser $a) {
        $lt = $this->PDO->prepare("INSERT INTO logged_users (devicekey, uniquekey, time, ip, useragent, lalo) VALUES (:devicekey, :uniquekey, :time, :ip, :useragent, :lalo)");
        $lt->execute(array(
          ":devicekey" => $a->devicekey,
          ":uniquekey" => $a->uniquekey,
          ":time" => time(),
          ":ip" => $a->ip,
          ":useragent" => $a->useragent,
          ":lalo" => $a->lalo
        ));
        $lt = null;
        return;
      }
  }
