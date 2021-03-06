<?php

namespace Blog\Response;

use Blog\Response;

class HtmlResponse implements Response
{
    private $body;

    private $headers = [];

    const STANDARD_HEADERS = [
        'Content-Type' => 'text/html'
    ];

    public function getStatus()
    {
        return 200;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * HtmlResponse constructor.
     * @param $html
     * @param array $headers
     */
    public function __construct($html, array $headers = [])
    {

// TODO - we could lock down the javascript and other resources that can be run on
// a site, via a CSP header, like one of the following.
//
// Content-Security-Policy: default-src 'self'; ...; report-uri /my_amazing_csp_report_parser;
// Content-Security-Policy: script-src 'unsafe-inline';
// Content-Security-Policy-Report-Only: default-src 'self'; ...; report-uri /my_amazing_csp_report_parser;
// Content-Security-Policy: default-src 'none'; script-src 'self' 'unsafe-inline' www.google-analytics.com; img-src www.google-analytics.com;

    // Cache-Control: must-revalidate
    // Cache-Control: no-cache
    // Cache-Control: no-store
    // Cache-Control: no-transform
    // Cache-Control: public
    // Cache-Control: private
    // Cache-Control: proxy-revalidate
    // Cache-Control: max-age=<seconds>
    // Cache-Control: s-maxage=<seconds>
        $this->headers = array_merge(self::STANDARD_HEADERS, $headers);
        $this->body = $html;
    }

    public function getBody()
    {
        return $this->body;
    }
}
