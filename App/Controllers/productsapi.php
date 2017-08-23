<?php

      require("App/Models/productsapi_Model.php");
      $model = new productsapi_Model();
      $router = new CoreApp\Router();

      $router->post("getProductsByCategory", TRUE, function() {
        $category_from = $_POST["id"];
        $position = $_POST["position"];
        print_r(json_encode($model->getProductsByCategory($category_from, $position)));
      });

      $router->post("getOneProduct", TRUE, function() {
        $prod_id = $_POST["prodid"];
        print_r($model->getOneProduct($prod_id));
      });

      $router->post("uploadProduct", TRUE, function() {
        $product = $_POST["product"];
        print_r($model->uploadProduct($product));
      });

      $router->post("deleteProduct", TRUE, function() {
        $a = $_POST["prodid"];
        $model->deleteProduct($a);
      });

      $router->post("position", TRUE, function() {
        $c = isset($_POST["category"]) ? $_POST["category"] : 0;
        $prods = $_POST["products"];
        $model->position($c, $prods);
      });

      $router->post("backProduct", TRUE, function() {
        $a = $_POST["prod_id"];
        $model->backProduct($a);
      });

      $router->post("getlabels", TRUE, function() {
          print_r($model->getLabels());
      });
