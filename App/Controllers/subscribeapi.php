<?php

      require("App/Models/subscribeapi_Model.php");
      $model = new subscribeapi_Model();
      $router = new CoreApp\Router();

      $router->post("newsubscribe", TRUE, function() {
          $p = $_POST;
          $model->uploadNewSubscribe($p);
      });
