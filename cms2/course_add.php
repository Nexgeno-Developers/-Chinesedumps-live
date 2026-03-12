<?PHP 

ob_start();

session_start();



	include ("../includes/config/classDbConnection.php");

	include("../includes/common/classes/validation_class.php");

	include("../includes/common/functions/func_uploadimg.php");

	include ("../includes/common/inc/sessionheader.php");

	include("../includes/common/classes/classUser.php");

	include("../includes/common/classes/classEvents.php");

	include("../functions.php");



include_once 'functions.php';

$ccAdminData = get_session_data();





//--------------------Creat objects---------------------------



	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT

	$objValid 	 = new Validate_fields;

	$objEvent	=	new classEvents($objDBcon);	// VALIDATION CLASS classCategory

	$strError	=	"";

	

	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")



	{

		$spram[0]	=	$_POST['course_name'];

		$spram[1]	=	$_POST['course_status'];

		$spram[2]	=	date('Y-m-d H:i:s');

		$objValid->add_text_field("Course Name", $_POST['course_name'], "text", "y");



		if (!$objValid->validation())



		$strError = $objValid->create_msg();

		

		if(empty($strError))

		{

		

		$addevent = $objEvent->addCourse($spram);

		

		$strError	= "Course has been added successfully";

		}

	}
	
?>
<? include ("header.php"); ?>
<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" class="leftside"><?php include ("menu.php"); ?></td>



<td width="810" class="rightside"><h2>Add Course!</h2>



Welcome to your<?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.<br />



<form name="Form" id="Form" method="post" action="" enctype="multipart/form-data"><br />



  <table cellpadding='0' cellspacing='0' width='100%'>

    <tr>

      <td></td>

    </tr>

    <tr>

      <td class='header'>Add Course </td>

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

          <td align="right">* Course Name:</td>

          <td colspan="2"><input type="text" name="course_name" value="" id="course_name" class="required" /></td>

        </tr>

        <tr>

          <td align="right"> Course Status:</td>

				<td>
					<select name="course_status" id="course_status" class="required">

            <option value="1">Stable</option>

            <option value="0">Unstable</option>

          </select>
				</td>

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