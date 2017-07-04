<?php

    class CMSAPI extends CoreApp\Controller {

        public function __construct($info) {
            parent::__construct(__CLASS__);
            $this->loadModel(__CLASS__);
            print_r($_POST);
        }

        public function imageset() {
          $section = $_POST["section"];
          $this->model->UploadImageSet($section);
        }

        public function text() {
          $section = $_POST["section"];
          $this->model->uploadText($section);
        }

        public function itemset() {
          $section = $_POST["section"];
          $this->model->UploadItemSet($section);
        }

    }
