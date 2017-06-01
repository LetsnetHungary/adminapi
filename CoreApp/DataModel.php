<?php

namespace CoreApp;

  abstract class DataModel extends Model {

    protected $database;
    protected $PDO;
    protected $curl;
    protected $queryBuilder;
    protected $log;
    protected $response;

    public function __construct() {
      $this->database = new Database();
      $this->queryBuilder = new QueryBuilder();
      $this->log = array();
      $this->response = new Response();
    }

  }
