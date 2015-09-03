<?php

use Jig\JigConfig;

function mockEmptyRequest()
{
    $_server = [
        'REQUEST_URI' => '/',
        'REQUEST_METHOD' => 'GET',
        'SERVER_PROTOCOL' => "HTTP/1.1",
    ];

    return new \Arya\Request($_server, [], [], [], [], []);
}



/**
 * @return JigConfig
 */
function mockJigConfig()
{
    $jigConfig = new JigConfig(
        __DIR__."/../templates/",
        __DIR__."/./compile/",
        'tpl',
        \Jig\Jig::COMPILE_ALWAYS
    );

    return $jigConfig;
}
