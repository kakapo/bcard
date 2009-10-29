var j=0;

function add_row(){
	  if (document.layers) {
	     sqd = document.layers["sqd"];
	  } else if (document.all) {
	     //sqd = sqd;
	  } else if (document.getElementById) {
	     sqd = document.getElementById("sqd");
	  }
	  
	j=sqd.rows.length;
	newRow=sqd.insertRow(-1);
	newcell=newRow.insertCell(0);
	newcell.innerHTML=j;
	newcell=newRow.insertCell(1);
	newcell.innerHTML ='<input type="radio" name="number" id="number" value="'+j+'"><input type="check" name="check[]" value="'+j+'" checked style="display:none"><input type="hidden" id="xpos'+j+'" name="xpos'+j+'" value="0" /><input type="hidden" id="ypos'+j+'" name="ypos'+j+'" value="0" />'
	newcell=newRow.insertCell(2);
	
	newcell.innerHTML='内容(Content)：<textarea id="text'+j+'" name="text'+j+'" rows="3" cols="20" onKeyUp="TOI_update('+j+')">'+j+'</textarea>';
	newcell=newRow.insertCell(3);
	
	newcell.innerHTML='文字大小(Size)：<select size="1" id="size'+j+'" name="size'+j+'" onChange="TOI_update('+j+')"><option value="12">12</option><option value="14" selected>14</option><option value="16">16</option><option value="18">18</option><option value="20">20</option><option value="24">24</option><option value="26">26</option></select>px';
	newcell=newRow.insertCell(4);
	
	newcell.innerHTML='文字颜色(Color)：<input type="text" id="color'+j+'" name="color'+j+'" size="4" value="#993366" /><img id="BtnColor'+j+'" style="background-color:#993366" src="rect.gif" border="0" title="选取颜色" align="absMiddle" onClick="TOI_setColor('+j+')">';
	newcell=newRow.insertCell(5);
	
	newcell.innerHTML='文字角度(Angle)：<input type="text" id="angle'+j+'" name="angle'+j+'" size="1" value="0" />';
	
	newcell=newRow.insertCell(6);
	
	newcell.innerHTML='高级(Advanced)<input type="checkbox" name="preview'+j+'" value="1" />';
	
	
	var newdiv = document.createElement('DIV');
	newdiv.id = "demoTab"+j;
	newdiv.className = "demoTabCss";
	newdiv.innerHTML = '<span id="demoText'+j+'"></span>';
	document.body.appendChild(newdiv);
	
	TOI_Bind(j);
	try{
		TOI_update(j);
	}catch(e){
		alert("错误"+e);
	}
}

