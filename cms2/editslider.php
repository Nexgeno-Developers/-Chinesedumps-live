<?PHP 
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classEvents.php");

include_once 'functions.php';
$ccAdminData = get_session_data();

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
		$spram[9]		 =  "";
		
	$farr			=	array("'");
	$aarr			=	array("\'");

	$sInfo = mysql_query("select * from sliders where s_id ='".$_GET['ven_id']."'");
	$fetchSlider = mysql_fetch_array($sInfo);

	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")
	{

		$totalReport = mysql_query("select * from sliders ");
		$totalRep = mysql_num_rows($totalReport);
		$totalRep = $totalRep + 1;

		
			
		
		$spram[3] = $_POST["s_alt"];
		$spram[4] = $_POST["s_link"];
		
		$spram[5] = date('Y-m-d');
		$spram[6] = 1;
		$spram[7] = $_POST["oldBanner"];
		$spram[8]	=	$_FILES['exam_demo'];
		$spram[9]	=	$_POST['type'];

		$newnamedemo = $spram[7];

		if($_FILES['exam_demo']['name']!=''){

			$demotype	=	$_FILES['exam_demo']['type'];
			if($demotype=='image/jpeg' || $demotype=='application/msword' || $demotype=='application/pdf'){

				if (!file_exists("../images/slider/".$spram[0])){mkdir("../images/reports/".$spram[0]);}
				copy($spram[8]['tmp_name'],"../images/slider/".$spram[0]."/".$newnamedemo);

			}else{
				$strError	.=	"<b>Error! Banner type is not valid.</b>";
			}
			$spram[2] = $newnamedemo;
		}else{
			$spram[2] = $spram[7];
		}
		
		mysql_query("update sliders set type='".$spram[9]."', s_image='".$spram[2]."', s_alt='".$spram[3]."', s_link='".$spram[4]."' where s_id='".$_POST["sid"]."'");

		

			$strError	.= "Report has been added successfully";
		

	}


?>

<? include ("header.php"); ?>

<script language="Javascript" src="js/category.js"></script>

<script language="JavaScript" src="../includes/js/calendar.js"></script>



<table cellpadding="0" cellspacing="0" width="100%">

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
									<option value="slider" <?php if($fetchSlider['type']=='slider') echo 'selected="selected"';?>>Slider</option>
									<option value="proxy exams" <?php if($fetchSlider['type']=='proxy exams') echo 'selected="selected"';?>>Proxy Exams</option>
									<option value="test centers" <?php if($fetchSlider['type']=='test centers') echo 'selected="selected"';?>>Test Centers</option>
									<option value="lab" <?php if($fetchSlider['type']=='lab') echo 'selected="selected"';?>>Lab</option>
									<option value="written" <?php if($fetchSlider['type']=='written') echo 'selected="selected"';?>>Written</option>
								</select>
							</td>
						</tr>
						 <tr>
              <td align="right"> Banner:</td>
              <td colspan="2"><input name="exam_demo" type="file" id="exam_demo" value="" /></td>
            </tr>
			<tr>
              <td align="right"> Alt:</td>
              <td colspan="2"><input name="s_alt" type="text" id="s_alt" value="<?=$fetchSlider["s_alt"]?>" /></td>
            </tr>
			<tr>
              <td align="right"> Link:</td>
              <td colspan="2"><input name="s_link" type="text" id="s_link" value="<?=$fetchSlider["s_link"]?>" /></td>
            </tr>
		

        <tr align="right">

          <td align="left">&nbsp; <input type="hidden" name="oldBanner" value="<?=$fetchSlider["s_image"]?>">
          <input type="hidden" name="sid" value="<?=$fetchSlider["s_id"]?>"></td>

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