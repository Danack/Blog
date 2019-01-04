
Because I keep forgetting and having to remind myself every couple of years, here is to properly setup an image for anti-aliasing in PHP using the GD library. You need to create an image and give it a proper background colour, even if that background colour is transparent. This makes the image library use a proper alpha-channel (which is the only sensible way of doing alpha blending) rather than using an indexed based alpha, where only one 'colour' is transparent and all the others are fully opaque.The code below produces an image like this:

<!-- end_preview -->

{{ articleImage('phpGD_imageTest.png', 'original', 'none') }}

The image has a background colour on the left-hand side of the picture, and the font it anti-aliased against that correctly. The font is also anti-aliased against the transparent background of the right-hand side of the picture, and so blends with the background of the page.


{% set code_to_highlight %}

$font = '../../fonts/Arial.ttf';
$text = 'The Quick Brown Fox Jumps over the Lazy Dog';

// Create the image
function imageCreateTransparent($x, $y) {
    $imageOut = imagecreatetruecolor($x, $y);
    $backgroundColor = imagecolorallocatealpha($imageOut, 0, 0, 0, 127);
    imagefill($imageOut, 0, 0, $backgroundColor);

    return $imageOut;
}


$image = imageCreateTransparent(600, 100);

// Create some colors
$white = imagecolorallocate($image, 255, 255, 255);
$fontColour = imagecolorallocate($image, 0xff, 0x2f, 0x2f);

// Draw the white box
imagefilledrectangle($image, 0, 0, 399, 29, $white);

// Add the text over the top
imagettftext($image, 20, 0, 10, 20, $fontColour, $font, $text);
imagesavealpha($image, true);
header("Content-Type: image/png");
imagepng($image);

{% endset %}

{{ syntaxHighlighter(code_to_highlight, 'php') }}



