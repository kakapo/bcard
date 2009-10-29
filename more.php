<?php 

if($_GET['type']=='1'){
?>
<input type="hidden" id="xpos_avatarTab" name="xpos_avatarTab" value="0" />
<input type="hidden" id="ypos_avatarTab" name="ypos_avatarTab" value="0" />
<ul>
<label>
图片URL:<input type="text" name="avatar_url" id="avatar_url" size="50">
<img src="" id="avatar_url_img" width="60" height="60" style="display:none">
<input type="button" value="获取" onclick="getUrlImage('avatar_url','avatarTab');">
<input type="radio" name="avatarimg" id="avatar_url_radio" value="" onclick="addgif('avatar_url_img','avatarTab');">
</label>
<br>
<label>
上传图片:
<iframe width="520" height="25" src="upload.php?type=avatar" allowTransparency="true"  frameborder="0" scrolling="No" style="background-color:transparent"></iframe>
<input type="radio" name="avatarimg" id="avatar_file_radio" value="" onclick="addgif('avatar_file_img','avatarTab');">
<img src="" id="avatar_file_img" width="60" height="60" style="display:none">
</label>

	<?php
	$gif_arr = glob("avatar/*");
	foreach ($gif_arr as $k=>$gif){
	if($k<100){
	$gif_name = substr($gif,strpos($gif,"/")+1);
	?>
	<li>
	<input type="radio" name="avatarimg" id="avatarimg" value="<?=$gif_name?>" onclick="addgif('avatar_<?=$k?>','avatarTab');">
	<br><img id="avatar_<?=$k?>" src="<?=$gif?>" width="60" height="60"/>
	</li>
	<? 
	}
	}
	?>
</ul>
<? }?>
<?php 
if($_GET['type']=='2'){
?>
<ul>
<label>
图片URL:<input type="text" name="bg_url" id="bg_url" size="50">
<img src="" id="bg_url_img" width="60" height="60" style="display:none">
<input type="button" value="获取" onclick="getUrlImage('bg_url','avatarTab');">
<input type="radio" name="bgimg" id="bg_url_radio" value="" onclick="changeBgImage(this.value,1);">
</label>
<br>
<label>
上传图片:
<iframe width="520" height="25" src="upload.php?type=bg" allowTransparency="true"  frameborder="0" scrolling="No" style="background-color:transparent"></iframe>
<input type="radio" name="bgimg" id="bgimg_file_radio" value="" onclick="changeBgImage(this.value,1);">
<img src="" id="bgimg_file_img" width="60" height="60" style="display:none">
</label>
	    <?php
	    $gif_arr = glob("bg/*");
	    foreach ($gif_arr as $k=>$gif){
	    	if($k<200){
	    	$gif_name = substr($gif,strpos($gif,"/")+1);
	    ?>
		 <li>
		 <input type="radio" name="bgimg" id="bgimg" value="<?=$gif_name?>" onclick="changeBgImage(this.value,0);">
		<br><img id="bg_<?=$k?>" src="<?=$gif?>" width="60" height="60"/>
		 </li>
		 <? 
	    	}
	    	}
		 ?>
		</ul>
<? }?>



<?php 
if($_GET['type']=='3'){
?>
<input type="hidden" id="xpos_gifTab" name="xpos_gifTab" value="0" />
<input type="hidden" id="ypos_gifTab" name="ypos_gifTab" value="0" />
<ul>

<label>
图片URL:<input type="text" name="smallgif_url" id="smallgif_url" size="50">
<img src="" id="smallgif_url_img" style="display:none">
<input type="button" value="获取" onclick="getUrlImage('smallgif_url','avatarTab');">
<input type="radio" name="smallgif" id="smallgif_url_radio" value="" onclick="addgif('smallgif_url_img','gifTab');">
</label>
<br>
<label>
上传图片:
<iframe width="520" height="25" src="upload.php?type=smallgif" allowTransparency="true"  frameborder="0" scrolling="No" style="background-color:transparent"></iframe>
<input type="radio" name="smallgif" id="smallgif_file_radio" value="" onclick="addgif('smallgif_file_img','gifTab');">
<img src="" id="smallgif_file_img" style="display:none">
</label>

<?php
$gif_arr = glob("img/*.gif");
foreach ($gif_arr as $k=>$gif){
	if($k<100){
	$gif_name = substr($gif,strpos($gif,"/")+1);
?>
 <li>
 <input type="radio" name="smallgif" id="smallgif" value="<?=$gif_name?>" onclick="addgif('gif_<?=$k?>','gifTab');">
 <br><img id="gif_<?=$k?>" src="<?=$gif?>"/>
 </li>
 <? 
	}
	}
 ?>
</ul>
<? }?>
<?php 
if($_GET['type']=='4'){
?>
<ul>

<label>
图片URL:<input type="text" name="glitter_url" id="glitter_url" size="50">
<img src="" id="glitter_url_img" width="60" height="60" style="display:none">
<input type="button" value="获取" onclick="getUrlImage('glitter_url','avatarTab');">
<input type="radio" name="glitter" id="glitter_url_radio" value="">
</label>
<br>
<label>
上传图片:
<iframe width="520" height="25" src="upload.php?type=glitter" allowTransparency="true"  frameborder="0" scrolling="No" style="background-color:transparent"></iframe>
<input type="radio" name="glitter" id="glitter_file_radio" value="">
<img src="" id="glitter_file_img" width="60" height="60" style="display:none">
</label>
<?php
$gif_arr = glob("glitter/*.gif");
foreach ($gif_arr as $k=>$gif){
	if($k<100){
	$gif_name = substr($gif,strpos($gif,"/")+1);
?>
 <li>
 <input type="radio" name="glitter" id="glitter" value="<?=$gif_name?>">
 <br><img id="glitter_<?=$k?>" src="<?=$gif?>"/>
 </li>
 <? 
	}
	}
 ?>
</ul>
<? }?>