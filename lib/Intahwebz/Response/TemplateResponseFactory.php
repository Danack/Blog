<?php

namespace Intahwebz\Response;

use Jig\ViewModel;

interface TemplateResponseFactory {
    /**
     * @param $templateFilename
     * @return mixed
     */
    function create($templateFilename, ViewModel $viewModel = null);
}
 