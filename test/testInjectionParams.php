<?php

use Tier\InjectionParams;

// These classes will only be created once by the injector.
$shares = [
    'Jig\JigRender',
    'Jig\Jig',
    'Jig\JigConverter',
    'Amp\Reactor',
    'Intahwebz\Form\DataStore',

    // new AutogenPath(__DIR__."/../autogen/"),
    // new DataPath(__DIR__."/../data/"),
    // new ExternalLibPath(__DIR__.'/../lib/'),
    // new LogPath(__DIR__.'/../var/log/'),
    // new YamlPath(__DIR__."/../data/TableMapper/"),
    // new WebRootPath(__DIR__.'/./fixtures/'),
    new \Blog\Value\CachePath(__DIR__.'/./tmp/cache/'),
    new \Blog\Value\StoragePath(__DIR__."/../var/temp"),
    new \FileFilter\YuiCompressorPath("/usr/lib/yuicompressor.jar"),
    new \Jig\JigCompilePath(__DIR__."/../var/compile/"),
    new \Jig\JigTemplatePath(__DIR__."/../templates/"),
];


// Alias interfaces (or classes) to the actual types that should be used 
// where they are required. 
$aliases = [
    'ArtaxServiceBuilder\ResponseCache' =>
    'ArtaxServiceBuilder\ResponseCache\NullResponseCache',
    'Blog\FilePacker' => 'BlogMock\MockFilePacker',
    'Blog\Repository\BlogPostRepo' => 'Blog\Repository\Stub\BlogPostStubRepo',
    'Blog\Service\SourceFileFetcher' => 'BlogMock\Service\StubSourceFileFetcher',
    'Blog\Site\LoginStatus' => 'Blog\Site\LoginStatus\LoginStatusStub',
    'Blog\Site\ThemeCSS' => 'Blog\Site\ThemeCSS\StubThemeCSS',
    'Blog\UserPermissions' => 'Blog\User\AnonymousPermissions',
    'FCForms\DataStore' => 'FCForms\DataStore\ArrayDataStore',
    'FCForms\Escaper' => 'FCForms\Bridge\ZendEscaperBridge',
    'FCForms\FileFetcher' => 'FCForms\FileFetcher\EmptyFileFetcher',
    'FCForms\Render' => 'FCForms\Render\BootStrapRender',
    'Intahwebz\ObjectCache' => 'Intahwebz\Cache\NullObjectCache',
    'Intahwebz\Domain' => 'BaseReality\DomainBlog',
    'Intahwebz\Utils\ScriptInclude' => 'Intahwebz\Utils\ScriptIncludeIndividual',
    'Intahwebz\Form\DataStore' => 'BlogStub\ArrayDataStore',
    'Intahwebz\Framework\VariableMap' => 'BlogStub\StubVariableMap',
    'Intahwebz\FileFetcher' => 'BlogStub\StubFileFetcher',
    //'Jig\Escaper' => 'Jig\Bridge\ZendEscaperBridge',
    'Psr\Log\LoggerInterface' => 'Intahwebz\NullLogger',
    'ScriptHelper\ScriptInclude' => 'ScriptHelper\ScriptInclude\ScriptIncludeIndividual',
    'ScriptHelper\ScriptURLGenerator' => 'ScriptHelper\ScriptURLGenerator\StandardScriptURLGenerator',
    'ScriptHelper\ScriptVersion' => 'ScriptHelper\ScriptVersion\DateScriptVersion',
    
    
    
];

// Delegate the creation of types to callables.
$delegates = [
    'Amp\Reactor'         => 'Amp\getReactor',
    'Jig\JigConfig'       => 'Blog\AppTest::createJigConfig',
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
