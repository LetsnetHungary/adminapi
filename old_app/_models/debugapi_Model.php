<?php

    class debugapi_Model extends CoreApp\DataModel {
        public function __construct() {
            parent::__construct();
        }
        public function apidebug()
        {
            print_r($_POST);
            die();
        }
    }
