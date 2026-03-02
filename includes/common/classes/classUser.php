<?PHP

	//--------------------------------------------------------------------------------



	//					<<<<Creation of the Class User class>>>>	



	error_reporting(0);

	//--------------------------------------------------------------------------------



	//					<<<<Headers including area>>>>>



	//===================================================================================

	class classUser



	{



		//--------------------------------------------------------------------------------



								// Class Properties (Class Level Variables)



		//--------------------------------------------------------------------------------



			var $objDbConn		;

			var	$rowsPerPage 	;

			var $linkPerPage	;

			var	$PageNo			; 	# Shows # of Page			

			var $PageIndex	 	;	# Shows # of Page Index				



		//..........................................................................................................//



		/*Constructor initialize all the variables*/



//-------------------------------------------------------------------------------------------//



		function classUser($objDBcon)

		{

			$this->objDbConn	= 	$objDBcon; # Populate the Connection property with the Connection Class Object.

		}

//-------------------------------------------------------------------------------------------//

		function addUser($spram)

		{

		if($spram[10]!=''){

		 $add_que = "INSERT INTO tbl_user(user_fname,user_lname,user_phone,user_email,user_password,user_status,creatDate)VALUES('".$spram[1]."','".$spram[2]."','".$spram[13]."','".$spram[5]."','".$spram[9]."','".$spram[10]."','".$spram[6]."')";

		 }else{

		 $add_que = "INSERT INTO tbl_user(user_fname,user_lname,user_phone,user_email,user_password,user_status,creatDate)VALUES('".$spram[1]."','".$spram[2]."','".$spram[13]."','".$spram[5]."','".$spram[9]."','Active','".$spram[6]."')";

		 }

		 $qryexe = mysql_query($add_que) or die(mysql_error()); 

		return mysql_insert_id();

		}

		

		function getUserIDsignup($spram)

			{

			$sql	= "select * from tbl_user where user_id='".$spram."'";

			$rs		= mysql_query($sql);

			$row	= mysql_fetch_array($rs);

			return	$row;

			}

		

		function getUserID($spram)

		{

		$sql	= "select * from tbl_user where user_email='".$spram[1]."'";

		$rs		= mysql_query($sql);

		$row	= mysql_fetch_array($rs);

		return	$row;

		}



		//--------------------------------------------------------------------------------------------

   			function getAllRealOrder($limits)

				{

				 $qry = "select * from order_master order by OrderDate DESC".$limits;

				 $rs  =	mysql_query($qry);

				 return $rs;

				}

		

//------------------------------------------------------------------------------------

      function showAllRealOrder($result)

							{

					

					$counter = 1;

					$amount	=0.00;

				

					while($rows=mysql_fetch_array($result))

						{	

					$login	=	'';

				    

					$amount	= $amount+$rows['Net_Amount'];

								

										if (($counter % 2)!=0 )

											$intColor = "#FFFFFF";

										else

											$intColor = "#f3eeee";

					    						 

						//$sql_chk = "select * from order_detail where UserID ='".$rows['Cust_ID']."'";

						$utype	= "select * from order_master where Cust_ID='".$rows['Cust_ID']."'";

						$rs_ut	= mysql_query($utype);

						$num	= mysql_num_rows($rs_ut);

						

						$ocnt   = 1;

															

						$query	="select * from tbl_user where user_id='".$rows['Cust_ID']."'";

						$rs		=mysql_query($query);

						$row	=mysql_fetch_array($rs);

						$date	=date('Y-m-d');

						

							if($rows['Status']==1){	

							$chek='Disable this';

							$colstatus	=	'style="color:#FF0000"';

							}else{

							$chek='Enable this';

							$colstatus	=	'style="color:#00FF00"';

							}

							$tableName  ="order_master";

							$tablefield ="ID";

							$tablestatus ="Status";

							

										

							$statusOrder= "<div id='txtHint".$rows['ID']."'>

	<a href='javascript:showHint(".$rows['ID'].", \"$tableName\",\"$tablefield\",\"$tablestatus\")' ".$colstatus.">".$chek." </a></div>";

	 

	 $login       = '<a href="../adminlogin.php?uid='.$rows['Cust_ID'].'&sid=8" target="_blank">Login</a> |';

//---------------------------------------------------------------------------------------

				

//----------------------------------------------------------------------------------------

			$row_c=array();

			$row_c['computed_value']=0;

			$row_c['coupon_code']="N/A";

			$query_c	="select coupon_code, computed_value from coupon_order where order_id='".$rows['ID']."' and order_type='1'";

			$rs_c		=mysql_query($query_c);

			if(mysql_num_rows($rs_c)>0)

			{

				$row_c	=mysql_fetch_array($rs_c);

			}

			

	$showTr		.='	<tr bgcolor='.$intColor.'>

	

	<td class=item style=padding-left: 0px;>'.$row['user_email'].'</td>

	<td class=item align="left" valign="middle">'.$row['user_password'].'</td>

	<td width=5 class=item style=padding-right: 0px;>'.$rows['Order_Id'].'</td>

    <td class=item style=padding-left: 0px;><strong>'.$typepro.'</strong><i>'.$rows['OrderDesc'].'</i></td>

    <td class=item style=padding-left: 0px;>'.$rows['OrderDate'].'</td>

    <td class=item style=padding-left: 0px;>$'.$rows['Net_Amount'].'</td>

	<td class=item style=padding-left: 0px;>'.@$row_c['coupon_code'].'</td>

	<td class=item style=padding-left: 0px;>$'.@$row_c['computed_value'].'</td>

	<td class=item style=padding-left: 0px;>'.$statusOrder.'</td>

    <td class=item nowrap=nowrap><a href="orderdetail.php?ord='.$rows['ID'].'">Details</a> | <a href="masterorder.php?action=up&uid='.$rows['Cust_ID'].'&cart='.$rows['Cart_ID'].'&sid='.$rows['SiteID'].'&nid='.$rows['ID'].'" onclick="return confirm(&quot;Do you want to delete ?&quot;);">Delete</a></td>

	

  </tr>';	

 	

								

								$counter++;	

								}

				$showTr		.='<tr bgcolor="#D8ECF1" height="3">

				<td  colspan=7 align=right><b>Total:</b></td>

    			<td  colspan=3  style=padding-left: 2px; class=total> $ '.$amount.'</b></td>

				</tr>';		

				

				 		

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

					

				}#End of showOrder Function	

//------------------------------------------------------------------------------------

			function deleteMasterOrder($uid,$cart)

			{

				$q="delete from order_master where Cust_ID='".$uid."'and Cart_ID='".$cart."'";

					mysql_query($q);

				$qq	="delete from  order_detail where UserID='".$uid."'and Cart_ID='".$cart."'";

				mysql_query($qq);

			}	



		function ValidUserLogin($spram)

			{

			$sql	= "select user_email,user_password from tbl_user where (user_email='".$spram[1]."' and user_password='".$spram[2]."') and user_status='Active' ";

			$rs		= mysql_query($sql);

			$num	= mysql_num_rows($rs);

			if($num ==1)

			return true;

			else

			return false;

			}

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		function addguest($spram)

		{

		

		$add_que = "UPDATE tbl_tmporder set bill_fname='".$spram[1]."',bill_lname='".$spram[2]."',bill_address='".$spram[3]."',bill_postcode='".$spram[4]."',bill_email='".$spram[5]."',bill_phone='".$spram[6]."',bill_country='".$spram[11]."',bill_city='".$spram[12]."',bill_state='".$spram[13]."' where tmp_sess='".session_id()."'";

		 $qryexe = mysql_query($add_que);



		}

		function checkUserDuplicationemail($email)



			{					

				$qry = "select  * from tbl_user where user_email = '".$email."'"; 

				$qryexe =  mysql_query($qry); 

				if(mysql_num_rows($qryexe)>0)	

				return true;

				else

				return false;



			}



		

		function addguest_ship($spram)

		{

		

		$add_que = "UPDATE tbl_tmporder set bill_fname='".$spram[1]."',bill_lname='".$spram[2]."',bill_address='".$spram[3]."',bill_postcode='".$spram[4]."',bill_email='".$spram[5]."',bill_phone='".$spram[6]."',bill_country='".$spram[11]."',bill_city='".$spram[12]."',bill_state='".$spram[13]."',ship_name='".$spram[1]."',ship_address='".$spram[3]."',ship_zipcode='".$spram[4]."',ship_country='".$spram[11]."',ship_city='".$spram[12]."',ship_state='".$spram[13]."' where tmp_sess='".session_id()."'";

		 $qryexe = mysql_query($add_que);



		}

		function addguest_ship_onluship($spram)

		{

		

		$add_que = "UPDATE tbl_tmporder set ship_name='".$spram[1]."',ship_address='".$spram[3]."',ship_zipcode='".$spram[4]."',ship_country='".$spram[11]."',ship_city='".$spram[12]."',ship_state='".$spram[13]."' where tmp_sess='".session_id()."'";

		 $qryexe = mysql_query($add_que);



		}

//-------------------------------------------------------------------------------------------//



		function addAdminPermission($adminId)



		{

			$qry = "select * from  tblmodules";

	

			$res = mysql_query($qry);

			//echo mysql_num_rows($res);

			$i=0;

			while($rows	=	mysql_fetch_array($res))

			{ 

				

			  $add_que = "INSERT INTO `tblpermissions` (`AdminID`,`ModuleID`,`Read`,`Write`,`Edit`,`Delete`) VALUES (".$adminId.",".$rows['ModuleID'].",1,1,1,1)"; 

			mysql_query($add_que);

			}

		}

  function getAllTepmorder($limits)

				{

			   $qry = " select T.* from temp_master T order by T.ID DESC".$limits;

			   $rs  =	mysql_query($qry);

			   return $rs;

				

				}

	function getAllTepmorderreorder($limits)

				{

			   $qry = "select  * from  tbl_reorder order by id_re DESC ".$limits;

			   $rs  =	mysql_query($qry);

			   return $rs;

				

				}

	

	//------------------------------------------------------------------------------------

      function showTempOrder($result)

							{

					 $counter = 1;

					while($rows=mysql_fetch_array($result))

									{	

								

										if (($counter % 2)!=0 )

											$intColor = "";

												

										else

											$intColor = "bgcolor='#f3eeee'";

				

				$qury	="select * from  tbl_user where user_id='".$rows['Cust_ID']."'";	

				$rs_qry	=mysql_query($qury);

				$rs_row	=mysql_fetch_array($rs_qry);

				

				$qry_master	="select * from order_master where Cust_ID='".$rs_row['UserID']."' and Cart_ID='".$rows['Cart_ID']."'";	

				$rs_master	=mysql_query($qry_master);

				$rs_num		=mysql_num_rows($rs_master);

			

			$row_c=array();

			$row_c['computed_value']=0;

			$row_c['coupon_code']="N/A";

			$query_c	="select coupon_code, computed_value from coupon_order where order_id='".$rows['ID']."' and order_type='0'";

			$rs_c		=mysql_query($query_c);

			if(mysql_num_rows($rs_c)>0)

			{

				$row_c	=mysql_fetch_array($rs_c);

			}

				$Save	=	"<a href='javascript:showHintnamesave(".$rows['ID'].");' > Process </a>";				

									$showTr		.='

									<tr class=background2>

									<td class=item align="left" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['ID'].'" /></td>

    <td width=5 class=item style=padding-right: 0px;>'.$rs_row['user_email'].'</td>

	<td width=5 class=item style=padding-right: 0px;>'.$rs_row['user_password'].'</td>

    <td class=item style=padding-left: 0px;>'.$rows['OrderDesc'].'</td>

	<td width=5 class=item style=padding-right: 0px;>'.$rows['OrderDate'].'</td>

	<td class=item style=padding-left: 0px;>$'.$rows['Net_Amount'].'</td>

	<td class=item style=padding-left: 0px;>'.@$row_c['coupon_code'].'</td>

	<td class=item style=padding-left: 0px;>$'.@$row_c['computed_value'].'</td>

	<td class=item style=padding-left: 0px;><input name="tid'.$rows['ID'].'" type="text" id="tid'.$rows['ID'].'" value=""/></td>

    

    

    

    <td class=item nowrap=nowrap>'.$Save.' | <a href="odrproces.php?action=up&pid='.$rows['ID'].'" onclick="return confirm(&quot;Do you want to delete ?&quot;);">Delete</a></td>

	

  </tr>';	

								

								$counter++;	

				//}

								}

						 		

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

					

				}#End of showOrder Function	

	//------------------------------------------------------------------------------------

      function showTempOrder_reorder($result)

							{

					 $counter = 1;

					while($rows=mysql_fetch_array($result))

									{	

								

										if (($counter % 2)!=0 )

											$intColor = "";

												

										else

											$intColor = "bgcolor='#f3eeee'";

				

				$qury	="select * from  order_detail where ID='".$rows['id_detailorder']."'";	

				$rs_qry	=mysql_query($qury);

				$roword	=mysql_fetch_array($rs_qry);

				

	if($roword['VendorID']!=''){

	$getreitems	=	mysql_fetch_array(mysql_query("SELECT * from tbl_vendors where ven_id='".$roword['VendorID']."'"));

	$exmname	=	$getreitems['ven_name'];

	$numberidid	=	$getreitems['ven_id'];

		if($roword['submonth']=='3'){ $priceprice	=	$getreitems['ven_pri3']; }

		if($roword['submonth']=='6'){ $priceprice	=	$getreitems['ven_pri6']; }

		if($roword['submonth']=='12'){ $priceprice	=	$getreitems['ven_pri12']; }

	}

	if($roword['TrackID']!=''){

	$getreitems	=	mysql_fetch_array(mysql_query("SELECT * from tbl_cert where cert_id='".$roword['TrackID']."'"));

	$exmname	=	$getreitems['cert_name'];

	$numberidid	=	$getreitems['cert_id'];

		if($roword['submonth']=='3'){ $priceprice	=	$getreitems['cert_pri3']; }

		if($roword['submonth']=='6'){ $priceprice	=	$getreitems['cert_pri6']; }

		if($roword['submonth']=='12'){ $priceprice	=	$getreitems['cert_pri12']; }

	}

	if($roword['ProductID']!='' && $roword['ProductID']!='0'){

	$getreitems	=	mysql_fetch_array(mysql_query("SELECT * from tbl_exam where exam_id='".$roword['ProductID']."'"));

	$exmname	=	$getreitems['exam_name'];

	$numberidid	=	$getreitems['exam_id'];

		if($roword['submonth']=='3'){ $priceprice	=	$getreitems['exam_pri3']; }

		if($roword['submonth']=='6'){ $priceprice	=	$getreitems['exam_pri6']; }

		if($roword['submonth']=='12'){ $priceprice	=	$getreitems['exam_pri12']; }

	}

	$userget	=	mysql_fetch_array(mysql_query("SELECT * from tbl_user where user_id='".$roword['UserID']."'"));

	

				$Save	=	"<a href='javascript:showHintnamesave(".$rows['id_re'].");' > Process </a>";				

				$showTr		.='	<tr class=background2>

									<td class=item align="left" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['id_re'].'" /></td>

    <td width=5 class=item style=padding-right: 0px;>'.$userget['user_email'].'</td>

	<td width=5 class=item style=padding-right: 0px;>'.$userget['user_password'].'</td>

	<td width=5 class=item style=padding-right: 0px;>'.$exmname.'</td>

   <td width=5 class=item style=padding-right: 0px;>'.$rows['startdate'].'</td>

	<td width=5 class=item style=padding-right: 0px;>'.$rows['enddate'].'</td>

	<td class=item style=padding-left: 0px;>$'.$rows['price'].'</td><td class=item style=padding-left: 0px;>';

	

	if($rows['order_id']==''){

	$showTr		.='<input name="tid'.$rows['id_re'].'" type="text" id="tid'.$rows['id_re'].'" value=""/>';

	$pro	=	$Save.' | ';

	}else{

	$showTr		.= $rows['order_id'];

	$pro	=	'';

	}

	$showTr		.='</td>  

    

    <td class=item nowrap=nowrap>'.$pro.'<a href="reodrproces.php?action=up&pid='.$rows['id_re'].'" onclick="return confirm(&quot;Do you want to delete ?&quot;);">Delete</a></td>

	

  </tr>';	

								

								$counter++;	

				}

						 		

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

					

				}#End of showOrder Function	

//------------------------------------------------------------------------------------

		function deleteTemorder($pid)

			{

				$q="delete from temp_master where ID='".$pid."'";

				mysql_query($q);

				

				

			}

			

			function deleteTemordermaster($pid,$cart)

			{

				$q="delete from order_master where ID='".$pid."'";

				mysql_query($q);

				

				$q2="delete from order_detail where Cart_ID='".$pid."'";

				mysql_query($q2);

				

				

			}

		function deleteTemorderReorder($pid)

			{

				$q="delete from temp_master where ID='".$pid."'";

				mysql_query($q);

			}

//-------------------------------------------------------------------------------------------//



function deleteUserdemo($spram)

   				 {

				$strSql='DELETE FROM tbl_demo_download WHERE dnID="'.$spram.'"';

				mysql_query($strSql);

				}	

function deleteUserdemofull($spram)

   				 {

				$strSql='DELETE FROM tbl_full_download WHERE dnID="'.$spram.'"';

				mysql_query($strSql);

				}	  

function deleteExamRequestcode($spram)

   				 {

				$strSql='DELETE FROM exam_request WHERE examr_id="'.$spram.'"';

				mysql_query($strSql);

				}	  

function deleteDemoDownload($spram)

   				 {

				$strSql='DELETE FROM  tbl_demo_download_email WHERE tbl_demo_id="'.$spram.'"';

				mysql_query($strSql);

				}				

function deleteUser($spram)

   				 {

				

				//...................................................................

				//$q1		=	"select * FROM tbl_user WHERE user_id ='".$spram."'";

				//$dw3	=	mysql_query($q1) or die(mysql_error());	

				//$row	=	mysql_fetch_array($dw3);

				//if($row['user_profile_photo'] != ''){

			   // unlink("../upload/".$row['user_profile_photo']);

				//unlink("../upload/thumbs/".$row['user_profile_photo']);

				//}

				//...................................................................

				$strSql='DELETE FROM tbl_user WHERE user_id="'.$spram.'"';

				mysql_query($strSql);

				}	  

//-------------------------------------------------------------------------------------------//



function getuserName($spram)



			{					



				$qry = "select * from tbl_user where user_id = '".$spram."'"; 



				$qryexe =  mysql_query($qry); 



				return $qryexe;



			}

//-------------------------------------------------------------------------------------------//



function getAdminEdit($spram)



			{					



				$qry = "select * from tbl_admin where adm_id = '".$spram[0]."'"; 



				$qryexe =  mysql_query($qry); 



				return $qryexe;



			}

function getUserhtml($spram, $stats='')

				{

					$showTr 	=	"";

					$counter	=	"";

					$comments	=	"";

					$edit	=	"";

					$del	=	"";

					$counter	=	'1';

					while($rows=mysql_fetch_array($spram))

					{	

										if (($counter % 2)!=0 )

											$intColor = "";

										else

											$intColor = "bgcolor='#f3eeee'";											

							$edit	=	'<a href="edituser.php?nid='.$rows['user_id'].'">Edit </a>';

							$sendemail	=	'| <a href="manageuser.php?mainsend=send&user='.$rows['user_id'].'">Send Email </a>';

							$del	=	'| <a href="manageuser.php?action=up&nid='.$rows['user_id'].'" onclick= "return delete_pro();">Delete</a>';

							

							$showTr		.='<tr '.$intColor.'>';

							if($stats=='')

							{

								$showTr		.=		'<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['user_id'].'" /></td>';

							}

	

	$showTr		.='<td width=5 align="center" class=item style=padding-center: 0px;>'.$rows['user_fname'].'&nbsp;'.$rows['user_lname'].'</td>

    <td class=item align="center" style=padding-center: 0px;>'.$rows['user_email'].'</td>

    <td class=item align="center" style=padding-center: 0px;>'.$rows['user_password'].'</td>

	 <td align="center" class=item>'.$rows['user_status'].'</td>';

	 						if($stats=='')

							{

								$showTr		.=		'<td align="center" class=item nowrap=nowrap>'.$edit.''.$sendemail.''.$del.' </td>';

							}

$showTr		.=		'  </tr>';	

				$counter++;

								}

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

				}

				

				function getUserhtmldemo($spram)

				{

					$showTr 	=	"";

					$counter	=	"";

					$comments	=	"";

					$edit	=	"";

					$del	=	"";

					$counter	=	'1';

					while($rows=mysql_fetch_array($spram))

					{	

										if (($counter % 2)!=0 )

											$intColor = "";

										else

											$intColor = "bgcolor='#f3eeee'";

					$del	=	'<a href="managedemo.php?action=up&nid='.$rows['dnID'].'" onclick= "return delete_pro();">Delete</a>';

					$useremail	=	mysql_fetch_array(mysql_query("SELECT * from tbl_user where user_id='".$rows['dnUID']."'"));

					$userexam	=	mysql_fetch_array(mysql_query("SELECT * from tbl_exam where exam_id='".$rows['itemid']."'"));

					$showTr		.='<tr '.$intColor.'>

									<td class=item align="center" valign="middle">

	<input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['dnID'].'" /></td>

    <td width=5 align="center" class=item style=padding-center: 0px;>'.$useremail['user_fname'].'&nbsp;'.$useremail['user_lname'].'</td>

    <td class=item align="center" style=padding-center: 0px;>'.$useremail['user_email'].'</td>

	 <td align="center" class=item>'.$userexam['exam_name'].'</td>

	 <td align="center" class=item nowrap=nowrap>'.$del.' </td>

  </tr>';	

				$counter++;

								}

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

				}

				

				function getUserhtmldemofull($spram)

				{

					$showTr 	=	"";

					$counter	=	"";

					$comments	=	"";

					$edit	=	"";

					$del	=	"";

					$counter	=	'1';

					while($rows=mysql_fetch_array($spram))

					{	

										if (($counter % 2)!=0 )

											$intColor = "";

										else

											$intColor = "bgcolor='#f3eeee'";

					$del	=	'<a href="managefull.php?action=up&nid='.$rows['dnID'].'" onclick= "return delete_pro();">Delete</a>';

		//			$useremail	=	mysql_fetch_array(mysql_query("SELECT * from tbl_user where user_id='".$rows['dnUID']."'"));

		//			$userexam	=	mysql_fetch_array(mysql_query("SELECT * from tbl_exam where exam_id='".$rows['itemid']."'"));

					$showTr		.='<tr '.$intColor.'>

									<td class=item align="center" valign="middle">

	<input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['dnID'].'" /></td>

    <td width=5 align="center" class=item style=padding-center: 0px;>'.$rows['user_fname'].'&nbsp;'.$rows['user_lname'].'</td>

    <td class=item align="center" style=padding-center: 0px;>'.$rows['user_email'].'</td>

	 <td align="center" class=item>'.$rows['exam_name'].'</td>

	 <td align="center" class=item nowrap=nowrap>'.$del.' </td>

  </tr>';	

				$counter++;

								}

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

				}

				

// --------------------------- Exam Request------------------------------

function getUserexamRequest($spram)

				{

					$showTr 	=	"";

					$counter	=	"";

					$comments	=	"";

					$edit	=	"";

					$del	=	"";

					$counter	=	'1';

					while($rows=mysql_fetch_array($spram))

					{	

										if (($counter % 2)!=0 )

											$intColor = "";

										else

											$intColor = "bgcolor='#f3eeee'";

					$del	=	'<a href="exam_request.php?action=up&nid='.$rows['examr_id'].'" onclick= "return delete_pro();">Delete</a>';

						$showTr		.='<tr '.$intColor.'>

									<td class=item align="center" valign="middle">

	<input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['examr_id'].'" /></td>

    <td class=item align="center" style=padding-center: 0px;>'.$rows['examr_email'].'</td>

	 <td align="center" class=item>'.$rows['examr_code'].'</td>

<td align="center" class=item>'.$rows['examr_date'].'</td>

	 <td align="center" class=item nowrap=nowrap>'.$del.' </td>

  </tr>';	

				$counter++;

								}

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

				}

// --------------------------- Demo Download------------------------------

function getdemoDownload($spram)

				{

					$showTr 	=	"";

					$counter	=	"";

					$comments	=	"";

					$edit	=	"";

					$del	=	"";

					$counter	=	'1';

					while($rows=mysql_fetch_array($spram))

					{	

										if (($counter % 2)!=0 )

											$intColor = "";

										else

											$intColor = "bgcolor='#f3eeee'";

					$del	=	'<a href="view_demoDownload.php?action=up&nid='.$rows['tbl_demo_id'].'" onclick= "return delete_pro();">Delete</a>';

						$showTr		.='<tr '.$intColor.'>

									<td class=item align="center" valign="middle">

	<input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['tbl_demo_id'].'" /></td>

    <td class=item align="center" style=padding-center: 0px;>'.$rows['demo_download_email'].'</td>

	 <td align="center" class=item>'.$rows['exam_code'].'</td>

	<td align="center" class=item>'.$rows['demo_download_date'].'</td>	

	 <td align="center" class=item nowrap=nowrap>'.$del.' </td>

  </tr>';	

				$counter++;

								}

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

				}



// --------------------------- Get Sales Info------------------------------

function getSaleinfo($spram)

				{

				

					$showTr 	=	"";

					$counter	=	"";

					$comments	=	"";

					$edit	=	"";

					$del	=	"";

					$counter	=	'1';

					while($rows=mysql_fetch_array($spram))

					{	

										if (($counter % 2)!=0 )

											$intColor = "";

										else

											$intColor = "bgcolor='#f3eeee'";

					

					$dd = mysql_query("select  user_email from tbl_user where user_id='".$rows['Cust_ID']."'");

					$fetch_email = mysql_fetch_object($dd);

					$email_user =  $fetch_email->user_email;

					

						$showTr		.='<tr '.$intColor.'>

    <td class=item align="center" style=padding-center: 0px;>'.$email_user.'</td>

	 <td align="center" class=item>'.$rows['OrderDesc'].'</td>

	 <td align="center" class=item>'.$rows['OrderDate'].'</td>

  </tr>';	

				$counter++;

								}

			return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';

				}



	

	

//-------------------------------------------------------------------------------------------//

function checkUserDuplication($email)

			{					

				 $qry = "select  * from tbl_user where user_email= '".$email."'"; 

				$qryexe =  mysql_query($qry); 

				if(mysql_num_rows($qryexe)>0)	

				return false;

				else

				return true;

			}

		

//-------------------------------------------------------------------------------------------//

 function UpdateUserAdmin($spram)

	  {

		$strQury="update tbl_user set 

													user_fname			 = '".$spram[1]."',

													user_lname			 = '".$spram[2]."',

													user_password		 = '".$spram[9]."',

													user_status			 = '".$spram[10]."'

													

													where  user_id	     = '".$spram[0]."'";

				

		mysql_query($strQury);

	  }	

	  ////////////////////////////////////////////////////////////////////////////////////////

	  function sendemailpassword($email){

	

		 $new_password= chr(rand(96,122)).rand(1,9).chr(rand(96,122)).rand(1,9).chr(rand(96,122)).rand(1,9).chr(rand(96,122)).rand(1,9);

		

		$strQury="update tbl_user set user_password = '".md5($new_password)."' where user_email = '".$email."'";

		$exequery	=	mysql_query($strQury);

		

		

		return $new_password;

		

		

	}





//////////////////////////////////Check User login////////////////////////////////////////////

function boolValidateuser1($strUser,$strPassword)

		{

		$rsname	=	"SELECT * from tbl_user where user_email='".$strUser."' and user_password='".md5($strPassword)."' and user_status='Active'";

		$exe		=	mysql_query($rsname);

		$rowsNum	=	mysql_num_rows($exe);

		return $rowsNum;

		}

function getId($strUsername)

		{

		$strSql="SELECT *  FROM tbl_user WHERE user_email='".$strUsername."'";

		

		$rs=mysql_query($strSql);	

		$row	=	mysql_fetch_array($rs);

		

		return $adm_id	=	$row['user_id'];

		

		}

  



	function getAllUsers($Condition)



		{

		

			 $query	=	"select *from tbl_user ".$Condition." ORDER BY user_id  DESC ";

			$qry = mysql_query($query);



			return $qry;



		}





		function getAllOrdersExport($order)



		{

			$totallength	=	$order;

			$values	=	substr($totallength,0,-1);

			

		//	$r	=	"select id as order_id,full_name as Name, order_type as Package, order_date,total_price,transaction_status as order_status,rafill from  tbl_order where id IN (".$values.") order by field(id,".$values.")";

		  	$r	=	"select a.id as order_id,full_name as Name, order_type as Package,order_date,total_price,transaction_status as order_status,rafill,fnameship as shipFirstName,lnameship as shipLastName, address as shipStreet, zip as shipZip,city as shipCity, state as shipState,country as shipCountry, telephone as shipTelephone ,user_address as address,user_email as Email,user_city,user_state,user_country,user_phone,user_post from  tbl_order a,  tbl_usershipping b ,tbl_user c where a.id = b.order_id and a.uid=c.user_id and  a.id IN (".$values.") ";

		

			 $exe	=	mysql_query($r);

			

			

			return $exe;



		}



//-------------------------------------------------------------------------------------------//

//////////////////////////////////Check User login////////////////////////////////////////////

function boolValidateuser($strUser,$strPassword)

		{

		$rsname	=	"SELECT * from tbl_user where user_email='".$strUser."' and user_password='".md5($strPassword)."' and user_status='Active'";

		$exe	=	mysql_query($rsname);

		$rowsNum	=	mysql_num_rows($exe);		

		return $rowsNum;

		}



////////////////////////////////////////////////////////////////////////////////////////////////////////

function addOrderpayment($fullname,$interval,$productPrice,$totalprice,$spramship,$rafill,$offers,$shippingprice)



	{

	

	$todayDate	=	date("Y-m-d");

	$time		=	date("H:i:s");

	$addquery	=	"INSERT INTO tbl_order



	                                        (



												uid,

												full_name,

												order_type,

												order_price,

												order_date,

												order_time,

												total_price,

												transaction_status,

												rafill,

												offers



												)



												VALUES



												(



												'".$_SESSION['userId']."',

												'".$fullname."',

												'".$interval."',

												'".$productPrice."',

												'".$todayDate."',

												'".$time."',

												'".$totalprice."',

												'Received',

												'".$rafill."',

												'".$offers."'



												)";



				mysql_query($addquery);

				$_SESSION['userOrderId'] = mysql_insert_id();

				$lastOrederId			 = mysql_insert_id();

				

				list($year, $month, $day) = explode('-', $todayDate);

				$next = date('Y-m-d', mktime(0, 0, 0, $month + $interval, $day, $year));

				

				$addnextship	=	"INSERT INTO next_shipping



	                                        (

												uid,

												orderId,

												order_type,

												order_start,

												order_end											

												)



												VALUES



												(



												'".$_SESSION['userId']."',

												'".$lastOrederId."',

												'".$interval."',

												'".$todayDate."',

												'".$next."'

												)";



				mysql_query($addnextship);

				

		$addshipping	=	"INSERT INTO tbl_usershipping (

															fnameship,

															lnameship,

															order_id,

															user_id,

															address,

															zip,

															telephone,

															country,

															state,

															city,

															date

															)VALUES(

															'".$spramship[7]."',

															'".$spramship[8]."',

															'".$lastOrederId."',

															'".$_SESSION['userId']."',

															'".$spramship[1]."',

															'".$spramship[6]."',

															'".$spramship[2]."',

															'".$spramship[3]."',

															'".$spramship[4]."',

															'".$spramship[5]."',

															'".$todayDate."'

															)";

		

		mysql_query($addshipping);

		return $lastOrederId;

				



	}

