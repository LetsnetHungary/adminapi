<?php

      $router = new CoreApp\Router();


      require("App/Models/comissionapi_Model.php");
      $model = new comissionapi_Model();

      $router->post("getComissions", TRUE, function() {
          echo json_encode($model->getCommission());
      });

      $router->post("getProducts", TRUE, function() {
          echo json_encode($model->getProducts());
      });

      $router->post("getShops", TRUE, function() {
          echo json_encode($model->getShops());
      });

      $router->post("getPrices", TRUE, function() {
          echo json_encode($model->getPrices());
      });

      $router->post("addComission", TRUE, function() {
          $data = $_POST;
          $model->addCommission($data);
      });

      $router->post("deleteComissions", TRUE, function() {
          $id = $_POST["id"];
          $model->deleteCommission($id);
      });

      $router->post("refreshCount", TRUE, function() {
        $id = $_POST["id"];
        $count = $_POST["count"];
        $model->refreshCount($id, $count);
      });
