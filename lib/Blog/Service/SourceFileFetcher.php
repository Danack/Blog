<?php

namespace Blog\Service;

interface SourceFileFetcher
{
    /**
     * @param $srcFile
     * @return string The contents of the file
     */
    public function fetch($srcFile);
}
