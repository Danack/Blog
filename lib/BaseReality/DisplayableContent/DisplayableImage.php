<?php


namespace BaseReality\DisplayableContent;

use Intahwebz\Route;
use BaseReality\Content\Image;


class DisplayableImage implements \Intahwebz\DisplayableContent {

    /**
     * @var \Intahwebz\ContentWithThumbNail
     */
    public $image;

    /**
     * @var \Intahwebz\Route
     */
    public $route;

    /**
     * @var \Intahwebz\Domain
     */
    public $domain;

    public $baseURL;
    
    function __construct(Image $image, Route $route, \Intahwebz\Domain $domain) {
        $this->image = $image;
        $this->route = $route;
        $this->domain = $domain;
        $this->baseURL = $this->domain->getContentDomain($this->image->getContentID());
    }

    function getContentID() {
        return $this->image->getContentID();
    }

    function getDOMID() {
        return $this->image->getDOMID();
    }

    function display() {
        $linkURL = $this->getContentURL();
        return $this->image->render(false, $linkURL, $linkURL);
    }

    function displayPreview() {
        $linkURL = $this->getContentURL();
        $thumbURL = $this->getContentURL(640);
        
        return $this->image->render(false, $linkURL, $thumbURL);
    }

    function displayThumbnail() {
        $linkURL = $this->getContentURL();
        $thumbURL = $this->getContentURL('thumbnail');
        
        return $this->image->render(true, $linkURL, $thumbURL);
    }

    function getDisplayableVersion() {
        $proxyURL = $this->getContentURL();
        $thumbURL = $this->getContentURL(640);

        return new RoutedImage($this->image, $proxyURL, $thumbURL);
    }

    function getContentURL($size = false){
        $params = [
            'imageID' => $this->image->imageID,
            'filename' => $this->image->name
        ];
        
        if ($size) {
            $params['size'] = $size;
        }

        return $this->baseURL.$this->route->generateURL(
            $params,
            $this->domain
        );
    }
}




 