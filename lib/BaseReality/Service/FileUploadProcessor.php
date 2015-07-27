<?php


namespace BaseReality\Service;

//use BaseReality\Mapper\FileMapper;
use Intahwebz\Storage\Storage;
use Intahwebz\FileFetcher;

class FileUploadProcessor
{
//
//
//    private $storage;
//    private $fileFetcher;
//    private $fileMapper;
//    
//    function __construct(Storage $storage,
//                         FileFetcher $fileFetcher,
//                         FileMapper $fileMapper) {
//        $this->storage = $storage;
//        $this->fileFetcher = $fileFetcher;
//        $this->fileMapper = $fileMapper;
//    }
//
//    function process($description, $uploadName) {
//        $uploadedFile = $this->fileFetcher->getUploadedFile($uploadName);
//        $bucket = CONTENT_BUCKET;
//
//        try {
//            $this->storage->uploadFileToS3Bucket(
//                $bucket,
//                \BaseReality\Content\File::STORAGE_FOLDER.'/'.$uploadedFile->name,
//                $uploadedFile->tmpName
//            );
//        }
//        catch(\Intahwebz\Exception\DuplicateFileException $dfe) {
//            //TODO - do something more sensible.
//            return [false, "File already exists in remote storage"];
//        }
//
//        $storageTag = $this->storage->getContentTag();
//        $fileData = $this->fileMapper->addFile($uploadedFile->name, $storageTag, $description, $bucket);
//        
//        return [true, "File stored"];
//    }
}
