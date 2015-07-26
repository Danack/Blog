<?php


namespace Intahwebz\Response;

use Intahwebz\Router;
use Intahwebz\Session;



class RedirectResponse extends SendableResponse implements Response {

    /**
     * @var
     */
    private $url;

    /**
     * @var int
     */
    private $delay;

    
    function __construct($url, $delay = 0) {
        $this->url = $url;
        $this->delay = $delay;
    }

    function process(\Intahwebz\Request $request) {
        if ($this->delay) {
            usleep($this->delay);
        }

        $headers[] = $this->createStatusHeader($request, 307);
        $headers["Location"] = $this->url;
        $this->sendHeaders($headers);
    }
}




 