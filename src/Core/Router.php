<?php

namespace Core;

use Core\App;

class Router
{
    public static array $params = [];
    public static string $route;

    public function __construct(
        private App $app
    ) {}

    public function route() : void
    {
        self::splitRoute();

        switch (self::$route) {
            case '/':
                $controller = new \Controllers\Page($this->app);
                $controller->index();
                break;
            default:
                $routeSplit = explode('/', self::$route);
                try {
                    $controllerClass = '\\Controllers\\' . ucfirst($routeSplit[1]);
                    $controller = new $controllerClass($this->app);
                    $controller->{$routeSplit[2]}();
                } catch (\Exception $e) {
                    View::renderError(500, $e->getMessage());
                }
        }
    }

    private function splitRoute() : void
    {
        $route = $_SERVER['REQUEST_URI'];
        $routeParts = explode('?', $route);

        self::$route = $routeParts[0];

        if (isset($routeParts[1])) {
            $paramsRaw = explode('&', $routeParts[1]);
            foreach ($paramsRaw as $paramRaw) {
                $param = explode('=', $paramRaw);
                self::$params[$param[0]] = $param[1];
            }
        }
    }
}