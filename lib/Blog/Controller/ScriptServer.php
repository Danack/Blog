<?php

namespace Blog\Controller;

use Intahwebz\Response\FileResponse;
use Intahwebz\StoragePath;
use Blog\Value\AutogenPath;
use Blog\Value\WebRootPath;
use Blog\Value\ExternalLibPath;
use Tier\ResponseBody\FileResponseCreator;
use Blog\FilePacker;
use Arya\Request;
use Arya\Response;
use Tier\ResponseBody\EmptyBody;
use Arya\FileBody;

function extractItems($cssInclude)
{
    $items = [];
    $cssIncludeArray = explode(',', $cssInclude);
    foreach ($cssIncludeArray as $cssIncludeItem) {
        $cssIncludeItem = urldecode($cssIncludeItem);
        $cssIncludeItem = trim($cssIncludeItem);
        $versionString = str_replace(
            array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "."),
            "",
            $cssIncludeItem
        );
        
        $versionString = trim($versionString);

        if (mb_strlen($versionString) == 0) {
            //This isn't actually a JS include but is instead a version number
            continue;
        }

        $items[] = $cssIncludeItem;
    }

    return $items;
}


function checkIfModifiedHeader(Request $request, $unixTime)
{
    if (!$request->hasHeader('If-Modified-Since')) {
        return false;
    }

    $header = $request->getHeader('If-Modified-Since');

    if (@strtotime($header) == $unixTime) {
        return true;
    }

    return false;
}


class ScriptServer
{
    /**
     * @var FilePacker
     */
    private $filePacker;

    /**
     * @param StoragePath $storagePath
     * @param AutogenPath $autogenPath
     * @param WebRootPath $webRootPath
     * @param ExternalLibPath $externalLibPath
     */
    public function __construct(
        Response $response,
        FileResponseCreator $fileResponseCreator,
        FilePacker $filePacker,
        WebRootPath $webRootPath
    ) {
        $this->webRootPath = $webRootPath->getPath();
        $this->filePacker = $filePacker;
        $this->response = $response;
    }

    /**
     * @param $cssInclude
     * @return array
     */
    private function getCSSFilesToInclude($cssInclude)
    {
        $files = array();
        $items = extractItems($cssInclude);
        foreach ($items as $item) {
            $files[] = $this->getCSSFilename($item);
        }

        return $files;
    }

    /**
     * @param $cssIncludeItem
     * @return string
     */
    private function getCSSFilename($cssIncludeItem)
    {
        $cssIncludeItem = str_replace(array("\\", ".." ), "", $cssIncludeItem);

        return $this->webRootPath."/css/".$cssIncludeItem.".css";
    }

    /**
     * @param $jsIncludeItem
     * @return string
     */
    private function getJavascriptFilename($jsIncludeItem)
    {
        $jsIncludeItem = str_replace(array("\\", ".."), "", $jsIncludeItem);

        return $this->webRootPath . "js/" . $jsIncludeItem . ".js";
    }

    /**
     * @param $jsInclude
     * @return array
     */
    private function getJSFilesToInclude($jsInclude)
    {
        $files = array();
        $items = extractItems($jsInclude);
        foreach ($items as $item) {
            $files[] = $this->getJavascriptFilename($item);
        }

        return $files;
    }

        /**
     * @param $cssInclude
     * @return FileResponse
     */
    public function getPackedCSS(Request $request, $cssInclude)
    {
        $cssIncludeArray = $this->getCSSFilesToInclude($cssInclude);

        return $this->getPackedFiles(
            $request,
            $cssIncludeArray,
            $appendLine = "\n",
            'text/css',
            'css'
        );
    }


    /**
     * @param $jsInclude
     * @return FileBody|EmptyBody
     * @throws \Exception
     */
    public function getPackedJavascript(Request $request, $jsInclude)
    {
        $jsIncludeArray = $this->getJSFilesToInclude($jsInclude);
        
        return $this->getPackedFiles(
            $request,
            $jsIncludeArray,
            $appendLine = "",
            'application/javascript',
            'js'
        );
    }
    
    public function getPackedFiles(Request $request, $jsIncludeArray, $appendLine, $contentType, $extension)
    {
        $finalFilename = $this->filePacker->getFinalFilename($jsIncludeArray, $extension);
        $notModifiedHeader = checkIfModifiedHeader(
            $request,
            @filemtime($finalFilename)
        );

        if ($notModifiedHeader) {
            $this->response->setStatus(304);
            return new EmptyBody();
        }

        $finalFilename = $this->filePacker->pack("john", $jsIncludeArray, $appendLine, $extension);

        $this->response->addHeader('Content-Type', $contentType);
        $fileBody = new FileBody($finalFilename);

        $headers = $this->filePacker->getHeaders();
        foreach ($headers as $key => $value) {
            $this->response->addHeader($key, $value);
        }

        return $fileBody;
    }
}
