<?php
    class sellsapi_Model extends CoreApp\DataModel {

      public function __construct() {
        parent::__construct();
      }

      public function getSells() {
        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $stmt = $PRODDB->prepare("SELECT prods.prod_id, prods.prod_name, sells.stock, sells.webshopstock, sells.webshopsold, sells.marketsold, sells.friendlysold FROM prods INNER JOIN sells ON (prods.prod_id = sells.prod_id) LIMIT 100");
        $stmt->execute();
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return($r);
      }

      public function addstock($prod_id, $count) {
        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $stmt = $PRODDB->prepare("UPDATE sells SET stock = :stock WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":stock" => $count,
          ":prod_id" => $prod_id
        ));
        if($count == 0) {
          $this->addWebshopStock($prod_id, 0);
        }
        return;
      }

      public function addWebshopStock($prod_id, $count) {
        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));

        $stmt = $PRODDB->prepare("SELECT webshopstock FROM sells WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        $r = $stmt->fetchAll();
        $difference = $r[0][0] - $count;

        $stmt = $PRODDB->prepare("UPDATE sells SET webshopstock = :count WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id,
          ":count" => $count
        ));

        $stmt = $PRODDB->prepare("UPDATE sells SET stock = stock + $difference WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        return;
      }

      public function addFriendlySold($prod_id, $count) {
        $PRODDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
        $stmt = $PRODDB->prepare("SELECT friendlysold FROM sells WHERE prod_id = :prod_id ");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        $r = $stmt->fetchAll();
        $difference =  $count - $r[0][0];

        $stmt = $PRODDB->prepare("UPDATE sells SET friendlysold = :count WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id,
          ":count" => $count
        ));

        $stmt = $PRODDB->prepare("UPDATE sells SET stock = stock - $difference WHERE prod_id = :prod_id");
        $stmt->execute(array(
          ":prod_id" => $prod_id
        ));
        return;
      }
    }
