<?php

namespace BaseReality\Content;

class Link implements \Intahwebz\ContentName {

    public $linkID;
    public $contentID;
    public $url;
    public $description;

    public $typeName = 'Link';

    /**
     * @param $linkID
     * @param $contentID
     * @param $url
     * @param $description
     * @return Link
     */
    static public function fromRaw($linkID, $contentID, $url, $description) {
        $link = new Link();
        $link->linkID = $linkID;
        $link->contentID = $contentID;
        $link->url = $url;
        $link->description = $description;
        
        return $link;
    }
    
    function getID() {
        return $this->linkID;
    }

    function getContentID() {
        return $this->contentID;
    }

    function getDOMID() {
        return $this->typeName."_".$this->linkID;
    }

    function setID($contentID, $linkID) {
        $this->contentID = $contentID;
        $this->linkID = $linkID;
    }
    
    function render($asObject) {
        $output = "";

        if($asObject == true) {
            $DOMID = $this->getDOMID();
            $output .= "<a href='".$this->url."' alt='' class='content' id='".$DOMID."' target='_blank'>";
        }
        else{
            $output .= "<a href='".$this->url."' alt='' target='_blank'>";
        }

        $output .= safeText($this->description);
        $output .= "</a>";

        return $output;
    }
}


