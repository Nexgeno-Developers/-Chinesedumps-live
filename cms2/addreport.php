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

		

	$farr			=	array("'");

	$aarr			=	array("\'");

	

	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")

	{

		$spram[0]	=	$_POST['exam'];

		

		$totalReport = mysql_query("select * from report_card where r_exam='".$spram[0]."'");

		$totalRep = mysql_num_rows($totalReport);

		$totalRep = $totalRep + 1;

		

		

		$spram[1]	=	$_FILES['exam_demo'];

		

			$getextension	=	substr($spram[1]['name'],-3);

			$newnamedemo	=	$totalRep."_".str_replace(" ","-",$spram[0]).'.'.$getextension;

		

		$spram[2]	=	$newnamedemo;

		$spram[3] = 1;

		$spram[4] = date('Y-m-d');

		

		mysql_query("insert into report_card (r_exam, r_image, r_status, r_date) values ('".$spram[0]."','".$spram[2]."','".$spram[3]."','".$spram[4]."')");



		



		if(empty($strError)){

			

			if($_FILES['exam_demo']['name']!=''){

					

				$demotype	=	$_FILES['exam_demo']['type'];

				

				if($demotype=='image/jpeg' || $demotype=='application/msword' || $demotype=='application/pdf'){

					

					if (!file_exists("../images/reports/".$spram[0])){mkdir("../images/reports/".$spram[0]);}

					

					copy($spram[1]['tmp_name'],"../images/reports/".$spram[0]."/".$newnamedemo);

					

				}else{

					

					$strError	.=	"<b>Error! Demo type is not valid.</b>";

				}

			}

			 

			//$addevent = $objEvent->addExam($spram);

			$strError	.= "Report has been added successfully";

			//header("location:exammanage.php");

		}

	}

		

		$category = $objEvent->fillComboCategory_exam($_POST['cmb_cate']);

		if(isset($_POST['cmb_cate'])){

		$subcat	=	$objEvent->fillComboSubCategory($_POST['cmb_subcategory'],$_POST['cmb_cate']);

		}else{

		$subcat	=	"<label><input type='checkbox' name='cmb_subcategory[]' value=''>---Select Vendor---</label>";

		}

		

		$exams = mysql_query("select * from tbl_exam order by exam_name");

?>

<? include ("header.php"); ?>

<script language="Javascript" src="js/category.js"></script>

<script language="JavaScript" src="../includes/js/calendar.js"></script>

<script language="javascript"  type="text/javascript">

	function validateCert(frm)

	{

		var bundleCount = frm.elements['cmb_subcategory[]'].length;

		var bundleSel   = false;

		for(i = 0; i < bundleCount; i++)

		{

			if(frm.elements['cmb_subcategory[]'][i].checked)

			{

				return true;

				break;

			}

		}

		if(!bundleSel)

		{

			alert('Select one or more Certification');

			return false;

		}

		return false;

	}

</script>

<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" class="leftside"><?php include ("menu.php"); ?></td>



<td width="810" class="rightside"><h2>Add Exam!</h2>



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

          <td align="right">* Exam Code:</td>

          <td colspan="2">

          <select class="form-control selectpicker" id="exam"  name="exam" data-live-search="true" required="required">

				<option value="null">Please Select Exam</option>

				<?php 

					for($e = 0;$e<mysql_num_rows($exams);$e++){

						$exam = mysql_fetch_object($exams);

				 ?>

				<option value="<?php echo $exam->exam_url; ?>"><?php echo $exam->exam_name ?></option>

				<?php } ?>

		 </select>

         </td>

        </tr>

             <tr>

              <td align="right"> Exam Demo:</td>

              <td colspan="2"><input name="exam_demo" type="file" id="exam_demo" value="" /></td>

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