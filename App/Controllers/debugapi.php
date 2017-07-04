<?php

  class debugapi extends CoreApp\Controller {
      public function __construct() {
        parent::__construct(__CLASS__);
        $this->loadModel(__CLASS__);
      }

      public function debug()
      {
        $a = $_POST;
          $this->model->apidebug($a);
      }
  }
