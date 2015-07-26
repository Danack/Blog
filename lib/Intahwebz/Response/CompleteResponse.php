<?php


namespace Intahwebz\Response;

use Intahwebz\Request;

class CompleteResponse extends SendableResponse implements \Intahwebz\Response\Response {

    private $headers;
    
    private $body;
    
    private $status;

    /**
     * @param $status
     * @param $headers
     * @param $body
     */
    function __construct($status, $headers, $body) {
        $this->status = $status;
    }


    /**
     * @param Request $request
     * @throws \Exception
     */
    function process(Request $request) {
        $statusHeader = $this->createStatusHeader($request, $this->status);
        $headers = array_merge([$statusHeader], $this->headers);
        $this->sendHeaders($headers);
        echo $this->body;
    }
}

 