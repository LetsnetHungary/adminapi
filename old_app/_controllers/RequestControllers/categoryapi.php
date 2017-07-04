<?php

  class categoryapi extends CoreApp\InnerController {
      public function __construct() {
        parent::__construct(__CLASS__);
        $this->model = $this->loadModel(__CLASS__);
      }

      public function getCategory() {
        $category_from = $_POST["id"];
        print_r(json_encode($this->model->getCategory($category_from)));
      }
  }
