<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>名片系统 Bussiness Card System beta 0.9.5 kakapo</title>
<meta name="author" content="kakapowu@gmail.com">
<script type="text/javascript" src="javascripts/prototype.js"> </script> 
<script type="text/javascript" src="javascripts/effects.js"> </script> 
<script type="text/javascript" src="javascripts/window.js"> </script> 

<link href="themes/default.css" rel="stylesheet" type="text/css"> 
<!-- Add this to have a specific theme--> 
<link href="themes/alphacube.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="toi.js"></script>
<script type="text/javascript" src="add.js"></script>
<style>
#container{width:100%;height:650px}
#div_left{float:left;border:0px #CCCCCC solid;margin:10px;padding:10px;height:620px;}
#div_right{ position:absolute;float:right;border:0px #CCCCCC solid;margin:10px;width:800px;height:600px;}

#div_bottom{border:1px #CCCCCC solid;margin:10px;padding:5px}
#card{width:300px;height:200px;margin:10px;border:1px #CCCCCC solid;background-color:#ccc}
#button_div{width:300px;height:50px;margin:10px;}

#avatar{width:100%;height:125px;}
#bgimage{width:100%;height:120px;margin-top:10px;}
#small_pic{width:100%;height:160px;margin-top:10px;}
#glitter_div{width:100%;height:160px;margin-top:10px;}
.line{padding:5px;margin:5px;}
.span{padding:5px;}
body{margin:0;padding:0;font-size:12px}
.demoTabCss{cursor:move;position:absolute;z-index:99;border:1px dotted #f00;}
li{float:left;margin:3px;border:1px #ccc solid;list-style-type:none}
</style>
</head>

<body>

 <form name="dform" id="dform" method="post" target="_genFrm" action="tm.php" enctype="multipart/form-data" onSubmit="return TOI_submit();">
      <input type="hidden" name="mserver" id="mserver" value="yes"/>
<div id="container">     
	<div id="div_left">
		  <div id="card"> <img src="blank.gif" id="backImage" border="1" width="300" height="200"/></div>
		  <div id="button_div">
			  <span class="span">
			  长(Width):<input type="text" name="card_width" id="card_width" value="300" size="2">
			  宽(Height):<input type="text" name="card_height" id="card_height" value="200" size="2">
			  <input type="button" value="改变(Resize)" onclick="changeCardSize();">
			  <br>
			  
			  <input type="button" value="复制到QQ(Copy)" name="bcopy" id="BtnCopy" onclick="CopyImage(document.getElementById('backImage'))" disabled />
			  <input type="button" value="保存到本地(Save)" name="bsave" id="BtnSave" onclick="SaveImage(document.getElementById('backImage'))" disabled />
			  <br />
			  <br />
			 <input type="submit" name="generate" id="generate" value="生成图片(Generate)" />
		     <input type="button" name="reset" id="reset" value="重调!(Reset)" onclick="TOI_reset();" /></span>
		  </div>
		  
	 </div>
	  
	  <div id="div_right">
		  	<div id="avatar">
			  	<input type="hidden" id="xpos_avatarTab" name="xpos_avatarTab" value="0" />
			    <input type="hidden" id="ypos_avatarTab" name="ypos_avatarTab" value="0" />
				  	<ul>
						 <?php
				    $gif_arr = glob("avatar/*");
				    foreach ($gif_arr as $k=>$gif){
				    	if($k<6){
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
					
			</div>
			<div id="bgimage">
				<ul>
			    <?php
			    $gif_arr = glob("bg/*");
			    foreach ($gif_arr as $k=>$gif){
			    	if($k<6){
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
			
			</div>
			<div id="small_pic">
				<input type="hidden" id="xpos_gifTab" name="xpos_gifTab" value="0" />
			    <input type="hidden" id="ypos_gifTab" name="ypos_gifTab" value="0" />
			    <ul>
			    <?php
			    $gif_arr = glob("img/*.gif");
			    foreach ($gif_arr as $k=>$gif){
			    	if($k<10){
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
			
			</div>
			<!-- end small_pic-->
			<div id="glitter_div">
				
			    <ul>
			    <?php
			    $gif_arr = glob("glitter/*.gif");
			    foreach ($gif_arr as $k=>$gif){
			    	if($k<6){
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
			
			</div>
			<!-- end small_pic-->
			
	   </div>
	<!-- end div_right-->
	</div>
	<!-- end div1-->
</div>
<!-- end container-->
<div id="div_bottom">
	<div class="line">
	新增文字(Add Text) 
		<table id="sqd" width="95%" border="0" cellpadding="0" cellspacing="3" >
	        <tr>
	          <td height="25">
	        	   0
	            </td>         
	            <td >
	               <input type="radio" name="number" id="number" value="0">
	               <input type="check" name="check[]" value="0" checked style="display:none">
	               <input type="hidden" id="xpos0" name="xpos0" value="5" />
	      		   <input type="hidden" id="ypos0" name="ypos0" value="18" />
	            </td>
	          
	          <td>
	            内容(Content)：<textarea id="text0" name="text0" rows="3" cols="20" onKeyUp="TOI_update(0)">某某某先生</textarea></td>
	          <td>
	            文字大小(Size)：<select size="1" id="size0" name="size0" onChange="TOI_update(0)">
	              <option value="12">12</option>
	              <option value="14" selected>14</option>
	              <option value="16">16</option>
	              <option value="18">18</option>
	              <option value="20">20</option>
	              <option value="24">24</option>
	              <option value="26">26</option>
	              <option value="36">36</option>
	              <option value="46">46</option>
	              <option value="52">52</option>
	              <option value="60">60</option>
	
	            </select>px</td>
	          <td>
	            文字颜色(Color)：<input type="text" id="color0" name="color0" size="4" value="#993366" /><img id="BtnColor0" style="background-color:#993366" src="rect.gif" border="0" title="选取颜色" align="absMiddle" onClick="TOI_setColor(0)"></td>
	          <td>
	            文字角度(Angle)：<input type="text" id="angle0" name="angle0" size="1" value="0" onkeyup="rotate('demoTab0',this.value)"/>
	           </td>
	
	          <td>
	           <input type="button" value="高级(Advanced)" onclick="openwin('advanced.php?text='+encodeURI(document.getElementById('text0').value));"/><input type="checkbox" id="preview0" name="preview0" value="1">
	            <span id="pre_pic0"></span></td>
	          
	        </tr>
	        
	</table>
	</div>


	<div class="line">
		<span class="span"><input type="button" name="add" onClick='add_row()'; value="添加新项目(Add New Item)" /></span>
		<span class="span"><input type="button" name="del" onClick='del_row()'; value="删除(Delete)" /></span>
	</div>
</div>
<!-- end div2-->
</form>


<div id="demoTab0" class="demoTabCss">
<span id="demoText0"></span>
</div>

<script language="javascript">
document.getElementById('backImage').onload = function() { 
  document.getElementById('backImage').onload = '';
  TOI_init();
}
</script>
<div style="display:block;position:absolute;left:50px;top:400px;width:300px;height:150px;">
  <iframe width="300" height="100" id="genFrm" name="_genFrm" src="about:blank"></iframe>
</div>
</body>
</html>
<script type="text/javascript">
win1_status = 0;
win2_status = 0;
win3_status = 0;
win4_status = 0;

win1 = new Window('1', {draggable:false,closable:false,maxHeight:600,maxWidth:800,className: "dialog", title: "头像(avatar)", width:380, height:250, top:0, left:0
,parent:$('div_right')});
//win1.getContent().innerHTML = "<h1>1</h1>";
win1.show();

win1.setContent('avatar', false, false);
win1.setConstraint(true, {left:0, right:0})
win1.toFront();
</script>

<script type="text/javascript">
win2 = new Window('2', {draggable:false,closable:false,maxHeight:600,maxWidth:800,className: "dialog", title: "背景图(background) ", width:380, height:250, top:0, left:400
,parent:$('div_right')});

win2.setContent('bgimage', false, false);
win2.setConstraint(true, {left:0, right:0})
win2.show();
win2.toFront();

</script>
 
<script type="text/javascript">
win3 = new Window('3', {draggable:false,closable:false,maxHeight:600,maxWidth:800,className: "dialog", title: "小动画(small GIF animation image)  ", width:380, height:250, top:300, left:0
,parent:$('div_right')});
win3.setContent('small_pic', false, false);
win3.setConstraint(true, {left:0, right:0})
win3.show();
win3.toFront();
</script>

<script type="text/javascript">
win4 = new Window('4', {draggable:false,closable:false,maxHeight:600,maxWidth:800,className: "dialog", title: "闪光(glitter image) ", width:380, height:250, top:300, left:400
,parent:$('div_right')}); 
win4.setContent('glitter_div', false, false);
win4.setConstraint(true, {left:0, right:0})
win4.show();
win4.toFront();

//win5 = new Window('5', {draggable:false,closable:false,maxHeight:600,maxWidth:1000,className: "dialog", title: "新增文字(Add Text) ", width:1000, height:200, top:620, left:40}); 
//win5.setContent('div_bottom', false, false);
//win5.setConstraint(true, {left:0, right:0})
//win5.show();
//win5.toFront();

//WindowStore.show(win4);
//WindowStore.init();
// Set up a windows observer, check ou debug window to get messages
	var myObserver = {
		
		onMaximize: function(eventName, win) {
			var opt = {
			    // Use POST
			    method: 'get',
			    parameters:'type='+win.getId(),
			    // Send this lovely data
			    //postBody: escape('thisvar=true&thatvar=Howdy&theothervar=2112'),
			    // Handle successful response
			    onSuccess: function(t) {
			        //alert(t.responseText);
			    },
			    // Handle 404
			    on404: function(t) {
			        alert('Error 404: location "' + t.statusText + '" was not found.');
			    },
			    // Handle other errors
			    onFailure: function(t) {
			        alert('Error ' + t.status + ' -- ' + t.statusText);
			    }
			}

			if(win==win1 && win1_status==0){
				//win1.setHTMLContent("aaa");
				win1.setAjaxContent("more.php",opt);
				win1_status =1;
			}			
			if(win==win2 && win2_status==0){
				//win2.setHTMLContent("bbb");
				win2.setAjaxContent("more.php",opt);
				win2_status =1;
			}			
			if(win==win3 &&win3_status==0){
				//win3.setHTMLContent("ddd");
				win3.setAjaxContent("more.php",opt);
				win3_status =1;
			}			
			if(win==win4 &&win4_status==0){
				win4.setAjaxContent("more.php",opt);
				win4_status =1;
			}
			//alert(win1.getId());
			//alert(win2.getId());
			//debug(eventName + " on " + win.getId())
		}

	}
	Windows.addObserver(myObserver);
</script>
