<?php

namespace CoreApp;
use Exception;

    class AttemptUser {
        private $ip;
        private $useragent;
        private $aemail;
        private $apassword;
        private $devicekey;
        private $lalo;

        private $e;
        private $p;
        private $uniquekey;
        private $allow;
        private $sitekey;

        public function __get($key) {
          if(property_exists($this, $key)) {
            return $this->$key;
          }
          else {
            //throw new Exception("Unknown member $key");
            echo "Unknown property...$key (get)"; return;
          }
        }

        public function __set($key, $value) {
          if(!property_exists($this, $key)) {
              //throw new Exception("Unknown member $key");
              echo "Unknown property...$key (set)"; return;
          }
          $this->$key = $value;
        }

        public function __construct() {
          $this->ip = $_SERVER["REMOTE_ADDR"];
          $this->useragent = $_SERVER["HTTP_USER_AGENT"];
        }

        public function prepareCredentials() {
            //$this->e = has_specchar($this->aemail);
            //$this->p = has_specchar($this->apassword);
            //do some magic (XSS INJECTION)
            $this->e = $this->aemail;
            $this->p = $this->apassword;
            unset($this->aemail);
            unset($this->apassword);
            return;
        }

        public function getLoginCredentials() {
            $credentials = array();
            $credentials["email"] = $this->e;
            $credentials["passw"] = $this->p;
            return $credentials;
        }
    }
