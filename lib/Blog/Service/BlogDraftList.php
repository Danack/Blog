<?php

namespace Blog\Service;

use Blog\Value\BlogDraftPath;

class BlogDraftList
{
     /**
      * @var BlogDraftPath
      */
     private $storagePath;

    public function __construct(BlogDraftPath $storagePath)
    {
        $this->storagePath = $storagePath;
    }

    public function getMap()
    {
        $draftDirectory = $this->storagePath->getPath();
        $pattern = $draftDirectory."*.tpl.md";
        $draftFilenameList = glob($pattern);
        $draftFilenameList = str_replace([$draftDirectory, ".tpl.md"], "", $draftFilenameList);

        $draftTitleList = str_replace("_", " ", $draftFilenameList);
        $draftFilenameTitleMap = array_combine($draftFilenameList, $draftTitleList);

        return $draftFilenameTitleMap;
    }
}
