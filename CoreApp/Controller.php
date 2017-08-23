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

            protected function viewInit($viewName) {
                $this->view = new View($viewName);
                $this->view->render($viewName);
            }

        }