function del_row() {
	if (document.layers) {
     	sqd = document.layers["sqd"];
     	numberObj = document.layers["number"];
  	}else if (document.all) {
     //sqd = sqd;
     	numberObj = document.all.number;
 	}else if (document.getElementById) {
     	sqd = document.getElementById("sqd");
     	numberObj = document.getElementsByName('number');
 	}
	if(sqd.rows.length==1) return;
	var checkit = false
	//alert(numberObj.length);
	for (var i=0;i<numberObj.length;i++) {
		//alert(i+'='+numberObj[i].value);
		if(numberObj[i].checked){
			checkit=true;
			n = numberObj[i].value;
			sqd.deleteRow(i);
			document.body.removeChild(document.getElementById('demoTab'+n)); 
			break;
		}
	}

	if (checkit) {
		for(i=1;i<sqd.rows.length;i++){
			sqd.rows[i].cells[0].innerHTML=i;
		}
	} else{
		alert("请选择一个要删除的对象 Please Select one");
		return false;
	}

}
function changeBgImage(img,remote){
	if(remote==0){
		document.getElementById('card').style.background = "#005599 url(bg/"+img+") repeat scroll left top ";
	}
	if (remote==1){
		document.getElementById('card').style.background = "#005599 url("+img+") repeat scroll left top ";
	}
}
function addgif(id,tabname){
	if(document.getElementById(tabname) ==null){
		var newdiv = document.createElement('DIV');
		newdiv.id = tabname;
		newdiv.className = "demoTabCss";
		var Gimg = document.getElementById(id);
//		if(tabname=='avatarTab'){
//			width = img.width+"px";
//			height = img.height+"px";
//		}
	//alert(img.width+'+'+img.height);
		
		newdiv.innerHTML = '<img src="'+Gimg.src+'" width='+Gimg.width+' height='+Gimg.height+'>';
		document.body.appendChild(newdiv);
		
	}else{
		newdiv =document.getElementById(tabname);
		var img = document.getElementById(id);
		//alert(img.width+'+'+img.height);
		newdiv.innerHTML = '<img src="'+img.src+'" width='+img.width+' height='+img.height+'>';
	}
	
	newdiv.onmousedown = function(ev)
  {  
    var cx = toi_get_offset(this, 'offsetLeft');
    var cy = toi_get_offset(this, 'offsetTop');

    if (typeof ev == 'undefined')
    {    
      this.X = event.clientX - cx;
      this.Y = event.clientY - cy;
      this.setCapture();
    }
    else
    {
      this.X = ev.pageX - cx;
      this.Y = ev.pageY - cy;
    }
    TOI_ondrag = true;
  };
  
  newdiv.onmouseup = function(ev)
  {
    TOI_ondrag = false;
    if (typeof ev == 'undefined')  
      this.releaseCapture();
  };
  
  newdiv.onmousemove = function(ev)
  {
  	
    var dx, dy;
    if (!TOI_ondrag) return;
    if (typeof ev == 'undefined')
    {
      dx = event.clientX - this.X;
      dy = event.clientY - this.Y;
    }
    else
    {
      dx = ev.pageX - this.X;
      dy = ev.pageY - this.Y;
    }

      var ix = toi_get_offset(TOI_img, 'offsetLeft');
	  var iy = toi_get_offset(TOI_img, 'offsetTop');
	  var iw = TOI_img.offsetWidth;
	  var ih = TOI_img.offsetHeight;
	  var tw = newdiv.offsetWidth;
	  var th = newdiv.offsetHeight;
  
	  TOI_xmin = ix;
	  TOI_ymin = iy;
	  TOI_xmax = ix + iw-tw;
	  TOI_ymax = iy + ih-th;
    //if (dx > TOI_xmax) dx = TOI_xmax;
    //if (dx < TOI_xmin) dx = TOI_xmin;
    //if (dy > TOI_ymax) dy = TOI_ymax;
    //if (dy < TOI_ymin) dy = TOI_ymin;

    newdiv.style.left = dx + 'px';
    newdiv.style.top = dy + 'px';
    
	document.getElementById('xpos_'+tabname).value = dx - TOI_xmin;
	document.getElementById('ypos_'+tabname).value = dy - TOI_ymin;
	 
  };
	//TOI_tab = document.getElementById('demoTab'+n);
	
	// count the posX & posY
	  var ix = toi_get_offset(TOI_img, 'offsetLeft');
	  var iy = toi_get_offset(TOI_img, 'offsetTop');
	
	  var iw = TOI_img.offsetWidth;
	
	  var ih = TOI_img.offsetHeight;
	  
	  var tw = newdiv.offsetWidth;
	  var th = newdiv.offsetHeight;
	   
	  TOI_xmin = ix;
	  TOI_ymin = iy;
	  TOI_xmax = ix + iw;
	  TOI_ymax = iy + ih;
	  
	  var xpos = parseInt(document.getElementById('xpos_'+tabname).value);
	  var ypos = parseInt(document.getElementById('ypos_'+tabname).value);  
	  if (xpos == -1) xpos = parseInt((iw - tw) / 2);
	  if (ypos == -1) ypos = parseInt((ih - th) / 2);
	
	  newdiv.style.left = (xpos + ix) + 'px';
	  newdiv.style.top = (ypos + iy) + 'px';
	
	
}
function openwin(url){
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=750,height=680,screenX=130,screenY=130,top=100,left=130')
} 
//创建和运用滤镜
function rotate(name, angle){
	//将角度转变成弧度
	var rad = degToRad(angle);
	//计算弧度的cos和sin值
	costheta = Math.cos(rad);
	sintheta = Math.sin(rad);
	//创建对象
	var el = document.getElementById(name);
	if(el) {
		//应用滤镜
		el.style.filter = "progid:DXImageTransform.Microsoft.Matrix()";
		  
		//设置滤镜的属性值
		el.filters.item("DXImageTransform.Microsoft.Matrix").SizingMethod = "auto expand";
		el.filters.item("DXImageTransform.Microsoft.Matrix").FilterType = "bilinear";
		//运用旋转滤镜
		el.filters.item("DXImageTransform.Microsoft.Matrix").M11 = costheta;
		el.filters.item("DXImageTransform.Microsoft.Matrix").M12 = -sintheta;
		el.filters.item("DXImageTransform.Microsoft.Matrix").M21 = sintheta;
		el.filters.item("DXImageTransform.Microsoft.Matrix").M22 = costheta;
		}
}

//角度变弧度函数
var pi = Math.PI;
function degToRad(x) { return ( x/(360/(2*pi)) ); }
function radToDeg(x) { return ( x*(360/(2*pi)) ); }

function changeCardSize(){
	var w  = document.getElementById('card_width').value;
	var h  = document.getElementById('card_height').value;
	document.getElementById('card').style.width=w;
	document.getElementById('backImage').style.width=w;
	document.getElementById('card').style.height=h;
	document.getElementById('backImage').style.height=h;
}
function getUrlImage(id,tab){
	var img  = id+'_img';
	var idradio = id+"_radio";
	var imgObj = document.getElementById(img);
	imgObj.src= document.getElementById(id).value;
	imgObj.style.display= '';
	document.getElementById(idradio).value = document.getElementById(id).value;
	//document.getElementById(idradio).checked = true;
	
}
