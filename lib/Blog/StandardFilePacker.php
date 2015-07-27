<?php


namespace Blog;

use Intahwebz\FileFilter\ConcatenatingFilter;
use Intahwebz\FileFilter\GzipFilter;
use Intahwebz\FileFilter\YuiCompressorFilter;
use Intahwebz\StoragePath;
use Blog\Value\AutogenPath;
use Blog\Value\WebRootPath;
use Blog\Value\ExternalLibPath;
use Intahwebz\File;
use Intahwebz\YuiCompressorPath;

class StandardFilePacker implements FilePacker
{
    /**
     * @var string
     */
    private $storagePath;

    /**
     * @var string
     */
    private $autogenPath;

    /**
     * @var string
     */
    private $webRootPath;
    
    /** @var  string  */
    private $externalLibPath;

    public function __construct(
        StoragePath $storagePath,
        AutogenPath $autogenPath,
        WebRootPath $webRootPath,
        ExternalLibPath $externalLibPath,
        YuiCompressorPath $yuiCompressorPath
    ) {
        $this->storagePath = $storagePath->getPath();
        $this->autogenPath = $autogenPath->getPath();
        $this->webRootPath = $webRootPath->getPath();
        $this->externalLibPath = $externalLibPath->getPath();
        $this->yuiCommpressorPath = $yuiCompressorPath;
    }

    public function getHeaders()
    {
        return ['Content-Encoding' =>'gzip'];
    }
    
    public function getFinalFilename(array $filesToPack, $extension)
    {
        $jsInclude = implode("_", $filesToPack);
        $outputFilename = str_replace(array(',', '.', '/', '\\', '%2F'), '_', $jsInclude);
        $outputFilename = mb_substr($outputFilename, 0, 64).'_'.md5($outputFilename);

        return $this->storagePath."/cache/filepacker/".$outputFilename.".".$extension;
    }

    public function pack($outputFilename, $jsIncludeArray, $appendLine, $extension)
    {
        $finalFilename = $this->getFinalFilename($jsIncludeArray, $extension);
        $outputFile = File::fromFullPath($finalFilename);
        //$outputFile = new File($this->storagePath."/cache/filepacker/", $outputFilename, "js");
        $filter = new ConcatenatingFilter($outputFile, $jsIncludeArray, $appendLine);
        $minFile = $outputFile->addExtension('min');
        
        $filter = new YuiCompressorFilter($filter, $minFile, $this->yuiCommpressorPath);
        $compressedFile = $minFile->addExtension('gz', true);
        $filter = new GzipFilter($filter, $compressedFile);
        $filter->process();
    
        $finaleFile = $filter->getFile();
    
        /** @var $finaleFile file */
        $finalFilename = $finaleFile->getPath();

        return $finalFilename;
    }
}
