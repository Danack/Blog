<?php


namespace BaseReality\DisplayableContent;

use Intahwebz\Route;
use BaseReality\Content\File;


class DisplayableFile implements \Intahwebz\DisplayableContent {

    /**
     * @var \BaseReality\Content\File
     */
    public $file;

    /**
     * @var \Intahwebz\Route
     */
    public $route;

    /**
     * @var \Intahwebz\Domain
     */
    public $domain;
    
    public static function fromFile(File $file, Route $route, \Intahwebz\Domain $domain) {
        $instance = new self();
        $instance->file = $file;
        $instance->route = $route;
        $instance->domain = $domain;
        
        return $instance;
    }

    function getContentID() {
        return $this->file->getContentID();
    }

    function getDOMID() {
        return $this->file->getDOMID();
    }

    function display(){
        $url = $this->getContentURL();
        return $this->file->render(true, $url);
    }
    function displayThumbnail() {
        $url = $this->getContentURL();
        return $this->file->render(false, $url);
    }

    function displayPreview(){
        $url = $this->getContentURL();
        return $this->file->render(false, $url);
    }

    function getDisplayableVersion() {
        return $this->file;
    }


    function getContentURL(/** @noinspection PhpUnusedParameterInspection */
        $size = false){
        $params = [
            'fileID' => $this->file->fileID,
            'filename' => $this->file->getFilename()
        ];
        
        return $this->route->generateURL(
            $params,
            $this->domain
        );
    }
}




 