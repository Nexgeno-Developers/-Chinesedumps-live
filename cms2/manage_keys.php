<?php



ob_start();

session_start();



	include ("../includes/config/classDbConnection.php");

	include("../includes/common/functions/func_uploadimg.php");

	include ("../includes/common/inc/sessionheader.php");

	include("../includes/common/classes/classUser.php");

	include("../includes/common/classes/classPagingAdmin.php");



//---------------------------------------------------------------

	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT

	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory

	

include_once 'functions.php';

$ccAdminData = get_session_data();

//----------------------------------------------------------------------------------------------------	

	$show				=	"";

	$title				=	"";

	$resultset			=	"";

	$condition			=	"";

	$limist				=	"";

	$LIMIT				=	"";

	$TotalRecs	     	= 	0;	

	$NaviLinks        	= 	"";	

	$BackNaviLinks	 	= 	"";		

	$ForwardNaviLinks 	= 	"";		

	$TotalPages	      	= 	"";

	$PageNo		      	= 	1;			

	$PageIndex	      	= 	1 ;				

	$rowsPerPage      	= 	100;											

	$linkPerPage      	= 	25;

	$of					=	"of";

//----------------------------------------------------------------------------------------------------		

	$order_id = $_GET['id'];

	$page_heading="Manage Keys for order $order_id";

	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">

<head>

<title><?=$websitetitle?></title>

<link href="new.css" rel="stylesheet" type="text/css" />

<link href="style1.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.style1 {color: #FF0000}

-->

</style>

<script language="JavaScript" src="../includes/js/calendar.js"></script>

<script type="text/javascript" src="Editor3/scripts/innovaeditor.js"></script>

<script language="javascript" type="text/javascript">

function Cofirm()

{

var mes=window.confirm ("Are You Sure To Delete the Selected Item");

if (mes==true)

{

return true;

}

else

{

return false;

}

}

</script>

<script language="JavaScript" src="../includes/js/functions.js"></script>

<script language="JavaScript" src="js/categorystatusall.js"></script>

</head>

<body>



<div class="topbar">

<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td valign="top"><img src="images/admin_icon.png" border="0" alt="Admin Header" /></td>

<td valign="top" align="right">
<?php if(isset($_SESSION['strAdmin'])): ?>
	<div style="display: inline-block; vertical-align: middle;">
		<span style="color: #ffffff; margin-right: 15px; font-weight: 500;">Welcome, <?php echo htmlspecialchars($_SESSION['strAdmin']); ?></span>
		<a href="logout.php" style="color: #ffffff; text-decoration: none; padding: 8px 16px; background-color: rgba(255, 255, 255, 0.2); border-radius: 4px; font-weight: 500; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.2)'">Logout</a>
	</div>
<?php else: ?>
	<div style="display: inline-block; vertical-align: middle;">
		<a href="index.php" style="color: #ffffff; text-decoration: none; padding: 8px 16px; background-color: rgba(255, 255, 255, 0.2); border-radius: 4px; font-weight: 500; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.2)'">Login</a>
	</div>
<?php endif; ?>
</td>

</tr>

</table></div>



<table cellpadding="0" cellspacing="0" width="102%">

<tr>

<td width="190" class="leftside"><?php include ("menu.php"); ?></td>



<td width="810" valign="top" class="rightside"><h2><?=$page_heading?>!</h2>



Welcome to your <?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.



<br />



<br />
<br>


<form action="multi_keys_action.php" name="generateKeys" method="GET">
	<input type="text" name="cot" value="2" />
	<input type="hidden" name="id" value="<?=$_GET['id']?>" />
	<input type="hidden" name="act" value="gen" />
	<input type="submit" value="Generate" name="submit">
</form>


