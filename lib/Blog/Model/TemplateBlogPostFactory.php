<?php


namespace Blog\Model;

use Blog\Content\BlogPost;
use Intahwebz\ObjectCache;
use Jig\Jig;
use Auryn\Injector;
use Blog\Service\SourceFileFetcher;
use Zend\Escaper\Escaper;

class TemplateBlogPostFactory
{
    private $objectCache;
    private $jig;
    private $injector;
    private $sourceFileFetcher;

    public function __construct(
        ObjectCache $objectCache,
        Jig $jig,
        Injector $injector,
        SourceFileFetcher $sourceFileFetcher,
        Escaper $escaper
    ) {
        $this->objectCache = $objectCache;
        $this->jig = $jig;
        $this->injector = $injector;
        $this->sourceFileFetcher = $sourceFileFetcher;
        $this->escaper = $escaper;
    }

    public function create(BlogPost $blogPost)
    {
        return new TemplateBlogPost(
            $blogPost,
            $this->objectCache,
            $this->jig,
            $this->injector,
            $this->escaper
        );
    }
}
