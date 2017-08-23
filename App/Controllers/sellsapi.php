<?php

      require("App/Models/sellsapi_Model.php");
      $model = new sellsapi_Model();
      $router = new CoreApp\Router();

      $router->post("getSells", TRUE, function() {
          echo json_encode($model->getSells());
      });

      $router->post("addStock", TRUE, function() {
        $prod_id = $_POST["prod_id"];
        $count = $_POST["count"];
        $model->addStock(ct($info)d_id, $count);
      });

      $router->post("addWebshopStock", TRUE, function() {
        $prod_id = $_POST["prod_id"];
        $count = $_POST["count"];
        $model->addWebshopStock($prod_id, $count);
      });

      $router->post("addFriendlySold", TRUE, function() {
        $prod_id = $_POST["prod_id"];
        $count = $_POST["count"];
        $model->addFriendlySold($prod_id, $count);
      });
