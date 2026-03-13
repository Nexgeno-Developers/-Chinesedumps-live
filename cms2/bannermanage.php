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

// Count total records (for pagination info) - moved before HTML output
$countRes = mysql_query("SELECT COUNT(*) AS cnt FROM `sliders`");
$countRow = mysql_fetch_assoc($countRes);
$totalRows = intval($countRow['cnt']);

?>

<? include ("header.php"); ?>

<script language="JavaScript" src="js/categorystatusnew.js"></script>

<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" valign="top" class="leftside"><?php include ("menu.php"); ?></td>

<td width="810" valign="top" class="rightside">
<div style="margin-bottom: 32px;">
	<h2 style="margin: 0 0 8px 0; font-size: 28px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 12px;">
		<i class="fa-solid fa-images" style="color: #3c85ba;"></i>
		Banners Management
	</h2>
	<p style="margin: 0; color: #64748b; font-size: 14px;">Manage and organize your website banners. You can reorder banners, edit details, or remove banners from this page.</p>
</div>

<form id="form1" name="form1" method="post" action="">

<div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
	<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
		<div style="flex: 1;">
			<h3 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 600; color: #1e293b;">Banner List</h3>
			<p style="margin: 0; font-size: 13px; color: #64748b;">Total: <strong><?= $totalRows ?></strong> banner<?= $totalRows != 1 ? 's' : '' ?></p>
		</div>
		<div style="display: flex; gap: 12px; align-items: center;">
			<button type="submit" name="btn_save_order" value="Save Order" class="button" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; font-size: 14px; font-weight: 600; color: #ffffff; background: linear-gradient(135deg, #3c85ba 0%, #2d6a9a 100%); border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(60, 133, 186, 0.2);">
				<i class="fa-solid fa-save"></i>
				Save Order
			</button>
			<a href="addslider.php" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; font-size: 14px; font-weight: 600; color: #ffffff; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); border-radius: 8px; text-decoration: none; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2);">
				<i class="fa-solid fa-plus"></i>
				Add Banner
			</a>
		</div>
	</div>
</div>

<table cellpadding='0' cellspacing='0' class='list' width='100%' style="margin-bottom: 24px;">

    <tr>
        <td class='header' align="center" style="padding: 16px 20px;">Alt Text</td>
        <td class='header' align="center" style="padding: 16px 20px;">Link</td>
        <td class='header' align="center" style="padding: 16px 20px;">Type</td>
        <td class="header" align="center" style="padding: 16px 20px; min-width: 120px;">Order</td>
        <td class='header' align="center" style="padding: 16px 20px; min-width: 200px;">Preview</td>
        <td class='header' align="center" style="padding: 16px 20px; min-width: 150px;">Actions</td>
    </tr>

  <!-- LOOP THROUGH USERS -->


<?php 
// $sqlSlider = mysql_query("select * from sliders");

// for($sr = 0; $sr < mysql_num_rows($sqlSlider); $sr++){

