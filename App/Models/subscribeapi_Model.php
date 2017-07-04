<?php

class subscribeapi_Model extends CoreApp\DataModel {
  function __construct() {
    parent::__construct();

  }
  public function uploadNewSubscribe($array) {
    if ($this->checkArray($array)) {
        $this->upload($array);
    }
  }
  private function checkArray($array){
  $a = ["firstname", "lastname", "email"];

    for ($i=0; $i < count($a); $i++) {
      if(!isset($array[$a[$i]])){
        die("missing ".$a[$i]);
      }
    }
    return true;
  }
  private function upload($array){
    $user_firstname = $array["firstname"];
    $user_lastnames = $array["lastname"];
    $user_email = $array["email"];

    $db = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>subscribeDB"));
    $sth = $db->prepare("INSERT INTO `subscribed_users`(`firstname`, `lastname`, `time`, `email`) VALUES (:fname, :lname, :t, :mail)");

    $sth->execute(array(
      ":fname" => $user_firstname,
      ":lname" => $user_lastnames,
      ":t" => time(),
      ":mail" => $user_email
    ));
  }
}
