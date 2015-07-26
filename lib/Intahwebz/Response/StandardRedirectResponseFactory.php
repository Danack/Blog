<?php

namespace Intahwebz\Response;


use Jig\JigRender;
use Jig\ViewModel;

class StandardRedirectResponseFactory implements RedirectResponseFactory {

    /**
     * @var JigRender
     */
    private $jigRender;

    function __construct(JigRender $jigRender) {
        $this->jigRender = $jigRender;
    }

    function create($url, $delay = 0) {
        return new RedirectResponse($url, $delay);
    }
}

