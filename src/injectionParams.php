<?php

use Tier\InjectionParams;
use Blog\Value\AutogenPath;
use Intahwebz\DataPath;
use Intahwebz\StoragePath;
use Blog\Value\WebRootPath;
use Blog\Value\ExternalLibPath;
use Intahwebz\LogPath;
use Intahwebz\YuiCompressorPath;
use Blog\Value\CachePath;

// These classes will only be created once by the injector.
$shares = [
    'Jig\JigRender',
    'Jig\Jig',
    'Jig\JigConverter',
    'Amp\Reactor',
    'Intahwebz\DB\Connection',
    'Intahwebz\Utils\ScriptInclude',
    new AutogenPath(__DIR__."/../autogen/"),
    new DataPath(__DIR__."/../data/"),
    new StoragePath(__DIR__."/../var/"),
    new WebRootPath(__DIR__.'/../public/'),
    new LogPath(__DIR__.'/../var/log/'),
    new ExternalLibPath(__DIR__.'/../lib/'),
    new YuiCompressorPath("/usr/lib/yuicompressor.jar"),
    new CachePath(__DIR__.'/../var/cache/'),
    'Intahwebz\Session',
    
    'BaseReality\Form\LoginForm',
    'BaseReality\Form\BlogUploadForm',
    'BaseReality\Form\BlogEditForm',
    'BaseReality\Form\BlogReplaceForm',
];
    

// Alias interfaces (or classes) to the actual types that should be used 
// where they are required. 
$aliases = [
    'ArtaxServiceBuilder\ResponseCache' =>
    'ArtaxServiceBuilder\ResponseCache\NullResponseCache',
    'Blog\FilePacker' => 'Blog\StandardFilePacker',
    'Blog\Mapper\BlogPostMapper' => '\Blog\Mapper\BlogPostSQLMapper',
    'Blog\Service\SourceFileFetcher' => 'Blog\Service\OnlineSourceFileFetcher',
    'Intahwebz\DB\Connection' => 'Intahwebz\DB\MySQLiConnection',
    'Intahwebz\DB\StatementFactory' =>'Intahwebz\DB\MySQLiStatementFactory',
    'Intahwebz\Domain' => 'BaseReality\DomainBlog',
    'Intahwebz\ObjectCache' => 'Intahwebz\Cache\NullObjectCache',
    'Intahwebz\Storage\Storage' => 'Intahwebz\Storage\S3Storage',
    'Psr\Log\LoggerInterface' => 'Intahwebz\NullLogger',
    'Jig\Jig' => 'Blog\Service\BlogJig',
    'Intahwebz\Session' => 'Intahwebz\Session\Session',
    'Blog\Mapper\LoginMapper' => 'Blog\Mapper\LoginSQLMapper',
    'Intahwebz\Request' => 'Intahwebz\Routing\HTTPRequest',
    'Intahwebz\FileFetcher' => 'Intahwebz\Utils\UploadedFileFetcher',
    'Intahwebz\Framework\VariableMap' => 'Intahwebz\Framework\RequestVariableMap',
    'Intahwebz\Form\DataStore' => 'Blog\Bridge\SessionDataStore'
];


// Delegate the creation of types to callables.
$delegates = [
    'Amp\Reactor' => 'Amp\getReactor',
    'GithubService\GithubArtaxService\GithubService' => 'createGithubArtaxService',
    'Jig\JigConfig' => 'createJigConfig',
    'Blog\Data\TemplateList' => 'createTemplateList',
    //'Intahwebz\DB\MySQLiConnection' => 'createMySQLiConnection'
    'Intahwebz\Utils\ScriptInclude' => 'createScriptInclude',
    'Intahwebz\Routing\HTTPRequest' => 'createHTTPRequest',
    'Intahwebz\Utils\UploadedFileFetcher' => 'createUploadedFileFetcher'
];



// If necessary, define some params that can be injected purely by name.
$params = [];

$prepares = [
    //'Jig\Jig' => 'prepareJig'
];

$injectionParams = new InjectionParams(
    $shares,
    $aliases,
    $delegates,
    $params,
    $prepares
);

return $injectionParams;
