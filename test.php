<?php
$new_im = new Imagick();
$new_im->setFormat('gif');


$im = new Imagick('bg/10.gif');

$im1 = $im->clone();
$draw = new ImagickDraw();
$draw->setFontSize(20);
$im->annotateImage($draw, 4, 30, 0, 'hello world!');	
$draw->setFillColor(new ImagickPixel('#FF0000'));
$im1->annotateImage($draw, 4, 30, 0, 'hello world!');

$new_im->newImage( $im->getImageWidth(), $im->getImageHeight(), 'transparent' );
$new_im->compositeImage($im,Imagick::COMPOSITE_OVER,0,0);

$pics = array($im, $im1);
$color=new ImagickPixel();
$color->setColor("black");

foreach($pics as $k => $v)
{
//$v->roundCorners(5,5);
//$v->borderImage($color, 5, 5);
	$new_im->addImage($v);
	$new_im->setImageDelay(10);
	
}

	header( "Content-Type: image/gif" );
	echo $new_im->getImagesBlob();
?>