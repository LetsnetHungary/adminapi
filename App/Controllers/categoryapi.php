<?php

  class categoryapi extends CoreApp\Controller {
      public function __construct($info) {
        parent::__construct(__CLASS__);
        $this->loadModel(__CLASS__);
      }

      public function getCategory() {
        $category_from = $_POST["id"];
        print_r(json_encode($this->model->getCategory($category_from)));
      }
  }
