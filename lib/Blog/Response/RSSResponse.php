<?php

namespace Blog\Response;

use Blog\Response;

class RSSResponse implements Response
{
    private $body;

    private $headers = [];

    public function getStatus()
    {
        return 200;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * XMLResponse constructor.
     * @param $xml
     * @param array $headers
     */
    public function __construct($xml, array $headers = [])
    {
        $standardHeaders = [
            'Content-Type' => 'application/rss+xml; charset=utf-8'
        ];

        $this->headers = array_merge($standardHeaders, $headers);
        $this->body = $xml;
    }

    public function getBody()
    {
        return $this->body;
    }
}
