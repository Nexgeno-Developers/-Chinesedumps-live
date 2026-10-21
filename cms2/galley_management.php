<?PHP
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';

require_once dirname(__FILE__) . '/../includes/common/app_env.php'; app_apply_error_settings();

//----------------------------------------------------------------------------------------------------		

	session_start();

	error_reporting(0);

//----------------------------------------------------------------------------------------------------		

	$strTitle	=	"ShowNews";

//----------------------------------------------------------------------------------------------------		

include_once ("../includes/config/classDbConnection.php");

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

				$pgObj 		=  new classPaging ("galley_management.php",$rowsPerPage,$linkPerPage,"","","");

		

		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))

				{

					$PageNo			 = san_int($_GET['currentPage']);	# Shows # of Page			

					$PageIndex		 = san_int($_GET['pgIndex']);		# Shows # of Page Index				

				}	



					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;

//----------------------------------------------------------------------------------------------------		

	if(isset($_GET['action'])&& $_GET['action'] == "up")

	{

		$objEvent->delete_Banner_new($_GET['nid']);

	}

		

	if(isset($_POST['btn_delete']) && $_POST['btn_delete']	== "Delete Selected Items" ){		

		$counter	=	$_POST['counter']; 

		

		for($i=1; $i<=$counter; $i++)

			{ 

			

				if(isset($_POST['chkbox'.$i]))

				{

				$objEvent->delete_Banner_new($_POST['chkbox'.$i]);

				}

			}

			

		}

	$searchExamName = "";
	$whereClause = "";
	$searchConditions = array();
	if(isset($_POST['exam_name'])){
		$searchExamName = trim($_POST['exam_name']);
	}
	if($searchExamName !== ""){
		$searchConditions[] = "e.exam_name LIKE '%" . mysql_real_escape_string($searchExamName) . "%'";
	}
	if(!empty($searchConditions)){
		$whereClause = " WHERE " . implode(" AND ", $searchConditions);
	}
	$searchExamNameSafe = htmlspecialchars($searchExamName, ENT_QUOTES, 'UTF-8');


?>

<? include ("header.php"); ?>

<script language="JavaScript" src="js/categorystatusnew.js"></script>
<style>
.page-head-wrap{
	padding:20px 18px;
}
.page-head-row{
	display:flex;
	justify-content:space-between;
	align-items:center;
	gap:12px;
}
.page-head-title{
	margin:0 0 8px 0;
}
.page-head-sub{
	margin:0 0 16px 0;
	color:#64748b;
}
.theme-action-btn{
	display:inline-flex;
	align-items:center;
	gap:8px;
	padding:8px 20px;
	font-size:14px;
	font-weight:600;
	color:#ffffff;
	background:#333;
	border-radius:8px;
	text-decoration:none;
	position: relative;
	top:20px;
}
.theme-action-btn:link,
.theme-action-btn:visited,
.theme-action-btn:hover,
.theme-action-btn:active,
.theme-action-btn:focus{
	color:#ffffff;
	text-decoration:none;
}
</style>

<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" valign="top" class="leftside"><?php include ("menu.php"); ?></td>

<td width="810" valign="top" class="rightside page-head-wrap">
<div class="page-head-row">
	<h2 class="page-head-title">Exam Results Management</h2>
	<?php if(role_can('Exam Results Module', 'write')){ ?>
	<a href="add_gallery.php" class="theme-action-btn">
		<i class="fa-solid fa-plus"></i> Add Banner
	</a>
	<?php } ?>
</div>

<p class="page-head-sub">Welcome to your <?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.</p>

<form id="form1" name="form1" method="post" action="">


<table cellpadding='0' cellspacing='0' class='list' width='100%'>

   <tr>

    <td height="19" align="center" colspan="6">&nbsp;</td>

   </tr>

  <tr>

     <td colspan="5" align="left" style="padding: 12px;">
		<div style="display: flex; align-items: center; gap: 10px; width: 100%;">
			<input type="text" name="exam_name" id="exam_name" value="<?=$searchExamNameSafe;?>" placeholder="Exam Name" style="width: 70%; box-sizing: border-box;" />
			<input type="submit" name="Search" id="Search" value="Search" class="button" style="width: 15%; min-width: 110px;" />
			<a class="menu" href="galley_management.php" style="width: 15%; min-width: 110px; display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box;">Reset</a>
		</div>
	  </td>

	 
    </tr>


  <tr>

	<!--<td class='header' width='206' align="center">Group ID </td>-->
	<td class='header' width='206' align="center">Exam Name </td>

 <!-- <td class='header' width='171' align="center">Link </td>
	
  <td class='header' width='171' align="center">Type </td>

  <td class='header' width='171' align="center">Status </td>-->
  <td class='header' width='171' align="center">multiple </td>

	<td width="271" align="center" class='header'>Option</td>

    </tr>

  <!-- LOOP THROUGH USERS -->


