<?php


namespace Blog;

interface Response
{
    public function getStatus();
    public function getBody();
    public function getHeaders();
}
