<?php
    class categoryapi_Model extends CoreApp\DataModel {
        public function __construct() {
          parent::__construct();
        }

        public function getCategory($category_from) {

          $CATDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));

          $stmt = $CATDB->prepare("SELECT category_name, category_id, GROUP_CONCAT(DISTINCT CONCAT(category_id,',',category_name) SEPARATOR ';') FROM main_categories WHERE category_visible = :category_visible AND category_from = :category_from GROUP BY category_from");
          $stmt->execute(array(
            ":category_visible" => 1,
            ":category_from" => $category_from
          ));

          $result = $stmt->fetchAll(PDO::FETCH_NUM);

          $category = array();
          if(!empty($result)) {
            $category["subcats"] = array();
            $category["name"] = $result[0][0];
            $category["id"] = $result[0][1];

            $subcategories = explode(";", $result[0][2]);

            $c_sub = count($subcategories);

            for($i = 0; $i < $c_sub; $i++) {
              $subc = explode(",", $subcategories[$i]);
              $category["subcats"][$subc[0]] = $subc[1];
            }
          }
          else {
            $category = array();
          }
          
          return $category;
        }
    }
