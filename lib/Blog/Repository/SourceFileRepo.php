<?php


namespace Blog\Repository;

interface SourceFileRepo
{

    /**
     * @param $filename
     * @return \Blog\Content\SourceFile
     */
    public function getSourceFile($filename);

    public function addSourceFile($filename, $text);

    public function updateSourceFile($sourceFileID, $filename, $text);
}
