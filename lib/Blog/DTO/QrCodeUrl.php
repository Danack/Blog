<?php

namespace Blog\DTO;

class QrCodeUrl {

    public $src;

    public $secret;

    /**
     * QrCodeUrl constructor.
     * @param $src
     */
    public function __construct($src, $secret)
    {
        $this->src = $src;
        $this->secret = $secret;
    }


}