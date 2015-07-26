<?php


namespace BaseReality\DisplayableContent;


class RoutedImage {

    /** @var  \Intahwebz\ContentWithThumbNail */
    public $content;

    public $proxyURL;

    public $thumbURL;
    
    function __construct($content, $proxyURL, $thumbURL) {
        $this->content = $content;
        $this->proxyURL = $proxyURL;
        $this->thumbURL = $thumbURL;
    }

    function displayPreview() {
        return $this->content->render(false, $this->proxyURL, $this->thumbURL);
    }

    function displayThumbnail() {
        return $this->content->render(true, $this->proxyURL, $this->thumbURL);
    }

    function getContentID() {
        return $this->content->getContentID();
    }
}
