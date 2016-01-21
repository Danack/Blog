<?php

use Tier\InjectionParams;

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
    new Blog\Value\StoragePath(__DIR__."/../var/"),
    //new WebRootPath(__DIR__.'/../public/'),
    //new LogPath(__DIR__.'/../var/log/'),
    //new ExternalLibPath(__DIR__.'/../lib/'),
    new \FileFilter\YuiCompressorPath("/usr/lib/yuicompressor.jar"),
    new \Blog\Value\CachePath(__DIR__.'/../var/cache/'),
    new \FileFilter\TmpPath(__DIR__.'/../var/cache/'),
    new \Blog\Value\BlogDraftPath(__DIR__."/../var/blogDraft/"),
    new \FileFilter\TmpPath(__DIR__.'/../var/cache/'),
    new \FCForms\FileFetcher\FileUploadPath(__DIR__.'/../var/file_upload/'),
    'Blog\Config',
    'Room11\HTTP\HeadersSet',
    'Blog\Debug',
    'FCForms\FileFetcher\UploadedFileFetcher',
];
    

// Alias interfaces (or classes) to the actual types that should be used 
// where they are required. 
$aliases = [
    'ArtaxServiceBuilder\ResponseCache' => 'ArtaxServiceBuilder\ResponseCache\NullResponseCache',
    'ASM\Driver' => 'ASM\File\FileDriver',
    'Blog\Repository\BlogPostRepo' => 'Blog\Repository\SQL\BlogPostSQLRepo',
    'Blog\Repository\LoginRepo' => 'Blog\Repository\SQL\LoginSQLRepo',
    'Blog\Repository\SourceFileRepo' => 'Blog\Repository\SQL\SourceFileSQLRepo',
    'Blog\Service\SourceFileFetcher' => 'Blog\Service\SourceFileFetcher\DBSourceFileFetcher',
    'FileFilter\Storage' => 'FileFilter\Storage\S3\S3Storage',
    'Intahwebz\DB\Connection' => 'Intahwebz\DB\MySQLiConnection',
    'Intahwebz\DB\StatementFactory' =>'Intahwebz\DB\MySQLiStatementFactory',
    'Intahwebz\Domain' => 'BaseReality\DomainBlog',
    'Intahwebz\ObjectCache' => 'Intahwebz\Cache\NullObjectCache',
    'Jig\Jig' => 'Blog\Service\BlogJig',
    'Jig\Escaper' => 'Jig\Bridge\ZendEscaperBridge',
    'FCForms\Escaper' => 'FCForms\Bridge\ZendEscaperBridge',
    'FCForms\Render' => 'FCForms\Render\BootStrapRender',
    'FCForms\DataStore' => 'Blog\Bridge\SessionDataStore',
    'FCForms\FileFetcher' => 'FCForms\FileFetcher\PSR7UploadedFileFetcher',
    'Psr\Log\LoggerInterface' => 'Blog\NullLogger',
    'Room11\HTTP\RequestHeaders' => 'Room11\HTTP\RequestHeaders\HTTPRequestHeaders',
    'Room11\HTTP\RequestRouting' => 'Room11\HTTP\RequestRouting\PSR7RequestRouting',
    'Room11\HTTP\VariableMap' => 'Room11\HTTP\VariableMap\PSR7VariableMap',
    'ScriptHelper\FilePacker' => 'ScriptHelper\FilePacker\YuiFilePacker',
    'ScriptHelper\ScriptVersion' => 'ScriptHelper\ScriptVersion\DateScriptVersion',
    'ScriptHelper\ScriptURLGenerator' => 'ScriptHelper\ScriptURLGenerator\StandardScriptURLGenerator',
    'Zend\Diactoros\Response\EmitterInterface' => 'Zend\Diactoros\Response\SapiEmitter',
];

//'Jig\Escaper' => 'Jig\Bridge\ZendEscaperBridge',


// Delegate the creation of types to callables.
$delegates = [
    'ASM\Session' => 'createSession',
    'ASM\File\FileDriver' => 'createASMFileDriver',
    'Amp\Reactor' => 'Amp\getReactor',
    'Blog\Data\TemplateList' => 'createTemplateList',
    'GithubService\GithubArtaxService\GithubService' => 'createGithubArtaxService',
    //'FileFilter\Storage\S3\S3Config' => 'createS3Config',
    'ScriptHelper\ScriptInclude' => 'createScriptInclude',
    'Jig\JigConfig' => 'createJigConfig',
    'Room11\Caching\LastModifiedStrategy' => 'createCaching',
    'Intahwebz\DB\MySQLiConnection' => 'createMySQLiConnection',
    'Blog\Site\EditBlogPostBox' => ['Blog\Site\EditBlogPostBox', 'createEditBox'],
    'Blog\UserPermissions' => 'createUserPermissions',
    'FastRoute\Dispatcher' => 'createDispatcher',
];

// If necessary, define some params that can be injected purely by name.
$params = [ ];

$defines = [
    'Tier\Path\AutogenPath'       => [':path' => __DIR__."/../autogen/"],
    'Intahwebz\DataPath'          => [':path' => __DIR__."/../data/"],
    'Tier\Path\CachePath'         => [':path' => __DIR__.'/../var/cache/'],
    'Tier\Path\ExternalLibPath'   => [':path' => __DIR__.'/../lib/'],
    'Tier\Path\WebRootPath'       => [':path' => __DIR__.'/../public/'],
    'FileFilter\YuiCompressorPath' => ["/usr/lib/yuicompressor.jar"],
];

$prepares = [
    'Jig\Jig' => 'prepareJig'
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
