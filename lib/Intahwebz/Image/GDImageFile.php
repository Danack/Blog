<?php

namespace Intahwebz\Image;


use Intahwebz\ImageFile;

class GDImageFile extends ImageFile{

    public $imageHandle;
    private $fontFile;

    public function __construct($image, $width, $height) {
        $this->imageHandle = $image;
        $this->srcWidth  = $width;
        $this->srcHeight = $height;
    }

    function saveImage($destFileName, $type) {

        if ($this->destWidth == null) {
            $this->destWidth = $this->srcWidth;
        }

        if ($this->destHeight == null) {
            $this->destHeight = $this->srcHeight;
        }

        $dstImage = imagecreatetruecolor($this->destWidth, $this->destHeight);

        //TODO http://basereality.test:8080/image/131/109/tumblr_l02t64vRZa1qbrvsjo1_500.png
        //Turning this off fucked the image:
//		imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);

        $white = imagecolorallocate($dstImage, 255, 255, 255);
        imagefill($dstImage, 0, 0, $white);

        imagecopyresampled($dstImage, $this->imageHandle, 0, 0, 0, 0,
            $this->destWidth, $this->destHeight,
            $this->srcWidth, $this->srcHeight
        );

        $types = array(
            'jpg' => array('imagecreatefromjpeg', 'imagejpeg', 80),
            'jpeg' => array('imagecreatefromjpeg', 'imagejpeg', 80),
            'gif' => array('imagecreatefromgif', 'imagegif'),
            'png' => array('imagecreatefrompng', 'imagepng')
        );

        ensureDirectoryExists($destFileName);

//        $thepath = pathinfo($destFileName);
//        $fileExtension = mb_strtolower($thepath['extension']);

        $quality = false;

        $func = "imagejpeg";

        if(array_key_exists($type, $types) == true){
            $func = $types[$type][1];

            if(isset($types[$type][2]) === TRUE){
                $quality = $types[$type][2];
            }
        }

        if($quality != false){
            $func($dstImage, $destFileName, $quality);
        }
        else{
            $func($dstImage, $destFileName);
        }

        if(file_exists($destFileName) == FALSE){
            throw new \Exception("Failed to save image destFileName ".$destFileName." func ".$func);
        }
    }

    function setFont($fontFile) {
        $this->fontFile = $fontFile;
    }

    function	renderDebugText($textArray){
//		$black = 0x000000;
        $color = 0x00ff00;
        $fontHeight = 16;
        $fontSpacing = 5;
        $count = 1;

        foreach($textArray as $debugText){
            imagettftext($this->imageHandle, $fontHeight, 0, $fontHeight, $count * ($fontHeight + $fontSpacing), $color, $this->fontFile, $debugText);
            $count += 1;
        }
    }

    function drawTextCentred($fontHeight, $x, $y, $text){
        $black = 0x000000;
        $color = 0x00ff00;

        $textBox = imagettfbbox($fontHeight, 0, $this->fontFile, $text);

        $width = (($textBox[2] + $textBox[4]) - ($textBox[0] + $textBox[6])) / 2;
        $offsetX = -($width / 2);

        $height = ($textBox[5] + $textBox[7]) / 2;
        $offsetY = -$height;

        imagefilledrectangle($this->imageHandle, $x + $offsetX, $y + $offsetY, $x + $offsetX + $width, $y + $offsetY + $height, $black);
        imagettftext($this->imageHandle, $fontHeight, 0, $x + $offsetX, $y + $offsetY, $color, $this->fontFile, $text);
    }


    /*
        function resizeToFile($destFileName, $maxWidth, $maxHeight) {
            //TODO - change when I get a > 16MB camera
            if ($this->srcWidth * $this->srcHeight > (2048 * 2048)) {
                $errorString = "Image size is too big > 2048 * 2048 cursize is:";
                $errorString .= " [".$this->srcWidth." * ".$this->srcHeight."]";
                logToFileDebug($errorString);
                throw new ImageTooLargeException($errorString);
            }

            $this->setWidthHeight(
                $maxWidth,
                $maxHeight
            );

            ensureDirectoryExists($destFileName);

            $dstImage = imagecreatetruecolor($this->destWidth, $this->destHeight);

            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);

            imagecopyresampled($dstImage, $this->imageHandle, 0, 0, 0, 0,
                $this->destWidth, $this->destHeight,
                $this->srcWidth, $this->srcHeight
            );

            $this->saveImageToFile($dstImage, $destFileName);
        } */
}

