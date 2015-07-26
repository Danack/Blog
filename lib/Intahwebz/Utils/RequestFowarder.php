<?php


namespace Intahwebz\Utils;

use Intahwebz\ViewModel;
use Intahwebz\Router;
use Intahwebz\Session;

use BaseReality\Content\BaseRealityConstant;


class RequestFowarder {

    private $router;

    /**
     * @var ViewModel
     */
    private $viewModel = null;

    /**
     * @var Session
     */
    private $session = null;

    public function __construct(
        Session $session,
        Router $router,
        ViewModel $viewModel) {

        $this->session = $session;
        $this->router = $router;
        $this->viewModel = $viewModel;
    }

    /**
     * Redirect (302) a request
     * @param $message
     * @param $route
     * @param array $params
     */
    function forward($message, $route, $params = array()) {
        $this->viewModel->addStatusMessage($message);
        $this->session->setSessionVariable(BaseRealityConstant::$FlashMessage, $this->viewModel->getStatusMessages());

        $location = $this->router->generateURLForRoute($route, $params);

        header("Location: ".$location);
        exit(0);
    }
}

 