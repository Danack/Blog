<?php


namespace BaseReality\DisplayableContent;


use BaseReality\Content\Link;


class DisplayableLink implements \Intahwebz\DisplayableContent {

    /**
     * @var \BaseReality\Content\Link
     */
    public $link;

    /**
     * @var \Intahwebz\Route
     */
    public $route;

    public static function fromLink(Link $link) {
        $instance = new self();
        $instance->link = $link;

        return $instance;
    }
    

    function getContentID() {
        return $this->link->getContentID();
    }

    function getDOMID() {
        return $this->link->getDOMID();
    }

    function display() {
        return $this->link->render(true);
    }

    function displayPreview() {
        return $this->link->render(false);
    }

    function displayThumbnail() {
        return $this->link->render(false);
    }

    function getDisplayableVersion() {
        return $this->link;
    }
}




 