<?php

namespace Intahwebz\Storage;

use Psr\Log\LoggerInterface;
use Intahwebz\StoragePath;

use Intahwebz\UploadedFile;
use Intahwebz\Exception\DuplicateFileException;
use Intahwebz\Exception\ExternalAPIFailedException;
use  Intahwebz\S3Bridge\S3ClientFactory;
use Aws\S3\S3Client;


class S3Storage extends Storage
{    
    private $storagePath;
    
    private $s3ClientFactory;
    
    const AMAZON_S3_PREFERRED_LOCATION = 's3-ap-southeast-1.amazonaws.com';

    function __construct(
        S3ClientFactory $s3ClientFactory,
        StoragePath $storagePath
    ) {
        $this->s3ClientFactory = $s3ClientFactory;
        $this->storagePath = $storagePath->getPath();
    }

    function getContentTag()
    {
        return "*S3";
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param $folder
     * @return mixed
     * @throws DuplicateFileException
     * @throws ExternalAPIFailedException
     */
    function storeFile(UploadedFile $uploadedFile, $folder)
    {
        
        $originalFilename = $uploadedFile->name;
        $tmpFilename = $uploadedFile->tmpName;
        $storageFilename = $folder.'/'.$originalFilename;
        $result = self::uploadFileToS3Bucket(
            $this->backupBucket,
            $storageFilename,
            $tmpFilename
        );

        if($result == true){
            return $originalFilename;
        }

        throw new ExternalAPIFailedException('Could not upload file.');
    }

    /**
     * @param $buckets
     * @param $backupDirectory
     * @throws \Exception
     * @throws \S3_Exception
     */
    function backupS3ToLocalFile($buckets, $backupDirectory){
        
        $s3 = new \AmazonS3();
        $count = 0;
        $bucketCount = 0;

        foreach($buckets as $bucket){

            $objectList = $s3->get_object_list($bucket);

            $bucketCount++;
            $objectCount = 0;

            foreach($objectList as $object){

                $objectCount++;
                $this->logger->info("bucket $bucket $bucketCount / ".count($buckets)." object $object $objectCount / ".count($objectList)."\r\n");

                $backupFilename = $backupDirectory."/".$bucket."/".$object;

                $backupFilename = str_replace("//", "/", $backupFilename);

                $this->logger->info("Save file ".$backupFilename."\r\n");

                ensureDirectoryExists($backupFilename);

                if(is_dir($backupFilename) == true){
                    //skip bogus items that have no file name
                }
                else{
                    $etagFileName = $backupFilename.'.etag';

                    $etagFileExists = file_exists($etagFileName);

                    $tempFilename = tempnam(sys_get_temp_dir(), 's3_');

                    $opt = array(
                        'fileDownload' => $tempFilename,//$backupFilename,
                    );

                    if($etagFileExists){
                        $etagLines = file($etagFileName);
                        $etagCached = implode('', $etagLines);
                        $opt['etag'] = $etagCached;
                    }

                    $response = $s3->get_object(
                        $bucket,
                        $object,
                        $opt
                    );

                    if($response->isOK() == true){
                        //renameMultiplatform($tempFilename, $backupFilename);
                        saveTmpFile($tempFilename, $backupFilename);
                        //$this->logger->info("Download of $localFilename was okay");
                    }
                    else{
                        if($response->status == 304){  //not modified
                            $this->logger->info("Skipping file, 304\r\n");
                        }
                        else{
                            $this->logger->info("Failed to retrieve file from S3\r\n");
                            var_dump($response);
                            exit(0);
                        }
                    }

                    if($etagFileExists == false){
                        $metadata = $s3->get_object_metadata($bucket, $object);
                        $fileHandle = fopen($etagFileName, 'w');

                        if($fileHandle == false){
                            $this->logger->info("Unable to open file [".$etagFileName."] for writing etag.]\r\n");
                            exit(0);
                        }

                        fwrite($fileHandle, $metadata["ETag"]);
                        fclose($fileHandle);
                    }
                }
                $count++;
            }
        }
    }

    /**
     * @param $bucket
     * @param $storageFilename
     * @param $tmpFilename
     * @return bool
     * @throws DuplicateFileException
     * @throws ExternalAPIFailedException
     */
    public function uploadFileToS3Bucket($bucket, $storageFilename, $tmpFilename)
    {
        $region = $this->getS3RegionOfBucket($bucket);
        $s3 = $this->s3ClientFactory->createClient();
        
        if (!$s3->doesBucketExist($bucket)) {
            $response = $s3->createBucket([
                'Bucket' => $bucket,
                'LocationConstraint' => self::AMAZON_S3_PREFERRED_LOCATION
            ]);
        }

        if($s3->doesObjectExist($bucket, $storageFilename) == true) {
            throw new DuplicateFileException("File already exists");
        }

        // Upload an object.
        $response = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $storageFilename,
            'SourceFile' => $tmpFilename
        ]);
    }

    /**
     * @param $bucket
     * @param $storageFilename
     * @param $localFilename
     * @throws ExternalAPIFailedException
     * @throws \Exception
     */
    function downloadFileFromS3Bucket($bucket, $storageFilename, $localFilename)
    {
        $region = $this->getS3RegionOfBucket($bucket);
        $s3 = $this->s3ClientFactory->createClient($region);

        $tempFilename = tempnam(sys_get_temp_dir(), 's3_');

        $response = $s3->getObject([
            'Bucket' => $bucket,
            'Key'    => $storageFilename,
            'SaveAs' => $tempFilename
        ]);

        $fileSize = filesize($tempFilename);

        if ($fileSize == 0) {
            unlink($tempFilename);
            throw new \Exception("File was 'downloaded ok' but file size was zero? ");
        }
        
        saveTmpFile($tempFilename, $localFilename);
    }


    /**
     * @param $bucket
     * @param bool $pattern
     * @return array
     * @throws \Exception
     */
    function listFiles($bucket, $pattern = false)
    {
        //try{
        $s3 = new \AmazonS3();
        $s3->enable_path_style(true);

        if(defined('PROXY_PORT') && PROXY_PORT == true){
            $s3->set_proxy("proxy://127.0.0.1:".PROXY_PORT);
        }

        $options = array();

        if ($pattern != false) {
            $options['prefix'] = $pattern;
        }

        $response = $s3->list_objects($bucket, $options);

        if($response->isOK() !== true){
            throw new \Exception("Something went wrong " .$response->status);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $responseArray = $response->body->toSaneArray();

        $objectListArray = $responseArray['Contents'];

        //Todo - check isTruncate == false. Should never happen.

        $keysToCopy = array(
            'Key',
            'LastModified',
            'ETag',
            'Size',
        );

        $files = array();

        foreach($objectListArray as $object){
            $file = array();
            foreach($keysToCopy as $keyToCopy){
                $file[$keyToCopy] = $object[$keyToCopy];
            }
            $files[] = $file;
        }

        return $files;
    }

    function renameFile($originalFilename, $newFilename)
    {
        $bucket = CONTENT_BUCKET;
        $s3 = new \AmazonS3();
        $s3->enable_path_style(true);
        $folder = 'images';

        $storageFilename = $folder.'/'.$newFilename;

        if($s3->if_object_exists ($bucket, $storageFilename) == true){
            throw new DuplicateFileException("File already exists");
        }

        $response = $s3->copy_object(
            array( // Source
                'bucket'   => $bucket,
                'filename' => $folder.'/'.$originalFilename
            ),
            array( // Destination
                'bucket'   => $bucket,
                'filename' => $storageFilename,
            )
        );

        if($response->isOK() == true){
            return true;
        }

        throw new ExternalAPIFailedException('Failed to upload file: '.$response->body);
    }


    function getS3RegionOfBucket($bucket)
    {
        $s3 = $this->s3ClientFactory->createClient();
        $response = $s3->getBucketLocation(array(
            'Bucket' => $bucket,
        ));

        return $response->get("Location");
    }
}

