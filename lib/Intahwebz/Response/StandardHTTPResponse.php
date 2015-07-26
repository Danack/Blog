<?php


namespace Intahwebz\Response;

use Intahwebz\Request;

class StandardHTTPResponse extends SendableResponse implements \Intahwebz\Response\Response {

    private $httpCode;
    private $uri;
    
    function __construct($httpCode, $uri) {
        $this->httpCode = $httpCode;
        $this->uri = $uri;
    }
    //            header("HTTP/1.0 404 Not Found", true, 404);
//            echo "No route matched. No route matched.No route matched.No route matched.No route matched.No route matched.No route matched.No route matched.";
//            break;


    function process(Request $request) {
        $headers[] = $this->createStatusHeader($request, 200);

        $this->sendHeaders($headers);

        echo $body;
    }
}

 