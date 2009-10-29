<?php
$string = "早上好！";

//静态: 镜面效果
//reflection("logo.png");

//静态: 文字PS效果
//ps_fonts($string,'red',30,"FZSZJW.TTF");

//动画效果：文字图片合成
//pic_hecheng("0009.gif",$string,'red',20);

//动画效果:背景闪光字
//glitter_fonts($string,"glitter_blue.gif",'blue',50,"FZSZJW.TTF");

//动画效果:字体打印输出
//ani_print("欢迎,热烈欢迎！");

//动画效果：字体发光
//ani_grow("Kakapo's nest!",'red','red',50,'Times-Bold');

//动画效果：字体边框粗细
//ani_border("Kakapo's nest!",'red','black',50,'Times-Bold');

//动画效果：字体倒影水面
//ani_wave("Kakapo's nest!",'red',50,'Times-Bold');

//彩字秀:给每个字附加样式，样式可以是动画小图片
//czxiu($string,"autogen0.gif","#CB1016",25,"STXINGKA.TTF");

// 按钮效果
//web_button();
//com_gifs("gif/pig.gif","gif/welcome.gif");
//deal("glitter/glitter.gif");
/**
 * 图片和文字的定位合成 
 * @param string $image
 * @param string $text
 * @param string $fontColor
 * @param integer $fontSize
 * @param integer $x
 * @param integer $y
 * @param string $font
 */
