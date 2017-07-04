<?php
    class CMSAPI_Model extends CoreApp\Model {

      public function __construct() {
          parent::__construct();
          $this->db->exec("Use ".CoreApp\AppConfig::getCMSDB());
      }

      //TEXTS SECTION

      public function uploadText($section) {
        $section_json = json_decode(json_encode($section));

        $old_records = json_decode(json_encode($this->getOldTexts($section_json->view, $section_json->section_name, $section_json->type)));
        $records = json_decode(json_encode($section["record"]));

        foreach ($records as $key => $value) {
          if(is_object($value)) {
            foreach ($value as $k => $v) {
              if(isset($old_records->$key->$k)) {
                if($value == $old_records->$key) {
                  continue;
                }
                $this->updateTextRecord($section["view"], $section["section_name"], $key, $k, $v);
                continue;
              }
              else {
                $this->uploadTextRecord($section["view"], $section["section_name"], $key, $k, $v);
                continue;
              }
            }
          }
          else if(isset($old_records->$key)) {
            if($value == $old_records->$key) {
              continue;
            }
            $this->updateTextRecord($section["view"], $section["section_name"], $key, "---", $value);
            continue;
          }
          else {
            $this->uploadTextRecord($section["view"], $section["section_name"], $key, "---", $value);
            continue;
          }
        }
        foreach ($old_records as $key => $value) {
          if(!isset($records->$key)) {
            $this->deleteTextRecord($section["view"], $section["section_name"], $key);
          }
        }
        print_r(json_encode($this->getOldTexts($section["view"], $section["section_name"])));
      }

      private function updateTextRecord($view_id, $section, $defaultkey, $innerkey, $value) {
        if(is_object($value)) {
          $value = json_encode($value);
        }
        $stmt = $this->db->prepare("UPDATE cms_texts SET value = :value WHERE defaultkey = :defaultkey AND innerkey = :innerkey AND section = :section AND view = :view");
        $stmt->execute(array(
          ":value" => $value,
          ":defaultkey" => $defaultkey,
          ":innerkey" => $innerkey,
          ":section" => $section,
          ":view" => $view_id
        ));
        return;
      }

      private function uploadTextRecord($view_id, $section, $defaultkey, $innerkey, $value) {
        if(is_object($value)) {
          $value = json_encode($value);
        }
        $stmt = $this->db->prepare("INSERT INTO cms_texts (view, section, defaultkey, innerkey, value) VALUES (:view, :section, :defaultkey, :innerkey, :value)");
        $stmt->execute(array(
          ":view" => $view_id,
          ":section" => $section,
          ":defaultkey" => $defaultkey,
          ":innerkey" => $innerkey,
          ":value" => $value
        ));
        return;
      }

      private function deleteTextRecord($view_id, $section, $defaultkey) {
        $stmt = $this->db->prepare("DELETE FROM cms_texts WHERE view = :view AND section = :section AND defaultkey = :defaultkey");
        $stmt->execute(array(
          ":view" => $view_id,
          ":section" => $section,
          ":defaultkey" => $defaultkey
        ));
        return;
      }

      private function getOldTexts($view, $section) {
        $stmt = $this->db->prepare("SELECT cms_texts.defaultkey, cms_texts.innerkey, cms_texts.value FROM cms_texts WHERE view = :view AND section = :section");
        $stmt->execute(array(
          ":view" => $view,
          ":section" => $section
        ));
        if($result = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
          $json = array();
          $c_result = count($result);
          for($i = 0; $i < $c_result; $i++) {
            if($result[$i]["innerkey"] == "---") {
              $json[$result[$i]["defaultkey"]] = $result[$i]["value"];
              continue;
            }
            else {
              $stmt = $this->db->prepare("SELECT value FROM cms_texts WHERE defaultkey = :defaultkey AND innerkey = :innerkey");
              $stmt->execute(array(
                ":defaultkey" => $result[$i]["defaultkey"],
                ":innerkey" => $result[$i]["innerkey"]
              ));
              if($innerval = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $json[$result[$i]["defaultkey"]][$result[$i]["innerkey"]] = $innerval[0]["value"];
              }
            }
          }
          return $json;
        }
        return array();
      }

      //IMAGESET SECTION

      public function UploadImageSet($section) {
        $view = $section["view"];
        $section_name = $section["section_name"];
        $records = $section["records"];
        $count_records = count($records);

        $old_records = $this->GetOldRecords($view, $section_name);
        $old_records_count = count($old_records);
        for($i=0; $i < $count_records; $i++) {
            if($records[$i]["imgtype"] == "new") {
              $this->uploadImage($view, $section_name, $records[$i]);
            }
            else if($records[$i]["imgtype"] == "old") {
              if($records[$i]["image_id"] == $old_records[$i]["image_id"]) {
                continue;
              }
              else {
                $position = $this->Imposition($records[$i]["image_id"], $old_records);
                if($position) {
                  $this->updateImageRecord($records[$i]["image_id"], $records[$i]["position"]);
                }
              }
            }
          }

          for($i=0; $i < $old_records_count; $i++) {
            $shouldDeleteOldRecord = $this->shouldDeleteOldImageRecord($old_records[$i]["image_id"], $records);
            if($shouldDeleteOldRecord) {
              $this->DeleteImageRecord($old_records[$i]["image_id"]);
            }
          }

          $json_content = array();
          $json = $this->getImageSetJsonContent($view, $section_name);
          $json_count = count($json);

          for($k = 0; $k < $json_count; $k++) {
            $type = explode('/', $json[$k]["type"]);
            $json_content[$json[$k]["image_id"]] = "../../_cms/rastaclat/_img/$view/$section_name/".$json[$k]["image_id"].".$type[1]";
          }

          echo json_encode($json_content);
      }

      private function uploadImage($view, $section_name, $record) {
        $image_id = $this->generateID(7);
        $stmt = $this->db->prepare("INSERT INTO cms_image (view, section, image_id, position, type, encode, data) VALUES (:view, :section, :image_id, :position, :type, :encode, :data)");
        $stmt->execute(array(
          ":view" => $view,
          ":section" => $section_name,
          ":image_id" => $image_id,
          ":position" => $record["position"],
          ":type" => $record["type"],
          ":encode" => $record["encode"],
          ":data" => $record["data"]
        ));
        return;
      }

      private function Imposition($id, $old_records) {
        for($i=0; $i < count($old_records); $i++) {
          if($id == $old_records[$i]["image_id"]) {
            return $old_records[$i]["position"];
          }
        }
        return false;
      }

      private function Iposition($id, $old_records) {
        for($i=0; $i < count($old_records); $i++) {
          if($id == $old_records[$i]["productkey"]) {
            return $old_records[$i]["position"];
          }
        }
        return false;
      }

      private function updateImageRecord($id, $position) {
        $stmt = $this->db->prepare("UPDATE cms_image SET position = :position WHERE image_id = :image_id");
        $stmt->execute(array(
          ":image_id" => $id,
          ":position" => $position
        ));
      }

      private function shouldDeleteOldImageRecord($id, $records) {
        $c_records = count($records);
        for($i = 0; $i < $c_records; $i++) {
          if($id == $records[$i]["image_id"]) {
            return false;
          }
        }
        return true;
      }

      private function DeleteImageRecord($id) {
        $stmt = $this->db->prepare("DELETE FROM cms_image WHERE image_id =  :image_id");
        $stmt->execute(array(
          "image_id" => $id
        ));
      }

      private function getImageSetJsonContent($view, $section_name) {
        $stmt = $this->db->prepare("SELECT image_id, type FROM cms_image WHERE view = :view AND section = :section_name ORDER BY position");
        $stmt->execute(array(
          ":view" => $view,
          ":section_name" => $section_name
        ));
        if($records = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
          return($records);
        }
        else {
          return array();
        }
      }

      //ITEMSET SECTION

      public function UploadItemSet($section) {

        $old_records = $this->GetOldRecords($section["view"], $section["section_name"]);
        $c_old_records = count($old_records);
        $records = $section["records"];
        $c_records = count($records);

        for($i = 0; $i < $c_records; $i++) {

          if($records[$i]["itemtype"] == "new") {
            $this->uploadNewItem($section["view"], $section["section_name"], $records[$i]);
          }
          else if($records[$i]["itemtype"] == "old") {
            if($records[$i]["prodid"] == $olds_records[$i]["productkey"]) {
              continue;
            }
            else {
              $position = $this->Iposition($records[$i]["prodid"], $old_records);
              if($position) {
                $this->updateItemRecord($records[$i]["prodid"], $records[$i]["position"]);
              }
            }
          }
        }

        $stmt = $this->db->prepare("SELECT cms_items.productkey, cms_item_prop.innerkey, cms_item_prop.value, cms_item_image.type FROM cms_items INNER JOIN cms_item_prop ON (cms_items.productkey = cms_item_prop.defaultkey) INNER JOIN cms_item_image ON (cms_items.productkey = cms_item_image.image_id) ORDER BY cms_items.position");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $c_result = count($result);

        $json = array();

        for($k = 0; $k < $c_result; $k++) {
          $json[$result[$k]["productkey"]][$result[$k]["innerkey"]] = $result[$k]["value"];
          $json[$result[$k]["productkey"]]["imagetype"] = $result[$k]["type"];
        }
        echo json_encode($json);
      }

      private function uploadNewItem($view, $section, $record) {
        $stmt = $this->db->prepare("INSERT INTO cms_items (view, section, productkey, position) VALUES (:view, :section, :productkey, :position)");
        $stmt->execute(array(
          ":view" => $view,
          ":section" => $section,
          ":productkey" => $record["prodid"],
          ":position" => $record["position"]
        ));

        $this->uploadItemSetImage($view, $section, $record);
        $this->uploadItemSetPlaintext($view, $section, $record);
      }

      private function uploadItemSetImage($view, $section, $record) {
        $stmt = $this->db->prepare("INSERT INTO cms_item_image (view, section, image_id, position, type, encode, data) VALUES (:view, :section, :image_id, :position, :type, :encode, :data)");
        $stmt->execute(array(
          ":view" => $view,
          ":section" => $section,
          ":image_id" => $record["prodid"],
          ":position" => "0",
          ":type" => $record["image"]["type"],
          ":encode" => $record["image"]["encode"],
          ":data" => $record["image"]["data"]
        ));
        return;
      }

      private function uploadItemSetPlaintext($view, $section, $record) {
        $properties = json_decode(json_encode($record["properties"]));
        foreach ($properties as $key => $value) {
          $stmt = $this->db->prepare("INSERT INTO cms_item_prop (view, section, defaultkey, innerkey, value) VALUES (:view, :section, :defaultkey, :innerkey, :value)");
          $stmt->execute(array(
            ":view" => $view,
            ":section" => $section,
            ":defaultkey" => $record["prodid"],
            ":innerkey" => $key,
            ":value" => $value
          ));
        }
      }

      private function updateItemRecord($prodid, $position) {
        $stmt = $this->db->prepare("UPDATE cms_items SET position = :position WHERE productkey = :productkey");
        $stmt->execute(array(
          ":productkey" => $prodid,
          ":position" => $position
        ));
      }

      private function GetOldRecords($view, $section_name) {

        $stmt = $this->db->prepare("SELECT section_map.type FROM section_map INNER JOIN cms_map ON (section_map.cms_map_id = cms_map.cms_map_id) WHERE section_map.view = :view AND section_map.section = :section");
        $stmt->execute(array(
          ":view" => $view,
          ":section" => $section_name
        ));

        if($result = $stmt->fetchAll(PDO::FETCH_ASSOC)) {

          $orderby = "";
          switch ($result[0]["type"]) {
            case 'imageset':
              $query = "SELECT image_id, position, type FROM cms_image WHERE view = :view AND section = :section ORDER BY position";
              break;

            case 'text':
              return $this->getOldTexts($view, $section_name);
              break;

            case 'itemset':
              $db_name = "cms_items";
              $query = "SELECT cms_items.productkey, cms_items.position FROM cms_items INNER JOIN cms_item_image ON (cms_items.productkey = cms_item_image.image_id) INNER JOIN cms_item_prop ON (cms_items.productkey = cms_item_prop.defaultkey) WHERE cms_items.view = :view AND cms_items.section = :section GROUP BY cms_items.productkey ORDER BY cms_items.position";
              break;

            default:
              break;
          }

          $stmt = $this->db->prepare($query);
          $stmt->execute(array(
            ":view" => $view,
            ":section" => $section_name
          ));

          if($records = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return($records);
          }
          else {
            return array();
          }
        }
        return array();
      }

      private function generateID($length) {
          return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyz', ceil($length/strlen($x)) )),1,$length);
      }

    }
