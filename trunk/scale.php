<?php
$img = "font/".substr($_GET['f'],0,strpos($_GET['f'],".")).".gif";
$im = new Imagick($img);
$w= $im->getImageWidth();
$h= $im->getImageHeight();
$im->cropImage($w-20,$h-15,0,0);
$im->scaleImage($w/2,$h/2);
echo $im;
?>