function bcard_hecheng($image,$font_array,$width=300,$height=200,$avatarimg='',$bgimg='',$frontimg=''){
	global $imgfile;
	// 新图像
	$imageObj = new Imagick();
	$imageObj->setFormat("gif");
	//初始化画笔
	$draw = new ImagickDraw();
	
	//初始化背景
	if($bgimg!=''){
		$bghandel = fopen($bgimg,'r');
		$bgimgobj = new Imagick();
		$bgimgobj->readImageFile($bghandel);
	}
	//初始化头像
	if($avatarimg!=''){
		$handel = fopen($avatarimg['file'],'r');
		$avatarObj = new Imagick();
		$avatarObj->readImageFile($handel);
		$avatarObj->thumbnailImage(60,60);
	}
	//初始化闪光图
	if($frontimg!=''){
		$handel = fopen($frontimg,'r');
		$frimgObj = new Imagick();
		$frimgObj->readImageFile($handel);
		$frimgObj->setImageIndex(0);
	}
	
	//小动画
	$handel = fopen($image['file'],'r');
	$im = new Imagick();
	$im->readImageFile($handel);
	foreach ( $im as $k=>$frame )
	{
		echo $k."=";
		//第一帧
		$imageObj->newImage($width,$height,'white','png');
		//背景
		if($bgimgobj!=''){
			$tmp = new Imagick();
	    	$tmp->newImage( $width, $height, "transparent", "gif" );
	   		//$bgimgobj->scaleImage($width,$width);
			$imageObj->compositeImage($tmp->textureImage( $bgimgobj ),Imagick::COMPOSITE_OVER,0,0);
			$tmp->destroy();
		}
		//头像
		if(isset($avatarObj)){
			$imageObj->compositeImage($avatarObj,Imagick::COMPOSITE_OVER,$avatarimg['x'],$avatarimg['y']);
		}
				
		if($frimgObj!=''){
			//$frimg = new Imagick($frontimg);
			$tmp = new Imagick();
	    	$tmp->newImage( $width, $height, "transparent", "gif" );
			//$frimg->setImageIndex($k);
			echo $k." ";
			$imageObj->compositeImage($tmp->textureImage( $frimgObj->getImage() ),Imagick::COMPOSITE_PLUS,0,0);
			if(!$frimgObj->nextImage()){
				$frimgObj->setImageIndex(0);
			}
			$tmp->destroy();
		}
		
		$imageObj->compositeImage($frame,Imagick::COMPOSITE_OVER,$image['x'],$image['y']);
	
		$imageObj ->setImageMatte( true ); 
		$num=1;
		// 简单文字书写
		foreach ($font_array as $font){
			if($font['advanced']==''){
				$draw->setFillColor( $font['color'] );
				$draw->setFontSize( $font['size']);
				$draw->setFont( $font['font']);
				
				$properties = $imageObj->queryFontMetrics( $draw, $font['text'] );
				$font_width =  $properties["textWidth"];
				$font_height = $properties["textHeight"];

				$move_arr = _catulateMove($font_width,$font_height,$font['angle']);
				$x_move = $move_arr['x_move'];
				$y_move = $move_arr['y_move'];
				
				$font_height = ($font_height-5)*cos(deg2rad($font['angle']));
				$imageObj->annotateImage( $draw, $font['x']+$x_move, $font['y']+$font_height+$y_move, $font['angle'], $font['text'] );
			}

		}
		//高级文字合成
		foreach ($font_array as $font){
			if($font['advanced']==1){
				//先生成高级图片再进行合成
				//$advanced_font_img = join_glitter_fonts($width,$height,$glitter_fonts);
				$tmp_font_img = glitter_fonts($font['text'],$font['advanced_set']['style'],$font['advanced_set']['color'],
				$font['advanced_set']['size'],$font['advanced_set']['font'],$font['advanced_set']['border'],$font['advanced_set']['bcolor'],
				$font['advanced_set']['transparent'],$font['advanced_set']['angle']);
				$advanced_font_img = new Imagick($tmp_font_img);
				$num = $advanced_font_img->getNumberImages();
				echo $num;
				if($num>1){
					$advanced_font_img->setImageIndex(0);
					do{
						echo "@".$advanced_font_img->getImageIndex();
						$imageObj->compositeImage($advanced_font_img->getImage(),Imagick::COMPOSITE_OVER,$font['x'], $font['y']);
						$imageObj->setImageDelay( $advanced_font_img->getImageDelay() );
						$imageObj->newImage($width,$height,'transparent','png');
					}while($advanced_font_img->nextImage());
				}else{
					$imageObj->compositeImage($advanced_font_img,Imagick::COMPOSITE_OVER,$font['x'], $font['y']);
				}
				$advanced_font_img->destroy();
			}
		}
			

		if($num==1){
			$imageObj->setImageDelay( $im->getImageDelay() );
		}
	}
	

	//header( "Content-Type: image/gif" );
	//$imageObj->getImagesBlob();
	$imageObj->writeImages($imgfile,1);
}
function _catulateMove($width,$height,$angle){
	if($angle>90 && $angle<=180) {
		$x_move = $width*cos(deg2rad(180-$angle));
		$y_move = $height*cos(deg2rad(180-$angle));
	}				
	if($angle>180 && $angle<=270) {
		$x_move = $width*sin(deg2rad(270-$angle));
		$y_move = $width*cos(deg2rad(270-$angle));
		
	}				
	if($angle>270 && $angle<=360) {
		$x_move = 0;
		$y_move = $width*sin(deg2rad(360-$angle));
		
	}
	if($angle<=90 ){
		$x_move = 0; 
		$y_move = 0; 
	}
	return array("x_move"=>$x_move,"y_move"=>$y_move);
}
function join_glitter_fonts($width,$height,$glitter_fonts){
	$canvas = new Imagick();
	
	$draw = new ImagickDraw();
	$draw->setFillColor( $glitter_fonts['color'] );
	$draw->setFontSize( $glitter_fonts['size'] );
	$draw->setFont( $glitter_fonts['font'] );
	if($border>0){
		$draw->setStrokeColor  ($glitter_fonts['bcolor']);
	}
	$draw->setStrokeWidth  ($glitter_fonts['border']);

	$canvas->newImage( $width, $height, "white", "gif" );
	foreach ($glitter_fonts['text'] as $font){
		$canvas->annotateImage( $draw, $font['x'], $font['y'], $font['angle'], $font['string'] );
	}
	if($glitter_fonts['style']!='solid'){
		$im = new Imagick( $glitter_fonts['style'] );
		$tile = new Imagick();
		if($glitter_fonts['transparent']==1){
			$canvas->paintTransparentImage( 'white', 0, 0 );
		}elseif($glitter_fonts['transparent']==2){
			$canvas->paintTransparentImage( 'black', 0, 0 );
		}
		foreach ( $im as $frame )
		{
		    $tmp = new Imagick();
		    $tmp->newImage( $width, $height, "transparent", "gif" );
		    $tile->addImage( $tmp->textureImage( $frame ) );
		    $tile->compositeImage( $canvas, Imagick::COMPOSITE_OVER , 0, 0 );
		}
		//$tile->writeImages($imgfile,1);
//		header( "Content-Type: image/gif" );
//		echo $tile->getImagesBlob();
//		die;
		return $tile;
	}else{
		//$canvas->writeImages($imgfile,1);
		return $canvas;
	}

	//header( "Content-Type: image/gif" );
	//echo $tile->getImagesBlob();
	
}
function deal($image,$color="#666666"){
	$im = new Imagick($image);
	//echo $im->getImageWidth()."=".$im->getImageHeight();
	foreach ($im as $i){
		//replaceImageColor($i,$color,'transparent',0.9);
		$im->chopImage ($im->getImageWidth(), 22,48,$i->getImageHeight()-22);
		//$im->sampleImage ($i->getImageWidth(),$i->getImageHeight()-10);
	}
	//header("Content-Type: image/gif");
	//echo $im->getImagesBlob();
	$im->writeImages(dirname(__FILE__)."/".$image,1);
}
/**
 * 合成闪烁字体效果
 * @param string $string
 * @param string $glitter_gif
 * @param integer $fontSize
 * @param string $font
 * @return void
 */
