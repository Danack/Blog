<?php

namespace BaseReality\Content;

//use Intahwebz\Routable;
use Intahwebz\Cache\KeyName;

class BlogPost implements \Intahwebz\ContentName {

    use     KeyName;
    
    var     $contentID;
    var     $datestamp;

    var     $blogPostID = null;
    var     $title;
    var     $isActive;
    var     $blogPostTextID;
    var     $blogPostText;

    var     $typeName = 'BlogPost';

    function getID() {
        return $this->blogPostID;
    }
    function getDOMID() {
        return $this->typeName."_".$this->blogPostID;
    }

    function getContentID() {
        return $this->contentID;
    }

    function setID($contentID, $blogPostID) {
        $this->contentID = $contentID;
        $this->blogPostID = $blogPostID;
    }

    function getContentURL() {
        return "/blog/".$this->blogPostID;
    }

    function display() {
        $blogPostText = str_replace("\n", "&nbsp;<br/>", $this->blogPostText);
        return $blogPostText;
    }

    function displayDate($includeYear = false) {
        $dateFormat = 'jS M';

        if ($includeYear == true) {
            $dateFormat = $dateFormat." Y";
        }

        return formatDateString($this->datestamp, $dateFormat);
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
        $output .= "<a href='$url' class='content' id='$idString'>";
        $output .= $this->title;
        $output .= "</a>";

        return $output;
    }

    function getCacheKey($name) {
        return $this->getClassKey($name.'_'.$this->blogPostID);
    }

    function getRouteName() {
        return "blogPost";
    }

    function getRouteParams() {
        $params = array(
            'blogPostID' => $this->blogPostID,
            'title' => str_replace(" ", "_", $this->title),
        );
        return $params;
    }

    function renderTitleLink($url = null) {
        return sprintf("<a href='%s'>%s</a>", addslashes($url), safeText($this->title));
    }

    function showTitle() {
        if ($this->isActive) {
            return $this->title;
        }
        else{
            return $this->title." (inactive)";
        }
    }
}
