<?php

use Blog\Config;

$env = [
//    Config::LIBRATO_STATSSOURCENAME,
    Config::JIG_COMPILE_CHECK,
//    Config::CACHING_SETTING,
//
//    Config::DOMAIN_CANONICAL,
//    Config::DOMAIN_CDN_PATTERN,
//    Config::DOMAIN_CDN_TOTAL,
//    
//    Config::SCRIPT_VERSION,
    Config::SCRIPT_PACKING,
    
    'MYSQL_PORT',
    'MYSQL_USERNAME',
    'MYSQL_PASSWORD',
    'MYSQL_ROOT_PASSWORD',
    'MYSQL_SERVER',
    'MYSQL_SOCKET_CONNECTION',
    'SESSION_NAME',
];

return $env;
