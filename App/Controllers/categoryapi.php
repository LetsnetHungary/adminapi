<?php

      require("App/Models/categoryapi_Model.php");
      $model = new categoryapi_Model();
      $router = new CoreApp\Router();

      $router->post("getCategory", TRUE, function() {
          $category_from = $_POST["id"];
          print_r(json_encode($model->getCategory($category_from)));
      });
