<?php

    class ordersapi_Model extends CoreApp\DataModel {
        public function __construct() {
            parent::__construct();
        }

        public function getOrders() {
          $ORDERSDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $ORDERSDB->prepare("SELECT id, datee, year, month, day, name, email, phone, type, state, cart, visible FROM neworders WHERE visible = 1 ORDER BY id DESC");
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $result;
        }

        public function viewOrder($id) {
          $ORDERSDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $ORDERSDB->prepare("SELECT id, datee, name, email, phone, birth, address, afa, afaname, afaaddress, afadata, type, pickpackdata, state, cart FROM neworders WHERE id = :id");
          $stmt->execute(array(
              ":id" => $id
          ));
          if($result = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
              return($result[0]);
          }
        }

        public function setState($id, $state) {
          $ORDERSDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $ORDERSDB->prepare("UPDATE neworders SET state = :state WHERE id = :id");
          $stmt->execute(array(
            ":state" => $state,
            ":id" => $id
          ));
          return;
        }

        public function notVisible($id) {
          $ORDERSDB = $this->database->PDOConnection(CoreApp\AppConfig::getData("database=>prodDB"));
          $stmt = $ORDERSDB->prepare("UPDATE neworders SET visible = '0' WHERE id = :id");
          $stmt->execute(array(
            ":id" => $id
          ));
          return;
        }
    }
