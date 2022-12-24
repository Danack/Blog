<?php

declare(strict_types=1);

use Blog\Config;

$sha = `git rev-parse HEAD`;

if ($sha === null) {
    echo "Failed to read sha from git. Is git installed in container?";
    exit(-1);
}

$sha = trim($sha);


$default = [
    'opcache.enabled' => '1',
    'script.version' => '100917010607',
    'script.packing' => true,
    'caching.setting' => 'caching.time',
    'jig.compilecheck' => 'COMPILE_CHECK_EXISTS',
    'system.build_debug_php_containers' => false,
    Config::BLOG_COMMIT_SHA => $sha,
    Config::BLOG_DEPLOY_TIME => (new DateTime())->format('Y_m_d_H_i_s')
];


$local = [
    'opcache.enabled' => '0',
    'caching.setting' => 'caching.time',
    'system.build_debug_php_containers' => true,
    Config::BLOG_ENVIRONMENT => 'local'
];

$prod = [
    Config::BLOG_ENVIRONMENT => 'prod',

];

//$varnish_debug = [
//    'varnish.pass_all_requests' => false
//];