function selectCOuntry($val='')

{

	 $state_codes = "SELECT * from pro_country_spr";

	 $exe	=	mysql_query($state_codes);

	

				$txtCombo = "<select name=\"country\"  class=\"input3\"  id=\"country\"  >";

				/*$txtCombo .= "<option selected=\"selected\" value=\"United Kingdom\"  >United Kingdom</option>";*/

                  while($row	=	mysql_fetch_array($exe)){

				  if($val	==	$row['name'])

				  $txtCombo .= "<option selected=\"selected\" value=\"".$row['name']."\"  >".$row['name']."</option>";

                  else

				  $txtCombo .= "<option value=\"".$row['name']."\">".$row['name']."</option>";

                    }

					return $txtCombo."</select> " ;

           

}



function selectState($val='')

{

	 $state_codes = array(

        "Alabama              " => "AL",

        "Alaska               " => "AK",

        "Arizona              " => "AZ",

        "Arkansas             " => "AR",

        "California           " => "CA",

        "Colorado             " => "CO",

        "Connecticut          " => "CT",

        "Delaware             " => "DE",

        "District of Columbia " => "DC",

        "Florida              " => "FL",

        "Georgia              " => "GA",

        "Hawaii               " => "HI",

        "Idaho                " => "ID",

        "Illinois             " => "IL",

        "Indiana              " => "IN",

        "Iowa                 " => "IA",

        "Kansas               " => "KS",

        "Kentucky             " => "KY",

        "Louisiana            " => "LA",

        "Maine                " => "ME",

        "Maryland             " => "MD",

        "Massachusetts        " => "MA",

        "Michigan             " => "MI",

        "Minnesota            " => "MN",

        "Mississippi          " => "MS",

        "Missouri             " => "MO",

        "Montana              " => "MT",

        "Nebraska             " => "NE",

        "Nevada               " => "NV",

        "New Hampshire        " => "NH",

        "New Jersey           " => "NJ",

        "New Mexico           " => "NM",

        "New York             " => "NY",

        "North Carolina       " => "NC",

        "North Dakota         " => "ND",

        "Ohio                 " => "OH",

        "Oklahoma             " => "OK",

        "Oregon               " => "OR",

        "Pennsylvania         " => "PA",

        "Rhode Island         " => "RI",

        "South Carolina       " => "SC",

        "South Dakota         " => "SD",

        "Tennessee            " => "TN",

        "Texas                " => "TX",

        "Utah                 " => "UT",

        "Vermont              " => "VT",

        "Virginia             " => "VA",

        "Washington           " => "WA",

        "West Virginia        " => "WV",

        "Wisconsin            " => "WI",

        "Wyoming              " => "WY",

        "Alberta              " => "AB",

        "British Columbia     " => "BC",

        "Manitoba             " => "MB",

        "New Brunswick        " => "NB",

        "Newfoundland         " => "NF",

        "Northwest Territories" => "NT",

        "Nova Scotia          " => "NS",

        "Nunavut              " => "NU",

        "Ontario              " => "ON",

        "Prince Edward Island " => "PE",

        "Quebec               " => "QC",

        "Saskatchewan         " => "SK"

         );



				$txtCombo = "<select name=\"state[]\" id=\"state[]\"  >";

                  foreach ($state_codes as $k => $v){

				if($val	==	$v)

				  $txtCombo .= "<option selected=\"selected\" value=\"".$v."\"  >".$k."</option>";

                  else

				  $txtCombo .= "<option value=\"".$v."\" >".$k."</option>";

                    }

					return $txtCombo."</select> " ;

           

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	

	function sendOrdermail($orderId,$fullname,$interval,$productPrice,$totalprice,$spramship,$rafil,$spram,$offers,$shippingprice){

	

		  $shipprice	=	number_format($shippingprice,2);

		  $pprice	=	number_format($productPrice,2);

		  $tprice	=	number_format($totalprice,2);

		  $ordernumber	=	"5604".$orderId."";

		 	

		  $message	.=	'<table width="100%" border="0" cellpadding="0" cellspacing="10" style="border:5px solid #80d2f7;">

		<tr>

			<td valign="top"><a href="'.WEB_ROOT.'" target="_blank"><img src="'.WEB_ROOT.'images/logoemail.jpg" alt="" border="0px" /></a></td>

		</tr>

		<tr>

			<td valign="top" style="font-family:Arial, Helvetica, sans-serif;">

				<table width="100%" border="0" cellpadding="0" cellspacing="4" bgcolor="#FFFFFF" align="center">

					<tr>

						<td height="30" colspan="2" valign="top"><span style="font-weight:bold; font-size:24px; font-family:"Times New Roman", Times, serif; color:#7d83ba;">Thermogene Order information </span></td>

					</tr>

					<tr>

						<td width="77%" valign="top" style="font-size:12px; color:#000;">Dear '.$fullname.', Please take note of the information outlined below as it will help you with the setup of your order information. Thank you again.<br/>

<br/>Sincerely, <br/>

<span style="color:#000;">Thermogene Team</span></td>

					    <td width="23%" align="center" valign="top" style="font-size:12px;"><a href="'.WEB_ROOT.'/login.php"><img src="'.WEB_ROOT.'images/signin.gif" alt="" border="0px" /></a></td>

					</tr>

					<tr>

						<td colspan="2" valign="top" style="border-bottom:1px solid #cccccc;">&nbsp;</td>

					</tr>

					<tr>

						<td colspan="2" valign="top" style="padding:5px 0 5px 0;">

							<table width="100%" border="0" cellpadding="2" cellspacing="2">

								<tr>

									

								    <td width="11%" valign="top" style="font-size:14px; font-weight:normal; color:#333;">Username</td>

								    

								</tr>

								<tr>

									

								    <td width="11%" valign="top" style="font-size:12px; font-weight:normal; color:#000;"> <a href="#" style="color:#7d83ba; text-decoration:underline;">'.$spram[10].'</a> </td>

								    

								</tr>

								

								<tr>

									<td colspan="3" valign="top" style="font-size:14px; color:#000;">Ordering additional numbers</td>

								</tr>

								<tr>

									<td colspan="3" valign="top" style="font-size:12px; color:#000;">If you like to add additional local or toll free numbers to your account, please contact Thermogene Support 24/7/365 at 1-800-328-8428 or<a href="'.WEB_ROOT.'" style="color:#7d83ba; text-decoration:underline;"> online.</a> </td>

								</tr>

					    </table>						</td>

					</tr>

					<tr>

					  <td colspan="2" valign="top">

						<table width="100%" border="0" cellspacing="4" cellpadding="0">

                   <tr>

                   <td width="50%" align="left" valign="top" style="font-size:16px; font-weight:bold; color:#7d83ba;">Your information</td>

                   <td width="50%" align="left" valign="top" style="font-size:16px; font-weight:bold; color:#7d83ba;">Your order </td>

                   </tr>

                   <tr>

                     <td width="50%" align="left" valign="top" style="font-size:12px; font-weight:normal;">Billing Information</td>

                     

                   </tr>

                   <tr>

                     <td><table width="100%" border="0" cellspacing="2" cellpadding="2">

                       <tr>

                         <td width="22%" align="left" valign="middle" style="font-size:12px; color:#000;">First Name:</td>



                         <td width="78%" align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[1].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">Last Name:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[2].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">Address 1:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[3].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">Address 2:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[4].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">Country:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[8].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">City:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[6].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">State/Province:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[7].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">Postal Code:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[5].'</td>

                       </tr>

					    <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">Phone:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[9].'</td>

                       </tr>

					    <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;" nowrap="nowrap">Email Address:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spram[10].'</td>

                       </tr>

                     </table></td>

                     <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="3">

                       <tr>

                         <td colspan="3" align="left" valign="middle" style="font-size:12px; color:#000;">Plan</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" nowrap style="font-size:12px; color:#000;">Thermogene Product</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">&nbsp;</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">&nbsp;</td>

                       </tr>

                       <tr>

                         <td width="35%" align="left" valign="middle" nowrap style="font-size:12px; color:#000;">Product Price

                         :</td>

                         <td width="17%" align="left" valign="middle" style="font-size:12px; color:#000;">US&nbsp;$'.number_format($productPrice,2).'</td>

                         <td width="48%" align="left" valign="middle" style="font-size:12px; color:#000;">&nbsp;</td>

                       </tr>

                       <tr>

                         <td colspan="3">

						 <table width="65%" border="0" cellspacing="0" cellpadding="0" style="border-top:1px solid #cccccc;">

                         <tr>

                           <td></td>

                         </tr>

                       </table>		</td>

                       </tr>

                       <tr>

                         <td height="30" colspan="3" valign="bottom">Shipping &amp; handling: (s)</td>

                       </tr>

                       <tr>

                         <td width="35%" align="left" valign="middle" style="font-size:12px; color:#000;">&nbsp;</td>

                         <td width="17%" align="left" valign="middle" style="font-size:12px; color:#000;">US&nbsp;$'.number_format($shippingprice,2).'</td>

                         <td width="48%" align="left" valign="middle" style="font-size:12px; color:#000;">&nbsp;</td>

                       </tr>

                       

                       <tr>

                         <td colspan="3">

						 <table width="65%" border="0" cellspacing="0" cellpadding="0" style="border-top:1px solid #cccccc;">

                         <tr>

                           <td></td>

                         </tr>

                       </table>						 </td>

                       </tr>

                      

					   <tr>

                         <td width="35%" height="40" align="left" valign="middle" nowrap style="font-size:12px; color:#000;">Total Price <br/>

<span style="color:#999999; font-weight:normal;"></span></td>

                         <td align="left" colspan="2" valign="middle" style="font-size:12px; color:#000;">US $'.number_format($totalprice,2).'</td>

                       </tr>

                     </table></td>

                   </tr>

				   <tr>

					   <td align="left" valign="top"><table width="65%" border="0" cellspacing="0" cellpadding="0" style="border-top:1px solid #cccccc;">

                        

                       </table></td>

					   <td align="left" valign="top"><table width="65%" border="0" cellspacing="0" cellpadding="0" style="border-top:1px solid #cccccc;">

                         <tr>

                           <td></td>

                         </tr>

                       </table></td>

					 </tr>

					 </table>

</td>

					</tr>

					<tr>

						<td colspan="2" valign="top" style="font-size:12px;">

						 <table width="100%" border="0" cellspacing="4" cellpadding="0">

                   <tr>

                   <td width="50%" align="left" valign="top" style="font-size:16px; font-weight:bold; color:#7d83ba;">Shipping Address</td>

                  

                   </tr>

                   <tr>

                     <td><table width="100%" border="0" cellspacing="2" cellpadding="2">

                       <tr>

                         <td width="30%" align="left" valign="middle" style="font-size:12px; color:#000;">Complete Name:</td>

                         <td width="70%" align="left" valign="middle" style="font-size:12px; color:#000;">'.$spramship[7].'&nbsp;'.$spramship[8].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">Address: </td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spramship[1].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">Country:  </td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spramship[1].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">City:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spramship[5].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">State/Province:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spramship[4].'</td>

                       </tr>

                       <tr>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">Postal Code:</td>

                         <td align="left" valign="middle" style="font-size:12px; color:#000;">'.$spramship[6].'</td>

                       </tr>

                     </table></td>

                     <td></td>

                   </tr></table>

						</td>

					</tr>

					<tr>

						<td colspan="2" style="font-size:12px; padding-top:50px;">Regards,<br/>

						Thermogene Team.</td>

					</tr>

					<tr>

						<td colspan="2" valign="top" style="border-top:1px solid #cccccc; font-size:12px; color:#818181; padding:10px 0 10px 0;">&copy; 2009 <a href="'.WEB_ROOT.'" target="_blank" style="color:#7d83ba; text-decoration:none;">thermogene.com</a> All Rights Reserved. Help improve thermogene. <a href="'.WEB_ROOT.'/contact-us.php" style="color:#7d83ba; text-decoration:none;">Send us your feedback</a></td>

					</tr>

			  </table>

			</td>

		</tr>

</table>';

			

			$to = SHIPPING_EMAIL.','.$spram[10];

			

			$subject = 'Order Confirmation';

			

			$headers = "From: ThermoGene<noreply@noreply.com>\r\n";

			$headers .= "Reply-To: ". strip_tags($spram['10']) . "\r\n";

			$headers .= "MIME-Version: 1.0\r\n";

			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";



            mail($to, $subject, $message, $headers);

	return  $ordernumber;

	}







			function getUser($spram)

			{

			$strQury	= "select * from tbl_user where user_id ='".$spram."'";	

				$rs         =  mysql_query($strQury);

				return $rs;

			}



function addMasteDetail($arrnews)

	{

   	    $UserId	=  $arrnews[3];

		$CartId =  $arrnews[4];

		$dayedate	=	date('Y-m-d');			

		

		$q="select * from  tbl_user where UserID='".$UserId."'";

 		$rs1=mysql_query($q);

		$row1=mysql_fetch_array($rs1);

		$Username= $row1['FirstName']."".$row1['LastName'];	

		

		$qy_cart_master	=	"select * from temp_master where Cart_ID='".$CartId."'";

  	    $r_cart_master	=	mysql_fetch_array(mysql_query($qy_cart_master));

 	

	$qry_temp = "insert into order_master(Cust_ID,Order_Id,OrderDate,Status,Net_Amount,Cart_ID,OrderDesc,ExpiryDate)values('".$UserId."','".$arrnews[2]."','".$dayedate."','1','".$r_cart_master['Net_Amount']."','".$CartId."','".$r_cart_master['OrderDesc']."','".$Expiredate."')";

		mysql_query($qry_temp);

		$lastid	=	mysql_insert_id();

		

		$qy_cart		=	"select * from temp_order where UserID='".$UserId."'";

  		$r_cart 		=	mysql_query($qy_cart);

			

		  while($row_cart=mysql_fetch_array($r_cart))

			 {

			 	

			$price_one	=	$row_cart['Price'];

			

			if($row_cart['subscrip']=='3'){	$Expiredate	=	date('Y-m-d', strtotime( '+12 days' ) );	}

            if($row_cart['subscrip']=='6'){	$Expiredate	=	date('Y-m-d', strtotime( '+12 days' ) ); 	}

            if($row_cart['subscrip']=='12'){ $Expiredate	=	date('Y-m-d', strtotime( '+12 days' ) ); }

			if($row_cart['subscrip']=='1'){ $Expiredate	=	date('Y-m-d', strtotime( '+12 days' ) ); }

			if($row_cart['subscrip']=='0'){ $Expiredate	=	date('Y-m-d', strtotime( '+12 days' ) ); }

			//$totnetamout	=	$row_cart['Price'];

				

			$add_cart = $row_cart['Quantity'] * $price_one;

				

		$query_order="insert into order_detail(UserID,Cart_ID,VendorID,TrackID,ProductID,Description,ExpireDate,strdate,submonth,masterid,status, PTypeID)values('".$UserId."','".$row_cart['CartID']."','".$row_cart['VendorID']."','".$row_cart['TrackID']."','".$row_cart['Pid']."','".$row_cart['Product']."','".$Expiredate."','".$dayedate."','".$row_cart['subscrip']."','".$lastid."','1','".$row_cart['PType']."')";

 				mysql_query($query_order);



				// Add an Instance for Test Engine

				if($row_cart['PType']=="sp" || $row_cart['PType']=="both")

				{

					$order_id = mysql_insert_id();

					$time = date("Y-m-d H:i:s");

					$serial_number = md5($order_id.$time);

					$expiry = date('Y-m-d', strtotime("+12 days"));

					$engine_arr = mysql_fetch_array(mysql_query("select id from tbl_package_engine where package_id = '{$row_cart['Pid']}' and type='Simulator' and os = 'Win';"));

					$engine_id = $engine_arr['id'];

					if(!empty($engine_id))

					{

						$insert="insert into tbl_package_instance (id, engine_id, order_number, serial_number, mboard_number, instance_expiry) Values('', '$engine_id', '{$order_id}', '$serial_number', '', '$expiry');";

						$query=mysql_query($insert);

					}

				}

		}

						

			mysql_query("DELETE from temp_order where UserID='".$UserId."'");

			mysql_query("DELETE from temp_master where Cust_ID='".$UserId."'");

	

	}

		////////////////////// Demo Download Email/////////////////////////

function add_demo_email($usrdm)

	{

		

		$chked = "select demo_download_email from tbl_demo_download_email where demo_download_email='".$usrdm[2]."' and exam_code='".str_replace("-demo","",$usrdm[0])."'";

		$cked_query = mysql_query($chked);

			if(mysql_num_rows($cked_query) != "0")

			{				

					header('Location: ' . BASE_URL . $_POST['vname'].'-demo-'.$_POST['ecode'].'.html');

			}

			

			else{

			$todayDate	=	date("Y-m-d");

			$query_demo = "insert into tbl_demo_download_email(exam_code, exam_id, demo_download_email, demo_download_date)values('".str_replace("-demo","",$usrdm[0])."','".$usrdm[4]."','".$usrdm[2]."','".$todayDate."')";

				if(mysql_query($query_demo))

					{

						header('Location: ' . BASE_URL . $_POST['vname'].'-demo-'.$_POST['ecode'].'.html');

					}

				else

					{

					echo "sorry";

					}

	

			}



}

////////////////////// Exam Ruequest Email/////////////////////////

function examRequest_email($exmreq)

	{

		

		$chked = "select * from exam_request where examr_code='".$exmreq[1]."' and examr_email='".$exmreq[2]."'";

		$cked_query = mysql_query($chked);

			if(mysql_num_rows($cked_query) != "0")

			{

				header('Location: ' . BASE_URL . $exmreq[1].'-examRequest.html');

			}

			

			else{

			$todayDate	=	date("Y-m-d");

			$query_demo = "insert into exam_request(examr_code, examr_email,examr_date)values('".$exmreq[1]."','".$exmreq[2]."','".$todayDate."')";

				if(mysql_query($query_demo))

					{

						

						header('Location: ' . BASE_URL . $exmreq[1].'-examRequest.html');

						

						

					}

				else

					{

					echo "sorry";

					}

	

			}



}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}



?> 
