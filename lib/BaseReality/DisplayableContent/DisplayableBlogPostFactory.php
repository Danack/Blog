<?php


namespace BaseReality\DisplayableContent;

use BaseReality\Content\BlogPost;
use Jig\JigRender;
use Intahwebz\ObjectCache;

class DisplayableBlogPostFactory {

    private $route;
    private $domain;
    private $objectCache;
    private $jigRender;
    
    function __construct(
        \Intahwebz\Domain $domain, 
        \Intahwebz\Router $router,
        JigRender $jigRender,
        ObjectCache $objectCache
    ) {
        //TODO - YAY - GLOBAL VARIABLES
        $this->route = $router->getRoute('blogPost_Get');
        $this->jigRender = $jigRender;
        $this->domain = $domain;
        $this->objectCache = $objectCache;
    }

    function create(BlogPost $blogPost) {
        return new DisplayableBlogPost($blogPost, $this->route, $this->domain, $this->jigRender, $this->objectCache);
    }

}

 