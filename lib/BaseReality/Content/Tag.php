<?php

namespace BaseReality\Content;


class Tag implements \Intahwebz\ContentName {

    public $tagID;
    public $contentID;
    public $text;

    public $typeName = 'Tag';

    
    static function fromRaw($tagID, $contentID, $text) {
        $tag = new self();
        $tag->tagID = $tagID;
        $tag->contentID = $contentID;
        $tag->text = $text;

        return $tag;
    }
    
    function display() {
        return $this->text;
    }

    function displayPreview(){
        return $this->text;
    }

    function displayThumbnail(){
        return $this->text;
    }

    function deleteClass() {
        // TODO: Implement deleteClass() method.
    }

    function getID() {
        return $this->tagID;
    }
    function getDOMID(){
        return $this->typeName."_".$this->tagID;
    }

    function getContentID(){
        return $this->contentID;
    }

    function setID($contentID, $typeID) {
        $this->contentID = $contentID;
        $this->tagID = $typeID;
    }
}


