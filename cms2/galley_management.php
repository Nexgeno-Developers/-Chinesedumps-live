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

				$pgObj 		=  new classPaging ("vendormanage.php",$rowsPerPage,$linkPerPage,"","","");

		

		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))

				{

					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			

					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				

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


?>

<? include ("header.php"); ?>

<script language="JavaScript" src="js/categorystatusnew.js"></script>

<table cellpadding="0" cellspacing="0" width="90%">

<tr>

<td width="190" valign="top" class="leftside"><?php include ("menu.php"); ?></td>

<td width="810" valign="top" class="rightside"><h2>Banners Management Replica!</h2>

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

	  <td align="right"><a class="menu" href="add_gallery.php"><img src="images/News-Add.gif" width="16" height="16" border="0" class="icon2" /> Add <span class="menu_header"> Banner </span></a>&nbsp;&nbsp;</td>

    </tr>

  <tr>

    <td height="19" align="center" colspan="6">&nbsp;</td>

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
    <td class="item" nowrap="nowrap" align="center"><a href="gallery_new.php?group_id=<?=$slider->group_id?>">View By group ID </a>| <a href="galley_management.php?action=up&amp;nid=<?=$slider->s_id?>" onclick="return delete_pro();">Delete</a></td>
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

<table>

<tr> 

<td valign="top" align="center" class="rightside" ><div align="center"><strong>&nbsp;&nbsp; 

	  <?=$BackNaviLinks;?>

	  &nbsp;&nbsp; 

	  <?=$NaviLinks;?>

	  &nbsp;&nbsp; 

	  <?=$ForwardNaviLinks;?> </strong><br />

                  Total Records Found &nbsp;

		<?=$TotalRecs;?><br />

                  Total Pages Found &nbsp;

		<?=$TotalPages;?><br /><br />

		</div></td><br/>

		<tr>

		<td valign="top" class="rightside"  align="center"><div align="center"><?=$emptyError?></div></td>

		</tr>

</table>

</form></td>



</table>

<? include("footer.php")?>

</body>

</html>


