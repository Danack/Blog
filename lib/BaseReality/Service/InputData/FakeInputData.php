<?php

declare(strict_types = 1);

namespace BaseReality\Service\InputData;

class FakeInputData implements InputData
{
    private $data;

    /**
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
