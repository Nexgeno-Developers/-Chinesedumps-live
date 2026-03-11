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
	function watermark_image($oldimage_name, $new_image_name){
    global $image_path;
    list($owidth,$oheight) = getimagesize($oldimage_name);
    $width = 350;
		$height = 200;    
    $im = imagecreatetruecolor($width, $height);
    $img_src = imagecreatefromjpeg($oldimage_name);
    imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
    $watermark = imagecreatefrompng($image_path);
    list($w_width, $w_height) = getimagesize($image_path);        
    $pos_x = ($width - $w_width)/2; 
    $pos_y = ($height - $w_height)/2;
    imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
    imagejpeg($im, $new_image_name, 100);
    imagedestroy($im);
    unlink($oldimage_name);
    return true;
	}

	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")
	{

		$totalReport = mysql_query("select * from sliders ORDER BY s_id DESC");
		$totalRep = mysql_fetch_array($totalReport);
		$totalRep = $totalRep["s_id"] + 1;

		$spram[1]	=	$_FILES['exam_demo'];
			$getextension	=	substr($spram[1]['name'],-3);
			$newnamedemo	=	$totalRep."_mainBanner.".$getextension;
			
		$spram[2]	=	$newnamedemo;
		$spram[3] = $_POST["s_alt"];
		$spram[4] = $_POST["s_link"];
		
		$spram[5] = date('Y-m-d');
		$spram[6] = 1;
		$spram[7] = $_POST["type"];
		
		mysql_query("insert into sliders (s_image, s_alt, s_link, s_updated, status,type) values ('".$spram[2]."','".$spram[3]."','".$spram[4]."','".$spram[5]."','".$spram[6]."','".$spram[7]."')");


		if(empty($strError)){
			if($_FILES['exam_demo']['name']!=''){

				$demotype	=	$_FILES['exam_demo']['type'];
				if($demotype=='image/jpeg' || $demotype=='application/msword' || $demotype=='application/pdf'){
					if (!file_exists("../images/slider/".$spram[0])){mkdir("../images/reports/".$spram[0]);}
					if($_POST["type"] == 'slider'){
						copy($spram[1]['tmp_name'],"../images/slider/".$spram[0]."/".$newnamedemo);
					}else{
						watermark_image($spram[1]['tmp_name'],"../images/slider/".$spram[0]."/".$newnamedemo);
					}
				}else{
					$strError	.=	"<b>Error! Banner type is not valid.</b>";
				}
			}

			 

		

			$strError	.= "Report has been added successfully";

			

		}

	}


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

          <td colspan="2">* Required information</td>

        </tr>


			 <tr>
				<td align="right"> Type:</td>
				<td colspan="2">
					<select name="type">
						<option value="slider">Slider</option>
						<option value="proxy exams">Proxy Exams</option>
						<option value="test centers">Test Centers</option>
						<option value="lab">Lab</option>
						<option value="written">Written</option>
					</select>
				</td>
			</tr>

			 <tr>
				<td align="right"> Banner:</td>
				<td colspan="2"><input name="exam_demo" type="file" id="exam_demo" value="" /></td>
			</tr>
			<tr>
              <td align="right"> Alt:</td>
              <td colspan="2"><input name="s_alt" type="text" id="s_alt" value="" /></td>
            </tr>
			<tr>
              <td align="right"> Link:</td>
              <td colspan="2"><input name="s_link" type="text" id="s_link" value="" /></td>
            </tr>
		

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