function glitter_fonts($string,$style="glitter_blue.gif",$fontColor='red',$fontSize=50,$font='simhei.ttf',$border=1,$bcolor='white',$trans=0,$fontAngle=0){
	global $imgfile;

	$text = new Imagick();
	$draw = new ImagickDraw();
	$draw->setGravity( Imagick::GRAVITY_CENTER );
	$draw->setFillColor( $fontColor );
	$draw->setFontSize( $fontSize );
	$draw->setFont( $font );
	if($border>0){
		$draw->setStrokeColor  ($bcolor);
	}
	$draw->setStrokeWidth  ($border);

	$properties = $text->queryFontMetrics( $draw, $string );
	$cos = cos(deg2rad($fontAngle));
	$sin = sin(deg2rad($fontAngle));
	$width = intval( ($properties["textWidth"] ) * $cos ) + intval($properties["textHeight"]*$sin);
	$height = intval( ($properties["textWidth"] ) * $sin)+ intval($properties["textHeight"]*$cos);
	
	$text->newImage( $width, $height, "white", "png" );
	$text->annotateImage( $draw, 0, 0, $fontAngle, $string );
	
	if($style!='solid'){
		$im = new Imagick( $style );
		$tile = new Imagick();
		if($trans==1){
			$text->paintTransparentImage( 'white', 0, 0 );
		}elseif($trans==2){
			$text->paintTransparentImage( 'black', 0, 0 );
		}
		
		foreach ( $im as $frame )
		{
			
		    $tmp = new Imagick();
		    $tmp->newImage( $width, $height, "white", "png" );
		    $tile->addImage( $tmp->textureImage( $frame ) );
		    
		    $tile->compositeImage( $text, Imagick::COMPOSITE_OVER , 0, 0 );
		    if($trans==2){
		   		$tile->paintTransparentImage( 'white', 0, 0 );
		    }
		}
		$tile->writeImages($imgfile,1);
		
	}else{
		$text->paintTransparentImage( 'white', 0, 0 );
		$text->writeImages($imgfile,1);
	}

	//header( "Content-Type: image/gif" );
	//echo $tile->getImagesBlob();
	return $imgfile;
}
function com_gifs($image1,$image2){
	$gif1 = new Imagick($image1);
	$gif2 = new Imagick($image2);
	$comgif  = new Imagick();
	$comgif->setFormat("gif");
	$comWidth = $gif1->getImageWidth()+$gif2->getImageWidth();
	$comHeight = $gif1->getImageHeight();
	$gif1_delay = $gif1->getImageDelay();
	$gif2_delay = $gif2->getImageDelay();
	$gif1_number = $gif1->getNumberImages();
	$gif2_number = $gif2->getNumberImages();
	$len_sma = 0;
	$len_big = 0;
	
	$avg_delay = intval(($gif1_delay+$gif2_delay)/2);
//	echo $gif1_delay."=".$gif2_delay;
//	echo $gif1_number."=".$gif2_number;
//	die;
	if($gif1_number>$gif2_number){
			
		foreach ($gif1 as $g1){
			$len_big += $gif1_delay;
			foreach ($gif2 as $g2){
				$len_sma +=$gif2_delay;
				$comgif->newImage($comWidth,$comHeight,"transparent","gif");
				$comgif->compositeImage($g1,Imagick::COMPOSITE_OVER,0,0);
				$comgif->compositeImage($g2,Imagick::COMPOSITE_OVER,$g1->getImageWidth(),0);
				$comgif->setImageDelay( $avg_delay );
			}
		}	
	}else if($gif1_number<$gif2_number){
		
		foreach ($gif2 as $g2){
			foreach ($gif1 as $g1){
				$comgif->newImage($comWidth,$comHeight,"transparent","gif");
				$comgif->compositeImage($g1,Imagick::COMPOSITE_OVER,0,0);
				$comgif->compositeImage($g2,Imagick::COMPOSITE_OVER,$g1->getImageWidth(),0);
				$comgif->setImageDelay( $avg_delay );
			}
		}
	}else{
		foreach ($gif2 as $g2){
			foreach ($gif1 as $g1){
				$comgif->newImage($comWidth,$comHeight,"transparent","gif");
				$comgif->compositeImage($g1,Imagick::COMPOSITE_OVER,0,0);
				$comgif->compositeImage($g2,Imagick::COMPOSITE_OVER,$g1->getImageWidth(),0);
				$comgif->setImageDelay( $avg_delay );
			}
		}
	}
	
	header( "Content-Type: image/gif" );
	echo $comgif->getImagesBlob();
	//echo $gif2->getImagesBlob();

	
	
}
function web_button(){
	
	$im = new Imagick();
	$im->newImage(105,35,"transparent","png");
	
	$draw = new ImagickDraw();
	$draw->setFillColor("green");
	$draw->roundRectangle (0,0,100,30,5,5);
	$draw->setFillColor("white");
	//$draw->setFillAlpha( 0.21 );
//	$draw->bezier( array( 
//                    array( "x" => 10 , "y" => 25 ),
//                    array( "x" => 39, "y" => 49 ),
//                    array( "x" => 60, "y" => 55 ),
//                    array( "x" => 75, "y" => 70 ),
//                    array( "x" => 100, "y" => 70 ),
//                    array( "x" => 100, "y" => 10 ),
//                 ) ); 
	$gradient = new Imagick();
	$gradient->newPseudoImage(93,20,"gradient:transparent-white");
	
	

	$draw->setFillAlpha( 0.17 );
	$draw->roundRectangle (2,1,98,28,5,5);
	
	$draw->setFillColor("white");
	$draw->setFillAlpha( 0.17 );
	$draw->roundRectangle (4,4,95,8,3,3);
	$im->drawImage($draw);
	//$im->compositeImage($gradient,imagick::COMPOSITE_ATOP ,4,10);
	

	
	
	//$im->shadowImage(0.1, 0, 0,0);
	//$im->embossImage(0.9, -0.1);
	$im_clone = $im->clone();
	//$im_clone->setImageBorderColor ('red');
	//$im_clone->setImageOpacity(45);
	
	$canvas = new Imagick();
	$canvas->newImage(200,100,"white",'png');
	$canvas->compositeImage($im,imagick::COMPOSITE_OVER ,50,30);
	//$canvas->compositeImage($im_clone,imagick::COMPOSITE_OVER ,50,30);
	
	$im->trimImage( 0 );
	header("Content-Type: image/png");
	echo $canvas;
}
/**
 * 文字ps处理
 * @param string $text
 * @param string $fontColor
 * @param integer $fontSize
 * @param string $font
 * @return void
 */