// $slider = mysql_fetch_object($sqlSlider);

   // Fetch sliders ordered by (type, banner_order)
  $sqlAll = "SELECT * FROM `sliders` ORDER BY `type` ASC, `banner_order` ASC {$LIMIT}";
  $sqlSlider = mysql_query($sqlAll) or die(mysql_error());

  $counter = 0;
  while ($slider = mysql_fetch_object($sqlSlider)) {
    $counter++;
    
 ?>


 <tr style="transition: background-color 0.2s ease;">
   <td class="item" style="padding: 16px 20px; vertical-align: middle;">
		<div style="font-weight: 500; color: #1e293b;"><?= htmlspecialchars($slider->s_alt) ?: '<span style="color: #94a3b8; font-style: italic;">No alt text</span>' ?></div>
	</td>
	<td class="item" style="padding: 16px 20px; vertical-align: middle;">
		<?php if ($slider->s_link): ?>
			<a href="<?= htmlspecialchars($slider->s_link) ?>" target="_blank" style="color: #3c85ba; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
				<i class="fa-solid fa-link" style="font-size: 12px;"></i>
				<span style="overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($slider->s_link) ?></span>
			</a>
		<?php else: ?>
			<span style="color: #94a3b8; font-style: italic;">No link</span>
		<?php endif; ?>
	</td>
	<td class="item" style="padding: 16px 20px; vertical-align: middle;">
		<span style="display: inline-block; padding: 4px 12px; background: #e0f2fe; color: #0369a1; border-radius: 6px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
			<?= htmlspecialchars($slider->type) ?>
		</span>
	</td>
	<td class="item" style="padding: 16px 20px; vertical-align: middle; text-align: center;">
		<input type="number" name="order[<?= intval($slider->s_id) ?>]" value="<?= intval($slider->banner_order) ?>" style="width: 70px; padding: 8px; text-align: center; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 14px; font-weight: 600; color: #1e293b; background: #ffffff; transition: all 0.2s ease;" min="1" onfocus="this.style.borderColor='#3c85ba'; this.style.boxShadow='0 0 0 3px rgba(60, 133, 186, 0.1)';" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';" />
	</td>
	<td class="item" style="padding: 16px 20px; vertical-align: middle; text-align: center;">
		<?php if ($slider->s_image): ?>
			<a href="../images/slider/<?= htmlspecialchars($slider->s_image) ?>" target="_blank" style="display: inline-block; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.15)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.1)';">
				<img src="../images/slider/<?= htmlspecialchars($slider->s_image) ?>" alt="<?= htmlspecialchars($slider->s_alt) ?>" style="max-width: 200px; max-height: 100px; display: block; object-fit: cover;" />
			</a>
		<?php else: ?>
			<span style="color: #94a3b8; font-style: italic;">No image</span>
		<?php endif; ?>
	</td>
    <td class="item" style="padding: 16px 20px; vertical-align: middle; text-align: center;">
		<div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
			<a href="editslider.php?ven_id=<?= intval($slider->s_id) ?>" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #3c85ba; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 500; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(60, 133, 186, 0.3);" onmouseover="this.style.background='#2d6a9a'; this.style.boxShadow='0 2px 6px rgba(60, 133, 186, 0.4)';" onmouseout="this.style.background='#3c85ba'; this.style.boxShadow='0 1px 3px rgba(60, 133, 186, 0.3)';">
				<i class="fa-solid fa-pencil" style="font-size: 12px;"></i>
				Edit
			</a>
			<a href="bannermanage.php?action=up&amp;nid=<?= intval($slider->s_id) ?>" onclick="return delete_pro();" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #dc2626; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 500; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(220, 38, 38, 0.3);" onmouseover="this.style.background='#b91c1c'; this.style.boxShadow='0 2px 6px rgba(220, 38, 38, 0.4)';" onmouseout="this.style.background='#dc2626'; this.style.boxShadow='0 1px 3px rgba(220, 38, 38, 0.3)';">
				<i class="fa-solid fa-trash" style="font-size: 12px;"></i>
				Delete
			</a>
		</div>
	</td>
</tr>

<?php } ?>

<?php if ($counter == 0): ?>
<tr>
	<td colspan="6" class="item" style="padding: 48px 20px; text-align: center;">
		<div style="color: #94a3b8;">
			<i class="fa-solid fa-images" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
			<p style="font-size: 16px; font-weight: 500; margin: 0 0 8px 0; color: #64748b;">No banners found</p>
			<p style="font-size: 14px; margin: 0; color: #94a3b8;">Get started by adding your first banner.</p>
			<a href="addslider.php" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 16px; padding: 10px 20px; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.2s ease;">
				<i class="fa-solid fa-plus"></i>
				Add Your First Banner
			</a>
		</div>
	</td>
</tr>
<?php endif; ?>

</table>

<div style="background: #ffffff; border-radius: 12px; padding: 20px; margin-top: 24px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
  <div style="text-align: center;">
    <div style="margin-bottom: 12px;">
        <?= $BackNaviLinks ?>&nbsp;&nbsp;<?= $NaviLinks ?>&nbsp;&nbsp;<?= $ForwardNaviLinks ?>
    </div>
    <div style="display: flex; justify-content: center; gap: 24px; flex-wrap: wrap; font-size: 14px; color: #64748b;">
      <div>
        <strong style="color: #1e293b;">Total Records:</strong> <?= $totalRows ?>
      </div>
      <?php if ($TotalPages): ?>
      <div>
        <strong style="color: #1e293b;">Total Pages:</strong> <?= $TotalPages ?>
      </div>
      <?php endif; ?>
    </div>
    <?php if (isset($emptyError) && $emptyError): ?>
    <div style="margin-top: 16px;">
      <?= $emptyError ?>
    </div>
    <?php endif; ?>
  </div>
</div>
        
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

