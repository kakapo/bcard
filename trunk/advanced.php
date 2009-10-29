<?php session_start();
$text = isset($_GET['text'])?$_GET['text']:"";
$tmp_font_img = "font_".session_id().".gif";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
// FORM, select color & color change?

function TOI_setColor(n, c)
{
  var input = null;
  var img = null;
  var val = null;
 
  img = document.getElementById('BtnColor'+n);
  input = document.getElementById('color'+n);
      
  if (!input || !img) return;
  if (typeof c != 'undefined')
  {
    val = c;
  }
  else
  {
    var url = 'selcolor.html?&n=' + n+'&color=' + input.value.substring(1);
   
    if (typeof showModalDialog == 'undefined')
    {
      window.open(url, '_selcolor', 'status=no,width=555,height=410');
      return;
    }
    else
    {
    	
      val = showModalDialog(url, window, 'status:no;dialogWidth:565px;dialogHeight:480px;help:no');
    }
  }
  if (val && val != input.value) 
  {
    input.value = val;
    img.style.backgroundColor = val;
  }
}
function TOI_callback(type, msg)
{
  	document.getElementById('gopreview').disabled = false;
  	document.getElementById('gopreview').value = '生成图片!';

  // update the image source
  if (type == 'ok')
  {
    document.getElementById('fontImage').src = msg;
  }
  
}
function TOI_submit()
{
  document.getElementById('fontImage').title = '图片生成中,请稍候...';
  var fontform = document.getElementById("fontform");
  document.getElementById('gopreview').disabled = true;
  document.getElementById('gopreview').value = '请稍候...';
  return true;
}
function apply2bcard(){
	window.opener.document.getElementById('preview0').checked = true;
	window.opener.document.getElementById('pre_pic0').innerHTML ="<img src='tmp/<?=$tmp_font_img?>'>";
	window.close();
}
</script>
<title>高级字体 Advanced Text</title>
<style>
#demo{border:1px #CCCCCC solid;margin:10px;padding:10px;height:100px;width:300px;}
#div2{border:1px #CCCCCC solid;margin:10px;padding:10px}
#card{float:left;width:300px;height:200px;margin:10px;border:1px #CCCCCC solid;}
#div_right{float:left;margin-left:20px;width:60%;height:300px;}
#avatar{width:100%;height:50px;border:1px #ccc solid}
#bgimage{width:100%;height:100px;border:1px #ccc solid}
#small_pic{width:100%;height:200px;border:1px #ccc solid}
.line{padding:10px;margin:5px;}
.span{padding:5px;}
.demoTabCss{cursor:move;position:absolute;z-index:99;border:1px dotted #f00;}
li{float:left;margin:3px;border:1px #ccc solid;}
</style>
<body>
 <form name="fontform" id="fontform" method="post" target="_fontFrm" action="font2gif.php" enctype="multipart/form-data" onSubmit="return TOI_submit();">
<div id="demo"><img src="blank.gif" id="fontImage" /></div>
 <div style="clear:both"></div>
<div id="div2">
<span class="span"><input type="submit" id="gopreview" value="预览 Preview" /></span>
<span class="span"><input type="button" name="generate" id="generate" value="应用到所有文字! Apply to All" onclick="apply2bcard();"/></span>
</div>
<div class="line">文字内容(Content)：<textarea name="text" rows="5" cols="20"><?=$text?></textarea></div>
<div class="line">文字样式选择(Style)：
            <select name="font" size="1" onchange="document.getElementById('fontImg').src='scale.php?f='+this.value;">
              <option value="byxs3500.ttf">博洋行书(简体)</option>
              <option value="bycs3500.ttf">博洋草书</option>
              <option value="fzszjw.ttf">方正水柱简体</option>
              <option value="FZJZJ.ttf">方正剪纸简体</option>
              <option value="FZCATOONJ.ttf">方正卡通简体</option>
              <option value="FZPHJ.ttf">方正平和简体</option>
              <option value="BYLT3500.ttf">博洋柳体3500</option>
            </select>&nbsp;<img src="scale.php?f=byxs3500.ttf" id="fontImg" align="absMiddle" />  </div>
<div class="line"> 文字大小 (Size)：<select id="size0" name="size">
              <option value="12">12</option>
              <option value="12">12</option>
              <option value="14" >14</option>
              <option value="16">16</option>
              <option value="18">18</option>
              <option value="20">20</option>
              <option value="24" >24</option>
              <option value="26">26</option>
              <option value="36" selected>36</option>
              <option value="46" >46</option>
              <option value="56">56</option>
            </select>px</div>
 <div class="line"> 文字角度(Angle)：<input type="text" name="angle" value="0" size="1"></div>
 <div class="line"> 文字边框 (Border)：厚度(Strength)：<select id="border" name="border">
              <option value="0" selected>0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>px
             颜色(Border Color)：<input type="text" id="color0" name="color0" size="4" value="#993366" /><img id="BtnColor0" style="background-color:#993366" src="rect.gif" border="0" title="选取颜色" align="absMiddle" onClick="TOI_setColor(0)">  
            </div>     
     
      <div class="line">文字颜色 (Font Color)： 
      <input type="radio" name="style" value="solid" checked><input type="text" id="color1" name="color1" value="#993366"><img id="BtnColor1" style="background-color:#993366" src="rect.gif" border="0" title="选取颜色" align="absMiddle" onClick="TOI_setColor(1)">
      </div>
     <div class="line">文字背景透明(Background Transparent)：<input type="radio" name="transparent" value="1"> &nbsp;&nbsp;&nbsp;&nbsp;文字透明(Front Transparent)：<input type="radio" name="transparent" value="2"></div>    
     <div class="line">文字背景图片(Background Image)：<font color="red">请先选择一种透明效果,否则背景看不见(Please select one of transparent styles.)</font>
      <br>
      <ul>
          <?php
    $gif_arr = glob("glitter/*");
    foreach ($gif_arr as $k=>$gif){
    	if($k<50){
    	$gif_name = substr($gif,strpos($gif,"/")+1);
    ?>
      <li><input type="radio" name="style" value="<?=$gif_name?>"><img src="<?=$gif?>"></li>
      <?}
    }?>
    </ul>
      </div>     
</form>
<div style="display:none;">
  <iframe width="500" height="200" id="genFrm" name="_fontFrm" src="about:blank"></iframe>
</div>
</body>
</html>
