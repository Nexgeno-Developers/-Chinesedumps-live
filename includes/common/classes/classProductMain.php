<?PHP



	

	error_reporting(0);



	class classProduct

	{

		//--------------------------------------------------------------------------------

								// Class Properties (Class Level Variables)

		//--------------------------------------------------------------------------------

			var $objDbConn		;

			var	$rowsPerPage 	;

			var $linkPerPage	;

			var	$PageNo			; 	# Shows # of Page			

			var $PageIndex	 	;	# Shows # of Page Index				

//..........................................................................................................



		function classProduct($objDbConn)

		{

			$this->objDbConn	= 	$objDbConn; 

		}

//.................................................................................................

		function getVendorsallpage()

		{

			$strSql		=	"SELECT * FROM tbl_vendors where ven_status='1' order by ven_name ASC";

			$result		=	mysql_query($strSql);

			return $result;

		}

//.................................................................................................

		function showVendorallpage($result,$basepath)

			{

				$count		=	0;

				$count1		=	1;

				$showTr		=	"";

				$count12	=	1;

				$showTr	.='';

				

				$total	=	mysql_num_rows($result);

					while($row=mysql_fetch_array($result))

					{

					

				$page		= 	$row['ven_url'];

				$url		=	$basepath."".str_replace(' ', '-',$page).".htm";

				$title		=	$row['ven_name']." Certification";

				

				

				if($count12=='1'){

				$showTr	.='';

				}

				$showTr	.='

				<li><a title="'.$title.'" style=" color:#3E3E3E;text-decoration:none;"  href="'.$url.'"><span>&nbsp;</span>'.str_replace("-"," ",strtoupper($row['ven_name'])).'</a></li>';

				if($count12=='3'){

				$showTr	.='';

				$count12	=	'0';

				}else{

					

					if($count1==$total && $count12=='2'){

					$showTr	.='';

					}

					if($count1==$total && $count12=='1'){

					$showTr	.='';

					}

						

				}

				$count12++;

			   $count ++;

			   $count1++;

					}

				

				$showTr	.='';

				

				return $showTr;

			}

//.................................................................................................

	function getTrackID($venName)

	{

	  $sql			=	"SELECT * FROM tbl_cert where cert_url='".$venName."'";

	  $rs			=	 mysql_query($sql);

	  $row			= 	 mysql_fetch_array($rs);

	  return $row;

	}

	function getVendorsID($venName)

	{

	  $sql			=	"select * from tbl_vendors where ven_status='1' and ven_url='".$venName."'";

	  $rs			=	 mysql_query($sql);

	  $row			= 	 mysql_fetch_array($rs);

	  return $row;

	}

	function getAllReorderProducts($userId)

	{

		$strQury	= "select * from order_master where Cust_ID='".$userId."' ";	

		$rs_order			= mysql_query($strQury);

		return $rs_order;

		

	}

	//---------------------------------------------------------------------------------------//

function getAllOrder($userId)

		{

 		$strQury	= "select * from order_master where Cust_ID='".$userId."' and Status='1'";	

		$rs_order	= mysql_query($strQury);

		return $rs_order;

		}		

//--------------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------------//

function showOrders($result)

{

				$showTr	='';

				$counter = 1;


					

				while($row_order=mysql_fetch_array($result))

				{	

				if($row_order['Status']==1)

					{

					$order_status	=	"Active";

					}

					else

					{

					$order_status	=	"Expired";

					}

					

					$expo	=	explode('<br/>',$row_order['OrderDesc']);

					$totalexpo	=	count($expo);

					$newexpo	=	$totalexpo-1;

					

					//$getpro	=	mysql_fetch_array(mysql_query("SELECT * from product where ID='".$getorderdet['TrackID']."'"));

										

					$descount	=	$row_order['Gross_Amount']-$row_order['Net_Amount'];

			$getorderdet	=	mysql_query("SELECT * from order_detail where masterid='".$row_order['ID']."' and UserID='".$_SESSION['uid']."'");

	$roword	=	mysql_fetch_array($getorderdet);		

					$showTr		.='<li>  <table width="100%"  cellpadding="6"  style="margin-bottom:8px;padding-bottom:8px;" >

					 <td width="120" nowrap="nowrap" style=" color:#565655;" width:><strong>Order Number </strong></td>

    <td width="196">'.$counter.'</td>

    <td width="120" style=" color:#565655;"><strong></strong></td>

    <td width="196"></td>

  </tr>

	 <td width="120" style=" color:#565655; " ><strong>Order Date</strong></td>

    <td width="196">'.$row_order['OrderDate'].'</td>

    <td width="120" style=" color:#565655;"  ><strong>N/Amount</strong></td>

    <td width="196">$'.$row_order['Net_Amount'].'</td>

  </tr>

  <tr>

   <td width="120" nowrap="nowrap" style=" color:#565655;" width:><strong>Order ID </strong></td>

    <td width="196">'.$roword['ID'].'</td>

   

   <td width="120" style=" color:#565655;" ><strong>Status</strong></td>

    <td width="196">'.$order_status.'</td>

  </tr>

  <tr>

   <td width="120" style=" color:#565655;" ><strong>Paypal ID</strong></td>

    <td width="196">'.$row_order['Order_Id'].'</td>

   <td width="120" style=" color:#565655;" ><strong>Description</strong></td>

    <td width="196">';


	

	//while($roword	=	mysql_fetch_array($getorderdet)){

	$showTr		.= $row_order['OrderDesc'];

	if(date("Y-m-d") > $roword['ExpireDate'] && $roword['ExpireDate']!='0'){

	$showTr		.='<br><font style="color:red; font-weight:bold;">Expire</font>&nbsp;';

	}

	

	//}

	

	$showTr		.='</td></tr></table></li>';	

						   $counter++;	

								}

						 		

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

					

				}#End of showOrder Function

	//-----------------------show re order records----------------------------------------------

	function showReOrders($result,$userId,$basepath)

	{

	  while($row=mysql_fetch_array($result))

		{

		 $current              = date('Y-m-d');

		//echo $orderDate       = $row['OrderDate']."<br>";

		 $ExpireDate     = $row['ExpiryDate'];

		if($current>$ExpireDate)

		{

			

			$sql_detail = " select * from order_detail  where UserID = '".$userId."' and ExpireDate = '".$ExpireDate."' ";

			//$sql = "select * from order_master where Cust_ID='".$userId."' and SiteID ='".$siteId."'";

			$rs_detail  = mysql_query($sql_detail);

			//echo mysql_num_rows($rs_detail);

			

			$row_pdetail = mysql_fetch_array($rs_detail); 

			

			$typID =$row_pdetail['PTypeID'];

			if($typID==0)

			{

			 $sql_pro = "select * from tblproducts  where ProductID = '".$row_pdetail['ProductID']."' ";

			 $rs_pro  = mysql_query($sql_pro);

			 $row_pro = mysql_fetch_array($rs_pro);

			 

			$sql_ptyp ="select * from tblproductsdetail where ProductID = '".$row_pdetail['ProductID']."' ";

			$rs_ptyp  = mysql_query($sql_ptyp);

			while($row_ptyp=mysql_fetch_array($rs_ptyp))

				{

				 $query = "select * from tblproducttypes  where ProductTypeID  = '".$row_ptyp['ProductTypeID']."'";

				 $rs_q  = mysql_query($query);

				 $row_q = mysql_fetch_array($rs_q);

				 

				 

				 $reorder="<a href='".$basepath."reorder.htm'>Re-Order</a>";

				

				$showTR .="<tr>

							  <td height='25' class='indexmaindata'>".$row_pro['ProductCode']."</td>

							  <td class='indexmaindata' >".$row_q['ProductTypeName']."</td>

							  <td class='indexmaindata' >".$row['OrderDate']."</td>

							  <td class='indexmaindata' >".$row['ExpiryDate']."</td>

							  <td class='indexmaindata' >".$reorder."</td>

							  </tr>";

				

				 }

			}

			else

			{

				 $pid     = $row_pdetail['ProductID']; 

				 

				 $sql_pro = "select * from tblproducts  where ProductID = '".$pid."' ";

				 $rs_pro  = mysql_query($sql_pro);

				 $row_pro = mysql_fetch_array($rs_pro);

				 

				  $query = "select * from tblproducttypes  where ProductTypeID  = '".$row_pdetail['PTypeID']."'";

				  $rs_q  = mysql_query($query);

				  $row_q = mysql_fetch_array($rs_q);

				  //$basepath."reorder.htm"

				  $reorder="<a href='".$basepath."reorder.htm'>Re-Order</a>";

				  

			$showTR .="<tr>

						  <td height='25'  class='indexmaindata'>".$row_pro['ProductCode']."</td>

						  <td class='indexmaindata' >".$row_q['ProductTypeName']."</td>

						  <td class='indexmaindata' >".$row['OrderDate']."</td>

						  <td class='indexmaindata' >".$row['ExpiryDate']."</td>

						  <td class='indexmaindata' >".$reorder."</td>

						  </tr>"; 

			}

		

		}

		else

		{

			$showTR .=" <tr>

						  <td height='25' colspan='5'  class='indexmaindata'>No Expired Products</td>

						  </tr>"; 

		}

		

	}

	

	return $showTR;

	}



	function getVendorsIDID($venName)

	{

	 $sql			=	"select * from tbl_vendors where ven_status='1' and ven_id='".$venName."'";

	  $rs			=	 mysql_query($sql);

	  $row			= 	 mysql_fetch_array($rs);

	  return $row;

	}

//.......................................................get certifications...............................

	function getTrack($vid)

	{

		 $strSql 	=	"SELECT  * from tbl_cert where ven_id='".$vid."' and cert_status='1'";

		 $result	=	mysql_query($strSql);

	  	 return $result;

	}

/////..................................................Show all certifications...........................

	function showTrack($result,$vendorName,$basepath)

	{

			$showTR	=	'';

			$count	=	'1';

			$b		=	'1';

			$count1	=	'1';

			$color='';

						

                                  

			

			

					 while($rows	=	mysql_fetch_array($result))

					 {

					 

					$Track	= $rows['cert_name'];

					$track_name	=	$rows['cert_name'];

					$Track2	="<b>" .$vendorName."&nbsp;".$rows['cert_name']."</b>";

					$tnic  =  str_replace('+', '-plus',strtolower($rows['cert_name']));

					$tnic  =  str_replace('.', '',strtolower($tnic));

					

					$page	= strtolower($rows['cert_name']); 

					$farr	=	array(" ","",":");

					$aarr	=	array("-","","");

					$url	= $basepath."".$rows['cert_url']."-cert.htm";

					$Title	=  $vendorName.' - '.$rows['cert_name'];

					

			if($count1%2=='0'){

				$border	=	'border-left:dashed 1px #E4E4E4;padding-left:10px;';

			}else{

				$border	=	'margin-right:15px;';

				

				}

		

$showTR	.=	"<li>

                        <ul class='list-box list-elements'>

                            <li>

                                <a title='".$Title."' href='".strtolower($url)."'>$Track</a>

                                <p>$Track</p>

                            </li>

                            <li>

							<form action='".strtolower($url)."'>

							<button type='submit' class='black-button'>View Details</button>

							</form>

							</li>

                            <li><b></b></li>

                        </ul>

                    </li>";

  

$count++;

$count1++;



}

  			



return $showTR;



	}

	

//...................Get sale point....................................................................................

	function getTrackSale()

	{

		 $strSql 	=	"SELECT  * from website where salesoption='Only Vendors' or salesoption='Vendors and Certificates' or salesoption='Vendors and Exams' or salesoption='Vendors, Certificates and Exams'";

		 $result	=	mysql_query($strSql);

  	     return $result;

	}

//...................Get sale point certify....................................................................................

	function getCertifySale()

	{

		 $strSql 	=	"SELECT  * from website where salesoption='Only Certifications' or salesoption='Vendors and Certificates' or salesoption='Certificates and Exams' or salesoption='Vendors, Certificates and Exams'";

		 $result	=	mysql_query($strSql);

  	     return $result;

	}

//...............................show sale vendor..................................................................

	function showTrackBundels($result,$vendorName,$basepath,$ven)

			{

			

			$showTR	=	'';

			$count	=	'1';

			$color='';

			$showTR	.="<table width='100%' cellspacing='5' ><tr>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;'><strong></strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;'><strong>Exams</strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;'><strong>3 Month Price</strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;' nowrap='nowrap'><strong>6 Month Price</strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;'><strong>1 Year Price</strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:##333333;'><strong>&nbsp;</strong></td>

			</tr>";

			$rows	=	mysql_fetch_array($result);

												

			

			$iubque	=	"SELECT * from tbl_exam where ven_id ='".$ven['ven_id']."' and exam_status='1'";

			$getitempro		=	mysql_query($iubque);

			$totalitemse	=	mysql_num_rows($getitempro);

					

					

							

$showTR	.="<tr><td width='172' align='left' style='padding-left:5px;border-bottom:dashed 1px #333333;'><strong>".$ven['ven_name']." Certification Exams</strong></td>

<td width='55' align='left' style='padding-left:5px;border-bottom:dashed 1px #333333;'>".$totalitemse." Exams </td>

<td width='73' align='left'  style='padding-left:15px;border-bottom:dashed 1px #333333;' valign='middle'>";



if($ven['ven_pri3']!=''){

$showTR	.="<input name='sellcombi' type='radio'  id='sellcombi' checked='checked' value='".$ven['ven_pri3']."' /> $".$ven['ven_pri3']."";

}

$showTR	.="</td><td width='61' align='left' style='padding-left:15px;border-bottom:dashed 1px #333333;'>";

if($ven['ven_pri6']!=''){

$showTR	.="<input name='sellcombi' type='radio'  id='sellcombi2' value='".$ven['ven_pri6']."' /> $".$ven['ven_pri6']." ";

}

$showTR	.="</td><td width='63' align='left' style='padding-left:15px;border-bottom:dashed 1px #333333;'>";

if($ven['ven_pri12']!=''){

$showTR	.="<input name='sellcombi' type='radio'  id='sellcombi3' value='".$ven['ven_pri12']."' /> $".$ven['ven_pri12']." ";

}

$showTR	.="</td><td width='140' align='center' style='padding-left:5px;border-bottom:dashed 1px #333333;'><a href='javascript:void(0);' onclick='return submitPro(".$ven['ven_id'].")' ><img src='images/addtocart.gif' alt='Order Now' border='0'></a><br/><div id='carthidshowss".$ven['ven_id']."'></div></td></tr>";

	

$showTR	.="</table>";

  			



return $showTR ;

	}

//...............................show sale vendor..................................................................

	function showCertBundels($result,$vendorName,$basepath,$ven)

			{

			

			$showTR	=	'';

			$count	=	'1';

			$color='';

			$showTR	.="<table width='100%' cellspacing='5' ><tr>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;'><strong></strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;'><strong>Exams</strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;'><strong>3 Month Price</strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;' nowrap='nowrap'><strong>6 Month Price</strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:#333333;'><strong>1 Year Price</strong></td>

			<td align='left' style='border-bottom:dashed 1px #333333;color:##333333;'><strong>&nbsp;</strong></td>

			</tr>";

			$rows	=	mysql_fetch_array($result);

												

			

			$iubque	=	"SELECT * from tbl_exam where cert_id ='".$ven['cert_id']."' and exam_status='1'";

			$getitempro		=	mysql_query($iubque);

			$totalitemse	=	mysql_num_rows($getitempro);

					

					

							

$showTR	.="<tr><td width='172' align='left' style='padding-left:5px;border-bottom:dashed 1px #333333;'><strong>".$ven['cert_name']." Certification</strong></td>

<td width='55' align='left' style='padding-left:5px;border-bottom:dashed 1px #333333;'>".$totalitemse." Exams </td>

<td width='73' align='left'  style='padding-left:15px;border-bottom:dashed 1px #333333;' valign='middle'>";



if($ven['cert_pri3']!=''){

$showTR	.="<input name='sellcombi' type='radio'  id='sellcombi' checked='checked' value='".$ven['cert_pri3']."' /> $".$ven['cert_pri3']."";

}

$showTR	.="</td><td width='61' align='left' style='padding-left:15px;border-bottom:dashed 1px #333333;'>";

if($ven['cert_pri6']!=''){

$showTR	.="<input name='sellcombi' type='radio'  id='sellcombi2' value='".$ven['cert_pri6']."' /> $".$ven['cert_pri6']." ";

}

$showTR	.="</td><td width='63' align='left' style='padding-left:15px;border-bottom:dashed 1px #333333;'>";

if($ven['cert_pri12']!=''){

$showTR	.="<input name='sellcombi' type='radio'  id='sellcombi3' value='".$ven['cert_pri12']."' /> $".$ven['cert_pri12']." ";

}

$showTR	.="</td><td width='140' align='center' style='padding-left:5px;border-bottom:dashed 1px #333333;'><a href='javascript:void(0);' onclick='return submitProCer(".$ven['cert_id'].")' ><img src='images/addtocart.gif' alt='Order Now' border='0'></a><br/><div id='carthidshowss".$ven['cert_id']."'></div></td></tr>";

	

$showTR	.="</table>";

  			



return $showTR ;

	}



//.........................................get all exams........................................................

function getAllItems($proid)

 {

 	$qqq	=	"SELECT exam_id	FROM  tbl_exam WHERE  exam_status='1' and ven_id='".$proid."'";

	$getnewQuery	=	mysql_query($qqq);

  	$i='1';

	while($rowitem	=	mysql_fetch_array($getnewQuery)){

	$proname	.=	$rowitem['exam_id'];

	if($i!=mysql_num_rows($getnewQuery)){

	$proname	.=	',';

	}

	$i++;

	}

	return $proname;

 }

 

 function getAllItemsCerts($proid)

 {

 	$qqq	=	"SELECT exam_id	FROM  tbl_exam WHERE  exam_status='1' and cert_id='".$proid."'";

	$getnewQuery	=	mysql_query($qqq);

  	$i='1';

	while($rowitem	=	mysql_fetch_array($getnewQuery)){

	$proname	.=	$rowitem['exam_id'];

	if($i!=mysql_num_rows($getnewQuery)){

	$proname	.=	',';

	}

	$i++;

	}

	return $proname;

 }

 

 ////////////////////////////////////////////get product/////////////////////////////////////////////////////////

 function getProduct($TID)

	{

      $strstr	=	"SELECT * FROM tbl_exam WHERE cert_id='$TID'";

	 $result	=	mysql_query( $strstr);

	 return $result;

	}

/////////////////////////////////////////////Show al exams//////////////////////////////////////////////////////

//-------------------------------------------------------------------------------//

function showAllexams($spam,$trNIck,$base_path)

	{

			$showTR		=	'';

			$count		=	0;

			$c  		=	1;

			$rowNum = mysql_num_rows($spam[1]);

			while( $row	=	mysql_fetch_array($spam[1]))

			{	

				//$id		= 	$row['ID'];

				$typeName = "";

			

	//		$showTR	.='<table width="100%" border="0" cellspacing="0" cellpadding="0">';

			

			 $var		=	$row['exam_name'];

			 $title1	=	$trNIck." "."Exam";

			 $tnic 		=  	$row['exam_name'];

			 $title		=	$spam[3]." "."Exam";

			 $title2	=	$row['exam_name']."-";	

		

			$val = $rowNum;

			$bar	='';	

			if($rowNum>1)

			{

				$bar = "|";

			}	else

			{

			$bar =	'';

			}		

	 $tnic 		=  	$row['exam_name'];

     

	$typeName .= "";

		$val--;

			 

			 $url		=	$base_path.$row['exam_url'].".htm";

			

			$original_date	=	$row['exam_date'];

			$pieces   = explode("-",$original_date);

			if($original_date=='0000-00-00'){

			$new_date	=	'-';

			}else{

			$new_date = date("M j, Y",strtotime($original_date));

			}

			if(isset($row["QA"]) and $row["QA"] != "0")

			{

				$buynow_img = '<img src="images/buynow.png" alt=" " width="75" height="34" border="0" />';

			}

			else{

				$buynow_img = '';

			}

			$showTR	   .=  '

			

			<li>

                        <ul class="list-box list-elements">

                            <li>

                                <a href="'.$url.'">'.$row['exam_name'].'</a>

                                <p>'.$row['exam_fullname'].'</p>

                            </li>

                            <li>

							<input name="sellcombi" type="radio"  id="sellcombi0" checked="checked" style="display:none;" value="'.$row['exam_pri0'].'" />

							<form action="'.$url.'" method="post"><button class="black-button">Buy Now</button></form></li>

                            

                        </ul>

                    </li>';

				 $count ++;	

		

	//	 $showTR	   .=  "</table>";

		 }

		 return 	 $showTR;	

	}

	

	function getExamVendor($TID){

		$newq = "select * from tbl_exam where exam_url='".$TID."'";

		$newquery = mysql_query($newq);



		$exam_result = mysql_fetch_array($newquery);

		return $exam_result;

	}

	

	function getVendorname($TID){

		$newq = "select * from tbl_exam where exam_name='".$TID."'";

		$newquery = mysql_query($newq);



		$exam_result = mysql_fetch_array($newquery);

		return $exam_result;

	}

	

	function getProductId($pCode)

	{

	$strSql 	=	"SELECT * FROM tbl_exam WHERE exam_url='".$pCode."'";

    $result		=	mysql_query($strSql);

	$re 		=	mysql_fetch_array($result);

	return $re;

	}

function get_vendorName($vendor_id){

	$strSql 	=	"SELECT * FROM tbl_vendors WHERE ven_id='".$vendor_id."'";

    $result		=	mysql_query($strSql);

	$vendor_result 		=	mysql_fetch_array($result);

	return $vendor_result;

	}

	function get_certName($cert_id){

	

	

	 $cert_idd=explode(',',$cert_id);

	//print_r($cert_ids);



$cert_ids=array_filter($cert_idd);	

$jj=0;

 foreach($cert_ids as $k=>$v)

 	{ 

      $strSql 	=	"SELECT cert_name FROM tbl_cert WHERE cert_id='$v'";

	  $result		=	mysql_query($strSql);



	/*while($cert_result 	=	mysql_fetch_assoc($result))

	{

	  echo $cert_result["cert_name"];

	}*/

	

	$row=mysql_fetch_object($result);

	$cert_result[$jj] 	=	$row->cert_name;

		//$cert_result[$jj] 	=	mysql_fetch_array($result);

		

		$jj++;

//	$cert_result 	=	mysql_fetch_assoc($result);

	

	

//	print_r($cert_result);

//	return $cert_result;

	

	}

	

	//print_r($cert_result);

	return $cert_result;

	

	}//end of foreach loop



/////////////////















//////////////////////////////////////////////////////////////////////////////////////////////

}

?>