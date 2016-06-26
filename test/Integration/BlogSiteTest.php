<?php

use Behat\Mink\Session;

/**
 * @group integration
 */
class BlogSiteTest extends \PHPUnit_Framework_TestCase
{
    private $driver;

    /**
     * @var \Behat\Mink\Session
     */
    private $session;
    
    public function setup()
    {
        if (!ini_get('allow_url_fopen')) {
            $this->markTestSkipped("allow_url_fopen is not open");
            return;
        }
        
        $this->driver = new \Behat\Mink\Driver\GoutteDriver();
        $this->session = new \Behat\Mink\Session($this->driver);
        $this->session->start();  // start session:
    }
    
    public function teardown()
    {
    }
    
    private function visit($uri)
    {
        try {
            $siteURL = "http://blog.basereality.test".$uri;
            $this->session->visit($siteURL);  // open some page in browser:
        }
        catch (\Exception $e) {
            $this->fail("Exception visiting URI $uri : ".$e->getMessage());
        }
    }

    public function routeListProvider()
    {
        return [
            ["/css/{cssInclude}"],
            ['/js/{jsInclude}'],
            ['/rss'],
            ['/blog/{blogPostID:\d+}[/{title:[^\./]+}{separator:\.?}{format:\w+}]'],
            ['/staticFile/{filename:\w+}'],
            ['/staticImage/{filename:[^/]+}[/{size:\w+}]'],
            ['/templateViewer'],
            ['/'],
        ];
    }

    public function testIndexBasic()
    {
        $this->visit("/");  // open some page in browser:
        $this->assertEquals(200, $this->session->getStatusCode());
    }
    
    /**
     * @group integration
     */
    public function testJSBasic()
    {
        $this->visit("/js/blog");  // open some page in browser:
        $this->assertEquals(200, $this->session->getStatusCode());
    }
}
