<?php

namespace Blog\Content;

use Intahwebz\Cache\KeyName;

class BlogPost
{
    use KeyName;
    
    //var     $contentID;
    public $datestamp;

    public $blogPostID;
    public $title;
    public     $isActive;
    //public     $blogPostTextID;
    public $text;

    public $blogPostText;
    //public     $typeName = 'BlogPost';

    public static function create($blogPostID, $title, $text, $datestamp, $isActive)
    {
        $instance = new self();
        $instance->blogPostID = $blogPostID;
        $instance->title = $title;
        $instance->text = $text;
        $instance->datestamp = $datestamp;
        $instance->blogPostText = $text;
        $instance->isActive = $isActive;
        
        return $instance;
    }

    /**
     * @return mixed
     */
    public function getDatestamp()
    {
        return $this->datestamp;
    }

    /**
     * @return mixed
     */
    public function getBlogPostID()
    {
        return $this->blogPostID;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

//    function getID(){
//        return $this->blogPostID;
//    }
//    function getDOMID(){
//        return $this->typeName."_".$this->blogPostID;
//    }

//    function getContentID() {
//        return $this->contentID;
//    }
//
//    function setID($contentID, $blogPostID) {
//        $this->contentID = $contentID;
//        $this->blogPostID = $blogPostID;
//    }
//
//    function getContentURL() {
//        return "/blog/".$this->blogPostID;
//    }
//
//    function display() {
//        $blogPostText = str_replace("\n", "&nbsp;<br/>", $this->blogPostText);
//        return $blogPostText;
//    }
//
//    function displayDate($includeYear = false) {
//        $dateFormat = 'jS M';
//
//        if ($includeYear == true) {
//            $dateFormat = $dateFormat." Y";
//        }
//
//        return formatDateString($this->datestamp, $dateFormat);
//    }
//
//    function displayPreview() {
//        return $this->renderThumbnail(false);
//    }
//
//    function displayThumbnail() {
//        return $this->renderThumbnail(true);
//    }
//
//    function renderThumbnail($url) {
//        $output = '';
//        $idString = $this->getDOMID();
//        //$url = $this->getContentURL();
//        $output .= "<a href='$url' class='content' id='$idString'>";
//        $output .= $this->title;
//        $output .= "</a>";
//
//        return $output;
//    }

    public function getCacheKey($name)
    {
        return $this->getClassKey($name.'_'.$this->blogPostID);
    }
//
//    function getRouteName() {
//        return "blogPost";
//    }
//
//    function getRouteParams() {
//        $params = array(
//            'blogPostID' => $this->blogPostID,
//            'title' => str_replace(" ", "_", $this->title),
//        );
//        return $params;
//    }
//
//    function renderTitleLink($url = null) {
//        return sprintf("<a href='%s'>%s</a>", addslashes($url), safeText($this->title));
//    }
//
//    function showTitle() {
//        if ($this->isActive) {
//            return $this->title;
//        }
//        else{
//            return $this->title." (inactive)";
//        }
//    }
}
