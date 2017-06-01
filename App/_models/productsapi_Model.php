<?php
    class productsapi_Model extends CoreApp\DataModel {
      public function __construct() {
        parent::__construct();
      }

      public function getProductsByCategory($prod_categories, $position) {

        $CATDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $stmt = $CATDB->prepare("SELECT prods.prod_id, prods.prod_name FROM prods INNER JOIN categories ON (prods.prod_id = categories.prod_id) INNER JOIN category_position ON (category_position.prod_id = prods.prod_id) WHERE categories.prod_categories LIKE :prod_categories AND category_position.category = :category GROUP BY prods.prod_id ORDER BY category_position.position LIMIT 20");
        
        if($prod_categories == "all") {
           $stmt->execute(array(
          ":prod_categories" => "%all%",
          ":category" => $prod_categories
        ));
        }
        else {
          $stmt->execute(array(
            ":prod_categories" => "%=>$prod_categories%",
            ":category" => $prod_categories
          ));
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  
      }

      public function getOneProduct($prod_id) {

        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $PRODDB->exec("SET GLOBAL group_concat_max_len = 100000;");

        $stmt = $PRODDB->prepare("SELECT prods.prod_name, prods.prod_price, prods.outofstock, GROUP_CONCAT(categories.prod_categories) FROM prods INNER JOIN categories ON (prods.prod_id = categories.prod_id) WHERE prods.prod_id = :prod_id GROUP BY prods.prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));

        $result = $stmt->fetchAll(PDO::FETCH_NUM);

        $product = array();
        $product["prod_id"] = $prod_id;
        $product["prod_name"] = $result[0][0];
        $product["prod_price"] = $result[0][1];
        $product["outofstock"] = $result[0][2];

        /* CATEGORIES SECTION */

        $product["categories"] = array();
        $categories = explode(',', $result[0][3]);
        $c_c = count($categories);

        if(!empty($categories)) {
          for($i=0; $i < $c_c; $i++) {
            $cats = explode("=>", $categories[$i]);
            $c_cats = count($cats);
            $c = array();
            for ($k=0; $k < $c_cats; $k++) {
              $stmt = $PRODDB->prepare("SELECT category_name FROM main_categories WHERE category_id = :category_id");
              $stmt->execute(array(
                ":category_id" => $cats[$k]
              ));
              $r = $stmt->fetchAll(PDO::FETCH_NUM);
              $c[$cats[$k]] = $r[0][0];
            }
            array_push($product["categories"], $c);
          }
        }

        /* END CATEGORIES SECTION */

        /* LABELS SECTION */

        $product["labels"] = array();
        $stmt = $PRODDB->prepare("SELECT GROUP_CONCAT(labels.label_id) FROM labels WHERE labels.prod_id = :prod_id GROUP BY labels.prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        $labels = $stmt->fetchAll(PDO::FETCH_NUM);
        if(!empty($labels)) {
          $labels = explode(",", $labels[0][0]);
          $c_l = count($labels);
          $l = array();
          for($i=0; $i < $c_l; $i++) {
            $stmt = $PRODDB->prepare("SELECT label_name FROM main_labels WHERE label_id = :label_id");
            $stmt->execute(array(
              ":label_id" => $labels[$i]
            ));
            $r = $stmt->fetchAll(PDO::FETCH_NUM);
            $l[$labels[$i]] = $r[0][0];
          }
          $product["labels"] = $l;
        }
        else {
          $product["labels"] = array();
        }
        

        /* END LABELS SECTION */

        /* PROPERTIES SECTION */

        $product["properties"] = array();

        $stmt = $PRODDB->prepare("SELECT GROUP_CONCAT(DISTINCT CONCAT(properties.p_id, '|', properties.property_id, '|', properties.property_value) SEPARATOR ';') FROM properties WHERE properties.prod_id = :prod_id GROUP BY properties.prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        $properties = $stmt->fetchAll(PDO::FETCH_NUM);
        if(!empty($properties)) {
          $properties = explode(";", $properties[0][0]);
          $c_p = count($properties);
          for ($i=0; $i < $c_p; $i++) {
            $prop = explode("|", $properties[$i]);
            $product["error"] = $prop;
            $product["properties"][$prop[0]] = array();
            $product["properties"][$prop[0]]['name'] = $prop[1];
            $product["properties"][$prop[0]]['value'] = $prop[2];
          }
        }
        else {
          $product["labels"] = array();
        }
       
        /* END PROPERTIES SECTION */

        return json_encode($product);
      }

      /* UPLOAD PRODUCT SECTION */

      public function uploadProduct($product) {

        $prod_id = isset($product["prodid"]) ? $product["prodid"] : $this->v4();
        $prod_id = empty($product["prodid"]) ? $this->v4() : $product["prodid"];

        $prod_name = isset($product["name"]) ? $product["name"] : " ___ PRODUCT ___";
        $prod_name = empty($product["name"]) ? " ___ PRODUCT ___" : $product["name"];

        $prod_price = isset($product["price"]) ? $product["price"] : "0";
        $prod_price = empty($product["price"]) ? "0" : $product["price"];

        $outofstock = $product["outofstock"];

        $categories = isset($product["categories"]) ? $product["categories"] : false;
        $labels = isset($product["labels"]) ? $product["labels"] : false;
        $properties = isset($product["properties"]) ? $product["properties"] : false;
        $records = isset($product["images"]) ? $product["images"] : false;

        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));

        /* INSERT TO PRODS TABLE */

        $stmt = $PRODDB->prepare("SELECT id FROM prods WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        if($result = $stmt->fetchAll(PDO::FETCH_ASSOC)){
          $sth = $PRODDB->prepare("UPDATE prods SET prod_name = :prod_name, prod_price = :prod_price, outofstock = :outofstock WHERE prod_id = :prod_id");  
        }
        else {
          $sth = $PRODDB->prepare("INSERT INTO prods (prod_id, prod_name, prod_price, outofstock) VALUES (:prod_id, :prod_name, :prod_price, :outofstock)");    
        }

        $sth->execute(array(
          ":prod_id" => $prod_id,
          ":prod_name" => $prod_name,
          ":prod_price" => $prod_price,
          ":outofstock" => $outofstock
        ));  

        /* END INSERT TO PRODS TABLE */
        
        /* INSERT TO PROD CATEGORIES TABLE */

        $stmt = $PRODDB->prepare("DELETE FROM categories WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));

        $stmt = $PRODDB->prepare("DELETE FROM category_position WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));

        if($categories) {
          $stmt = $PRODDB->prepare("SELECT category_id, category_name FROM main_categories");
          $stmt->execute();
          $oc = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $oc_c = count($oc);
          $old_categories = array();
          $old_categories_names = array();

          for($i=0; $i< $oc_c; $i++) {
            $old_categories[$i] = $oc[$i]["category_id"];
            $old_categories_names[$i] = $oc[$i]["category_name"];
          }

          $stmt = $PRODDB->prepare("INSERT INTO category_position (prod_id, category, position) VALUES (:prod_id, :category, :position)");
          $stmt->execute(array(
            ":prod_id" => $prod_id,
            ":category" => "all",
            ":position" => 0
          ));

          $c_c = count($categories);
          for ($i=0; $i <$c_c; $i++) { 
            $p_categories = "";
            $k = 0;
            if(!empty($categories)) {
              foreach ($categories[$i] as $key => $value) {
                if(!in_array($key, $old_categories) && !in_array($value, $old_categories_names)) {
                  $stmt = $PRODDB->prepare("INSERT INTO main_categories (category_name, category_id, category_from, category_visible) VALUES (:category_name, :category_id, :category_from, :category_visible)");
                  if($k == 0) {
                    $k++;
                    continue;
                  }
                  else {
                    $keys = array_keys($categories[$i]);
                    $array = array(":category_name" => $value, ":category_id" => $key, ":category_from" => $keys[$k - 1], "category_visible" => "1");
                  }
                  $stmt->execute($array);
                }
                if($k == 0) {
                  $p_categories .= $key;
                }
                else {
                  $stmt = $PRODDB->prepare("INSERT INTO category_position (prod_id, category, position) VALUES (:prod_id, :category, :position)");
                  $stmt->execute(array(
                    ":prod_id" => $prod_id,
                    ":category" => $key,
                    ":position" => 0
                  ));
                  $p_categories .= "=>$key";
                }
                $k++;
              }
              $stmt = $PRODDB->prepare("INSERT INTO categories (prod_id, prod_categories) VALUES (:prod_id, :prod_categories)");
              $stmt->execute(array(
                ":prod_id" => $prod_id,
                ":prod_categories" => $p_categories
              ));
            }
          }
        }

        /* END INSERT TO PROD CATEGORIES TABLE */

        /* INSERT TO LABELS TABLE */

        $stmt = $PRODDB->prepare("DELETE FROM labels WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));

        if($labels) {
          $stmt = $PRODDB->prepare("SELECT label_id, label_name FROM main_labels");
          $stmt->execute();
          $ol = $stmt->fetchAll();
          $old_labels = array();
          $old_labels_names = array();
          $ol_c = count($ol);
          for ($i=0; $i < $ol_c; $i++) { 
            $old_labels[$i] = $ol[$i]["label_id"];
            $old_labels_names[$i] = $ol[$i]["label_name"];
          }
          $ol = NULL;
          $ol_c = NULL; 

          if(!empty($labels)) {
            foreach ($labels as $key => $value) {
              if(!in_array($key, $old_labels) && !in_array($value, $old_labels_names)) {
                $stmt = $PRODDB->prepare("INSERT INTO main_labels (label_id, label_name) VALUES (:label_id, :label_name)");
                $stmt->execute(array(
                  ":label_id" => $key,
                  ":label_name" => $value
                ));
              }
              $stmt = $PRODDB->prepare("INSERT INTO labels (prod_id, label_id) VALUES (:prod_id, :label_id)");
                $stmt->execute(array(
                  ":prod_id" => $prod_id,
                  ":label_id" => $key
              ));
            }
          }
        }

        /* END INSERT TO LABELS TABLE */

        /* INSERT TO PROPERTIES TABLE */

        $stmt = $PRODDB->prepare("DELETE FROM properties WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));

        if(!empty($properties))  {
          $i = 0;
          foreach ($properties as $key => $value) {
            $stmt = $PRODDB->prepare("INSERT INTO properties (prod_id, p_id, property_id, property_value, position) VALUES (:prod_id, :p_id, :property_id, :property_value, :position)");
            $stmt->execute(array(
              ":prod_id" => $prod_id,
              ":p_id" => $key,
              ":property_id" => $value["name"],
              ":property_value" => $value["value"],
              ":position" => $i
            ));
            $i++;
          }
        }
          
        /* END INSERT TO PROPERTIES TABLE */

        /* INSERT TO PROD_IMAGES TABLE */
        $count_records = count($records);
        $old_records = $this->GetOldRecords($prod_id);
        $old_records_count = count($old_records);
        $deleted_records = array();

        if($records[0]["imagetype"] == "new") {
          $stmt = $PRODDB->prepare("DELETE FROM prod_images WHERE prod_id = :prod_id");
          $stmt->execute(array(
            ":prod_id" => $prod_id
          ));
          $this->uploadImage($prod_id, $records[0], 1);
        }

        /* SELLS TABLE */

        $stmt = $PRODDB->prepare("INSERT INTO sells (prod_id, stock, webshopstock, webshopsold, marketsold, friendlysold) VALUES (:prod_id, :stock, :webshopstock, :webshopsold, :marketsold, :friendlysold)");
        $stmt->execute(array(
          ":prod_id" => $prod_id,
          ":stock" => 1000,
          ":webshopstock" => 500,
          ":webshopsold" => 0,
          ":marketsold" => 0,
          ":friendlysold" => 0
        ));

        /* END SELLS TABLE */

        $product = array("prod_id" => $prod_id);
        return json_encode($product);

        /*

        for($i=0; $i < $count_records; $i++) {
          if($records[$i]["imagetype"] == "new") {
            $this->uploadImage($prod_id, $records[$i], $i);
          }
          else if($records[$i]["imagetype"] == "old") {
            if($records[$i]["prod_id"] == $old_records[$i]["prod_id"]) {
              continue;
            }
            else {
              $position = $this->ImagePosition($records[$i]["prod_id"], $old_records);
              if($position) {
                $this->updateImageRecord($records[$i]["prod_id"], $i);
              }
            }
          }
        }

        for($i=0; $i < $old_records_count; $i++) {
          $shouldDeleteOldRecord = $this->shouldDeleteOldRecord($old_records[$i]["prod_id"], $records);
          if($shouldDeleteOldRecord) {
            $this->DeleteImageRecord($old_records[$i]["prod_id"], $i);
            array_push($old_records[$i]["prod_id"], $deleted_records);
          }
        }

        return json_encode($deleted_records);
        */

        /* END INSERT TO PROD_IMAGES TABLE */

      }

      private function uploadImage($prod_id, $record, $position) {
        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $stmt = $PRODDB->prepare("INSERT INTO prod_images (prod_id, type, data, position) VALUES (:prod_id, :type, :data, :position)");
        $stmt->execute(array(
          ":prod_id" => $prod_id,
          ":type" => $record["type"],
          ":data" => $record["data"],
          ":position" => $position
        ));
        return;
      }

      private function DeleteImageRecord($prod_id, $position) {
        $stmt = $this->db->prepare("DELETE FROM prod_images WHERE prod_id = :prod_id AND position = :position");
        $stmt->execute(array(
          ":prod_id" => $prod_id,
          ":position" => $position
        ));
      }

      private function shouldDeleteOldRecord($id, $records) {
        for($i = 0; $i < count($records); $i++) {
          if($id == $records[$i]["prod_id"]) {
            return false;
          }
        }
        return true;
      }

      private function updateImageRecord($id, $position) {
        $stmt = $this->db->prepare("UPDATE cms_image SET position = :position WHERE image_id = :image_id");
        $stmt->execute(array(
          ":image_id" => $id,
          ":position" => $position
        ));
      }

      private function ImagePosition($id, $old_records) {
        for($i=0; $i < count($old_records); $i++) {
          if($id == $old_records[$i]["prod_id"]) {
            return $old_records[$i]["position"];
          }
        }
        return false;
      }

      public function GetOldRecords($prod_id) {
        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $stmt = $PRODDB->prepare("SELECT prod_id, type, position FROM prod_images WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        if($records = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
          return($records);
        }
        else {
          return array();
        }
      }

      /* END UPLOAD PRODUCT SECTION */

      /* GET LABELS SECTION */

      public function getLabels() {
        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $stmt = $PRODDB->prepare("SELECT label_id, label_name FROM main_labels");
        $stmt->execute();
        $ol = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($ol);
      }

      /* END GET LABELS SECTION */

      /* DELETE PRODUCT SECTION */

      public function deleteProduct($prod_id) {
        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $stmt = $PRODDB->prepare("UPDATE prods SET prod_id = CONCAT(prod_id, '-', 'nincs') WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        return;
      }

      /* END DELETE PRODUCT SECTION */

      /* BACK PRODUCT SECTION */

      public function backProduct($prod_id) {
        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $stmt = $PRODDB->prepare("UPDATE prods SET prod_id = SUBSTRING(prod_id, 1, LENGTH(prod_id) -6) WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        return;
      }

      /* END BACK PRODUCT SECTION */

      public static function v4() {
        return sprintf('%04x-%04x-%04x-%04x',mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),mt_rand(0, 0xffff));
      }
    }
