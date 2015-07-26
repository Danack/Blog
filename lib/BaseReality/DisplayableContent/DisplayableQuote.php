<?php


namespace BaseReality\DisplayableContent;

use Intahwebz\Route;
use BaseReality\Content\Quote;


class DisplayableQuote implements \Intahwebz\DisplayableContent {

    /**
     * @var \BaseReality\Content\Quote
     */
    public $quote;

    /**
     * @var \Intahwebz\Route
     */
    public $route;

    /**
     * @var \Intahwebz\Domain
     */
    public $domain;

    function __construct() { }
    
    static function fromQuote(Quote $image, Route $route, \Intahwebz\Domain $domain) {
        $instance = new self();
        $instance->quote = $image;
        $instance->route = $route;
        $instance->domain = $domain;

        return $instance;
    }

    function getContentID() {
        return $this->quote->getContentID();
    }

    function getDOMID() {
        return $this->quote->getDOMID();
    }


    function display() {
        $url = $this->getContentURL();
        return $this->quote->render(true, $url);
    }

    function displayPreview() {
        $url = $this->getContentURL();
        return $this->quote->render(false, $url);
    }

    function displayThumbnail() {
        $url = $this->getContentURL();
        return $this->quote->render(false, $url);
    }

    function getDisplayableVersion() {
        return $this->quote;
    }

    function getContentURL(){
        $params = [
            'quoteID' => $this->quote->quoteID,
        ];

        return $this->route->generateURL(
            $params,
            $this->domain
        );
    }
}




 