<?php

namespace Blog\Route;

use FastRoute\RouteCollector;

class BlogRoutes implements Routes
{
    public function addRoutesToCollector(RouteCollector $routeCollector)
    {
        $routes = $this->getRoutes();

//        var_dump($routes);
//        exit(0);

        foreach ($routes as $routeInfo) {
            //echo $routeInfo[1] . " " . $routeInfo[0] . " " . $routeInfo[2] . "<br/>";
            $routeCollector->addRoute($routeInfo[1], $routeInfo[0], $routeInfo[2]);
        }

        //exit(0);
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
        ['/rss',  'GET', 'Blog\Controller\BlogRSS::rssFeed'],
//        [
//            '/blog/{blogPostID:\d+}[/{title:[^\./]+}{separator:\.?}{format:\w+}]',
//            'GET',
//            'Blog\Controller\Blog::display'
//        ],
        [
            '/blog/{blogPostID:\d+}/{title}',
            'GET',
            'Blog\Controller\Blog::display'
        ],

        [
            '/staticImage/{filename:[^/]+}[/{size:\w+}]',
            'GET',
            'Blog\Controller\ProxyController::staticImage'
        ],

        //['GET', '/templateViewer', ['Blog\Controller\TemplateViewer', 'index']],
        //['POST', '/templateViewer', ['Blog\Controller\TemplateViewer', 'displayTemplate']],

        // ['/login', 'GET', ['Blog\Controller\Login', 'loginGet']],
        // ['POST', '/login', ['Blog\Controller\Login', 'loginPost']],
        //['GET', '/logout', ['Blog\Controller\Login', 'logout']],

        // ['GET', '/draft/{filename:\w+}', ['Blog\Controller\Blog', 'showDraft']],
        // ['GET', '/drafts', ['Blog\Controller\Blog', 'showDrafts']],

        // ['GET', '/upload', ['Blog\Controller\BlogUpload', 'showUpload']],
        // ['POST', '/upload', ['Blog\Controller\BlogUpload', 'uploadPost']],

        //['GET', '/uploadFile', ['Blog\Controller\FileUpload', 'showUpload']],
        //['POST', '/uploadFile', ['Blog\Controller\FileUpload', 'uploadPost']);

        //['GET', '/listFiles', ['Blog\Controller\FileUpload', 'listFiles']);


        //['GET', '/uploadResult', ['Blog\Controller\BlogUpload', 'uploadResult']);
        //['GET', '/blogreplace/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'showReplace']);
        //['POST', '/blogreplace/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'processReplace']);
        //['GET', '/staticFile/{filename:[^/]+}', ['Blog\Controller\Proxy', 'staticFile']);

        //['GET', '/perfTest', ['Blog\Controller\Blog', 'perfTest']);
        // ['GET', '/sourceFile/{filename:.+}', ['Blog\Controller\FileUpload', 'showFile']);
        // ['GET', '/listFiles', ['Blog\Controller\FileUpload', 'listFiles']);
        ['/', 'GET', 'Blog\Controller\Blog::index'],

        ];

        return $routes;
    }
}
