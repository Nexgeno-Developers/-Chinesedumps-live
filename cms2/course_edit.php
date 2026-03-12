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

	$objEvent	=	new classEvents($objDBcon);	   // VALIDATION CLASS classCategory

	$cInfo = mysql_query("select * from tbl_course where id ='".$_GET['id']."'");
	$get_course = mysql_fetch_assoc($cInfo);

	$strError	=	"";

	$spram[0]		 =  $_GET['id'];

	$farr			=	array("'");

	$aarr			=	array("\'");

	if(isset($_POST['Submit']) && $_POST['Submit']  == "Update")
	{

			$spram[1]	=	$_POST['course_name'];

			$spram[2]	=	$_POST['course_status'];

			$objValid->add_text_field("Course Name", $_POST['course_name'], "text", "y");


		if(empty($strError))
		{

				$addevent = $objEvent->updateCourse($spram);
				
				if($addevent){
					if($get_course['status'] != $spram[2]){
						if($spram[2] == 1){
							$msg = 'Your '.$spram[1].' is Stable now. <br>		';
							$msg .= 'PASS PASS PASS';
						}else{
							$msg = 'Your '.$spram[1].' is unstable now please hold until it will be stable. <br>';
							$msg .= 'Once it will be stable we will be notifying you and then you can hit the exam immediately. <br>';
							
						}
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							// Create email headers
						$fromEmail = from_email();
						$headers .= 'From: ' . $fromEmail . "\r\n" .
								'Reply-To: ' . $fromEmail . "\r\n" .
								'X-Mailer: PHP/' . phpversion();  
				// 		$headers .= 'From: support@chinesedumps.com'."\r\n".
				// 				'Reply-To: support@chinesedumps.com'."\r\n" .
				// 				'X-Mailer: PHP/' . phpversion();  
								
					  mail($fromEmail, "Stable Unstable Status of " . $spram[1], $msg, $headers);
				// 	  mail("chinesexams@gmail.com","Stable Unstable Status of ".$spram[1],$msg,$headers);
								// To send HTML mail, the Content-type header must be set
				 
						
					
						$course_subs_sql = mysql_query("select * from tbl_course_subscription where course = '".$spram[1]."' AND status_sbc =0");
						//$subs_mail = 'tamirkhan2@gmail.com';
						for($i = 0; $i < mysql_num_rows($course_subs_sql); $i++){
						
							$subs_user = mysql_fetch_object($course_subs_sql);
								 $msg1 = ' <br><br><br>To unsubscribe <a href="' . BASE_URL . 'stable-unstable-unsubscribe.php?id='.$subs_user->email.'&course_name='.$subs_user->course.'">Click here</a>';
							mail($subs_user->email,"Stable Unstable Status of ".$spram[1],$msg.$msg1,$headers);  
					 }
						 
					}
				}
			
				$strError	= "Course has been updated successfully";

				header("location:courses_status_manage.php");

		}

	}
	
	
	
?>



<? include ("header.php"); ?>

<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" class="leftside"><?php include ("menu.php"); ?></td>



<td width="810" class="rightside"><h2>Edit Course!</h2>



Welcome to your<?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.<br />



<form name="Form" id="Form" method="post" action="" enctype="multipart/form-data"><br />



  <table cellpadding='0' cellspacing='0' width='100%'>

    <tr>

      <td></td>

    </tr>

    <tr>

      <td class='header'>Edit Course </td>

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

          <td colspan="2"><input  name="course_name" id="course_name" type="text" value="<?= $get_course['name']; ?>" /></td>

        </tr>

        <tr>

          <td align="right">* Course Name:</td>

          <td colspan="2">
					<select name="course_status" id="course_status" class="required">

            <option value="1" <?php if( $get_course['status']== 1 ) echo 'selected="selected"';?>>Stable</option>

            <option value="0" <?php if( $get_course['status']== 0 ) echo 'selected="selected"';?>>Unstable</option>

          </select>
					</td>

        </tr>

        <tr align="right">

          <td align="left">&nbsp;</td>

          <td colspan="2" align="left"><input type="submit" name="Submit" value="Update" /></td>

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
