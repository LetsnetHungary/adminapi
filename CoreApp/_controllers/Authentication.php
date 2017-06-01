<?php

namespace CoreApp\Controller;
use \CoreApp;

  class Authentication extends CoreApp\InnerController {

      public function __construct() {
        parent::__construct(ClassName(__CLASS__));
        $this->model = $this->loadModel(ClassName(__CLASS__));
      }

      public function loginAttemptUser(CoreApp\AttemptUser $a) {
        $this->model->loginAttemptUser($a);
      }

      private function checkIfUserLoggedIn() {
        return $this->model->checkIfUserLoggedIn();
      }

      public function userShouldChangeLocation($l) {
        if(!$this->checkIfUserLoggedIn()) {
            //if the user is not logged in
            header("location: $l");
        }
      }

      public function loggedInChangeLocation($l) {
         if($this->checkIfUserLoggedIn()) {
             //if the user is logged in
             header("location: $l");
         }
       }

  }