function ps_fonts($text,$fontColor='red',$fontSize=20,$font="simsun.ttc"){
	$im = new Imagick();
	$im->setFormat("png");
	
	$draw = new ImagickDraw();
	$draw ->setFont($font);
	$draw->setFillColor($fontColor);
	$draw->setFontSize($fontSize);
	$draw->setGravity( Imagick::GRAVITY_NORTH  );
	
	$properties = $im->queryFontMetrics($draw, $text);
	$font_width = intval( $properties["textWidth"]+5 );
	$font_height = intval( $properties["textHeight"]+5 );
	
	$im->newImage($font_width,$font_height,'transparent');
	$im->annotateImage($draw,0,0,0,$text);
    
	$ellipse = new Imagick();
	$ellipse->newImage($font_width*2,$font_height*2,"transparent","png");
	$draw->setFillAlpha(0.3);
	$draw->ellipse (100,30,100,30,160,320);
	$ellipse->drawImage($draw);
	
	$gradient = new Imagick();
	$gradient_cl=$gradient->clone();
	$gradient->newPseudoImage($font_width,$font_height,"gradient:white-transparent");
	$gradient_cl->newPseudoImage($font_width,$font_height,"gradient:transparent-white");
	$im->compositeImage($gradient,imagick::COMPOSITE_ATOP,0,0);
	$ellipse->compositeImage($gradient,imagick::COMPOSITE_ATOP,0,0);

	$im->compositeImage($ellipse,imagick::COMPOSITE_OVER,10,10);
	header("Content-Type: image/png");
	echo $im;
	
}
function reflection($image){
	/* Read the image */
	$im = new Imagick($image);
	 
	/* Thumbnail the image */
	$im->thumbnailImage( 200, null );
	 
	/* Create a border for the image */
	$im->borderImage( "white", 5, 5 );
	 
	/* Clone the image and flip it */
	$reflection = $im->clone();
	$reflection->flipImage();
	 
	/* Create gradient. It will be overlayd on the reflection */
	$gradient = new Imagick();
	 
	/* Gradient needs to be large enough for the image
	and the borders */
	$gradient->newPseudoImage( $reflection->getImageWidth() + 10,
	                           $reflection->getImageHeight() + 10,
	                           "gradient:transparent-black"
	                        );
	 
	/* Composite the gradient on the reflection */
	$reflection->compositeImage( $gradient, imagick::COMPOSITE_OVER, 0, 0 );
	 
	/* Add some opacity */
	$reflection->setImageOpacity( 0.3 );
	 
	/* Create empty canvas */
	$canvas = new Imagick();
	 
	/* Canvas needs to be large enough to hold the both images */
	$width = $im->getImageWidth() + 40;
	$height = ( $im->getImageHeight() * 2 ) + 30;
	$canvas->newImage( $width, $height, "black", "png" );
	 
	/* Composite the original image and the reflection on the canvas */
	$canvas->compositeImage( $im, imagick::COMPOSITE_OVER, 20, 10 );
	$canvas->compositeImage( $reflection, imagick::COMPOSITE_OVER,
	                        20, $im->getImageHeight() + 10 );
	 
	/* Output the image*/
	header( "Content-Type: image/png" );
	echo $canvas;
}
/**
 * 彩字秀
 * @param string $string
 * @param string $glitter_gif
 * @param integer $fontSize
 * @param string $font
 * @return void
 */
