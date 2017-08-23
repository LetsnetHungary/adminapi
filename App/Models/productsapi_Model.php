<?php
    class productsapi_Model extends CoreApp\DataModel {

      public $products;

      public function __construct() {
      }

      public function getProductsByCategory($prod_categories, $position) {
      }

      public function getOneProduct($prod_id) {

      }

      /* UPLOAD PRODUCT SECTION */

      public function uploadProduct($product) {

      }

      private function uploadImage($prod_id, $record, $position) {

      }

      private function DeleteImageRecord($prod_id, $position) {

      }

      private function shouldDeleteOldRecord($id, $records) {

      }

      private function updateImageRecord($id, $position) {

      }

      private function ImagePosition($id, $old_records) {

      }

      public function GetOldRecords($prod_id) {

      }

      /* END UPLOAD PRODUCT SECTION */

      /* GET LABELS SECTION */

      public function getLabels() {

      }

      /* END GET LABELS SECTION */

      /* DELETE PRODUCT SECTION */

      public function deleteProduct($prod_id) {

      }

      /* END DELETE PRODUCT SECTION */

      /* BACK PRODUCT SECTION */

      public function backProduct($prod_id) {

      }

      /* END BACK PRODUCT SECTION */


      /* POSITION SECTION */

      public function position($category, $products) {

      }
      /* END POSITION SECTION */

      public static function v4() {
      }
    }
