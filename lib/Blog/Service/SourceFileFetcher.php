<?php

namespace Blog\Service;

interface SourceFileFetcher
{
    /**
     * @param $srcFile
     * @return string
     */
    public function fetch($srcFile);
}
