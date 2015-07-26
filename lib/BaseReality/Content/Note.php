<?php

namespace BaseReality\Content;

class Note implements \Intahwebz\ContentName {

    public $noteID = null;
    public $contentID;
    public $title;
    public $text;
    public $typeName = 'Note';

    public static function fromRaw($noteID, $contentID, $title, $text) {
        $note = new Note();
        $note->noteID = $noteID;
        $note->contentID = $contentID;
        $note->title = $title;
        $note->text = $text;
        
        return $note;
    }
    
    function getID() {
        return $this->noteID;
    }

    function getDOMID() {
        return $this->typeName."_".$this->noteID;
    }

    function getContentID() {
        return $this->contentID;
    }

    function setID($contentID, $noteID) {
        $this->contentID = $contentID;
        $this->noteID = $noteID;
    }

    function getContentURL() {
        //TODO - this needs to go through router.
        return "/note/".$this->noteID;
    }
    
    function render() {
        return $this->display();
    }

    function display() {
        $noteText = str_replace("\n", "&nbsp;<br/>", $this->text);
        return $noteText;
    }

    function displayPreview() {
        return $this->renderThumbnail(false);
    }

    function displayThumbnail() {
        return $this->renderThumbnail(true);
    }

    function renderThumbnail($url) {
        $output = '';
        $idString = $this->getDOMID();

        //$url = $this->getContentURL();

        //if($asObject){
            $output .= "<a href='$url' class='content' id='$idString'>";
//        }
//        else{
//            $output .= "<a href='$url'>";
//        }

        $output .= $this->title;
        $output .= "</a>";

        return $output;
    }
}

