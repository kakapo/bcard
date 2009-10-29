// JavaScript for TextOnImage (toi.js)
// Author:  hightman@twomice.net
// Date:  2007/03/23
// Version: 1.0.0
//
// public functions:
//
// TOI_Update()
// TOI_Init()
// auto bind??, only support IE?
// 
// Should recount the maxX, maxY ... after window.onresize??
// set the ie or firefox flag
var ieFlag = (navigator.appVersion.indexOf("MSIE")!=-1);
var ffFlag = (navigator.userAgent.indexOf("Firefox")!=-1);

// Try to resize the image
function LoadDemo(img)
{
  if (!ieFlag && !ffFlag)
  {
    img.onmouseover = '';
    img.onmouseout = '';
  }
}

// Zoom out the demo Image if it was zoom in
function OutDemo()
{
  var demo = document.getElementById('DemoImg2');
  if (!demo) return;
  demo.style.display = 'none';
}

function OverDemo(img, ev)
{
  var demo = document.getElementById('DemoImg2');
  if (!demo) return;  
  demo.style.display = '';
  
  var ex, ey;
  var jmg = demo.childNodes[0];
  jmg.src = img.src.replace('_s.', '.');  

  ex = (ieFlag ? (ev.clientX + document.body.scrollLeft) : ev.pageX);
  ey = (ieFlag ? (ev.clientY + document.body.scrollTop) : ev.pageY);
  if (img.alt.indexOf('RECOMMEND') != -1) ey += 40; 
  else ey -= (jmg.height + 30);
  if ((ex + jmg.width) > document.body.clientWidth) ex = ex - jmg.width - 100;
  if (ex < 0) ex = 0;  
  demo.style.left = String(ex) + 'px';
  demo.style.top = String(ey) + 'px';
}

// Save the image to Local disk
function SaveImage(img)
{
	var win = document.getElementById('genFrm').contentWindow;
	if (!win || img.tagName != 'IMG') return;
	win.location.href = img.src;
	setTimeout(function() { win.document.execCommand("SaveAs"); }, 200);
}

// Check the browser version (only IE support OK)
if (!ieFlag && !ffFlag)
{
  alert('您的浏览器不是IE或FF, 页面显示可能会不正常!');
}

// Global Variables
var TOI_inited = false;
var TOI_ondrag = false;
var TOI_xmax, TOI_ymax, TOI_xmin, TOI_ymin;
var TOI_img, TOI_txt, TOI_tab;
var TOI_isrc;

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

    // update the preview?? (only: 1 & 3)
   
    TOI_update(n);
    
  }
}

// Init the current data. (from the form)
function TOI_init()
{
  if (TOI_inited == true) return;
  // init the vaiables
  TOI_img = document.getElementById('backImage');
  TOI_txt = document.getElementById('demoText0');
  TOI_tab = document.getElementById('demoTab0');
  TOI_isrc = new Image();
  TOI_isrc.src = TOI_img.src;
  TOI_inited = true;

  
  TOI_Bind('0');

  // update the preview
  TOI_update('0');
}

