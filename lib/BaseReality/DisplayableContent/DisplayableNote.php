<?php


namespace BaseReality\DisplayableContent;

use Intahwebz\Route;
use BaseReality\Content\Note;


class DisplayableNote implements \Intahwebz\DisplayableContent {

    /**
     * @var \BaseReality\Content\Note
     */
    public $note;

    /**
     * @var \Intahwebz\Route
     */
    public $route;

    /**
     * @var \Intahwebz\Domain
     */
    public $domain;

    
    public static function fromNote(Note $image, Route $route, \Intahwebz\Domain $domain) {
        $instance = new self();
        $instance->note = $image;
        $instance->route = $route;
        $instance->domain = $domain;

        return $instance;
    }

    function getContentID() {
        return $this->note->getContentID();
    }

    function getDOMID() {
        return $this->note->getDOMID();
    }


    function display() {
        $url = $this->getContentURL();
        return $this->note->render(true, $url);
    }

    function displayPreview() {
        $url = $this->getContentURL();
        return $this->note->render(false, $url);
    }

    function displayThumbnail() {
        $url = $this->getContentURL();
        return $this->note->renderThumbnail($url);
    }

    function getDisplayableVersion() {
        return $this->note;
    }

    function getContentURL(){
        $params = [
            'noteID' => $this->note->noteID,
        ];

        return $this->route->generateURL(
            $params,
            $this->domain
        );
    }
}




 