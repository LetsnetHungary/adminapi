<?

    namespace CoreApp;

    class Router {

        public $getroutes;
        public $postroutes;
        public $deleteroutes;
        public $putroutes;

        public function __construct() {
            $this->getroutes = [];
            $this->postroutes = [];
            $this->putroutes = [];
            $this->deleteroutes = [];
        }

        public function get($uri, $authReq, $callback) {
            if($authReq) $this->checkAuth();
            $rA = [];
            $rA['href'] = $uri;
            $rA['callback'] = $callback;
            array_push($this->getroutes, $rA);
        }
        public function post($uri, $authReq, $callback) {
            if($authReq) $this->checkAuth();
            $rA = [];
            $rA['href'] = $uri;
            $rA['callback'] = $callback;
            array_push($this->postroutes, $rA);
        }
        public function delete($uri, $authReq, $callback) {
          if($authReq) $this->checkAuth();
            $rA = [];
            $rA['href'] = $uri;
            $rA['callback'] = $callback;
            array_push($this->deleteroutes, $rA);
        }
        public function put($uri, $authReq, $callback) {
          if($authReq) $this->checkAuth();
            $rA = [];
            $rA['href'] = $uri;
            $rA['callback'] = $callback;
            array_push($this->putroutes, $rA);
        }

        private function checkAuth(){
          $authObject = new Authenticate();
          if(!$authObject->checkAuth($_POST)) die("You don't have access to this content");
        }
    }
