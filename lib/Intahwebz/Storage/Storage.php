<?php


namespace Intahwebz\Storage;





abstract class Storage {

    /**
     * @var \Psr\Log\LoggerInterface
     */
    var $logger;

//    private  function __construct() {
//        //Implementation must declare constructor
//    }

    abstract public function uploadFileToS3Bucket($bucket, $storageFilename, $tmpFilename);

    abstract function downloadFileFromS3Bucket($bucket, $storageFilename, $localFilename);
    
	/**
	 * Gets a local version of the file from storage.
	 * @param $content
	 * @return mixed
	 */
//	abstract function getLocalCacheFile(Content $content);

	/**
	 * Gets the content tag that indicates what type of storage the content is stored in.
	 * @return mixed
	 */
	abstract function getContentTag();

	abstract function renameFile($originalFilename, $newFilename);

    abstract function listFiles($bucket, $pattern);
}


