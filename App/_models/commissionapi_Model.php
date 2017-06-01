<?php
    class commissionapi_Model extends CoreApp\DataModel {
        public function __construct() {
          parent::__construct();
        }

        public function getCommission() {
          $COMMDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $COMMDB->prepare("SELECT prods.prod_id, prods.prod_name, commission.id, commission.who, commission.price, commission.count, commission.sold, commission.about, commission.givedate, commission.deadline FROM prods INNER JOIN commission ON (commission.prod_id = prods.prod_id) WHERE commission.visible = 1");
          $stmt->execute();
          $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $rc = count($r);

          $commissions = array();

          for($i=0; $i < $rc; $i++) {
            $commissions[$r[$i]["prod_id"]]["prod_name"] = $r[$i]["prod_name"];
            if(isset($commissions[$r[$i]["prod_id"]]["records"])) {
              array_push($commissions[$r[$i]["prod_id"]]["records"], $r[$i]);
            }
            else {
              if(empty($r[$i]["who"])) {
                continue;
              }
              $commissions[$r[$i]["prod_id"]]["records"] = array();
              array_push($commissions[$r[$i]["prod_id"]]["records"], $r[$i]);
            }
          }
          return($commissions);
        }

        public function getProducts() {
          $COMMDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $COMMDB->prepare("SELECT prods.prod_id, prods.prod_name FROM prods LIMIT 1000");
          $stmt->execute();
          $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $r;
        }

        public function getShops() {
          $COMMDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $COMMDB->prepare("SELECT who FROM commission GROUP BY who");
          $stmt->execute();
          $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $r;
        }

        public function getPrices() {
          $COMMDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $COMMDB->prepare("SELECT price FROM commission GROUP BY price");
          $stmt->execute();
          $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $r;
        }

        public function addCommission($data) {
          $COMMDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $COMMDB->prepare("INSERT INTO commission (prod_id, who, price, count, sold, about, givedate, deadline, visible) VALUES (:prod_id, :who, :price, :count, :sold, :about, :givedate, :deadline, 1)");
          $stmt->execute(array(
            ":prod_id" => $data["data"]["product"],
            ":who" => $data["data"]["name"],
            ":price" => $data["data"]["price"],
            ":count" => $data["data"]["count"],
            ":sold" => '0',
            ":about" => '0',
            ":givedate" => date("Y.m.d"),
            ":deadline" => $data["data"]["deadline"]
          ));

          $stmt = $COMMDB->prepare("UPDATE sells SET stock = stock - ".$data["data"]["count"].", marketsold = marketsold + ".$data["data"]["count"]." WHERE prod_id = :prod_id");
          $stmt->execute(array(
            ":prod_id" => $data["data"]["product"]
          ));
          return;
        }

        public function deleteCommission($id) {
          $COMMDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $COMMDB->prepare("UPDATE commission SET visible = '0' WHERE id = :id");
          $stmt->execute(array(
            ":id" => $id
          ));
          return;
        }

        public function refreshCount($id, $count) {
          $COMMDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $COMMDB->prepare("UPDATE commission SET sold = :count WHERE id = :id");
          $stmt->execute(array(
            ":count" => $count,
            ":id" => $id
          ));
          return;
        }
    }
