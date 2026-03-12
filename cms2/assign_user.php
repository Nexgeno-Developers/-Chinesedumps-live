<?php
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");
	include("../includes/common/classes/classPagingAdmin.php");
	include("../includes/common/classes/classPrices.php");
	include("../functions.php");
//---------------------------------------------------------------
	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory
	$objPricess = new classPrices();
	
	include_once 'functions.php';
	$ccAdminData = get_session_data();
	
	$msg = '';
//-----------------------------------------------------------------
	if(isset($_POST['action']) && $_POST['action']  == "Insert")
	{
		$user = ltrim(rtrim($_POST['sel_user']));
		$pmethod = $_POST['dmethod'];
		

		$q = mysql_query("select * from tbl_user where user_email='".$_POST['sel_user']."'");
		$qry = mysql_fetch_array($q);
		$user = $qry["user_id"];
		if(mysql_num_rows($q) != '0'){
			
			
		
		
		$exam = $_POST['exam_id'];
		$ptype = $_POST['ptype'];
		$duration = '0';

		$dayedate	=	date('Y-m-d');
	
		global $price_both, $price_sp;
		$strSql 	=	"SELECT * FROM tbl_exam WHERE exam_id='".$exam."'";
			$result		=	mysql_query($strSql) or die(mysql_error());
			$re 		=	mysql_fetch_array($result);
		
		$net_amount = $objPricess->getProductPrice($exam,$ptype,$duration);
	
		if($ptype=="sp")
			{
				$append = " (Testing Engine only)";
				$myexpiry = date('Y-m-d', strtotime('+12 days'));
			}
		elseif($ptype=="both"){
				$append = " (Secure PDC + Testing Engine)";
				$myexpiry = date('Y-m-d', strtotime('+12 days'));
			}
		else{
				$append = " (Secure PDC)";
				$myexpiry = date('Y-m-d', strtotime('+12 days'));
		}	
			
			$qry_temp = "insert into order_master(Cust_ID,Order_Id,OrderDate,Status,Net_Amount,Cart_ID,OrderDesc)values('".$user."','m_assigned','".$dayedate."','1','".$net_amount."','manual-assign','".$re['exam_name'].$append."')";
			mysql_query($qry_temp);
			$lastordid	=	mysql_insert_id();
			$query_order="insert into order_detail(UserID,masterid,Cart_ID,PaymentType, VendorID,TrackID,ProductID,Description,ExpireDate,strdate,submonth,status, PTypeID)values('".$user."','".$lastordid."','manual-assign','".$pmethod."','','','".$exam."','".$exam."','".$myexpiry."','".$dayedate."','0','1','".$ptype."')";
 			mysql_query($query_order);
			$order_id = mysql_insert_id();
			


			
		if($ptype=="sp" || $ptype=="both")
		{
			$dayedate	=	date('Y-m-d');
			$net_amount = $price_both;
			$append = " (Secure PDC + Testing Engine)";
			if($ptype=="sp")
			{
				$net_amount = $price_sp;
				$append = " (Testing Engine only)";
			}
			$strSql 	=	"SELECT exam_name FROM tbl_exam WHERE exam_id='".$exam."'";
			$result		=	mysql_query($strSql) or die(mysql_error());
			$re 		=	mysql_fetch_array($result);

				// Add an Instance for Test Engine
			$time = date("Y-m-d H:i:s");
			$serial_number = md5($order_id.$time);
			$expiry = date('Y-m-d', strtotime("+12 days"));
			$engine_arr = mysql_fetch_array(mysql_query("select id from tbl_package_engine where package_id = '{$exam}' and type='Simulator' and os = 'Win';"));
			$engine_id = $engine_arr['id'];
			if(!empty($engine_id))
			{
				$insert="insert into tbl_package_instance (id, engine_id, order_number, serial_number, mboard_number, instance_expiry) Values('', '$engine_id', '{$order_id}', '$serial_number', '', '$expiry');";
				$query=mysql_query($insert);
			}
		}
			
			
			
			
			
			$msg = "Record has been updated";
			header("Location: masterorder.php");
			
		}
		

			
			
			
		else{
			$str = "";
			$msg = "Email Not Found <a href='exammanage.php'>Try Again</a>";
			
		
		
		
		}
}
	if(isset($_GET['act']) && $_GET['act']=='del' && $_GET['id']  != "")
	{
		//mysql_query("delete from tbl_exam_user where id = '".$_GET['id']."'");
		header("Location: assign_user.php");
	}


	if(isset($_GET['exm_id']) && $_GET['exm_id']  != "")
	{

		$str = "Select User: ";
		$str .= "<input type='text' name='sel_user'>";
		$str .= "<br /><br /> Select Method";
		$str .= "
		<select name='dmethod'>
			<option value='Swreg' selected='selected'>Credit/DebitCard</option>
		</select>";
		
		$str .= "<br /><br />
		<input type='hidden' value='$_GET[exm_id]' name='exam_id' />
		<input type='hidden' value='$_GET[ptype]' name='ptype' />
		<input type='hidden' value='Insert' name='action' />
		<input type='submit' value=' Submit ' />";
	}
?>
<? include ("header.php"); ?>

<script language="JavaScript" src="js/categorystatusnew.js"></script>

<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" valign="top" class="leftside"><?php include ("menu.php"); ?></td>

<td width="810" valign="top" class="rightside"><h2>Assign Exam to User!</h2>

Welcome to your <?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.

<br />
<form id="form1" name="form1" method="post" action="assign_user.php">
<br />
<table>
		<tr>
		<td valign="top" class="rightside"><div align="center"><? echo $str;?></div></td>
		</tr>
</table>

<table>
		<tr>
		<td valign="top" class="rightside"><div align="center"><? echo $msg;?></div></td>
		</tr>
</table>
<table cellpadding='0' cellspacing='0' class='list' width='90%'>

    <tr>

    <td height="19" align="right" colspan="4">&nbsp;</td>

	</tr>
  <tr>

    <td width='33' class='header' align="center">&nbsp;</td>
      <td class='header' width='133'>User Name</td>
    <td class='header' width='133'>Exam Name</td>
	<td width="271" align="center" class='header'>&nbsp;</td>

    </tr>

  <!-- LOOP THROUGH USERS -->


</table>

</form></td>



</table>

<? include("footer.php")?>

</body>

</html>

