<?php

namespace BlogTest\Contorller;

use BlogTest\BaseTestCase;
use Mockery\Mock;
use Arya\Response;

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

    public function testShowUpload()
    {
        $injector = createTestInjector();
        $uploadForm = $injector->make('BaseReality\Form\BlogUploadForm');
        
        $mock = \Mockery::mock($uploadForm)
            ->shouldReceive('validate')
            ->andReturn(true)
            ->mock();

        $injector = createTestInjector(['BaseReality\Form\BlogUploadForm' => $mock]);
        $result = $injector->execute('Blog\Controller\BlogUpload::showUpload');
        $this->assertInstanceOf('Tier\Tier', $result);
    }
    
    
    public function testShowUploadInvalid()
    {
        $injector = createTestInjector();
        $uploadForm = $injector->make('BaseReality\Form\BlogUploadForm');
        
        $mock = \Mockery::mock($uploadForm)
            ->shouldReceive('false')
            ->andReturn(true)
            ->mock();

        $injector = createTestInjector(['BaseReality\Form\BlogUploadForm' => $mock]);
        $result = $injector->execute('Blog\Controller\BlogUpload::showUpload');
        $this->assertInstanceOf('Tier\Tier', $result);
    }
    
    
    
    
    public function testUploadPost()
    {
        $this->injector->execute('Blog\Controller\BlogUpload::uploadPost');
    }


    public function testUploadResult()
    {
        $result = $this->injector->execute('Blog\Controller\BlogUpload::uploadResult');
        $this->assertInstanceOf('Tier\Tier', $result);
    }
}
