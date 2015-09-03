<?php

namespace Blog\Controller;

use Blog\Value\CachePath;
use Intahwebz\Storage\Storage;
use Intahwebz\FileFilter\StorageDownloadFilter;

use Room11\HTTP\Body\FileBody;

class Proxy
{
    public function staticFile(Storage $storage, CachePath $cachePath, $filename)
    {
        $filename = str_replace(array("\\", ".."), "", $filename);

        $filter = new StorageDownloadFilter(
            $storage,
            $cachePath->getFile("static/original", $filename, null),
            'static.basereality.com',
            $filename
        );

        $filter->process();
        $fileNameToServe = $filter->getFile()->getPath();

        return new FileBody($fileNameToServe, 'text/plain');
    }
}
