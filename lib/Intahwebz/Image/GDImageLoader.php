<?php

namespace Intahwebz\Image;


use Intahwebz\ImageLoader;


class GDImageLoader implements ImageLoader {

    /**
     * @param $srcFileName String
     * @throws \Exception
     * @return GDImageFile
     */
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
        $imageHandle = $func($srcFileName);
        $imageInfo = getimagesize($srcFileName);
        //var_dump($imageInfo);
        //TODO - get rid of finfo above and use the:
        //$imageInfo['mime'];

        return new GDImageFile($imageHandle, $imageInfo[0], $imageInfo[1]);
    }

    //static function createThumbnailFromFileDB($imageID, $destFileName, $maxWidth, $maxHeight){
    function createImageFromBlob($blob){
        $image = @imagecreatefromstring($blob);

        if ($image === FALSE){
            //logToFileError('Error loading image $imageID from database.');
            //return FALSE;
            throw new \Exception("Failed to create image from data retreived from database, presumably file is corrupt.");
        }

        $width = imagesx($image);
        $height = imagesy($image);

        return new GDImageFile($image, $width, $height);
    }


    /* For the love of God.
     *
     * Debian doesn't include the function "ImageColorMatch" as it has been included by the PHP developers and
     * and so is considered a branch of GD library - and so is a security risk. Because the PHP developers wrote it.
     */
    function    ImageTrueColorToPalette2( &$image, $dither, $ncolors ){
        $width = imagesx( $image );
        $height = imagesy( $image );
        $colors_handle = ImageCreateTrueColor( $width, $height );
        ImageCopyMerge( $colors_handle, $image, 0, 0, 0, 0, $width, $height, 100 );
        ImageTrueColorToPalette( $image, $dither, $ncolors );
        ImageColorMatch( $colors_handle, $image );
        ImageDestroy( $colors_handle );
    }
}


