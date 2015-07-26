<?php

namespace Blog\Service;


interface SourceFileFetcher
{
    /**
     * @param $srcFile
     * @return string
     */
    function fetch($srcFile);
}



