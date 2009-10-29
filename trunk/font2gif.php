<?php
session_start();
include("functions.php");
//print_r($_POST);die;
$tmp_font_img = "font_".session_id().".gif";
$imgfile = dirname(__FILE__)."/tmp/".$tmp_font_img;
$string = empty($_POST['text'])?"空":$_POST['text'];
$style = empty($_POST['style'])?"":$_POST['style'];
$transparent = isset($_POST['transparent'])?$_POST['transparent']:0;
if($style=='solid') {
	$color =$_POST['color1']; 
}else{
	$style = "glitter/".$style;
	if($transparent==2){
		$color='black';
	}else{
		$color=$_POST['color1'];
	}
}
$font = "font/".$_POST['font'];
$angle =$_POST['angle'];
$size = $_POST['size'];
$border =$_POST['border'];
$bcolor =$_POST['color0'];

$_SESSION['advanced_set'] = array("string"=>$string,"style"=>$style,"color"=>$color,"size"=>$size,"angle"=>$angle,"font"=>$font,"border"=>$border,"bcolor"=>$bcolor,"transparent"=>$transparent);
glitter_fonts($string,$style,$color,$size,$font,$border,$bcolor,$transparent,$angle);
?>
<script>
parent.window.TOI_callback("ok","tmp/<?=$tmp_font_img?>?<?=microtime()?>");
</script>