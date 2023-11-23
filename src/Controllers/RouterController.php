<?php
namespace App\Controllers;

class RouterController {
    private $routes = [];

    /**
     * Create new route and action.
     * 
     * @param type string $url
     * @param string $controllerAction
     */
    public function addRoute($url, $controllerAction) 
    {
        $this->routes[$url] = $controllerAction;
    }

    /**
     * Load route by url.
     * 
     * @param string $url
     * @return string
     */
    public function route($url) 
    {
        
        if (array_key_exists($url, $this->routes)) {
            return $this->routes[$url];
        } 
    }
}

