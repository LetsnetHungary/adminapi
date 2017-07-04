<?php

namespace CoreApp;

  class Response {

    private $responsemessage = array("error" => "---");

    public function setResponse($key, $message) {
      $this->responsemessage[$key] = $message;
    }

    public function Send() {
      echo json_encode($this->responsemessage);
      die();
    }
  }
