<?php

  class productsapi extends CoreApp\InnerController {
      public function __construct() {
        parent::__construct(__CLASS__);
        $this->model = $this->loadModel(__CLASS__);
      }

      public function getProductsByCategory() {
        $category_from = $_POST["id"];
        $position = $_POST["position"];
        print_r(json_encode($this->model->getProductsByCategory($category_from, $position)));
      }

      public function getOneProduct() {
        $prod_id = $_POST["prodid"];
        print_r($this->model->getOneProduct($prod_id));
      }

      public function uploadProduct() {
        $product = $_POST["product"];
        print_r($this->model->uploadProduct($product));
      }

      public function deleteProduct() {
        $a = $_POST["prodid"];
        $this->model->deleteProduct($a);
      }

      public function position() {
        $c = $_POST["category"];
        $prods = $_POST["products"];

        $this->model->position($c, $prods);
      }

      public function backProduct() {
        $a = $_POST["prod_id"];
        $this->model->backProduct($a);
      }

      public function getLabels() {
        print_r($this->model->getLabels());
      }
  }
