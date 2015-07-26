<?php


namespace Intahwebz\Utils;

use Intahwebz\UnknownFileType;
use Intahwebz\UploadedFile;

//TODO - friends don't let friends use CURL
function curlDownloadFileAndReturnHeaders($url, $fileHandle) {

    $ch = curl_init();

    $headers = array();

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_NOPROGRESS, FALSE);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 1024);
    curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FILE, $fileHandle);

    curl_exec($ch);

    $responseInfo = curl_getinfo($ch);

    curl_close($ch);

    return $responseInfo;
}


class RemoteFileFetcher {

    /**
     * @param $imageURL
     * @return UploadedFile
     */
    function getImageFromLink($imageURL) {
        $tempFilename = tempnam(sys_get_temp_dir(), 'Tux');

        $fileHandle = fopen($tempFilename, 'w+');

        $headerArray = curlDownloadFileAndReturnHeaders($imageURL, $fileHandle);

        fclose($fileHandle);

        if (false) {
            $filename = $this->calcFilename($headerArray, $imageURL);
        }
        else {
            $filename = $this->calcFilenameFromFinfo($tempFilename, $imageURL);
        }

        return new UploadedFile(
            $filename,
            $tempFilename,
            filesize($tempFilename)
        );
    }

    //TODO move this to elsewhere - it's not anything to do with downloading the file
    function calcFilenameFromFinfo($tempFilename, $imageURL) {

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tempFilename);
        
        $allowedFileTypes = array( 
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif' 
        );
        
        $extension = array_search(
            $mime,
            $allowedFileTypes,
            true
        );
        
        if ($extension == false) {
            throw new UnknownFileType("Could not determine file type of file downloaded from $imageURL");
        }

        $urlPath = parse_url($imageURL, PHP_URL_PATH);

        $filename = pathinfo($urlPath, PATHINFO_FILENAME);

        if(mb_strlen($filename) == 0){
            $filename = date("Y_m_d_H_i_s");
        }

        $filename = $filename.".".$extension;

        return $filename;
    }
    
    
    function calcFilename($headerArray, $imageURL) {

        $fileInfo = array();

        foreach($headerArray as $headerKey => $headerValue){
            if(mb_strcasecmp('Content-type', $headerKey) == 0 ||
                mb_strcasecmp('content_type', $headerKey) == 0){
                $fileInfo['contentType'] = $headerValue;
            }
        }

        $urlInfo = parse_url($imageURL);
        $lastSlashPosition = mb_strrpos($urlInfo['path'], '/');

        if($lastSlashPosition === FALSE){
            $filename = $urlInfo['path'];
        }
        else{
            $filename = mb_substr($urlInfo['path'], $lastSlashPosition + 1);//+1 to exclude the slash
        }

        if(mb_strlen($filename) == 0){
            $filename = date("Y_m_d_H_i_s");
            //Cannot guess image type from made up file name.

            if(array_key_exists('contentType', $fileInfo) == TRUE){
                $extension = getFileExtensionForMimeType($fileInfo['contentType']);
                $filename .= ".".$extension;
            }
        }
        
        return $filename;
    }
    
}

 