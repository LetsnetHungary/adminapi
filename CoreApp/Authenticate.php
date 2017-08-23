<?php

namespace CoreApp;

class Authenticate{
  public function __construct() {
  }

  public function setSession($post_array){
    if($this->checkIfPostIsValid($post_array)){
      $_SESSION[$post_array["devicekey"]] = $post_array["uniquekey"];
    }
    else{
      print_r("You don't have access to set auth_code");
      die();
    }
  }
  private function checkIfPostIsValid($array){
    $database = DB::init("letsneth_authenticate");
    $stmt = $database->prepare("SELECT * FROM `logged_users` WHERE `uniquekey` = :uk");
    $stmt->execute(array(
      ":uk" => $array["uniquekey"]
    ));
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    foreach($result as $user){
      if($user["devicekey"] == $array["devicekey"]){
        return TRUE;
      }
    }
    return FALSE;
  }

  public function checkAuth($post){
    foreach ($_SESSION as $key => $value) {
      if($key == $post["devicekey"] && $value == $post["uniquekey"]){
        return TRUE;
      }
    }
    return FALSE;
  }
  public function logOut($post_array){
    foreach ($_SESSION as $key => $value) {
      if($key == $post_array["devicekey"]) unset($_SESSION[$key]);
    }
  }
}
