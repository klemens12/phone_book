<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\RouterController;
use App\Helpers\SessionHelper;

$router = new RouterController();

$router->addRoute('/', 'App\Controllers\IndexController@run');
$router->addRoute('/login', 'App\Controllers\IndexController@loginAction');
$router->addRoute('/logout', 'App\Controllers\IndexController@logoutAction');
$router->addRoute('/registration', 'App\Controllers\IndexController@registration');
$router->addRoute('/register', 'App\Controllers\IndexController@registerAction');
$router->addRoute('/createItem', 'App\Controllers\PhoneBooksController@createAction');
$router->addRoute('/removeItem', 'App\Controllers\PhoneBooksController@deleteAction');

$uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

try{
    $route = $router->route($uri);
    list($controller, $action) = explode('@', $route);
    
    $controllerInstance = new $controller();
    $controllerInstance->$action();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
