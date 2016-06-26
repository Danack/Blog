<?php

namespace BlogTest\Contorller;

use BlogTest\BaseTestCase;

class BlogRSTest extends BaseTestCase
{
    /**
     * @var \Auryn\Injector
     */
    private $injector;

    public function setUp()
    {
        parent::setUp();
        $this->injector = createTestInjector();
    }

    public function testRSSFeed()
    {
        $result = $this->injector->execute(['Blog\Controller\BlogRSS', 'rssFeed']);
        //TODO - assertions.
        //TODO - save file to VFS.
    }
}