function czxiu($string,$glitter_gif="glitter_blue.gif",$fontColor='red',$fontSize=50,$font='simhei.ttf'){
	$im = new Imagick( $glitter_gif );

	//$text = new Imagick();
	
	$draw = new ImagickDraw();
	//$draw->setGravity( Imagick::GRAVITY_CENTER );
	$draw->setFillColor( $fontColor );
	$draw->setFontSize( $fontSize );
	$draw->setFont( $font );
	
	$properties = $im->queryFontMetrics( $draw, $string );
	$width = intval( $properties["textWidth"] );
	$height = intval( $properties["textHeight"] );
	$len=mb_strlen($string,"UTF-8");
	$fontWidth = intval($width/$len);
	$tile = new Imagick();
	
	foreach ( $im as $frame )
	{

		$frame_width = $frame->getImageWidth();
		$frame_height = $frame->getImageHeight();
		
	    $tmp = new Imagick();
	    $tmp->newImage( $frame_width*$len, $frame_height, "transparent", "gif" );
	    $tile->addImage( $tmp->textureImage( $frame ) );		
		$w=14;
		
		for($i=0;$i<$len;$i++){
			$part = mb_substr($string,$i,1,"UTF-8");
			$tile->annotateImage( $draw, $w , $frame_height-15,0, $part );
			$w +=$frame_width;
			
		}
		
	}
	
	header( "Content-Type: image/gif" );
	echo $tile->getImagesBlob();
}


