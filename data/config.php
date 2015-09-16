<?php

use Blog\Config;

$default = [
    //global/default variables go here.
    'nginx.sendFile' => 'off',
    
    'mysql.charset' => 'utf8mb4',
    'mysql.collation' => 'utf8mb4_unicode_ci',
    'mysql.casetablenames' => '0',
    'mysql.datadir' => '/var/lib/mysql',
    'mysql.socket' => '/var/lib/mysql/mysql.sock',
    'mysql.log.directory' => '/var/log',

    'phpfpm.www.maxmemory' => '16M', 
    
    'github.root.directory' => '/home/github/',
];

$socketDir = '/var/run/php-fpm';

$centos = [
    'nginx.log.directory' => '/var/log/nginx',
    'nginx.root.directory' => '/usr/share/nginx',
    'nginx.conf.directory' => '/etc/nginx',
    'nginx.run.directory' => '/var/run',
    'nginx.user' => 'nginx',
    'nginx.sendFile' => 'off',

    'blog.root.directory' => '/home/github/Blog/Blog/',

    'phpfpm.maxmemory' => '16M',
    'phpfpm.user' => 'blog',
    'phpfpm.group' => 'www-data',
    'phpfpm.socket.directory' => $socketDir,
    'phpfpm.conf.directory' => '/etc/php-fpm.d',
    'phpfpm.pid.directory' => '/var/run/php-fpm',

    'phpfpm.fullsocketpath' => $socketDir."/php-fpm-blog-".basename(dirname(__DIR__)).".sock",

    'php.conf.directory' => '/etc/php',
    'php.log.directory' => '/var/log/php',
    'php.errorlog.directory' => '/var/log/php',
    'php.session.directory' => '/var/lib/php/session',

    'ssl.directory' => '/temp/ssl',
];

$centos_guest = $centos;

$dev = [
    Config::LIBRATO_STATSSOURCENAME => 'blog.test',    
    Config::JIG_COMPILE_CHECK => \Jig\Jig::COMPILE_ALWAYS,
];

$live = [
    Config::LIBRATO_STATSSOURCENAME => 'blog.com',
    Config::JIG_COMPILE_CHECK => \Jig\Jig::COMPILE_CHECK_EXISTS,
];