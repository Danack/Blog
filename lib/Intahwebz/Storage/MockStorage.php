<?php


namespace Intahwebz\Storage;



class MockStorage extends Storage {


//    function __construct() {
//        //Implementation must declare constructor
//    }


    public function uploadFileToS3Bucket($bucket, $storageFilename, $tmpFilename) {
        // TODO: Implement uploadFileToS3Bucket() method.
    }

    function downloadFileFromS3Bucket($bucket, $storageFilename, $localFilename) {
        // TODO: Implement downloadFileFromS3Bucket() method.
    }

    /**
     * Gets the content tag that indicates what type of storage the content is stored in.
     * @return mixed
     */
    function getContentTag(){
        return "*MOCK";
    }

    function renameFile($originalFilename, $newFilename) {
        // TODO: Implement renameFile() method.
    }

    function listFiles($bucket, $pattern) {
        // TODO: Implement listFiles() method.
    }


}