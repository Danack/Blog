<?php

namespace Intahwebz\Image;


use Intahwebz\ImageFile;
use Intahwebz\ImageLoader;

class ImagickImageLoader implements ImageLoader {

    
    
    
	function createImageFromFile($srcFileName){
        $types = array(
            'image/jpg' => 'imagecreatefromjpeg',
            'image/jpeg' => 'imagecreatefromjpeg',
            'image/gif' => 'imagecreatefromgif',
            'image/png' => 'imagecreatefrompng'
        );

        //TODO - make this a generic function to be used elsewhere.
        $finfo = new \finfo(FILEINFO_MIME);
        $type = $finfo->file($srcFileName);
        $mime = mb_substr($type, 0, mb_strpos($type, ';'));

        if(array_key_exists($mime, $types) == FALSE){
            throw new \Exception("Unsupported image mime type: ".$mime);
        }

        $func = $types[$mime];
        $src = $func($srcFileName);

        return $src;
    }

/*
 
TODO - presumably image uploading isn't working. 

	function createImageFromBlog(){
		//static function createThumbnailFromFileDB($imageID, $destFileName, $maxWidth, $maxHeight){

			$fileInfo = getDBFileInfoAndContents($imageID);

			if($fileInfo == FALSE){
				throw new Exception("Failed to retrieve image $imageID from database.");
			}

			if(function_exists('imagecreatefromstring') !== TRUE){
				logToFileFatal("Error, function imagecreatefromstring doesn't exist - presumably the GD libraries are not installed.");
				exit(0);
			}

			$image = FALSE;

			if(strcmp($fileInfo['contentType'], "image/gif") === 0){

				
//					$dummy_file = "dummy.gif";
//
//					# write the contents to a dummy file
//					$output = fopen("$dummy_file", "wb");
//					fwrite($output, $fileInfo['contents']);
//					fclose($output);
//
//					# create the gif from the dummy file
//					$image = ImageCreateFromGif($dummy_file);
//
//					# get rid of the dummy file
//					//unlink($dummy_file);
//
//					$im = new Imagick($image);
				$im = new Imagick();

				$im->readimageblob($fileInfo['contents']);

				//$im->coalesceImages();

				$width = $im->getImageWidth();
				$height = $im->getImageHeight();

				//echo "width $width <br/>";
				//echo "height $height <br/>";


				$newsize = self::setWidthHeight( $width,
					$height,
					$maxWidth,
					$maxHeight);

				$count = $im->getNumberImages();


				for ($x = 1; $x<=$im->getNumberImages(); $x++) {
					$im->previousImage();
					$im->thumbnailImage($newsize[0], $newsize[1]);
					$im->writeImage('img'.$x.'.png');
				}

				$coalesced = $im->coalesceImages();

				$type = $im->getFormat();
				header("Content-type: $type");

				if ($coalesced->getNumberImages() > 1){
					echo $coalesced->getImagesBlob();
				}
				else{
					echo $coalesced->getImageBlob();
				}

				exit(0);
			}
			else{
				$image = @imagecreatefromstring($fileInfo['contents']);

				if ($image === FALSE){
					//logToFileError('Error loading image $imageID from database.');
					//return FALSE;
					throw new Exception("Failed to create image from data retreived from database, presumably file is corrupt.");
				}
			}

			$cursize = array();

			$cursize['0'] = imagesx($image);
			$cursize['1'] = imagesy($image);

			if($cursize[0] * $cursize[0] > (4096 * 4096)){
				return FALSE;
			}

			$newsize = self::setWidthHeight( $cursize[0],
				$cursize[1],
				$maxWidth,
				$maxHeight);

			$dst = imagecreatetruecolor($newsize[0], $newsize[1]);
			$src = $image;

			imagealphablending( $dst, false);
			imagesavealpha($dst, true);

			imagecopyresampled( $dst, $src, 0, 0, 0, 0,
				$newsize[0], $newsize[1],
				$cursize[0], $cursize[1]);

			//ImageTrueColorToPalette2( $dst, 10, 255);

			self::saveImageToFile($dst, $destFileName);
			return;
		}
*/


	/* For the love of God.
	 *
	 * Debian doesn't include the function "ImageColorMatch" as it has been included by the PHP developers and
	 * and so is considered a branch of GD library - and so is a security risk. Because the PHP developers wrote it.
	 */

	static function    ImageTrueColorToPalette2( &$image, $dither, $ncolors ){
			$width = imagesx( $image );
			$height = imagesy( $image );
			$colors_handle = ImageCreateTrueColor( $width, $height );
			ImageCopyMerge( $colors_handle, $image, 0, 0, 0, 0, $width, $height, 100 );
			ImageTrueColorToPalette( $image, $dither, $ncolors );
			ImageColorMatch( $colors_handle, $image );
			ImageDestroy( $colors_handle );
		}


    /**
     * @param $blob
     * @return ImageFile
     */
    function createImageFromBlob($blob) {
        // TODO: Implement createImageFromBlob() method.
    }
}