// Drag event bind
function TOI_Bind(j){
	// Drag event bind
  var newTabDiv = document.getElementById('demoTab'+j);
  newTabDiv.onmousedown = function(ev)
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
  
  newTabDiv.onmouseup = function(ev)
  {
    TOI_ondrag = false;
    if (typeof ev == 'undefined')  
      this.releaseCapture();
  };
  
  newTabDiv.onmousemove = function(ev)
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
	  var tw = newTabDiv.offsetWidth;
	  var th = newTabDiv.offsetHeight;
  
	  TOI_xmin = ix;
	  TOI_ymin = iy;
	  TOI_xmax = ix + iw-tw;
	  TOI_ymax = iy + ih-th;
    if (dx > TOI_xmax) dx = TOI_xmax;
    if (dx < TOI_xmin) dx = TOI_xmin;
    if (dy > TOI_ymax) dy = TOI_ymax;
    if (dy < TOI_ymin) dy = TOI_ymin;

    newTabDiv.style.left = dx + 'px';
    newTabDiv.style.top = dy + 'px';
    
	document.getElementById('xpos'+j).value = dx - TOI_xmin;
	document.getElementById('ypos'+j).value = dy - TOI_ymin;
	 
  };
 
}
// Update the preview
function TOI_update(n)
{
  TOI_txt = document.getElementById('demoText'+n);
  TOI_tab = document.getElementById('demoTab'+n);

  var form = document.getElementById('dform');
  if ( document.getElementById('generate').disabled ==true)
    return false;
//alert(document.getElementById('text'+n));
  // get the default value
  var text = document.getElementById('text'+n).value;
  text = text.replace(/ /g, '&nbsp;');
  text = text.replace(/\n/g, '<br>');
  TOI_txt.innerHTML = text;

  // generate the style
  var size = document.getElementById('size'+n);
  var color = document.getElementById('color'+n);
  TOI_txt.style.fontSize = size.value + 'px';
  TOI_txt.style.color = color.value;

//   generate the filter: glow or shadow
//  var filter = '';
//  if (form.filter.value == 'shadow')
//  {
//    filter  = 'dropshadow(color=' + form.fcolor0.value + ', offx=' + form.fdist.value;
//    filter += ',offy=' + form.fdist.value + ', positive=1)';
//  }
//  else if (form.filter.value == 'border')
//  {
//    filter  = 'glow(color=' + form.fcolor0.value + ', strength=' + form.fdist.value + ')';
//  }
//  else
//  {
//    filter = 'none';
//  }
//  TOI_txt.style.filter = filter;

  // reset the image & tab show
//  if (TOI_tab.style.display == 'none')
//  {  
//    TOI_tab.style.display = '';
//    TOI_img.src = (form.ifile.value == '' ? TOI_isrc.src : form.ifile.value);
//    
//  	document.getElementById('BtnCopy').disabled = true;
//  	document.getElementById('BtnSave').disabled = true;
//  }

  // count the posX & posY
  var ix = toi_get_offset(TOI_img, 'offsetLeft');
  var iy = toi_get_offset(TOI_img, 'offsetTop');

  var iw = TOI_img.offsetWidth;

  var ih = TOI_img.offsetHeight;
  
  var tw = TOI_tab.offsetWidth;
  var th = TOI_tab.offsetHeight;
   
  TOI_xmin = ix;
  TOI_ymin = iy;
  TOI_xmax = ix + iw;
  TOI_ymax = iy + ih;
  
  var xpos = parseInt(document.getElementById('xpos'+n).value);
  var ypos = parseInt(document.getElementById('ypos'+n).value);  
  if (xpos == -1) xpos = parseInt((iw - tw) / 2);
  if (ypos == -1) ypos = parseInt((ih - th) / 2);

  TOI_tab.style.left = (xpos + ix) + 'px';
  TOI_tab.style.top = (ypos + iy) + 'px';

}

// TOI_submit
function TOI_submit()
{
  //TOI_img.src = 'img/wait2.gif';
  TOI_img.title = '图片生成中,请稍候(Building)...';
  
  Hidden_Tabs('none');
  //TOI_tab.style.display = 'none';
  
  var dform = document.getElementById("dform");
  if (document.getElementById('mserver').value == 'yes')    
  dform.action = ((Math.round(Math.random()*1000)%3) > 0 ? 'tm.php' : 'tm.php');
  document.getElementById('generate').disabled = true;
  document.getElementById('generate').value = '请稍候(Loading)...';
  return true;
}

function Hidden_Tabs(hidden){
	if (document.layers) {
     	numberObj = document.layers["number"];
  	}else if (document.all) {
     	numberObj = document.all.number;
 	}else if (document.getElementById) {
     	numberObj = document.getElementsByName('number');
 	}
 	
	if(typeof numberObj.length!='undefined'){
		for (var i=0;i<numberObj.length;i++) {
				n = numberObj[i].value;
				document.getElementById("demoTab"+n).style.display = hidden;
		}
	}else{
		document.getElementById("demoTab0").style.display = hidden;
	}
	if(document.getElementById("gifTab") !=null){
		document.getElementById("gifTab").style.display = hidden;
	}	
	if(document.getElementById("avatarTab") !=null){
		document.getElementById("avatarTab").style.display = hidden;
	}
}

