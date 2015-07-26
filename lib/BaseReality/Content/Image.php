<?php
namespace BaseReality\Content;

use BaseReality\DTO\ContentDTO;
use BaseReality\DTO\ImageDTO;

class Image implements \Intahwebz\ContentName, \Storable {

    var $contentID;
    var $datestamp;

    var $imageID;
    var $name;
    var $storageType;

    var $typeName = 'Image';

    const ROUTE_NAME_WITH_SIZE = 'proxyImageWithSize';

    const ROUTE_NAME_WITHOUT_SIZE = 'proxyImageWithoutSize';

    const STORAGE_FOLDER = 'images';

    function __construct(ImageDTO $imageDTO = null, ContentDTO $contentDTO = null) {
        if ($imageDTO != null) {
            $this->imageID = $imageDTO->imageID;
            $this->name = $imageDTO->name;
            $this->storageType = $imageDTO->storageType;
        }
        if ($contentDTO != null) {
            $this->contentID = $contentDTO->contentID;
            $this->datestamp = $contentDTO->datestamp;
        }
    }

    function getContentID() {
        return $this->contentID;
    }

    function getName() {
        return $this->name;
    }

    function getID() {
        return $this->imageID;
    }

    function getDOMID() {
        return $this->typeName."_".$this->imageID;
    }

    function setID($contentID, $imageID) {
        $this->contentID = $contentID;
        $this->imageID = $imageID;
    }

    function getStorageFolder() {
        return self::STORAGE_FOLDER;
    }

    function getLocalCacheFolder($versionInfo = null) {
        if ($versionInfo == null) {
            $versionInfo = array();
        }
        
        $directory = self::STORAGE_FOLDER;

        ksort($versionInfo);
        
        foreach ($versionInfo as $value) {
            $directory .= '/'.$value;
        }
        
        return $directory;
    }

    //TODO - does this belong in here?
    function getFilename() {
        $filename = $this->name;
        $pathInfo = pathinfo($filename);

        $fileExtension = "";

        if(array_key_exists('extension', $pathInfo) == true){
            $fileExtension = $pathInfo['extension'];
        }

        return "imageContent_".$this->contentID.".".$fileExtension;
    }


    function render($asContentObject, $linkURL, $thumbURL) {
        $DOMID = $this->getDOMID();

        $output = "";

        if($asContentObject == true){
            $output .= "<a href='".$linkURL."' target='_blank' class='clickableLink content' id='$DOMID' >";
        }
        else{
            $output .= "<a href='".$linkURL."' target='_blank' class='' id='$DOMID' >";
        }

        $output .= "<table class='contentImageWrapper' width='128px' height='128px' border='0' cellspacing='0' cellpadding='0px'><tr><td valign='middle'>";


        $output .= "<img src='".$thumbURL."' alt='An image' class='contentImageThumbnail' />";

        $output .= "</td></tr></table>";
        $output .= "</a>";

        return $output;
    }
    
}
