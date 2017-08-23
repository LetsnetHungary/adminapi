<?

    namespace CoreApp;

        class App {

            private $routes;
            public function __construct() {
                $uri = rtrim($_GET["url"], '/');
                $uri = isset($_GET["url"]) ? explode("/", $uri) : [];
                $this->getController($uri);
            }

            private function loop($uri, $routes) {
              $return_array = array();
                $c_uri = count($uri);
                for($i = 0; $i <= $c_uri; $i++) {
                    array_push($return_array, array());
                    $c_routes = count($routes);
                    for($k = 0; $k < $c_routes; $k++) {
                        if($c_uri != count($routes[$k]) || ($routes[$k][$i] != $uri[$i] && strpos($routes[$k][$i], ":") != 1 )) {
                            unset($routes[$k]);
                        }
                        else {
                          array_push($return_array[$i], $k);
                        }
                    }
                    $routes = array_values($routes);
                }
                if(empty($routes)) {
                  return "HTTPError";
                }

                $return_array["function"] = $this->prepareReturnArray($return_array);
                for ($j=0; $j < $i - 1; $j++) {
                  if (strpos($routes[0][$j], ":") == 1) {
                    $return_array["parameters"][substr($routes[0][$j], 2, strlen($routes[0][$j]) - 3)] = $uri[$j];
                  }
                }

                return($return_array);
            }

            private function prepareReturnArray($array){
              $c_a = count($array);
              $index = 0;
              for ($i = $c_a - 1; $i >= 0 ; $i--) {
                $index = $array[$i][$index];
              }
              return $index;
            }
            private function getController($uri) {
                $href = $uri[0];

                $controllers = scandir("App/Controllers");
                $return = false;
                foreach($controllers as $controllerFile) {
                    if($controllerFile ==  $href . ".php"){
                        $return = $href;
                    }
                }
                if (!$return) {
                  require("App/Controllers/HTTPError.php");
                  return;
                }
                else {
                   require("App/Controllers/$href.php");
                }
                unset($uri[0]);
                $uri = array_values($uri);
                $this->routing($uri, $this->selectMethod($router));
            }
            private function routing($uri, $r)
            {
              $routes = [];
              foreach($r as $route) {
                  array_push($routes, explode("/", $route));
              }

              $route = $this->loop($uri, $routes);
              if ($route != "HTTPError") {
                $this->routes[$route["function"]]["callback"]($route['parameters']);
              }
              else{
                require("App/Controllers/HTTPError.php");
              }

            }

            private function selectMethod($r)
            {
              switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                  $this->routes = $r->getroutes;
                  return array_column($r->getroutes, "href");
                case 'POST':
                  $this->routes = $r->postroutes;
                  return array_column($r->postroutes, "href");
                case 'DELETE':
                  $this->routes = $r->deleteroutes;
                  return array_column($r->deleteroutes, "href");
                case 'PUT':
                  $this->routes = $r->putroutes;
                  return array_column($r->putroutes, "href");
                default:
                  return;
              }
            }


        }
