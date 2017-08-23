<?php

      require("App/Models/ordersapi_Model.php");
      $model = new ordersapi_Model();
      $router = new CoreApp\Router();

      $router->post("getOrders", TRUE, function() {
          echo json_encode($model->getOrders());
      });

      $router->post("viewOrder", TRUE, function() {
           if(isset($_POST["id"])) {
               $id = $_POST["id"];
               $a = $model->viewOrder($id);
               echo(json_encode($a));
           }
           else {
               echo "Nincs választott termék!";
           }
      });

      $router->post("setState", TRUE, function() {
        $id = $_POST["id"];
        $state = $_POST["state"];
        $model->setState($id, $state);
      });

      $router->post("notVisible", TRUE, function() {
          $id = $_POST["id"];
          $model->notVisible($id);
      });
