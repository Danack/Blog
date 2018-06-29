<?php

declare(strict_types=1);

namespace Blog\Route;

use FastRoute\RouteCollector;

interface Routes
{
    public function getRoutes();
    public function addRoutesToCollector(RouteCollector $routeCollector);
}
