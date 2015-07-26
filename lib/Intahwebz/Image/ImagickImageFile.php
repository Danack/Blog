<?php

namespace Intahwebz\Image;


use Intahwebz\ImageFile;

use Psr\Log\LoggerInterface;

use Intahwebz\Exception\InternalAPIFailedException;
use Intahwebz\Utils\ImageTooLargeException;

class ImagickImageFile extends ImageFile{

    /**
     * @var LoggerInterface
     */
    private $logger;
    
    public function __construct(LoggerInterface $logger) {
    
    }
    
	static function setWidthHeight($srcWidth, $srcHeight, $maxWidth, $maxHeight){

		$ret = array($srcWidth, $srcHeight);

		$ratio = $srcWidth / $srcHeight;

		if($srcWidth > $maxWidth || $srcHeight > $maxHeight){

			$ret[0] = $maxWidth;
			$ret[1] = $ret[0] / $ratio;

			if($ret[1] > $maxHeight){
				$ret[1]  = $maxHeight;
				$ret[0] = $maxHeight * $ratio;
			}
		}

		$ret[0] = intval(ceil($ret[0]));
		$ret[1] = intval(ceil($ret[1]));

		return $ret;
	}


	static function saveImageToFile($dst, $destFileName){

		$types = array(
			'jpg' => array('imagecreatefromjpeg', 'imagejpeg', 80),
			'jpeg' => array('imagecreatefromjpeg', 'imagejpeg', 80),
			'gif' => array('imagecreatefromgif', 'imagegif'),
			'png' => array('imagecreatefrompng', 'imagepng')
		);

		ensureDirectoryExists($destFileName);

		$thepath = pathinfo($destFileName);
		$fileExtension = mb_strtolower($thepath['extension']);

		$quality = false;

		$func = "imagejpeg";

		if(array_key_exists($fileExtension, $types) == true){
			$func = $types[$fileExtension][1];

			if(isset($types[$fileExtension][2]) === TRUE){
				$quality = $types[$fileExtension][2];
			}
		}

		if($quality != false){
			$func($dst, $destFileName, $types[$fileExtension][2]);
		}
		else{
			$func($dst, $destFileName);
		}

		if(file_exists($destFileName) == FALSE){
			throw new \Exception("Failed to save image destFileName ".$destFileName." func ".$func);
		}
	}



	function createthumb($srcFileName, $destFileName, $maxWidth, $maxHeight) {

		ensureDirectoryExists($destFileName);

		if (is_file($srcFileName)) {

			if(filesize($srcFileName) == 0){
				throw new InternalAPIFailedException("Trying to resize file but it's of size 0.");
			}

			$cursize = getimagesize($srcFileName);

			if ($cursize === FALSE) {
				throw new InternalAPIFailedException("Failed to read image size getimagesize(srcFileName $srcFileName)");
			}

			//TODO - change when I get a > 16MB camera
			if ($cursize[0] * $cursize[1] >(2048 * 2048)) {
				$errorString = "Image size is too big > 2048 * 2048 cursize is:";
				$errorString .= getVar_DumpOutput($cursize);
				throw new ImageTooLargeException($errorString);
			}

			$newsize = self::setWidthHeight(
				$cursize[0],
				$cursize[1],
				$maxWidth,
				$maxHeight
			);

			$dst = imagecreatetruecolor($newsize[0], $newsize[1]);

			$src = $this->createImageFromFile($srcFileName);

			imagealphablending( $dst, false);
			imagesavealpha($dst, true);

			imagecopyresampled($dst, $src, 0, 0, 0, 0,
				$newsize[0], $newsize[1],
				$cursize[0], $cursize[1]
			);

			self::saveImageToFile($dst, $destFileName);

            $this->logger->debug("Image resizing complete.");
		}
		else {
			throw new \Exception("Source image [$srcFileName] does not exist");
		}
	}

    function saveImage($destFileName, $imageType) {
        // TODO: Implement saveImage() method.
    }

    /**
     * @param $srcFileName
     * @return resource
     */
    function createImageFromFile($srcFileName) {
        unused($srcFileName);
        return null;
    }
}


