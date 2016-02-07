<?php

use Blog\Config;

$default = [
    //global/default variables go here.
    'nginx_sendFile' => 'off',
    'app_name' => 'blog',
    'phpfpm_www_maxmemory' => '16M', 
    //'github_root_directory' => '/home/github/',

    Config::REPOSITORY_MAPPING => Config::REPOSITORY_MAPPING_SQL
];

$socketDir = '/var/run/php-fpm';

$centos = [
    'nginx_log_directory' => '/var/log/nginx',
    'nginx_root_directory' => '/usr/share/nginx',
    'nginx_conf_directory' => '/etc/nginx',
    'nginx_run_directory' => '/var/run',
    'nginx_user' => 'nginx',
    'nginx_sendFile' => 'off',

    'blog_root_directory' => dirname(__DIR__),

    'phpfpm_maxmemory' => '16M',
    'phpfpm_user' => 'blog',
    'phpfpm_group' => 'www-data',
    'phpfpm_socket_directory' => $socketDir,
    'phpfpm_conf_directory' => '/etc/php-fpm.d',
    'phpfpm_pid_directory' => '/var/run/php-fpm',

    'phpfpm_fullsocketpath' => $socketDir."/php-fpm-blog-".basename(dirname(__DIR__)).".sock",

    'php_conf_directory' => '/etc/php',
    'php_log_directory' => '/var/log/php',
    'php_errorlog_directory' => '/var/log/php',
    'php_session_directory' => '/var/lib/php/session',

    'ssl_directory' => '/temp/ssl',
    
    'MYSQL_PORT' => 3306,
    'MYSQL_USERNAME' => 'intahwebz',
    'MYSQL_PASSWORD' => 'pass123',
    'MYSQL_ROOT_PASSWORD' => 'pass123',
    'MYSQL_SERVER' => null,
    'MYSQL_SOCKET_CONNECTION' => '/var/lib/mysql/mysql.sock',
    'SESSION_NAME' => 'irsession',
];





$centos_guest = $centos;
//this doesn't work in vagrant on virtualBox
$centos_guest['nginx_sendFile'] = 'off'; 


$dev = [
    Config::LIBRATO_STATSSOURCENAME => 'blog.test',    
    Config::JIG_COMPILE_CHECK => \Jig\Jig::COMPILE_ALWAYS,
    Config::SCRIPT_PACKING => false,
    Config::KEYS_LOADER => Config::KEYS_LOADER_CLAVIS,
];

$live = [
    Config::LIBRATO_STATSSOURCENAME => 'blog.com',
    Config::JIG_COMPILE_CHECK => \Jig\Jig::COMPILE_CHECK_EXISTS,
    Config::SCRIPT_PACKING => true,
    Config::KEYS_LOADER => Config::KEYS_LOADER_CLAVIS,
];

$stub_all_the_things = [
    Config::REPOSITORY_MAPPING => Config::REPOSITORY_MAPPING_STUB,
    Config::KEYS_LOADER => Config::KEYS_LOADER_NONE,
];

$perf = [
    Config::JIG_COMPILE_CHECK => \Jig\Jig::COMPILE_CHECK_EXISTS,
];

