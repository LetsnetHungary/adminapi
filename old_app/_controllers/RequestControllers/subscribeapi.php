<?php

    class subscribeapi extends CoreApp\InnerController {
        public function __construct() {
            parent::__construct(__CLASS__);
            $this->model = $this->loadModel(__CLASS__);
        }

        public function newsubscribe() {
            $p = $_POST;
            $this->model->uploadNewSubscribe($p);
        }
    }
