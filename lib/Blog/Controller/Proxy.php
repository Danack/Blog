<?php

namespace Blog\Controller;

use Blog\Value\CachePath;
use FileFilter\Storage;
use FileFilter\StorageDownloadFilter;
use Room11\HTTP\Body\FileBody;

class Proxy
{

    public function staticImage($filename, $size = null)
    {
        header('Content-type: image/png');
        readfile(__DIR__ . '/../../../files/' . $filename);
        //new FileResponse()
        exit(0);
    }

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
