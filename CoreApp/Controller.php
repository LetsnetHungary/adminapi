<?

    namespace CoreApp;

        class Controller {


            public $routeINFO;

            protected $model;
            protected $view;

            public function __construct() {
                $this->view = NULL;
                $this->model = [];
                $this->routeINFO = [];
            }

            protected function loadModel($modelName) {
                $modelName .= '_Model';
                $modelF = "App/Models/".$modelName.".php";
                if(file_exists($modelF)) {
                    require($modelF);
                    $this->model = new $modelName();
                }
                return NULL;
            }

            protected function setAuthentication() {
              $this->authentication = Appconfig::getData("authentication");
              if($this->authentication) {
                  //autchentication on
                  $a = new \CoreApp\Controller\Authentication();
                  return $a;
              }
              return null;
            }

            public function PageModulesPHP($sitekey, $pagemodules) {
              $c_p = count($pagemodules);
              for($i=0; $i < $c_p; $i++) {
                $path = "_cms/$sitekey/modules/php/".$pagemodules[$i]["viewid"]."/".$pagemodules[$i]["module"].".php";
                $this->includePagemodulPHP($path);
              }
            }

            private function includePagemodulPHP($path) {
              include($path);
              return;
            }

        }
