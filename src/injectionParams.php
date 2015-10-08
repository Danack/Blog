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

// These classes will only be created  by the injector once
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
    'Intahwebz\FileFetcher' => 'Intahwebz\Utils\UploadedFileFetcher',
    'Intahwebz\Framework\VariableMap' => 'Intahwebz\Framework\RequestVariableMap',
    'Intahwebz\Form\DataStore' => 'Blog\Bridge\SessionDataStore',
    
    'Room11\HTTP\Request' => 'Room11\HTTP\Request\Request',
    'Room11\HTTP\Response' => 'Room11\HTTP\Response\Response',
    
    'FilePacker\FilePacker' => 'FilePacker\YuiFilePacker'
];


// Delegate the creation of types to callables.
$delegates = [
    'Amp\Reactor' => 'Amp\getReactor',
    'GithubService\GithubArtaxService\GithubService' => 'createGithubArtaxService',
    'Jig\JigConfig' => 'createJigConfig',
    'Blog\Data\TemplateList' => 'createTemplateList',
    'Intahwebz\Utils\ScriptInclude' => 'createScriptInclude',
    'Intahwebz\Utils\UploadedFileFetcher' => 'createUploadedFileFetcher',
    'Room11\Caching\LastModifiedStrategy' => 'createCaching',
];

// If necessary, define some params that can be injected purely by name.
$params = [ ];

$defines = [
    'Tier\Path\AutogenPath'       => [':path' => __DIR__."/../autogen/"],
    'Intahwebz\DataPath'          => [':path' => __DIR__."/../data/"],
    'Intahwebz\StoragePath'       => [':path' => __DIR__."/../var/"],
    'Tier\Path\CachePath'         => [':path' => __DIR__.'/../var/cache/'],
    'Tier\Path\ExternalLibPath'   => [':path' => __DIR__.'/../lib/'],
    'Tier\Path\WebRootPath'       => [':path' => __DIR__.'/../public/'],
    'FileFilter\YuiCompressorPath' => ["/usr/lib/yuicompressor.jar"],
    'Intahwebz\DB\MySQLiConnection' => [
        ':host'     => MYSQL_SERVER,
        ':username' => MYSQL_USERNAME,
        ':password' => MYSQL_PASSWORD,
        ':port'     => MYSQL_PORT,
        ':socket'   => MYSQL_SOCKET_CONNECTION
    ],
];

$prepares = [
];

$injectionParams = new InjectionParams(
    $shares,
    $aliases,
    $delegates,
    $params,
    $prepares,
    $defines
);

return $injectionParams;
