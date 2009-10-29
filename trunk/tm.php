<?php
session_start();
include("functions.php");
//print_r($_SESSION);die;
$time = time();
$imgfile = dirname(__FILE__)."/tmp/bcard_".session_id().$time.".gif";

// small gif
$img_dir = dirname(__FILE__)."/img/";
$img = (!empty($_POST['smallgif']))?$_POST['smallgif']:"blank.gif";
$image = array('file'=>$img_dir.$img,'x'=>$_POST['xpos_gifTab'],'y'=>$_POST['ypos_gifTab']);
if(preg_match("/http:\/\//ism",$img)){
	$image['file'] = $img;
}elseif(preg_match("/tmp\/upload_/ism",$img)){
	$image['file'] = dirname(__FILE__)."/".$img;
}

// avatar 
$avatar = (!empty($_POST['avatarimg']))?$_POST['avatarimg']:"0000.gif";
$avatarimg = array("file"=>dirname(__FILE__)."/avatar/".$avatar,"x"=>$_POST['xpos_avatarTab'],"y"=>$_POST['ypos_avatarTab']);
if(preg_match("/http:\/\//ism",$avatar)){
	$avatarimg['file'] = $avatar;
}elseif(preg_match("/tmp\/upload_/ism",$avatar)){
	$avatarimg['file'] = dirname(__FILE__)."/".$avatar;
}
//print_r($avatarimg);

// bgimg
$bgimg = !empty($_POST['bgimg'])?$_POST['bgimg']:"";
if(preg_match("/http:\/\//ism",$bgimg)){
	$bgimg = $bgimg;
}elseif(preg_match("/tmp\/upload_/ism",$bgimg)){
	$bgimg = dirname(__FILE__)."/".$bgimg;
}elseif(!empty($bgimg)){
	$bgimg = dirname(__FILE__)."/bg/".$bgimg;
}

// glitter
$frontimg = !empty($_POST['glitter'])?$_POST['glitter']:"";
if(preg_match("/http:\/\//ism",$frontimg)){
	$frontimg = $frontimg;
}elseif(preg_match("/tmp\/upload_/ism",$frontimg)){
	$frontimg = dirname(__FILE__)."/".$frontimg;
}elseif(!empty($frontimg)){
	$frontimg = dirname(__FILE__)."/glitter/".$frontimg;
}
// font

foreach ($_POST['check'] as $i){
   $advanced = (isset($_POST['preview'.$i]))?$_POST['preview'.$i]:"";
   $font_prep[]=array("x"=>$_POST['xpos'.$i],'y'=>$_POST['ypos'.$i],"text"=>$_POST['text'.$i],"color"=>$_POST['color'.$i],"size"=>$_POST['size'.$i],'angle'=>$_POST['angle'.$i],
   "font"=>"font/simsun.ttc","advanced"=>$advanced,"advanced_set"=>$_SESSION['advanced_set']);
  
}

$card_width = !empty($_POST['card_width'])?$_POST['card_width']:300;
$card_height = !empty($_POST['card_height'])?$_POST['card_height']:200;

//print_r($font_prep);die;
if($_POST['split']==1){
	splitGiftoImage($image,$font_prep,$card_width,$card_height);
}else{
	bcard_hecheng($image,$font_prep,$card_width,$card_height,$avatarimg,$bgimg,$frontimg);
}//


?>
<script>
parent.window.TOI_callback("ok","tmp/bcard_<?=session_id().$time?>.gif");
</script>