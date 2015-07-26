<?php


namespace Blog\Data;


class TemplateList {

    private $list;

    public function __construct($list)
    {
        $this->list = $list;
    }

    public function getList()
    {
        return $this->list;
    }
}

