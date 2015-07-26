<?php
namespace BaseReality\Content;

use BaseReality\DTO\ContentDTO;
use BaseReality\DTO\FileDTO;



class File implements \Intahwebz\ContentName, \Storable {

    public $contentID;
    public $datestamp;

    public $fileID;
    public $name;
    public $description;
    public $storageType;

    public $typeName = 'File';

    const  STORAGE_FOLDER = 'files';

    function __construct(FileDTO $fileDTO = null, ContentDTO $contentDTO = null) {
        if ($fileDTO != null) {
            $this->fileID       = $fileDTO->fileID;
            $this->name         = $fileDTO->name;
            $this->description  = $fileDTO->description;
            $this->storageType  = $fileDTO->storageType;
        }
        if ($contentDTO != null) {
            $this->contentID = $contentDTO->contentID;
            $this->datestamp = $contentDTO->datestamp;
        }
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
    
    function getContentURL() {
        return "/file/".$this->fileID."/".$this->name;
    }

    function getName() {
        return $this->name;
    }

    function getID(){
        return $this->fileID;
    }

    function getContentID(){
        return $this->contentID;
    }

    function getDOMID(){
        return $this->typeName."_".$this->fileID;//."_".$this->uniqueID;
    }

    function	setID($contentID, $fileID){
        $this->contentID = $contentID;
        $this->fileID = $fileID;
    }

    function render($asContentObject, $url){
        $output = "<a href='".$url."' target='_blank'  >";

        if($asContentObject == true){
            $DOMID = $this->getDOMID();
            $output .= "<span class='content' id='".$DOMID."' >";
        }

        if($this->description != null){
            $output .= $this->description;
        }
        else{
            $output .= "".$this->name;
        }

        if($asContentObject == true){
            $output .= "</span>";
        }
        $output .= "</a>";
        return $output;
    }

    function getFilename() {
        return $this->name;
    }
}



