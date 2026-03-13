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
<style>
TABLE.list TR TD.item {
	padding: 12px;
	font-size: 14px;
	color: #1e293b;
	border-bottom: 1px solid #e2e8f0;
}
TABLE.list TR TD.item a {
	color: #3c85ba;
	text-decoration: none;
	font-weight: 500;
	transition: color 0.2s ease;
	margin: 0 4px;
}
TABLE.list TR TD.item a:hover {
	color: #2d6a9a;
	text-decoration: underline;
}
TABLE.list TR:last-child TD.item {
	border-bottom: none;
}
.badge {
	display: inline-block;
	padding: 4px 12px;
	border-radius: 12px;
	font-size: 12px;
	font-weight: 600;
	text-transform: uppercase;
}
.badge-stable {
	background: #d1fae5;
	color: #065f46;
}
.badge-unstable {
	background: #fee2e2;
	color: #991b1b;
}
</style>
<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" valign="top" class="leftside"><?php include("menu.php"); ?></td>

<td width="810" valign="top" class="rightside">
<div style="margin-bottom: 32px;">
	<h2 style="margin: 0 0 8px 0; font-size: 28px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 12px;">
		<i class="fa-solid fa-graduation-cap" style="color: #3c85ba;"></i>
		Stable Courses Management
	</h2>
	<p style="margin: 0; color: #64748b; font-size: 14px;">Manage and organize your courses. You can add, edit, or remove courses from this page.</p>
</div>

<form id="form1" name="form1" method="post" action="">

<div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
	<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
		<div style="flex: 1;">
			<h3 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 600; color: #1e293b;">Courses List</h3>
			<p style="margin: 0; font-size: 13px; color: #64748b;">Total: <strong><?=$course_count;?></strong> course<?= $course_count != 1 ? 's' : '' ?></p>
		</div>
		<div>
			<a href="course_add.php" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 20px; font-size: 14px; font-weight: 600; color: #ffffff; background: #333; border-radius: 8px; text-decoration: none; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
				<i class="fa-solid fa-plus"></i>
				Add Course
			</a>
		</div>
	</div>

<div style="overflow-x: auto;">
<table cellpadding='0' cellspacing='0' class='list' width='100%' style="border-collapse: collapse;">

  <tr>

	<td class='header' align="center" style="padding: 12px;"><i class="fa-solid fa-book" style="margin-right: 6px;"></i>Course Name</td>

  <td class='header' align="center" style="padding: 12px;"><i class="fa-solid fa-toggle-on" style="margin-right: 6px;"></i>Status</td>

	<td align="center" class='header' style="padding: 12px;"><i class="fa-solid fa-gear" style="margin-right: 6px;"></i>Actions</td>

    </tr>

  <!-- LOOP THROUGH USERS -->


<?php $sqlcourse = mysql_query("select * from tbl_course");

$course_count = mysql_num_rows($sqlcourse);
for($sr = 0; $sr < mysql_num_rows($sqlcourse); $sr++){

$course = mysql_fetch_object($sqlcourse);
 ?>


 <tr>
    <td class="item" align="center" style="padding: 12px;"><?= $course->name?></td>
		<td class="item" align="center" style="padding: 12px;">
			<?php if($course->status == 1): ?>
				<span class="badge badge-stable">Stable</span>
			<?php else: ?>
				<span class="badge badge-unstable">Unstable</span>
			<?php endif; ?>
		</td>
    <td class="item" nowrap="nowrap" align="center" style="padding: 12px;">
		<a href="course_edit.php?id=<?= $course->id?>">Edit</a> | 
		<a href="courses_status_manage.php?action=up&nid=<?= $course->id?>" onclick="return delete_pro();" style="color: #dc2626;">Delete</a>
	</td>
</tr>

<?php } ?>

</table>
</div>
</div>

<div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
	<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
		<div style="flex: 1;">
			<div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
				<?=$BackNaviLinks;?>
				<?=$NaviLinks;?>
				<?=$ForwardNaviLinks;?>
			</div>
			<div style="margin-top: 12px; display: flex; gap: 24px; flex-wrap: wrap;">
				<div style="display: flex; align-items: center; gap: 8px;">
					<i class="fa-solid fa-list" style="color: #64748b;"></i>
					<span style="color: #64748b; font-size: 14px;">Total Records: <strong style="color: #1e293b;"><?=$course_count;?></strong></span>
				</div>
			</div>
		</div>
		<div>
			<?=$emptyError?>
		</div>
	</div>
</div>

</form>
</td>



</table>

<? include("footer.php")?>

</body>

</html>

