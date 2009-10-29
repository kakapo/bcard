<?php
class ImageUploader 
{
	var $mSize;
	var $mFileName;
	var $mFileMime;
	var $mFileTmpName;
	var $mFileExt;
	var $mErrorNo;
	
	function ImageUploader(){
		
	}

	function setFileValues($file)
	{
			//size
			$this->mSize = $file['size'];
			//filename
			$this->mFileName = $file['name'];
			//type
			$this->mFileMime = $file['type'];
			//tmp_name
			$this->mFileTmpName = $file['tmp_name'];
			//file ext
			$this->mFileExt = strrchr($this->mFileName,'.');	
			//error_no
			$this->mErrorNo = $file['error'];
	}
	function validateUploaded(){
		if($this->mErrorNo=='0'){
			return true;
		}else{
			return false;
		}
	}
	function validateFileType(){
		$im='';
				
		if (($this->mFileMime== "image/jpeg")||($this->mFileMime == "image/pjpeg"))
		{
			$im = imagecreatefromjpeg($this->mFileTmpName);
		}
		elseif ($this->mFileMime== "image/x-png")
		{
			$im = imagecreatefrompng($this->mFileTmpName);
		}
		elseif ($this->mFileMime == "image/gif")
		{
			$im = imagecreatefromgif($this->mFileTmpName);
		}
		if($im){
			return true;
		}else{
			return false;
		}
	}
	function validateSize($maxWidth,$maxHeight){
		list($width, $height, $type, $attr) = getimagesize($this->mFileTmpName);
		if(($width <= $maxWidth) && ($height<=$maxHeight)){
			return true;
		}else {
			return false;
		}
	}
		
	function uploadAndThumbnail($maxWidth,$maxHeight,$todir,$tofile,$quality=85){
		//echo $maxWidth."X".$maxHeight;
		//die;
		include_once(KFC_LIBS_DIR."/WMThumbnail/class.WMThumbnail.inc.php");
		
		$thumbnail = new WMThumbnail($this->mFileTmpName,0); 
		$thumbnail->setMaxWidth($maxWidth);		
		$thumbnail->setMaxHeight($maxHeight);
		
		//if($is_wm){
			//$thumbnail->addLogo($logourl, $position, 1);
		//}
		$mix = $thumbnail->save($todir."/".$tofile, $quality);
		@chmod ($todir."/".$tofile, 0777);
	}
	
	function upload($todir,$tofile){
	
		return @move_uploaded_file($this->mFileTmpName, $todir."/".$tofile);
		//@chmod ($todir."/".$tofile, 0777);
			
	}
	function getOriName(){
		return $this->mFileName;
	}
	    
	function getName()
	{
		list($usec, $sec) = explode(" ", microtime());
		$usec = substr(strstr($usec,"."),1);
		return 'pic'.date("His",$sec).$usec.$this->mFileExt;
	}

}
?>