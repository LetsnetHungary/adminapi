<?

    namespace CoreApp;

        class App {

            protected $route;
            protected $closure_to_call;

            public function __construct() {
                $this->route = new Route();
                $this->closure_to_call = $this->route->info["closure"];
                $this->routing();
            }

            private function routing() {
                include "CoreApp/RoutingClosures.php";
                include AppConfig::getData("routingClosuresF");

                $closure_to_call = $this->closure_to_call;
                $$closure_to_call($this->route->info);

                CoreApp\Session::init();

            }

        }
