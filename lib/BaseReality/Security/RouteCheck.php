<?php


namespace BaseReality\Security;

use Intahwebz\Session;
use Intahwebz\Router;

// TODO - figure out how to do security in a secure non-named way.
class RouteCheck
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var AccessControl
     */
    private $accessControl;

    public function __construct(Session $session, Router $router, AccessControl $accessControl)
    {
        $this->session = $session;
        $this->router = $router;
        $this->accessControl = $accessControl;
    }


    /**
     * @param $routeName
     * @param array $params
     * @return \Intahwebz\Route|null
     */
    public function isRouteAllowed($routeName, $params = array())
    {
        $route = $this->router->getRoute($routeName, $params);
        $userRole = $this->session->getSessionVariable(\Blog\Site\Constant::$userRole);

        $accessRules = $route->get('access');
        $resourceName = null;
        $privilege = null;
        if ($accessRules) {
            $resourceName = $accessRules[0];
            $privilege = $accessRules[1];
        }

        if ($this->accessControl->isAllowed($userRole, $resourceName, $privilege)) {
            return $route;
        }

        return null;
    }
}
