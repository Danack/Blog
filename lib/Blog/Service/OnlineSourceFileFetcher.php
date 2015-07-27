<?php

namespace Blog\Service;

use Intahwebz\FileFilter\StorageDownloadFilter;
use Blog\Value\CachePath;
use Intahwebz\Storage\Storage;

class OnlineSourceFileFetcher implements SourceFileFetcher
{

    /**
     * @var CachePath
     */
    private $cachePath;

    /**
     * @var Storage
     */
    private $storage;

    public function __construct(CachePath $cachePath, Storage $storage)
    {
        $this->cachePath = $cachePath;
        $this->storage = $storage;
    }

    /**
     * @param $srcFile
     * @return string
     */
    public function fetch($srcFile)
    {
        $filter = new StorageDownloadFilter(
            $this->storage,
            $this->cachePath->getFile("static/original", $srcFile, null),
            'static.basereality.com', //STATIC_BUCKET,
            $srcFile
        );

        $filter->process();
        $fileNameToServe = $filter->getFile()->getPath();

        return $fileNameToServe;
    }
}
