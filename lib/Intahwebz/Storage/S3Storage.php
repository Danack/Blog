<?php

namespace Intahwebz\Storage;

use Psr\Log\LoggerInterface;
use Intahwebz\StoragePath;

use Intahwebz\UploadedFile;
use Intahwebz\Exception\DuplicateFileException;
use Intahwebz\Exception\ExternalAPIFailedException;

//define('AMAZON_S3_PREFERRED_LOCATION', \AmazonS3::REGION_APAC_SE1);
define('AMAZON_S3_PREFERRED_LOCATION', 's3-ap-southeast-1.amazonaws.com');

function S3Init() {
    //This function exists because this include does a load of crap, including
    //accessing the filesystem, so we only include it when we're actually going to be making
    //an S3 call, to avoid slowing down every page.
    require_once __DIR__.'/../../../lib/amazonWS/sdk.class.php';
}


class S3Storage extends Storage {
    
    private $storagePath;

    function __construct(LoggerInterface $logger, StoragePath $storagePath) {
        $this->storagePath = $storagePath->getPath();
        $this->logger = $logger;
    }

    function getContentTag(){
        return "*S3";
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param $folder
     * @return mixed
     * @throws DuplicateFileException
     * @throws ExternalAPIFailedException
     */
    function storeFile(UploadedFile $uploadedFile, $folder){
        S3Init();

        $bucket = CONTENT_BUCKET;
        $originalFilename = $uploadedFile->name;
        $tmpFilename = $uploadedFile->tmpName;
        $storageFilename = $folder.'/'.$originalFilename;
        $result = self::uploadFileToS3Bucket($bucket, $storageFilename, $tmpFilename);

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
        S3Init();
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
     * @throws \S3_Exception
     */
    public function uploadFileToS3Bucket($bucket, $storageFilename, $tmpFilename){
        S3Init();
        $region = $this->getS3RegionOfBucket($bucket);

        $s3 = new \AmazonS3();
        if ($region) {
            $s3->set_region($region);
        }
        
        $s3->enable_path_style(true);

        $this->logger->info("Uploading to bucket ".$bucket." storageFilename ".$storageFilename." size is ".filesize($tmpFilename));

        if (!$s3->if_bucket_exists($bucket)){
            $this->logger->info("Bucket $bucket does not exist, creating.");
            $response = $s3->create_bucket($bucket, AMAZON_S3_PREFERRED_LOCATION);
            if (!$response->isOK()){
                //die('Could not create `' . $bucket . '`.');
                throw new ExternalAPIFailedException('Could not create [' . $bucket . '] in AmazonS3.');
            }
        }

        if($s3->if_object_exists ($bucket, $storageFilename) == true){
            throw new DuplicateFileException("File already exists");
        }

        // Upload an object.
        $response = $s3->create_object(
            $bucket,
            $storageFilename,
            array(
                'fileUpload' => $tmpFilename
            )
        );

        if($response->isOK() == true){
            return true;
        }

        throw new ExternalAPIFailedException('Failed to upload file: '.$response->body);
    }

    /**
     * @param $bucket
     * @param $storageFilename
     * @param $localFilename
     * @throws ExternalAPIFailedException
     * @throws \Exception
     */
    function downloadFileFromS3Bucket($bucket, $storageFilename, $localFilename) {
        S3Init();
        $s3 = new \AmazonS3();// Instantiate the AmazonS3 class
        
        $region = $this->getS3RegionOfBucket($bucket);
        
        if ($region) {
            $s3->set_region($region);
        }

        $s3->enable_path_style(true);

        $startTime = microtime();

        $this->logger->info("About to fetch file from S3 bucket: ".$bucket." filename: ".$storageFilename." localCacheFilename: ".$localFilename);

        $tempFilename = tempnam(sys_get_temp_dir(), 's3_');

        $response = $s3->get_object(
            $bucket,
            $storageFilename,
            array(
                'fileDownload' => $tempFilename,
            )
        );

        $this->logger->info("Download complete from S3 bucket: ".$bucket." filename: ".$storageFilename." localCacheFilename: ".$localFilename);

        if($response->isOK() == true) {
            //renameMultiplatform($tempFilename, $localFilename);
            saveTmpFile($tempFilename, $localFilename);
            $this->logger->info("Download of $localFilename was okay");
        }
        else {
            $this->logger->info("Download of $localFilename was not okay, removing temp file");
            $removed = unlink($tempFilename);

            $responseAsString = getVar_DumpOutput($response);

            if($removed == false){
                $errorString = "Failed to retrieve file from S3 AND the cached file was not deleted: ".$responseAsString;
                $this->logger->error($errorString);
                //TODO - turn into server admin notice - this should be thrown by api not this class.
                throw new ExternalAPIFailedException($errorString);
            }

            $errorString = "Failed to retrieve file from S3: ".$responseAsString;
            $this->logger->error($errorString);
            throw new ExternalAPIFailedException($errorString);
        }


//        $timeTaken = microtime_diff($startTime);

        $fileSize = filesize($localFilename);

        if ($fileSize == 0) {
            unlink($localFilename);
            throw new \Exception("File was 'downloaded ok' but file size is zero? ");
        }
        
        //$speedString = calculateSpeedString($fileSize, $timeTaken);

        //$this->logger->info("File ".$localFilename." download at $speedString ($fileSize bytes in $timeTaken seconds).");
    }


    /**
     * @param $bucket
     * @param bool $pattern
     * @return array
     * @throws \Exception
     */
    function listFiles($bucket, $pattern = false) {
        S3Init();
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

    function renameFile($originalFilename, $newFilename) {

        S3Init();
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

    function getS3RegionOfBucket($bucket) {
        S3Init();
        $s3 = new \AmazonS3();
        $s3->enable_path_style(true);
        $response = $s3->get_bucket_region($bucket);

        if (!$response->isOK()) {
            var_dump($response);
            throw new ExternalAPIFailedException('Could not find region for [' . $bucket . '].');
        }
        $region = $response->body;

        if ($region && mb_strlen($region) > 0) {
        
            $whyNotJustGiveMeTheURL = "s3-".$region.".amazonaws.com";
            return $whyNotJustGiveMeTheURL;
        }

        return false;
    }
}

