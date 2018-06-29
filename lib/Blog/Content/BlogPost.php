<?php

namespace Blog\Content;

// use Intahwebz\Cache\KeyName;

class BlogPost
{
    // use KeyName;

    public $datestamp;
    public $blogPostID;
    public $title;
    public $isActive;
    public $blogPostTextID;
    public $text;
    public $blogPostText;


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

//    public function getCacheKey($name)
//    {
//        return $this->getClassKey($name.'_'.$this->blogPostID);
//    }
}
