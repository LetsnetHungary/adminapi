<?php

    class debugapi_Model extends CoreApp\DataModel {
        public function __construct() {
            parent::__construct();
        }
        public function apidebug($a)
        {
            print_r($a);
            die();
        }
    }