function splitGiftoImage($image,$font_array,$width=300,$height=200){
	global $imgfile;
	$imageObj = new Imagick();
	$imageObj->setFormat("gif");
	
	$im = new Imagick($image['file']);
	$imageObj->newImage($width*3,$height,'transparent','png');
	$x=0;
	$y=0;
	//print_r($im->getQuantumDepth  ());	
	foreach ( $im as $frame )
	{
	
		$imageObj->compositeImage($frame,Imagick::COMPOSITE_OVER,$x,$y);
		$x += $frame->getImageWidth();
	}
	
	//header( "Content-Type: image/gif" );
	//$imageObj->getImagesBlob();
	$imageObj->writeImages($imgfile,1);
}
/**
 *镜面效果 
 *@param string $image 
 */
function mirror($image){
	$im = new Imagick($image);
	
	$height = $im->getImageHeight();
	$width = $im->getImagewidth();
	
	$new_im = new Imagick();
	$new_im ->newImage($width,$height*2,"transparent","png");
	
	$im_clone = $im->clone();
	$im_clone->flipImage();
	
	
	$tile = new Imagick();
	$layer = createShadeImage($height,0,1);
	$tmp = new Imagick();
	$tmp->newImage( $width, $height, "transparent", "gif" );
    $tile->addImage( $tmp->textureImage( $layer ) );
	
	
	$im_clone->compositeImage($tile,Imagick::COMPOSITE_OVER   , 0, 0 );
	$new_im->compositeImage($im,Imagick::COMPOSITE_OVER,0,0);
	$new_im->compositeImage($im_clone,Imagick::COMPOSITE_OVER,0,$height);
	header( "Content-Type: image/png" );
	echo $new_im;
}
/**
 * create a gradient image 
 * @param integer $height
 * @param float $start
 * @param float $end
 * @param float $percent
 * @return void
 */
function createShadeImage($height,$start,$end,$percent=1){
	$im = new Imagick();
	
	$im ->newImage(10,$height,"white","png");
	$im ->setImageMatte( true ); 
	$it = $im->getPixelIterator();
	$i=$start;
	$middle = intval($height*$percent);
	$interal = ($end-$start)/$height*$percent;
	
	foreach( $it as $row => $pixels )
	{
	    foreach ( $pixels as $column => $pixel )
	    {
		 	$pixel->setColorValue(imagick::COLOR_OPACITY ,$i);
	    }
	    if($row>=$middle){
	   		$i=$i+$interal;
	    }else{
	    	$i=$i+($end-$start)/$height;
	    }
	    $it->syncIterator();
	}
//	header( "Content-Type: image/png" );
//	echo $im;
	return $im;
}
/**
 * replace one color to another color or transparent
 * @param imagick $im
 * @param string $color
 * @param string $replacecolor
 * @param float $opacity
 * @return void
 */
