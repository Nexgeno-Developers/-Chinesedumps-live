<?PHP

ini_set("display_errors","0");

//----------------------------------------------------------------------------------------------------		

	session_start();

	error_reporting(0);

//----------------------------------------------------------------------------------------------------		

	$strTitle	=	"ShowNews";

//----------------------------------------------------------------------------------------------------		

include ("../includes/config/classDbConnection.php");

include ("../includes/common/classes/classmain.php");

include ("../includes/common/inc/sessionheader.php");

include("../includes/common/classes/classPagingAdmin.php");

include ("../includes/common/classes/classProduct.php");

include ("../includes/common/classes/classEvents.php");

include_once 'functions.php';

$ccAdminData = get_session_data();



//----------------------------------------------------------------------------------------------------		

						/*General Coding Area*/

//----------------------------------------------------------------------------------------------------		

	$objDBcon   = 	new classDbConnection; // VALIDATION CLASS OBJECT

	$objMain	=	new classMain($objDBcon);

	$objProduct	=	new classProduct($objDBcon);

	$objEvent	=	new classEvents($objDBcon);

	

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

	if(isset($_POST['Search'])){

	$rowsPerPage      	= 	1000000;	

	}else{

		$rowsPerPage      	= 	50;											

		}

	

	$linkPerPage      	= 	10;

	$of					=	"of";

//----------------------------------------------------------------------------------------------------		



		if(!isset($pgObj) && empty($pgObj))

				$pgObj 		=  new classPaging ("courses_status_manage.php",$rowsPerPage,$linkPerPage,"","","");

		

		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))

				{

					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			

					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				

				}	



					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;

//----------------------------------------------------------------------------------------------------		

	if(isset($_GET['action'])&& $_GET['action'] == "up")

	{

		$objEvent->delete_course_manage($_GET['nid']);

	}


?>

<? include ("header.php"); ?>

<script language="JavaScript" src="js/categorystatusnew.js"></script>

<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" valign="top" class="leftside"><?php include("menu.php"); ?></td>

<td width="810" valign="top" class="rightside"><h2>Stable Courses Management</h2>

Welcome to your <?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.

<br />

<form id="form1" name="form1" method="post" action="">



<br />

<table cellpadding='0' cellspacing='0' class='list' width='90%'>

   <tr>

    <td height="19" align="center" colspan="6">&nbsp;</td>

   </tr>

  <tr>

     <td colspan="5"  align="center">
      

	  </td>

	  <td align="right" style="padding: 16px;"><a href="course_add.php" class="btn-add"><i class="fa-solid fa-plus"></i> Add Course</a></td>

    </tr>

  <tr>

    <td height="19" align="center" colspan="6">&nbsp;</td>

   </tr>

  <tr>

	<td class='header' width='33%' align="center">Course Name </td>

  <td class='header' width='33%' align="center">Status </td>

	<td width="33%" align="center" class='header'>Option</td>

    </tr>

  <!-- LOOP THROUGH USERS -->


<?php $sqlcourse = mysql_query("select * from tbl_course");

$course_count = mysql_num_rows($sqlcourse);
for($sr = 0; $sr < mysql_num_rows($sqlcourse); $sr++){

$course = mysql_fetch_object($sqlcourse);
 ?>


 <tr>
    <td class="item" style="padding-left:" 0px;="" align="center"><?= $course->name?></td>
		<td class="item" style="padding-left:" 0px;="" align="center"><?= ($course->status == 1) ? 'Stable' : ''; ?><?= ($course->status == 0) ? 'Unstable' : ''; ?></td>
    <td class="item" nowrap="nowrap" align="center"><a href="course_edit.php?id=<?= $course->id?>">Edit </a>| <a href="courses_status_manage.php?action=up&nid=<?= $course->id?>" onclick="return delete_pro();">Delete</a></td>
</tr>

<?php } ?>

</table>

<table>

<tr> 

<td valign="top" align="center" class="rightside" ><div align="center"><strong>&nbsp;&nbsp; 

	  <?=$BackNaviLinks;?>

	  &nbsp;&nbsp; 

	  <?=$NaviLinks;?>

	  &nbsp;&nbsp; 

	  <?=$ForwardNaviLinks;?> </strong><br />

                  Total Records Found &nbsp;

		<?=$course_count;?><br />

		</div></td><br />

		<tr>

		<td valign="top" class="rightside"  align="center"><div align="center"><?=$emptyError?></div></td>

		</tr>

</table>

</form></td>



</table>

<? include("footer.php")?>

</body>

</html>

