<?

    $router = new CoreApp\Router();

        $router->post("A/asdf", TRUE, function() {
          die("siker");
        });

        $router->post("A/asdfg", TRUE, function() {
            $view = new CoreApp\View("Index");
            $view->render();
        });

        $router->get("A/(:param)", TRUE, function($parameters) {
            $view = new CoreApp\View("Index");
            $view->parameters = $parameters;
            $view->render();
        });
        $router->put("B/(:p)", TRUE, function($parameters) {
            $view = new CoreApp\View("Index");
            $view->render();
        });
        $router->put("(:param)/(:p)",TRUE, function($parameters) {
            $view = new CoreApp\View("Index");
            $view->render();
        });
