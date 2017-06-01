<?php

    class ordersapi extends CoreApp\InnerController {

      public function __construct() {
        parent::__construct(__CLASS__);
        $this->model = $this->loadModel(__CLASS__);
      }

      public function getOrders() {
        echo json_encode($this->model->getOrders());
      }

      public function viewOrder() {
         if(isset($_POST["id"])) {
             $id = $_POST["id"];
             $a = $this->model->viewOrder($id);
             echo(json_encode($a));
         }
         else {
             echo "Nincs választott termék!";
         }
       }

       public function setState() {
         $id = $_POST["id"];
         $state = $_POST["state"];
         $this->model->setState($id, $state);
       }

       public function notVisible() {
         $id = $_POST["id"];
         $this->model->notVisible($id);
       }
    }
