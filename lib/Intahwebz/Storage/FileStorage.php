<?php

namespace Intahwebz\Storage;

use Psr\Log\LoggerInterface;

use Intahwebz\UploadedFile;
//use Intahwebz\Utils\Image;
use Intahwebz\StoragePath;


class FileStorage extends Storage {

    function __construct(LoggerInterface $logger, StoragePath $storagePath) {
        $this->logger = $logger;
        $this->storagePath = $storagePath->getPath();
    }

    function getContentTag(){
        return "*FILE";
    }

//	function getLocalCachedFilename($content){
//		$storageFilename = str_replace(array('..', '\\', '/'), '',  $content[CLASS_CONTENT.'.text']);
//		return $this->storagePath.'/cache/files/'.$storageFilename;
//	}

    private function getStoragePath($filename){
        $storageFilename = str_replace(array('..', '\\', '/'), '', $filename);
        return $this->storagePath.'files/'.$storageFilename;
    }

    public function uploadFileToS3Bucket($bucket, $storageFilename, $tmpFilename) {
        
    }
    
    
    function storeFile(
        UploadedFile $uploadedFile,
        /** @noinspection PhpUnusedParameterInspection */$folder){
        $tmpFilename = $uploadedFile->tmpName;
        $storageFilename = $this->getStoragePath($uploadedFile->name);
        $this->logger->debug("Uploading to local file system  original file name ".$tmpFilename. " storageFilename ".$storageFilename." size is ".$uploadedFile->size);

        $directoryExists = ensureDirectoryExists($storageFilename);

        if($directoryExists == FALSE){
            throw new \Exception("Could not create directory to store file as [$storageFilename] ");
        }

        $copyResult = copy($tmpFilename, $storageFilename);

        if($copyResult == FALSE){
            throw new \Exception("Failed to copy the file from [$tmpFilename] to [$storageFilename]");
        }

        return $storageFilename;
    }


//	function getLocalCacheFile(Content $content){
//		$filename = $content->getName();
//		$storedFilePath = $this->getStoragePath($filename);
//		if(file_exists($storedFilePath) === FALSE){
//			throw new MissingContentException($storedFilePath, "Content not found, could not read file storage path [$storedFilePath] for content: ", $content);
//		}
//
//		$originalCacheFileName = $content->getLocalCachedFilename();//getLocalCachedFilename($content);
//
//		if(file_exists($originalCacheFileName) === FALSE){
//			ensureDirectoryExists($originalCacheFileName);
//
//			if(file_exists($storedFilePath) === FALSE){
//				throw new MissingContentException($originalCacheFileName, "Content not found, could not read file storage path [$storedFilePath] for content: ", $content);
//			}
//
//			$copyResult = copy($storedFilePath, $originalCacheFileName);
//
//			if($copyResult == FALSE){
//				throw new \Exception("Failed to copy file from $storedFilePath to $originalCacheFileName");
//			}
//		}
//
//		return $originalCacheFileName;
//	}

    function renameFile($originalFilename, $newFilename) {
    }

    function downloadFileFromS3Bucket($bucket, $storageFilename, $localFilename) { 
        throw new \Exception("Not implemented.");   
    }

    function listFiles($bucket, $pattern = false) {
        throw new \BadMethodCallException("listFiles is not implemented.");
    }
}

