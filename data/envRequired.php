<?php

use Blog\Config;

$env = [
    Config::JIG_COMPILE_CHECK,
    Config::SCRIPT_PACKING,
    Config::REPOSITORY_MAPPING,

    'MYSQL_PORT',
    'MYSQL_USERNAME',
    'MYSQL_PASSWORD',
    'MYSQL_ROOT_PASSWORD',
    'MYSQL_SERVER',
    'MYSQL_SOCKET_CONNECTION',
    'SESSION_NAME',
];

return $env;
