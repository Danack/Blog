<?php

namespace BaseReality\Content;

//use Intahwebz\Routable;
use Intahwebz\Cache\KeyName;
use Intahwebz\ContentName;

class BlogPost implements ContentName
{
    use     KeyName;
    
    public $contentID;
    public $datestamp;
           
    public $blogPostID = null;
    public $title;
    public $isActive;
    public $blogPostTextID;
    public $blogPostText;
           
    public $typeName = 'BlogPost';

    public function getID()
    {
        return $this->blogPostID;
    }

    public function getDOMID()
    {
        return $this->typeName."_".$this->blogPostID;
    }

    public function getContentID()
    {
        return $this->contentID;
    }

    public function setID($contentID, $blogPostID)
    {
        $this->contentID = $contentID;
        $this->blogPostID = $blogPostID;
    }

    public function getContentURL()
    {
        return "/blog/".$this->blogPostID;
    }

    public function display()
    {
        $blogPostText = str_replace("\n", "&nbsp;<br/>", $this->blogPostText);
        return $blogPostText;
    }

    public function displayDate($includeYear = false)
    {
        $dateFormat = 'jS M';

        if ($includeYear == true) {
            $dateFormat = $dateFormat." Y";
        }

        return formatDateString($this->datestamp, $dateFormat);
    }

    public function displayPreview()
    {
        return $this->renderThumbnail(false);
    }

    public function displayThumbnail()
    {
        return $this->renderThumbnail(true);
    }

    public function renderThumbnail($url)
    {
        $output = '';
        $idString = $this->getDOMID();
        //$url = $this->getContentURL();
        $output .= "<a href='$url' class='content' id='$idString'>";
        $output .= $this->title;
        $output .= "</a>";

        return $output;
    }

    public function getCacheKey($name)
    {
        return $this->getClassKey($name.'_'.$this->blogPostID);
    }

    public function getRouteName()
    {
        return "blogPost";
    }

    public function getRouteParams()
    {
        $params = array(
            'blogPostID' => $this->blogPostID,
            'title' => str_replace(" ", "_", $this->title),
        );
        return $params;
    }

    public function renderTitleLink($url = null)
    {
        return sprintf("<a href='%s'>%s</a>", addslashes($url), safeText($this->title));
    }

    public function showTitle()
    {
        if ($this->isActive) {
            return $this->title;
        }
        else {
            return $this->title." (inactive)";
        }
    }
}
