<?php

namespace Intahwebz\Response\Response;


use Jig\Jig;
use Intahwebz\Response\TemplateResponse;
use Jig\ViewModel;
use Jig\ViewModel\BasicViewModel;

class TemplateResponseFactory implements \Intahwebz\Response\TemplateResponseFactory {

    /**
     * @var Jig
     */
    private $jig;

    /**
     * @var ViewModel
     */
    private $viewModel;

    function __construct(Jig $jig, ViewModel $viewModel) {
        $this->jig = $jig;
        $this->viewModel = $viewModel;
    }

    function create($templateFilename, ViewModel $viewModel = null) {
        if ($viewModel == null) {
            $viewModel = $this->viewModel;
        }

        return new TemplateResponse($this->jig, $templateFilename, $viewModel);
    }
}
 