<?php


namespace Intahwebz\Response;


class TextResponse extends SendableResponse implements \Intahwebz\Response\Response {

    private $text;

    function __construct($text) {
        $this->text = $text;
    }

    function process(\Intahwebz\Request $request) {
        $headers[] = $this->createStatusHeader($request, 200);

        $this->sendHeaders($headers);
        echo $this->text;
    }
}