function replaceImageColor(& $im,$color='white',$replacecolor='black',$opacity=0.3){
	$im ->setImageMatte( true ); 
	$color_pix = new ImagickPixel($color);
	$it = $im->getPixelIterator();
	foreach( $it as $row => $pixels )
	{
	    foreach ( $pixels as $column => $pixel )
	    {
			$color  = $pixel->getColorValue (imagick::COLOR_ALPHA );
			if($pixel->isSimilar($color_pix,1.732)){
				$pixel->setColor( $replacecolor );
				$pixel->setColorValue(imagick::COLOR_OPACITY  ,$opacity);
			}
	    }
	    $it->syncIterator();
	}
	
}
/**
 * repeat a pic to a big size
 * @param imagick $new_im
 * @param imagick $image
 * @param integer $width
 * @param integer $height
 * @return void
 */
function repeatPic(&$new_image,$image,$width,$height){
	$new_image->newImage($width,$height,new ImagickPixel("transparent"));
	$org_width = $image->getImageWidth();
	$org_height = $image->getImageHeight();
	for ($i=0,$c=ceil($width%$org_width);$i<=$c;$i++){
		for ($j=0,$cc=ceil($height%$org_height);$j<=$cc;$j++){
			$new_image->compositeImage($image,Imagick::COMPOSITE_OVER,$org_width*$i,$org_height*$j);
		}
	}	
}
/**
 * 打字效果动画 
 * 
 */
function ani_print($text,$textColor='red',$fontsize=40,$font='simhei.ttf'){
	$animation = new Imagick();
	$animation->setFormat('gif');
	$textColor = new ImagickPixel($textColor);
	$color = new ImagickPixel('white');
		
	$draw = new ImagickDraw();
	$draw->setfont( $font );
	$draw->setFontSize($fontsize);
	$draw->setFillColor( $textColor );
	$draw->setGravity(imagick::GRAVITY_WEST );

	$properties = $animation->queryFontMetrics( $draw, $text );
	$width = intval( $properties["textWidth"] + 5 );
	$height = intval( $properties["textHeight"] );
	$strlen = mb_strlen($text,'UTF-8');
	
	
	$animation->newImage( $width, $height, $color );
	$draw->line(3,5,3,$height);
	$animation->drawimage($draw);
	$animation->setImageDelay( 50 );	
	$animation->newImage( $width, $height, $color );
	$animation->setImageDelay( 50 );
	$animation->newImage( $width, $height, $color );
	$animation->drawimage($draw);
	$animation->setImageDelay( 50 );
	
	for ( $i = 0; $i <= $strlen; $i++ )
	{
		$part = mb_substr( $text, 0, $i,"UTF-8" );
	    $animation->newImage( $width, $height, $color );
	    $draw->setGravity(imagick::GRAVITY_WEST );
	    $animation->annotateImage ($draw, 0, 0, 0, $part );
	    $animation->setImageDelay( 20 );
	}
	header( "Content-Type: image/gif" );
	echo $animation->getImagesBlob();
}
/**
 * 发光 动画
 * 
 */
