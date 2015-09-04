<?php

use Blog\Config;


$config = [];


$dev = [];
$dev[Config::JIG_COMPILE_CHECK] = \Jig\Jig::COMPILE_ALWAYS;
$dev[Config::LIBRATO_STATSSOURCENAME] = 'blog.test';

$live = [];
$live[Config::JIG_COMPILE_CHECK] = \Jig\Jig::COMPILE_CHECK_EXISTS;
$live[Config::LIBRATO_STATSSOURCENAME] = 'blog.com';


define('CONTENT_BUCKET', 'content.basereality.com');


$config['live'] = $live;
$config['dev'] = $dev;

return $config;