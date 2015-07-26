<?php


namespace Intahwebz\Response;

use Jig\Jig;
use Jig\ViewModel;

use Intahwebz\Request;

class TemplateResponse extends SendableResponse implements \Intahwebz\Response\Response {

    /**
     * @var Jig
     */
    private $jig;

    /**
     * @var String
     */
    private $templateFilename;

    /**
     * @var ViewModel
     */
    private $viewModel;

    /**
     * @param Jig $jigRender
     * @param $templateFilename
     * @param ViewModel $viewModel
     */
    function __construct(Jig $jigRender, $templateFilename, ViewModel $viewModel = null) {
        $this->jig = $jigRender;
        $this->templateFilename = $templateFilename;
        $this->viewModel = $viewModel;
    }

    /**
     * @throws \Jig\JigException
     */
    function process(Request $request) {

        $headers[] = $this->createStatusHeader($request, 200); 

        $text = $this->jig->renderTemplateFile(
            $this->templateFilename, 
            $this->viewModel
        );

        $this->sendHeaders($headers);
        echo $text;
    }
}

