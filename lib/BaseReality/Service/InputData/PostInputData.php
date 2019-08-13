<?php

declare(strict_types = 1);

namespace BaseReality\Service\InputData;

class PostInputData implements InputData
{
    public function getData()
    {
        $json = file_get_contents('php://input');
        $data = json_decode_safe($json);

        return $data;
    }
}
