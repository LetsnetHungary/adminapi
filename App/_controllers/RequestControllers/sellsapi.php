<?php

  class sellsapi extends CoreApp\InnerController {
      public function __construct() {
          parent::__construct(__CLASS__);
          $this->model = $this->loadModel(__CLASS__);
    		}

        public function getSells() {
          echo json_encode($this->model->getSells());
        }

        public function addStock() {
          $prod_id = $_POST["prod_id"];
          $count = $_POST["count"];
          $this->model->addStock($prod_id, $count);
        }

        public function addWebshopStock() {
          $prod_id = $_POST["prod_id"];
          $count = $_POST["count"];
          $this->model->addWebshopStock($prod_id, $count);
        }

        public function addFriendlySold() {
          $prod_id = $_POST["prod_id"];
          $count = $_POST["count"];
          $this->model->addFriendlySold($prod_id, $count);
        }
  }
