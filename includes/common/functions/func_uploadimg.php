<?
function fileUpload_thumb($filename,$largimg,$smallimg,$width,$height)
{
//$_FILES[filename][]
//make sure this directory is writable!
		//large Image Save
		//$picture1 = trim($filename['name']);
		//$picture1=rand(1000,9999).$picture1;		
//		copy($_FILES['file1']['tmp_name'],"picture/".$picture1);
	
	$path_thumbs = $smallimg;
	$path_big = $largimg;
	//the new width of the resized image, in pixels.
	$img_thumb_width = $width; //
	$img_thumb_height = $height;

	$extlimit = "yes"; //Limit allowed extensions? (no for all extensions allowed)
	//List of allowed extensions if extlimit = yes
	$limitedext = array(".gif",".jpg",".png",".jpeg",".bmp");
	
	//the image -> variables
	$file_type = $filename['type'];
	$file_name = $filename['name'];
	$file_size = $filename['size'];
	$file_tmp  = $filename['tmp_name'];

	//check if you have selected a file.
	if(!is_uploaded_file($file_tmp)){
	  echo "Error: Please select a file size upto 1 mb!. <br>--<a href=\"$_SERVER[PHP_SELF]\">back</a>";
	  exit(); //exit the script and don't process the rest of it!
	}
   //check the file's extension
   $ext = strrchr($file_name,'.');
   $ext = strtolower($ext);
   //uh-oh! the file extension is not allowed!
   if (($extlimit == "yes") && (!in_array($ext,$limitedext))) {
	  echo "Wrong file extension.  <br>--<a href=\"$_SERVER[PHP_SELF]\">back</a>";
	  exit();
   }
   //so, whats the file's extension?
   $getExt = explode ('.', $file_name);
   $file_ext = $getExt[count($getExt)-1];

   //create a random file name
   $rand_name = md5(time());
   $rand_name= rand(0,999999999);
   //the new width variable
   $ThumbWidth = $img_thumb_width;
   $ThumbHeight = $img_thumb_height;

   //////////////////////////
   // CREATE THE THUMBNAIL //
   //////////////////////////
   
   //keep image type
   if($file_size){
	  if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
		   $new_img = imagecreatefromjpeg($file_tmp);
	   }elseif($file_type == "image/x-png" || $file_type == "image/png"){
		   $new_img = imagecreatefrompng($file_tmp);
	   }elseif($file_type == "image/gif"){
		   $new_img = imagecreatefromgif($file_tmp);
	   }
	   //list the width and height and keep the height ratio.
	   list($width, $height) = getimagesize($file_tmp);
	   //calculate the image ratio
	   $imgratio=$width/$height;
	   
	   if ($imgratio>1){
		  $newwidth = $ThumbWidth;
		  $newheight = $ThumbHeight;  //change//$ThumbWidth/$imgratio;
	   }else{
			 $newheight = $ThumbHeight;
			 $newwidth =  $ThumbWidth; //change//$ThumbWidth*$imgratio;
	   }
	   //function for resize image.
	   if (function_exists(imagecreatetruecolor)){
	   $resized_img = imagecreatetruecolor($newwidth,$newheight);
	   }else{
			 die("Error: Please make sure you have GD library ver 2+");
	   }
	   //the resizing is going on here!
	  imagecopyresized($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	   //finally, save the image
	   ImageJpeg ($resized_img,"$path_thumbs/$rand_name.$file_ext");
	   ImageDestroy ($resized_img);
	   ImageDestroy ($new_img);	   
	}
	//ok copy the finished file to the thumbnail directory		
	move_uploaded_file ($file_tmp, "$path_big/$rand_name.$file_ext");
	$filename = $rand_name.".".$file_ext; //thumbnail picture store
	return $filename;
}

function upload_profile_img($filename)
{
	if($filename['name'] !='') 
	{			
		//$filename = $_FILES['imgfile1'];
		$largimg = "upload";
		$smallimg = "upload/thumbs";
		$width = 168;
		$height = 129;
		$imgname = fileUpload_thumb($filename,$largimg,$smallimg,$width,$height);		
	}
	return $imgname;
}	

function upload_img_big($filename)
{
	if($filename['name'] !='') 
	{			
		$largimg = "../upload";
		$smallimg = "../upload/thumbs";
		$width = 500;
		$height = 500;
		$imgname = fileUpload_thumb($filename,$largimg,$smallimg,$width,$height);		
	}
	return $imgname;
}
function upload_top_right_img($filename)
{
	if($filename['name'] !='') 
	{			
		$largimg = "../upload";
		$smallimg = "../upload/thumbs";
		$width = 100;
		$height = 100;
		$imgname = fileUpload_thumb($filename,$largimg,$smallimg,$width,$height);		
	}
	return $imgname;
}

function upload_news_img($filename)
{
	if($filename['name'] !='') 
	{			
		$largimg = "../upload";
		$smallimg = "../upload/thumbs";
		$width = 100;
		$height = 100;
		$imgname = fileUpload_thumb($filename,$largimg,$smallimg,$width,$height);		
	}
	return $imgname;
}

function upload_buddies_img($filename)
{
	if($filename['name'] !='') 
	{			
		$largimg = "../upload";
		$smallimg = "../upload/thumbs";
		$width = 100;
		$height = 100;
		$imgname = fileUpload_thumb($filename,$largimg,$smallimg,$width,$height);		
	}
	return $imgname;
}



function upload_blog_img($filename)
{
	if($filename['name'] !='') 
	{			
		$largimg = "../upload";
		$smallimg = "../upload/thumbs";
		$width = 100;
		$height = 100;
		$imgname = fileUpload_thumb($filename,$largimg,$smallimg,$width,$height);		
	}
	return $imgname;
}

function upload_event_img($filename)
{
	if($filename['name'] !='') 
	{			
		$largimg = "upload";
		$smallimg = "upload/thumbs";
		$width = 300;
		$height = 300;
		$imgname = fileUpload_thumb($filename,$largimg,$smallimg,$width,$height);		
	}
	return $imgname;
}
function upload_event_img_admin($filename)
{
	if($filename['name'] !='') 
	{			
		$largimg = "../upload";
		$smallimg = "../upload/thumbs";
		$width = 300;
		$height = 300;
		$imgname = fileUpload_thumb($filename,$largimg,$smallimg,$width,$height);		
	}
	return $imgname;
}

function upload_event_img_user($filename)
{
	if($filename['name'] !='') 
	{			
		$largimg = "upload";
		$smallimg = "upload/thumbs";
		$width = 200;
		$height = 200;
		$imgname = fileUpload_thumb($filename,$largimg,$smallimg,$width,$height);		
	}
	return $imgname;
}


?>