<?

    $static = function($info) {
       $view = new CoreApp\View($info["view"]);
       return $view->render();
    };

    $mvc = function ($info) {

        $method = 0;
        $args = [];

        $rURI = explode("/", $info["requestedURI"]);
        $c_rURI = count($rURI);
        $maNum = count(explode("/", $info["href"]));

        $routingOrder = $info["order"];

        $k = 0;

        while($maNum < $c_rURI) {
            if($routingOrder[$k] == "{method}") {
                $method = $rURI[$maNum];
            }
            else if($routingOrder[$k] == "{argument}") {
                array_push($args, $rURI[$maNum]);
            }
            $k++;
            $maNum++;
        }

        $controllerFile = "App/Controllers/".$info['controller'].".php";
        if(file_exists($controllerFile)) require $controllerFile;

        $controller = new $info["controller"]($info);

       // print_r($controller);

        if($method) {
            if(method_exists($controller, $method)) {
				call_user_func(array($controller, $method), $args);
				return;
			}
        }
    };