<table cellpadding='0' cellspacing='0' class='list' width='97%'>

	<table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-collapse: collapse" bordercolor="#000000">

			<tr>

			  <td width="5%" height="20" align="center" bgcolor="#D2DFF6" class="BdrgrnT BdrgrnB"><strong>ID</strong></td>

			  <td width="30%" height="20" align="center" bgcolor="#D2DFF6" class="BdrgrnT BdrgrnB"><strong>Package</strong></td>

			  <td width="30%" height="20" align="center" bgcolor="#D2DFF6" class="BdrgrnT BdrgrnB"><strong>Serial Number</strong></td>

			  <td width="7%" height="20" align="center" bgcolor="#D2DFF6" class="BdrgrnT BdrgrnB"><strong>M-Board</strong></td>

			  <td width="20%" height="20" align="center" bgcolor="#D2DFF6" class="BdrgrnT BdrgrnB"><strong>Expiry</strong></td>

			  <td width="8%" height="20" align="center" bgcolor="#D2DFF6" class="BdrgrnT BdrgrnB"><strong>Action</strong></td>

			</tr>




	<?php

		$sql_order = "SELECT order_detail.*, tbl_package_instance.id as instance_id, tbl_package_instance.engine_id, tbl_package_instance.serial_number, tbl_package_instance.mboard_number, tbl_package_instance.instance_expiry FROM  order_detail, tbl_package_instance where order_detail.ID='".$order_id."' and order_detail.status='1' and (order_detail.PTypeID='sp' or order_detail.PTypeID='both') and order_detail.ID =  tbl_package_instance.order_number order by tbl_package_instance.order_number desc;";

		$results = mysql_query($sql_order) or die(mysql_error());

		if(mysql_num_rows($results)>0)

		{

			?>

			<?php

			$old_order_id='';

			$instance=0;

			while($ordered_package = mysql_fetch_array($results))

			{

				$strSql 	=	"SELECT concat(exam_name,' - ',exam_fullname) as full_name FROM tbl_exam WHERE exam_id='".$ordered_package['ProductID']."'";

				$result		=	mysql_query($strSql) or die(mysql_error());

				$re 		=	mysql_fetch_array($result);

	?>



				  <form action="keys_action.php" method=post name="frmList" id="frmList">

			  <tr>

				  <td height="35" bgcolor="#FFFFFF" class="bodytext BdrgrnB"><?=$ordered_package['ID']?></td>

				  <td bgcolor="#FFFFFF" class="bodytext BdrgrnB"><strong><?=$re['full_name']?></strong></td>

				  <td bgcolor="#FFFFFF" class="bodytext BdrgrnB"><?=$ordered_package['serial_number']?></td>

				  <td bgcolor="#FFFFFF" class="bodytext BdrgrnB"><strong><?=$ordered_package['mboard_number']?></strong></td>

				  <td bgcolor="#FFFFFF" class="bodytext BdrgrnB">

				  <input type="hidden" name="id" value="<?=$ordered_package['ID']?>">

				  <input type="hidden" name="iid" value="<?=$ordered_package['instance_id']?>">

				  <input type="hidden" name="act" value="updexpiry">

				  <input type="text" value="<?=$ordered_package['instance_expiry']?>" name="expiry">

				  <input type=submit class=smallbuttons name="Change" value="Change">

				  </td>

				  <td bgcolor="#FFFFFF" class="bodytext BdrgrnB">[<a href="keys_action.php?id=<?=$ordered_package['ID']?>&act=reset&iid=<?=$ordered_package['instance_id']?>">Reset</a>]<br>[<a href="keys_action.php?id=<?=$ordered_package['ID']?>&act=gen&iid=<?=$ordered_package['instance_id']?>">Generate</a>]</td>

			  </tr>

				</form>

	<?php

			}

		}

		else

		{

			echo "You have not any Active order for Test Engine.";

		}

	?>

	</table>

	</table>

<br />

<?=$emptyError?>

<table>

<tr>  

<td valign="top" class="rightside">&nbsp;&nbsp; 

	 </td>

		<tr>

		<td valign="top" class="rightside"><div align="center"><strong>&nbsp;&nbsp; 

	  <?=$BackNaviLinks;?>

	  &nbsp;&nbsp; 

	  <?=$NaviLinks;?>

	  &nbsp;&nbsp; 

	  <?=$ForwardNaviLinks;?> </strong><br />

                  Total Records Found &nbsp;

		<?=$TotalRecs;?><br />

                  Total Pages Found &nbsp;

		<?=$TotalPages;?><br /><br />

		</div></td>



		</tr>







</table></td>







</table>

<? include("footer.php")?>

</body>



</html>