<?php

namespace Blog;

interface FilePacker
{
    public function getHeaders();
    public function getFinalFilename(array $filesToPack, $extension);
    public function pack($outputFilename, $jsIncludeArray, $appendLine, $extension);
}
