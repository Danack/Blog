<?php

namespace Intahwebz\Response;

class FileResponse extends SendableResponse implements \Intahwebz\Response\Response {

    private $fileNameToServe;
    
    private $prependText = null;
    private $appendText = null;


    private $contentType = null;
    

    function __construct($fileNameToServe, $contentType) {
        
        //parent::__construct($request);
        
        if (is_readable($fileNameToServe) === false) {
            throw new \Exception("File $fileNameToServe isn't readable, can't serve it.");
        }
        $this->fileNameToServe = $fileNameToServe;
        

        $this->contentType = $contentType;
        

    }
    
    function prepend($text) {
        $this->prependText = $text;
    }
    
    function append($text) {
        $this->appendText = $text;
    }

    /**
     * @param $seconds_to_cache
     * @return mixed
     */
    function createCachingHeaders($seconds_to_cache) {
        ///** @noinspection PhpUnusedParameterInspection */  $secondsForCDNToCache = false
        $currentTimeStamp = gmdate("D, d M Y H:i:s", time()) . " UTC";
        $timeStamp = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " UTC";

        //	if($secondsForCDNToCache === false){
        //		$secondsForCDNToCache = intval($seconds_to_cache / 10);
        //	}

        $headers["Date"] = $currentTimeStamp;
        $headers["Expires"] = $timeStamp;
        $headers["Cache-Control"] = "must-revalidate, private";
        
        //$this->setHeader("Date", $currentTimeStamp);
        //header("HTTP1/0 200 Ok");
        //header("Content-Type: $mimeType");
        //header("Date: $currentTimeStamp");

        //TODO - does this get cached by CDN.
        //header("Expires: $timeStamp");
        //$this->setHeader("Expires", $timeStamp);
        //$this->response->setHeader("Expires", $timeStamp);
        //header("Pragma: cache");

        //header("Expires: -1");
        //$this->response->setHeader("Cache-Control", "must-revalidate, private");
        //header("Cache-Control: max-age=$seconds_to_cache, s-maxage=$secondsForCDNToCache");
        //max-age = browser max age
        //s-maxage = intermediate (cache e.g. CDN)
        
        return $headers;
    }


    function process(\Intahwebz\Request $request) {
        
        $headers = [];

        $headers = $this->createCachingHeaders(3600);
        
//        var_dump($headers);
//        exit(0);
        
        $lastModifiedTime = filemtime($this->fileNameToServe);
        $fileSize = filesize($this->fileNameToServe);

        if ($this->contentType) {
            $headers["Content-Type"] = $this->contentType;
        }

        $ifModifiedHeader = $request->getHeader('HTTP_IF_MODIFIED_SINCE');
        if ($ifModifiedHeader) {
            if (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModifiedTime) {
                $headers[] = $this->createStatusHeader($request, 304, "Not Modified");
                $this->sendHeaders($headers);

                return;
            }
        }

        $filesize = $fileSize;
        //TODO appendtext should be a different type....it's not cacheable as a filesytem file
        if ($this->prependText) {
            $filesize += strlen($this->prependText);
        }
        if ($this->appendText) {
            $filesize += strlen($this->appendText);
        }

        $headers['Content-Length'] = $fileSize;
        $headers['Last-Modified'] = \getLastModifiedTime($lastModifiedTime);
        $this->sendHeaders($headers);
        $this->sendFile();
    }

    /**
     * @throws \Exception
     */
    private function sendFile() {
        if ($this->prependText) {
            echo $this->prependText;
        }

        $result = @readfile($this->fileNameToServe);

        if($result === false){
            throw new \Exception("Failed to readfile [$this->fileNameToServe] for serving.");
        }
        
        if ($this->appendText) {
            echo $this->appendText;
        }
    }
}

 