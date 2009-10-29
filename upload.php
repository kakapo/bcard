<?php
include_once("ImageUploader.class.php");
session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="background-color: transparent;margin:0;padding:0">
<form method="POST" enctype="multipart/form-data">
 <!-- MAX_FILE_SIZE must precede the file input field -->
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<input type="file" name="img_file" id="avatar_file" size="50">
<input type="submit" value="upload">
</form>
</body>
<?php

	
	if(!empty($_FILES['img_file'])){
		$iu = new ImageUploader();
		$iu->setFileValues($_FILES['img_file']);
		if($iu->validateUploaded() && $iu->validateFileType()){
			$tmpfile = "upload_".session_id().time().strrchr ($iu->mFileName,'.');
			$res = $iu->upload(dirname(__FILE__)."/tmp/",$tmpfile);
			if($res){
				switch ($_GET['type']){
					case "avatar":
		?>
				<script>
				parent.document.getElementById('avatar_file_img').style.display= '';;
				parent.document.getElementById('avatar_file_img').src="tmp/<?=$tmpfile?>";
				parent.document.getElementById('avatar_file_radio').value="tmp/<?=$tmpfile?>";
				parent.document.getElementById('avatar_file_radio').checked = true;
				parent.window.addgif("avatar_file_img","avatarTab");
				</script>
		<?
					break;
			
					case "bg":
		?>
				<script>
				parent.document.getElementById('bgimg_file_img').style.display= '';;
				parent.document.getElementById('bgimg_file_img').src="tmp/<?=$tmpfile?>";
				parent.document.getElementById('bgimg_file_radio').value="tmp/<?=$tmpfile?>";
				parent.document.getElementById('bgimg_file_radio').checked = true;
				parent.window.changeBgImage(parent.document.getElementById('bgimg_file_radio').value,1);
				</script>
		<?					
					break;
					case  "smallgif":
		?>
				<script>
				parent.document.getElementById('smallgif_file_img').style.display= '';;
				parent.document.getElementById('smallgif_file_img').src="tmp/<?=$tmpfile?>";
				parent.document.getElementById('smallgif_file_radio').value="tmp/<?=$tmpfile?>";
				parent.document.getElementById('smallgif_file_radio').checked = true;
				parent.window.addgif('smallgif_file_img','gifTab');
				</script>
		<?						
					break;
					
					case "glitter":
			?>
				<script>
				parent.document.getElementById('glitter_file_img').style.display= '';;
				parent.document.getElementById('glitter_file_img').src="tmp/<?=$tmpfile?>";
				parent.document.getElementById('glitter_file_radio').value="tmp/<?=$tmpfile?>";
				parent.document.getElementById('glitter_file_radio').checked = true;
				//parent.window.addgif('smallgif_file_img','gifTab');
				</script>
		<?						
					break;
					
				}
			}
		
		}else{
	?>
		<script>
		alert("注意: 只支持小于2M的png, gif, jpg图片!");
		</script>
	<?
		}
	}
	?>