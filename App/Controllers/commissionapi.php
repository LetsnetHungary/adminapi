<?php

  class commissionapi extends CoreApp\Controller {
      public function __construct($info) {
        parent::__construct(__CLASS__);
        $this->loadModel(__CLASS__);
      }

      public function getCommissions() {
  			echo json_encode($this->model->getCommission());
  		}

  		public function getProducts() {
  			echo json_encode($this->model->getProducts());
  		}

  		public function getShops() {
  			echo json_encode($this->model->getShops());
  		}

  		public function getPrices() {
  			echo json_encode($this->model->getPrices());
  		}

      public function addCommission() {
        $data = $_POST;
        $this->model->addCommission($data);
      }

      public function deleteCommission() {
        $id = $_POST["id"];
        $this->model->deleteCommission($id);
      }

      public function refreshCount() {
        $id = $_POST["id"];
        $count = $_POST["count"];
        $this->model->refreshCount($id, $count);
      }
  }
