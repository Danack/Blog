<?php

namespace Intahwebz\Response;

use Jig\ViewModel;

interface RedirectResponseFactory {
    /**
     * @param $templateFilename
     * @return mixed
     */
    function create($url, $flashMessage = null, $delay = 0);
}
 