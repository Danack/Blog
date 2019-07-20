<?php

namespace Blog\Route;

use FastRoute\RouteCollector;

class ApiRoutes implements Routes
{
    public function addRoutesToCollector(RouteCollector $routeCollector)
    {
        $routes = $this->getRoutes();
        foreach ($routes as $routeInfo) {
            //echo $routeInfo[1] . " " . $routeInfo[0] . " " . $routeInfo[2] . "<br/>";
            $routeCollector->addRoute($routeInfo[1], $routeInfo[0], $routeInfo[2]);
        }
    }

    public function doesRouteRequireApiKey($requestPath, $requestMethod)
    {
        foreach ($this->getRoutes() as $route) {
            $routePath = $route[0];
            $routeMethod = $route[1];

            if ($requestPath === $routePath) {
                if ($requestMethod === $routeMethod) {
                    return $route[3];
                }
            }
        }

        throw new \Exception("Path [$requestPath] is not found, cannot determine if needs api key.");
    }

    public function getRoutes()
    {

        // Each row of this array should return an array of:
        // - The path to match
        // - The method to match
        // - The route info
        //
        // If the route info is a string callable, it will be invoked.
        // If the route info is an array, the first element will be the callable for the controller. The
        // subsequent elements should be 'setup' callables that will be invoked before the controller is run
        // and before the middleware elements are run.
        //
        // This allows use to configure data per endpoint e.g. the endpoints that should be secured by
        // and api key, should call an appropriate callable.
        $routes = [

            ['/github/notification', 'POST', '\BaseReality\ApiController\Github::pushNotification'],
            ['/github/notification_fake', 'GET', '\BaseReality\ApiController\Github::pushNotificationFake'],

            ['/github/build_complete', 'GET', 'BaseReality\ApiController\Github::buildComplete'],



            ['/test/caught_exception', 'GET', 'Osf\ApiController\Debug::testCaughtException'],
            ['/test/uncaught_exception', 'GET', 'Osf\ApiController\Debug::testUncaughtException'],

            ['/', 'GET', 'BaseReality\ApiController\HealthCheck::get'],

        ];

        return $routes;
    }
}
