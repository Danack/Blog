<?php

use Behat\Mink\Session;

/**
 * @group aws
 */
class AWSTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Auryn\Injector
     */
    private $injector;

    public function setup()
    {
        $this->injector = createTestInjector();
    }
    
    public function teardown()
    {
    }
    
    public function testAWSStorage()
    {
        $storage = $this->injector->make('Intahwebz\Storage\S3Storage');
        $storage->getS3RegionOfBucket('static.basereality.com');
    }
}
