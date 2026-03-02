<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

$pagez = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
$pagez .= "?".$_SERVER['QUERY_STRING'];
$page_outQuery = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

$getPage	=	$objMain->getContent(4);

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	
	$copyright	=	$exec['copyright'];
	
	function display_paging($total, $limit, $pagenumber)
	{
		// how many page numbers to show in list at a time
		$showpages = "8"; // 1,3,5,7,9...	
		// set up icons to be used
		// do calculations
		$pages = ceil($total / $limit);
		$offset = ($pagenumber * $limit) - $limit;
		$end = $offset + $limit;
	
		// prepare paging links
		$html = '<div class="pagination">';
		// if first link is needed
		if($pagenumber > 1) { 
			$previous = $pagenumber -1;
			$html .= '<a href="javascript:void(0);" onClick="GoToPage(1);" title="First Page">&laquo; First</a>';
	//$html .= '<a href="'.$baseurl.'1">'.$icon_first.'</a> ';
		}
		// if previous link is needed
		if($pagenumber > 2) {
			$previous = $pagenumber -1;
			$html .= '<a href="javascript:void(0);" onClick="GoToPage('.$previous.');" title="Previous Page">&laquo; Previous</a>';
		}
		// print page numbers
		if ($pages>=2) { 
			$p=1;
			//$html .= "| ";
			$pages_before = $pagenumber - 1;
			$pages_after = $pages - $pagenumber;
			$show_before = floor($showpages / 2);
			$show_after = floor($showpages / 2);
			if ($pages_before < $show_before){
				$dif = $show_before - $pages_before;
				$show_after = $show_after + $dif;
			}
			if ($pages_after < $show_after){
				$dif = $show_after - $pages_after;
				$show_before = $show_before + $dif;
			}   
			$minpage = $pagenumber - ($show_before+3);
			$maxpage = $pagenumber + ($show_after+3);
	
			if ($pagenumber > ($show_before+1) && $showpages > 0) {
				$html .= " ..... ";
			}
			while ($p <= $pages) {
				if ($p > $minpage && $p < $maxpage) {
					if ($pagenumber == $p) {
							$html .= '<a href="javascript:void(0);" class="number current" title="Page '.$p.'">'.$p.'</a>';					
					} else {
						$html .= '<a href="javascript:void(0);" onClick="GoToPage('.$p.');" class="number" title="Page '.$p.'">'.$p.'</a>';					
					}
				}
				$p++;
			}
			if ($maxpage-1 < $pages && $showpages > 0) {
				$html .= " ..... ";
			}
		}
		// if next link is needed
		if($end < $total) {
			$next = $pagenumber +1;
			if ($next != ($pages)) {
				$html .= '<a href="javascript:void(0);" onClick="GoToPage('.$next.');" title="Next Page">Next &raquo;</a>';
			}
		}
		// if last link is needed
		if($end < $total) { 
			$last = $pages;
				$html .= '<a href="javascript:void(0);" onClick="GoToPage('.$last.');" title="Last Page">Last &raquo;</a>';
		}
		$html .= '</div> <!-- End .pagination -->';
		// return paging links
		return $html;
	}
// pagging parameters default
	$currentpage = 1; // current page..started from 1
	$limit = 500; // records to be shown per page
	$start = 0; // start of query
	
	//set parameters dynamically if page is not one	
			if(isset($_POST["current_page"]) && $_POST["current_page"]>1)
			{
				$start = ($_POST["current_page"]-1) * $limit;
				$end = $limit;
				$currentpage=$_POST["current_page"];
			}
			else
			{
				$start = 0;
				$end = $limit;
			}
			?>
<script language="javascript">
//submit form to the same page using js
function GoToPage(page)
{
	document.getElementById('current_page').value=page;
	document.frmPaging.submit();
}
</script>
<style>
/*************** Pagination ***************/

.pagination {
                text-align: right;
                padding: 20px 0 5px 0;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 10px;
                }
.pagination a {
                margin: 0 5px 0 0;
                padding: 3px 6px;
                }

.pagination a.number {
				border: 1px solid #ddd;
                }

.pagination a.current {
/*                background: #469400 url('../images/bg-button-green.gif') top left repeat-x !important;*/
				background: #0D5289 !important;
                border-color: #0D5289 !important;
                color: #fff !important;
                }
				
.pagination a.current:hover {
				text-decoration: underline;
                }

</style>
<?php
	$queryPop		=	"SELECT * FROM tbl_vendors where ven_status='1' order by ven_name ASC";
	// query to get total number of records
	$res7 = mysql_query($queryPop);
	$total_pages=mysql_num_rows($res7);
	// listing query
	$queryPop .=" limit $start, $end";
	$result=mysql_query($queryPop)or die(mysql_error());
	
	$Products	=	$objPro->showVendorallpage($result,$websiteURL);
		
	$firstlink	=	" ".$getPage[1];
		
?>

<?php $this_page = "vendors" ?>
<? include("includes/header.php");?>
<script language="javascript" type="text/javascript">
function showHideDiv(divID)
    {
        var divstyle = new String();
        divstyle = document.getElementById(divID).style.display;
		if(divstyle.toLowerCase()=="inline" || divstyle == "")
        {
            document.getElementById(divID).style.display = "none";
        }
        else
        {
            document.getElementById(divID).style.display= "inline";
        }		  
    }
</script>
<div class="content-box">
        
        
        

        <div class="about-chinesedumps related-cert">
            <div class="max-width">
                <h1 class="center-heading"><span><?php echo $getPage[1]; ?></span></h1>
                <div class="black-heading"><?php echo $getPage[1]; ?></div>
                
                <ul class="popular-vendor-list group">
                    <li>
                        <ul class="group">
                          <?=$Products?>
                            
                        </ul>
                    </li>

   
                    
                </ul>            
            </div>
        </div>
	</div>


              <? include("includes/footer.php");?>