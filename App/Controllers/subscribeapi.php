<?php

    class subscribeapi extends CoreApp\Controller {
        public function __construct() {
            parent::__construct(__CLASS__);
            $this->loadModel(__CLASS__);
        }

        public function newsubscribe() {
            $p = $_POST;
            $this->model->uploadNewSubscribe($p);
        }
    }
