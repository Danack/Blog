<?php


namespace Blog\Controller;

use Room11\HTTP\Body\TextBody;
use Room11\HTTP\Body\JsonBody;
use Tier\Bridge\JigExecutable;
use Jig\Jig;
use Jig\JigDispatcher;

class ReactTest
{
    function reactTest()
    {
        return JigExecutable::create('pages/react');
    }

    function reactApi()
    {
        $data = [
            'text' => "Hello the time is : " . date('Y_m_d_H_i_s')
        ];

        return new JsonBody($data);
    }

    function template_render(JigDispatcher $jig)
    {
        $json = $_REQUEST["json"];
        $data = json_decode($json, true);
        $output = $jig->renderTemplateFromString($data['text'], "template_" . rand(10000, 1000000));

        return new JsonBody(['text' => $output]);
    }

}