// TOI_callback, 参与判断成功与否
function TOI_callback(type, msg)
{
  	document.getElementById('generate').disabled = false;
  	document.getElementById('generate').value = '生成图片(Generate)';

  // update the image source
  if (type == 'ok')
  {
    TOI_img.src = msg;
    document.getElementById('BtnCopy').disabled = false;
    document.getElementById('BtnSave').disabled = false;
  }
  else
  {
  	
    alert("==="+msg);
    //TOI_update();
    TOI_img.src = TOI_isrc.src;
    TOI_img.width = TOI_isrc.width;
    TOI_img.height = TOI_isrc.height;
  }
}

// Reset the form
function TOI_reset()
{
  //document.dform.reset();  
  document.getElementById('generate').disabled = false;
  document.getElementById('generate').value = '生成图片(Generate)';
  document.getElementById('BtnCopy').disabled = true;
  document.getElementById('BtnSave').disabled = true;
  
  TOI_img.src = TOI_isrc.src;
  TOI_isrc.width=300;
  TOI_isrc.height=200;
  TOI_img.width = TOI_isrc.width;
  TOI_img.height = TOI_isrc.height;
  Hidden_Tabs('');
  //TOI_update(0);
}

// Update the background image
function TOI_upimg()
{
  var form = document.dform;
  var isrc = form.ifile.value;
  var pos = isrc.lastIndexOf('.');
  var img = new Image();

  // on load
  img.onload = function () {
    var width = img.width;
    var height = img.height;
    img.onload = '';
    if (width > 600 || height > 400)
    {
      // => 480*360
      var ratio1 = 320/width;
      var ratio2 = 240/height;
      if (ratio1 > ratio2)
      {
        width = parseInt(ratio2 * width);
        height = 240;
      }
      else
      {
        width = 320;
        height = parseInt(ratio1 * height);
      }      
      alert('注意：图片过大, 系统自动进行了一些调整');
    }
    
    TOI_img.src = img.src;
    TOI_img.width = width;
    TOI_img.height = height;
    //form.xpos.value = '-1';
    //form.ypos.value = '-1';
    if (form.title.value == '')
    {
      var pos2 = isrc.lastIndexOf('\\');
      form.title.value = isrc.substring(pos2+1,pos);
    }
    TOI_update();
    delete img;
  };

  // on error
  img.onerror = function () {
    img.onerror = '';
    alert('注意：您刚刚试图上载的一个文件似乎不是合法图片');
    delete img;
  };

  // store the source address
  img.src = isrc;
}

// get object's offset
function toi_get_offset(o, n)
{
  var w = 0;
  do { w = w + o[n]; } while (o = o.offsetParent);
  return w;
}

// Bind the init function after page loaded.
if (typeof window.attachEvent != 'undefined')
{
 
  window.attachEvent('onload', TOI_init);
  //window.attachEvent('onresize', TOI_update);
}
else if (typeof window.addEventListener != 'undefined')
{
//alert('ff');
  window.addEventListener('load', TOI_init, false);
 // window.addEventListener('resize', TOI_update(0), false);
}
else
{
	alert('ddd');
  window.onload = function() { TOI_init(); }
  window.onresize = function() { TOI_update(0); }
}

// 复制图片到剪贴版
function CopyImage(img)
{
  if (img.tagName != 'IMG') return;
  if (typeof img.contentEditable == 'undefined' || !document.body.createControlRange)
  {
    alert('抱歉，浏览器不支持直接复制图片！\n请将鼠标移到图片上方，单击鼠标右键在弹出菜单中选择“复制”');
  }
  else
  {
    var ctrl = document.body.createControlRange();
    img.contentEditable = true;
    ctrl.addElement(img);
    ctrl.execCommand('Copy');
    img.contentEditable = false;
    alert('复制完成，到QQ对话框里按Ctrl-V就可以啦！\n\n若不能粘贴请重复尝试或用鼠标右键选复制');
  }
}
