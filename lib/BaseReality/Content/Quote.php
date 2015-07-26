<?php

namespace BaseReality\Content;


class Quote implements \Intahwebz\ContentName {

    public $contentID;
    public $quoteID;
    public $text;
    public $author;

    public $typeName = 'Quote';

    static function fromRaw($contentID, $quoteID, $text, $author) {
        $quote = new Quote();
        $quote->contentID = $contentID;
        $quote->quoteID = $quoteID;
        $quote->text = $text;
        $quote->author = $author;

        return $quote;
    }

    function getID(){
        return $this->quoteID;
    }

    function getContentID(){
        return $this->contentID;
    }

    function getDOMID(){
        return $this->typeName."_".$this->quoteID;//"_".$this->uniqueID;
    }

    function setID($contentID, $quoteID) {
        $this->quoteID = $quoteID;
        $this->contentID = $contentID;
    }

    function render($asContent, $url) {
        $output = "";

        if($asContent){
            $idString = $this->getDOMID();
            $output .= "&quot;<i class='content' id='$idString'>";
        }
        else{
            $output .= "&quot;<i>";
        }

        $output .= $this->text;
        $output .= "</i>&quot;&nbsp;-&nbsp;";

        $output .= "<a href='$url'>";
        $output .= $this->author;
        $output .= "</a>";

        return $output;
    }
}

