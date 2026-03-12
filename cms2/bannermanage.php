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
// Handle “Save Order” submission: normalize per type (lab, proxy exams, slider, test centers, written)
if (isset($_POST['btn_save_order']) && $_POST['btn_save_order'] === "Save Order") {
    // 1) Collect posted values [ s_id => postedPriority ]
    $postedOrders = [];
    if (isset($_POST['order']) && is_array($_POST['order'])) {
        foreach ($_POST['order'] as $sliderId => $postedPriority) {
            $sid = intval($sliderId);
            $prio = intval($postedPriority);
            if ($prio < 1) {
                die("Error: Slider #{$sid} has an invalid order ({$prio}). Every order must be ≥ 1.");
            }
            $postedOrders[$sid] = $prio;
        }
    }

    // 2) Start a transaction and lock all rows
    mysql_query("START TRANSACTION") or die("Could not start transaction: " . mysql_error());
    mysql_query("SELECT s_id FROM sliders FOR UPDATE") or die("Could not lock sliders: " . mysql_error());

    // 3) Fetch all sliders (s_id, type, old banner_order)
    $resAll = mysql_query("
        SELECT s_id, `type`, banner_order
        FROM sliders
    ") or die("Could not fetch sliders: " . mysql_error());

    // 4) Bucket by type, assigning each slider a “key” = (postedPriority or old banner_order), then s_id
    $byType = [];
    while ($row = mysql_fetch_assoc($resAll)) {
        $sid      = intval($row['s_id']);
        $stype    = $row['type'];
        $oldOrder = intval($row['banner_order']);

        // Determine the “priority” for sorting: use posted value if provided, else the old value
        $priority = isset($postedOrders[$sid]) ? $postedOrders[$sid] : $oldOrder;

        // Store a tuple [priority, s_id] so ties break by s_id
        $byType[$stype][$sid] = [
            'priority' => $priority,
            'sid'      => $sid
        ];
    }

    // 5) For each type, sort by (priority ASC, then s_id ASC), and reindex to 1..N
    foreach ($byType as $stype => $map) {
        // Build an array of [sid => sortingKey], where sortingKey = priority + (sid/1000000)
        // (or simply use a usort on an array of tuples)
        $sortable = [];
        foreach ($map as $sid => $info) {
            $sortable[] = [
                'sid'      => $sid,
                'priority' => intval($info['priority'])
            ];
        }

        // Sort by priority ascending, then sid ascending
        usort($sortable, function($a, $b) {
            if ($a['priority'] !== $b['priority']) {
                return $a['priority'] - $b['priority'];
            }
            return $a['sid'] - $b['sid'];
        });

        // Reindex: assign banner_order = 1, 2, 3, … in the sorted sequence
        $newOrder = 1;
        foreach ($sortable as $item) {
            $sid = intval($item['sid']);
            mysql_query("
                UPDATE sliders
                SET banner_order = {$newOrder}
                WHERE s_id = {$sid}
            ") or die("Could not update slider #{$sid}: " . mysql_error());
            $newOrder++;
        }
    }

    // 6) Commit transaction
    mysql_query("COMMIT") or die("Could not commit transaction: " . mysql_error());

    // 7) Redirect to avoid resubmission
    header("Location: bannermanage.php");
    exit;
}

//----------------------------------------------------------------------------------------------------
// Handle single deletion via GET action=up&nid=...
if (isset($_GET['action']) && $_GET['action'] === "up" && isset($_GET['nid'])) {
    $nid = intval($_GET['nid']);
    $objEvent->delete_Banner($nid);
    header("Location: bannermanage.php");
    exit;
}


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

		$objEvent->delete_Banner($_GET['nid']);
	    // Redirect back to avoid form resubmission on reload
        header("Location: bannermanage.php");
        exit;
	}

		

	if(isset($_POST['btn_delete']) && $_POST['btn_delete']	== "Delete Selected Items" ){		

		$counter	=	$_POST['counter']; 

		

		for($i=1; $i<=$counter; $i++)

			{ 

			

				if(isset($_POST['chkbox'.$i]))

				{

				$objEvent->delete_Banner($_POST['chkbox'.$i]);

				}

			}

		    // Redirect back to avoid form resubmission on reload
            header("Location: bannermanage.php");
            exit;

		}


?>

<? include ("header.php"); ?>

<script language="JavaScript" src="js/categorystatusnew.js"></script>

<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" valign="top" class="leftside"><?php include ("menu.php"); ?></td>

<td width="810" valign="top" class="rightside"><h2>Banners Management!</h2>

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

	  <td align="right"><input type="submit" name="btn_save_order" value="Save Order" class="button" />&nbsp;&nbsp;<a class="menu" href="addslider.php"><img src="images/News-Add.gif" width="16" height="16" border="0" class="icon2" /> Add <span class="menu_header"> Banner </span></a>&nbsp;&nbsp;</td>

    </tr>

  <tr>

    <td height="19" align="center" colspan="6">&nbsp;</td>

   </tr>

    <tr>
    
        <td class='header' width='206' align="center">Alt Text </td>
        
        <td class='header' width='171' align="center">Link </td>
        
        <td class='header' width='171' align="center">Type </td>
        
        <td class="header" width="80" align="center">Banner Order</td>
        
        <td class='header' width='171' align="center">Preview </td>
        
        <td width="271" align="center" class='header'>Option</td>
    
    </tr>

  <!-- LOOP THROUGH USERS -->


<?php 
// $sqlSlider = mysql_query("select * from sliders");

// for($sr = 0; $sr < mysql_num_rows($sqlSlider); $sr++){

// $slider = mysql_fetch_object($sqlSlider);

   // Fetch sliders ordered by (type, banner_order)
  $sqlAll = "SELECT * FROM `sliders` ORDER BY `type` ASC, `banner_order` ASC {$LIMIT}";
  $sqlSlider = mysql_query($sqlAll) or die(mysql_error());

  // Count total records (for pagination info)
  $countRes = mysql_query("SELECT COUNT(*) AS cnt FROM `sliders`");
  $countRow = mysql_fetch_assoc($countRes);
  $totalRows = intval($countRow['cnt']);

  $counter = 0;
  while ($slider = mysql_fetch_object($sqlSlider)) {
    $counter++;
    
 ?>


 <tr>
   <td class="item" style="padding-left:" 0px;="" align="center"><?=$slider->s_alt?></td>
	 <td class="item" style="padding-left:" 0px;="" align="center"><?=$slider->s_link?></td>
	 <td class="item" style="padding-left:" 0px;="" align="center"><?=$slider->type?></td>
	 <td class="item" align="center"><input type="number" name="order[<?= intval($slider->s_id) ?>]" value="<?= intval($slider->banner_order) ?>" style="width: 50px; text-align: center;" min="1" /></td>
	 <td class="item" style="padding-left:" 0px;="" align="center"><a href="../images/slider/<?=$slider->s_image?>"><img src="../images/slider/<?=$slider->s_image?>" width="350"></a></td>
    <td class="item" nowrap="nowrap" align="center"><a href="editslider.php?ven_id=<?=$slider->s_id?>">Edit </a>| <a href="bannermanage.php?action=up&amp;nid=<?=$slider->s_id?>" onclick="return delete_pro();">Delete</a></td>
</tr>

<?php } ?>

</table>

<table>
  <tr>
    <td valign="top" align="center" class="rightside">
      <div align="center">
        <?= $BackNaviLinks ?>&nbsp;&nbsp;<?= $NaviLinks ?>&nbsp;&nbsp;<?= $ForwardNaviLinks ?>
        <br />
        Total Records Found: <?= $totalRows ?><br />
        Total Pages: <?= $TotalPages ?><br /><br />
      </div>
    </td>
  </tr>
  <tr>
    <td valign="top" class="rightside" align="center">
      <div align="center"><?= $emptyError ?></div>
    </td>
  </tr>
</table>
        
<!--<table>-->

<!--<tr> -->

<!--<td valign="top" align="center" class="rightside" ><div align="center"><strong>&nbsp;&nbsp; -->

<!--	  <?=$BackNaviLinks;?>-->

<!--	  &nbsp;&nbsp; -->

<!--	  <?=$NaviLinks;?>-->

<!--	  &nbsp;&nbsp; -->

<!--	  <?=$ForwardNaviLinks;?> </strong><br />-->

<!--                  Total Records Found &nbsp;-->

<!--		<?=$TotalRecs;?><br />-->

<!--                  Total Pages Found &nbsp;-->

<!--		<?=$TotalPages;?><br /><br />-->

<!--		</div></td><br/>-->

<!--		<tr>-->

<!--		<td valign="top" class="rightside"  align="center"><div align="center"><?=$emptyError?></div></td>-->

<!--		</tr>-->

<!--</table>-->

</form></td>



</table>

<? include("footer.php")?>

</body>

</html>

