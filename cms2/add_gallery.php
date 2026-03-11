<?PHP 
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
  include ("../includes/config/config.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classEvents.php");

include_once 'functions.php';
$ccAdminData = get_session_data();
$image_path = base_path('images/chinesedumps-logo.png');
//--------------------Creat objects---------------------------

	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objValid 	 = new Validate_fields;
	$objEvent	=	new classEvents($objDBcon);	// VALIDATION CLASS classCategory

	$strError	=	"";
		$spram[1]		 =  "";
		$spram[2]		 =  "";
		$spram[3]		 =  ""; 
		$spram[4]		 =  "";
		$spram[5]		 =  "";
		$spram[6]		 =  "";
		
	$farr			=	array("'");
	$aarr			=	array("\'");
	
$image_path = base_path('images/stamp_logo.png');
// 	function watermark_image($oldimage_name, $new_image_name){
//     global $image_path;
//     list($owidth,$oheight) = getimagesize($oldimage_name);
//     $width = 350;
// 		$height = 200;    
//     $im = imagecreatetruecolor($width, $height);
//     $img_src = imagecreatefromjpeg($oldimage_name);
//     imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
//     $watermark = imagecreatefrompng($image_path);
//     list($w_width, $w_height) = getimagesize($image_path);        
//     $pos_x = ($width - $w_width)/2; 
//     $pos_y = ($height - $w_height)/2;
//     imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
//     imagejpeg($im, $new_image_name, 100);
//     imagedestroy($im);
//     unlink($oldimage_name);
//     return true;
// 	}

    // function watermark_image($oldimage_name, $new_image_name) {
    //     global $image_path;
    
    //     // Get original image size
    //     list($width, $height) = getimagesize($oldimage_name);
    
    //     // Create canvas with original size
    //     $im = imagecreatetruecolor($width, $height);
    
    //     // Load original image
    //     $img_src = imagecreatefromjpeg($oldimage_name);
    
    //     // Copy original image as-is (NO resizing)
    //     imagecopy($im, $img_src, 0, 0, 0, 0, $width, $height);
    
    //     // Load watermark
    //     $watermark = imagecreatefrompng($image_path);
    //     list($w_width, $w_height) = getimagesize($image_path);
    
    //     // Center position
    //     $pos_x = ($width - $w_width) / 2;
    //     $pos_y = ($height - $w_height) / 2;
    
    //     // Add watermark
    //     imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
    
    //     // Save image
    //     imagejpeg($im, $new_image_name, 100);
    
    //     // Cleanup
    //     imagedestroy($im);
    //     imagedestroy($img_src);
    //     imagedestroy($watermark);
    
    //     unlink($oldimage_name);
    //     return true;
    // }
    
function watermark_image($oldimage_name, $new_image_name) {
    global $image_path;

    // Original image
    list($width, $height) = getimagesize($oldimage_name);
    $im = imagecreatetruecolor($width, $height);
    $img_src = imagecreatefromjpeg($oldimage_name);
    imagecopy($im, $img_src, 0, 0, 0, 0, $width, $height);

    // Watermark
    list($wm_width, $wm_height) = getimagesize($image_path);

    // 🔥 Control watermark size here (25% of image width)
    $target_wm_width = (int) ($width * 0.25);
    $scale = $target_wm_width / $wm_width;
    $target_wm_height = (int) ($wm_height * $scale);

    // Resize watermark
    $watermark_src = imagecreatefrompng($image_path);
    $watermark = imagecreatetruecolor($target_wm_width, $target_wm_height);
    imagealphablending($watermark, false);
    imagesavealpha($watermark, true);

    imagecopyresampled(
        $watermark,
        $watermark_src,
        0, 0, 0, 0,
        $target_wm_width,
        $target_wm_height,
        $wm_width,
        $wm_height
    );

    // Center watermark
    $pos_x = ($width - $target_wm_width) / 2;
    $pos_y = ($height - $target_wm_height) / 2;

    imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $target_wm_width, $target_wm_height);

    imagejpeg($im, $new_image_name, 100);

    imagedestroy($im);
    imagedestroy($img_src);
    imagedestroy($watermark);
    imagedestroy($watermark_src);

    unlink($oldimage_name);
    return true;
}



	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")
	{
		
		 for($i=0; $i < count($_FILES['exam_demo']['tmp_name']);$i++)
    {
			
		$totalReport = mysql_query("select * from sliders_new ORDER BY group_id DESC");
		$totalRep = mysql_fetch_array($totalReport);
		$totalRep = $totalRep["group_id"][$i] ;
		$spram[3] = $_POST['group_id'];	
			
			$getextension	=	substr($_FILES['exam_demo']['name'][$i],-4);
			$newnamedemo	=	rand()."_mainBanner".$_FILES['exam_demo']['name'][$i];
			
		//$spram[2]	=	$newnamedemo; 
		
		mysql_query("insert into sliders_new (s_image, group_id) values ('".$newnamedemo."','".$spram[3]."')");

	watermark_image($_FILES['exam_demo']['tmp_name'][$i],"../images/slider/".$newnamedemo);
			
		}
		$strError	.= "Report has been added successfully";
	}
	$category = $objEvent->fillComboCategory_exam($spram[12]);

	$subcat	=	$objEvent->fillComboSubCategory($spram[13],$spram[12]);
	$exam	=	$objEvent->fillComboExam($spram[14],$spram[13],$spram[12]);


?>

<? include ("header.php"); ?>

<script language="Javascript" src="js/category.js"></script>

<script language="JavaScript" src="../includes/js/calendar.js"></script>



<table cellpadding="0" cellspacing="0" width="102%">

<tr>

<td width="190" class="leftside"><?php include ("menu.php"); ?></td>



<td width="810" class="rightside"><h2>Add Banner!</h2>



Welcome to your<?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.<br />



<form name="Form" id="Form" method="post" action="" onSubmit="return validateCert(this)" enctype="multipart/form-data"><br />



  <table cellpadding='0' cellspacing='0' width='100%'>

    <tr>

      <td></td>

    </tr>

    <tr>

      <td class='header'>Add Exam </td>

    </tr>

    <tr>

      <td class='setting1'><table width="80%" border="0" align="center" cellpadding="2" cellspacing="2">

        <tr>

          <td align="left">&nbsp;</td>

          <td colspan="2" style="color: #FF0000;"><?=$strError?></td>

        </tr>

        <tr>

          <td width="24%" align="left" nowrap="nowrap"><div align="right"></div></td>

        </tr> 
				<tr>

          <td align="right">* Exam Name:</td>

          <td colspan="2"><div id="txtHint"><?=$exam?></div></td>

        </tr>
		
			<tr>
				<td align="right"> Multiple:</td>
				<td colspan="2"><input type="file" name="exam_demo[]" multiple/></td>
			</tr>
<?php/* 		<tr>
              <td align="right"> Video Link:</td>
              <td colspan="2"><input name="video_link" type="text" id="video_link" value="" /></td>
            </tr> */  ?>

		

        <tr align="right">

          <td align="left">&nbsp;</td>

          <td colspan="2" align="left"><input type="submit" name="Submit" value="Submit" /></td>

        </tr>

      </table>      

      <p>&nbsp;</p>

      </td>

    </tr>

  </table>

	  </form>

<br /></td>

</tr>

</table>

</td>

</tr>

</table>

<? include("footer.php")?>

</body>

</html>