function ani_grow($text,$textColor='red',$growColor='red',$fontsize=40,$font='simhei.ttf'){
	$animation = new Imagick();
	$animation->setFormat("gif");
	$textColorObj = new ImagickPixel($textColor);
	$color = new ImagickPixel('white');
	$growColor = new ImagickPixel($growColor);
		
	$draw = new ImagickDraw();
	$draw->setfont( $font );
	$draw->setFontSize($fontsize);
	
	$draw->setGravity(imagick::GRAVITY_WEST );

	$properties = $animation->queryFontMetrics( $draw, $text );
	$width = intval( $properties["textWidth"] + 5 );
	$height = intval( $properties["textHeight"] );
	$strlen = mb_strlen($text,'UTF-8');
	for ( $i = 1; $i <= 5; $i++ )
	{
		$animation->newImage( $width, $height, $color );
		$im = new Imagick();
		$im->newImage( $width, $height, new ImagickPixel( "transparent" ) );//new ImagickPixel( "transparent" )
		$draw->setFillColor( $textColorObj );
		$im->annotateImage ($draw,  0, 0, 0, $text );
		$im_blur = new Imagick();
		$im_blur->newImage( $width, $height, new ImagickPixel( "transparent" ) );
		$draw->setFillColor( $growColor );
		$im_blur->annotateImage($draw, 0, 0, 0, $text);
		$im_blur->blurImage(4,$i);
		
		$im_blur->compositeImage( $im, Imagick::COMPOSITE_OVER, 0, 0 );
		$animation->compositeImage( $im_blur, Imagick::COMPOSITE_OVER, 0, 0 );
  
    	$animation->setImageDelay( 30 );
	}
	header( "Content-Type: image/gif" );
	echo $animation->getImagesBlob();
}
/**
 * 边框动画
 * 
 */
function ani_border($text,$textColor,$borderColor,$fontsize=40,$font='simhei.ttf'){
	$animation = new Imagick();
	$animation->setFormat("gif");
	
	$textColor = empty($textColor)?'red':$textColor;
	$textColor = new ImagickPixel($textColor);
	$borderColor = new ImagickPixel($borderColor);
	$color = new ImagickPixel('white');
		
	$draw = new ImagickDraw();
	$draw->setfont( $font );
	$draw->setFontSize($fontsize);
	$draw->setFillColor( $textColor );
	$draw->setGravity(imagick::GRAVITY_WEST );

	$properties = $animation->queryFontMetrics( $draw, $text );
	$width = intval( $properties["textWidth"] + 5 );
	$height = intval( $properties["textHeight"] );
	$strlen = mb_strlen($text,'UTF-8');
	
	for ( $i = 1; $i <= 5; $i++ )
	{
	    /* Create a new frame */
	    $animation->newImage( $width, $height, $color );
		$draw->setStrokeColor  ($borderColor);
		//$draw->setStrokeOpacity  ( $i/10);
		$draw->setStrokeWidth  ($i/3);
		$animation->annotateImage ($draw,  0, 0,0, $text );
	    $animation->setImageDelay( 30 );
	}
	header( "Content-Type: image/gif" );
	echo $animation->getImagesBlob();
}
/**
 * 字体倒影在水面的波纹动画 
 * @param imagick $animate
 * @param string $text
 * @param string $textColor
 * @param integer $fontSize
 * @param string $font
 * @return void
 */
function ani_wave($text,$textColor='red',$fontSize=40,$font='simhei.ttf'){
	$animate = new Imagick();
	$animate->setFormat("gif");
	$textColor = new ImagickPixel($textColor);
	$trans = new ImagickPixel('white');
	
	$draw = new ImagickDraw();
	$draw->setfont( $font );
	$draw->setFontSize($fontSize);
	$draw->setFillColor( $textColor );
	
	$properties = $animate->queryFontMetrics( $draw, $text );
	$width = intval( $properties["textWidth"] + 5 );
	$height = intval( $properties["textHeight"] );
	$strlen = mb_strlen($text,'UTF-8');
	
	for ( $i = 0; $i <= $strlen; $i++ )
	{
		$animate->newImage( $width, $height*2, $trans );

		$im = new imagick();
		$im->newImage( $width, $height, $trans );
		$draw->annotation(0, $height-5, $text );
		$im->drawimage($draw);
		
		$im_clone = $im->clone();
		$im_clone->flipImage ();
		$im_clone->modulateImage  (80,10,100);
		$im_clone->waveImage   (2,(10+$i));
		
		$animate->compositeImage( $im, Imagick::COMPOSITE_OVER , 0, 0 );
		$animate->compositeImage( $im_clone, Imagick::COMPOSITE_OVER , 0, $height );
		$animate->setImageDelay( 30 );
	}
		
	header( "Content-Type: image/gif" );
	echo $animate->getImagesBlob();
}
?> 