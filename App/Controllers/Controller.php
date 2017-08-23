<?

    $router = new CoreApp\Router();

    $router->post("Authenticate", FALSE, function() {

        $authObject = new CoreApp\Authenticate();
        $authObject->setSession($_POST);

    });
    $router->post("LogOut", FALSE, function() {
        $authObject = new CoreApp\Authenticate();
        $authObject->logOut($_POST);
    });
