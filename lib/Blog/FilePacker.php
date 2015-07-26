<?php


namespace Blog;


interface FilePacker {
    function getHeaders();
    function getFinalFilename(array $filesToPack, $extension);
    function pack($outputFilename, $jsIncludeArray, $appendLine, $extension);
}