<?php /*$sqlSlider = mysql_query("select * from sliders_new");

for($sr = 0; $sr < mysql_num_rows($sqlSlider); $sr++){

$slider = mysql_fetch_object($sqlSlider);
 ?>


 <tr>
   <td class="item" style="padding-left:" 0px;="" align="center"><?=$slider->group_id?></td>
	 <!--<td class="item" style="padding-left:" 0px;="" align="center"><?=$slider->s_link?></td>
	 <td class="item" style="padding-left:" 0px;="" align="center"><?=$slider->type?></td>
	 <td class="item" style="padding-left:" 0px;="" align="center"><a href="../images/slider/<?=$slider->s_image?>"><img src="../images/slider/<?=$slider->s_image?>" width="350"></a></td>-->
	 <td class="item" style="padding-left:" 0px;="" align="center"><a href="../images/slider_new/<?=$slider->filesb?>"><img src="../images/slider_new/<?=$slider->filesb?>" width="350"></a></td>
    <td class="item" nowrap="nowrap" align="center"><a href="gallery_new.php?group_id=<?=$slider->group_id?>">View By group ID </a><?php if(role_can('Exam Results Module', 'delete')){ ?>| <a href="galley_management.php?action=up&amp;nid=<?=$slider->s_id?>" onclick="return delete_pro();">Delete</a><?php } ?></td>
</tr>

<?php }*/  ?>

<?php 

$sqlSlider = mysql_query("
    SELECT 
        sc.cert_name,
        v.ven_name,
        e.exam_name,
        s.group_id,
        s.s_image,
        s.s_id
    FROM sliders_new s
    LEFT JOIN tbl_exam e ON s.group_id = e.exam_id
    LEFT JOIN tbl_cert sc ON e.cert_id = sc.cert_id
    LEFT JOIN tbl_vendors v ON e.ven_id = v.ven_id
	" . $whereClause . "
	ORDER BY e.exam_name ASC
");

for($sr = 0; $sr < mysql_num_rows($sqlSlider); $sr++){

$slider = mysql_fetch_object($sqlSlider);
 ?>


 <tr>
    <td class="item" style="padding-left:" 0px;="" align="center">
        <? //= $slider->group_id?> <? //= $slider->cert_name ?> <?  //= $slider->cert_name ?>
        <?= $slider->exam_name ?> 
    </td>
	<td class="item" style="padding-left:" 0px;="" align="center"><a taget="_blank" href="../images/slider/<?=$slider->s_image?>"><img src="../images/slider/<?=$slider->s_image?>" width="250"></a></td>
    <td class="item" nowrap="nowrap" align="center"><a href="gallery_new.php?group_id=<?=$slider->group_id?>">View By group ID </a>| <a href="galley_management.php?action=up&amp;nid=<?=$slider->s_id?>" onclick="return delete_pro();">Delete</a></td>
</tr>

<?php } ?>

</table>
</div>

<div class="pagination-container">
	<div class="pagination-left">
		<?php if(isset($emptyError) && !empty($emptyError)): ?>
			<?=$emptyError?>
		<?php else: ?>
			<span style="color: #64748b; font-size: 14px;">No record exist.</span>
		<?php endif; ?>
	</div>
	
	<div class="pagination-right">
		<div style="display: flex; gap: 8px; align-items: center; margin-bottom: 8px;">
			<strong><?=$BackNaviLinks;?></strong>
			<strong><?=$NaviLinks;?></strong>
			<strong><?=$ForwardNaviLinks;?></strong>
		</div>
		<p class="pagination-info">Total Records Found: <strong><?=$TotalRecs;?></strong></p>
		<p class="pagination-info">Total Pages Found: <strong><?=$TotalPages;?></strong></p>
	</div>
</div>

</form></td>



</table>

<? include("footer.php")?>

</body>

</html>


