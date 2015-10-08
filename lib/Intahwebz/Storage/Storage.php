<?php


namespace Intahwebz\Storage;





abstract class Storage
{
    abstract public function uploadFileToS3Bucket($bucket, $storageFilename, $tmpFilename);

    abstract function downloadFileFromS3Bucket($bucket, $storageFilename, $localFilename);
    

	/**
	 * Gets the content tag that indicates what type of storage the content is stored in.
	 * @return mixed
	 */
	abstract function getContentTag();

	abstract function renameFile($originalFilename, $newFilename);

    abstract function listFiles($bucket, $pattern);
}


