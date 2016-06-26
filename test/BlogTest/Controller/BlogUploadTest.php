<?php

namespace BlogTest\Contorller;

use BlogTest\BaseTestCase;
use Mockery\Mock;


/**
 * Class BlogUpload
 * @package BlogTest\Contorller
 * @group blogupload
 */
class BlogUploadTest extends BaseTestCase
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

    private function createInjectorForLoggedIn()
    {
        $mocks = ['Blog\UserPermissions' => new \Blog\User\LoggedInPermissions('admin')];

        return createTestInjector($mocks);
    }
    
    public function testShowUpload()
    {
        $injector = $this->createInjectorForLoggedIn();
        $uploadForm = $injector->make('Blog\Form\BlogUploadForm');
        $mock = \Mockery::mock($uploadForm)
            ->shouldReceive('validate')
            ->andReturn(true)
            ->mock();
        
        $injector->share('Blog\Form\BlogUploadForm', $mock);
        $result = $injector->execute('Blog\Controller\BlogUpload::showUpload');
        $this->assertInstanceOf('Tier\Executable', $result);
    }
    
    
    public function testShowUploadInvalid()
    {
        $injector = $this->createInjectorForLoggedIn();
        $uploadForm = $injector->make('Blog\Form\BlogUploadForm');
        $mock = \Mockery::mock($uploadForm)
            ->shouldReceive('false')
            ->andReturn(true)
            ->mock();

        $injector->share('Blog\Form\BlogUploadForm', $mock);
        $result = $injector->execute('Blog\Controller\BlogUpload::showUpload');
        $this->assertInstanceOf('Tier\Executable', $result);
    }

//    public function testUploadPost()
//    {
//        $this->injector->execute('Blog\Controller\BlogUpload::uploadPost');
//    }


    public function testUploadResult()
    {
        $result = $this->injector->execute('Blog\Controller\BlogUpload::uploadResult');
        $this->assertInstanceOf('Tier\Executable', $result);
    }
}
