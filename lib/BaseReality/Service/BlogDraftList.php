<?php

namespace BaseReality\Service;

use Intahwebz\StoragePath;

class BlogDraftList
{
     /**
      * @var StoragePath
      */
     private $storagePath;

    public function __construct(StoragePath $storagePath)
    {
        $this->storagePath = $storagePath;
    }

    public function getMap()
    {
        $draftDirectory = $this->storagePath->getSafePath('blogDraft');
        $pattern = $draftDirectory."*.tpl.md";
        $draftFilenameList = glob($pattern);
        $draftFilenameList = str_replace([$draftDirectory, ".tpl.md"], "", $draftFilenameList);

        $draftTitleList = str_replace("_", " ", $draftFilenameList);
        $draftFilenameTitleMap = array_combine($draftFilenameList, $draftTitleList);

        return $draftFilenameTitleMap;
    }
}
