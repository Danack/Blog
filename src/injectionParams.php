<?php

use Tier\InjectionParams;
//use Blog\Value\AutogenPath;
//use Intahwebz\DataPath;
//use Intahwebz\StoragePath;
//use Blog\Value\WebRootPath;
//use Blog\Value\ExternalLibPath;
//use Intahwebz\LogPath;
use FileFilter\YuiCompressorPath;
use Blog\Value\CachePath;
use FCForms\FileFetcher\StubFileFetcher;

// These classes will only be created  by the injector once
$shares = [
    'Jig\Jig',
    'Jig\JigConverter',
    'Amp\Reactor',
    'ASM\Session',
    'Intahwebz\DB\Connection',
    'ScriptHelper\ScriptInclude',
    //new AutogenPath(__DIR__."/../autogen/"),
    //new DataPath(__DIR__."/../data/"),
    //new StoragePath(__DIR__."/../var/"),
    //new WebRootPath(__DIR__.'/../public/'),
    //new LogPath(__DIR__.'/../var/log/'),
    //new ExternalLibPath(__DIR__.'/../lib/'),
    new YuiCompressorPath("/usr/lib/yuicompressor.jar"),
    new CachePath(__DIR__.'/../var/cache/'),
    new \FileFilter\TmpPath(__DIR__.'/../var/cache/'),
    'Blog\Config',
    'Room11\HTTP\HeadersSet',
    'Blog\Debug',
    'FCForms\FileFetcher\UploadedFileFetcher',
];
    

// Alias interfaces (or classes) to the actual types that should be used 
// where they are required. 
$aliases = [
    'ArtaxServiceBuilder\ResponseCache' =>
    'ArtaxServiceBuilder\ResponseCache\NullResponseCache',
    'ASM\Driver' => 'ASM\File\FileDriver',
    
    'Blog\Repository\BlogPostRepo' => '\Blog\Repository\SQL\BlogPostSQLRepo',
    'Blog\Service\SourceFileFetcher' => 'Blog\Service\OnlineSourceFileFetcher',
    'Intahwebz\DB\Connection' => 'Intahwebz\DB\MySQLiConnection',
    'Intahwebz\DB\StatementFactory' =>'Intahwebz\DB\MySQLiStatementFactory',
    'Intahwebz\Domain' => 'BaseReality\DomainBlog',
    'Intahwebz\ObjectCache' => 'Intahwebz\Cache\NullObjectCache',
    'FileFilter\Storage' => 'FileFilter\Storage\S3\S3Storage',
    'Psr\Log\LoggerInterface' => 'Blog\NullLogger',
    'Jig\Jig' => 'Blog\Service\BlogJig',
    'FCForms\Render' => 'FCForms\Render\BootStrapRender',
    'Blog\Repoistory\LoginRepo' => 'Blog\Repository\LoginSQLRepo',
    'Intahwebz\FileFetcher' => 'Intahwebz\Utils\UploadedFileFetcher',
    'FCForms\DataStore' => 'Blog\Bridge\SessionDataStore',
    'FCForms\FileFetcher' => 'FCForms\FileFetcher\UploadedFileFetcher',
    'Room11\HTTP\VariableMap' => 'Room11\HTTP\VariableMap\RequestVariableMap',
    'Room11\HTTP\RequestHeaders' => 'Room11\HTTP\Request\HTTPRequestHeaders',
    'Room11\HTTP\Response' => 'Room11\HTTP\Response\Response',
    'ScriptHelper\FilePacker' => 'ScriptHelper\FilePacker\YuiFilePacker',
    
    'ScriptHelper\ScriptVersion' => 'ScriptHelper\ScriptVersion\DateScriptVersion',
    'ScriptHelper\ScriptURLGenerator' => 'ScriptHelper\ScriptURLGenerator\StandardScriptURLGenerator'
    
];


// Delegate the creation of types to callables.
$delegates = [
    'ASM\Session' => 'createSession',
    'ASM\File\FileDriver' => 'createASMFileDriver',
    'Amp\Reactor' => 'Amp\getReactor',
    'Blog\Data\TemplateList' => 'createTemplateList',
    'GithubService\GithubArtaxService\GithubService' => 'createGithubArtaxService',
    'FileFilter\Storage\S3\S3Config' => 'createS3Config',
    'ScriptHelper\ScriptInclude' => 'createScriptInclude',
    'Jig\JigConfig' => 'createJigConfig',
    'Room11\Caching\LastModifiedStrategy' => 'createCaching',
    'Intahwebz\DB\MySQLiConnection' => 'createMySQLiConnection',
    'Blog\Site\EditBlogPostBox' => ['Blog\Site\EditBlogPostBox', 'createEditBox'],
    'Blog\UserPermissions' => 'createUserPermissions',
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
