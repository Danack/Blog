<?php

use Tier\InjectionParams;

use Blog\Value\AutogenPath;
use Intahwebz\DataPath;
use Blog\Value\ExternalLibPath;
use Intahwebz\StoragePath;
use Intahwebz\YamlPath;
use Blog\Value\WebRootPath;
use Intahwebz\LogPath;
use Intahwebz\YuiCompressorPath;
use Blog\Value\CachePath;


// These classes will only be created once by the injector.
$shares = [
    'Jig\JigRender',
    'Jig\Jig',
    'Jig\JigConverter',
    'Amp\Reactor',
    new AutogenPath(__DIR__."/../autogen/"),
    new DataPath(__DIR__."/../data/"),
    new ExternalLibPath(__DIR__.'/../lib/'),
    new LogPath(__DIR__.'/../var/log/'),
    new StoragePath(__DIR__."/../var/"),
    new StoragePath(__DIR__."/../var/"),
    new YamlPath(__DIR__."/../data/TableMapper/"),
    new WebRootPath(__DIR__.'/./fixtures/'),
    new YuiCompressorPath("/usr/lib/yuicompressor.jar"),
    new CachePath(__DIR__.'/./tmp/cache/'),
    'Intahwebz\Form\DataStore'
];




//$provider->share(new ExternalLibPath(__DIR__.'/../lib/'));
//$provider->share(new FontPath(__DIR__.'/../fonts/'));

//$templatePath = new TemplatePath(__DIR__."/../templates/phpbasereality/");
//$provider->share($templatePath);
//$generatedSourcePath = new GeneratedSourcePath(__DIR__."/../var/src");
//$provider->share($generatedSourcePath);



// Alias interfaces (or classes) to the actual types that should be used 
// where they are required. 
$aliases = [
    'ArtaxServiceBuilder\ResponseCache' =>
    'ArtaxServiceBuilder\ResponseCache\NullResponseCache',
    'Blog\Service\SourceFileFetcher' => 'BlogMock\Service\StubSourceFileFetcher',
    'Blog\Mapper\BlogPostMapper' => 'Blog\Mapper\BlogPostMapperMock',
    'Blog\FilePacker' => 'BlogMock\MockFilePacker',
    'Intahwebz\ObjectCache' => 'Intahwebz\Cache\NullObjectCache',
    'Intahwebz\Domain' => 'BaseReality\DomainBlog',
    'Intahwebz\Utils\ScriptInclude' => 'Intahwebz\Utils\ScriptIncludeIndividual',
    'Psr\Log\LoggerInterface' => 'Intahwebz\NullLogger',
    'Intahwebz\Form\DataStore' => 'BlogStub\ArrayDataStore',
    'Intahwebz\Framework\VariableMap' => 'BlogStub\StubVariableMap',
    'Intahwebz\FileFetcher' => 'BlogStub\StubFileFetcher',
];


// Delegate the creation of types to callables.
$delegates = [
    'Amp\Reactor'         => 'Amp\getReactor',
    'Arya\Request'        => 'mockEmptyRequest',
    //'Blog\Model\BlogPost' => 'mockBlogPost',
    'Jig\JigConfig'       => 'mockJigConfig',
];



// If necessary, define some params that can be injected purely by name.
$params = [];

$injectionParams = new InjectionParams(
    $shares,
    $aliases,
    $delegates,
    $params
);

return $injectionParams;